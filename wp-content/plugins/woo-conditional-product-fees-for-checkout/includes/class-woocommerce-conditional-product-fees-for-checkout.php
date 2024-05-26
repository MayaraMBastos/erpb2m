<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @subpackage Woocommerce_Conditional_Product_Fees_For_Checkout_Pro/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @subpackage Woocommerce_Conditional_Product_Fees_For_Checkout_Pro/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected  $version ;
    const  WCPFC_VERSION = WCPFC_PRO_PLUGIN_VERSION ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'woocommerce-conditional-product-fees-for-checkout';
        $this->version = WCPFC_PRO_PLUGIN_VERSION;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $prefix = ( is_network_admin() ? 'network_admin_' : '' );
        add_filter(
            "{$prefix}plugin_action_links_" . WCPFC_PRO_PLUGIN_BASENAME,
            array( $this, 'plugin_action_links' ),
            10,
            4
        );
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader. Orchestrates the hooks of the plugin.
     * - Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_i18n. Defines internationalization functionality.
     * - Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin. Defines all hooks for the admin area.
     * - Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-conditional-product-fees-for-checkout-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-conditional-product-fees-for-checkout-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-conditional-product-fees-for-checkout-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-conditional-product-fees-for-checkout-public.php';
        $this->loader = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_i18n();
        $plugin_i18n->set_domain( $this->get_plugin_name() );
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
        $plugin_admin = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'wcpfc_pro_admin_menu_pages' );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'wcpfc_pro_notifications' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_product_fees_conditions_values_ajax', $plugin_admin, 'wcpfc_pro_product_fees_conditions_values_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_wcpfc_pro_product_fees_conditions_values_ajax', $plugin_admin, 'wcpfc_pro_product_fees_conditions_values_ajax' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_product_fees_conditions_values_product_ajax', $plugin_admin, 'wcpfc_pro_product_fees_conditions_values_product_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_wcpfc_pro_product_fees_conditions_values_product_ajax', $plugin_admin, 'wcpfc_pro_product_fees_conditions_values_product_ajax' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_wc_multiple_delete_conditional_fee', $plugin_admin, 'wcpfc_pro_wc_multiple_delete_conditional_fee' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_product_fees_conditions_sorting', $plugin_admin, 'wcpfc_pro_conditional_fee_sorting' );
        $this->loader->add_action( 'wp_ajax_nopriv_pro_product_fees_conditions_sorting', $plugin_admin, 'wcpfc_pro_conditional_fee_sorting' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_wc_disable_conditional_fee', $plugin_admin, 'wcpfc_pro_multiple_disable_conditional_fee' );
        $this->loader->add_action( 'wp_ajax_nopriv_wcpfc_pro_wc_disable_conditional_fee', $plugin_admin, 'wcpfc_pro_multiple_disable_conditional_fee' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wcpfc_pro_remove_admin_submenus' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wcpfc_pro_welcome_conditional_fee_screen_do_activation_redirect' );
        if ( !empty($page) && false !== strpos( $page, 'wcpfc' ) ) {
            $this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'wcpfc_pro_admin_footer_review' );
        }
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_save_master_settings', $plugin_admin, 'wcpfc_pro_save_master_settings' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_clone_fees', $plugin_admin, 'wcpfc_pro_clone_fees' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_change_status_from_list_section', $plugin_admin, 'wcpfc_pro_change_status_from_list_section' );
        $this->loader->add_action( 'wp_ajax_wcpfc_pro_sm_sort_order', $plugin_admin, 'wcpfc_pro_sm_sort_order' );
        $this->loader->add_action( 'wp_ajax_nopriv_wcpfc_pro_sm_sort_order', $plugin_admin, 'wcpfc_pro_sm_sort_order' );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'wcpfc_pro_conditional_fee_add_to_cart' );
        $this->loader->add_filter(
            'woocommerce_locate_template',
            $plugin_public,
            'woocommerce_locate_template_product_fees_conditions',
            1,
            3
        );
    }
    
    /**
     * Return the plugin action links.  This will only be called if the plugin
     * is active.
     *
     * @param array $actions associative array of action names to anchor tags
     *
     * @return array associative array of plugin action links
     * @since 1.0.0
     */
    public function plugin_action_links( $actions )
    {
        $custom_actions = array(
            'configure' => sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array(
            'page' => 'wcpfc-pro-list',
        ), admin_url( 'admin.php' ) ) ), __( 'Settings', $this->plugin_name ) ),
            'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://www.thedotstore.com/docs/plugins/woocommerce-conditional-product-fees-checkout/' ), __( 'Docs', $this->plugin_name ) ),
            'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://www.thedotstore.com/support' ), __( 'Support', $this->plugin_name ) ),
        );
        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }
    
    /**
     * Allowed html tags used for wp_kses function
     *
     * @param array add custom tags
     *
     * @return array
     * @since     1.0.0
     *
     */
    public static function allowed_html_tags( $tags = array() )
    {
        $allowed_tags = array(
            'a'        => array(
            'href'         => array(),
            'title'        => array(),
            'class'        => array(),
            'target'       => array(),
            'data-tooltip' => array(),
        ),
            'ul'       => array(
            'class' => array(),
        ),
            'li'       => array(
            'class' => array(),
        ),
            'div'      => array(
            'class' => array(),
            'id'    => array(),
        ),
            'select'   => array(
            'rel-id'   => array(),
            'id'       => array(),
            'name'     => array(),
            'class'    => array(),
            'multiple' => array(),
            'style'    => array(),
        ),
            'input'    => array(
            'id'         => array(),
            'value'      => array(),
            'name'       => array(),
            'class'      => array(),
            'type'       => array(),
            'data-index' => array(),
        ),
            'textarea' => array(
            'id'    => array(),
            'name'  => array(),
            'class' => array(),
        ),
            'option'   => array(
            'id'       => array(),
            'selected' => array(),
            'name'     => array(),
            'value'    => array(),
        ),
            'br'       => array(),
            'p'        => array(),
            'b'        => array(
            'style' => array(),
        ),
            'em'       => array(),
            'strong'   => array(),
            'i'        => array(
            'class' => array(),
        ),
            'span'     => array(
            'class' => array(),
        ),
            'small'    => array(
            'class' => array(),
        ),
            'label'    => array(
            'class' => array(),
            'id'    => array(),
            'for'   => array(),
        ),
        );
        return $allowed_tags;
    }

}