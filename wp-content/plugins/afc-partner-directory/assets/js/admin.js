/**
 * Partner logo Media Library integration (wp-admin).
 */
( function ( $ ) {
	'use strict';

	$( function () {
		var frame;
		var $input = $( '#afc_partner_logo_id' );
		var $preview = $( '#afc-partner-logo-preview' );
		var $remove = $( '#afc-partner-remove-logo' );

		if ( ! $input.length ) {
			return;
		}

		$( '#afc-partner-select-logo' ).on( 'click', function ( event ) {
			event.preventDefault();

			if ( frame ) {
				frame.open();
				return;
			}

			frame = wp.media( {
				title: afcPartnerAdmin.selectLogoTitle,
				button: { text: afcPartnerAdmin.selectLogoButton },
				library: { type: 'image' },
				multiple: false,
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				$input.val( attachment.id );
				$preview.attr( 'src', attachment.url ).show();
				$remove.show();
			} );

			frame.open();
		} );

		$remove.on( 'click', function ( event ) {
			event.preventDefault();
			$input.val( '0' );
			$preview.hide().attr( 'src', '' );
			$remove.hide();
		} );
	} );
}( jQuery ) );
