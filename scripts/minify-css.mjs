#!/usr/bin/env node
/**
 * Writes a .min.css next to every front-end stylesheet. The originals stay the source of truth;
 * PHP loads the .min.css when it exists (unless SCRIPT_DEBUG). Shared hosting has no build step,
 * so the .min.css files are committed. Re-run after editing any CSS:  node scripts/minify-css.mjs
 * Uses esbuild through npx (dev machine only, nothing is added to the repo).
 */
import { execFileSync } from 'node:child_process';
import { readdirSync, statSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const dirs = ['wp-content/themes/hri-theme/assets/css', 'wp-content/plugins/hri-core/assets'];
let before = 0;
let after = 0;
for (const d of dirs) {
	for (const f of readdirSync(join(root, d)).filter((n) => n.endsWith('.css') && !n.endsWith('.min.css'))) {
		const src = join(root, d, f);
		const out = src.replace(/\.css$/, '.min.css');
		execFileSync('npx', ['--yes', 'esbuild@0.24.2', src, '--minify', `--outfile=${out}`, '--log-level=warning'], { stdio: 'inherit' });
		before += statSync(src).size;
		after += statSync(out).size;
		console.log(`${f.padEnd(24)} ${String(statSync(src).size).padStart(7)} -> ${String(statSync(out).size).padStart(7)}`);
	}
}
console.log(`total ${before} -> ${after} (${Math.round(100 - (after * 100) / before)}% smaller)`);
