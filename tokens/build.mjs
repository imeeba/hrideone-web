#!/usr/bin/env node
/**
 * Build theme.json from tokens/tokens.json + tokens/theme.base.json.
 * Usage: node tokens/build.mjs
 */
import { readFileSync, writeFileSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const tokens = JSON.parse(readFileSync(resolve(here, 'tokens.json'), 'utf8'));
const base = JSON.parse(readFileSync(resolve(here, 'theme.base.json'), 'utf8'));
const out = resolve(here, '../wp-content/themes/hri-theme/theme.json');

const prim = tokens.color.primitive;
const ref = (path) => {
	const [group, step] = path.split('.');
	const v = prim[group]?.[step];
	if (!v) throw new Error(`Unknown color reference: ${path}`);
	return v;
};

const RANGES = {
	thai: 'U+02D7, U+0303, U+0331, U+0E01-0E5B, U+200C-200D, U+25CC',
	latin: 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD',
	'latin-ext': 'U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF',
};

const kanitFaces = [];
for (const w of ['300', '400', '500', '600', '700']) {
	for (const subset of ['thai', 'latin', 'latin-ext']) {
		kanitFaces.push({
			fontFamily: 'Kanit',
			fontStyle: 'normal',
			fontWeight: w,
			fontDisplay: 'swap',
			src: [`file:./assets/fonts/kanit/kanit-${subset}-${w}-normal.woff2`],
			unicodeRange: RANGES[subset],
		});
	}
}
const montserratFaces = ['latin', 'latin-ext'].map((subset) => ({
	fontFamily: 'Montserrat Variable',
	fontStyle: 'normal',
	fontWeight: '100 900',
	fontDisplay: 'swap',
	src: [`file:./assets/fonts/montserrat/montserrat-${subset}-wght-normal.woff2`],
	unicodeRange: RANGES[subset],
}));
const faces = { body: kanitFaces, display: montserratFaces };

const theme = structuredClone(base);
const s = (theme.settings ??= {});

s.color = {
	...(s.color ?? {}),
	palette: tokens.color.semantic.map(({ slug, name, ref: r }) => ({ slug, name, color: ref(r) })),
	gradients: tokens.color.gradient.map(({ slug, name, stops, angle }) => ({
		slug,
		name,
		gradient: `linear-gradient(${angle}deg, ${ref(stops[0])} 0%, ${ref(stops[1])} 100%)`,
	})),
};

s.typography = {
	...(s.typography ?? {}),
	fontFamilies: tokens.font.family.map(({ slug, name, stack }) => ({
		slug,
		name,
		fontFamily: stack,
		...(faces[slug] ? { fontFace: faces[slug] } : {}),
	})),
	fontSizes: tokens.font.size.map(({ slug, name, min, max }) => ({
		slug,
		name,
		size: max,
		fluid: min === max ? false : { min, max },
	})),
};

s.spacing = {
	...(s.spacing ?? {}),
	spacingSizes: tokens.spacing.map(({ slug, name, size }) => ({ slug, name, size })),
};

s.shadow = { ...(s.shadow ?? {}), presets: tokens.shadow };

s.layout = { contentSize: tokens.layout.content, wideSize: tokens.layout.wide };

s.custom = {
	...(s.custom ?? {}),
	color: prim,
	radius: tokens.radius,
	lineHeight: tokens.font.lineHeight,
	fontWeight: tokens.font.weight,
	ratio: tokens.ratio,
	tap: tokens.tap,
	motion: tokens.motion,
	gutter: tokens.layout.gutter,
};

writeFileSync(out, JSON.stringify(theme, null, '\t') + '\n');
console.log(`theme.json written: ${s.color.palette.length} colors, ${s.typography.fontSizes.length} font sizes, ${s.spacing.spacingSizes.length} spacing steps`);
