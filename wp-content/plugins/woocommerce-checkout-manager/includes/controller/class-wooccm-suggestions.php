<?php

class WOOCCM_Checkout_Suggestions_Controller {

  protected static $_instance;

  public function __construct() {
    $this->init();
  }

  public static function instance() {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  // Admin    
  // -------------------------------------------------------------------------

  public function add_page() {
    include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm-suggestions-list-table.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/view/backend/pages/suggestions.php' );
  }

  public function add_menu() {
    add_submenu_page(WOOCCM_PREFIX, __('Suggestions', 'woocommerce-checkout-manager'), __('Suggestions', 'woocommerce-checkout-manager'), 'manage_woocommerce', WOOCCM_PREFIX . '_suggestions', array($this, 'add_page'));
  }

  // fix for activateUrl on install now button
  public function network_admin_url($url, $path) {

    if (wp_doing_ajax() && !is_network_admin()) {
      if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'install-plugin') {
        if (strpos($url, 'plugins.php') !== false) {
          $url = self_admin_url($path);
        }
      }
    }

    return $url;
  }

  public function add_redirect() {

    if (isset($_REQUEST['activate']) && $_REQUEST['activate'] == 'true') {
      if (wp_get_referer() == admin_url('admin.php?page=' . WOOCCM_PREFIX . '_suggestions')) {
        wp_redirect(admin_url('admin.php?page=' . WOOCCM_PREFIX . '_suggestions'));
      }
    }
  }

  public function init() {
    add_action('admin_menu', array($this, 'add_menu'));
    add_action('admin_init', array($this, 'add_redirect'));
    add_filter('network_admin_url', array($this, 'network_admin_url'), 10, 2);
  }

}

WOOCCM_Checkout_Suggestions_Controller::instance();

