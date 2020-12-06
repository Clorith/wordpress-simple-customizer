<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'WP_ADMIN' ) ) {
	die();
}

	do_action( 'simple-customize-options-before-settings' );
?>
<form action="<?php echo admin_url( 'themes.php?page=simple-customize&tab=settings' ); ?>" method="post">
	<?php wp_nonce_field( 'simple-customize-settings' ); ?>
    <input type="hidden" name="simple-customize-settings" value="edit">
    <table class="form-table">
        <tr valign="top">
            <td>
                <label>
                    <input type="checkbox" name="simple-customize-settings-includefile" id="simple-customize-settings-includefile" <?php checked( ( isset( $settings['includefile'] ) ? $settings['includefile'] : false ), true ); ?>>
                    <?php _e( 'Do not create a separate CSS file', 'simple-customize-plugin' ); ?>
					<br />
					<small>
						<?php _e( 'Add styles to your page header instead (reduces requests to server)', 'simple-customize-plugin' ); ?>
					</small>
                </label>
            </td>
        </tr>

	    <tr valign="top">
		    <td>
			    <label>
				    <input type="checkbox" name="simple-customize-settings-minified" id="simple-customize-settings-minified" <?php checked( ( isset( $settings['minified'] ) ? $settings['minified'] : false ), true ); ?>>
				    <?php _e( 'Create a minified CSS file', 'simple-customize-plugin' ); ?>
				    <br />
				    <small>
					    <?php _e( 'This will create a single line CSS file and compress it as much as possible', 'simple-customize-plugin' ); ?>
				    </small>
			    </label>
		    </td>
	    </tr>

        <tr valign="top">
            <td>
                <label>
                    <input type="checkbox" name="simple-customize-settings-compatibility" id="simple-customize-settings-compatibility" <?php checked( (isset( $settings['compatibility'] ) ? $settings['compatibility'] : false ), true ); ?>>
                    <?php _e( 'Enable compatibility mode', 'simple-customize-plugin' ); ?>
					<br />
					<small>
						<?php _e( 'Useful for sites where pretty permalinks require special attention (primarily nginx)', 'simple-customize-plugin' ); ?>
					</small>
                </label>
            </td>
        </tr>

		<tr valign="top">
			<td>
				<label>
					<input type="checkbox" name="simple-customize-settings-advanced" id="simple-customize-settings-advanced" <?php checked( ( isset( $settings['advanced'] ) ? $settings['advanced'] : false ), true ); ?>>
					<?php _e( 'Developer Mode', 'simple-customize-plugin' ); ?>
					<br />
					<small>
						<?php _e( 'Will include references in the CSS to more easily identify rules, and gives more fine grained control in the customizer screen.', 'simple-customize-plugin' ); ?>
					</small>
				</label>
			</td>
		</tr>

        <tr valign="top">
            <td>
                <label for="google-api-key">
                    <?php esc_html_e( 'Google API key', 'simple-customizer' ); ?>
                </label>
                <br><br>
                <input type="text" name="simple-customize-settings-google-api-key" id="google-api-key" value="<?php echo esc_attr( ( isset( $settings['google_api'] ) ? $settings['google_api'] : '' ) ); ?>">
            </td>
        </tr>

	    <?php do_action( 'simple-customize-options-before-settings-submit' ); ?>

        <tr valign="top">
            <td>
                <?php submit_button(); ?>
            </td>
        </tr>
    </table>

	<?php do_action( 'simple-customize-options-after-settings' ); ?>
</form>