/**
 * Receive data from the customizer
 */
( function ( exports, $ ) {
	"use strict";

	var api = wp.customize,
		OldPreviewer,
		simple_select = false;

	api.SimpleCustomizerPreviewer = {
		init: function () {
			var self = this;

			this.preview.bind( 'simple-customizer-preview-click', function ( data ) {
				var theseParents,
					styled;

				if ( simple_select ) {
					simple_select = false;

					if ($("#customize-strict-grab").is(':checked')) {
						theseParents = data.parents.strict;
					} else {
						theseParents = data.parents.non_strict;
					}

					$("#simple_customize_selected").val(theseParents);
					$("#simple_customize_label").val(data.label);
					$("#simple_customize_default").val(data.default);

					$("#simple_customize_selector_auto").find('option').remove().end();

					styled = $.map(data.styles, function(value, index) {
						return [value];
					});

					for (var i = 0; i < styled.length; i++) {
						$("#simple_customize_selector_auto").append('<option value="' + styled[i] + '">' + styled[i] + '</option>');
					}

					simple_customize_reveal();
					simple_customize_show();
				}
			} );
		}
	};

	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	OldPreviewer = api.Previewer;
	api.Previewer = OldPreviewer.extend( {
		initialize: function( params, options ) {
			// Store a reference to the Previewer
			api.SimpleCustomizerPreviewer.preview = this;

			// Call the old Previewer's initialize function
			OldPreviewer.prototype.initialize.call( this, params, options );
		}
	} );

	$( function() {
		// Initialize our Previewer
		api.SimpleCustomizerPreviewer.init();
	} );

	function simple_customize_hide() {
		$(".simple-customize").addClass( 'simple-customize-hide' );
		$(".simple-select-button").addClass( 'simple-customize-hide' );
		$(".simple-select-info").addClass( 'simple-customize-show' );
		$(".customize-control-content").removeClass( 'simple-customize-hide' );
	}
	function simple_customize_show() {
		$(".simple-customize-hide").removeClass( 'simple-customize-hide' );

		$(".simple-select-info").removeClass( 'simple-customize-show' );
	}
	function simple_customize_reveal() {
		$(".simple-customize-reveal").removeClass( 'simple-customize-reveal' );
	}

	$("#customize-theme-controls").on( 'change', '#simple_customize_selector_auto', function (e) {
		$("#simple_customize_default").val($("#customize-preview iframe").contents().find($("#simple_customize_selected").val()).css($(this).val()));
	}).on('click', '#simple_customize_selector', function (e) {
		simple_select = true;
		simple_customize_hide();
	}).on('click', '#simple_customize_cancel', function (e) {
		simple_select = false;
		simple_customize_show();
	}).on( 'click', '#simple_customize_store', function (e) {
		e.preventDefault();
		$.post(
			SimpleCustomize.ajaxurl,
			{
				action: 'simple-customize-new-object',
				label: $("#simple_customize_label").val(),
				object: $("#simple_customize_selected").val(),
				default: $("#simple_customize_default").val(),
				selector: $("#simple_customize_selector_auto").val(),
				selector_manual: $("#simple_customize_selector_manual").val(),
				category: $("#simple_customize_category").val(),
				_nonce: SimpleCustomize.customizerNonce
			}, function (response) {
				location.reload();
			}
		);
	});
} )( wp, jQuery );