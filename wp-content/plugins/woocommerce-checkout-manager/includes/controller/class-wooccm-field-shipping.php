<?php

class WOOCCM_Field_Controller_Shipping extends WOOCCM_Field_Controller {

  protected static $_instance;
  public $shipping;

  public function __construct() {
    $this->includes();
    $this->init();
  }

  public static function instance() {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function includes() {
    include_once( WOOCCM_PLUGIN_DIR . 'includes/model/class-wooccm-field-shipping.php' );
  }

  function init() {
    add_action('wooccm_sections_header', array($this, 'add_header'));
    add_action('woocommerce_sections_' . WOOCCM_PREFIX, array($this, 'add_section'), 99);
    add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'add_order_data'));
  }

  // Admin
  // ---------------------------------------------------------------------------

  public function add_header() {
    global $current_section;
    ?>
    <li><a href="<?php echo admin_url('admin.php?page=wc-settings&tab=wooccm&section=shipping'); ?>" class="<?php echo ( $current_section == 'shipping' ? 'current' : '' ); ?>"><?php esc_html_e('Shipping', 'woocommerce-checkout-manager'); ?></a> | </li>
    <?php
  }

  public function add_section() {

    global $current_section, $wp_roles, $wp_locale;

    if ('shipping' == $current_section) {

      $fields = WOOCCM()->shipping->get_fields();
      $defaults = WOOCCM()->shipping->get_defaults();
      $types = WOOCCM()->shipping->get_types();
      $conditionals = WOOCCM()->shipping->get_conditional_types();
      $option = WOOCCM()->billing->get_option_types();
      $multiple = WOOCCM()->billing->get_multiple_types();
      $template = WOOCCM()->billing->get_template_types();
      $disabled = WOOCCM()->billing->get_disabled_types();
      $product_categories = $this->get_product_categories();

      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/backend/pages/shipping.php' );
    }
  }

  // Admin Order
  // ---------------------------------------------------------------------------

  function add_order_data($order) {

    if ($fields = WOOCCM()->shipping->get_fields()) {

      $defaults = WOOCCM()->shipping->get_defaults();

      foreach ($fields as $field_id => $field) {

        if (!in_array($field['name'], $defaults)) {

          $key = sprintf('_%s', $field['key']);

          if ($value = get_post_meta($order->get_id(), $key, true)) {
            ?>
            <p id="<?php echo esc_attr($field['key']); ?>" class="form-field form-field-wide form-field-type-<?php echo esc_attr($field['type']); ?>">
              <strong title="<?php echo esc_attr(sprintf(__('ID: %s | Field Type: %s', 'woocommerce-checkout-manager'), $key, __('Generic', 'woocommerce-checkout-manager'))); ?>">
                <?php echo esc_attr(trim($field['label'])); ?>:
              </strong>
              <br/>
              <?php echo esc_html($value); ?>
            </p>
            <?php
          }
        }
      }
    }
  }

}

WOOCCM_Field_Controller_Shipping::instance();
