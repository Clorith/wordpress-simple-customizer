<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}


if ( isset( $_GET['export'] ) )
{
	if ( isset( $_GET['css'] ) ) {
		$export = $simple_customize->generate_css( true, $_GET['export'] );
	}
	else {
		$export = array(
			'theme'      => $_GET['export'],
			'categories' => array(),
			'options'    => array(),
			'fonts'      => array()
		);

		$terms = get_terms(
			'simple-customize',
			array(
				'hide_empty' => false
			)
		);
		foreach ( $terms AS $term ) {
			$export['categories'][] = array(
				'name' => $term->name,
				'slug' => $term->slug
			);
		}

		$entries = get_posts( array(
			'posts_per_page' => - 1,
			'post_type'      => 'simple-customize',
			'meta_key'       => '_simple_customize_theme',
			'meta_value'     => $_GET['export']
		) );
		foreach ( $entries AS $entry ) {
			$meta = get_post_meta( $entry->ID );

			$export['options'][] = array(
				'name'      => $entry->post_title,
				'category'  => $meta['_simple_customize_category'][0],
				'selector'  => $meta['_simple_customize_selector'][0],
				'attribute' => $meta['_simple_customize_attribute'][0],
				'default'   => $meta['_simple_customize_default'][0],
				'value'     => get_theme_mod( $entry->post_name, $meta['_simple_customize_default'][0] )
			);
		}

		$fonts = get_posts( array(
			'posts_per_page' => - 1,
			'post_type'      => 's-c-font',
			'post_status'    => array( 'draft', 'publish' )
		) );
		foreach ( $fonts AS $font ) {


			$export['fonts'][] = array(
				'name'   => $font->post_title,
				'status' => $font->post_status,
				'url'    => get_post_meta( $font->ID, '_simple_customize_font', true )
			);
		}

		$export = json_encode( $export );
	}
	?>

	<h3><?php _e( 'Export customization', 'simple-customize-plugin' ); ?></h3>
	<textarea name="simple-customize-export" style="width: 100%; height: 75px;"><?php echo $export; ?></textarea>

	<?php
	_e( 'Copy the text above, it represents your themes customizations, categories and fonts', 'simple-customize-plugin' );
}