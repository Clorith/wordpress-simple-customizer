jQuery(document).ready(function ($) {
	$("#simple-customize-font-load").click(function (e) {
		e.preventDefault();
		simple_customize_load_webfonts( $(this).data( 'simple-customize-font-nonce' ) );
	});

	function simple_customize_load_webfonts( nonce ) {
		var ajaxData = {
			action      : 'simple-customize',
			_ajax_nonce : nonce,
			todo        : 'refresh-fonts'
		};

		$.post( ajaxurl, ajaxData, function ( response ) {
			$(".simple-customize-font-select-box").html( response );
		} );
	}

	$(".simple-customize-font-modal").click(function (e) {
		if ( $(".simple-customize-font-select-box").text().trim() == '' ) {
			simple_customize_load_webfonts( $("#simple-customize-font-load").data( 'simple-customize-font-nonce' ) );
		}
	});
	$(".simple-customize-font-select-box").on( 'click', '.simple-customize-font', function () {
		$(".simple-customize-font.active").removeClass( 'active' );
		$(this).addClass( 'active' );

		var preview_font_name = $(this).text(),
			preview_font_family = $(this).data( 'simple-customize-font-family-preview' );

		$("#simple-customize-add-font-location").val( $(this).data( 'simple-customize-font-url' ) );
		$("#simple-customize-add-font-label").val( preview_font_name );

		WebFont.load({
			google : {
				families : [preview_font_name]
			}
		});

		$("#simple-customize-font-preview").css( 'font-family', preview_font_family );
	});

	$(".simple-customize-edit-entry").click(function (e) {
		var $row = $(this).closest('tr'),
			slug = $(this).data('customize-slug');

		$("#simple-customize-edit-slug").val(slug);
		$("#edit-selector-name").text($(".simple-customize-name", $row).text().trim());
		$("#simple-customize-edit-category").val($(".simple-customize-category", $row).text().trim());
		$("#simple-customize-edit-object").val($(".simple-customize-object", $row).text().trim());
		$("#simple-customize-edit-selector_manual").val($(".simple-customize-selector", $row).text().trim());
		$("#simple-customize-edit-default").val($(".simple-customize-default", $row).text().trim());
	});
});