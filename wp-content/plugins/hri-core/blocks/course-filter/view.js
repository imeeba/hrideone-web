/**
 * Course filter enhancement. Without this file the form still works as a normal GET form.
 * Fetches the same URL with the chosen filters and swaps only the results area.
 */
( () => {
	const root = document.querySelector( '[data-hri-courses]' );
	const form = root?.querySelector( '[data-hri-filter]' );
	if ( ! root || ! form ) {
		return;
	}

	root.classList.add( 'is-enhanced' );

	// The filter panel starts open (no-JS default); fold it on small screens.
	const panel = form.querySelector( '.hri-filter__panel' );
	const narrow = window.matchMedia( '(max-width: 899px)' );
	if ( panel && narrow.matches ) {
		panel.open = false;
	}

	let timer;
	let controller;

	const currentTarget = () => {
		const params = new URLSearchParams();
		for ( const [ key, value ] of new FormData( form ) ) {
			if ( value !== '' ) {
				params.append( key, value );
			}
		}
		const query = params.toString();
		return new URL( form.getAttribute( 'action' ), location.href ).pathname + ( query ? '?' + query : '' );
	};

	const load = async ( target, push = true ) => {
		controller?.abort();
		controller = new AbortController();
		root.classList.add( 'is-loading' );

		try {
			const response = await fetch( target, { signal: controller.signal, headers: { 'X-Requested-With': 'fetch' } } );
			if ( ! response.ok ) {
				throw new Error( 'bad response' );
			}
			const doc = new DOMParser().parseFromString( await response.text(), 'text/html' );
			const fresh = doc.querySelector( '[data-hri-results]' );
			if ( ! fresh ) {
				throw new Error( 'no results' );
			}
			root.querySelector( '[data-hri-results]' ).replaceWith( fresh );
			if ( push ) {
				history.pushState( {}, '', target );
			}
		} catch ( error ) {
			if ( error.name !== 'AbortError' ) {
				location.href = target; // Fall back to a normal page load.
			}
			return;
		} finally {
			root.classList.remove( 'is-loading' );
		}
	};

	form.addEventListener( 'change', ( event ) => {
		if ( event.target.name !== 'q' ) {
			load( currentTarget() );
		}
	} );

	form.addEventListener( 'input', ( event ) => {
		if ( event.target.name === 'q' ) {
			clearTimeout( timer );
			timer = setTimeout( () => load( currentTarget() ), 350 );
		}
	} );

	form.addEventListener( 'submit', ( event ) => {
		event.preventDefault();
		clearTimeout( timer );
		load( currentTarget() );
	} );

	root.addEventListener( 'click', ( event ) => {
		const reset = event.target.closest( '[data-hri-reset]' );
		const page = event.target.closest( '.hri-pagination a' );

		if ( reset ) {
			event.preventDefault();
			for ( const element of form.elements ) {
				if ( element.type === 'checkbox' ) {
					element.checked = false;
				} else if ( element.name === 'q' ) {
					element.value = '';
				}
			}
			load( currentTarget() );
		} else if ( page ) {
			event.preventDefault();
			load( page.getAttribute( 'href' ) );
			root.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		}
	} );

	window.addEventListener( 'popstate', () => {
		const params = new URLSearchParams( location.search );
		for ( const element of form.elements ) {
			if ( element.type === 'checkbox' ) {
				element.checked = params.getAll( element.name ).includes( element.value );
			} else if ( element.name === 'q' ) {
				element.value = params.get( 'q' ) || '';
			}
		}
		load( location.pathname + location.search, false );
	} );
} )();
