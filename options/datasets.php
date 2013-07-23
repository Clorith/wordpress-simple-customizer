
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
                        </td>
                    </tr>
                ';
            }
        ?>
        </tbody>
    </table>
</form>