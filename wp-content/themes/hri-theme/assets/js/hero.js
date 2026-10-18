/**
 * Hero cover (Cover block, style "Hero") with a background video.
 * - Plays at most the first 10 seconds, in a loop (a shorter video loops as it is).
 * - Pause / play button (WCAG 2.2.2: moving content longer than 5 seconds needs one).
 * - Visitors who prefer reduced motion get the first frame, paused.
 */
( () => {
	const MAX_SECONDS = 10;
	const labels = ( window.hriL10n && window.hriL10n.hero ) || { pause: 'Pause video', play: 'Play video' };
	const icons = {
		pause: '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7 5h4v14H7zM13 5h4v14h-4z"/></svg>',
		play: '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>',
	};
	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.querySelectorAll( '.wp-block-cover.is-style-hero' ).forEach( ( cover ) => {
		const video = cover.querySelector( 'video.wp-block-cover__video-background' );
		if ( ! video || cover.querySelector( '.hri-hero__toggle' ) ) {
			return;
		}
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
				video.play().catch( () => {} );
			} else {
				video.pause();
			}
		} );
		video.addEventListener( 'play', sync );
		video.addEventListener( 'pause', sync );

		if ( reduceMotion ) {
			video.removeAttribute( 'autoplay' );
			video.pause();
		}
		sync();
		cover.appendChild( button );
	} );
} )();
