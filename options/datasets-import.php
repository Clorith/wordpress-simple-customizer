<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>

<?php if ( ! isset( $_GET['export'] ) ) { ?>
<form action="<?php echo admin_url( 'themes.php?page=simple-customize&tab=datasets' ); ?>" method="post">
	<h3><?php _e( 'Import customizations', 'simple-customize-plugin' ); ?></h3>

	<?php wp_nonce_field( 'simple-customize-import' ); ?>

	<textarea name="simple-customize-import" style="width: 100%; height: 75px;" placeholder="Put your customization string here"></textarea>
	<?php submit_button( __( 'Import customization', 'simple-customize-plugin' ) ); ?>
</form>

<?php
}