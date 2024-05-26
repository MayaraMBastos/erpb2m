<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * define constant variabes
 * define admin side constant
 *
 * @param null
 *
 * @author Multidots
 * @since  1.0.0
 */
// define constant for plugin slug
define( 'CONDITIOANAL_FEE_PRO_PLUGIN_NAME', 'WooCommerce Conditional Product Fees for Checkout Pro' );
if ( ! defined( 'WCPFC_PRO_PLUGIN_VERSION' ) ) {
	define( 'WCPFC_PRO_PLUGIN_VERSION', '3.2' );
}
if ( ! defined( 'WCPFC_PRO_PLUGIN_URL' ) ) {
	define( 'WCPFC_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WCPFC_PLUGIN_DIR' ) ) {
	define( 'WCPFC_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'WCPFC_PRO_PLUGIN_DIR_PATH' ) ) {
	define( 'WCPFC_PRO_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WCPFC_PRO_SLUG' ) ) {
	define( 'WCPFC_PRO_SLUG', 'woocommerce-conditional-product-fees-for-checkout' );
}
if ( ! defined( 'WCPFC_PRO_PLUGIN_BASENAME' ) ) {
	define( 'WCPFC_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'WCPFC_PRO_PERTICULAR_FEE_AMOUNT_NOTICE' ) ) {
	define( 'WCPFC_PRO_PERTICULAR_FEE_AMOUNT_NOTICE', esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.', 'woocommerce-conditional-product-fees-for-checkout' ) );
}