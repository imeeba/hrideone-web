/**
 * Lead form enhancement. Without this file the form still works as a normal POST (admin-post.php).
 * Posts the fields to admin-post.php (hri_ajax=1 → JSON) and shows the result in place.
 */
( () => {
	document.querySelectorAll( '[data-hri-lead]' ).forEach( ( form ) => {
		const status = form.querySelector( '[data-hri-lead-status]' );
		const button = form.querySelector( '[type="submit"]' );

		form.addEventListener( 'submit', async ( event ) => {
			if ( ! form.checkValidity() ) {
				return; // Let the browser show its own messages.
			}
			event.preventDefault();

			const label = button.textContent;
			button.disabled = true;
			button.textContent = form.dataset.sending;
			status.textContent = '';
			status.classList.remove( 'is-error' );

			try {
				const response = await fetch( form.dataset.endpoint, { method: 'POST', body: ( () => { const data = new FormData( form ); data.append( 'hri_ajax', '1' ); return data; } )(), headers: { Accept: 'application/json' } } );
				const result = await response.json().catch( () => ( {} ) );
				if ( ! response.ok || ! result.ok ) {
					throw new Error( result.message || form.dataset.failed );
				}
				const done = document.createElement( 'div' );
				done.className = 'hri-lead__done';
				done.setAttribute( 'role', 'status' );
				done.tabIndex = -1;
				const title = document.createElement( 'h3' );
				title.className = 'hri-lead__done-title';
				title.textContent = form.dataset.doneTitle;
				const text = document.createElement( 'p' );
				text.textContent = form.dataset.doneText;
				done.append( title, text );
				form.replaceWith( done );
				done.focus();
			} catch ( error ) {
				status.textContent = error.message || form.dataset.failed;
				status.classList.add( 'is-error' );
				button.disabled = false;
				button.textContent = label;
			}
		} );
	} );
} )();
