/**
 * Gallery block, style "Slider": the gallery is a horizontal scroll-snap strip (CSS, works
 * without JS). This adds previous / next buttons, dots, keyboard arrows and marks the slide in
 * the middle (is-active) and its neighbours (is-prev / is-next) for the 3D side effect.
 */
( () => {
	const labels = ( window.hriL10n && window.hriL10n.slider ) || { prev: 'Previous', next: 'Next', slide: 'Slide %1$s of %2$s' };
	const arrow = ( d ) => `<svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="${ d }"/></svg>`;
	const format = ( n, total ) => labels.slide.replace( '%1$s', n ).replace( '%2$s', total );

	document.querySelectorAll( '.wp-block-gallery.is-style-slider' ).forEach( ( track, index ) => {
		const slides = Array.from( track.querySelectorAll( ':scope > figure.wp-block-image' ) );
		if ( slides.length < 2 || track.dataset.hriSlider ) {
			return;
		}
		track.dataset.hriSlider = '1';
		track.id = track.id || `hri-slider-${ index + 1 }`;
		track.setAttribute( 'tabindex', '0' );
		track.setAttribute( 'role', 'region' );
		track.setAttribute( 'aria-roledescription', 'carousel' );
		const heading = track.closest( '.hri-section' )?.querySelector( 'h2' );
		if ( heading ) {
			heading.id = heading.id || `${ track.id }-title`;
			track.setAttribute( 'aria-labelledby', heading.id );
		}
		slides.forEach( ( slide, i ) => {
			slide.setAttribute( 'role', 'group' );
			slide.setAttribute( 'aria-roledescription', 'slide' );
			slide.setAttribute( 'aria-label', format( i + 1, slides.length ) );
		} );

		const controls = document.createElement( 'div' );
		controls.className = 'hri-slider__controls';
		const button = ( cls, label, d ) => {
			const b = document.createElement( 'button' );
			b.type = 'button';
			b.className = `hri-slider__arrow ${ cls }`;
			b.setAttribute( 'aria-label', label );
			b.setAttribute( 'aria-controls', track.id );
			b.innerHTML = arrow( d );
			return b;
		};
		const prev = button( 'hri-slider__arrow--prev', labels.prev, 'M15 5l-7 7 7 7' );
		const next = button( 'hri-slider__arrow--next', labels.next, 'M9 5l7 7-7 7' );
		const dots = document.createElement( 'div' );
		dots.className = 'hri-slider__dots';
		const dotButtons = slides.map( ( _, i ) => {
			const d = document.createElement( 'button' );
			d.type = 'button';
			d.className = 'hri-slider__dot';
			d.setAttribute( 'aria-label', format( i + 1, slides.length ) );
			d.setAttribute( 'aria-controls', track.id );
			d.addEventListener( 'click', () => go( i ) );
			dots.appendChild( d );
			return d;
		} );
		controls.append( prev, dots, next );
		track.after( controls );

		let active = -1;
		const center = ( slide ) => slide.offsetLeft + slide.offsetWidth / 2;
		const go = ( i ) => {
			const target = slides[ ( i + slides.length ) % slides.length ];
			track.scrollTo( { left: center( target ) - track.clientWidth / 2, behavior: 'smooth' } );
		};
		const update = () => {
			const middle = track.scrollLeft + track.clientWidth / 2;
			let nearest = 0;
			slides.forEach( ( slide, i ) => {
				if ( Math.abs( center( slide ) - middle ) < Math.abs( center( slides[ nearest ] ) - middle ) ) {
					nearest = i;
				}
			} );
			if ( nearest === active ) {
				return;
			}
			active = nearest;
			slides.forEach( ( slide, i ) => {
				slide.classList.toggle( 'is-active', i === active );
				slide.classList.toggle( 'is-prev', i < active );
				slide.classList.toggle( 'is-next', i > active );
			} );
			dotButtons.forEach( ( d, i ) => d.setAttribute( 'aria-current', i === active ? 'true' : 'false' ) );
		};

		prev.addEventListener( 'click', () => go( active - 1 ) );
		next.addEventListener( 'click', () => go( active + 1 ) );
		track.addEventListener( 'keydown', ( e ) => {
			if ( 'ArrowLeft' === e.key || 'ArrowRight' === e.key ) {
				e.preventDefault();
				go( active + ( 'ArrowRight' === e.key ? 1 : -1 ) );
			}
		} );
		let frame = 0;
		track.addEventListener( 'scroll', () => {
			cancelAnimationFrame( frame );
			frame = requestAnimationFrame( update );
		}, { passive: true } );
		window.addEventListener( 'resize', () => {
			active = -1;
			update();
		} );
		// Three or more photos: start on the second one, so there is a photo on each side.
		if ( slides.length > 2 ) {
			track.scrollLeft = center( slides[ 1 ] ) - track.clientWidth / 2;
		}
		update();
	} );
} )();
