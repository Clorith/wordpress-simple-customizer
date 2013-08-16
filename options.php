<?php
    $theme = wp_get_theme();

    //  Importer
    if ( isset( $_POST['simple-customize-import'] ) && ! empty( $_POST['simple-customize-import'] ) )
    {
        $import = unserialize( base64_decode( $_POST['simple-customize-import'] ) );

        if ( ! empty( $import ) )
        {
            $theme = $import['theme'];

            foreach ( $import['options'] AS $oid => $option )
            {
                set_theme_mod( sanitize_title( $option['label'] ), $option['value'] );
                unset( $import['options'][$oid]['value'] );
            }

            $options = get_option( 'simple_customize', array( $theme => array() ) );
            $options[$theme]= $import['options'];
            update_option( 'simple_customize', $options );

            $categories = get_option( 'simple_customize_category', array( $theme => array() ) );
            $categories[$theme] = $import['categories'];
            update_option( 'simple_customize_category', $categories );

            $fonts = get_option( 'simple_customize_fonts', array( $theme => array() ) );
            $fonts[$theme] = $import['fonts'];
            update_option( 'simple_customize_fonts', $fonts );
        }
    }

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
        $fonts = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );
        $fonts_update = array(
            $theme->stylesheet => array()
        );

        foreach( $fonts[$theme->stylesheet] AS $font )
        {
            if ( sanitize_title( $font['font-label'] ) != $_GET['font-delete'] )
                $fonts_update[$theme->stylesheet][] = $font;
        }

        update_option( 'simple_customize_fonts', $fonts_update );
    }
    if ( isset( $_GET['font-disable'] ) && ! empty( $_GET['font-disable'] ) )
    {
        $fonts = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );

        foreach( $fonts[$theme->stylesheet] AS $fid => $font )
        {
            if ( sanitize_title( $font['font-label'] ) == $_GET['font-disable'] )
                $fonts[$theme->stylesheet][$fid]['font-status'] = 'disabled';
        }

        update_option( 'simple_customize_fonts', $fonts );
    }
    if ( isset( $_GET['font-enable'] ) && ! empty( $_GET['font-enable'] ) )
    {
        $fonts = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );

        foreach( $fonts[$theme->stylesheet] AS $fid => $font )
        {
            if ( sanitize_title( $font['font-label'] ) == $_GET['font-enable'] )
                $fonts[$theme->stylesheet][$fid]['font-status'] = 'enabled';
        }

        update_option( 'simple_customize_fonts', $fonts );
    }

    //  Dataset options
    if ( isset( $_GET['clear'] ) && ! empty( $_GET['clear'] ) )
    {
        $options    = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );
        $categories = get_option( 'simple_customize_category', array( $theme->stylesheet => array() ) );
        $fonts      = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );

        unset( $options[$_GET['clear']] );
        unset( $categories[$_GET['clear']] );
        unset( $fonts[$_GET['clear']] );

        update_option( 'simple_customize', $options );
        update_option( 'simple_customize_category', $categories );
        update_option( 'simple_customize_fonts', $fonts );
    }

    //  Settings options
    if ( isset( $_POST['simple-customize-settings'] ) )
    {
        $settings = get_option( 'simple_customize_settings', array() );
        $settings['includefile'] = ( isset( $_POST['simple-customize-settings-includefile'] ) ? false : true );
        $settings['advanced'] = ( isset( $_POST['simple-customize-settings-advanced'] ) ? true : false);

        update_option( 'simple_customize_settings', $settings );
    }

    //  Our settings, which will be reused across the options pages
    $options = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );
    $categories = get_option( 'simple_customize_category', array( $theme->stylesheet => array() ) );
    $fonts = get_option( 'simple_customize_fonts', array( $theme->stylesheet => array() ) );
    $settings = get_option( 'simple_customize_settings', array() );
?>
<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php
            $tabs = array(
                'home'       => __( 'Simple Customize', 'simple-customize-plugin' ),
                'fonts'      => __( 'Fonts', 'simple-customize-plugin' ),
                'datasets'   => __( 'Datasets', 'simple-customize-plugin' ),
                'settings'   => __( 'Settings', 'simple-customize-plugin' ),
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
            case 'help':
                $include = 'help.php';
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
