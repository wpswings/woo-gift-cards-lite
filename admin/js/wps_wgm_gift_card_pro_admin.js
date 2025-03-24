/**
 * All of the code for notices on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package          woo-gift-cards-lite
 */

(function( $ ) {
	'use strict';

	jQuery( document ).ready(
		function(){
            $(".wps_wgm_nav_tab").removeClass("wps-gift-cards-pro-tag");
           
			if (typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined') {
				const { registerBlockType }      = wp.blocks;
				const { TextControl, PanelBody } = wp.components;
				const { useState }               = wp.element;
				const { useBlockProps }          = wp.blockEditor;

				registerBlockType('giftcard/check-balance', {
					title      : 'WPSwings Check Gift Card Balance Shortcode',
					icon       : 'media-document',
					category   : 'widgets',
					attributes : {
						shortcode : { type: 'string', default: '[wps_check_your_gift_card_balance]' }
					},
					edit: function(props) {
						return wp.element.createElement('div', useBlockProps(),
							wp.element.createElement(TextControl, {
								label       : 'Enter Shortcode',
								value       : props.attributes.shortcode,
								onChange    : function(shortcode) { props.setAttributes({ shortcode: shortcode }) },
								placeholder : '[wps_check_your_gift_card_balance]'
							}),
							wp.element.createElement('p', {}, 'Shortcode Output: ' + props.attributes.shortcode)
						);
					},
					save: function(props) {
						return wp.element.createElement('div', useBlockProps.save(), props.attributes.shortcode);
					}
				});
			}
		}
	);
})( jQuery );
