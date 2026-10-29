/*
 * Cycles the colour scheme.
 * Logged-in users: saved to their preferences.
 * Visitors: saved in this browser and applied early by an inline head script (see Hooks.php).
 */
( function () {
	var KEY = 'knowislam-scheme';
	var ORDER = [ 'auto', 'light', 'dark', 'sepia' ];
	var ICONS = { auto: '◐', light: '☀', dark: '☾', sepia: '☕' };
	var isAnon = mw.user.isAnon();
	var root = document.documentElement;

	function store( value ) {
		if ( !isAnon ) {
			new mw.Api().saveOption( KEY, value );
			return;
		}
		try { localStorage.setItem( KEY, value ); } catch ( e ) { /* storage unavailable */ }
	}
	function current() {
		var forced = root.getAttribute( 'data-ki-scheme' );
		if ( forced && ORDER.indexOf( forced ) !== -1 ) {
			return forced;
		}
		for ( var i = 0; i < ORDER.length; i++ ) {
			if ( document.body.classList.contains( 'ki-scheme-' + ORDER[ i ] ) ) {
				return ORDER[ i ];
			}
		}
		return 'auto';
	}
	function apply( value ) {
		ORDER.forEach( function ( s ) { document.body.classList.remove( 'ki-scheme-' + s ); } );
		document.body.classList.add( 'ki-scheme-' + value );
		if ( isAnon ) {
			root.setAttribute( 'data-ki-scheme', value );
		} else {
			root.removeAttribute( 'data-ki-scheme' );
		}
		var btn = document.querySelector( '.ki-scheme-toggle' );
		if ( btn ) {
			btn.setAttribute( 'data-icon', ICONS[ value ] );
			btn.title = mw.msg( 'knowislam-toggle-scheme' ) + ': ' + value;
			btn.setAttribute( 'aria-label', btn.title );
		}
	}

	var button = document.querySelector( '.ki-scheme-toggle' );
	if ( button ) {
		button.hidden = false;
		apply( current() );
		button.addEventListener( 'click', function () {
			var next = ORDER[ ( ORDER.indexOf( current() ) + 1 ) % ORDER.length ];
			apply( next );
			store( next );
		} );
	}
}() );
