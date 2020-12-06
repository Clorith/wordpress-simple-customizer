<?php
/**
 * Prevent direct access to files
 */
if ( ! defined( 'WP_ADMIN' ) ) {
	die();
}

global $simple_customize;
global $wpdb;

do_action( 'simple-customize-options-before-datasets' );
?>
<?php _e( "The list below contains all the themes you've styled, you can here remove your custom stylings entirely without editing the specific theme.", 'simple-customize-plugin' ); ?>

<br />

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
		$entries = $wpdb->get_results( "
			SELECT
				meta_value
			FROM
				" . $wpdb->postmeta . "
			WHERE
				meta_key = '_simple_customize_theme'
			GROUP BY
				meta_value
		" );

		foreach( $entries AS $entry )
		{
			$theme = wp_get_theme ( $entry->meta_value );
			$dataset_line = '';


			$dataset_line .= '
				<tr>
					<td>' . apply_filters( 'simple-customzier-datasets-list-item-name', $theme->Name ) . '</td>
					<td>' . apply_filters( 'simple-customizer-datasets-list-item-author', $theme->Author ) . '</td>
					<td>
			';

			$dataset_actions = '
						<a href="' . wp_nonce_url( 'themes.php?page=simple-customize&tab=datasets&clear=' . $entry->meta_value, 'simple-customize-clear-' . $entry->meta_value ) . '">' . __( 'Delete stylings', 'simple-customize-plugin' ) . '</a>
						 |
						<a href="' . admin_url( 'themes.php?page=simple-customize&tab=datasets&export=' . $entry->meta_value ) . '">' . __( 'Export customizations', 'simple-customize-plugin' ) . '</a>
						 |
						<a href="' . admin_url( 'themes.php?page=simple-customize&tab=datasets&export=' . $entry->meta_value . '&css=true' ) . '">' . __( 'Export CSS', 'simple-customize-plugin' ) . '</a>
			';

			$dataset_line .= apply_filters( 'simple-customizer-datasets-list-item-actions', $dataset_actions, $entry );

			$dataset_line .= '
					</td>
				</tr>
			';

			echo apply_filters( 'simple-customizer-datasets-list-item', $dataset_line );
		}
	?>
	</tbody>
</table>

<?php
	include_once( plugin_dir_path( __FILE__ ) . '/datasets-export.php' );
    include_once( plugin_dir_path( __FILE__ ) . '/datasets-import.php' );

	do_action( 'simple-customize-options-after-datasets' );
?>