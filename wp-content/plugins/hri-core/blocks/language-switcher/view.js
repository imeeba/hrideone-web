/* Close the language dropdown on outside click, Escape, or choosing a link. */
( () => {
	const menus = document.querySelectorAll( '.hri-lang__details' );
	if ( ! menus.length ) {
		return;
	}

	const closeAll = ( except ) => {
		menus.forEach( ( menu ) => {
			if ( menu !== except ) {
				menu.open = false;
			}
		} );
	};

	document.addEventListener( 'click', ( event ) => closeAll( event.target.closest( '.hri-lang__details' ) ) );
	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' ) {
			const open = [ ...menus ].find( ( menu ) => menu.open );
			if ( open ) {
				open.open = false;
				open.querySelector( 'summary' )?.focus();
			}
		}
	} );
} )();
