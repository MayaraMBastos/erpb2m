<?php

/**
 * Plugin Name: WooCommerce Checkout Manager
 * Description: Manages WooCommerce Checkout, the advanced way.
 * Version:     5.0.0
 * Author:      QuadLayers
 * Author URI:  https://www.quadlayers.com
 * Copyright:   2019 QuadLayers (https://www.quadlayers.com)
 * Text Domain: woocommerce-checkout-manager
 */
if (!defined('ABSPATH')) {
  die('-1');
}

if (!defined('WOOCCM_PLUGIN_NAME')) {
  define('WOOCCM_PLUGIN_NAME', 'WooCommerce Checkout Manager');
}
if (!defined('WOOCCM_PLUGIN_VERSION')) {
  define('WOOCCM_PLUGIN_VERSION', '5.0.0');
}
if (!defined('WOOCCM_PLUGIN_FILE')) {
  define('WOOCCM_PLUGIN_FILE', __FILE__);
}
if (!defined('WOOCCM_PLUGIN_DIR')) {
  define('WOOCCM_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR);
}
if (!defined('WOOCCM_PREFIX')) {
  define('WOOCCM_PREFIX', 'wooccm');
}
if (!defined('WOOCCM_WORDPRESS_URL')) {
  define('WOOCCM_WORDPRESS_URL', 'https://wordpress.org/plugins/woocommerce-checkout-manager/');
}
if (!defined('WOOCCM_REVIEW_URL')) {
  define('WOOCCM_REVIEW_URL', 'https://wordpress.org/support/plugin/woocommerce-checkout-manager/reviews/?filter=5#new-post');
}
if (!defined('WOOCCM_DOCUMENTATION_URL')) {
  define('WOOCCM_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/woocommerce-checkout-manager/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_DEMO_URL')) {
  define('WOOCCM_DEMO_URL', 'https://quadlayers.com/portfolio/woocommerce-checkout-manager/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_PURCHASE_URL')) {
  define('WOOCCM_PURCHASE_URL', WOOCCM_DEMO_URL);
}
if (!defined('WOOCCM_SUPPORT_URL')) {
  define('WOOCCM_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_GROUP_URL')) {
  define('WOOCCM_GROUP_URL', 'https://www.facebook.com/groups/quadlayers');
}

if (!class_exists('WOOCCM', false)) {
  include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm.php' );
}

function WOOCCM() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
  return WOOCCM::instance();
}

// Global for backwards compatibility.
$GLOBALS['wooccm'] = WOOCCM();
