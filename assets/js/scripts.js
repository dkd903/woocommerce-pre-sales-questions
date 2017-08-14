( function( $ ) {
	"use strict";

	$( '#wcpsq_form' ).on( 'submit', function( e ) {
		e.preventDefault();
		var $form = $( this ),
			$nonceElem = $form.find( '#psq_nonce' ),
			formData = $form.serialize();
			console.log(formData);
		$.ajax( {
			type: "POST",
			url: $form.attr( 'action' ),
			data: formData,
			dataType: "json"
		} ).done( function( resp ) {
    		console.log( resp );
  		} ).error( function( resp ) {
    		console.log( resp );
  		} );
	} );

} )( jQuery );