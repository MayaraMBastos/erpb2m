<?php
/**
 * Plugin Name: Email Customizer for WooCommerce
 * Description: Customize WooCommerce Emails (Create, Edit and Delete Email Templates)
 * Author:      ThemeHigh
 * Version:     1.0.4
 * Author URI:  https://www.themehigh.com
 * Plugin URI:  https://www.themehigh.com/product/woocommerce-email-customizer
 * Text Domain: woo-email-customizer
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 4.0.1
 */
 
if(!defined('ABSPATH')){ exit; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce');
	}
}

register_activation_hook( __FILE__, 'activate_thwecmf' );

function activate_thwecmf(){
 	$upload_dir = wp_upload_dir();
    $wecm_dir = $upload_dir['basedir'].'/thwec_templates';
  	$wecm_dir = trailingslashit($wecm_dir);
  	$create = !file_exists($wecm_dir) && !is_dir($wecm_dir) ? wp_mkdir_p($wecm_dir) : true;
	if($create){
		$src_dir = plugin_dir_path( __FILE__ ).'classes/inc/templates';
		$open_dir = opendir($src_dir);  
		while( $file = readdir($open_dir) ) {  
			if (( $file != '.' ) && ( $file != '..' )) {
				if($file && !file_exists($wecm_dir . '/' . $file)){
					copy($src_dir . '/' . $file, $wecm_dir . '/' . $file);
				}
			}  
		}  
		closedir($open_dir); 
	}
}

if(is_woocommerce_active()) {
	if(!class_exists('WECMF_Email_Customizer')){	
		class WECMF_Email_Customizer {	
			public function __construct(){
				add_action('init', array($this, 'init'));
			}		
			public function init() {		
				$this->load_plugin_textdomain();
				
				define('TH_WECMF_VERSION', '1.0.4');
				!defined('TH_WECMF_BASE_NAME') && define('TH_WECMF_BASE_NAME', plugin_basename( __FILE__ ));
				!defined('TH_WECMF_PATH') && define('TH_WECMF_PATH', plugin_dir_path( __FILE__ ));
				!defined('TH_WECMF_URL') && define('TH_WECMF_URL', plugins_url( '/', __FILE__ ));
				!defined('TH_WECMF_ASSETS_URL') && define('TH_WECMF_ASSETS_URL', TH_WECMF_URL .'assets/');
								
				require_once( TH_WECMF_PATH . 'classes/class-wecmf-settings.php' );

				WECMF_Settings::instance();					
			}

			public function load_plugin_textdomain(){							
				load_plugin_textdomain('woo-email-customizer', FALSE, dirname(plugin_basename( __FILE__ )) . '/languages/');
			}
		}	
	}
	new WECMF_Email_Customizer();
}