/**
 * Gift Card AI Assistant chat.
 *
 * @package woo-gift-cards-lite
 */
(function () {
	'use strict';

	var data = window.wps_wgm_ai;
	var root = document.querySelector( '.wps-wgm-ai' );
	if ( ! data || ! root ) {
		return;
	}

	var list    = root.querySelector( '.wps-wgm-ai-messages' );
	var form    = root.querySelector( '.wps-wgm-ai-form' );
	var input   = root.querySelector( '#wps-wgm-ai-input' );
	var send    = form.querySelector( 'button[type="submit"]' );
	var welcome = root.querySelector( '.wps-wgm-ai-welcome' );
	var s       = data.strings;

	function post( action, fields ) {
		var body = new FormData();
		body.append( 'action', action );
		body.append( 'nonce', data.nonce );
		Object.keys( fields || {} ).forEach( function ( key ) {
			body.append( key, fields[ key ] );
		} );
		return fetch( data.ajaxurl, { method: 'POST', credentials: 'same-origin', body: body } )
			.then( function ( response ) {
				return response.json();
			} );
	}

	function escapeHtml( text ) {
		return String( text )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' )
			.replace( /'/g, '&#039;' );
	}

	// Minimal markdown: escape first, then bold, code, bullet lists and tables.
	function format( text ) {
		var lines = escapeHtml( text ).split( '\n' );
		var html  = '';
		var inList = false;
		var table  = null;

		function inline( line ) {
			return line
				.replace( /`([^`]+)`/g, '<code>$1</code>' )
				.replace( /\*\*([^*]+)\*\*/g, '<strong>$1</strong>' );
		}
		function closeBlocks() {
			if ( inList ) {
				html  += '</ul>';
				inList = false;
			}
			if ( table ) {
				html += '<div class="wps-wgm-ai-table"><table>' + table + '</tbody></table></div>';
				table = null;
			}
		}

		lines.forEach( function ( raw ) {
			var line = raw.trim();
			if ( /^\|.*\|$/.test( line ) ) {
				if ( /^\|[\s:|-]+\|$/.test( line ) ) {
					return; // Separator row.
				}
				var cells = line.slice( 1, -1 ).split( '|' ).map( function ( cell ) {
					return inline( cell.trim() );
				} );
				if ( ! table ) {
					closeBlocks();
					table = '<thead><tr><th>' + cells.join( '</th><th>' ) + '</th></tr></thead><tbody>';
				} else {
					table += '<tr><td>' + cells.join( '</td><td>' ) + '</td></tr>';
				}
				return;
			}
			if ( /^[-*] /.test( line ) ) {
				if ( table ) {
					closeBlocks();
				}
				if ( ! inList ) {
					html  += '<ul>';
					inList = true;
				}
				html += '<li>' + inline( line.slice( 2 ) ) + '</li>';
				return;
			}
			closeBlocks();
			if ( line ) {
				html += '<p>' + inline( line ) + '</p>';
			}
		} );
		closeBlocks();
		return html;
	}

	function addMessage( role, text, isHtml ) {
		if ( welcome ) {
			welcome.remove();
			welcome = null;
		}
		var item   = document.createElement( 'div' );
		var bubble = document.createElement( 'div' );
		item.className   = 'wps-wgm-ai-msg is-' + role;
		bubble.className = 'wps-wgm-ai-bubble';
		bubble.innerHTML = isHtml ? text : format( text );
		item.appendChild( bubble );
		list.appendChild( item );
		list.scrollTop = list.scrollHeight;
		return bubble;
	}

	function addChange( change ) {
		var bubble = addMessage( 'change', '' );
		bubble.innerHTML =
			'<span class="wps-wgm-ai-change-label">' + escapeHtml( s.needs ) + '</span>' +
			'<p>' + escapeHtml( change.summary ) + '</p>' +
			'<div class="wps-wgm-ai-change-actions">' +
				'<button type="button" class="button button-primary" data-decision="confirm">' + escapeHtml( s.confirm ) + '</button>' +
				'<button type="button" class="button" data-decision="cancel">' + escapeHtml( s.cancel ) + '</button>' +
			'</div>';

		bubble.addEventListener( 'click', function ( event ) {
			var button = event.target.closest( 'button[data-decision]' );
			if ( ! button ) {
				return;
			}
			var actions = bubble.querySelector( '.wps-wgm-ai-change-actions' );
			actions.querySelectorAll( 'button' ).forEach( function ( b ) {
				b.disabled = true;
			} );
			post( 'wps_wgm_ai_decide', { id: change.id, decision: button.getAttribute( 'data-decision' ) } )
				.then( function ( response ) {
					var message = ( response.data && response.data.message ) || s.error;
					actions.outerHTML = '<p class="wps-wgm-ai-change-result ' + ( response.success ? 'is-ok' : 'is-error' ) + '">' + escapeHtml( message ) + '</p>';
				} )
				.catch( function () {
					actions.outerHTML = '<p class="wps-wgm-ai-change-result is-error">' + escapeHtml( s.error ) + '</p>';
				} );
		} );
	}

	function setBusy( busy ) {
		input.disabled = busy;
		send.disabled  = busy;
	}

	function ask( text ) {
		addMessage( 'user', text );
		var thinking = addMessage( 'assistant', s.thinking );
		thinking.parentNode.classList.add( 'is-thinking' );
		setBusy( true );

		post( 'wps_wgm_ai_chat', { message: text } )
			.then( function ( response ) {
				thinking.parentNode.remove();
				if ( response.success ) {
					addMessage( 'assistant', response.data.reply || '' );
					( response.data.changes || [] ).forEach( addChange );
				} else {
					var d = response.data || {};
					addMessage( 'assistant', d.message || s.error, !! d.is_html ).parentNode.classList.add( 'is-error' );
				}
			} )
			.catch( function () {
				thinking.parentNode.remove();
				addMessage( 'assistant', s.error ).parentNode.classList.add( 'is-error' );
			} )
			.then( function () {
				setBusy( false );
				input.focus();
			} );
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		var text = input.value.trim();
		if ( ! text || send.disabled ) {
			return;
		}
		input.value        = '';
		input.style.height = '';
		ask( text );
	} );

	// Enter sends, Shift+Enter adds a line.
	input.addEventListener( 'keydown', function ( event ) {
		if ( 'Enter' === event.key && ! event.shiftKey ) {
			event.preventDefault();
			form.requestSubmit();
		}
	} );
	input.addEventListener( 'input', function () {
		input.style.height = '';
		input.style.height = Math.min( input.scrollHeight, 160 ) + 'px';
	} );

	root.addEventListener( 'click', function ( event ) {
		var example = event.target.closest( '.wps-wgm-ai-example' );
		if ( example && ! send.disabled ) {
			ask( example.textContent.trim() );
		}
	} );

	root.querySelector( '.wps-wgm-ai-reset' ).addEventListener( 'click', function () {
		if ( window.confirm( s.reset ) ) {
			post( 'wps_wgm_ai_reset' ).then( function () {
				window.location.reload();
			} );
		}
	} );

	( data.history || [] ).forEach( function ( message ) {
		addMessage( message.role, message.text );
	} );
})();
