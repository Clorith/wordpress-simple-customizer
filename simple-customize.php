<?php
/**
 * Plugin Name: Simple Customizer
 * Plugin URI: http://www.clorith.net/wordpress-simple-customize/
 * Description: Customize the look of your themes without modifying any code, just point and click on the element you wish to change.
 * Version: 1.7.1
 * Author: Clorith
 * Text Domain: simple-customizer
 * Author URI: http://www.clorith.net
 * License: GPL2
 *
 * Copyright 2013 Marius Jensen (email : marius@jits.no)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class simple_customize
{
    /**
     * @var array $sections Used for storing our added sections before displaying them
     * @var array $settings The settings we wish to implement
     */
	private $version   = "1.7.1";
	private $debug     = false;
    private $sections  = array();
    public $settings   = array();
    public $pluginurl  = "";
    public $permalink  = "";
    public $theme      = "";
    public $config     = array();
    public $fonts      = array();
    public $options    = array();
    public $categories = array();

	public $meta       = array();
	public $attribute  = array();

	private $google    = array(
		'font_url' => '//fonts.googleapis.com/css?family=',
	);

    /**
     * Class constructor
     * Initiates various WP hooks that we need for this to actually work
     */
    function __construct()
    {
        $this->permalink       = get_option( 'permalink_structure', 'none' );
        $this->theme           = wp_get_theme();
        $this->config          = get_option( 'simple_customize_settings', array() );
		$this->google['fonts'] = get_option( 'simple_customize_google_fonts', '' );
	    $this->debug           = ( defined( 'WP_DEBUG' ) ? WP_DEBUG : false );

        add_action( 'customize_register', array( $this, 'build' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'style' ), ( defined( 'PHP_INT_MAX' ) ? PHP_INT_MAX : 9999 ) );
        add_action( 'wp_head', array( $this, 'wp_head_css' ) );
        add_action( 'customize_preview_init', array( $this, 'style_customize' ) );
        add_action( 'init', array( $this, 'init_build' ) );

	    add_action( 'init', array( $this, 'custom_post_type' ), 5 );
		add_action( 'init', array( $this, 'options_forms' ) );
	    add_action( 'init', array( $this, 'maybe_update_plugin' ), 20 );
	    add_action( 'init', array( $this, 'maybe_reload_css_cache' ) );
	    add_action( 'init', array( $this, 'getSettings' ), 20 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 15 );

        add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_js' ) );
        add_action( 'customize_preview_init', array( $this, 'customize_js_preview' ) );

        add_action( 'wp_ajax_simple-customize-new-object', array( $this, 'customize_ajax_add' ) );
		add_action( 'wp_ajax_simple-customize', array( $this, 'ajax_handler' ) );

        add_action( 'plugins_loaded', array( $this, 'load_i18n' ) );
        add_action( 'admin_menu', array( $this, 'populate_menu' ) );

	    add_action( 'customize_save_after', array( $this, 'cache_css' ) );
    }

	/**
	 * Function for checking if the plugin has been updated
	 * Also handles migrating old customizations to our new format
	 *
	 * @return void
	 */
	function maybe_update_plugin() {
		//  Make sure we only run the updater if there's truly a need on subsequent runs
		if ( version_compare( $this->version, get_option( 'simple-customize-version', 0 ), '>' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

			if ( version_compare( get_option( 'simple-customize-version', 0 ), '1.5', '>=' ) && version_compare( get_option( 'simple-customize-version', 0 ), '1.6.6', '<' ) ) {
				/*
				 * Run migrations, WordPress 4.7 changed the customizer to not support integer-only theme option names
				 */
				$entries  = get_posts( array(
					'posts_per_page' => -1,
					'post_type'      => 'simple-customize',
				) );

				$mods = array();

				foreach ( $entries AS $entry ) {
					$meta  = get_post_meta( $entry->ID );
					$theme = $meta['_simple_customize_theme'][0];

					if ( ! isset( $mods[ $theme ] ) ) {
						$mods[ $theme ] = get_option( "theme_mods_" . $theme, false );
					}

					if ( false === $mods[ $theme ] ) {
						// false means no theme options exist, and we can ignore this theme completely
						continue;
					}

					if ( ! isset( $mods[ $theme ][ $entry->ID ] ) ) {
						// No settings for this entry are set, so it can also be safely ignored
						continue;
					}

					// Set up the prefixed version of the theme mod with the non-prefixed version data
					$mods[ $theme ][ 'simple-customize-' . $entry->ID ] = $mods[ $theme ][ $entry->ID ];

					// Remove the previous non-prefixed value from the stored data
					unset( $mods[ $theme ][ $entry->ID ] );
				}

				// Loop over all the theme mods we've got and re-save them
				foreach( $mods AS $theme => $data ) {
					update_option( 'theme_mods_' . $theme, $data );
				}

				update_option( 'simple-customize-version', $this->version );
			}

			if ( get_option( 'simple-customize-version', 0 ) < 1.5 ) {
				$customizations = get_option( 'simple_customize', array() );
				$categories     = get_option( 'simple_customize_category', array() );
				$fonts          = get_option( 'simple_customize_fonts', array() );

				foreach ( $customizations AS $theme_name => $theme_entries ) {
					$mods = get_option( "theme_mods_" . $theme_name );

					foreach ( $theme_entries AS $theme ) {
						$new = wp_insert_post(
							array(
								'post_title'  => $theme['label'],
								'post_status' => 'publish',
								'post_type'   => 'simple-customize'
							)
						);

						update_post_meta( $new, '_simple_customize_selector', $theme['object'] );
						update_post_meta( $new, '_simple_customize_attribute', ( isset( $theme['selector_manual'] ) && ! empty( $theme['selector_manual'] ) ? $theme['selector_manual'] : $theme['selector'] ) );
						update_post_meta( $new, '_simple_customize_default', $theme['default'] );
						update_post_meta( $new, '_simple_customize_category', $theme['category'] );
						update_post_meta( $new, '_simple_customize_theme', $theme_name );

						$this_slug = sanitize_title( $theme['label'] );

						unset( $mods[ $this_slug ] );
					}

					update_option( "theme_mods_" . $theme_name, $mods );
				}

				foreach ( $categories AS $theme => $category_entries ) {
					foreach ( $category_entries AS $category ) {
						wp_insert_term(
							$category['category-label'],
							'simple-customize'
						);
					}
				}

				foreach ( $fonts AS $theme => $font_data ) {
					foreach ( $font_data AS $font ) {
						wp_insert_post(
							array(
								'post_title'  => $font,
								'post_status' => 'publish',
								'post_type'   => 's-c-font'
							)
						);
					}
				}

				update_option( 'simple-customize-version', $this->version );

				delete_option( 'simple_customize' );
				delete_option( 'simple_customize_fonts' );
				delete_option( 'simple_customize_category' );
			}
		}
	}

	/**
	 * Check if we should bust the CSS cache and rebuild it
	 *
	 * @return void
	 */
	function maybe_reload_css_cache() {
		if ( isset( $_GET['reload-customize-css'] ) && check_admin_referer( 'simple-customize-reload-css' ) ) {
			$this->cache_css();
		}
	}

	/**
	 * Load up our plugins language files
	 *
	 * @return void
	 */
    function load_i18n() {
        $i18n_dir = 'simple-customizer/i18n/';
        load_plugin_textdomain( 'simple-customizer', false, $i18n_dir );
    }

    /**
     * Convert an RGB string into hex, which is the color format used by color pickers
     *
     * @param $rgb
     *
     * @return string
     */
    function rgb2hex( $rgb )
    {
        $hex = "#";
        $hex .= str_pad( dechex( $rgb[0] ), 2, "0", STR_PAD_LEFT );
        $hex .= str_pad( dechex( $rgb[1] ), 2, "0", STR_PAD_LEFT );
        $hex .= str_pad( dechex( $rgb[2] ), 2, "0", STR_PAD_LEFT );

        return $hex; // returns the hex value including the number sign (#)
    }

	/**
	 * Pass a customizer value in to confirm if it's a hex color value
	 *
	 * @param $hex
	 *
	 * @return bool
	 */
    function confirmHex( $hex )
    {
        return ctype_xdigit( trim( ltrim( $hex, '#' ) ) );
    }

	/**
	 * Build our sub-menu page under the Appearances menu item
	 *
	 * @return void
	 */
    function populate_menu()
    {
        $page_title  = __( 'WordPress Simple Customize', 'simple-customizer' );
        $menu_title  = __( 'Simple Customize', 'simple-customizer' );
        $capability  = 'edit_theme_options';
        $parent_slug = 'themes.php';
        $menu_slug   = 'simple-customize';
        $function    = array( $this, 'options_page' );

        add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    }
    function options_page()
    {
		include_once( dirname( __FILE__ ) . '/options.php' );
    }

	/**
	 * Enqueue scripts and styles
	 *
	 * @return void
	 */
	function admin_enqueue_scripts() {
		if ( isset( $_GET['page'] ) && 'simple-customize' == $_GET['page'] ) {
			/**
			 * Load up ThickBox for displaying modals
			 */
			add_thickbox();

			wp_register_style( 'simple-customize-options', plugins_url( 'resources/css/options.css', __FILE__ ), array(), '1.2.6.1' );

			wp_enqueue_style( 'simple-customize-options' );


			/**
			 * Font specific loads
			 */
			if ( isset( $_GET['tab'] ) && 'fonts' == $_GET['tab'] ) {
				wp_register_script( 'google-webfont-api', '//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js', array() );

				wp_enqueue_script( 'google-webfont-api' );
			}

			/**
			 * Load our options specific javascript
			 */
			wp_register_script( 'simple-customize-options', plugins_url( 'resources/js/options.js', __FILE__ ), array(), '1.2.6.1' );

			wp_enqueue_script( 'simple-customize-options' );
		}
	}

	/**
	 * The AJAX handler looks for google fonts and loads them
	 * into our fonts modal on the plugin options page
	 *
	 * @return void
	 */
	function ajax_handler() {
		$google_key = self::get_plugin_settings( 'google_api' );

		// A google API key is needed for this functionality, so bail early if one does not exist.
		if ( ! $google_key ) {
			return;
		}

		if ( isset( $_POST['todo'] ) && 'refresh-fonts' == $_POST['todo'] && check_ajax_referer( 'simple-customize-get-fonts' ) ) {
			$gfonts_http = wp_remote_retrieve_body( wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $google_key ) );

			$gfonts = json_decode( $gfonts_http );

			update_option( 'simple_customize_google_fonts', $gfonts );

			$return = '';

			foreach( $gfonts->items AS $font ) {
				if ( ! isset( $font->files->regular ) ) {
					continue;
				}

				$return .= sprintf(
					'<div class="simple-customize-font" data-simple-customize-font-family-preview="\'%1$s\', %2$s" data-simple-customize-font-url="%3$s">%1$s</div>',
					$font->family,
					$font->category,
					$font->files->regular
				);
			}

			echo $return;

			die();
		}
	}

	/**
	 * Register our Custom Post Types and Taxonomies
	 *
	 * @return void
	 */
	function custom_post_type() {
		register_post_type(
			'simple-customize',
			array(
				'public'              => $this->debug,
				'has_archive'         => true,
				'hierarchical'        => true,
				'supports'            => array( 'title', 'custom-fields' ),
				'taxonomies'          => array( 'simple-customize' ),
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'can_export'          => false,
				'labels'              => array(
					'name' => __( 'Simple Customize Debug', 'simple-customizer' )
				)
			)
		);

		register_post_type(
			's-c-font',
			array(
				'public'              => $this->debug,
				'has_archive'         => true,
				'hierarchical'        => true,
				'supports'            => array( 'title', 'custom-fields' ),
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'can_export'          => false,
				'labels'              => array(
					'name' => __( 'Simple Customize Fonts Debug', 'simple-customizer' )
				)
			)
		);

		register_taxonomy(
			'simple-customize',
			'simple-customize',
			array(
				'public'  => $this->debug,
				'show_ui' => $this->debug,
				'labels'  => array(
					'name' => __( 'Categories', 'simple-customizer' )
				)
			)
		);
	}

	/**
	 * This function checks and handles any option triggered from
	 * the plugin options page
	 *
	 * @return void
	 */
	function options_forms() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		$theme = $this->theme;

		//  Importer
		if ( isset( $_POST['simple-customize-import'] ) && ! empty( $_POST['simple-customize-import'] ) && check_admin_referer( 'simple-customize-import' ) )
		{
			//  Backwards compatible check for old import formats
			if ( stristr( $_POST['simple-customize-import'], 'theme' ) ) {
				$import = json_decode( wp_unslash( $_POST['simple-customize-import'] ) );
			}
			else {
				$import = unserialize( base64_decode( wp_unslash( $_POST['simple-customize-import'] ) ) );
			}

			if ( isset( $import ) && ! empty( $import ) )
			{
				$theme = $import->theme;

				foreach ( $import->fonts AS $font ) {
					$new_font = wp_insert_post(
						array(
							'post_type'   => 's-c-font',
							'post_title'  => $font->name,
							'post_status' => $font->status
						)
					);

					update_post_meta( $new_font, '_simple_customize_font', $font->url );
				}

				foreach ( $import->categories AS $category ) {
					wp_insert_term(
						$category->name,
						'simple-customize',
						array(
							'slug' => $category->slug
						)
					);
				}
				foreach ( $import->options AS $customize ) {
					$new = wp_insert_post(
						array(
							'post_type'   => 'simple-customize',
							'post_title'  => $customize->name,
							'post_status' => 'publish'
						)
					);

					update_post_meta( $new, '_simple_customize_selector', $customize->selector );
					update_post_meta( $new, '_simple_customize_attribute', $customize->attribute );
					update_post_meta( $new, '_simple_customize_default', $customize->default );
					update_post_meta( $new, '_simple_customize_category', $customize->category );
					update_post_meta( $new, '_simple_customize_theme', $theme );

					set_theme_mod( $new, $customize->value );
				}
			}
		}

		// Edit a customize option
		if ( isset( $_POST['simple-customize-edit-slug'] ) && ! empty( $_POST['simple-customize-edit-slug'] ) && check_admin_referer( 'simple-customize-edit-selector' ) )
		{
			update_post_meta( $_POST['simple-customize-edit-slug'], '_simple_customize_selector', $_POST['edit-selector_manual'] );
			update_post_meta( $_POST['simple-customize-edit-slug'], '_simple_customize_attribute', $_POST['edit-object'] );
			update_post_meta( $_POST['simple-customize-edit-slug'], '_simple_customize_default', $_POST['edit-default'] );
			update_post_meta( $_POST['simple-customize-edit-slug'], '_simple_customize_category', $_POST['edit-category'] );
		}

		// Delete a customization
		if ( isset( $_GET['delete'] ) && ! empty( $_GET['delete'] ) && check_admin_referer( 'simple-customize-delete-selector-' . $_GET['delete'] ) )
		{
			delete_post_meta( $_GET['delete'], '_simple_customize_selector' );
			delete_post_meta( $_GET['delete'], '_simple_customize_attribute' );
			delete_post_meta( $_GET['delete'], '_simple_customize_default' );
			delete_post_meta( $_GET['delete'], '_simple_customize_category' );
			delete_post_meta( $_GET['delete'], '_simple_customize_theme' );
			wp_delete_post( $_GET['delete'] );
		}

		// Delete a customizer category
		if ( isset( $_GET['category-delete'] ) && ! empty( $_GET['category-delete'] ) && check_admin_referer( 'simple-customize-delete-category-' . $_GET['category-delete'] ) )
		{
			wp_delete_term( $_GET['category-delete'], 'simple-customize' );
		}

		// Add a new customizer category
		if ( isset( $_POST['category-label'] ) && ! empty( $_POST['category-label'] ) && check_admin_referer( 'simple-customize-add-category' ) )
		{
			wp_insert_term(
				wp_unslash( $_POST['category-label'] ),
				'simple-customize'
			);
		}

		// Add a new customizer option
		if ( isset( $_POST['label'] ) && ! empty( $_POST['label'] ) && check_admin_referer( 'simple-customize-add-selector' ) )
		{
			/**
			 * Check our default value for RGB value and convert to hex if found
			 */
			if ( substr( $_POST['default'], 0, 3 ) == 'rgb' ) {
				$_POST['default'] = $this->rgb2hex( explode( ",", str_replace( array( 'rgba(', 'rgb(', ')' ), array( '', '', '' ), $_POST['default'] ) ) );
			}

			$new = wp_insert_post(
				array(
					'post_type'   => 'simple-customize',
					'post_title'  => $_POST['label'],
					'post_status' => 'publish'
				)
			);

			update_post_meta( $new, '_simple_customize_selector', $_POST['object'] );
			update_post_meta( $new, '_simple_customize_attribute', ( ! empty( $_POST['selector_manual'] ) ? $_POST['selector_manual'] : $_POST['selector'] ) );
			update_post_meta( $new, '_simple_customize_default', $_POST['default'] );
			update_post_meta( $new, '_simple_customize_category', $_POST['category'] );
			update_post_meta( $new, '_simple_customize_theme', $this->theme->stylesheet );
		}

		// Add a new font
		if ( isset( $_POST['font-label'] ) && ! empty( $_POST['font-label'] ) && check_admin_referer( 'simple-customize-add-font' ) )
		{
			$new = wp_insert_post(
				array(
					'post_type'   => 's-c-font',
					'post_title'  => $_POST['font-label'],
					'post_status' => 'publish'
				)
			);

			update_post_meta( $new, '_simple_customize_font', $_POST['font-location'] );
		}

		// Remove a font
		if ( isset( $_GET['font-delete'] ) && ! empty( $_GET['font-delete'] ) && check_admin_referer( 'simple-customize-remove-font-' . $_GET['font-delete'] ) )
		{
			delete_post_meta( $_GET['font-delete'], '_simple_customize_font' );
			wp_delete_post( $_GET['font-delete'] );
		}

		// Enable a font
		if ( isset( $_GET['font-enable'] ) && ! empty( $_GET['font-enable'] ) && check_admin_referer( 'simple-customize-enable-font-' . $_GET['font-enable'] ) )
		{
			wp_update_post(
				array(
					'ID'          => $_GET['font-enable'],
					'post_status' => 'publish'
				)
			);
		}

		// Disable a font
		if ( isset( $_GET['font-disable'] ) && ! empty( $_GET['font-disable'] ) && check_admin_referer( 'simple-customize-disable-font-' . $_GET['font-disable'] ) )
		{
			wp_update_post(
				array(
					'ID'          => $_GET['font-disable'],
					'post_status' => 'draft'
				)
			);
		}

		// Wipe customizations for a theme
		if ( isset( $_GET['clear'] ) && ! empty( $_GET['clear'] ) && check_admin_referer( 'simple-customize-clear-' . $_GET['clear'] ) )
		{
			$entries  = get_posts( array(
				'posts_per_page' => - 1,
				'post_type'      => 'simple-customize',
				'meta_key'       => '_simple_customize_theme',
				'meta_value'     => $_GET['clear']
			) );

			foreach ( $entries AS $entry ) {
				wp_delete_post( $entry->ID, true );
			}
		}

		// Save our settings page entries
		if ( isset( $_POST['simple-customize-settings'] ) && check_admin_referer( 'simple-customize-settings' ) )
		{
			$settings = array();
			$settings['includefile']   = ( isset( $_POST['simple-customize-settings-includefile'] ) ? true : false );
			$settings['advanced']      = ( isset( $_POST['simple-customize-settings-advanced'] ) ? true : false);
			$settings['compatibility'] = ( isset( $_POST['simple-customize-settings-compatibility'] ) ? true : false );
			$settings['minified']      = ( isset( $_POST['simple-customize-settings-minified'] ) ? true : false );
			$settings['google_api']    = $_POST['simple-customize-settings-google-api-key'];

			update_option( 'simple_customize_settings', $settings );
		}
	}

    /**
     * Enqueue our customize controller for point and click functionality
     *
     * @return void
     */
    function customize_js() {
	    wp_enqueue_style( 'simple-customize-css-controls', plugin_dir_url( __FILE__ ) . '/resources/css/customizer.css', array(), $this->version );
	    wp_enqueue_script( 'simple-customize-controls-print', plugin_dir_url( __FILE__ ) . '/resources/js/customizer.js', array( 'jquery' ), $this->version, true );

        wp_localize_script(
        	'simple-customize-controls-print',
	        'SimpleCustomize',
	        array(
	        	'ajaxurl'         => admin_url( 'admin-ajax.php' ),
		        'customizerNonce' => wp_create_nonce( 'new-simple-customize' )
	        )
        );
    }

    function customize_js_preview() {
	    add_action( 'wp_enqueue_scripts', array( $this, 'customizer_js_preview_inner' ) );
    }

    function customizer_js_preview_inner() {
	    wp_enqueue_script( 'simple-customize-previewer', plugin_dir_url( __FILE__ ) . '/resources/js/customizer-preview.js', array( 'jquery' ), $this->version, true );
    }

    /**
     * Ajax storing of newly selected objects to style
     *
     * @return void
     */
    function customize_ajax_add() {
	    check_ajax_referer( 'new-simple-customize', '_nonce' );

	    /**
	     * Check our default value for RGB value and convert to hex if found
	     */
	    if ( substr( $_POST['default'], 0, 3 ) == 'rgb' ) {
		    $_POST['default'] = $this->rgb2hex( explode( ",", str_replace( array( 'rgba(', 'rgb(', ')' ), array( '', '', '' ), $_POST['default'] ) ) );
	    }

	    $new = wp_insert_post(
		    array(
			    'post_type'   => 'simple-customize',
			    'post_title'  => $_POST['label'],
			    'post_status' => 'publish'
		    )
	    );

	    update_post_meta( $new, '_simple_customize_selector', $_POST['object'] );
	    update_post_meta( $new, '_simple_customize_attribute', ( ! empty( $_POST['selector_manual'] ) ? $_POST['selector_manual'] : $_POST['selector'] ) );
	    update_post_meta( $new, '_simple_customize_default', $_POST['default'] );
	    update_post_meta( $new, '_simple_customize_category', $_POST['category'] );
	    update_post_meta( $new, '_simple_customize_theme', $this->theme->stylesheet );

        exit();
    }

    /**
     * Add a section or setting to the Customize screen
     *
     * @param string $name Name your section or setting (should be unique)
     * @param string $type The type of the option being added (section or setting)
     * @param array $args Arguments accepted are the ones normally accepted by the WP Customize API
     *
     * @return bool
     */
    function add( $name, $type, $args = array() )
    {
        /**
         * First we set the name, this is an always existent constant, and it's nice to have it as the first data point
         * We make sure it's unique, if not we append -%d
         */
        $name_original = $name;
        $iteration = 0;
        while( in_array( $name, $this->settings ) )
        {
            $iteration++;
            $name = $name_original . '-' . $iteration;
        }

        $array = array(
            'name' => $name
        );

        //  Next, iterate over the arguments array and insert them accordingly
        foreach ( $args AS $item => $data )
        {
            $array[ $item ] = $data;
        }

        //  Finally, enter the data into the appropriate data container
        switch ( $type )
        {
            case 'section':
                $this->sections[] = $array;
                break;
            default:
                $this->settings[] = $array;
        }

        return true;
    }

    /**
     * The build function generates our customize screen
     *
     * @param mixed $custom WP Customize class
     *
     * @return void
     */
    function build( $custom )
    {
	    //  Include our extended customizer controls
	    require_once( plugin_dir_path( __FILE__ ) . '/customizer/customizer-extend.php' );

        //  Loop through the defined sections, sections hold our settings fields so it makes sense to define these first
        foreach( $this->sections AS $section )
        {
            $custom->add_section(
                $section['name'],
                array(
                    'title'    => $section['title'],
                    'priority' => ( ! isset( $section['priority'] ) || empty( $section['priority'] ) ? 30 : $section['priority'] )
                )
            );
        }

        //  Next, generate the actual settings
        foreach( $this->settings AS $setting )
        {
	        $setting['name'] = (string) $setting['name'];

            $custom->add_setting(
                $setting['name'],
                array(
                    'default'   => ( isset( $setting['default'] ) ? $setting['default'] : '' ),
                    'transport' => 'postMessage'
                )
            );

            //  Since a setting also requires a controller, we initiate the controller straight away using the setting name as identifier.
            //  This means we won't get fatal errors for missing setting for a controller which may happen if we do this manually per setting!
            switch( $setting['type'] )
            {
	            case 'controller':
		            $custom->add_control(
			            new Simple_Customize_Controller(
				            $custom,
				            $setting['name'],
				            array(
					            'label'    => $setting['label'],
					            'section'  => $setting['section'],
					            'settings' => $setting['name']
				            )
			            )
		            );
		            break;
                case 'header':
                    $custom->add_control(
                        new WP_Customize_Header_Image_Control(
                            $custom,
                            $setting['name'],
                            array(
                                'label'    => $setting['label'],
                                'section'  => $setting['section'],
                                'settings' => $setting['name']
                            )
                        )
                    );
                    break;
                case 'background':
                    $custom->add_control(
                        new WP_Customize_Background_Image_Control(
                            $custom,
                            $setting['name'],
                            array(
                                'label'    => $setting['label'],
                                'section'  => $setting['section'],
                                'settings' => $setting['name']
                            )
                        )
                    );
                    break;
                case 'image':
                    $custom->add_control(
                        new WP_Customize_Image_Control(
                            $custom,
                            $setting['name'],
                            array(
                                'label'    => $setting['label'],
                                'section'  => $setting['section'],
                                'settings' => $setting['name']
                            )
                        )
                    );
                    break;
                case 'upload':
                    $custom->add_control(
                        new WP_Customize_Upload_Control(
                            $custom,
                            $setting['name'],
                            array(
                                'label'    => $setting['label'],
                                'section'  => $setting['section'],
                                'settings' => $setting['name']
                            )
                        )
                    );
                    break;
                case 'color':
                    $custom->add_control(
                        new WP_Customize_Color_Control(
                            $custom,
                            $setting['name'],
                            array(
                                'label'    => $setting['label'],
                                'section'  => $setting['section'],
                                'settings' => $setting['name']
                            )
                        )
                    );
                    break;
                case 'dropdown':
                    $custom->add_control(
                        $setting['name'],
                        array(
                            'label'     => $setting['label'],
                            'section'   => $setting['section'],
                            'type'      => 'select',
                            'choices'   => $setting['choices']
                        )
                    );
                    break;
                default:
                    $custom->add_control(
                        new WP_Customize_Control(
                            $custom,
                            $setting['name'],
                            array(
                                'label'    => $setting['label'],
                                'section'  => $setting['section'],
                                'settings' => $setting['name']
                            )
                        )
                    );
            }
        }
    }

    /**
     * Queue the stylesheet for our primary website
     *
     * @return void
     */
    function style() {
        if ( $this->permalink == 'none' || empty( $this->permalink ) || ( isset( $this->config['compatibility'] ) && $this->config['compatibility'] === true ) ) {
            wp_register_style( $this->theme->stylesheet . '-custom-css', home_url( '/?customize-css=' . $this->theme->stylesheet . '-custom-css.css' ), false, $this->version );
		}
        else {
            wp_register_style( $this->theme->stylesheet . '-custom-css', home_url( '/' . $this->theme->stylesheet . '-custom-css.css' ), false, $this->version );
		}

	    $fonts = get_posts( array(
		    'posts_per_page' => -1,
		    'post_type'      => 's-c-font',
		    'post_status'    => array( 'publish' )
	    ) );

	    foreach( $fonts AS $font ) {
		    $font_url = esc_url_raw( $this->google['font_url'] . str_replace( ' ', '+', $font->post_title ) );
		    wp_enqueue_style( 'font-' . sanitize_title( $font->post_title ), $font_url, false, $this->version );
	    }


        if ( ! isset( $this->config['includefile'] ) || false === $this->config['includefile'] ) {
	        wp_enqueue_style( $this->theme->stylesheet . '-custom-css' );
        }
    }

    /**
     * Queue the javascript file allowing for real time previews without reloading the frame
     *
     * @return void
     */
    function style_customize() {
        if ( $this->permalink == 'none' || empty( $this->permalink ) || ( isset( $this->config['compatibility'] ) && $this->config['compatibility'] === true ) ) {
            wp_register_script( $this->theme->stylesheet . '-custom-js', home_url( '?customize-js=' . $this->theme->stylesheet . '-custom-css.js' ), array( 'jquery', 'customize-preview' ), $this->version, true );
        }
        else {
            wp_register_script( $this->theme->stylesheet . '-custom-js', home_url( '/' . $this->theme->stylesheet . '-custom-css.js' ), array( 'jquery', 'customize-preview' ), $this->version, true );
        }

        wp_enqueue_script( $this->theme->stylesheet . '-custom-js' );
    }

    /**
     * Our build function for init, this is kind of magical
     *
     * We load our php scripts (that generate the javascript and css file) in using this function.
     *
     * Older browsers often define the type of file by file extension and ignores MIME type, this will help them understand what data they should display.
     *
     * @return void
     */
    function init_build() {
        //  If the current URL requested is our customized css one, serve it up nicely with an include then kill any further output so WP doesn't also load twice
        if ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] == home_url( '/' . $this->theme->stylesheet . '-custom-css.css?ver=' . $this->version, ( is_ssl() ? 'https' : 'http' ) ) || ( ( $this->permalink == 'none' || empty( $this->permalink ) || ( isset( $this->config['compatibility'] ) && $this->config['compatibility'] === true ) ) && isset( $_GET['customize-css'] ) && $_GET['customize-css'] == $this->theme->stylesheet . '-custom-css.css' ) )
        {

            header( 'Content-Type: text/css' );
            echo $this->generate_css();

            die();
        }

        //  If the current URL requested is our customized js one, serve it up nicely with an include then kill any further output so WP doesn't also load twice
        if ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] == home_url( '/' . $this->theme->stylesheet . '-custom-css.js?ver=' . $this->version, ( is_ssl() ? 'https' : 'http' ) ) || ( ( $this->permalink == 'none' || empty( $this->permalink ) || ( isset( $this->config['compatibility'] ) && $this->config['compatibility'] === true ) ) && isset( $_GET['customize-js'] ) && $_GET['customize-js'] == $this->theme->stylesheet . '-custom-css.js' ) )
        {
            header( 'Content-Type: text/javascript' );
            $this->generate_js();

            die();
        }
    }

    /**
     * Output our css directly in the <head> of our site (requires the theme to have wp_head() properly set)
     *
     * Used to reduce the amount of requests made to the users server
     *
     * @return void
     */
    function wp_head_css() {
        if ( isset( $this->config['includefile'] ) && true === $this->config['includefile'] )
        {
            echo '<style type="text/css">';
	        echo "\n";

	        echo '/* CSS Block generated by Simple Customize */';
	        echo "\n";

            echo $this->generate_css();

            echo '</style>';
	        echo "\n";
        }
    }

	/**
	 * Fetch a specific, or all, settings for the plugin.
	 *
	 * @param string $setting The setting to fetch
	 *
	 * @return mixed|null
	 */
    public static function get_plugin_settings( $setting = null ) {
    	$settings = get_option( 'simple_customize_settings', array() );

    	if ( null === $setting ) {
		    return $settings;
	    }

        if ( isset( $settings[ $setting ] ) ) {
            return $settings[ $setting ];
	    }

        return null;
    }

    /**
     * CSS File generator code
     * Used in <themename>-custom-css.css
     *
     * @param bool $force
     *
     * @return string
     */
    function generate_css( $force = false, $theme = null )
    {
	    if ( empty( $theme ) ) {
		    $theme = $this->theme->stylesheet;
	    }
	    if ( $force || false === ( $css = get_transient( 'simple-customize-css' ) ) ) {
		    $settings = get_option( 'simple_customize_settings', array() );
		    $css      = '';
		    $entries  = get_posts( array(
			    'posts_per_page' => - 1,
			    'post_type'      => 'simple-customize',
			    'meta_key'       => '_simple_customize_theme',
			    'meta_value'     => $theme
		    ) );

		    foreach ( $entries AS $entry ) {
			    $meta = get_post_meta( $entry->ID );

			    switch ( $meta['_simple_customize_attribute'][0] ) {
				    case 'background-image':
					    $css .= sprintf(
						    "%s { %s: url( '%s' ); }",
						    $meta['_simple_customize_selector'][0],
						    $meta['_simple_customize_attribute'][0],
						    get_theme_mod( 'simple-customize-' . $entry->ID, $meta['_simple_customize_default'][0] )
					    );
					    break;
				    default:
					    $css .= sprintf(
						    "%s { %s: %s; }",
						    $meta['_simple_customize_selector'][0],
						    $meta['_simple_customize_attribute'][0],
						    get_theme_mod( 'simple-customize-' . $entry->ID, $meta['_simple_customize_default'][0] )
					    );
			    }

			    if ( isset( $settings['advanced'] ) && true === $settings['advanced'] ) {
				    $css .= sprintf(
					    "/* Generated by: %d - %s */",
					    $entry->ID,
					    $entry->post_title
				    );
			    }

			    if ( ( isset( $settings['advanced'] ) && true === $settings['advanced'] ) || ( ! isset( $settings['minified'] ) || false === $settings['minified'] ) ) {
				    $css .= "\n";
			    }
		    }
	    }

	    return $css;
    }

	/**
	 * Force reload the CSS cache
	 *
	 * @return void
	 */
	function cache_css() {
		set_transient( 'simple-customize-css', $this->generate_css( true ) );
	}

    /**
     * JavaScript file generator code
     * Used in <themename>-custom-css.js for the responsive live previews
     *
     * @return void
     */
    function generate_js()
    {
        echo 'jQuery(document).ready(function ($) {';

	    $entries = get_posts( array(
		    'posts_per_page' => -1,
		    'post_type'      => 'simple-customize',
		    'meta_key'       => '_simple_customize_theme',
		    'meta_value'     => $this->theme->stylesheet
	    ) );

	    $entries = apply_filters( 'simple-customizer-entry-list', $entries );

	    foreach( $entries AS $entry )
	    {
		    $meta = get_post_meta( $entry->ID );

            echo "
                wp.customize( 'simple-customize-" . $entry->ID . "', function( value ) {
                    value.bind( function( newval ) {
                        $('" . $meta['_simple_customize_selector'][0] . "').css('" . $meta['_simple_customize_attribute'][0] . "', newval );
                    } );
                } );
                \n\n
            ";
        }

        echo '});';
    }

    /**
     * Write the core js file, the biggest section to this is the "add new element" button hack, since customize has no hooks for modifying the actual customize accordion
     *
     * @return void
     */
    function generate_core_js()
    {
        require_once( dirname( __FILE__ ) . '/customizer/customizer-js.php' );
    }

    /**
     * Upon page load, get our defined options and add settings for them
     *
     * @return void
     */
    function getSettings() {
	    $this->add(
		    'simple-customize-controller',
		    'section',
		    array(
			    'title'    => __( 'Simple Customize' ),
			    'priority' => 9999
		    )
	    );

	    $args = array(
		    'label'    => __( 'Simple Customize' ),
		    'type'     => 'controller',
		    'section'  => 'simple-customize-controller'
	    );

	    $this->add(
		    'simple-customize-controller',
		    'controller',
		    $args
	    );


	    $terms = get_terms(
		    'simple-customize',
		    array(
			    'hide_empty' => false
		    )
	    );

	    foreach( $terms AS $term )
	    {
            $this->add(
                $term->slug,
                'section',
                array(
                    'title' => $term->name
                )
            );
        }

	    $entries = get_posts( array(
		    'posts_per_page' => -1,
		    'post_type'      => 'simple-customize',
		    'meta_key'       => '_simple_customize_theme',
		    'meta_value'     => $this->theme->stylesheet
	    ) );

	    foreach( $entries AS $entry )
	    {
		    $this->meta = get_post_meta( $entry->ID );
            /**
             * Check that required fields that we can't auto-populate are filled in, if not we ignore this element
             */
            if ( empty( $entry->post_title ) || ! isset( $this->meta['_simple_customize_selector'][0] ) || empty( $this->meta['_simple_customize_selector'][0] ) ) {
                continue;
            }

            /**
             * Default the section if it isn't set
             */
            if ( empty( $this->meta['_simple_customize_category'][0] ) ) {
                $this->meta['category'] = 'colors';
            }

		    $this->attribute['args'] = array();

            switch ( $this->meta['_simple_customize_attribute'][0] )
            {
				case has_action( 'simple-customize-' . $this->meta['_simple_customize_attribute'][0] ):
					do_action( 'simple-customize-' . $this->meta['_simple_customize_attribute'][0] );
					break;
                default:
	                $this->attribute['type'] = 'text';
            }

		    if ( ! isset( $this->attribute['type'] ) || empty( $this->attribute['type'] ) ) {
			    $this->attribute['type'] = 'text';
		    }

            // If it's a color code, there's not much else we can do but get a color picker
            if ( $this->confirmHex( $this->meta['_simple_customize_default'][0] ) ) {
	            $this->attribute['type'] = 'color';
            }

            // If we are in advanced mode, ignore previous options and just make it a text box
            if ( isset( $this->config['advanced'] ) && true === $this->config['advanced'] ) {
	            $this->attribute['type'] = 'text';
            }

		    $this->attribute['args'] = array_merge( array(
                'label'    => $entry->post_title,
                'object'   => $this->meta['_simple_customize_attribute'][0],
                'selector' => $this->meta['_simple_customize_selector'][0],
                'default'  => $this->meta['_simple_customize_default'][0],
                'type'     => $this->attribute['type'],
                'section'  => $this->meta['_simple_customize_category'][0]
            ), $this->attribute['args'] );

            $this->add(
	            'simple-customize-' . $entry->ID,
                'color',
	            $this->attribute['args']
            );
        }
    }

	/**
	 * Get a loop of all our plugin customization entries
	 * They are all in a Custom Post Type so we just return
	 * the WP_Query object with them all
	 *
	 * @return WP_Query
	 */
	function get_customize() {
		$entries = new WP_Query( array(
			'post_type'      => 'simple-customize',
			'posts_per_page' => -1
		) );

		return $entries;
	}
}

/**
 * Load dependency files
 */
include_once( plugin_dir_path( __FILE__ ) . '/customizer/customizer-attributes.php' );

/**
 * Instantiate the plugin
 */
global $simple_customize;
$simple_customize = new simple_customize();
$simple_customize->pluginurl = plugins_url( '/', __FILE__ );