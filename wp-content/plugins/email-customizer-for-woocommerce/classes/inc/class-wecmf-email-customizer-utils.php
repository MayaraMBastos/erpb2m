<?php
/**
 * Email Customizer for WooCommerce common functions
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Email_Customizer_Utils')) :
class WECMF_Email_Customizer_Utils {
	private $test_email_override;
	private static $css_elm_props_map;
	const OPTION_KEY_TEMPLATE_SETTINGS = 'thwecmf_template_settings';
	const SETTINGS_KEY_TEMPLATE_LIST = 'templates';
	const SETTINGS_KEY_TEMPLATE_MAP = 'template_map';
	const OPTION_KEY_ADVANCED_SETTINGS = 'thwecmf_advanced_settings';
	const OPTION_KEY_WECMF_MISC = 'thwecmf_misc_settings';

	public function __construct() {
		$this->test_email_override = apply_filters('thwecmf_enable_test_mail_save',false);
	}

	public static function css_elm_props_mapping(){
		$elm_css_map = array(
    		'text'	=> array(
    			'.thwecmf-block-text'	=>	array(
    				'color', 'align', 'font_size', 'size_width', 'size_height', 'm_t', 'm_r', 'm_b', 'm_l', 'text_align'
    			),
    			'.thwecmf-block-text .thwecmf-block-text-holder'	=>	array(
    				'color', 'font_size', 'text_align', 'bg_color', 'b_t', 'b_r', 'b_b', 'b_l', 'border_color', 'border_style', 'p_t', 'p_r', 'p_b', 'p_l'
    			),
    			'.thwecmf-block-text *'		=>	array(
    				'color', 'font_size'
    			),
    		),
    		'image'	=>	array(
    			'.thwecmf-block-image td.thwecmf-image-column' => array(
    				'content_align'
    			),
    			'.thwecmf-block-image td.thwecmf-image-column p' => array(
    				'img_size_width', 'img_size_height' 
    			),
    		),	
    		'billing_address'	=>	array(
    			'.thwecmf-block-billing .thwecmf-address-alignment'	=> array( 'align' ),
    			'.thwecmf-block-billing .thwecmf-address-wrapper-table'	=> array(
    				'size_width', 'size_height', 'bg_color', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'm_t', 'm_r', 'm_b', 'm_l'
    			),
    			'.thwecmf-block-billing .thwecmf-billing-padding'		=> array(
    				'p_t', 'p_r', 'p_b', 'p_l'
    			),
    			'.thwecmf-block-billing .thwecmf-billing-header'	=> array(
    				'font_size', 'color','text_align'
    			),
    			'.thwecmf-block-billing .thwecmf-billing-body'	=> array(
    				'details_font_size', 'details_color','details_text_align'
    			),
    		),
    		'shipping_address'	=>	array(
    			'.thwecmf-block-shipping .thwecmf-address-alignment'	=> array( 'align' ),
    			'.thwecmf-block-shipping .thwecmf-address-wrapper-table'	=> array(
    				'size_width', 'size_height', 'bg_color', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'm_t', 'm_r', 'm_b', 'm_l'
    			),
    			'.thwecmf-block-shipping .thwecmf-shipping-padding'		=> array(
    				'p_t', 'p_r', 'p_b', 'p_l'
    			),
    			'.thwecmf-block-shipping .thwecmf-shipping-header'	=> array(
    				'font_size', 'color','text_align'
    			),
    			'.thwecmf-block-shipping .thwecmf-shipping-body'	=> array(
    				'details_font_size', 'details_color','details_text_align'
    			),
    		),
    		'gap'	=>	array(
    			'.thwecmf-block-gap'	=> array(
    				'height', 'bg_color', 'b_t', 'b_b', 'b_l', 'b_r',  'border_style', 'border_color'
    			),
    		),
    		'divider'	=>	array(
    			'.thwecmf-block-divider '	=>	array(
    				'm_t', 'm_r', 'm_b', 'm_l'
    			),
    			'.thwecmf-block-divider td'	=>	array(
    				'p_t', 'p_r', 'p_b', 'p_l', 'content_align'
    			),
    			'.thwecmf-block-divider td hr'	=>	array(
    				'width', 'divider_height', 'divider_color', 'divider_style'
    			),
    		),
    		't_builder'	=>	array(
    			'.thwecmf-main-builder .thwecmf-builder-column'	=> 	array(
    				'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'bg_color'
    			),
    		),
    	);
		return apply_filters('thwecmf_css_elm_props_mapping',$elm_css_map);
	}

	public static function thwecmf_woo_version_check( $version = '3.0' ) {
	  	if(function_exists( 'is_woocommerce_active' ) && is_woocommerce_active() ) {
			global $woocommerce;
			if( version_compare( $woocommerce->version, $version, ">=" ) ) {
		  		return true;
			}
	  	}
	  	return false;
	}

	public static function thwecmf_emogrifier_version_check( $version = '3.6' ) {
	  	if(function_exists( 'is_woocommerce_active' ) && is_woocommerce_active() ) {
			global $woocommerce;
			if( version_compare( $woocommerce->version, $version, ">" ) ) {
		  		return true;
			}
	  	}
	  	return false;
	}

	public static function prepare_template_name($display_name){
		$name = strtolower($display_name);
		$name = preg_replace('/\s+/', '_', $name);
		return $name;
	}

	public static function thwecmf_is_json_decode($data){
		$json_data = json_decode($data);
		$json_data = json_last_error() == JSON_ERROR_NONE ?  $json_data : false;
		return $json_data;
	}

	public static function is_user_capable(){
		$capable = false;
		$user = wp_get_current_user();
		$allowed_roles = apply_filters('thwecmf_user_capabilities_override', array('editor', 'administrator') );
		if( array_intersect($allowed_roles, $user->roles ) ) {
   			$capable = true;
   		}
   		return $capable;
	}

	public static function thwecmf_setup_initial_settings(){
		$settings = self::thwecmf_get_template_settings();
		if(isset($settings['templates']) && empty($settings['templates'])){
			$settings = self::thwecmf_save_template_settings(self::get_default_templates_json());
		}else{
			return true;
		}
		return $settings;
	}
	
	public static function thwecmf_get_template_settings(){
		$settings = get_option(self::OPTION_KEY_TEMPLATE_SETTINGS);
		if(empty($settings)){
			$settings = array(
				self::SETTINGS_KEY_TEMPLATE_LIST => array(), 
				self::SETTINGS_KEY_TEMPLATE_MAP => array()
			);
		}
		return $settings;
	}

	public static function thwecmf_get_template_list($settings=false){
		if(!is_array($settings)){
			$settings = self::thwecmf_get_template_settings();
		}
		return is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_LIST]) ? $settings[self::SETTINGS_KEY_TEMPLATE_LIST] : array();
	}

	public static function thwecmf_get_template_map($settings=false){
		if(!is_array($settings)){
			$settings = self::thwecmf_get_template_settings();
		}
		return is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_MAP]) ? $settings[self::SETTINGS_KEY_TEMPLATE_MAP] : array();
	}

	public static function thwecmf_reset_template_map(){
		$settings = self::thwecmf_get_template_settings();
		if( is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_MAP]) ){
			$settings[self::SETTINGS_KEY_TEMPLATE_MAP] = array();
		}
		return $settings;
		
	}

	public static function thwecmf_save_template_settings($settings, $new=false){
		$result = false;
		if($new){
			$result = add_option(self::OPTION_KEY_TEMPLATE_SETTINGS, $settings);
		}else{
			$result = update_option(self::OPTION_KEY_TEMPLATE_SETTINGS, $settings);
		}
		return $result;
	}

	public static function thwecmf_get_advanced_settings(){
		$settings = get_option(self::OPTION_KEY_ADVANCED_SETTINGS);
		return empty($settings) ? false : $settings;
	}
	
	public static function thwecmf_get_setting_value($settings, $key){
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}
	
	public static function thwecmf_get_settings($key){
		$settings = self::thwecmf_get_advanced_settings();
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}

	public static function get_wecmf_misc_settings($key){
		$settings = get_option(self::OPTION_KEY_WECMF_MISC);
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}

	public static function do_file_exist($file,$ext){
    	$extension = $ext ? $ext : 'php'; 
		$path = THWECMF_CUSTOM_T_PATH.$file.'.'.$extension;
    	return file_exists($path) ? true : false;
	}

	public static function get_default_templates_json(){
		$data = array();
		$data['templates']['customer_processing_order'] = array(
			'file_name' 	=> 'customer_processing_order.php',
			'display_name'	=> 'Customer Processing Order',
			'template_data'	=> '{"row":[{"data_id":"1010","data_type":"row","data_name":"one_column","data_css":"{\"height\":\"\",\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\"}","data_count":"1","child":[{"data_id":"1011","data_type":"column","data_name":"one_column_one","data_css":"{\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"width\":\"100%\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"text_align\":\"center\"}","child":[{"data_id":"1029","data_type":"element","data_name":"text","data_css":"{\"p_t\":\"45px\",\"p_r\":\"48px\",\"p_b\":\"45px\",\"p_l\":\"48px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"size_width\":\"100%\",\"size_height\":\"\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"#96588a\",\"color\":\"#ffffff\",\"font_size\":\"30px\",\"text_align\":\"left\",\"align\":\"\",\"textarea_content\":\"\"}","data_text":"{\"textarea_content\":\"Thank you for your order\"}","data_misc":"","child":"Text"}]}]},{"data_id":"1013","data_type":"row","data_name":"one_column","data_css":"{\"height\":\"\",\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\"}","data_count":"1","child":[{"data_id":"1014","data_type":"column","data_name":"one_column_one","data_css":"{\"p_t\":\"10px\",\"p_r\":\"48px\",\"p_b\":\"0px\",\"p_l\":\"48px\",\"width\":\"100%\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"#dddddd\",\"bg_color\":\"\",\"text_align\":\"left\"}","child":[{"data_id":"1023","data_type":"element","data_name":"text","data_css":"{\"p_t\":\"20px\",\"p_r\":\"0px\",\"p_b\":\"10px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"size_width\":\"100%\",\"size_height\":\"\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"color\":\"#636363\",\"font_size\":\"14px\",\"text_align\":\"left\",\"align\":\"\",\"textarea_content\":\"\"}","data_text":"{\"textarea_content\":\"Hi {customer_name},\\n\\nJust to let you know — we have received your order, and it is now being processed:\"}","data_misc":"","child":"Text"},{"data_id":"1015","data_type":"hook","data_name":"email_order_details_hook","child":"Email Order Details"},{"data_id":"1016","data_type":"hook","data_name":"before_order_table_hook","child":"Before Order Table"},{"data_id":"1017","data_type":"hook","data_name":"after_order_table_hook","child":"After Order Table"},{"data_id":"1018","data_type":"hook","data_name":"order_meta_hook","child":"Order Meta"},{"data_id":"1019","data_type":"hook","data_name":"customer_details_hook","child":"Customer Details"}]}]},{"data_id":"1027","data_type":"row","data_name":"one_column","data_css":"{\"height\":\"\",\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\"}","data_count":"1","child":[{"data_id":"1028","data_type":"column","data_name":"one_column_one","data_css":"{\"p_t\":\"20px\",\"p_r\":\"48px\",\"p_b\":\"48px\",\"p_l\":\"48px\",\"width\":\"100%\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"#dddddd\",\"bg_color\":\"\",\"text_align\":\"center\"}","child":[{"data_id":"1030","data_type":"element","data_name":"text","data_css":"{\"p_t\":\"15px\",\"p_r\":\"15px\",\"p_b\":\"15px\",\"p_l\":\"15px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"size_width\":\"100%\",\"size_height\":\"\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"color\":\"#636363\",\"font_size\":\"13px\",\"text_align\":\"center\",\"align\":\"\",\"textarea_content\":\"\"}","data_text":"{\"textarea_content\":\"{site_name}\"}","data_misc":"","child":"Text"}]}]}],"data_id":"t_builder","data_type":"builder","track_save":"1030","data_css":"{\"b_t\":\"1px\",\"b_r\":\"1px\",\"b_b\":\"1px\",\"b_l\":\"1px\",\"border_style\":\"solid\",\"border_color\":\"#dedede\",\"bg_color\":\"#ffffff\"}"}',
		);
		$data['templates']['customer_new_account'] = array(
			'file_name' 	=> 'customer_new_account.php',
			'display_name'	=> 'Customer New Account',
			'template_data'	=> '{"row":[{"data_id":"1001","data_type":"row","data_name":"one_column","data_css":"{\"height\":\"\",\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\"}","data_count":"1","child":[{"data_id":"1002","data_type":"column","data_name":"one_column_one","data_css":"{\"p_t\":\"45px\",\"p_r\":\"48px\",\"p_b\":\"45px\",\"p_l\":\"48px\",\"width\":\"100%\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"#dddddd\",\"bg_color\":\"#96588a\",\"text_align\":\"center\"}","child":[{"data_id":"1016","data_type":"element","data_name":"text","data_css":"{\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"size_width\":\"100%\",\"size_height\":\"\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"color\":\"#ffffff\",\"font_size\":\"30px\",\"text_align\":\"left\",\"align\":\"\",\"textarea_content\":\"\"}","data_text":"{\"textarea_content\":\"Welcome to {site_name}\"}","data_misc":"","child":"Text"}]}]},{"data_id":"1010","data_type":"row","data_name":"one_column","data_css":"{\"height\":\"\",\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\"}","data_count":"1","child":[{"data_id":"1011","data_type":"column","data_name":"one_column_one","data_css":"{\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"width\":\"100%\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"#dddddd\",\"bg_color\":\"\",\"text_align\":\"center\"}","child":[{"data_id":"1012","data_type":"element","data_name":"text","data_css":"{\"p_t\":\"20px\",\"p_r\":\"20px\",\"p_b\":\"15px\",\"p_l\":\"40px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"size_width\":\"100%\",\"size_height\":\"\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"color\":\"#636363\",\"font_size\":\"13px\",\"text_align\":\"left\",\"align\":\"\",\"textarea_content\":\"\"}","data_text":"{\"textarea_content\":\"Hi {user_login},\\n\\nThanks for creating an account on {site_name}. Your username is {user_login}. You can access your account area to view orders, change your password, and more at: {account_area_url}\\n\\nYour password has been automatically generated: {user_pass}\\n\\nWe look forward to seeing you soon.\"}","data_misc":"","child":"Text"}]}]},{"data_id":"1013","data_type":"row","data_name":"one_column","data_css":"{\"height\":\"\",\"p_t\":\"0px\",\"p_r\":\"0px\",\"p_b\":\"0px\",\"p_l\":\"0px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\"}","data_count":"1","child":[{"data_id":"1014","data_type":"column","data_name":"one_column_one","data_css":"{\"p_t\":\"10px\",\"p_r\":\"10px\",\"p_b\":\"10px\",\"p_l\":\"10px\",\"width\":\"100%\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"text_align\":\"center\"}","child":[{"data_id":"1015","data_type":"element","data_name":"text","data_css":"{\"p_t\":\"15px\",\"p_r\":\"15px\",\"p_b\":\"15px\",\"p_l\":\"15px\",\"m_t\":\"0px\",\"m_r\":\"auto\",\"m_b\":\"0px\",\"m_l\":\"auto\",\"size_width\":\"100%\",\"size_height\":\"\",\"b_t\":\"0px\",\"b_r\":\"0px\",\"b_b\":\"0px\",\"b_l\":\"0px\",\"border_style\":\"none\",\"border_color\":\"\",\"bg_color\":\"\",\"color\":\"#636363\",\"font_size\":\"13px\",\"text_align\":\"center\",\"align\":\"\",\"textarea_content\":\"\"}","data_text":"{\"textarea_content\":\"{site_name}\"}","data_misc":"","child":"Text"}]}]}],"data_id":"t_builder","data_type":"builder","track_save":"1016","data_css":"{\"b_t\":\"1px\",\"b_r\":\"1px\",\"b_b\":\"1px\",\"b_l\":\"1px\",\"border_style\":\"solid\",\"border_color\":\"#dedede\",\"bg_color\":\"#ffffff\"}"}',
		);
	$data['template_map'] = array();
		return $data;
	}

	public static function get_logged_user_email(){
		$email = '';
	   	$current_user = wp_get_current_user();
		if( $current_user !== 0 ){
			$email =  $current_user->user_email;
		}
		return $email;
	}

}
endif;