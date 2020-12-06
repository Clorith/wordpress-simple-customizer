<?php
	/**
	 * Prevent direct access to files
	 */
	if ( ! defined( 'WP_ADMIN' ) ) {
		die();
	}
    $theme = wp_get_theme();

    //  Our settings, which will be reused across the options pages
    $options      = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );
    $categories   = get_option( 'simple_customize_category', array( $theme->stylesheet => array() ) );
    $fonts        = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );
    $settings     = get_option( 'simple_customize_settings', array() );
	$google_fonts = get_option( 'simple_customize_google_fonts', array() );
?>
<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php
            $tabs = array(
                'home'       => __( 'Customizations', 'simple-customize-plugin' ),
                'fonts'      => __( 'Fonts', 'simple-customize-plugin' ),
                'datasets'   => __( 'Data', 'simple-customize-plugin' ),
                'settings'   => __( 'Settings', 'simple-customize-plugin' )
            );

            $current_tab = ( ! isset( $_GET['tab'] ) ? 'home' : $_GET['tab'] );

            foreach( $tabs AS $tab => $name )
            {
                echo '
                    <a class="nav-tab' . ( $current_tab == $tab ? ' nav-tab-active' : '' ) . '" href="?page=simple-customize&amp;tab=' . $tab . '">
                        ' . $name . '
                    </a>
                ';
            }
        ?>

        <a href="<?php echo admin_url( 'customize.php' ); ?>" class="add-new-h2"><?php _e( 'Customize your theme', 'simple-customize-plugin' ); ?></a>

        <a href="<?php echo wp_nonce_url( 'themes.php?page=simple-customize&tab=home&reload-customize-css=true', 'simple-customize-reload-css' ); ?>" class="add-new-h2"><?php _e( 'Reload CSS cache', 'simple-customize-plugin' ); ?></a>
    </h2>

    <br />

    <?php
        switch ( ( isset( $_GET['tab'] ) ? $_GET['tab'] : '' ) )
        {
            case 'home':
                $include = 'home.php';
                break;
            case 'fonts':
                $include = 'fonts.php';
                break;
            case 'datasets':
                $include = 'datasets.php';
                break;
            case 'settings':
                $include = 'settings.php';
                break;
            default:
                $include = 'home.php';
        }

        include_once( plugin_dir_path( __FILE__ ) . '/options/' . $include );
    ?>
</div>