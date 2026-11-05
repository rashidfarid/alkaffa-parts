/**
 * Static site part search: /shop/?q=... filters the parts in the browser.
 * An exact part number goes straight to that product.
 */
( function () {
	'use strict';

	var query = ( new URLSearchParams( window.location.search ).get( 'q' ) || '' ).trim();
	var cards = document.querySelectorAll( '.site-main--shop ul.products li.product' );
	if ( ! query || ! cards.length ) {
		return;
	}

	Array.prototype.forEach.call( document.querySelectorAll( 'input[name="q"]' ), function ( input ) {
		input.value = query;
	} );

	var needle = query.toLowerCase();
	var words = needle.split( /\s+/ );
	var matches = 0;
	var exact = null;
	Array.prototype.forEach.call( cards, function ( card ) {
		var text = card.getAttribute( 'data-search' );
		var found = words.every( function ( word ) {
			return text.indexOf( word ) !== -1;
		} );
		card.hidden = ! found;
		if ( found ) {
			matches++;
		}
		if ( card.getAttribute( 'data-sku' ) === needle ) {
			exact = card.getAttribute( 'data-url' );
		}
	} );

	if ( exact ) {
		window.location.replace( exact );
		return;
	}

	var status = document.getElementById( 'search-status' );
	if ( status ) {
		status.hidden = false;
		status.textContent = matches + ( 1 === matches ? ' part' : ' parts' ) + ' found for “' + query + '”';
	}
	var empty = document.getElementById( 'search-empty' );
	if ( empty ) {
		empty.hidden = matches > 0;
	}
}() );
