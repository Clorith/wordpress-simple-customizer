/**
 * Customizer Previewer
 */
( function ( wp, $ ) {
	"use strict";

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize,
		OldPreview;

	api.SimpleCustomizerPreviewer = {
		init: function () {
			var self = this;

			$("html").on('click', function( e ) {
				var parents_strict,
					parents_nostrict,
					styled,
					theseParents = $.map($(e.target).parents().not('html').not('body'), function(elm) {
					var entry = elm.tagName.toLowerCase();
					if (elm.className) {
						entry += "." + elm.className.replace(/ /g, '.');
					}
					return entry
				});

				parents_strict = theseParents[0];

				theseParents.reverse();
				parents_nostrict = theseParents.join(" ");

				styled = window.getComputedStyle(e.target);

				var PreviewData = {
					parents : {
						'strict'     : parents_strict,
						'non_strict' : parents_nostrict
					},
					styles  : styled,
					label   : $( e.target ).text().trim(),
					default : $( e.target ).css('color')
				};

				self.preview.send( 'simple-customizer-preview-click', PreviewData );
			} );
		}
	};

	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	OldPreview = api.Preview;
	api.Preview = OldPreview.extend( {
		initialize: function( params, options ) {
			// Store a reference to the Preview
			api.SimpleCustomizerPreviewer.preview = this;

			// Call the old Preview's initialize function
			OldPreview.prototype.initialize.call( this, params, options );
		}
	} );

	$( function () {
		// Initialize our Preview
		api.SimpleCustomizerPreviewer.init();
	} );
} )( window.wp, jQuery );
