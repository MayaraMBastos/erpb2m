<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="wcpfc-section-left">
		<div class="wcpfc-main-table res-cl">
			<h2><?php esc_html_e( 'Quick info', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
			<table class="table-outer">
				<tbody>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Product Type', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td class="fr-2"><?php esc_html_e( 'WooCommerce Plugin', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Product Name', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td class="fr-2"><?php esc_html_e( $plugin_name, 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Installed Version', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td class="fr-2"><?php esc_html_e( WCPFC_PRO_PREMIUM_VERSION, 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $plugin_version ); ?></td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'License & Terms of use', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td class="fr-2"><a target="_blank"
					                    href="<?php echo esc_url( "https://www.thedotstore.com/terms-and-conditions/" ); ?>"><?php esc_html_e( 'Click here', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a><?php esc_html_e( ' to view license and terms of use.', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
					</td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Help & Support', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td class="fr-2">
						<ul style="margin: 0px;">
							<li><a target="_blank"
							       href="<?php echo esc_url( add_query_arg( array( 'page' => 'wcpfc-pro-get-started' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Quick Start', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
							</li>
							<li><a target="_blank"
							       href="<?php echo esc_url( "https://www.thedotstore.com/docs/plugins/woocommerce-conditional-product-fees-checkout" ); ?>"><?php esc_html_e( 'Guide Documentation', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
							</li>
							<li><a target="_blank"
							       href="<?php echo esc_url( "https://www.thedotstore.com/support" ); ?>"><?php esc_html_e( 'Support Forum', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td class="fr-1"><?php esc_html_e( 'Localization', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td class="fr-2"><?php esc_html_e( 'English, Spanish', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
				</tr>

				</tbody>
			</table>
		</div>
	</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>