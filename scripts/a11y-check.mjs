#!/usr/bin/env node
/**
 * Automated WCAG 2.2 AA check (axe-core) of the main pages, desktop and mobile width.
 *
 *   npm i --no-save playwright axe-core      (Chromium: npx playwright install chromium)
 *   node scripts/a11y-check.mjs [--base http://127.0.0.1:8899/wp] [--pages /,/contact-us/] [--all]
 *
 * Exits 1 when a page has a "serious" or "critical" violation (--all: any violation).
 * axe finds roughly a third of WCAG problems; keyboard and screen-reader tests are still needed.
 */
import { chromium } from 'playwright';
import { createRequire } from 'node:module';
import { readFileSync } from 'node:fs';

const require = createRequire( import.meta.url );
const axeSource = readFileSync( require.resolve( 'axe-core/axe.min.js' ), 'utf8' );

const arg = ( name, fallback ) => {
	const i = process.argv.indexOf( name );
	return i > -1 ? process.argv[ i + 1 ] : fallback;
};
const base = arg( '--base', 'http://127.0.0.1:8899/wp' ).replace( /\/$/, '' );
const pages = arg( '--pages', '/,/in-house/,/public-training/,/courses/,/about/,/contact-us/,/accessibility/,/blog/,/?s=leader' ).split( ',' );
const strict = process.argv.includes( '--all' );
const tags = [ 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa', 'best-practice' ];

const browser = await chromium.launch();
let failed = 0;
for ( const width of [ 1280, 375 ] ) {
	const page = await browser.newPage( { viewport: { width, height: 900 } } );
	for ( const path of pages ) {
		const url = base + path;
		try {
			const res = await page.goto( url, { waitUntil: 'domcontentloaded', timeout: 90000 } );
			await page.waitForLoadState( 'load', { timeout: 15000 } ).catch( () => {} ); // Slow external images: check anyway.
			if ( ! res || res.status() >= 400 ) {
				console.log( `${ width } ${ path } SKIP (HTTP ${ res ? res.status() : '?' })` );
				continue;
			}
			await page.addScriptTag( { content: axeSource } );
			const violations = await page.evaluate( async ( t ) => ( await window.axe.run( document, { runOnly: { type: 'tag', values: t } } ) ).violations.map( ( v ) => ( {
				id: v.id, impact: v.impact, help: v.help, count: v.nodes.length, target: v.nodes[ 0 ].target.join( ' ' ),
			} ) ), tags );
			const bad = violations.filter( ( v ) => strict || [ 'serious', 'critical' ].includes( v.impact ) );
			failed += bad.length;
			console.log( `${ width } ${ path } ${ violations.length ? '' : 'OK' }` );
			for ( const v of violations ) {
				console.log( `   ${ bad.includes( v ) ? '✗' : '·' } [${ v.impact }] ${ v.id } ×${ v.count }: ${ v.help } — ${ v.target }` );
			}
		} catch ( e ) {
			failed++;
			console.log( `${ width } ${ path } ERROR ${ e.message.split( '\n' )[ 0 ] }` );
		}
	}
	await page.close();
}
await browser.close();
console.log( failed ? `\n${ failed } problem(s)` : '\nNo serious/critical problems' );
process.exit( failed ? 1 : 0 );
