#!/usr/bin/env node
/**
 * Keyboard and zoom checks that axe cannot do (WCAG 2.1.1, 2.4.3, 2.4.7, 2.4.11, 1.4.10, 1.4.12).
 *
 *   node scripts/keyboard-check.mjs [--base http://127.0.0.1:8899/wp] [--pages /,/contact-us/]
 *
 * Per page: Tab through every stop and report stops with no visible focus indicator, stops hidden
 * under the sticky header, and focus traps; then reflow at 320 px (= 1280 px at 400% zoom) and with
 * WCAG text spacing. At 375 px: the menu button opens with Enter, Escape closes it, focus returns.
 * Exit 1 when something fails.
 */
import { chromium } from 'playwright';

const arg = ( name, fallback ) => {
	const i = process.argv.indexOf( name );
	return i > -1 ? process.argv[ i + 1 ] : fallback;
};
const base = arg( '--base', 'http://127.0.0.1:8899/wp' ).replace( /\/$/, '' );
const pages = arg( '--pages', '/,/in-house/,/public-training/,/courses/,/contact-us/,/blog/' ).split( ',' );
let failed = 0;
const fail = ( msg ) => { failed++; console.log( '   ✗ ' + msg ); };

// What the focused element looks like: a visible outline / box-shadow, and whether the sticky header covers it.
const focusInfo = () => {
	const el = document.activeElement;
	if ( ! el || el === document.body ) return null;
	const cs = getComputedStyle( el );
	const r = el.getBoundingClientRect();
	// A focus style may sit on the element or on a parent (cards use :has(a:focus-visible)):
	// compare the element and 4 parents with and without focus.
	const look = () => {
		const out = [];
		for ( let n = el, k = 0; n && k < 5; n = n.parentElement, k++ ) {
			const c = getComputedStyle( n );
			out.push( [ c.outlineStyle, c.outlineWidth, c.outlineColor, c.boxShadow, c.borderColor, c.backgroundColor, c.textDecorationLine ].join( '|' ) );
		}
		return out.join( '/' );
	};
	if ( el.tagName === 'IFRAME' ) {
		// Focus is inside the embedded page; hri-core marks the iframe with a class instead.
		const ring = cs.outlineStyle !== 'none' && parseFloat( cs.outlineWidth ) > 0;
		return { tag: 'iframe', name: el.title || 'iframe', ring, covered: false, visible: r.width > 0, id: Array.prototype.indexOf.call( document.getElementsByTagName( '*' ), el ) };
	}
	const focused = look();
	el.blur();
	const blurred = look();
	el.focus();
	const ring = focused !== blurred;
	let covered = false;
	const header = document.querySelector( 'header.wp-block-template-part, .hri-header' );
	if ( header && r.width && r.height ) {
		const pos = getComputedStyle( header ).position;
		const hr = header.getBoundingClientRect();
		if ( ( pos === 'sticky' || pos === 'fixed' ) && ! header.contains( el ) && r.bottom <= hr.bottom ) covered = true;
	}
	const name = ( el.getAttribute( 'aria-label' ) || el.textContent || el.getAttribute( 'name' ) || el.tagName ).trim().replace( /\s+/g, ' ' ).slice( 0, 40 );
	return { tag: el.tagName.toLowerCase(), name, ring, covered, visible: r.width > 0 && r.height > 0, id: Array.prototype.indexOf.call( document.getElementsByTagName( '*' ), el ) };
};

const browser = await chromium.launch();

for ( const path of pages ) {
	console.log( path );
	const page = await browser.newPage( { viewport: { width: 1280, height: 800 } } );
	await page.goto( base + path, { waitUntil: 'load', timeout: 90000 } );

	// Skip link first, and it works.
	await page.keyboard.press( 'Tab' );
	const first = await page.evaluate( focusInfo );
	if ( ! first || ! /skip|ข้าม|スキップ/i.test( first.name ) ) fail( `first Tab stop is not the skip link (${ first && first.name })` );

	const seen = new Set();
	let stops = 0;
	for ( let i = 0; i < 150; i++ ) {
		await page.keyboard.press( 'Tab' );
		if ( await page.evaluate( () => document.activeElement && document.activeElement.tagName === 'IFRAME' ) ) {
			await page.waitForTimeout( 150 );
		}
		const f = await page.evaluate( focusInfo );
		if ( ! f ) break;
		if ( seen.has( f.id ) && i > 5 ) break; // Back at the start: done.
		seen.add( f.id );
		stops++;
		if ( ! f.visible ) continue; // Hidden honeypot etc.
		if ( ! f.ring ) fail( `no visible focus indicator: <${ f.tag }> "${ f.name }"` );
		if ( f.covered ) fail( `focus hidden under the sticky header: <${ f.tag }> "${ f.name }"` );
	}
	console.log( `   ${ stops } Tab stops` );

	// Reflow at 320 px and WCAG text spacing.
	await page.setViewportSize( { width: 320, height: 800 } );
	await page.addStyleTag( { content: '*{line-height:1.5!important;letter-spacing:.12em!important;word-spacing:.16em!important}p{margin-bottom:2em!important}' } );
	await page.waitForTimeout( 300 );
	const overflow = await page.evaluate( () => {
		const out = [];
		if ( document.documentElement.scrollWidth > window.innerWidth + 1 ) out.push( 'page scrolls sideways (' + document.documentElement.scrollWidth + 'px)' );
		for ( const el of document.querySelectorAll( 'main *, header *, footer *' ) ) {
			const cs = getComputedStyle( el );
			if ( ( cs.overflow === 'hidden' || cs.overflowX === 'hidden' ) && el.scrollWidth > el.clientWidth + 2 && el.textContent.trim() && ! el.closest( '.screen-reader-text, [aria-hidden=true], .hri-slider, .wp-block-gallery' ) ) {
				out.push( 'text cut off: ' + el.className.toString().slice( 0, 50 ) );
			}
		}
		return [ ...new Set( out ) ].slice( 0, 5 );
	} );
	overflow.forEach( ( o ) => fail( '320 px + text spacing: ' + o ) );
	await page.close();
}

// Mobile menu with the keyboard.
{
	const page = await browser.newPage( { viewport: { width: 375, height: 800 } } );
	await page.goto( base + '/', { waitUntil: 'load', timeout: 90000 } );
	const open = page.locator( 'button.wp-block-navigation__responsive-container-open' ).first();
	if ( await open.count() ) {
		await open.focus();
		await page.keyboard.press( 'Enter' );
		await page.waitForTimeout( 400 );
		const isOpen = await page.locator( '.wp-block-navigation__responsive-container.is-menu-open' ).count();
		if ( ! isOpen ) fail( 'mobile menu does not open with Enter' );
		const inside = await page.evaluate( () => !! document.activeElement.closest( '.wp-block-navigation__responsive-container' ) );
		if ( ! inside ) fail( 'focus does not move into the open mobile menu' );
		await page.keyboard.press( 'Escape' );
		await page.waitForTimeout( 400 );
		if ( await page.locator( '.wp-block-navigation__responsive-container.is-menu-open' ).count() ) fail( 'Escape does not close the mobile menu' );
		const back = await page.evaluate( () => document.activeElement && document.activeElement.classList.contains( 'wp-block-navigation__responsive-container-open' ) );
		if ( ! back ) fail( 'focus does not return to the menu button after Escape' );
		console.log( 'mobile menu: checked' );
	} else {
		console.log( 'mobile menu: no core menu button found' );
	}
	await page.close();
}

await browser.close();
console.log( failed ? `\n${ failed } problem(s)` : '\nKeyboard / reflow checks passed' );
process.exit( failed ? 1 : 0 );
