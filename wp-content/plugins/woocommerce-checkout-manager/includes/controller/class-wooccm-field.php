<?php

class WOOCCM_Field_Controller {

  protected static $_instance;
  public $billing;

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

  function enqueue_scripts() {

    global $current_section;

    wp_register_script('wooccm-admin-field', plugins_url('assets/backend/js/wooccm-admin-field.js', WOOCCM_PLUGIN_FILE), array('jquery', 'jquery-ui-datepicker', 'backbone', 'wp-util'), WOOCCM_PLUGIN_VERSION, true);

    wp_localize_script('wooccm-admin-field', 'wooccm_field', array(
        'ajax_url' => admin_url('admin-ajax.php?section=' . $current_section),
        'nonce' => wp_create_nonce('wooccm_field'),
        'args' => WOOCCM()->billing->get_args(),
        'message' => array(
            'remove' => esc_html__('Are you sure you want to remove this field?', 'woocommerce-checkout-manager'),
            'reset' => esc_html__('Are you sure you want to reset this fields?', 'woocommerce-checkout-manager')
        )
    ));

    if (isset($_GET['tab']) && $_GET['tab'] === WOOCCM_PREFIX) {
      wp_enqueue_style('media-views');
      wp_enqueue_script('wooccm-admin-field');
    }
  }

  public function get_product_categories() {

    $args = array(
        'taxonomy' => 'product_cat',
        'orderby' => 'id',
        'order' => 'ASC',
        'hide_empty' => true,
        'fields' => 'all');

    return get_terms($args);
  }

  // Ajax
  // ---------------------------------------------------------------------------

  public function ajax_toggle_field_attribute() {

    if (current_user_can('manage_woocommerce') && check_ajax_referer('wooccm_field', 'nonce') && isset($_REQUEST['field_id']) && isset($_REQUEST['field_attr'])) {

      $field_id = wc_clean(wp_unslash($_REQUEST['field_id']));
      $attr = wc_clean(wp_unslash($_REQUEST['field_attr']));

      $status = $this->toggle_field_attribute($field_id, $attr);

      wp_send_json_success($status);
    }

    wp_send_json_error(esc_html__('Unknow error', 'woocommerce-checkout-manager'));
  }

  public function ajax_change_field_attribute() {

    if (current_user_can('manage_woocommerce') && check_ajax_referer('wooccm_field', 'nonce') && isset($_REQUEST['field_id']) && isset($_REQUEST['field_attr']) && isset($_REQUEST['field_value'])) {


      if (array_key_exists('section', $_REQUEST)) {

        $section = wc_clean(wp_unslash($_REQUEST['section']));

        $field_id = wc_clean(wp_unslash($_REQUEST['field_id']));
        $attr = wc_clean(wp_unslash($_REQUEST['field_attr']));
        $value = wc_clean(wp_unslash($_REQUEST['field_value']));

        $field_data = array($attr => $value);

        if (isset(WOOCCM()->$section)) {

          $field = WOOCCM()->$section->update_field($field_id, $field_data);

          wp_send_json_success($field);
        }
      }
    }

    wp_send_json_error(esc_html__('Unknow error', 'woocommerce-checkout-manager'));
  }

  public function ajax_save_field() {

    if (current_user_can('manage_woocommerce') && check_ajax_referer('wooccm_field', 'nonce') && isset($_REQUEST['field_data'])) {

      $field_data = array(); //WOOCCM()->billing->get_args();

      parse_str($_REQUEST['field_data'], $field_data);

      if (array_key_exists('field_id', $_REQUEST)) {

        $field_id = wc_clean(wp_unslash($_REQUEST['field_id']));

        if ($field = $this->save_modal_field($field_id, $field_data)) {

          wp_send_json_success($field);
        }
      } else {

        if ($field = $this->add_modal_field($field_data)) {

          wp_send_json_success($field);
        }
      }
    }

    wp_send_json_error(esc_html__('Unknow error', 'woocommerce-checkout-manager'));
  }

  public function ajax_delete_field() {

    if (current_user_can('manage_woocommerce') && check_ajax_referer('wooccm_field', 'nonce') && isset($_REQUEST['field_id'])) {

      $field_id = wc_clean(wp_unslash($_REQUEST['field_id']));

      if ($this->delete_field($field_id)) {

        wp_send_json_success($field_id);
      }
    }

    wp_send_json_error(esc_html__('Unknow error', 'woocommerce-checkout-manager'));
  }

  public function ajax_reset_fields() {

    if (current_user_can('manage_woocommerce') && check_ajax_referer('wooccm_field', 'nonce')) {

      if (array_key_exists('section', $_REQUEST)) {

        $section = wc_clean(wp_unslash($_REQUEST['section']));

        if (isset(WOOCCM()->$section)) {

          WOOCCM()->$section->delete_fields();

          wp_send_json_success();
        }
      }
    }

    wp_send_json_error(esc_html__('Unknow error', 'woocommerce-checkout-manager'));
  }

  public function ajax_load_field() {

    if (current_user_can('manage_woocommerce') && check_ajax_referer('wooccm_field', 'nonce') && isset($_REQUEST['field_id'])) {

      $field_id = wc_clean(wp_unslash($_REQUEST['field_id']));

      if ($field = $this->get_modal_field($field_id)) {
        wp_send_json_success($field);
      }

      wp_send_json_error(esc_html__('Undefined field id', 'woocommerce-checkout-manager'));
    }

    wp_send_json_error(esc_html__('Unknow error', 'woocommerce-checkout-manager'));
  }

  // Modal
  // ---------------------------------------------------------------------------

  public function get_modal_field($field_id) {

    if (array_key_exists('section', $_REQUEST)) {

      $section = wc_clean(wp_unslash($_REQUEST['section']));

      if (isset(WOOCCM()->$section)) {

        if ($fields = WOOCCM()->$section->get_fields()) {

          if (isset($fields[$field_id])) {

            $field = $fields[$field_id];

            if (!empty($field['show_product'])) {
              $field['show_product_selected'] = array_filter(array_combine((array) $field['show_product'], array_map('get_the_title', (array) $field['show_product'])));
            }
            if (!empty($field['hide_product'])) {
              $field['hide_product_selected'] = array_filter(array_combine((array) $field['hide_product'], array_map('get_the_title', (array) $field['hide_product'])));
            }

            if (!empty($field['conditional_parent_key']) && $field['conditional_parent_key'] != $field['key']) {

//              $parent_id = @max(array_keys(array_column($fields, 'key'), $field['conditional_parent_key']));
              $parent_id = WOOCCM()->$section->get_field_id($fields, 'key', $field['conditional_parent_key']);

              if (isset($fields[$parent_id])) {
                $field['parent'] = $fields[$parent_id];
              }
            }

            //don't remove empty attr because previus data remain
            //$field = array_filter($field);

            return $field;
          }
        }
      }
    }
  }

  public function ajax_load_parent() {

    if (!empty($_REQUEST['conditional_parent_key'])) {

      $key = $_REQUEST['conditional_parent_key'];

      if (array_key_exists('section', $_REQUEST)) {

        $section = wc_clean(wp_unslash($_REQUEST['section']));

        if (isset(WOOCCM()->$section)) {

          if ($fields = WOOCCM()->$section->get_fields()) {

            $parent_id = WOOCCM()->$section->get_field_id($fields, 'key', $key);

            if (isset($fields[$parent_id])) {
              wp_send_json_success($fields[$parent_id]);
            }
          }
        }
      }
    }
  }

  // Save
  // ---------------------------------------------------------------------------

  public function toggle_field_attribute($field_id, $attr) {

    if (array_key_exists('section', $_REQUEST)) {

      $section = wc_clean(wp_unslash($_REQUEST['section']));

      if (isset(WOOCCM()->$section)) {

        if ($field = WOOCCM()->$section->get_field($field_id)) {

          $field_data = array($attr => !(bool) @$field[$attr]);

          $field = WOOCCM()->$section->update_field($field_id, $field_data);

          return $field_data[$attr];
        }
      }
    }
  }

  public function save_modal_field($field_id, $field_data) {

    if (array_key_exists('section', $_REQUEST)) {

      $section = wc_clean(wp_unslash($_REQUEST['section']));

      if (isset(WOOCCM()->$section)) {

        // fix unchecked checkboxes
        $field_data = wp_parse_args($field_data, WOOCCM()->$section->get_args());

        // don't override
        unset($field_data['order']);
        unset($field_data['required']);
        unset($field_data['position']);
        unset($field_data['disabled']);

        return WOOCCM()->$section->update_field($field_id, $field_data);
      }
    }
  }

  public function add_modal_field($field_data) {

    if (array_key_exists('section', $_REQUEST)) {

      $section = wc_clean(wp_unslash($_REQUEST['section']));

      if (isset(WOOCCM()->$section)) {

        return WOOCCM()->$section->add_field($field_data);
      }
    }
  }

  public function delete_field($field_id) {

    if (array_key_exists('section', $_REQUEST)) {

      $section = wc_clean(wp_unslash($_REQUEST['section']));

      if (isset(WOOCCM()->$section)) {

        return WOOCCM()->$section->delete_field($field_id);
      }
    }
  }

  function save_field_order() {

    global $current_section;

    if (in_array($current_section, array('billing', 'shipping', 'additional'))) {

      $section = wc_clean(wp_unslash($current_section));

      if (array_key_exists('field_order', $_POST)) {

        $field_order = wc_clean(wp_unslash($_POST['field_order']));

        if (is_array($field_order) && count($field_order) > 0) {

          if (isset(WOOCCM()->$section)) {

            $fields = WOOCCM()->$section->get_fields();

            $loop = 1;

            foreach ($field_order as $field_id) {

              if (isset($fields[$field_id])) {

                $fields[$field_id]['order'] = $loop;

                $loop++;
              }
            }

            WOOCCM()->$section->update_fields($fields);
          }
        }
      }
    }
  }

  function includes() {

    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-field-billing.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-field-shipping.php' );
    include_once( WOOCCM_PLUGIN_DIR . 'includes/controller/class-wooccm-field-additional.php' );

    if (!is_admin()) {
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-register.php' );
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-additional.php' );
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-disable.php' );
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-conditional.php' );
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-handler.php' );
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-i18n.php' );
      include_once( WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-filters.php' );
    }
  }

  function init() {

    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('wp_ajax_wooccm_load_parent', array($this, 'ajax_load_parent'));
    add_action('wp_ajax_wooccm_load_field', array($this, 'ajax_load_field'));
    add_action('wp_ajax_wooccm_save_field', array($this, 'ajax_save_field'));
    add_action('wp_ajax_wooccm_delete_field', array($this, 'ajax_delete_field'));
    add_action('wp_ajax_wooccm_reset_fields', array($this, 'ajax_reset_fields'));
    add_action('wp_ajax_wooccm_change_field_attribute', array($this, 'ajax_change_field_attribute'));
    add_action('wp_ajax_wooccm_toggle_field_attribute', array($this, 'ajax_toggle_field_attribute'));
    add_action('woocommerce_settings_save_' . WOOCCM_PREFIX, array($this, 'save_field_order'));
  }

}

WOOCCM_Field_Controller::instance();
