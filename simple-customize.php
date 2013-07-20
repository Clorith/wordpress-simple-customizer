<?php
/**
 * Plugin Name: Simple Customizer
 * Plugin URI: http://www.mrstk.net/wordpress-simple-customize/
 * Description: Customize the look of your themes without modifying any code, just point and click on the element you wish to change.
 * Version: 1.0.0
 * Author: Clorith
 * Author URI: http://www.mrstk.net
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

class simple_customize
{
    /**
     * @var array $sections Used for storing our added sections before displaying them
     * @var array $settings The settings we wish to implement
     */
    private $sections = array();
    public $settings = array();
    public $pluginurl = "";

    /**
     * Class constructor
     * Initiates various WP hooks that we need for this to actually work
     */
    function __construct()
    {
        add_action( 'customize_register', array( $this, 'build' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'style' ) );
        add_action( 'customize_preview_init', array( $this, 'style_customize' ) );
        add_action( 'init', array( $this, 'init_build' ) );

        add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_js' ) );
        add_action( 'wp_ajax_simple-customize-new-object', array( $this, 'customize_ajax_add' ) );

        add_action( 'admin_menu', array( $this, 'populate_menu' ) );
    }

    /**
     * Convert an RGB string into hex, which is the color format used by colorpickers
     *
     * @param $rgb
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

    function confirmHex( $hex )
    {
        return ctype_xdigit( trim( ltrim( $hex, '#' ) ) );
    }

    function populate_menu()
    {
        $page_title  = __( 'WordPress Simple Customize', 'simple-customize-plugin' );
        $menu_title  = __( 'Simple Customize', 'simple-customize-plugin' );
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
     * Enqueue our customize controller for point nad click functionality
     */
    function customize_js() {
        $permalink = get_option( 'permalink_structure', 'none' );

        if ( $permalink == 'none' || empty( $permalink ) )
            wp_register_script( 'css-controls-print', home_url( '?customize=custom-css-core.js' ), array( 'jquery' ), '1.0.0', true );
        else
            wp_register_script( 'css-controls-print', home_url( '/custom-css-core.js' ), array( 'jquery' ), '1.0.0', true );

        wp_enqueue_script( 'css-controls-print' );

        wp_localize_script( 'css-controls-print', 'SimpleCustomize', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
     * Ajax storing of newly selected objects to style
     */
    function customize_ajax_add() {
        $theme = wp_get_theme();
        $options = get_option( 'simple_customize_' . $theme->stylesheet, array() );

        /**
         * Check our default value for RGB value and convert to hex if found
         */
        if ( substr( $_POST['default'], 0, 3 ) == 'rgb' )
            $_POST['default'] = $this->rgb2hex( explode( ",", str_replace( array( 'rgba(', 'rgb(', ')' ), array( '', '', '' ), $_POST['default'] ) ) );

        $options[] = $_POST;

        if ( ! add_option( 'simple_customize_' . $theme->stylesheet, $options, '', 'no' ) )
            update_option( 'simple_customize_' . $theme->stylesheet, $options );

        exit();
    }

    /**
     * Add a section or setting to the Customize screen
     *
     * @param string $name Name your section or setting (should be unique)
     * @param string $type The type of the option being added (section or setting)
     * @param array $args Arguments accepted are the ones normally accepted by the WP Customize API
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
            $array[$item] = $data;
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
     */
    function build( $custom )
    {
        //  Loop through the defined sections, sections hold our settings fields so it makes sense to define these first
        foreach( $this->sections AS $section )
        {
            $custom->add_section(
                $section['name'],
                array(
                    'title'    => __( $section['title'] ),
                    'priority' => ( ! isset( $section['priority'] ) || empty( $section['priority'] ) ? 30 : $section['priority'] )
                )
            );
        }

        //  Next, generate the actual settings
        foreach( $this->settings AS $setting )
        {
            $custom->add_setting(
                $setting['name'],
                array(
                    'default'   => $setting['default'],
                    'transport' => 'postMessage'
                )
            );

            //  Since a setting also requires a controller, we initiate the controller straight away using the setting name as identifier.
            //  This means we won't get fatal errors for missing setting for a controller which may happen if we do this manually per setting!
            switch( $setting['type'] )
            {
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
     */
    function style() {
        $theme = wp_get_theme();
        $permalink = get_option( 'permalink_structure', 'none' );

        if ( $permalink == 'none' || empty( $permalink ) )
            wp_register_style( $theme->stylesheet . '-custom-css', home_url( '/?customize-css=' . $theme->stylesheet . '-custom-css.css' ), false, '1.0.0' );
        else
            wp_register_style( $theme->stylesheet . '-custom-css', home_url( '/' . $theme->stylesheet . '-custom-css.css' ), false, '1.0.0' );

        $fonts = get_option( 'simple_customize_fonts', array() );
        foreach ( $fonts AS $font )
        {
            wp_enqueue_style( 'font-' . sanitize_title( $font['font-label'] ), $font['font-location'] );
        }

        wp_enqueue_style( $theme->stylesheet . '-custom-css' );
    }

    /**
     * Queue the javascript file allowing for real time previews without reloading the frame
     */
    function style_customize() {
        $theme = wp_get_theme();
        $permalink = get_option( 'permalink_structure', 'none' );

        if ( $permalink == 'none' || empty( $permalink ) )
            wp_register_script( $theme->stylesheet . '-custom-js', home_url( '?customize-js=' . $theme->stylesheet . '-custom-css.js' ), array( 'jquery', 'customize-preview' ), '1.0.0', true );
        else
            wp_register_script( $theme->stylesheet . '-custom-js', home_url( '/' . $theme->stylesheet . '-custom-css.js' ), array( 'jquery', 'customize-preview' ), '1.0.0', true );

        wp_enqueue_script( $theme->stylesheet . '-custom-js' );
    }

    /**
     * Our build function for init, this is kind of magical
     *
     * We load our php scripts (that generate the javascript and css file) in using this function.
     *
     * Older browsers often define the type of file by file extension and ignores MIME type, this will help them understand what data they should display.
     */
    function init_build() {
        $theme = wp_get_theme();

        $permalink = get_option( 'permalink_structure', 'none' );

        //  If the current URL requested is our customized css one, serve it up nicely with an include then kill any further output so WP doesn't also load twice
        if ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']  == home_url( '/' . $theme->stylesheet . '-custom-css.css?ver=1.0.0', ( is_ssl() ? 'https' : 'http' ) ) || ( ( $permalink == 'none' || empty( $permalink ) ) && isset( $_GET['customize-css'] ) && $_GET['customize-css'] == $theme->stylesheet . '-custom-css.css' ) )
        {

            header( 'Content-Type: text/css' );
            $this->generate_css();

            die();
        }

        //  If the current URL requested is our customized js one, serve it up nicely with an include then kill any further output so WP doesn't also load twice
        if ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']  == home_url( '/' . $theme->stylesheet . '-custom-css.js?ver=1.0.0', ( is_ssl() ? 'https' : 'http' ) ) || ( ( $permalink == 'none' || empty( $permalink ) ) && isset( $_GET['customize-js'] ) && $_GET['customize-js'] == $theme->stylesheet . '-custom-css.js' ) )
        {
            header( 'Content-Type: text/javascript' );
            $this->generate_js();

            die();
        }

        //  If we are requesting the core javascript, go ahead and feed it
        if ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']  == home_url( '/custom-css-core.js?ver=1.0.0', ( is_ssl() ? 'https' : 'http' ) ) || ( ( $permalink == 'none' || empty( $permalink ) ) && isset( $_GET['customize'] ) && $_GET['customize'] == 'custom-css-core.js' ) )
        {
            header( 'Content-Type: text/javascript' );
            $this->generate_core_js();

            die();
        }
    }

    /**
     * CSS File generator code
     * Used in <themename>-custom-css.css
     */
    function generate_css()
    {
        $theme = wp_get_theme();

        $options = get_option( 'simple_customize_' . $theme->stylesheet, array() );

        foreach( $options AS $option )
        {
            echo $option['object'] . " { " . ( ! empty( $option['selector_manual'] ) ? $option['selector_manual'] : $option['selector'] ) . ": " . get_theme_mod( sanitize_title( $option['label'] ), $option['default'] ) . "; }\n";
        }
    }

    /**
     * JavaScript file generator code
     * Used in <themename>-custom-css.js for the responsive live previews
     */
    function generate_js()
    {
        echo 'jQuery(document).ready(function ($) {';

        foreach( $this->settings AS $setting )
        {
            echo "
                wp.customize( '" . $setting['name'] . "', function( value ) {
                    value.bind( function( newval ) {
                        $('" . $setting['object'] . "').css('" . $setting['selector'] . "', newval );
                    } );
                } );
                \n\n
            ";
        }

        echo '});';
    }

    /**
     * Write the core js file, the biggest section to this is the "add new element" button hack, since customize has no hooks for modifying the actual customize accordion
     */
    function generate_core_js()
    {
        ?>

        var appendHTML = ' \
            <li id="customize-section-simple_customize_control" class="control-section accordion-section customize-section open">\
                <h3 class="accordion-section-title customize-section-title" tabindex="0" title="Define customizable areas of your site with point and click"><?php _e( 'Simple Customize', 'simple-customize-plugin' ); ?></h3>\
                <ul class="accordion-section-content customize-section-content">\
                    <li id="customize conrol-simple_customize_control" class="customize-control customize-control-text">\
                        <label>\
                            <span class="customize-control-title"><?php _e( 'Name / Label', 'simple-customize-plugin' ); ?></span>\
                            <div class="customize-control-content">\
                                <input type="text" value="" id="simple_customize_label">\
                            </div>\
                        </label>\
                        <label>\
                            <span class="customize-control-title"><?php _e( 'Category', 'simple-customize-plugin' ); ?></span>\
                            <div class="customize-control-content">\
                                <select id="simple_customize_category" style="width:98%;">\
                                    <optgroup label="<?php _e( 'WordPress defaults', 'simple-customize-plugin' ); ?>">\
                                        <option value="title_tagline"><?php _e( 'Site Title & Tagline', 'simple-customize-plugin' ); ?></option>\
                                        <option value="colors" selected="selected"><?php _e( 'Colors', 'simple-customize-plugin' ); ?></option>\
                                        <option value="header_image"><?php _e( 'Header Image', 'simple-customize-plugin' ); ?></option>\
                                        <option value="background_image"><?php _e( 'Background Image', 'simple-customize-plugin' ); ?></option>\
                                        <option value="nav"><?php _e( 'Navigation', 'simple-customize-plugin' ); ?></option>\
                                        <option value="static_front_page"><?php _e( 'Static Front Page', 'simple-customize-plugin' ); ?></option>\
                                    </optgroup>\
                                    <optgroup label="<?php _e( 'Your categories', 'simple-customize-plugin' ); ?>">\
                                        <?php
                                            $categories = get_option( 'simple_customize_category', array() );

                                            foreach( $categories AS $category )
                                            {
                                                echo '<option value="' . sanitize_title( $category['category-label'] ) . '">' . $category['category-label'] . '</option>\\';
                                            }
                                        ?>
                                    </optgroup>\
                                </select>\
                            </div>\
                        </label>\
                        <label>\
                            <span class="customize-control-title"><?php _e( 'CSS selector', 'simple-customize-plugin' ); ?></span>\
                            <div class="customize-control-content">\
                                <input type="text" value="" id="simple_customize_selected">\
                            </div>\
                        </label>\
                        <label>\
                            <span class="customize-control-title"><?php _e( 'What to customize', 'simple-customize-plugin' ); ?></span>\
                            <div class="customize-control-content">\
                                <select id="simple_customize_selector_auto" style="width:98%;"></select>\
                            </div>\
                        </label>\
                        <label>\
                            <div class="customize-control-content">\
                                <input type="text" value="" id="simple_customize_selector_manual">\
                            </div>\
                        </label>\
                        <label>\
                            <span class="customize-control-title"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></span>\
                            <div class="customize-control-content">\
                                <input type="text" value="" id="simple_customize_default">\
                            </div>\
                        </label>\
                        <label>\
                            <span class="customize-control-title">&nbsp;</span>\
                            <div class="customize-control-content">\
                                <div class="simple-select-info updated" style="display: none;"><strong><?php _e( 'Select your element', 'simple-customize-plugin' ); ?></strong><br /><?php _e( 'You have started the customize process, please click the element you wish to customize in the preview window.', 'simple-customize-plugin' ); ?></div>\
                                <div class="simple-select-button">\
                                    <button type="button" class="button" id="simple_customize_selector" style="width:49%; text-align: center; background: transparent url( \'<?php echo plugins_url( 'resources/images/search.png', __FILE__ ); ?>\' ) 5px center no-repeat;"><?php _e( 'Find element', 'simple-customize-plugin' ); ?></button>\
                                    <button class="button button-primary" id="simple_customize_store" style="width:49%; text-align: center;"><?php _e( 'Add element', 'simple-customize-plugin' ); ?></button>\
                                    <br /><br />\
                                    <a href="<?php echo admin_url( 'themes.php?page=simple-customize' ); ?>" class="button button-primary" style="width: 100%;text-align:center;"><?php _e( 'Plugin options', 'simple-customize-plugin' ); ?></a>\
                                </div>\
                                <br /><br />\
                                <a href="<?php echo admin_url( 'themes.php?page=simple-customize&amp;tab=help' ); ?>" class="button" style="text-align: center; float: right; background: transparent url( \'<?php echo plugins_url( 'resources/images/help.png', __FILE__ ); ?>\' ) 5px center no-repeat; padding-left: 25px;"><?php _e( 'Help', 'simple-customize-plugin' ); ?></a>\
                            </div>\
                        </label>\
                    </li>\
                </ul>\
            </li>\
        ';

        jQuery(document).ready(function ($) {
            simple_select = false;

            $("#customize-theme-controls > ul").append( appendHTML );

            function iframeDetect()
            {
                if ( $("iframe").length > 0 )
                {
                    clearInterval(iframeDetector);

                    $("iframe").contents().on('click', function (e) {
                        if (simple_select) {
                            var theseParents = $.map($(e.target).parents().not('html').not('body'), function(elm) {
                                var entry = elm.tagName.toLowerCase();
                                if (elm.className) {
                                    entry += "." + elm.className.replace(/ /g, '.');
                                }
                                return entry
                            });

                            theseParents.reverse();
                            theseParents = theseParents.join(" ");

                            simple_select = false;

                            $(".simple-select-button").show();
                            $(".simple-select-info").hide();

                            $("#simple_customize_selected").val(theseParents);
                            $("#simple_customize_label").val($(e.target).text().trim());
                            $("#simple_customize_default").val($(e.target).css('color'));

                            var styled = window.getComputedStyle(e.target);
                            $("#simple_customize_selector_auto").find('option').remove().end();

                            for(var i = 0; i < styled.length; i++) {
                                $("#simple_customize_selector_auto").append('<option value="' + styled[i] + '">' + styled[i] + '</option>');
                            }
                        }
                    });
                }
            }

            $("#simple_customize_selector_auto").change(function (e) {
                $("#simple_customize_default").val($("iframe").contents().find($("#simple_customize_selected").val()).css($(this).val()));
            });

            var iframeDetector = setInterval( iframeDetect, 200 );

            $("#simple_customize_selector").on('click', function (e) {
                simple_select = true;
                $(".simple-select-button").hide();
                $(".simple-select-info").show();
            });

            $(".control-section").click(function (e) {
                $(".control-section").removeClass('open');
                $(this).addClass('open');
            });

            $("#simple_customize_store").click(function (e) {
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
                        category: $("#simple_customize_category").val()
                    }, function (response) {
                        $("#customize-controls").submit();
                    }
                );
            });
        });

        <?php
    }

    /**
     * Upon page load, get our defined options and add settings for them
     */
    function getSettings() {
        $theme = wp_get_theme();

        $categories = get_option( 'simple_customize_category_' . $theme->stylesheet, array() );

        foreach( $categories AS $category )
        {
            $this->add(
                sanitize_title( $category['category-label'] ),
                'section',
                array(
                    'title' => $category['category-label']
                )
            );
        }

        $options = get_option( 'simple_customize_' . $theme->stylesheet, array() );

        foreach( $options AS $option )
        {
            $this->add(
                sanitize_title( $option['label'] ),
                'color',
                array(
                    'label'    => $option['label'],
                    'object'   => $option['object'],
                    'selector' => ( ! empty( $option['selector_manual'] ) ? $option['selector_manual'] : $option['selector'] ),
                    'default'  => $option['default'],
                    'type'     => ( $this->confirmHex( $option['default'] ) ? 'color' : 'text' ) ,
                    'section'  => $option['category']
                )
            );
        }
    }
}

/**
 * Instantiate the plugin
 */
$simple_customize = new simple_customize();
$simple_customize->pluginurl = plugins_url( '/', __FILE__ );
$simple_customize->getSettings();