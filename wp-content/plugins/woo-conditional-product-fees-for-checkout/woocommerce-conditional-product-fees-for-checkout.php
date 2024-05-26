<?php

/**
 * Plugin Name: WooCommerce Conditional Product Fees for Checkout Premium
 * Plugin URI:          https://www.thedotstore.com/woocommerce-conditional-product-fees-checkout/
 * Description:         With this plugin, you can create and manage complex fee rules in WooCommerce store without the help of a developer.
 * Version:             3.2
 * Author:              Multidots
 * Author URI:          http://www.multidots.com
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         woocommerce-conditional-product-fees-for-checkout
 * Domain Path:         /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.8.0
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wcpffc_fs' ) ) {
    wcpffc_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wcpffc_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wcpffc_fs()
        {
            global  $wcpffc_fs ;
            
            if ( !isset( $wcpffc_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_3390_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_3390_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wcpffc_fs = fs_dynamic_init( array(
                    'id'              => '3390',
                    'slug'            => 'woocommerce-conditional-product-fees-for-checkout',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_d202bec45f41a5ae6b41399bde03f',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Premium',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'       => 'wcpfc-pro-list',
                    'first-path' => 'admin.php?page=wcpfc-pro-list',
                    'contact'    => false,
                    'support'    => false,
                    'network'    => true,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $wcpffc_fs;
        }
        
        // Init Freemius.
        wcpffc_fs();
        // Signal that SDK was initiated.
        do_action( 'wcpffc_fs_loaded' );
        wcpffc_fs()->get_upgrade_url();
    }

}

if ( !defined( 'WCPFC_PRO_PLUGIN_URL' ) ) {
    define( 'WCPFC_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'WCPFC_PLUGIN_DIR' ) ) {
    define( 'WCPFC_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'WCPFC_PRO_PLUGIN_BASENAME' ) ) {
    define( 'WCPFC_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'WCPFC_PRO_PERTICULAR_FEE_AMOUNT_NOTICE' ) ) {
    define( 'WCPFC_PRO_PERTICULAR_FEE_AMOUNT_NOTICE', 'You can turn off this button, if you do not need to apply this fee amount.' );
}
if ( !defined( 'WCPFC_PRO_PREMIUM_VERSION' ) ) {
    if ( !defined( 'WCPFC_PRO_PREMIUM_VERSION' ) ) {
        define( 'WCPFC_PRO_PREMIUM_VERSION', 'Free Version ' );
    }
}
if ( !defined( 'WCPFC_PRO_PLUGIN_NAME' ) ) {
    define( 'WCPFC_PRO_PLUGIN_NAME', 'WooCommerce Conditional Product Fees for Checkout' );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-conditional-product-fees-for-checkout-activator.php
 */
function wcpfc_pro_activation()
{
    set_transient( 'wcpfc-admin-notice', true );
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-conditional-product-fees-for-checkout-activator.php';
    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Activator::activate();
}

/**
 * The core plugin include constant file for set constant.
 */
require plugin_dir_path( __FILE__ ) . 'constant.php';
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-conditional-product-fees-for-checkout-deactivator.php
 */
function wcpfc_pro_deactivation()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-conditional-product-fees-for-checkout-deactivator.php';
    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wcpfc_pro_activation' );
register_deactivation_hook( __FILE__, 'wcpfc_pro_deactivation' );
add_action( 'admin_init', 'wcpfc_pro_deactivate_plugin' );
function wcpfc_pro_deactivate_plugin()
{
    if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
        deactivate_plugins( '/woo-conditional-product-fees-for-checkout/woocommerce-conditional-product-fees-for-checkout.php', true );
    }
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-conditional-product-fees-for-checkout.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wcpfc_pro_activation_run()
{
    $plugin = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro();
    $plugin->run();
}

wcpfc_pro_activation_run();
function wcpfc_pro_path()
{
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
}

/**
 * Helper function for logging
 *
 * For valid levels, see `WC_Log_Levels` class
 *
 * Description of levels:
 *     'emergency': System is unusable.
 *     'alert': Action must be taken immediately.
 *     'critical': Critical conditions.
 *     'error': Error conditions.
 *     'warning': Warning conditions.
 *     'notice': Normal but significant condition.
 *     'info': Informational messages.
 *     'debug': Debug-level messages.
 *
 * @param string $message
 *
 * @return mixed log
 */
function wcpfc_log( $message, $level = 'debug' )
{
    $chk_enable_logging = get_option( 'chk_enable_logging' );
    if ( 'off' === $chk_enable_logging ) {
        return;
    }
    $logger = wc_get_logger();
    $context = array(
        'source' => 'woocommerce-conditional-product-fees-for-checkout',
    );
    return $logger->log( $level, $message, $context );
}

add_action( 'admin_notices', 'wcpfc_admin_notice_function' );
function wcpfc_admin_notice_function()
{
    $wcpfc_admin = filter_input( INPUT_GET, 'wcpfc-hide-notice', FILTER_SANITIZE_STRING );
    $wc_notice_nonce = filter_input( INPUT_GET, '_wcpfc_notice_nonce', FILTER_SANITIZE_STRING );
    if ( isset( $wcpfc_admin ) && $wcpfc_admin === 'wcpfc_admin' && wp_verify_nonce( sanitize_text_field( $wc_notice_nonce ), 'wcpfc_hide_notices_nonce' ) ) {
        delete_transient( 'wcpfc-admin-notice' );
    }
    /* Check transient, if available display notice */
    
    if ( get_transient( 'wcpfc-admin-notice' ) ) {
        ?>
		<div id="message"
		     class="updated woocommerce-message woocommerce-admin-promo-messages welcome-panel wcpfc-panel">
			<a class="woocommerce-message-close notice-dismiss"
			   href="<?php 
        echo  esc_url( wp_nonce_url( add_query_arg( 'wcpfc-hide-notice', 'wcpfc_admin' ), 'wcpfc_hide_notices_nonce', '_wcpfc_notice_nonce' ) ) ;
        ?>"></a>
			<p>
				<?php 
        echo  sprintf( wp_kses( __( '<strong>WooCommerce Conditional Product Fees for Checkout is successfully installed and ready to go.</strong>', 'woocommerce-conditional-product-fees-for-checkout' ), array(
            'strong' => array(),
        ), esc_url( admin_url( 'options-general.php' ) ) ) ) ;
        ?>
			</p>
			<p>
				<?php 
        echo  wp_kses_post( __( 'Click on settings button and create your fees with multiple rules', 'woocommerce-conditional-product-fees-for-checkout' ) ) ;
        ?>
			</p>
			<?php 
        $url = add_query_arg( array(
            'page' => 'wcpfc-pro-list',
        ), admin_url( 'admin.php' ) );
        ?>
			<p>
				<a href="<?php 
        echo  esc_url( $url ) ;
        ?>"
				   class="button button-primary"><?php 
        esc_html_e( 'Settings', 'woocommerce-conditional-product-fees-for-checkout' );
        ?></a>
			</p>
		</div>
		<?php 
    }

}
