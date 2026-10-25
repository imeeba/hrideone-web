#!/usr/bin/env node
/**
 * Local stand-in for what the real host does: Brotli/gzip for text, long cache for static files.
 * `php -S` sends everything raw, which makes Lighthouse over-count bytes ~5x.
 * Usage: node scripts/perf-proxy.mjs [listen-port=8898] [upstream=http://127.0.0.1:8899]
 */
import http from 'node:http';
import zlib from 'node:zlib';

const port = Number(process.argv[2] || 8898);
const up = new URL(process.argv[3] || 'http://127.0.0.1:8899');
const TEXT = /^(text\/|application\/(javascript|json|xml)|image\/svg)/;
const agent = new http.Agent({ keepAlive: true });

http
	.createServer((req, res) => {
		const headers = { ...req.headers, host: up.host, 'accept-encoding': 'identity' };
		const p = http.request({ host: up.hostname, port: up.port, path: req.url, method: req.method, headers, agent }, (r) => {
			const h = { ...r.headers };
			const type = String(h['content-type'] || '');
			if (/\/wp-(content|includes)\//.test(req.url) && !/\.php/.test(req.url) && r.statusCode === 200) {
				h['cache-control'] = 'public, max-age=31536000, immutable';
			}
			const ae = String(req.headers['accept-encoding'] || '');
			if (r.statusCode === 200 && TEXT.test(type)) {
				const chunks = [];
				r.on('data', (c) => chunks.push(c));
				r.on('end', () => {
					// The site's own URLs point at the upstream port; send them through this proxy too,
					// otherwise CSS, fonts and images would bypass compression and cache headers.
					const self = `127.0.0.1:${port}`;
					const raw = Buffer.from(Buffer.concat(chunks).toString('utf8').split(up.host).join(self).split(up.host.replace(':', '\\u003a')).join(self));
					let out = raw;
					if (/\bbr\b/.test(ae)) {
						out = zlib.brotliCompressSync(raw, { params: { [zlib.constants.BROTLI_PARAM_QUALITY]: 5 } });
						h['content-encoding'] = 'br';
					} else if (/\bgzip\b/.test(ae)) {
						out = zlib.gzipSync(raw);
						h['content-encoding'] = 'gzip';
					}
					h['content-length'] = out.length;
					h.vary = 'Accept-Encoding';
					res.writeHead(r.statusCode, h);
					res.end(out);
				});
			} else {
				res.writeHead(r.statusCode, h);
				r.pipe(res);
			}
		});
		p.on('error', () => { res.writeHead(502); res.end('bad gateway'); });
		req.pipe(p);
	})
	.listen(port, '127.0.0.1', () => console.log(`perf-proxy :${port} -> ${up.href}`));
