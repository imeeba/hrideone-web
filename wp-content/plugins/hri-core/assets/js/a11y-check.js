/**
 * Accessibility check in the editor (hri-core, no build step).
 *
 * Lists images without alt text and skipped heading levels in the pre-publish panel and in the
 * document sidebar. A warning only: publishing is never blocked. Texts come from hriA11yCheck
 * (wp_localize_script), so they follow the admin language.
 */
( function ( wp, t ) {
	if ( ! wp || ! wp.plugins || ! t ) {
		return;
	}
	var el = wp.element.createElement;
	var useSelect = wp.data.useSelect;
	var editor = wp.editor || {};
	var PrePublish = editor.PluginPrePublishPanel;
	var DocPanel = editor.PluginDocumentSettingPanel;
	var sprintf = wp.i18n.sprintf;

	function walk( blocks, out ) {
		blocks.forEach( function ( b ) {
			out.push( b );
			if ( b.innerBlocks && b.innerBlocks.length ) {
				walk( b.innerBlocks, out );
			}
		} );
		return out;
	}

	function findProblems( blocks ) {
		var all = walk( blocks, [] );
		var noAlt = [];
		var problems = [];
		var prev = 1;
		var h1 = 0;
		all.forEach( function ( b ) {
			var a = b.attributes || {};
			if ( ( b.name === 'core/image' || b.name === 'core/media-text' ) && ( a.url || a.mediaUrl ) && ! String( a.alt || a.mediaAlt || '' ).trim() ) {
				noAlt.push( b.clientId );
			}
			if ( b.name === 'core/heading' ) {
				var level = a.level || 2;
				if ( level === 1 ) {
					h1++;
				}
				if ( level > prev + 1 ) {
					problems.push( { text: sprintf( t.skip, level, prev ), id: b.clientId } );
				}
				prev = level;
			}
		} );
		if ( noAlt.length ) {
			problems.unshift( { text: sprintf( t.noAlt, noAlt.length ), id: noAlt[ 0 ] } );
		}
		if ( h1 > 1 ) {
			problems.push( { text: t.h1, id: null } );
		}
		return problems;
	}

	function Report() {
		var blocks = useSelect( function ( select ) {
			return select( 'core/block-editor' ).getBlocks();
		}, [] );
		var problems = findProblems( blocks );
		if ( ! problems.length ) {
			return el( 'p', null, '✓ ' + t.ok );
		}
		return el(
			'ul',
			{ style: { margin: 0, paddingLeft: '1.2em', listStyle: 'disc' } },
			problems.map( function ( p, i ) {
				return el(
					'li',
					{ key: i },
					p.id
						? el( 'button', {
								type: 'button',
								className: 'components-button is-link',
								title: t.clickHint,
								style: { textAlign: 'left', height: 'auto', whiteSpace: 'normal' },
								onClick: function () {
									wp.data.dispatch( 'core/block-editor' ).selectBlock( p.id );
								},
						  }, p.text )
						: p.text
				);
			} )
		);
	}

	wp.plugins.registerPlugin( 'hri-a11y-check', {
		render: function () {
			return el(
				wp.element.Fragment,
				null,
				PrePublish ? el( PrePublish, { title: t.title, initialOpen: true }, el( Report ) ) : null,
				DocPanel ? el( DocPanel, { name: 'hri-a11y-check', title: t.title }, el( Report ) ) : null
			);
		},
	} );
} )( window.wp, window.hriA11yCheck );
