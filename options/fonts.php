<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'WP_ADMIN' ) ) {
	die();
}

$font_api_key = simple_customize::get_plugin_settings( 'google_api' );

if ( ! $font_api_key ) :
?>

<div class="notice inline notice-warning">
    <h2><?php esc_html_e( 'Google Fonts API key', 'simple-customizer' ); ?></h2>
    <p>
        <?php esc_html_e( 'The fonts integration requests a Google Fonts API key to work properly.', 'simple-customizer' ); ?>
    </p>
    <p>
        <a href="https://console.developers.google.com/apis/credentials" rel="noreferrer noopener">
            <?php esc_html_e( 'Get a Google API key here', 'simple-customizer' ); ?>
        </a>
    </p>
</div>

<?
return;
endif;


do_action( 'simple-customize-options-before-fonts' );
?>
<div class="options-toolbar">
	<a href="#TB_inline?width=600&height=700&inlineId=simple-customize-add-font" class="button button-primary thickbox modal-trigger simple-customize-font-modal"><?php _e( 'Add a new font', 'simple-customize-plugin' ); ?></a>
</div>

<div id="simple-customize-add-font" style="display: none;">
	<form action="<?php echo admin_url( 'themes.php?page=simple-customize&tab=fonts' ); ?>" method="post">
		<?php wp_nonce_field( 'simple-customize-add-font' ); ?>
		<div class="modal-title"><?php _e( 'Add a new font', 'simple-customize-plugin' ); ?></div>

		<div class="modal-body">
			<input type="hidden" id="simple-customize-add-font-label" name="font-label" value="" />
			<input type="hidden" id="simple-customize-add-font-location" name="font-location" value="" />

			<div class="control-group">
				<label><?php _e( 'Font name', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<div class="simple-customize-font-select-box">
						<?php
							if ( ! empty( $google_fonts ) ) {
								foreach ( $google_fonts->items AS $font ) {
									if ( ! isset( $font->files->regular ) ) {
										continue;
									}
									echo '
										<div class="simple-customize-font" data-simple-customize-font-family-preview="\'' . $font->family . '\', ' . $font->category . '" data-simple-customize-font-url="' . $font->files->regular . '">
											' . $font->family . '
										</div>
									';
								}
							}
						?>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label><?php _e( 'Preview', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<span id="simple-customize-font-preview">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris aliquet lacinia erat at tincidunt. Pellentesque et purus a ligula suscipit varius in at felis.
					</span>
				</div>
			</div>
		</div>

		<div class="modal-toolbar">
			<button type="button" id="simple-customize-font-load" data-simple-customize-font-nonce="<?php echo wp_create_nonce( 'simple-customize-get-fonts' ); ?>" class="button button-large"><?php _e( 'Reload fonts', 'simple-customize-plugin' ); ?></button>
			<button type="submit" class="button button-primary button-large"><?php _e( 'Add this font', 'simple-customize-plugin' ); ?></button>
		</div>
	</form>
</div>



<table class="wp-list-table widefat" cellspacing="0">
	<thead>
	<tr>
		<th scope="col"><?php _e( 'Font name', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Font options', 'simple-customize-plugin' ); ?></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope="col"><?php _e( 'Font name', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Font options', 'simple-customize-plugin' ); ?></th>
	</tr>
	</tfoot>

	<tbody id="the-list">
	<?php
	$fonts = get_posts( array(
		'posts_per_page' => -1,
		'post_type'      => 's-c-font',
		'post_status'    => array( 'draft', 'publish' )
	) );

	$fonts = apply_filters( 'simple-customizer-font-list', $fonts );

	foreach( $fonts AS $font )
	{
		?>
		<tr>
			<td>
				<?php echo $font->post_title; ?>
			</td>
			<td>
				<a href="<?php echo wp_nonce_url( '?page=simple-customize&amp;tab=fonts&amp;font-delete=' . $font->ID, 'simple-customize-remove-font-' . $font->ID ); ?>" class="delete"><?php _e( 'Remove this font', 'simple-customize-plugin' ); ?></a>
				<?php if ( 'draft' == $font->post_status ) { ?>
				 | <a href="<?php echo wp_nonce_url( '?page=simple-customize&amp;tab=fonts&amp;font-enable=' . $font->ID, 'simple-customize-enable-font-' . $font->ID ); ?>"><?php _e( 'Click to enable', 'simple-customize-plugin' ); ?></a>
				<?php } else { ?>
				 | <a href="<?php echo wp_nonce_url( '?page=simple-customize&amp;tab=fonts&amp;font-disable=' . $font->ID, 'simple-customize-disable-font-' . $font->ID ); ?>"><?php _e( 'Click to disable', 'simple-customize-plugin' ); ?></a>
				<?php } ?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>

<?php
do_action( 'simple-customize-options-after-fonts' );
