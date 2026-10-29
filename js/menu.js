/* Closes the navigation and account panels on outside click or Escape. */
( function () {
	var menus = Array.prototype.slice.call( document.querySelectorAll( '.ki-menu' ) );
	if ( !menus.length ) {
		return;
	}
	menus.forEach( function ( menu ) {
		menu.addEventListener( 'toggle', function () {
			if ( menu.open ) {
				menus.forEach( function ( other ) {
					if ( other !== menu ) {
						other.open = false;
					}
				} );
			}
		} );
	} );
	document.addEventListener( 'click', function ( e ) {
		menus.forEach( function ( menu ) {
			if ( menu.open && !menu.contains( e.target ) ) {
				menu.open = false;
			}
		} );
	} );
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key !== 'Escape' ) {
			return;
		}
		menus.forEach( function ( menu ) {
			if ( menu.open ) {
				menu.open = false;
				menu.querySelector( 'summary' ).focus();
			}
		} );
	} );
}() );
