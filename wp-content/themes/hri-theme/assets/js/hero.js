/**
 * Hero cover (Cover block, style "Hero") with a background video.
 * - Plays at most the first 10 seconds, in a loop (a shorter video loops as it is).
 * - Pause / play button (WCAG 2.2.2: moving content longer than 5 seconds needs one).
 * - Visitors who prefer reduced motion get the first frame, paused.
 * - The file is not downloaded during the first paint: PHP moves src to data-hri-src, and it is set here after
 *   the page has loaded, only on screens >= 782px and not when the visitor asked to save data or reduce motion.
 *   Then the play button is the only way to start it (and to download it).
 */
( () => {
	const MAX_SECONDS = 10;
	const labels = ( window.hriL10n && window.hriL10n.hero ) || { pause: 'Pause video', play: 'Play video' };
	const icons = ( window.hriL10n && window.hriL10n.hero && window.hriL10n.hero.icons ) || {
		pause: '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg>',
		play: '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>',
	};
	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const saveData = !! ( navigator.connection && navigator.connection.saveData );
	const wide = window.matchMedia( '(min-width: 782px)' );

	document.querySelectorAll( '.wp-block-cover.is-style-hero' ).forEach( ( cover ) => {
		const video = cover.querySelector( 'video.wp-block-cover__video-background' );
		if ( ! video || cover.querySelector( '.hri-hero__toggle' ) ) {
			return;
		}
		// Small screens: no video and no pause button (nothing moves, nothing to download).
		if ( ! wide.matches ) {
			video.remove();
			return;
		}
		const load = () => {
			if ( ! video.getAttribute( 'src' ) && video.dataset.hriSrc ) {
				video.src = video.dataset.hriSrc;
			}
			return video.play();
		};
		video.muted = true;
		video.playsInline = true;
		video.setAttribute( 'aria-hidden', 'true' );

		video.addEventListener( 'timeupdate', () => {
			if ( video.currentTime >= MAX_SECONDS ) {
				video.currentTime = 0;
			}
		} );

		const button = document.createElement( 'button' );
		button.type = 'button';
		button.className = 'hri-hero__toggle';
		const sync = () => {
			const paused = video.paused;
			button.innerHTML = paused ? icons.play : icons.pause;
			button.setAttribute( 'aria-label', paused ? labels.play : labels.pause );
		};
		button.addEventListener( 'click', () => {
			if ( video.paused ) {
				load().catch( () => {} );
			} else {
				video.pause();
			}
		} );
		video.addEventListener( 'play', sync );
		video.addEventListener( 'pause', sync );

		sync();
		cover.appendChild( button );
		if ( ! reduceMotion && ! saveData ) {
			// After load, when the browser is idle, so the video never delays the first paint or the LCP.
			const start = () => ( 'requestIdleCallback' in window ? window.requestIdleCallback( () => load().catch( () => {} ), { timeout: 3000 } ) : setTimeout( () => load().catch( () => {} ), 1500 ) );
			if ( document.readyState === 'complete' ) {
				start();
			} else {
				window.addEventListener( 'load', start, { once: true } );
			}
		}
	} );
} )();
