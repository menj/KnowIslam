/* Search overlay for small screens, where the header search field is hidden. */
( function () {
	var open = document.querySelector( '.ki-search-open' );
	var overlay = document.getElementById( 'ki-search-overlay' );
	if ( !open || !overlay ) {
		return;
	}
	var close = overlay.querySelector( '.ki-search-close' );
	var field = overlay.querySelector( 'input[type="search"]' );
	var lastFocus = null;

	open.hidden = false;

	function hide() {
		overlay.hidden = true;
		document.documentElement.classList.remove( 'ki-overlay-open' );
		open.setAttribute( 'aria-expanded', 'false' );
		if ( lastFocus && typeof lastFocus.focus === 'function' ) {
			lastFocus.focus();
		}
	}
	function show() {
		lastFocus = document.activeElement;
		overlay.hidden = false;
		document.documentElement.classList.add( 'ki-overlay-open' );
		open.setAttribute( 'aria-expanded', 'true' );
		field.focus();
	}

	open.addEventListener( 'click', show );
	close.addEventListener( 'click', hide );
	overlay.addEventListener( 'click', function ( e ) {
		if ( e.target === overlay ) {
			hide();
		}
	} );
	document.addEventListener( 'keydown', function ( e ) {
		if ( overlay.hidden ) {
			return;
		}
		if ( e.key === 'Escape' ) {
			e.preventDefault();
			hide();
			return;
		}
		if ( e.key === 'Tab' ) {
			var focusable = Array.prototype.slice.call(
				overlay.querySelectorAll( 'button:not([disabled]), input:not([disabled]):not([type="hidden"])' )
			);
			var first = focusable[ 0 ];
			var last = focusable[ focusable.length - 1 ];
			if ( e.shiftKey && document.activeElement === first ) {
				e.preventDefault();
				last.focus();
			} else if ( !e.shiftKey && document.activeElement === last ) {
				e.preventDefault();
				first.focus();
			}
		}
	} );
}() );
