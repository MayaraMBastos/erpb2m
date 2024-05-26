<?php

class WOOCCM_Field {

  protected $fields = array();
  protected $prefix = '';
  protected $option_name = '';
  protected $defaults = array();

  protected function order_fields($a, $b) {

    if (!isset($a['order']) || !isset($b['order']))
      return 0;

    if ($a['order'] == $b['order'])
      return 0;

    return ( $a['order'] < $b['order'] ) ? -1 : 1;
  }

  protected function duplicated_name($name, $fields) {

    if (!empty($fields)) {
      if (is_array($fields)) {
        foreach ($fields as $item) {
          if (isset($item['name']) && $item['name'] == $name) {
            return true;
          }
        }
      }
    }

    return false;
  }

  public function get_field_id($fields, $key = 'key', $value) {

    if (count($fields)) {

      foreach ($fields as $id => $field) {
        if ($field[$key] == $value) {
          return $id;
        }
      }
    }

    return 0;
  }

  public function get_next_id($fields) {

    if (count($fields)) {
      return max(array_keys($fields)) + 1;
    }

    return 0;
  }

  public function get_name($field_id) {
    return WOOCCM_PREFIX . $field_id;
  }

  public function get_key($prefix = '', $name) {
    return sprintf("%s_%s", $prefix, $name);
  }

  public function get_conditional_types() {

    $fields = self::get_types();

    unset($fields['heading']);
    unset($fields['button']);

    return array_keys($fields);
  }

  public function get_option_types() {

    return array(
        'multicheckbox',
        'multiselect',
        'select',
        'radio'
    );
  }

  public function get_multiple_types() {

    return array(
        'multicheckbox',
        'multiselect',
    );
  }

  public function get_template_types() {

    return array(
        'heading',
        'message',
        'button',
        'file',
//        'country',
//        'state'
    );
  }

  public function get_disabled_types() {

    return apply_filters('wooccm_fields_disabled_types', array(
        'message',
        'button',
    ));
  }

  public function get_types() {

    return apply_filters('wooccm_fields_types', array(
        'heading' => 'Heading',
        'message' => 'Message',
        'button' => 'Button',
        'text' => 'Text',
        'textarea' => 'Textarea',
        'password' => 'Password',
        'select' => 'Select',
        'radio' => 'Radio',
        'checkbox' => 'Checkbox',
        'time' => 'Timepicker',
        'date' => 'Datepicker',
        'country' => 'Country',
        'state' => 'State',
        'multiselect' => 'Multiselect',
        'multicheckbox' => 'Multicheckbox',
        'colorpicker' => 'Colorpicker',
        'file' => 'File',
    ));
  }

  function get_args() {

    return array(
        'id' => null,
        'key' => '',
        'name' => '',
        'type' => 'text',
        'disabled' => false,
        'order' => null,
        'priority' => null,
        'label' => '',
        'placeholder' => '',
        'description' => '',
        'default' => '',
        'position' => '',
        'clear' => false,
        'options' => array(
            0 => array(
                'label' => esc_html__('Option', 'woocommerce-checkout-manager'),
                'add_price_total' => 0,
                'add_price_type' => 'fixed',
                'add_price_tax' => 0,
                'default' => '',
                'order' => 0
            )
        ),
        'required' => false,
        'message_type' => 'info',
        'button_type' => '',
        'button_link' => '',
        'class' => array(),
        // Display
        // -------------------------------------------------------------------
        'show_cart_minimum' => 0,
        'show_cart_maximun' => 0,
        'show_role' => array(),
        'hide_role' => array(),
        'more_product' => false,
        'show_product' => array(),
        'hide_product' => array(),
        'show_product_cat' => array(),
        'hide_product_cat' => array(),
        'hide_account' => false,
        'hide_checkout' => false,
        'hide_email' => false,
        'hide_order' => false,
        'hide_invoice' => false,
        // Pickers
        // -------------------------------------------------------------------
        'time_limit_start' => null,
        'time_limit_end' => null,
        'time_limit_interval' => null,
        'date_limit' => 'fixed',
        'date_format' => '',
        'date_limit_variable_min' => -1,
        'date_limit_variable_max' => 1,
        'date_limit_fixed_min' => date('Y-m-d'),
        'date_limit_fixed_max' => date('Y-m-d'),
        'date_limit_days' => array(),
        // Price
        // -------------------------------------------------------------------
        'add_price' => false,
        'add_price_name' => '',
        'add_price_total' => null,
        'add_price_type' => 'fixed',
        'add_price_tax' => false,
        'extra_class' => '',
        // Conditional
        // -------------------------------------------------------------------
        'conditional' => false,
        'conditional_parent_key' => '',
        'conditional_parent_value' => '',
        // State
        // -------------------------------------------------------------------
        'country' => '',
        // Select 2
        // -------------------------------------------------------------------
        'select2' => false,
        'select2_allowclear' => false,
        'select2_selectonclose' => false,
        'select2_closeonselect' => false,
        'select2_search' => false,
        // Upload
        // -------------------------------------------------------------------
        'file_limit' => 1,
        'file_types' => array(),
        // Color
        // -------------------------------------------------------------------
        'pickertype' => '',
        // Listing
        // -------------------------------------------------------------------
        'listable' => false,
        'sortable' => false,
        'filterable' => false,
    );
  }

  public function sanitize_field_data($field_data) {

    $args = $this->get_args();

    foreach ($field_data as $key => $value) {

      if (array_key_exists($key, $args)) {

        $type = $args[$key];

        if (is_null($type) && !is_numeric($value)) {
          $field_data[$key] = (int) $value;
        } elseif (is_bool($type) && !is_bool($value)) {
          $field_data[$key] = ($value === 'true' || $value === '1' || $value === 1);
        } elseif (is_string($type) && !is_string($value)) {
          $field_data[$key] = strval($value);
        } elseif (is_array($type) && !is_array($value)) {
          $field_data[$key] = (array) $type;
        }
      } else {
        unset($field_data[$key]);
      }
    }

    return $field_data;
  }

  public function sanitize_field($field_id, $field, $fields) {

    $field = wp_parse_args($field, $this->get_args());

    $field['id'] = $field_id;

    if (empty($field['name'])) {

      $field['name'] = $this->get_name($field_id);

      if ($this->duplicated_name($field['name'], $fields)) {
        $field['name'] .= 'b';
      }
    }

    $field['key'] = $this->get_key($this->prefix, $field['name']);

    if (empty($field['position']) && is_array($field['class'])) {
      if ($position = array_intersect((array) $field['class'], array('form-row-wide', 'form-row-first', 'form-row-last'))) {
        $field['position'] = $position[0];
      } else {
        $field['position'] = 'form-row-wide';
      }
    }

    if (empty($field['order'])) {
      $field['order'] = $field_id + 1;
    }

    if (empty($field['order'])) {
      $field['order'] = $field_id + 1;
    }

    if (!empty($field['conditional_parent_key'])) {

      if (strpos($field['conditional_parent_key'], $this->prefix) === false) {
        $field['conditional_parent_key'] = sprintf('%s_%s', $this->prefix, $field['conditional_parent_key']);
      }

      if ($field['conditional_parent_key'] == $field['key']) {
        $field['conditional_parent_key'] = '';
      }
    }

    if (count($field['options']) > 1) {
      uasort($field['options'], array(__CLASS__, 'order_fields'));
    }

    return wp_unslash($field);
  }

  public function get_defaults() {
    return $this->defaults;
  }

  public function get_default_fields() {

    $fields = array();

    if ($this->prefix !== 'additional') {

      $prefix = sprintf('%s_', $this->prefix);

      //$filters = WOOCCM_Fields_Register::instance();
      //fix nesting level
      //remove_filter('woocommerce_' . $prefix . 'fields', array($filters, 'add_' . $prefix . 'fields'));
      remove_all_filters('woocommerce_' . $prefix . 'fields');

      foreach (WC()->countries->get_address_fields('', $prefix) as $key => $field) {

        $field['key'] = $key;
        $field['name'] = str_replace($prefix, '', $key);

        $fields[] = $field;
      }
    }

    return $fields;
  }

  public function get_fields() {
// breakes re order after reload
//    if (count($this->fields)) {
//      return $this->fields;
//    }

    if ($fields = $this->get_option()) {

      foreach ($fields as $field_id => $field) {
        $this->fields[$field_id] = apply_filters('wooccm_checkout_field_filter', $this->sanitize_field($field_id, $field, $fields), $field_id);
      }

      // Resort the fields by order
      uasort($this->fields, array(__CLASS__, 'order_fields'));
    }

    return apply_filters('wooccm_' . $this->prefix . '_fields', $this->fields);
  }

  public function update_fields($fields) {

    if (is_array($fields)) {

      foreach ($fields as $field_id => $field) {
        if (!array_key_exists('name', $field)) {
          return false;
        }
      }

      //reorder array based on ids
      ksort($fields);

      if ($this->update_option($fields)) {
        return $fields;
      }
    }

    return false;
  }

  public function delete_fields() {
    $this->delete_option();
  }

  // Field
  // ---------------------------------------------------------------------------

  public function get_field($field_id) {

    if ($fields = $this->get_fields()) {
      if (isset($fields[$field_id])) {
        return $fields[$field_id];
//        $field = $fields[$field_id];
//        return $this->sanitize_field($field_id, $field, $fields);
      }
    }
  }

  public function update_field($field_id, $field_data) {

    $fields = $this->get_fields();

    if (isset($fields[$field_id])) {

      $field_data = $this->sanitize_field_data($field_data);

      $fields[$field_id] = array_replace($fields[$field_id], $field_data);

      if ($this->update_fields($fields)) {
        return $fields[$field_id];
      }
    }

    return false;
  }

  public function add_field($field_data) {

    $fields = $this->get_fields();

    $field_id = $this->get_next_id($fields);

    $field_data = $this->sanitize_field_data($field_data);

    $field_data = $this->sanitize_field($field_id, $field_data, $fields);

    $field_data = wp_parse_args($field_data, $this->get_args());

    $fields[] = $field_data;

    if ($this->update_fields($fields)) {
      return $field_data;
    }

    return false;
  }

  public function delete_field($field_id) {

    $fields = $this->get_fields();

    unset($fields[$field_id]);

    if ($this->update_fields($fields)) {
      return true;
    }

    return false;
  }

  // Core
  // -------------------------------------------------------------------------

  protected function get_option($defaults = array()) {

    if ($fields = get_option($this->option_name, false)) {
      return $fields;
    }

    return $this->get_default_fields();
  }

  protected function update_option($fields) {

    update_option($this->option_name, $fields);

    return true;
  }

  protected function delete_option() {

    delete_option($this->option_name);
    add_option($this->option_name);

    return false;
  }

}
