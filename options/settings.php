<form action="" method="post">
    <input type="hidden" name="simple-customize-settings" value="edit">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label for="simple-customize-settings-includefile">
                    <?php _e( 'Do not create a CSS file, add styles to my page header instead (reduces requests to server)', 'simple-customize-plugin' ); ?>
                </label>
            </th>
            <td>
                <input type="checkbox" name="simple-customize-settings-includefile" id="simple-customize-settings-includefile" <?php checked( ( isset( $settings['includefile'] ) ? $settings['includefile'] : true ), false ); ?>>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="simple-customize-settings-advanced">
                    <?php _e( 'Enable Advanced Mode', 'simple-customize-plugin' ); ?>
                </label>
            </th>
            <td>
                <input type="checkbox" name="simple-customize-settings-advanced" id="simple-customize-settings-advanced" <?php checked( ( isset( $settings['advanced'] ) ? $settings['advanced'] : false ), true ); ?>>
            </td>
        </tr>

        <tr valign="top">
            <td>
                <?php submit_button(); ?>
            </td>
        </tr>
    </table>
</form>
