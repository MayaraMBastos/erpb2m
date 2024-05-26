<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @subpackage Woocommerce_Conditional_Product_Fees_For_Checkout_Pro/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @subpackage Woocommerce_Conditional_Product_Fees_For_Checkout_Pro/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public
{
    private static  $admin_object = null ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        self::$admin_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( '', '' );
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/woocommerce-conditional-product-fees-for-checkout-public.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin_name . 'font-awesome',
            WCPFC_PRO_PLUGIN_URL . 'admin/css/font-awesome.min.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/woocommerce-conditional-product-fees-for-checkout-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_localize_script( $this->plugin_name, 'my_ajax_object', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ) );
    }
    
    /**
     * Override WooCommerce file in our plugin
     *
     * @param string $template
     * @param string $template_name
     * @param mixed  $template_path
     *
     * @return string $template
     * @since    1.0.0
     *
     *
     */
    function woocommerce_locate_template_product_fees_conditions( $template, $template_name, $template_path )
    {
        global  $woocommerce ;
        $_template = $template;
        if ( !$template_path ) {
            $template_path = $woocommerce->template_url;
        }
        $plugin_path = wcpfc_pro_path() . '/woocommerce/';
        $template = locate_template( array( $template_path . $template_name, $template_name ) );
        // Modification: Get the template from this plugin, if it exists
        if ( !$template && file_exists( $plugin_path . $template_name ) ) {
            $template = $plugin_path . $template_name;
        }
        if ( !$template ) {
            $template = $_template;
        }
        // Return what we found
        return $template;
    }
    
    /**
     * Add fees in cart based on rule
     *
     * @param array $package
     * //package->fees_api()->add_fee
     *
     * @since    1.0.0
     *
     * @uses     Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin::wcpfc_pro_get_default_langugae_with_sitpress()
     * @uses     wcpfc_pro_get_woo_version_number()
     * @uses     WC_Cart::get_cart()
     * @uses     wcpfc_pro_fees_per_qty_on_ap_rules_off()
     * @uses     wcpfc_pro_cart_subtotal_before_discount_cost()
     * @uses     wcpfc_pro_cart_subtotal_after_discount_cost()
     * @uses     wcpfc_pro_match_country_rules()
     * @uses     wcpfc_pro_match_state_rules__premium_only()
     * @uses     wcpfc_pro_match_postcode_rules__premium_only()
     * @uses     wcpfc_pro_match_zone_rules__premium_only()
     * @uses     wcpfc_pro_match_variable_products_rule__premium_only()
     * @uses     wcpfc_pro_match_simple_products_rule()
     * @uses     wcpfc_pro_match_category_rule__premium_only()
     * @uses     wcpfc_pro_match_tag_rule()
     * @uses     wcpfc_pro_match_user_rule()
     * @uses     wcpfc_pro_match_user_role_rule__premium_only()
     * @uses     wcpfc_pro_match_coupon_rule__premium_only()
     * @uses     wcpfc_pro_match_cart_subtotal_before_discount_rule()
     * @uses     wcpfc_pro_match_cart_subtotal_after_discount_rule__premium_only()
     * @uses     wcpfc_pro_match_cart_total_cart_qty_rule()
     * @uses     wcpfc_pro_match_cart_total_weight_rule__premium_only()
     * @uses     wcpfc_pro_match_shipping_class_rule__premium_only()
     * @uses     wcpfc_pro_match_payment_gateway_rule__premium_only()
     * @uses     wcpfc_pro_match_shipping_method_rule__premium_only()
     * @uses     wcpfc_pro_fee_array_column_public()
     * @uses     wcpfc_pro_match_product_per_qty__premium_only()
     * @uses     wcpfc_pro_match_category_per_qty__premium_only()
     * @uses     wcpfc_pro_match_total_cart_qty__premium_only()
     * @uses     wcpfc_pro_match_product_per_weight__premium_only()
     * @uses     wcpfc_pro_match_category_per_weight__premium_only()
     * @uses     wcpfc_pro_match_total_cart_weight__premium_only()
     * @uses     wcpfc_pro_calculate_advance_pricing_rule_fees()
     *
     */
    public function wcpfc_pro_conditional_fee_add_to_cart( $package )
    {
        global  $woocommerce_wpml, $sitepress ;
        $default_lang = self::$admin_object->wcpfc_pro_get_default_langugae_with_sitpress();
        $fees_args = array(
            'post_type'        => 'wc_conditional_fee',
            'post_status'      => 'publish',
            'posts_per_page'   => -1,
            'suppress_filters' => false,
        );
        $get_all_fees = get_transient( 'get_all_fees' );
        
        if ( false === $get_all_fees ) {
            $get_all_fees_query = new WP_Query( $fees_args );
            $get_all_fees = $get_all_fees_query->get_posts();
            set_transient( 'get_all_fees', $get_all_fees );
        }
        
        $wc_curr_version = $this->wcpfc_pro_get_woo_version_number();
        $cart_array = $this->wcpfc_pro_get_cart();
        $cart_main_product_ids_array = $this->wcpfc_pro_get_main_prd_id( $sitepress, $default_lang );
        $cart_product_ids_array = $this->wcpfc_pro_get_prd_var_id( $sitepress, $default_lang );
        $cart_sub_total = WC()->cart->subtotal;
        if ( !empty($get_all_fees) ) {
            foreach ( $get_all_fees as $fees ) {
                
                if ( !empty($sitepress) ) {
                    $fees_id = apply_filters(
                        'wpml_object_id',
                        $fees->ID,
                        'wc_conditional_fee',
                        true,
                        $default_lang
                    );
                } else {
                    $fees_id = $fees->ID;
                }
                
                
                if ( !empty($sitepress) ) {
                    
                    if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
                        $language_information = apply_filters( 'wpml_post_language_details', null, $fees_id );
                    } else {
                        $language_information = wpml_get_language_information( $fees_id );
                    }
                    
                    $post_id_language_code = $language_information['language_code'];
                } else {
                    $post_id_language_code = $default_lang;
                }
                
                
                if ( $post_id_language_code === $default_lang ) {
                    $is_passed = array();
                    $final_is_passed_general_rule = array();
                    $new_is_passed = array();
                    $final_passed = array();
                    $cart_based_qty = 0;
                    foreach ( $cart_array as $woo_cart_item_for_qty ) {
                        $cart_based_qty += $woo_cart_item_for_qty['quantity'];
                    }
                    $fee_title = get_the_title( $fees_id );
                    $title = ( !empty($fee_title) ? __( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ) : __( 'Fee', 'woocommerce-conditional-product-fees-for-checkout' ) );
                    $getFeesCostOriginal = get_post_meta( $fees_id, 'fee_settings_product_cost', true );
                    $getFeeType = get_post_meta( $fees_id, 'fee_settings_select_fee_type', true );
                    
                    if ( isset( $woocommerce_wpml ) && !empty($woocommerce_wpml->multi_currency) ) {
                        
                        if ( isset( $getFeeType ) && !empty($getFeeType) && 'fixed' === $getFeeType ) {
                            $getFeesCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $getFeesCostOriginal );
                        } else {
                            $getFeesCost = $getFeesCostOriginal;
                        }
                    
                    } else {
                        $getFeesCost = $getFeesCostOriginal;
                    }
                    
                    $getFeetaxable = get_post_meta( $fees_id, 'fee_settings_select_taxable', true );
                    $getFeeStartDate = get_post_meta( $fees_id, 'fee_settings_start_date', true );
                    $getFeeEndDate = get_post_meta( $fees_id, 'fee_settings_end_date', true );
                    $getFeeStatus = get_post_meta( $fees_id, 'fee_settings_status', true );
                    if ( isset( $getFeeStatus ) && 'off' === $getFeeStatus ) {
                        continue;
                    }
                    $fees_cost = $getFeesCost;
                    $get_condition_array = get_post_meta( $fees_id, 'product_fees_metabox', true );
                    $general_rule_match = 'all';
                    
                    if ( isset( $getFeeType ) && !empty($getFeeType) && $getFeeType === 'percentage' ) {
                        $fees_cost = $cart_sub_total * $getFeesCost / 100;
                    } else {
                        $fees_cost = $getFeesCost;
                    }
                    
                    
                    if ( !empty($get_condition_array) ) {
                        $country_array = array();
                        $product_array = array();
                        $tag_array = array();
                        $user_array = array();
                        $cart_total_array = array();
                        $quantity_array = array();
                        foreach ( $get_condition_array as $key => $value ) {
                            if ( array_search( 'country', $value, true ) ) {
                                $country_array[$key] = $value;
                            }
                            if ( array_search( 'product', $value, true ) ) {
                                $product_array[$key] = $value;
                            }
                            if ( array_search( 'tag', $value, true ) ) {
                                $tag_array[$key] = $value;
                            }
                            if ( array_search( 'user', $value, true ) ) {
                                $user_array[$key] = $value;
                            }
                            if ( array_search( 'cart_total', $value, true ) ) {
                                $cart_total_array[$key] = $value;
                            }
                            if ( array_search( 'quantity', $value, true ) ) {
                                $quantity_array[$key] = $value;
                            }
                            //Check if is country exist
                            
                            if ( is_array( $country_array ) && isset( $country_array ) && !empty($country_array) && !empty($cart_array) ) {
                                $country_passed = $this->wcpfc_pro_match_country_rules( $country_array, $general_rule_match );
                                
                                if ( 'yes' === $country_passed ) {
                                    $is_passed['has_fee_based_on_country'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_country'] = 'no';
                                }
                            
                            }
                            
                            //Check if is product exist
                            
                            if ( is_array( $product_array ) && isset( $product_array ) && !empty($product_array) && !empty($cart_product_ids_array) ) {
                                $product_passed = $this->wcpfc_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match );
                                
                                if ( 'yes' === $product_passed ) {
                                    $is_passed['has_fee_based_on_product'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_product'] = 'no';
                                }
                            
                            }
                            
                            //Check if is tag exist
                            
                            if ( is_array( $tag_array ) && isset( $tag_array ) && !empty($tag_array) && !empty($cart_main_product_ids_array) ) {
                                $tag_passed = $this->wcpfc_pro_match_tag_rule( $cart_main_product_ids_array, $tag_array, $general_rule_match );
                                
                                if ( 'yes' === $tag_passed ) {
                                    $is_passed['has_fee_based_on_tag'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_tag'] = 'no';
                                }
                            
                            }
                            
                            //Check if is user exist
                            
                            if ( is_array( $user_array ) && isset( $user_array ) && !empty($user_array) && !empty($cart_array) ) {
                                $user_passed = $this->wcpfc_pro_match_user_rule( $user_array, $general_rule_match );
                                
                                if ( 'yes' === $user_passed ) {
                                    $is_passed['has_fee_based_on_user'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_user'] = 'no';
                                }
                            
                            }
                            
                            //Check if is Cart Subtotal (Before Discount) exist
                            
                            if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && !empty($cart_total_array) && !empty($cart_array) ) {
                                $cart_total_before_passed = $this->wcpfc_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match );
                                
                                if ( 'yes' === $cart_total_before_passed ) {
                                    $is_passed['has_fee_based_on_cart_total_before'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_total_before'] = 'no';
                                }
                            
                            }
                            
                            //Check if is quantity exist
                            
                            if ( is_array( $quantity_array ) && isset( $quantity_array ) && !empty($quantity_array) && !empty($cart_array) ) {
                                $quantity_passed = $this->wcpfc_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match );
                                
                                if ( 'yes' === $quantity_passed ) {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                }
                            
                            }
                        
                        }
                        
                        if ( isset( $is_passed ) && !empty($is_passed) && is_array( $is_passed ) ) {
                            $fnispassed = array();
                            foreach ( $is_passed as $val ) {
                                if ( '' !== $val ) {
                                    $fnispassed[] = $val;
                                }
                            }
                            
                            if ( 'all' === $general_rule_match ) {
                                
                                if ( in_array( 'no', $fnispassed, true ) ) {
                                    $final_is_passed_general_rule['passed'] = 'no';
                                } else {
                                    $final_is_passed_general_rule['passed'] = 'yes';
                                }
                            
                            } else {
                                
                                if ( in_array( 'yes', $fnispassed, true ) ) {
                                    $final_is_passed_general_rule['passed'] = 'yes';
                                } else {
                                    $final_is_passed_general_rule['passed'] = 'no';
                                }
                            
                            }
                        
                        }
                    
                    }
                    
                    
                    if ( empty($final_is_passed_general_rule) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
                        $new_is_passed['passed'] = 'no';
                    } else {
                        
                        if ( !empty($final_is_passed_general_rule) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
                            $new_is_passed['passed'] = 'no';
                        } else {
                            
                            if ( empty($final_is_passed_general_rule) && in_array( '', $final_is_passed_general_rule, true ) ) {
                                $new_is_passed['passed'] = 'no';
                            } else {
                                if ( !empty($final_is_passed_general_rule) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
                                    $new_is_passed['passed'] = 'yes';
                                }
                            }
                        
                        }
                    
                    }
                    
                    
                    if ( in_array( 'no', $new_is_passed, true ) ) {
                        $final_passed['passed'] = 'no';
                    } else {
                        $final_passed['passed'] = 'yes';
                    }
                    
                    if ( isset( $final_passed ) && !empty($final_passed) && is_array( $final_passed ) ) {
                        
                        if ( !in_array( 'no', $final_passed, true ) ) {
                            $texable = ( isset( $getFeetaxable ) && !empty($getFeetaxable) && 'yes' === $getFeetaxable ? true : false );
                            $currentDate = strtotime( date( 'd-m-Y' ) );
                            $feeStartDate = ( isset( $getFeeStartDate ) && '' !== $getFeeStartDate ? strtotime( $getFeeStartDate ) : '' );
                            $feeEndDate = ( isset( $getFeeEndDate ) && '' !== $getFeeEndDate ? strtotime( $getFeeEndDate ) : '' );
                            $fees_cost = $this->wcpfc_pro_price_format( $fees_cost );
                            if ( ($currentDate >= $feeStartDate || '' === $feeStartDate) && ($currentDate <= $feeEndDate || '' === $feeEndDate) ) {
                                if ( '' !== $fees_cost ) {
                                    $package->fees_api()->add_fee( array(
                                        'id'         => $fees_id,
                                        'name'       => $title,
                                        'amount'     => $fees_cost,
                                        'taxable'    => $texable,
                                        'tax_class'  => '',
                                        'menu_order' => $fees->menu_order,
                                    ) );
                                }
                            }
                        }
                    
                    }
                }
            
            }
        }
    }
    
    /**
     * Match country rules
     *
     * @param array  $country_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @since    1.3.3
     *
     * @uses     WC_Customer::get_shipping_country()
     *
     */
    public function wcpfc_pro_match_country_rules( $country_array, $general_rule_match )
    {
        $selected_country = WC()->customer->get_shipping_country();
        $is_passed = array();
        foreach ( $country_array as $key => $country ) {
            
            if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    }
                
                }
                if ( empty($country['product_fees_conditions_values']) ) {
                    $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                }
            }
            
            if ( 'not_in' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_country', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match simple products rules
     *
     * @param array  $cart_product_ids_array
     * @param array  $product_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @uses     wcpfc_pro_fee_array_column_public()
     *
     * @since    1.3.3
     *
     */
    public function wcpfc_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match )
    {
        $is_passed = array();
        foreach ( $product_array as $key => $product ) {
            if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
                if ( !empty($product['product_fees_conditions_values']) ) {
                    foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                        settype( $product_id, 'integer' );
                        
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_product'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $product['product_fees_conditions_is'] ) {
                if ( !empty($product['product_fees_conditions_values']) ) {
                    foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                        settype( $product_id, 'integer' );
                        
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[$key]['has_fee_based_on_product'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match tag rules
     *
     * @param array  $cart_product_ids_array
     * @param array  $tag_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @uses     wcpfc_pro_fee_array_column_public()
     * @uses     wp_get_post_terms()
     * @uses     wcpfc_pro_array_flatten()
     *
     * @since    1.3.3
     *
     */
    public function wcpfc_pro_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match )
    {
        $tagid = array();
        $is_passed = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_tag = wp_get_post_terms( $product, 'product_tag', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_tag ) && !empty($cart_product_tag) && is_array( $cart_product_tag ) ) {
                $tagid[] = $cart_product_tag;
            }
        }
        $get_tag_all = array_unique( $this->wcpfc_pro_array_flatten( $tagid ) );
        foreach ( $tag_array as $key => $tag ) {
            if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_tag', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match user rules
     *
     * @param array  $user_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @uses     get_current_user_id()
     *
     * @since    1.3.3
     *
     * @uses     is_user_logged_in()
     */
    public function wcpfc_pro_match_user_rule( $user_array, $general_rule_match )
    {
        if ( !is_user_logged_in() ) {
            return false;
        }
        $current_user_id = get_current_user_id();
        $is_passed = array();
        foreach ( $user_array as $key => $user ) {
            $user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
            if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                }
            
            }
            if ( 'not_in' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                }
            
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on cart subtotal before discount
     *
     * @param string $wc_curr_version
     * @param array  $cart_total_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @uses     WC_Cart::get_subtotal()
     *
     * @since    1.3.3
     *
     */
    public function wcpfc_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match )
    {
        global  $woocommerce, $woocommerce_wpml ;
        
        if ( $wc_curr_version >= 3.0 ) {
            $total = $this->wcpfc_pro_get_cart_subtotal();
        } else {
            $total = $woocommerce->cart->subtotal;
        }
        
        
        if ( isset( $woocommerce_wpml ) && !empty($woocommerce_wpml->multi_currency) ) {
            $new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
        } else {
            $new_total = $total;
        }
        
        $is_passed = array();
        foreach ( $cart_total_array as $key => $cart_total ) {
            settype( $cart_total['product_fees_conditions_values'], 'float' );
            if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            
            if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
                $cart_total['product_fees_conditions_values'];
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            
            if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_total', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on total cart quantity
     *
     * @param array $quantity_array
     *
     * @return array $is_passed
     * @since    1.3.3
     *
     * @uses     WC_Cart::get_cart()
     *
     */
    public function wcpfc_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match )
    {
        $quantity_total = 0;
        foreach ( $cart_array as $woo_cart_item ) {
            $quantity_total += $woo_cart_item['quantity'];
        }
        $is_passed = array();
        foreach ( $quantity_array as $key => $quantity ) {
            settype( $quantity['product_fees_conditions_values'], 'integer' );
            if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_quantity', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array  $is_passed
     * @param string $has_fee_based
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.6
     *
     */
    public function wcpfc_pro_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match )
    {
        $main_is_passed = 'no';
        $flag = array();
        
        if ( !empty($is_passed) ) {
            foreach ( $is_passed as $key => $is_passed_value ) {
                
                if ( 'yes' === $is_passed_value[$has_fee_based] ) {
                    $flag[$key] = true;
                } else {
                    $flag[$key] = false;
                }
            
            }
            
            if ( 'any' === $general_rule_match ) {
                
                if ( in_array( true, $flag, true ) ) {
                    $main_is_passed = 'yes';
                } else {
                    $main_is_passed = 'no';
                }
            
            } else {
                
                if ( in_array( false, $flag, true ) ) {
                    $main_is_passed = 'no';
                } else {
                    $main_is_passed = 'yes';
                }
            
            }
        
        }
        
        return $main_is_passed;
    }
    
    /**
     * Count qty for product based and cart based when apply per qty option is on. This rule will apply when advance pricing rule will disable
     *
     * @param int    $fees_id
     * @param array  $cart_array
     * @param int    $products_based_qty
     * @param float  $products_based_subtotal
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $products_based_qty, $products_based_subtotal
     * @since 1.3.3
     *
     * @uses  get_post_meta()
     * @uses  get_post()
     * @uses  get_terms()
     *
     */
    public function wcpfc_pro_fees_per_qty_on_ap_rules_off(
        $fees_id,
        $cart_array,
        $products_based_qty,
        $products_based_subtotal,
        $sitepress,
        $default_lang
    )
    {
        $productFeesArray = get_post_meta( $fees_id, 'product_fees_metabox', true );
        $all_rule_check = array();
        if ( !empty($productFeesArray) ) {
            foreach ( $productFeesArray as $condition ) {
                
                if ( array_search( 'product', $condition, true ) ) {
                    $site_product_id = '';
                    $cart_final_products_array = array();
                    // Product Condition Start
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
                                settype( $product_id, 'integer' );
                                foreach ( $cart_array as $value ) {
                                    
                                    if ( !empty($sitepress) ) {
                                        $site_product_id = apply_filters(
                                            'wpml_object_id',
                                            $value['product_id'],
                                            'product',
                                            true,
                                            $default_lang
                                        );
                                    } else {
                                        $site_product_id = $value['product_id'];
                                    }
                                    
                                    if ( $product_id === $site_product_id ) {
                                        $cart_final_products_array[$value['product_id']] = $value;
                                    }
                                }
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
                                foreach ( $cart_array as $value ) {
                                    
                                    if ( !empty($value['variation_id']) || 0 !== $value['variation_id'] ) {
                                        $product_id_lan = $value['variation_id'];
                                    } else {
                                        $product_id_lan = $value['product_id'];
                                    }
                                    
                                    $_product = wc_get_product( $product_id_lan );
                                    
                                    if ( !$_product->is_virtual( 'yes' ) ) {
                                        
                                        if ( !empty($sitepress) ) {
                                            $site_product_id = apply_filters(
                                                'wpml_object_id',
                                                $product_id_lan,
                                                'product',
                                                true,
                                                $default_lang
                                            );
                                        } else {
                                            $site_product_id = $product_id_lan;
                                        }
                                        
                                        if ( $product_id !== $site_product_id ) {
                                            $cart_final_products_array[$product_id_lan] = $value;
                                        }
                                    }
                                
                                }
                            }
                        }
                    }
                    
                    if ( !empty($cart_final_products_array) ) {
                        foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
                            $line_item_subtotal = (double) $cart_item['line_subtotal'] + (double) $cart_item['line_subtotal_tax'];
                            $all_rule_check[$prd_id]['qty'] = $cart_item['quantity'];
                            $all_rule_check[$prd_id]['subtotal'] = $line_item_subtotal;
                        }
                    }
                    // Product Condition End
                }
                
                
                if ( array_search( 'variableproduct', $condition, true ) ) {
                    $site_product_id = '';
                    $cart_final_var_products_array = array();
                    // Variable Product Condition Start
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
                                settype( $product_id, 'integer' );
                                foreach ( $cart_array as $value ) {
                                    
                                    if ( !empty($sitepress) ) {
                                        $site_product_id = apply_filters(
                                            'wpml_object_id',
                                            $value['variation_id'],
                                            'product',
                                            true,
                                            $default_lang
                                        );
                                    } else {
                                        $site_product_id = $value['variation_id'];
                                    }
                                    
                                    if ( $product_id === $site_product_id ) {
                                        $cart_final_var_products_array[$value['product_id']] = $value;
                                    }
                                }
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $product_id ) {
                                settype( $product_id, 'integer' );
                                foreach ( $cart_array as $value ) {
                                    
                                    if ( !empty($value['variation_id']) || 0 !== $value['variation_id'] ) {
                                        $product_id_lan = $value['variation_id'];
                                    } else {
                                        $product_id_lan = $value['product_id'];
                                    }
                                    
                                    $_product = wc_get_product( $product_id_lan );
                                    
                                    if ( !$_product->is_virtual( 'yes' ) ) {
                                        
                                        if ( !empty($sitepress) ) {
                                            $site_product_id = apply_filters(
                                                'wpml_object_id',
                                                $product_id_lan,
                                                'product',
                                                true,
                                                $default_lang
                                            );
                                        } else {
                                            $site_product_id = $product_id_lan;
                                        }
                                        
                                        if ( $product_id !== $site_product_id ) {
                                            $cart_final_var_products_array[$product_id_lan] = $value;
                                        }
                                    }
                                
                                }
                            }
                        }
                    }
                    
                    if ( !empty($cart_final_var_products_array) ) {
                        foreach ( $cart_final_var_products_array as $prd_id => $cart_item ) {
                            $line_item_subtotal = (double) $cart_item['line_subtotal'] + (double) $cart_item['line_subtotal_tax'];
                            $all_rule_check[$prd_id]['qty'] = $cart_item['quantity'];
                            $all_rule_check[$prd_id]['subtotal'] = $line_item_subtotal;
                        }
                    }
                    // Variable Product Condition End
                }
                
                // Category Condition Start
                
                if ( array_search( 'category', $condition, true ) ) {
                    $final_cart_products_cats_ids = array();
                    $cart_final_cat_products_array = array();
                    $all_cats = get_terms( array(
                        'taxonomy' => 'product_cat',
                        'fields'   => 'ids',
                    ) );
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $category_id ) {
                                settype( $category_id, 'integer' );
                                $final_cart_products_cats_ids[] = $category_id;
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            $final_cart_products_cats_ids = array_diff( $all_cats, $condition['product_fees_conditions_values'] );
                        }
                    }
                    
                    $terms = array();
                    $cart_value_array = array();
                    foreach ( $cart_array as $value ) {
                        $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                        $cart_value_array[] = $value;
                        $term_ids = wp_get_post_terms( $value['product_id'], 'product_cat', array(
                            'fields' => 'ids',
                        ) );
                        foreach ( $term_ids as $term_id ) {
                            $terms[$value['product_id']][$term_id] = $value['quantity'] . "||" . $line_item_subtotal;
                        }
                    }
                    foreach ( $terms as $cart_product_key => $main_term_data ) {
                        foreach ( $main_term_data as $cart_term_id => $term_data ) {
                            $term_data_explode = explode( "||", $term_data );
                            $cart_term_qty = json_decode( $term_data_explode[0] );
                            $cart_term_subtotal = json_decode( $term_data_explode[1] );
                            if ( in_array( $cart_term_id, $final_cart_products_cats_ids, true ) ) {
                                $cart_final_cat_products_array[$cart_product_key][$cart_term_id] = $cart_term_qty . "||" . $cart_term_subtotal;
                            }
                        }
                    }
                    if ( !empty($cart_final_cat_products_array) ) {
                        foreach ( $cart_final_cat_products_array as $prd_id => $main_cart_item ) {
                            foreach ( $main_cart_item as $term_id => $cart_item ) {
                                $cart_item_explode = explode( "||", $cart_item );
                                $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                                $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                            }
                        }
                    }
                }
                
                // Category Condition End
                
                if ( array_search( 'tag', $condition, true ) ) {
                    // Tag Condition Start
                    $final_cart_products_tag_ids = array();
                    $cart_final_tag_products_array = array();
                    $all_tags = get_terms( array(
                        'taxonomy' => 'product_tag',
                        'fields'   => 'ids',
                    ) );
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $tag_id ) {
                                $final_cart_products_tag_ids[] = $tag_id;
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            $final_cart_products_tag_ids = array_diff( $all_tags, $condition['product_fees_conditions_values'] );
                        }
                    }
                    
                    $final_cart_products_tag_ids = array_map( 'intval', $final_cart_products_tag_ids );
                    $tags = array();
                    $cart_value_array = array();
                    foreach ( $cart_array as $value ) {
                        $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                        $cart_value_array[] = $value;
                        $tag_ids = wp_get_post_terms( $value['product_id'], 'product_tag', array(
                            'fields' => 'ids',
                        ) );
                        foreach ( $tag_ids as $tag_id ) {
                            $tags[$value['product_id']][$tag_id] = $value['quantity'] . "||" . $line_item_subtotal;
                        }
                    }
                    foreach ( $tags as $cart_product_key => $main_tag_data ) {
                        foreach ( $main_tag_data as $cart_tag_id => $tag_data ) {
                            $tag_data_explode = explode( "||", $tag_data );
                            $cart_tag_qty = json_decode( $tag_data_explode[0] );
                            $cart_tag_subtotal = json_decode( $tag_data_explode[1] );
                            if ( !empty($final_cart_products_tag_ids) ) {
                                if ( in_array( $cart_tag_id, $final_cart_products_tag_ids, true ) ) {
                                    $cart_final_tag_products_array[$cart_product_key][$cart_tag_id] = $cart_tag_qty . "||" . $cart_tag_subtotal;
                                }
                            }
                        }
                    }
                    if ( !empty($cart_final_tag_products_array) ) {
                        foreach ( $cart_final_tag_products_array as $prd_id => $main_cart_item ) {
                            foreach ( $main_cart_item as $term_id => $cart_item ) {
                                $cart_item_explode = explode( "||", $cart_item );
                                $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                                $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                            }
                        }
                    }
                }
            
            }
        }
        if ( !empty($all_rule_check) ) {
            foreach ( $all_rule_check as $cart_item ) {
                $products_based_qty += $cart_item['qty'];
                $products_based_subtotal += $cart_item['subtotal'];
            }
        }
        if ( 0 === $products_based_qty ) {
            $products_based_qty = 1;
        }
        return array( $products_based_qty, $products_based_subtotal );
    }
    
    /**
     * Display array column
     *
     * @param array $input
     * @param int   $columnKey
     * @param int   $indexKey
     *
     * @return array $array It will return array if any error generate then it will return false
     * @since  1.0.0
     *
     * @uses   trigger_error()
     *
     */
    public function wcpfc_pro_fee_array_column_public( array $input, $columnKey, $indexKey = null )
    {
        $array = array();
        foreach ( $input as $value ) {
            if ( !isset( $value[$columnKey] ) ) {
                return false;
            }
            
            if ( is_null( $indexKey ) ) {
                $array[] = $value[$columnKey];
            } else {
                if ( !isset( $value[$indexKey] ) ) {
                    return false;
                }
                if ( !is_scalar( $value[$indexKey] ) ) {
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        
        }
        return $array;
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array $array
     *
     * @return array $result if $array is empty it will return false otherwise return array as $result
     * @since    1.0.0
     *
     */
    public function wcpfc_pro_array_flatten( $array )
    {
        if ( !is_array( $array ) ) {
            return false;
        }
        $result = array();
        foreach ( $array as $key => $value ) {
            
            if ( is_array( $value ) ) {
                $result = array_merge( $result, $this->wcpfc_pro_array_flatten( $value ) );
            } else {
                $result[$key] = $value;
            }
        
        }
        return $result;
    }
    
    /**
     * Find a matching zone for a given package.
     *
     * @param array $available_zone_id_array
     *
     * @return int $return_zone_id
     * @uses   WC_Customer::get_shipping_state()
     * @uses   WC_Customer::get_shipping_postcode()
     * @uses   wc_postcode_location_matcher()
     *
     * @since  3.0.0
     *
     * @uses   WC_Customer::get_shipping_country()
     */
    public function wcpfc_pro_check_zone_available( $available_zone_id_array )
    {
        $return_zone_id = '';
        $country = strtoupper( wc_clean( WC()->customer->get_shipping_country() ) );
        $state = strtoupper( wc_clean( WC()->customer->get_shipping_state() ) );
        $postcode = wc_normalize_postcode( wc_clean( WC()->customer->get_shipping_postcode() ) );
        $state_flag = false;
        $flag = false;
        $postcode_locations = array();
        $zone_array = array();
        foreach ( $available_zone_id_array as $zone_id ) {
            $zone_by_id = WC_Shipping_Zones::get_zone_by( 'zone_id', $zone_id );
            $zones = $zone_by_id->get_zone_locations();
            if ( !empty($zones) ) {
                foreach ( $zones as $zone_location ) {
                    if ( 'country' === $zone_location->type || 'state' === $zone_location->type ) {
                        $zone_array[$zone_id][$zone_location->type][] = $zone_location->code;
                    }
                    $location = new stdClass();
                    
                    if ( 'postcode' === $zone_location->type ) {
                        $location->zone_id = $zone_id;
                        $location->location_code = $zone_location->code;
                        
                        if ( false !== strpos( $location->location_code, '...' ) ) {
                            $postcode_locations_ex = explode( '...', $location->location_code );
                            $start_index = $postcode_locations_ex[0];
                            $end_index = $postcode_locations_ex[1];
                            
                            if ( $start_index < $end_index ) {
                                $total_count = $end_index - $start_index;
                                $new_index = $start_index;
                                for ( $i = 0 ;  $i <= $total_count ;  $i++ ) {
                                    $desh_location = new stdClass();
                                    
                                    if ( 0 === $i ) {
                                        $new_index = $start_index;
                                    } elseif ( $total_count === $i ) {
                                        $new_index = $end_index;
                                    } else {
                                        $new_index += 1;
                                    }
                                    
                                    $desh_location->zone_id = $zone_id;
                                    settype( $new_index, 'string' );
                                    $desh_location->location_code = $new_index;
                                    $postcode_locations[$zone_id][$i] = $desh_location;
                                }
                            }
                        
                        } else {
                            $postcode_locations[$zone_id][] = $location;
                        }
                    
                    }
                
                }
            }
        }
        
        if ( !empty($zone_array) ) {
            foreach ( $zone_array as $zone_id => $zone_location_detail ) {
                foreach ( $zone_location_detail as $zone_location_type => $zone_location_code ) {
                    if ( 'country' === $zone_location_type ) {
                        
                        if ( $postcode_locations ) {
                            foreach ( $postcode_locations as $post_zone_id => $postcode_location_detail ) {
                                
                                if ( $zone_id === $post_zone_id ) {
                                    if ( in_array( $country, $zone_location_code, true ) ) {
                                        $flag = 1;
                                    }
                                } else {
                                    if ( in_array( $country, $zone_location_code, true ) ) {
                                        $return_zone_id = $zone_id;
                                    }
                                }
                            
                            }
                        } else {
                            if ( in_array( $country, $zone_location_code, true ) ) {
                                $return_zone_id = $zone_id;
                            }
                        }
                    
                    }
                    
                    if ( 'state' === $zone_location_type ) {
                        $state_array = array();
                        foreach ( $zone_location_code as $subzone_location_code ) {
                            if ( false !== strpos( $subzone_location_code, ':' ) ) {
                                $sub_zone_location_code_explode = explode( ':', $subzone_location_code );
                            }
                            $state_array[] = $sub_zone_location_code_explode[1];
                            
                            if ( !$postcode_locations ) {
                                
                                if ( in_array( $state, $state_array, true ) ) {
                                    $return_zone_id = $zone_id;
                                    $state_flag = true;
                                }
                            
                            } else {
                                if ( in_array( $state, $state_array, true ) ) {
                                    $flag = 1;
                                }
                            }
                        
                        }
                    }
                
                }
            }
        } else {
            if ( $postcode_locations ) {
                $flag = 1;
            }
        }
        
        if ( true === $state_flag || 1 === $flag ) {
            if ( $postcode_locations ) {
                foreach ( $postcode_locations as $post_zone_id => $postcode_location_detail ) {
                    $matches = wc_postcode_location_matcher(
                        $postcode,
                        $postcode_location_detail,
                        'zone_id',
                        'location_code',
                        $country
                    );
                    $matches_count = count( $matches );
                    
                    if ( 0 !== $matches_count ) {
                        $matches_array_key = array_keys( $matches );
                        $return_zone_id = $matches_array_key[0];
                    } else {
                        $return_zone_id = '';
                    }
                
                }
            }
        }
        return $return_zone_id;
    }
    
    /**
     * Remove WooCommerce currency symbol
     *
     * @param float $price
     *
     * @return float $new_price2
     * @since  1.0.0
     *
     * @uses   get_woocommerce_currency_symbol()
     *
     */
    public function wcpfc_pro_remove_currency_symbol( $price )
    {
        $wc_currency_symbol = get_woocommerce_currency_symbol();
        $new_price = str_replace( $wc_currency_symbol, '', $price );
        $new_price2 = (double) preg_replace( '/[^.\\d]/', '', $new_price );
        return $new_price2;
    }
    
    /*
     * Get WooCommerce version number
     *
     * @since 1.0.0
     *
     * @return string if file is not exists then it will return null
     */
    function wcpfc_pro_get_woo_version_number()
    {
        // If get_plugins() isn't available, require it
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';
        // If the plugin version number is set, return it
        
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
        } else {
            return null;
        }
    
    }
    
    /**
     * Get applied fees on frontside
     *
     * @return array|object $fees
     * @since  1.3.3
     *
     */
    public function wcpfc_pro_get_applied_fees()
    {
        $fees = WC()->cart->get_fees();
        uasort( $fees, array( $this, 'wcpfc_pro_sorting_fees' ) );
        return $fees;
    }
    
    /**
     * Sorting fees on front side
     *
     * @param object $a
     * @param object $b
     *
     * @return int
     * @since  1.3.3
     *
     */
    public function wcpfc_pro_sorting_fees( $a, $b )
    {
        return ( $a->menu_order < $b->menu_order ? -1 : 1 );
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $cart_main_product_ids_array
     * @uses  wcpfc_pro_get_cart();
     *
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_main_prd_id( $sitepress, $default_lang )
    {
        $cart_array = $this->wcpfc_pro_get_cart();
        $cart_main_product_ids_array = array();
        foreach ( $cart_array as $woo_cart_item ) {
            
            if ( !empty($sitepress) ) {
                $cart_main_product_ids_array[] = apply_filters(
                    'wpml_object_id',
                    $woo_cart_item['product_id'],
                    'product',
                    true,
                    $default_lang
                );
            } else {
                $cart_main_product_ids_array[] = $woo_cart_item['product_id'];
            }
        
        }
        return $cart_main_product_ids_array;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $cart_product_ids_array
     * @uses  wcpfc_pro_get_cart();
     *
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_prd_var_id( $sitepress, $default_lang )
    {
        $cart_array = $this->wcpfc_pro_get_cart();
        $cart_product_ids_array = array();
        foreach ( $cart_array as $woo_cart_item ) {
            $_product = wc_get_product( $woo_cart_item['product_id'] );
            
            if ( !empty($sitepress) ) {
                $cart_product_ids_array[] = apply_filters(
                    'wpml_object_id',
                    $woo_cart_item['product_id'],
                    'product',
                    true,
                    $default_lang
                );
            } else {
                $cart_product_ids_array[] = $woo_cart_item['product_id'];
            }
        
        }
        return $cart_product_ids_array;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @return array $cart_array
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_cart()
    {
        $cart_array = WC()->cart->get_cart();
        return $cart_array;
    }
    
    /**
     * Price format
     *
     * @param string $price
     *
     * @return string $price
     * @since  1.3.3
     *
     */
    public function wcpfc_pro_price_format( $price )
    {
        $price = floatval( $price );
        return $price;
    }
    
    /**
     * get cart subtotal
     *
     * @return float $cart_subtotal
     * @since  1.5.2
     *
     */
    public function wcpfc_pro_get_cart_subtotal()
    {
        $get_customer = WC()->cart->get_customer();
        $get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
        $tax_display_cart = WC()->cart->tax_display_cart;
        $wc_prices_include_tax = wc_prices_include_tax();
        $tax_enable = wc_tax_enabled();
        $cart_subtotal = 0;
        
        if ( true === $tax_enable ) {
            
            if ( true === $wc_prices_include_tax ) {
                
                if ( 'incl' === $tax_display_cart && !($get_customer && $get_customer_vat_exempt) ) {
                    $cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
                } else {
                    $cart_subtotal += WC()->cart->get_subtotal();
                }
            
            } else {
                
                if ( 'incl' === $tax_display_cart && !($get_customer && $get_customer_vat_exempt) ) {
                    $cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
                } else {
                    $cart_subtotal += WC()->cart->get_subtotal();
                }
            
            }
        
        } else {
            $cart_subtotal += WC()->cart->get_subtotal();
        }
        
        return $cart_subtotal;
    }

}