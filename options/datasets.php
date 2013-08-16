
<?php _e( "The list below contains all the themes you've styled, you can here remove your custom stylings entirely without editing the specific theme.", 'simple-customize-plugin' ); ?>

<br />

<form action="" method="post">
    <table class="wp-list-table widefat" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?php _e( 'Theme Name', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Theme Author', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Actions', 'simple-customize-plugin' ); ?></th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th scope="col"><?php _e( 'Theme Name', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Theme Author', 'simple-customize-plugin' ); ?></th>
            <th scope="col"><?php _e( 'Actions', 'simple-customize-plugin' ); ?></th>
        </tr>
        </tfoot>

        <tbody id="the-list">
        <?php
            $options = get_option( 'simple_customize', array( $theme->stylesheet => array() ) );

            foreach( $options AS $themename => $themeoptions )
            {
                $theme = wp_get_theme ( $themename );
                echo '
                    <tr>
                        <td>' . $theme->Name . '</td>
                        <td>' . $theme->Author . '</td>
                        <td>
                            <a href="?page=simple-customize&tab=datasets&clear=' . $themename . '">' . __( 'Delete stylings', 'simple-customize-plugin' ) . '</a>
                             |
                            <a href="?page=simple-customize&tab=datasets&export=' . $themename . '">' . __( 'Export customizations', 'simple-customize-plugin' ) . '</a>
                        </td>
                    </tr>
                ';
            }
        ?>
        </tbody>
    </table>
</form>

<?php
    if ( isset( $_GET['export'] ) )
    {
        $export = array(
            'theme'      => $_GET['export'],
            'categories' => array(),
            'options'    => array(),
            'fonts'      => array()
        );

        if ( isset( $categories[$_GET['export']] ) )
        {
            foreach( $categories[$_GET['export']] AS $category )
            {
                $export['categories'][] = $category;
            }
        }

        if ( isset( $options[$_GET['export']] ) )
        {
            $theme_mods = get_theme_mods();

            foreach( $options[$_GET['export']] AS $option )
            {
                $mod = sanitize_title( $option['label'] );
                $option['value'] = $theme_mods[$mod];

                $export['options'][] = $option;
            }
        }

        if ( isset( $fonts[$_GET['export']] ) )
        {
            foreach( $fonts[$_GET['export']] AS $fnum => $font )
            {
                $export['fonts'][] = $font;
            }
        }
?>

    <h3><?php _e( 'Export customization', 'simple-customize-plugin' ); ?></h3>
    <textarea name="simple-customize-export" style="width: 100%; height: 75px;"><?php echo base64_encode( serialize( $export ) ); ?></textarea>

<?php
        _e( 'Copy the text above, it represents your themes customizations, categories and fonts', 'simple-customize-plugin' );
    }
    else {
?>

<form action="" method="post">
    <h3><?php _e( 'Import customizations', 'simple-customize-plugin' ); ?></h3>

    <textarea name="simple-customize-import" style="width: 100%; height: 75px;" placeholder="Put your customization string here"></textarea>
    <?php submit_button( __( 'Add customization', 'simple-customize-plugin' ) ); ?>
</form>

<?php
    }
?>
