/**
 * Al Kaffa Parts: menu, search toggle, scroll animations, counters.
 */
( function () {
	'use strict';

	var root = document.documentElement;
	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	root.classList.add( 'js' );

	function toggle( button, target, onOpen ) {
		if ( ! button || ! target ) {
			return;
		}
		button.addEventListener( 'click', function () {
			var open = button.getAttribute( 'aria-expanded' ) !== 'true';
			button.setAttribute( 'aria-expanded', String( open ) );
			if ( target.hasAttribute( 'hidden' ) || ! open ) {
				target.hidden = ! open;
			}
			target.classList.toggle( 'is-open', open );
			if ( open && onOpen ) {
				onOpen( target );
			}
		} );
	}

	toggle( document.querySelector( '.menu-toggle' ), document.getElementById( 'main-nav' ) );
	toggle( document.querySelector( '.search-toggle' ), document.getElementById( 'header-search' ), function ( el ) {
		var input = el.querySelector( 'input[type="search"]' );
		if ( input ) {
			input.focus();
		}
	} );

	// Header shadow once the page scrolls.
	var header = document.querySelector( '.site-header' );
	if ( header ) {
		var onScroll = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 10 );
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	// Hero video: skip it on slow or data-saving connections, when motion is reduced,
	// or if it fails to load; the Pajero photo underneath shows instead.
	var hero = document.querySelector( '.hero' );
	var heroVideo = document.querySelector( '.hero__video' );
	if ( heroVideo ) {
		var dropVideo = function () {
			if ( heroVideo.parentNode ) {
				heroVideo.parentNode.removeChild( heroVideo );
			}
			hero.classList.remove( 'has-video' );
		};
		var conn = navigator.connection || {};
		if ( reduceMotion || conn.saveData || /(^|-)2g$/.test( conn.effectiveType || '' ) ) {
			dropVideo();
		} else {
			var source = heroVideo.querySelector( 'source' );
			if ( source ) {
				source.addEventListener( 'error', dropVideo );
			}
			heroVideo.addEventListener( 'error', dropVideo );
		}
	}

	// Hero Pajero: 3D tilt that follows the pointer.
	var heroCar = document.querySelector( '.hero__car' );
	if ( hero && heroCar && ! reduceMotion ) {
		hero.addEventListener( 'pointermove', function ( e ) {
			var r = hero.getBoundingClientRect();
			var x = ( e.clientX - r.left ) / r.width - 0.5;
			var y = ( e.clientY - r.top ) / r.height - 0.5;
			heroCar.style.setProperty( '--ry', ( x * 16 ).toFixed( 2 ) + 'deg' );
			heroCar.style.setProperty( '--rx', ( -y * 8 ).toFixed( 2 ) + 'deg' );
		} );
		hero.addEventListener( 'pointerleave', function () {
			heroCar.style.setProperty( '--ry', '0deg' );
			heroCar.style.setProperty( '--rx', '0deg' );
		} );
	}

	// Count-up numbers.
	function countUp( el ) {
		var end = parseInt( el.getAttribute( 'data-count' ), 10 ) || 0;
		var suffix = el.getAttribute( 'data-suffix' ) || '+';
		if ( reduceMotion ) {
			el.textContent = end + suffix;
			return;
		}
		var start = null;
		var duration = 1400;
		function step( ts ) {
			if ( ! start ) {
				start = ts;
			}
			var p = Math.min( ( ts - start ) / duration, 1 );
			var eased = 1 - Math.pow( 1 - p, 3 );
			el.textContent = Math.round( end * eased ) + ( p === 1 ? suffix : '' );
			if ( p < 1 ) {
				requestAnimationFrame( step );
			}
		}
		requestAnimationFrame( step );
	}

	// Reveal on scroll. Shop product cards are revealed too, staggered per row.
	var shopCards = document.querySelectorAll( 'ul.products li.product' );
	Array.prototype.forEach.call( shopCards, function ( card, i ) {
		card.setAttribute( 'data-reveal', '' );
		card.style.setProperty( '--d', ( i % 4 ) * 0.07 + 's' );
	} );

	var items = document.querySelectorAll( '[data-reveal]' );
	if ( reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
		Array.prototype.forEach.call( items, function ( el ) {
			el.classList.add( 'is-visible' );
		} );
		Array.prototype.forEach.call( document.querySelectorAll( '[data-count]' ), countUp );
	} else {
		var io = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}
				entry.target.classList.add( 'is-visible' );
				Array.prototype.forEach.call( entry.target.querySelectorAll( '[data-count]' ), countUp );
				io.unobserve( entry.target );
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 } );
		Array.prototype.forEach.call( items, function ( el ) {
			io.observe( el );
		} );
	}

	// WhatsApp chat bubble: pops up once per visit after a few seconds, unless closed.
	var bubble = document.getElementById( 'wa-bubble' );
	if ( bubble ) {
		var store = function ( set ) {
			try {
				return set ? sessionStorage.setItem( 'akpWaBubble', '1' ) : sessionStorage.getItem( 'akpWaBubble' );
			} catch ( e ) {
				return null;
			}
		};
		var hideBubble = function () {
			bubble.classList.remove( 'is-open' );
			store( true );
			setTimeout( function () {
				bubble.hidden = true;
			}, 300 );
		};
		bubble.querySelector( '.wa-bubble__close' ).addEventListener( 'click', hideBubble );
		bubble.querySelector( '.btn' ).addEventListener( 'click', hideBubble );
		if ( ! store() ) {
			setTimeout( function () {
				bubble.hidden = false;
				void bubble.offsetWidth;
				bubble.classList.add( 'is-open' );
			}, 4000 );
		}
	}

	// Cart: put the cart contents into the WhatsApp message (WooCommerce Store API).
	Array.prototype.forEach.call( document.querySelectorAll( '[data-wa-cart]' ), function ( link ) {
		link.addEventListener( 'click', function ( e ) {
			var api = link.getAttribute( 'data-cart-api' );
			if ( ! window.fetch || ! api ) {
				return;
			}
			e.preventDefault();
			var win = window.open( '', '_blank' );
			var go = function ( url ) {
				if ( win ) {
					win.location.href = url;
				} else {
					window.location.href = url;
				}
			};
			fetch( api, { credentials: 'same-origin' } ).then( function ( r ) {
				return r.json();
			} ).then( function ( cart ) {
				var t = cart.totals;
				var money = function ( v ) {
					return t.currency_code + ' ' + ( v / Math.pow( 10, t.currency_minor_unit ) ).toFixed( t.currency_minor_unit );
				};
				var lines = [ 'Hello Al Kaffa Parts, I want to order:' ];
				cart.items.forEach( function ( item ) {
					lines.push( '• ' + item.quantity + ' × ' + item.name + ( item.sku ? ' (' + item.sku + ')' : '' ) + ' — ' + money( item.totals.line_total ) );
				} );
				lines.push( 'Total: ' + money( t.total_price ) );
				lines.push( 'Delivery address: ' );
				go( 'https://wa.me/' + link.getAttribute( 'data-wa-cart' ) + '?text=' + encodeURIComponent( lines.join( '\n' ) ) );
			} ).catch( function () {
				go( link.href );
			} );
		} );
	} );

	// Bounce the cart badge after AJAX add-to-cart (WooCommerce fires jQuery events).
	if ( window.jQuery ) {
		window.jQuery( document.body ).on( 'added_to_cart', function () {
			var badge = document.querySelector( '.cart-count' );
			if ( badge ) {
				badge.classList.remove( 'is-bump' );
				void badge.offsetWidth;
				badge.classList.add( 'is-bump' );
			}
		} );
	}
}() );
