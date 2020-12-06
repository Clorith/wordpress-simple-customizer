<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'WP_ADMIN' ) ) {
	die();
}

global $simple_customize;

do_action( 'simple-customize-options-before-home' );
?>
<h2>
	<?php _e( 'Customizations', 'simple-customize-plugin' ); ?>
</h2>

<small>
	<?php _e( 'Below is a list of your customizable elements. You don\'t have to do anything on this screen, feel free to just', 'simple-customize-plugin' ); ?> <a href="<?php echo admin_url( 'customize.php' ); ?>"><?php _e( 'start customizing.', 'simple-customize-plugin' ); ?></a>
</small>

<div class="options-toolbar">
	<a href="#TB_inline?width=600&height=200&inlineId=simple-customize-add-category" class="button button-primary thickbox modal-trigger"><?php _e( 'Add a new category', 'simple-customize-plugin' ); ?></a>
	<a href="#TB_inline?width=600&height=390&inlineId=simple-customize-add-selector" class="button button-primary thickbox modal-trigger"><?php _e( 'Add a new manual selector', 'simple-customize-plugin' ); ?></a>
</div>

<div id="simple-customize-add-category" style="display: none;">
	<form action="<?php echo admin_url( 'themes.php?page=simple-customize' ); ?>" method="post">
		<?php wp_nonce_field( 'simple-customize-add-category' ); ?>
		<div class="modal-title"><?php _e( 'Add a new category', 'simple-customize-plugin' ); ?></div>

		<div class="modal-body">
			<div class="control-group">
				<label for="simple-customize-category-label"><?php _e( 'Name', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-category-label" name="category-label">
				</div>
			</div>
		</div>

		<div class="modal-toolbar">
			<button type="submit" class="button button-primary button-large"><?php _e( 'Add this category', 'simple-customize-plugin' ); ?></button>
		</div>
	</form>
</div>

<div id="simple-customize-edit-selector" style="display: none">
	<form action="<?php echo admin_url( 'themes.php?page=simple-customize' ); ?>" method="post">
		<?php wp_nonce_field( 'simple-customize-edit-selector' ); ?>
		<input type="hidden" name="simple-customize-edit-slug" id="simple-customize-edit-slug" value="">
		<div class="modal-title"><?php _e( 'Edit selector', 'simple-customize-plugin' ); ?> - <span id="edit-selector-name"></span></div>

		<div class="modal-body">
			<div class="control-group">
				<label for="simple-customize-edit-category"><?php _e( 'Category', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<select id="simple-customize-edit-category" name="edit-category" style="width:100%;">
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

							$terms = apply_filters( 'simple-customizer-terms-list', $terms );

							foreach( $terms AS $term )
							{
								echo '<option value="' . sanitize_title( $term->slug ) . '">' . $term->name . '</option>';
							}
							?>
						</optgroup>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label for="simple-customize-edit-object"><?php _e( 'Selector', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-edit-object" style="width:100%;" name="edit-selector_manual">
				</div>
			</div>

			<div class="control-group">
				<label for="simple-customize-edit-selector_manual"><?php _e( 'Attribute', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-edit-selector_manual" style="width:100%;" name="edit-object">
				</div>
			</div>

			<div class="control-group">
				<label for="simple-customize-edit-default"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-edit-default" style="width:100%;" name="edit-default">
				</div>
			</div>
		</div>

		<div class="modal-toolbar">
			<div class="media-toolbar-primary">
				<button type="submit" class="button button-primary button-large"><?php _e( 'Edit this selector', 'simple-customize-plugin' ); ?></button>
			</div>
		</div>
	</form>
</div>

<div id="simple-customize-add-selector" style="display: none">
	<form action="<?php echo admin_url( 'themes.php?page=simple-customize' ); ?>" method="post">
		<?php wp_nonce_field( 'simple-customize-add-selector' ); ?>
		<div class="modal-title"><?php _e( 'Add a new selector', 'simple-customize-plugin' ); ?></div>

		<div class="modal-body">
			<div class="control-group">
				<label for="simple-customize-label"><?php _e( 'Name', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-label" style="width:100%;" name="label">
				</div>
			</div>

			<div class="control-group">
				<label for="simple_customize_category"><?php _e( 'Category', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<select id="simple_customize_category" name="category" style="width:100%;">
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
								foreach( $terms AS $term )
								{
									echo '<option value="' . sanitize_title( $term->slug ) . '">' . $term->name . '</option>';
								}
							?>
						</optgroup>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label for="simple-customize-object"><?php _e( 'Selector', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-object" style="width:100%;" name="object">
				</div>
			</div>

			<div class="control-group">
				<label for="simple-customize-selector_manual"><?php _e( 'Attribute', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-selector_manual" style="width:100%;" name="selector_manual">
				</div>
			</div>

			<div class="control-group">
				<label for="simple-customize-default"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></label>
				<div class="controls">
					<input type="text" id="simple-customize-default" style="width:100%;" name="default">
				</div>
			</div>
		</div>

		<div class="modal-toolbar">
			<div class="media-toolbar-primary">
				<button type="submit" class="button button-primary button-large"><?php _e( 'Add this selector', 'simple-customize-plugin' ); ?></button>
			</div>
		</div>
	</form>
</div>

<table class="wp-list-table widefat" cellspacing="0">
	<thead>
	<tr>
		<th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Category', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Selector', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Attribute', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Current value', 'simple-customize-plugin' ); ?></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Category', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Selector', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Attribute', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Default value', 'simple-customize-plugin' ); ?></th>
		<th scope="col"><?php _e( 'Current value', 'simple-customize-plugin' ); ?></th>
	</tr>
	</tfoot>

	<tbody id="the-list">
	<?php

	$entries = get_posts( array(
		'posts_per_page' => -1,
		'post_type'      => 'simple-customize',
		'meta_key'       => '_simple_customize_theme',
		'meta_value'     => $simple_customize->theme->stylesheet
	) );

	$entries = apply_filters( 'simple-customizer-entry-list', $entries );

	foreach( $entries AS $entry )
	{
		$meta = get_post_meta( $entry->ID );
		?>
		<tr>
			<td>
				<span class="simple-customize-name">
					<?php echo $entry->post_title; ?>
				</span>
				<div class="row-actions">
						<span class="edit">
							<a href="#TB_inline?width=600&height=340&inlineId=simple-customize-edit-selector" class="thickbox modal-trigger simple-customize-edit-entry" data-customize-slug="<?php echo $entry->ID; ?>"><?php _e( 'Edit', 'simple-customize-plugin' ); ?></a>
						</span>
						<span class="delete">
							| <a href="<?php echo wp_nonce_url( '?page=simple-customize&delete=' . $entry->ID, 'simple-customize-delete-selector-' . $entry->ID ); ?>" class="delete"><?php _e( 'Delete', 'simple-customize-plugin' ); ?></a>
						</span>
				</div>
			</td>
			<td>
				<span class="simple-customize-category">
					<?php echo $meta['_simple_customize_category'][0]; ?>
				</span>
			</td>
			<td>
				<span class="simple-customize-object">
					<?php echo $meta['_simple_customize_selector'][0]; ?>
				</span>
			</td>
			<td>
				<span class="simple-customize-selector">
					<?php echo $meta['_simple_customize_attribute'][0]; ?>
				</span>
			</td>
			<td>
				<span class="simple-customize-default">
					<?php echo $meta['_simple_customize_default'][0]; ?>
				</span>
			</td>
			<td>
				<span class="simple-customize-current-value">
					<?php echo get_theme_mod( $entry->ID, $meta['_simple_customize_default'][0] ); ?>
				</span>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>



<h2>
    <?php _e( 'Categories', 'simple-customize-plugin' ); ?>
</h2>

<small>
	<?php _e( 'A category is usually used to group together customization options of similar origin. One such example would be to create a category titled "Footer" and put all your customized options for the footer inside this category.', 'simple-customize-plugin' ); ?>
</small>

<br />
<br />

<table class="wp-list-table widefat" cellspacing="0">
	<thead>
	<tr>
		<th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope="col"><?php _e( 'Name', 'simple-customize-plugin' ); ?></th>
	</tr>
	</tfoot>

	<tbody id="the-list">
	<?php
	foreach( $terms AS $term )
	{
	?>
		<tr>
			<td colspan="2">
				<?php echo $term->name; ?>
				<div class="row-actions">
					<span class="delete">
						<a href="<?php echo wp_nonce_url( '?page=simple-customize&category-delete=' . $term->term_id, 'simple-customize-delete-category-' . $term->term_id ); ?>" class="delete"><?php _e( 'Delete', 'simple-customize-plugin' ); ?></a>
					</span>
				</div>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>

<?php
	do_action( 'simple-customize-options-after-home' );