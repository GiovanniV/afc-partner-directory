/**
 * AFC Partner Directory — application shell (mobile navigation).
 */
( function () {
	'use strict';

	var header = document.querySelector( '.afc-shell-header' );
	if ( ! header ) {
		return;
	}

	var toggle = header.querySelector( '.afc-shell-header__toggle' );
	var nav = header.querySelector( '#afc-shell-nav' );
	if ( ! toggle || ! nav ) {
		return;
	}

	var mobileQuery = window.matchMedia( '(max-width: 768px)' );

	function setNavOpen( open ) {
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		header.classList.toggle( 'afc-shell-header--open', open );
		document.body.classList.toggle( 'afc-shell-nav-open', open );
	}

	function closeNav() {
		setNavOpen( false );
	}

	toggle.addEventListener( 'click', function () {
		var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
		setNavOpen( ! expanded );
	} );

	nav.querySelectorAll( 'a' ).forEach( function ( link ) {
		link.addEventListener( 'click', closeNav );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' ) {
			closeNav();
		}
	} );

	function onViewportChange() {
		if ( ! mobileQuery.matches ) {
			closeNav();
		}
	}

	if ( typeof mobileQuery.addEventListener === 'function' ) {
		mobileQuery.addEventListener( 'change', onViewportChange );
	} else if ( typeof mobileQuery.addListener === 'function' ) {
		mobileQuery.addListener( onViewportChange );
	}

	window.addEventListener( 'resize', onViewportChange );
}() );
