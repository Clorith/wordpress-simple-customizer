<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

function simple_customize_attribute_font_family() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		$simple_customize->meta['_simple_customize_default'][0] => __( '&mdash; Default value &mdash;', 'simple-customize-plugin' ),
		'Andale Mono, sans-serif'     => 'Andale Mono',
		'Arial, sans-serif'           => 'Arial',
		'Arial Black, sans-serif'     => 'Arial Black',
		'Comic Sans, sans-serif'      => 'Comic Sans',
		'Courier New, sans-serif'     => 'Courier New',
		'Georgia, sans-serif'         => 'Georgia',
		'Impact, sans-serif'          => 'Impact',
		'Times New Roman, sans-serif' => 'Times New Roman',
		'Trebuchet, sans-serif'       => 'Trebuchet',
		'Verdana, sans-serif'         => 'Verdana',
		'Webdings, sans-serif'        => 'Webdings'
	);

	$fonts = get_posts( array(
		'posts_per_page' => -1,
		'post_type'      => 's-c-font',
		'post_status'    => array( 'publish' )
	) );
	foreach( $fonts AS $font )
	{
		$simple_customize->attribute['args']['choices'][ $font->post_title ] = $font->post_title;
	}
}
add_action( 'simple-customize-font-family', 'simple_customize_attribute_font_family' );

function simple_customize_attribute_visibility() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'visible'  => __( 'Visible', 'simple-customize-plugin' ),
		'hidden'   => __( 'Hidden', 'simple-customize-plugin' ),
		'collapse' => __( 'Collapsed', 'simple-customize-plugin' ),
		'inherit'  => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-visibility', 'simple_customize_attribute_visibility' );

function simple_customize_attribute_text_transform() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['chocies'] = array(
		'none'       => __( 'No effect (default)', 'simple-customize-plugin' ),
		'capitalize' => __( 'Capitalize first letter', 'simple-customize-plugin' ),
		'uppercase'  => __( 'Make text uppercase', 'simple-customize-plugin' ),
		'lowercase'  => __( 'Make text lowercase', 'simple-customize-plugin' ),
		'inherit'    => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-text-transform', 'simple_customize_attribute_text_transform' );

function simple_customize_attribute_text_decoration() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'none'         => __( 'No decoration (default)', 'simple-customize-plugin' ),
		'underline'    => __( 'Underline text', 'simple-customize-plugin' ),
		'overline'     => __( 'Overline text', 'simple-customize-plugin' ),
		'line-through' => __( 'Strike through text', 'simple-customize-plugin' ),
		'inherit'      => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-text-decoration', 'simple_customize_attribute_text_decoration' );

function simple_customize_attribute_text_align() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'left'    => __( 'Left align text', 'simple-customize-plugin' ),
		'right'   => __( 'Right align text', 'simple-customize-plugin' ),
		'center'  => __( 'Center text', 'simple-customize-plugin' ),
		'justify' => __( 'Justified position', 'simple-customize-plugin' ),
		'inherit' => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-text-align', 'simple_customize_attribute_text_align' );

function simple_customize_attribute_position() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'static'   => __( 'Static position (default)', 'simple-customize-plugin' ),
		'relative' => __( 'Allow child element to position relative to this', 'simple-customize-plugin' ),
		'absolute' => __( 'Position relative to parent element', 'simple-customize-plugin' ),
		'fixed'    => __( 'Freeze in place, even when scrolling', 'simple-customize-plugin' ),
		'inherit'  => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-position', 'simple_customize_attribute_position' );

function simple_customize_attribute_overflow() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['chocies'] = array(
		'visible' => __( 'Show overflow', 'simple-customize-plugin' ),
		'hidden'  => __( 'Hide overflow', 'simple-customize-plugin' ),
		'scroll'  => __( 'Add scrollbars for overflow', 'simple-customize-plugin' ),
		'auto'    => __( 'Let browser decide how to handle it', 'simple-customize-plugin' ),
		'inherit' => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-overflow', 'simple_customize_attribute_overflow' );

function simple_customize_attribute_list_style_type() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'dics'                 => __( 'Discs (default)', 'simple-customize-plugin' ),
		'circle'               => __( 'Circle', 'simple-customize-plugin' ),
		'square'               => __( 'Square', 'simple-customize-plugin' ),
		'decimal'              => __( 'Decimal', 'simple-customize-plugin' ),
		'decimal-leading-zero' => __( 'Decimals with leading zero', 'simple-customize-plugin' ),
		'lower-roman'          => __( 'Lowercase roman numerals', 'simple-customize-plugin' ),
		'upper-roman'          => __( 'Uppercase roman numerals', 'simple-customize-plugin' ),
		'lower-greek'          => __( 'Lowercase greek characters', 'simple-customize-plugin' ),
		'lower-latin'          => __( 'Lowercase latin characters', 'simple-customize-plugin' ),
		'upper-latin'          => __( 'Uppercase latin characters', 'simple-customize-plugin' ),
		'armenian'             => __( 'Armenian characters', 'simple-customize-plugin' ),
		'georgian'             => __( 'Georgian characters', 'simple-customize-plugin' ),
		'lower-alpha'          => __( 'Lowercase letters', 'simple-customize-plugin' ),
		'upper-alpha'          => __( 'Uppercase letters', 'simple-customize-plugin' ),
		'none'                 => __( 'None', 'simple-customize-plugin' ),
		'inherit'              => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-list-style-type', 'simple_customize_attribute_list_style_type' );

function simple_customize_attribute_font_weight() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'normal'  => __( 'Normal', 'simple-customize-plugin' ),
		'bold'    => __( 'Bold', 'simple-customize-plugin' ),
		'bolder'  => __( 'Bolder', 'simple-customize-plugin' ),
		'lighter' => __( 'Lighter', 'simple-customize-plugin' ),
		'100'     => __( 'Boldness 100', 'simple-customize-plugin' ),
		'200'     => __( 'Boldness 200', 'simple-customize-plugin' ),
		'300'     => __( 'Boldness 300', 'simple-customize-plugin' ),
		'400'     => __( 'Boldness 400', 'simple-customize-plugin' ),
		'500'     => __( 'Boldness 500', 'simple-customize-plugin' ),
		'600'     => __( 'Boldness 600', 'simple-customize-plugin' ),
		'700'     => __( 'Boldness 700', 'simple-customize-plugin' ),
		'800'     => __( 'Boldness 800', 'simple-customize-plugin' ),
		'900'     => __( 'Boldness 900', 'simple-customize-plugin' ),
		'inherit' => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-font-weight', 'simple_customize_attribute_font_weight' );

function simple_customize_attribute_font_variant() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'normal'     => __( 'Normal', 'simple-customize-plugin' ),
		'small-caps' => __( 'Small caps', 'simple-customize-plugin' ),
		'inherit'    => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-font-variant', 'simple_customize_attribute_font_variant' );

function simple_customize_attribute_font_style() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'normal'  => __( 'Normal', 'simple-customize-plugin' ),
		'italic'  => __( 'italic', 'simple-customize-plugin' ),
		'oblique' => __( 'Oblique', 'simple-customize-plugin' ),
		'inherit' => __( 'Use parent elements choise', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-font-style', 'simple_customize_attribute_font_style' );

function simple_customize_attribute_float() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'left'    => __( 'Float left', 'simple-customize-plugin' ),
		'right'   => __( 'Float right', 'simple-customize-plugin' ),
		'none'    => __( 'No float', 'simple-customize-plugin' ),
		'inherit' => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-float', 'simple_customize_attribute_float' );

function simple_customize_attribute_clear() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'none'    => __( 'No clear', 'simple-customize-plugin' ),
		'left'    => __( 'Clear left', 'simple-customize-plugin' ),
		'right'   => __( 'Clear right', 'simple-customize-plugin' ),
		'both'    => __( 'Clear both', 'simple-customize-plugin' ),
		'inherit' => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-clear', 'simple_customize_attribute_clear' );

function simple_customize_attribute_background_repeat() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'dropdown';
	$simple_customize->attribute['args']['choices'] = array(
		'repeat'    => __( 'Repeat as tiles', 'simple-customize-plugin' ),
		'repeat-x'  => __( 'Repeat horizontally', 'simple-customize-plugin' ),
		'repeat-y'  => __( 'Repeat vertically', 'simple-customize-plugin' ),
		'no-repeat' => __( 'Do not repeat', 'simple-customize-plugin' ),
		'inherit'   => __( 'Use parent elements choice', 'simple-customize-plugin' )
	);
}
add_action( 'simple-customize-background-repeat', 'simple_customize_attribute_background_repeat' );

function simple_customize_attribute_background_color() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'color';
}
add_action( 'simple-customize-background-color', 'simple_customize_attribute_background_color' );

function simple_customize_attribute_background_image() {
	global $simple_customize;

	$simple_customize->attribute['type'] = 'image';
}
add_action( 'simple-customize-background-image', 'simple_customize_attribute_background_image' );