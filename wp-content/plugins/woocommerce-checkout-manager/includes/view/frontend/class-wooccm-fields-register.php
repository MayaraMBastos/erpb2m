<?php

class WOOCCM_Fields_Register {

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

  public function add_billing_fields($fields) {
    return WOOCCM()->billing->get_fields();
  }

  public function add_shipping_fields($fields) {
    return WOOCCM()->shipping->get_fields();
  }

  public function add_additional_fields($fields) {

    $fields['additional'] = WOOCCM()->additional->get_fields();

    return $fields;
  }

  public function add_keys($fields) {

    $frontend_fields = array();

    foreach ($fields as $field_id => $field) {
      if (!empty($field['key']) && empty($field['disabled'])) {
        $frontend_fields[$field['key']] = $field;
      }
    }

    return $frontend_fields;
  }

  public function init() {

    // Add keys
    // -----------------------------------------------------------------------
    add_filter('wooccm_additional_fields', array($this, 'add_keys'));
    add_filter('wooccm_billing_fields', array($this, 'add_keys'));
    add_filter('wooccm_shipping_fields', array($this, 'add_keys'));

    // Billing fields
    // -----------------------------------------------------------------------
    add_filter('woocommerce_billing_fields', array($this, 'add_billing_fields'));

    // Shipping fields
    // -----------------------------------------------------------------------
    add_filter('woocommerce_shipping_fields', array($this, 'add_shipping_fields'));

    // Additional fields
    // -----------------------------------------------------------------------
    add_filter('woocommerce_checkout_fields', array($this, 'add_additional_fields'));
  }

}

WOOCCM_Fields_Register::instance();
