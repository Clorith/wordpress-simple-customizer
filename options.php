<?php
    $theme = wp_get_theme();

    // Code relating to customize settings
    if ( isset( $_GET['delete'] ) && ! empty( $_GET['delete'] ) )
    {
        $options = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );
        $options_update = array(
            $theme->stylesheet => array()
        );

        foreach( $options[$theme->stylesheet] AS $option )
        {
            if ( sanitize_title( $option['label'] ) != $_GET['delete'] )
                $options_update[$theme->stylesheet][] = $option;
        }

        update_option( 'simple_customize', $options_update );
    }
    if ( isset( $_GET['category-delete'] ) && ! empty( $_GET['category-delete'] ) )
    {
        $options = get_option( 'simple_customize_category', array( $theme->stylesheet => array() ) );
        $options_update = array(
            $theme->stylesheet => array()
        );

        foreach( $options[$theme->stylesheet] AS $option )
        {
            if ( sanitize_title( $option['category-label'] ) != $_GET['category-delete'] )
                $options_update[$theme->stylesheet][] = $option;
        }

        update_option( 'simple_customize_category', $options_update );
    }

    if ( isset( $_POST['category-label'] ) && ! empty( $_POST['category-label'] ) )
    {
        $categories = get_option( 'simple_customize_category', array( $theme->stylesheet => array() ) );

        $categories[$theme->stylesheet][] = $_POST;

        if ( ! add_option( 'simple_customize_category', $categories, '', 'no' ) )
            update_option( 'simple_customize_category', $categories );
    }

    if ( isset( $_POST['label'] ) && ! empty( $_POST['label'] ) )
    {
        $options = get_option( 'simple_customize', array( $theme->stylesheet => array()) );

        /**
         * Check our default value for RGB value and convert to hex if found
         */
        if ( substr( $_POST['default'], 0, 3 ) == 'rgb' )
            $_POST['default'] = $this->rgb2hex( explode( ",", str_replace( array( 'rgba(', 'rgb(', ')' ), array( '', '', '' ), $_POST['default'] ) ) );

        $options[$theme->stylesheet][] = $_POST;

        if ( ! add_option( 'simple_customize', $options, '', 'no' ) )
            update_option( 'simple_customize', $options );
    }

    //  Code relating to Fonts
    if ( isset( $_POST['font-label'] ) && ! empty( $_POST['font-label'] ) )
    {
        $fonts = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );

        $fonts[$theme->stylesheet][] = $_POST;

        if ( ! add_option( 'simple_customize_fonts', $fonts, '', 'no' ) )
            update_option( 'simple_customize_fonts', $fonts );
    }
    if ( isset( $_GET['font-delete'] ) && ! empty( $_GET['font-delete'] ) )
    {
        $options = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );
        $options_update = array(
            $theme->stylesheet => array()
        );

        foreach( $options[$theme->stylesheet] AS $option )
        {
            if ( sanitize_title( $option['font-label'] ) != $_GET['font-delete'] )
                $options_update[$theme->stylesheet][] = $option;
        }

        update_option( 'simple_customize_fonts', $options_update );
    }

    //  Dataset options
    if ( isset( $_GET['clear'] ) && ! empty( $_GET['clear'] ) )
    {
        $options = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );
        unset( $options[$_GET['clear']] );

        update_option( 'simple_customize', $options );
    }

    //  Our settings, which will be reused across the options pages
    $options = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );
    $categories = get_option( 'simple_customize_category', array( $theme->stylesheet => array() ) );
    $fonts = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );
?>
<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php
            $tabs = array(
                'home'       => __( 'Simple Customize', 'simple-customize-plugin' ),
                'fonts'      => __( 'Fonts', 'simple-customize-plugin' ),
                'datasets'   => __( 'Datasets', 'simple-customize-plugin' ),
                'help'       => __( 'Help', 'simple-customize-plugin' )
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
    </h2>

    <br />

    <?php
        include_once( plugin_dir_path( __FILE__ ) . '/options/' . $current_tab . '.php' );
    ?>
</div>