<?php
/**
 * Woo Email Customizer Settings
 *
 * @author   ThemeHiGH
 * @category Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Settings')) :
class WECMF_Settings {
	protected static $_instance = null;	
	public $admin = null;
	public $frontend_fields = null;
	private $plugins_pages = null;

	public function __construct() {
		$required_classes = apply_filters('th_wecmf_require_class', array(
			'admin' => array(
				'classes/class-wecmf-settings-page.php',
				'classes/inc/class-wecmf-builder-settings.php',
				'classes/inc/class-wecmf-general-template.php',
				'classes/inc/class-wecmf-template-settings.php',
			),
			'common' => array(
				'classes/inc/class-wecmf-email-customizer-utils.php',
			),
		));
		
		$this->include_required( $required_classes );

		$this->plugin_pages = array(
			'toplevel_page_thwecmf_email_customizer_templates',
			'email-customizer_page_thwecmf_email_customizer'
		);

		WECMF_Email_Customizer_Utils::thwecmf_setup_initial_settings();
	
		add_action('admin_menu', array($this, 'admin_menu'));
		add_filter('woocommerce_screen_ids', array($this, 'add_screen_id'));
		add_filter('plugin_action_links_'.TH_WECMF_BASE_NAME, array($this, 'add_settings_link'));
		$directory = $this->get_template_directory();
		!defined('THWECMF_CUSTOM_T_PATH') && define('THWECMF_CUSTOM_T_PATH', $directory);	
		$this->init();
		!defined('THWECMF_LOGIN_USER') && define('THWECMF_LOGIN_USER', WECMF_Email_Customizer_Utils::get_logged_user_email());
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function get_template_directory(){
	    $upload_dir = wp_upload_dir();
	    $dir = $upload_dir['basedir'].'/thwec_templates';
      	$dir = trailingslashit($dir);
      	return $dir;
	}

	protected function include_required( $required_classes ) {
		foreach($required_classes as $section => $classes ) {
			foreach( $classes as $class ){
				if('common' == $section  || ('frontend' == $section && !is_admin() || ( defined('DOING_AJAX') && DOING_AJAX) ) 
					|| ('admin' == $section && is_admin()) && file_exists( TH_WECMF_PATH . $class )){
					require_once( TH_WECMF_PATH . $class );
				}
			}
		}
	}

	public function init() {	
		if(is_admin()){
			$this->admin_instance = WECMF_General_Template::instance();
		}
		add_filter('woocommerce_locate_template', array($this, 'thwecmf_woo_locate_template'), 999, 3);	
		add_filter('woocommerce_email_styles', array($this, 'thwecmf_woocommerce_email_styles') );
	}

	public function wecf_capability() {
		$allowed = array('manage_woocommerce', 'manage_options');
		$capability = apply_filters('thwecmf_required_capability', 'manage_woocommerce');

		if(!in_array($capability, $allowed)){
			$capability = 'manage_woocommerce';
		}
		return $capability;
	}

	public function admin_menu() {
		global $wp;
		
		$page  = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : 'thwecmf_email_customizer_templates';
		if( $page == 'thwecmf_email_customizer' && !isset($_POST['i_edit_template']) ){
			$url =  admin_url('admin.php?page=thwecmf_email_customizer_templates&feature=premium');
			wp_redirect($url); 
			exit;
		}

		$capability = $this->wecf_capability();
		$this->screen_id = add_menu_page(__('Email Customizer'), __('Email Customizer'), $capability, 'thwecmf_email_customizer_templates', array($this, 'output_settings'), 'dashicons-admin-customizer', 56);

		add_submenu_page('thwecmf_email_customizer_templates', __('Templates'), __('Templates'), $capability, 'thwecmf_email_customizer_templates', array($this, 'output_settings'));

		add_submenu_page('thwecmf_email_customizer_templates', __('Add New'), __('Add New'), $capability, 'thwecmf_email_customizer',  array($this, 'output_settings'));
		add_action('admin_print_scripts', array($this, 'disable_admin_notices'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		
		if(!isset($_POST['i_edit_template'])){
			global $submenu;
			if( isset( $submenu['thwecmf_email_customizer_templates'][1][2] ) ){
				$submenu['thwecmf_email_customizer_templates'][1][2] = admin_url('admin.php?page=thwecmf_email_customizer_templates&feature=premium');
			}
		}
	}

	function add_screen_id($ids){
		$ids[] = 'woocommerce_page_thwecmf_email_customizer';
		$ids[] = strtolower(__('WooCommerce', 'woocommerce')) .'_page_thwecmf_email_customizer';
		return $ids;
	}
	
	public function add_settings_link($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=thwecmf_email_customizer_templates').'">'. __('Settings') .'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	function output_settings() {
		$page  = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : 'thwecmf_email_customizer_templates';

		if($page === 'thwecmf_email_customizer_templates'){			
			$fields_instance = WECMF_Template_Settings::instance();	
			$fields_instance->render_page();	
		}else if($page === 'thwecmf_email_customizer' && isset($_POST['i_edit_template'])){
			$fields_instance = WECMF_General_Template::instance();	
			$fields_instance->render_page();
		}else if($page === 'thwecmf_email_customizer'){
			$fields_instance = WECMF_General_Template::instance();	
			$fields_instance->render_page();
		}else{
			$fields_instance = WECMF_General_Template::instance();	
			$fields_instance->render_page();
		}
	}

	function disable_admin_notices(){
		$page  = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
		if($page === 'thwecmf_email_customizer'){
			global $wp_filter;
      		if (is_user_admin() ) {
        		if (isset($wp_filter['user_admin_notices'])){
            		unset($wp_filter['user_admin_notices']);
        		}
      		} elseif(isset($wp_filter['admin_notices'])){
            	unset($wp_filter['admin_notices']);
      		}
      		if(isset($wp_filter['all_admin_notices'])){
        		unset($wp_filter['all_admin_notices']);
      		}
		}
	}

	public function thwecmf_woo_locate_template($template, $template_name, $template_path){
		$template_map = WECMF_Email_Customizer_Utils::thwecmf_get_template_map();
		if($template_map && strpos($template_name, 'emails/') !== false){ 
		    $search = array('emails/', '.php');
            $replace = array('', '');
		    $template_name_new = str_replace($search, $replace, $template_name);
			if(array_key_exists($template_name_new, $template_map)) {
    			$template_name_new = $template_map[$template_name_new];
    			if($template_name_new != ''){  
        			$custom_path = $this->get_email_template_path($template_name_new);  
    				if($custom_path){
    					return $custom_path;
    				}
    			}		
    		}
    	}
       	return $template;
	}

	public function thwecmf_woocommerce_email_styles($buffer){
		$styles = '#tpf_t_builder #template_container,#tpf_t_builder #template_header,#tpf_t_builder #template_body,#tpf_t_builder #template_footer{width:100% !important;}';
		$styles.= '#tpf_t_builder #template_container{width:100% !important;border:0px none transparent !important;}';
		$styles .= '#tpf_t_builder #body_content > table:first-child > tbody > tr > td{padding:15px 0px !important;}'; //To remove the padding after header when woocommerce header hook used in template (48px 48px 0px) 
		$styles.= '#tpf_t_builder #wrapper{padding:0;background-color:transparent;}';
		$styles.= '#tpf_t_builder .thwec-block-text-holder a{color: #1155cc !important;}';
		$styles.= '#tpf_t_builder .thwecmf-columns p{color:#636363;font-size:14px;}';
		$styles.= '#tpf_t_builder .thwecmf-columns .td .td{padding:12px;}';
		$styles.= '#tpf_t_builder .thwecmf-columns .address{font-size:14px;}';
		return $buffer.$styles;
	}
	
	public function get_email_template_path($t_name){
    	$tpath = false;
    	$email_template_path = THWECMF_CUSTOM_T_PATH.$t_name.'.php';
    	if(file_exists($email_template_path)){
    	   	$tpath = $email_template_path;
    	}
    	return $tpath;
    }

    public function render_advanced_content(){
    	?>
    	<div id="wecmf_builder_page_disabled">
    		<div class="wecmf-feature-access-wrapper">
    			<div class="wecmf-feature-access">
    				<p><b>Upgrade to Premium to access this feature</b></p>
    				<p>Goto <a href=>Templates</a> to edit and assign templates to email status</p>
    			</div>
    		</div>
    	</div>
    	<?php
    }

	public function enqueue_admin_scripts($hook){
		if(!in_array($hook, $this->plugin_pages)){
			return;
		}
		wp_enqueue_media();
		wp_enqueue_style (array('woocommerce_admin_styles', 'jquery-ui-style'));
		wp_enqueue_style ('thwecmf-admin-style', plugins_url('/assets/css/thwecmf-admin.css', dirname(__FILE__)));
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('raleway-style','https://fonts.googleapis.com/css?family=Raleway:400,600,800');
		wp_enqueue_script('thwecmf-admin-script', plugins_url('/assets/js/thwecmf-admin.min.js', dirname(__FILE__)), 
		array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-tiptip', 'wc-enhanced-select', 'select2', 'wp-color-picker'), TH_WECMF_VERSION, true);

		$wecmf_var = array(
            'admin_url' => admin_url(),
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce('thwecmf_ajax_security'),
            'elm_css_map' => WECMF_Email_Customizer_Utils::css_elm_props_mapping(),
        );
		wp_localize_script('thwecmf-admin-script', 'thwecmf_admin_var', $wecmf_var);
	}	
}
endif;