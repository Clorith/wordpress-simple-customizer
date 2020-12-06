<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

	global $simple_customize;
	$settings = get_option( 'simple_customize_settings', array() );
	$customize_classes = array( 'simple-customize' );

	if ( isset( $settings['advanced'] ) && true === $settings['advanced'] ) {
		$customize_classes[] = 'simple-customize-is-advanced';
	}
?>
<label class="simple-customize-reveal <?php echo implode( " ", $customize_classes ); ?>">
	<span class="customize-control-title"><?php _e( 'Name', 'simple-customize-plugin' ); ?></span>
	<div class="customize-control-content">
		<input type="text" value="" id="simple_customize_label">
	</div>
</label>
<label class="simple-customize-reveal <?php echo implode( " ", $customize_classes ); ?>">
	<span class="customize-control-title"><?php _e( 'Category', 'simple-customize-plugin' ); ?></span>
	<div class="customize-control-content">
		<select id="simple_customize_category">
			<optgroup label="<?php _e( 'WordPress defaults', 'simple-customize-plugin' ); ?>">
				<option value="title_tagline"><?php _e( 'Site Title & Tagline', 'simple-customize-plugin' ); ?></option>
				<option value="colors" selected="selected"><?php _e( 'Colors', 'simple-customize-plugin' ); ?></option>
				<option value="header_image"><?php _e( 'Header Image', 'simple-customize-plugin' ); ?></option>
				<option value="background_image"><?php _e( 'Background Image', 'simple-customize-plugin' ); ?></option>
				<option value="nav"><?php _e( 'Navigation', 'simple-customize-plugin' ); ?></option>
				<option value="static_front_page"><?php _e( 'Static Front Page', 'simple-customize-plugin' ); ?></option>
			</optgroup>
			<optgroup label="<?php _e( 'Your categories', 'simple-customize-plugin' ); ?>">
				<?php
				$terms = get_terms(
					'simple-customize',
					array(
						'hide_empty' => false
					)
				);

				foreach( $terms AS $term )
				{
					echo '<option value="' . sanitize_title( $term->slug ) . '">' . $term->name . '</option>';
				}
				?>
			</optgroup>
		</select>
	</div>
</label>
<label class="simple-customize-advanced <?php echo implode( " ", $customize_classes ); ?>">
	<span class="customize-control-title"><?php _e( 'CSS selector', 'simple-customize-plugin' ); ?></span>
	<div class="customize-control-content">
		<input type="text" value="" id="simple_customize_selected">
	</div>
</label>
<label class="simple-customize-advanced <?php echo implode( " ", $customize_classes ); ?>">
	<input type="checkbox" id="customize-strict-grab" <?php echo ( isset( $settings['advanced'] ) && true === $settings['advanced'] ? '' : 'checked="checked"' ); ?>>
	<?php _e( 'Grab only the last selector', 'simple-customize-plugin' ); ?>
</label>
<label class="simple-customize-reveal <?php echo implode( " ", $customize_classes ); ?>">
	<span class="customize-control-title"><?php _e( 'What to customize', 'simple-customize-plugin' ); ?></span>
	<div class="customize-control-content">
		<select id="simple_customize_selector_auto"></select>
	</div>
</label>
<label class="simple-customize-advanced <?php echo implode( " ", $customize_classes ); ?>">
	<div class="customize-control-content">
		<input type="text" value="" id="simple_customize_selector_manual">
	</div>
</label>
<label class="simple-customize-advanced <?php echo implode( " ", $customize_classes ); ?>">
	<span class="customize-control-title"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></span>
	<div class="customize-control-content">
		<input type="text" value="" id="simple_customize_default">
	</div>
</label>
<span class="customize-control-title <?php echo implode( " ", $customize_classes ); ?>">&nbsp;</span>
<div class="simple-customize customize-control-content <?php echo implode( " ", $customize_classes ); ?>">
	<div class="simple-select-info updated">
        <p>
            <strong>
                <?php _e( 'Select your element', 'simple-customize-plugin' ); ?>
            </strong>

            <br />

            <?php _e( 'You have started the customize process, please click the element you wish to customize in the preview window.', 'simple-customize-plugin' ); ?>

            <br />
            <br />

            <button type="button" id="simple_customize_cancel">
                <?php _e( 'Cancel search', 'simple-customize-plugin' ); ?>
            </button>
        </p>
	</div>
	<div class="simple-select-button">
		<button type="button" class="button" id="simple_customize_selector">
			<?php printf( __( '%s Find something to customize', 'simple-customize-plugin' ), '<span class="dashicons dashicons-search"></span>' ); ?>
		</button>

		<br />
		<br />

		<button type="button" class="simple-customize-reveal button button-primary" id="simple_customize_store">
			<?php _e( 'Add element', 'simple-customize-plugin' ); ?>
		</button>
	</div>

	<br />
	<br />

	<a href="<?php echo admin_url( 'themes.php?page=simple-customize' ); ?>" class="simple-customize-settings-button button">
		<?php printf( __( '%s Settings', 'simple-customize-plugin' ), '<span class="dashicons dashicons-admin-settings"></span>' ); ?>
	</a>
</div>