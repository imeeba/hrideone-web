/* Search dropdown: focus the field when it opens; close on Escape or a click outside. */
( () => {
	const menus = document.querySelectorAll( '.hri-sf__details' );
	if ( ! menus.length ) {
		return;
	}

	menus.forEach( ( menu ) => {
		menu.addEventListener( 'toggle', () => {
			if ( menu.open ) {
				menu.querySelector( '.hri-sf__input' )?.focus();
			}
		} );
	} );

	document.addEventListener( 'click', ( event ) => {
		const inside = event.target.closest( '.hri-sf__details' );
		menus.forEach( ( menu ) => {
			if ( menu !== inside ) {
				menu.open = false;
			}
		} );
	} );

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
