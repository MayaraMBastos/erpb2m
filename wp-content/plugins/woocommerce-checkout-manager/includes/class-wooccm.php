<?php

final class WOOCCM {

  protected static $_instance;

  public function __construct() {

    include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm-install.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm-notices.php' );

    register_activation_hook(WOOCCM_PLUGIN_FILE, array('WOOCCM_Install', 'install'));

    load_plugin_textdomain('woocommerce-checkout-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    add_action('woocommerce_init', array($this, 'init_session'));
    add_action('woocommerce_init', array($this, 'includes'));
    add_action('woocommerce_init', array($this, 'field'));
    add_action('woocommerce_checkout_order_processed', array($this, 'clear_session'), 150);
  }

  public static function instance() {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function field() {

    $this->billing = WOOCCM_Field_Billing::instance();
    $this->shipping = WOOCCM_Field_Shipping::instance();
    $this->additional = WOOCCM_Field_Additional::instance();

    include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm-compatibility.php' );
  }

  public function includes() {
    include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm-admin.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/class-wooccm-upload.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-checkout.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-field.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-order.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-email.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-advanced.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-premium.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-suggestions.php' );
  }

  public function register_scripts() {
    
    global $wp_version;

    // Frontend
    // -----------------------------------------------------------------------
    wp_register_style('wooccm', plugins_url('assets/frontend/css/wooccm.css', WOOCCM_PLUGIN_FILE), false, WOOCCM_PLUGIN_VERSION, 'all');

    wp_register_script('wooccm-checkout', plugins_url('assets/frontend/js/wooccm-checkout.js', WOOCCM_PLUGIN_FILE), array('jquery', 'selectWoo'), WOOCCM_PLUGIN_VERSION, true);

    wp_localize_script('wooccm-checkout', 'wooccm_upload', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wooccm_upload'),
        'limit' => array(
            'max_file_size' => wp_max_upload_size(),
            'max_files' => 4,
        //'mime_types' => $this->get_mime_types(),
        ),
        'icons' => array(
            'interactive' => site_url('wp-includes/images/media/interactive.png'),
            'spreadsheet' => site_url('wp-includes/images/media/spreadsheet.png'),
            'archive' => site_url('wp-includes/images/media/archive.png'),
            'audio' => site_url('wp-includes/images/media/audio.png'),
            'text' => site_url('wp-includes/images/media/text.png'),
            'video' => site_url('wp-includes/images/media/video.png')
        ),
        'message' => array(
            'uploading' => esc_html__('Uploading, please wait...', 'woocommerce-checkout-manager'),
            'saving' => esc_html__('Saving, please wait...', 'woocommerce-checkout-manager'),
            'success' => esc_html__('Files uploaded successfully.', 'woocommerce-checkout-manager'),
            'deleted' => esc_html__('Deleted successfully.', 'woocommerce-checkout-manager'),
        )
    ));
  
    // Colorpicker
    // ---------------------------------------------------------------------
    wp_register_script('iris', admin_url('js/iris.min.js'), array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), $wp_version);

    wp_register_script('wp-color-picker', admin_url('js/color-picker.min.js'), array('iris'), $wp_version);

    wp_localize_script('wp-color-picker', 'wpColorPickerL10n', array(
        'clear' => __('Clear'),
        'defaultString' => __('Default'),
        'pick' => __('Select Color'),
        'current' => __('Current Color'),
    ));

    wp_register_script('farbtastic', admin_url('js/farbtastic.js'), array('jquery'), $wp_version);

    // Admin
    // -------------------------------------------------------------------------

    wp_register_script('wooccm-order-upload', plugins_url('assets/frontend/js/wooccm-order-upload.js', WOOCCM_PLUGIN_FILE), array(), WOOCCM_PLUGIN_VERSION, true);

    wp_localize_script('wooccm-order-upload', 'wooccm_upload', array(
        'ajax_url' => admin_url('admin-ajax.php?metabox=' . is_admin()),
        'nonce' => wp_create_nonce('wooccm_upload'),
        'message' => array(
            'uploading' => esc_html__('Uploading, please wait...', 'woocommerce-checkout-manager'),
            'saving' => esc_html__('Saving, please wait...', 'woocommerce-checkout-manager'),
            'success' => esc_html__('Files uploaded successfully.', 'woocommerce-checkout-manager'),
            'deleted' => esc_html__('Deleted successfully.', 'woocommerce-checkout-manager'),
        ),
        'icons' => array(
            'interactive' => site_url('wp-includes/images/media/interactive.png'),
            'spreadsheet' => site_url('wp-includes/images/media/spreadsheet.png'),
            'archive' => site_url('wp-includes/images/media/archive.png'),
            'audio' => site_url('wp-includes/images/media/audio.png'),
            'text' => site_url('wp-includes/images/media/text.png'),
            'video' => site_url('wp-includes/images/media/video.png')
        )
    ));
  }

  public function clear_session() {
    unset(WC()->session->wooccm);
  }

  public function init_session() {

    if (isset(WC()->session) && !WC()->session->wooccm) {

      WC()->session->wooccm = array(
          'fields' => array(),
          'fees' => array(),
          'files' => array(),
      );
    }
  }

}
