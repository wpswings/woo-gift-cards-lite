/**
 * My Account > My Gift Cards: search, filter, sort, paginate, view details,
 * copy code and resend email.
 *
 * @package woo-gift-cards-lite
 */
(function () {
	'use strict';

	var settings = window.wps_wgm_my_gift_cards;
	if ( ! settings ) {
		return;
	}

	var TYPE_FILTERS   = [ 'received', 'sent' ];
	var STATUS_FILTERS = [ 'active', 'expired', 'used' ];

	function format( template ) {
		var args = Array.prototype.slice.call( arguments, 1 );
		return template.replace( /%(\d+)\$s|%s/g, function ( match, position ) {
			return position ? args[ position - 1 ] : args.shift();
		} );
	}

	// The Clipboard API only exists on HTTPS pages, so fall back to execCommand elsewhere.
	function copyText( text ) {
		if ( navigator.clipboard && window.isSecureContext ) {
			return navigator.clipboard.writeText( text );
		}
		return new Promise( function ( resolve, reject ) {
			var field = document.createElement( 'textarea' );
			field.value = text;
			field.setAttribute( 'readonly', '' );
			field.style.position = 'fixed';
			field.style.opacity  = '0';
			document.body.appendChild( field );
			field.select();
			var ok = document.execCommand( 'copy' );
			document.body.removeChild( field );
			( ok ? resolve : reject )();
		} );
	}

	/**
	 * Wire up the search, filter, sort and pagination controls for one gift card list.
	 *
	 * @param {HTMLElement} root The .wps-wgm-mgc wrapper.
	 */
	function initList( root ) {
		var controls   = root.querySelector( '.wps-wgm-mgc-controls' );
		var grid       = root.querySelector( '.wps-wgm-mgc-grid' );
		if ( ! controls || ! grid ) {
			return;
		}

		var cards      = Array.prototype.slice.call( grid.querySelectorAll( '.wps-wgm-mgc-card' ) );
		var search     = controls.querySelector( '.wps-wgm-mgc-search-input' );
		var sort       = controls.querySelector( '.wps-wgm-mgc-sort-select' );
		var chips      = Array.prototype.slice.call( controls.querySelectorAll( '.wps-wgm-mgc-chip' ) );
		var results    = controls.querySelector( '.wps-wgm-mgc-results' );
		var noResults  = root.querySelector( '.wps-wgm-mgc-no-results' );
		var pagination = root.querySelector( '.wps-wgm-mgc-pagination' );
		var perPage    = parseInt( root.getAttribute( 'data-per-page' ), 10 ) || 6;
		var state      = { term: '', type: '', status: '', sort: 'default', page: 1 };
		var timer;

		controls.hidden = false;

		function number( card, attr, fallback ) {
			var value = card.getAttribute( attr );
			return '' === value ? fallback : parseFloat( value );
		}

		var comparators = {
			'default': function ( a, b ) {
				return number( a, 'data-index', 0 ) - number( b, 'data-index', 0 );
			},
			newest: function ( a, b ) {
				return number( b, 'data-id', 0 ) - number( a, 'data-id', 0 );
			},
			oldest: function ( a, b ) {
				return number( a, 'data-id', 0 ) - number( b, 'data-id', 0 );
			},
			'balance-desc': function ( a, b ) {
				return number( b, 'data-balance', 0 ) - number( a, 'data-balance', 0 );
			},
			'balance-asc': function ( a, b ) {
				return number( a, 'data-balance', 0 ) - number( b, 'data-balance', 0 );
			},
			// Cards that never expire go last; already expired ones are of no use, so they follow them.
			expiry: function ( a, b ) {
				var now  = Date.now() / 1000;
				var tier = function ( card ) {
					var expires = number( card, 'data-expires', null );
					return null === expires ? 1 : ( expires < now ? 2 : 0 );
				};
				return tier( a ) - tier( b ) ||
					number( a, 'data-expires', 0 ) - number( b, 'data-expires', 0 ) ||
					comparators[ 'default' ]( a, b );
			}
		};

		function matches( card ) {
			if ( state.type && card.getAttribute( 'data-type' ) !== state.type ) {
				return false;
			}
			if ( state.status && card.getAttribute( 'data-status' ) !== state.status ) {
				return false;
			}
			return ! state.term || -1 !== card.getAttribute( 'data-search' ).indexOf( state.term );
		}

		function renderPagination( pages ) {
			pagination.innerHTML = '';
			pagination.hidden    = pages < 2;
			if ( pages < 2 ) {
				return;
			}

			function addButton( label, page, extraClass, ariaLabel ) {
				var button       = document.createElement( 'button' );
				button.type      = 'button';
				button.className = 'wps-wgm-mgc-page' + ( extraClass ? ' ' + extraClass : '' );
				button.textContent = label;
				button.setAttribute( 'data-page', page );
				if ( ariaLabel ) {
					button.setAttribute( 'aria-label', ariaLabel );
				}
				if ( page === state.page && ! extraClass ) {
					button.setAttribute( 'aria-current', 'page' );
				}
				if ( page < 1 || page > pages ) {
					button.disabled = true;
				}
				pagination.appendChild( button );
			}

			function addGap() {
				var gap       = document.createElement( 'span' );
				gap.className = 'wps-wgm-mgc-page-gap';
				gap.textContent = '…';
				gap.setAttribute( 'aria-hidden', 'true' );
				pagination.appendChild( gap );
			}

			addButton( '‹', state.page - 1, 'is-prev', settings.previous );
			var last = 0;
			for ( var page = 1; page <= pages; page++ ) {
				// Always show the first, last and the pages around the current one.
				if ( 1 === page || pages === page || Math.abs( page - state.page ) <= 1 ) {
					if ( page - last > 1 ) {
						addGap();
					}
					addButton( String( page ), page, '', format( settings.page, page ) );
					last = page;
				}
			}
			addButton( '›', state.page + 1, 'is-next', settings.next );
		}

		function render() {
			var visible = cards.filter( matches ).sort( comparators[ state.sort ] || comparators[ 'default' ] );
			var pages   = Math.max( 1, Math.ceil( visible.length / perPage ) );
			state.page  = Math.min( Math.max( 1, state.page ), pages );
			var start   = ( state.page - 1 ) * perPage;
			var shown   = visible.slice( start, start + perPage );

			cards.forEach( function ( card ) {
				card.hidden = true;
			} );
			shown.forEach( function ( card ) {
				card.hidden = false;
				grid.appendChild( card );
			} );

			grid.hidden       = ! visible.length;
			noResults.hidden  = !! visible.length;
			results.textContent = visible.length ? format( settings.results, start + 1, start + shown.length, visible.length ) : '';

			chips.forEach( function ( chip ) {
				var filter = chip.getAttribute( 'data-filter' );
				var active = 'all' === filter ? ! state.type && ! state.status : filter === state.type || filter === state.status;
				chip.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
			} );

			renderPagination( pages );
		}

		function reset() {
			state.term   = '';
			state.type   = '';
			state.status = '';
			state.page   = 1;
			search.value = '';
			render();
		}

		search.addEventListener( 'input', function () {
			clearTimeout( timer );
			timer = setTimeout( function () {
				state.term = search.value.trim().toLowerCase();
				state.page = 1;
				render();
			}, 150 );
		} );

		sort.addEventListener( 'change', function () {
			state.sort = sort.value;
			state.page = 1;
			render();
		} );

		// Type (received/sent) and status filters combine, e.g. "Received" + "Active".
		controls.addEventListener( 'click', function ( event ) {
			var chip = event.target.closest( '.wps-wgm-mgc-chip' );
			if ( ! chip ) {
				return;
			}
			var filter = chip.getAttribute( 'data-filter' );
			if ( 'all' === filter ) {
				state.type   = '';
				state.status = '';
			} else if ( -1 !== TYPE_FILTERS.indexOf( filter ) ) {
				state.type = state.type === filter ? '' : filter;
			} else if ( -1 !== STATUS_FILTERS.indexOf( filter ) ) {
				state.status = state.status === filter ? '' : filter;
			}
			state.page = 1;
			render();
		} );

		pagination.addEventListener( 'click', function ( event ) {
			var button = event.target.closest( '.wps-wgm-mgc-page' );
			if ( ! button || button.disabled ) {
				return;
			}
			state.page = parseInt( button.getAttribute( 'data-page' ), 10 );
			render();
			var top = controls.getBoundingClientRect().top;
			if ( top < 0 ) {
				controls.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			}
		} );

		noResults.querySelector( '.wps-wgm-mgc-reset' ).addEventListener( 'click', reset );

		render();
	}

	/**
	 * Open the details dialog for a card.
	 *
	 * @param {HTMLElement} card    The card element.
	 * @param {HTMLElement} trigger The button that opened it, refocused on close.
	 */
	function openDetails( card, trigger ) {
		var root     = card.closest( '.wps-wgm-mgc' );
		var dialog   = root.querySelector( '.wps-wgm-mgc-dialog' );
		var template = card.querySelector( '.wps-wgm-mgc-details' );
		if ( ! dialog || ! template ) {
			return;
		}

		var body = dialog.querySelector( '.wps-wgm-mgc-dialog-body' );
		body.innerHTML = '';
		body.appendChild( document.importNode( template.content, true ) );

		dialog.wpsTrigger = trigger;
		if ( 'function' === typeof dialog.showModal ) {
			dialog.showModal();
		} else {
			dialog.setAttribute( 'open', '' );
		}
		dialog.querySelector( '.wps-wgm-mgc-dialog-close' ).focus();
	}

	function closeDetails( dialog ) {
		if ( 'function' === typeof dialog.close ) {
			dialog.close();
		} else {
			dialog.removeAttribute( 'open' );
			dialog.dispatchEvent( new Event( 'close' ) );
		}
	}

	function resend( button ) {
		if ( button.disabled || ! window.confirm( settings.confirm ) ) {
			return;
		}

		var notice      = button.closest( '.wps-wgm-mgc-actions' ).querySelector( '.wps-wgm-mgc-notice' );
		var label       = button.querySelector( '.wps-wgm-mgc-resend-label' );
		var buttonLabel = label.textContent;
		var data        = new FormData();
		data.append( 'action', 'wps_wgm_my_account_resend_giftcard' );
		data.append( 'nonce', settings.nonce );
		data.append( 'coupon_id', button.getAttribute( 'data-coupon-id' ) );

		button.disabled    = true;
		label.textContent  = settings.sending;
		notice.textContent = '';
		notice.className   = 'wps-wgm-mgc-notice';

		function show( message, success ) {
			notice.textContent = message;
			notice.className   = 'wps-wgm-mgc-notice ' + ( success ? 'is-success' : 'is-error' );
		}

		fetch( settings.ajaxurl, { method: 'POST', credentials: 'same-origin', body: data } )
			.then( function ( response ) {
				return response.text();
			} )
			.then( function ( text ) {
				// check_ajax_referer() answers "-1" rather than JSON when the nonce has expired.
				if ( '-1' === text.trim() ) {
					show( settings.expired, false );
					return;
				}
				var json;
				try {
					json = JSON.parse( text );
				} catch ( e ) {
					show( settings.error, false );
					return;
				}
				show( ( json.data && json.data.message ) || settings.error, !! json.success );
			} )
			.catch( function () {
				show( settings.error, false );
			} )
			.then( function () {
				button.disabled   = false;
				label.textContent = buttonLabel;
			} );
	}

	document.querySelectorAll( '.wps-wgm-mgc' ).forEach( function ( root ) {
		initList( root );

		var dialog = root.querySelector( '.wps-wgm-mgc-dialog' );
		if ( ! dialog ) {
			return;
		}
		dialog.addEventListener( 'close', function () {
			if ( dialog.wpsTrigger ) {
				dialog.wpsTrigger.focus();
			}
		} );
		// A click on the backdrop lands on the dialog element itself.
		dialog.addEventListener( 'click', function ( event ) {
			if ( event.target === dialog ) {
				closeDetails( dialog );
			}
		} );
	} );

	document.addEventListener( 'click', function ( event ) {
		var copy = event.target.closest( '.wps-wgm-mgc-copy' );
		if ( copy ) {
			var copyLabel = copy.querySelector( '.wps-wgm-mgc-copy-label' );
			var original  = copyLabel.textContent;
			copyText( copy.getAttribute( 'data-code' ) ).then( function () {
				copyLabel.textContent = settings.copied;
				copy.classList.add( 'is-copied' );
				setTimeout( function () {
					copyLabel.textContent = original;
					copy.classList.remove( 'is-copied' );
				}, 1500 );
			} );
			return;
		}

		var view = event.target.closest( '.wps-wgm-mgc-view' );
		if ( view ) {
			openDetails( view.closest( '.wps-wgm-mgc-card' ), view );
			return;
		}

		var close = event.target.closest( '.wps-wgm-mgc-dialog-close' );
		if ( close ) {
			closeDetails( close.closest( '.wps-wgm-mgc-dialog' ) );
			return;
		}

		var button = event.target.closest( '.wps-wgm-mgc-resend' );
		if ( button ) {
			resend( button );
		}
	} );
})();
