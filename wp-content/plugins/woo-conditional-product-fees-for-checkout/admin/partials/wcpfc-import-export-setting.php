<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
$get_status = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
$msg        = '';
$style      = "display:none;";
if ( 'success' === $get_status ) {
	$style = "display:block;";
	$msg   = esc_html__( 'Import successfully', 'advanced-flat-rate-shipping-for-woocommerce' );
}
?>
<div class="imp_exp_msg" style="<?php echo esc_attr( $style ); ?>">
	<?php esc_html_e( $msg ); ?>
</div>
<div class="wcpfc-section-left">
	<div class="wcpfc-main-table res-cl">
		<h2><?php echo esc_html__( 'Import &amp; Export Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
		<table class="table-outer">
			<tbody>
			<tr>
				<th scope="row" class="titledesc"><label
						for="blogname"><?php echo esc_html__( 'Export Settings Data', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
				</th>
				<td>
					<form method="post">
						<div class="wcpfc_main_container">
							<p class="wcpfc_button_container"><?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?></p>
							<p class="wcpfc_content_container">
								<?php wp_nonce_field( 'wcpfc_export_save_action_nonce', 'wcpfc_export_action_nonce' ); ?>
								<input type="hidden" name="wcpfc_export_action" value="export_settings"/>
								<strong><?php esc_html_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></strong>
							</p>
						</div>
					</form>

				</td>
			</tr>
			<tr>
				<th scope="row" class="titledesc"><label
						for="blogname"><?php echo esc_html__( 'Import Settings Data', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
				</th>
				<td>
					<form method="post" enctype="multipart/form-data">
						<div class="wcpfc_main_container">
							<p>
								<input type="file" name="import_file"/>
							</p>
							<p class="wcpfc_button_container">
								<input type="hidden" name="wcpfc_import_action" value="import_settings"/>
								<?php wp_nonce_field( 'wcpfc_import_action_nonce', 'wcpfc_import_action_nonce' ); ?>
								<?php
								$other_attributes = array( 'id' => 'wcpfc_import_setting' );
								?>
								<?php submit_button( __( 'Import' ), 'secondary', 'submit', false, $other_attributes ); ?>
								<strong><?php esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></strong>
							</p>
						</div>
					</form>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>
