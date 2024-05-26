<?php
/**
 * Plugin Name: WooCommerce Parcelas Com e Sem Juros
 * Plugin URI: //wordpress.org/plugins/woo-parcelas-com-e-sem-juros/
 * Description: Adiciona quantidade de parcelas e o valor de cada parcela com ou sem juros (PagSeguro ou Mercado Pago), nas páginas que listam todos os produtos e na página individual de cada produto.
 * Author: Leonardo Robles
 * Author URI: https://www.atitudeti.com.br/
 * Version: 1.5
 * Requires at least: 4.7
 * Tested up to: 5.2.3
 * Text Domain: woo-parcelas-com-e-sem-juros
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 3.6.5
 */

if(!defined('ABSPATH')){
	exit;
}

define('WC_PARCELAS_PATH',	plugin_dir_path(__FILE__));
define('WC_PARCELAS_URL',	plugin_dir_url(__FILE__));
define('WC_PARCELAS_VERSION', '1.5');
define('WC_PARCELAS_NAME', 'WooCommerce Parcelas com e sem Juros');
define('WC_PARCELAS_SLUG', 'woo-parcelas-com-e-sem-juros');

/**
 * The code that runs during plugin activation
 * This action is documented in includes/class-woo-parcelas-com-e-sem-juros-activator.php
 */


function woocommerce_parcelas_activate(){
	require_once WC_PARCELAS_PATH.'includes/class-woo-parcelas-com-e-sem-juros-activator.php';
	Woocommerce_Parcelas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation
 * This action is documented in includes/class-woo-parcelas-com-e-sem-juros-deactivator.php
 */
function woocommerce_parcelas_deactivate(){
	require_once WC_PARCELAS_PATH.'includes/class-woo-parcelas-com-e-sem-juros-deactivator.php';
	Woocommerce_Parcelas_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'woocommerce_parcelas_activate');
register_deactivation_hook(__FILE__, 'woocommerce_parcelas_deactivate');


if(!class_exists('WC_Parcelas')):

/**
 * The core plugin class
 *
 */
class WC_Parcelas{
	/**
	 * Instance of this class
	 *
	 * @var 	object
	 */
	protected static $instance = null;

	/**
	 * Initialize plugin actions and filters
	 */
	private function __construct(){
		/**
		 * Check if WooCommerce is active
		 */
		

		if(class_exists( 'WooCommerce')){
				    /**
					 * Add plugin text domain
					 */
				    add_action('init', array($this, 'load_plugin_textdomain'));

					/**
					 * Add plugin action links
					 */
					add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plguin_action_links'));

					/**
					 * Add plugin Stylesheet and JavaScript, in admin
					 */
					add_action('admin_enqueue_scripts', array($this, 'admin_stylesheet_and_javascript'));

					/**
					 * Include plugin files
					 */
					$this->includes();
				}
				else{
					add_action('admin_notices', array($this, 'wc_missing_notice'));
				}

			}

			public static function get_instance(){
		// If the single instance hasn't been set, set it now.
				if(null == self::$instance){
					self::$instance = new self;
				}

				return self::$instance;
			}

	/**
	 * Call plugin text domain
	 */
	public function load_plugin_textdomain(){
		load_plugin_textdomain('woo-parcelas-com-e-sem-juros', false, dirname(plugin_basename(__FILE__)).'/languages/');
	}

	/**
	 * Call plugin action links
	 *
	 * @param 	$links
	 * @return 	array 	$links
	 */
	public function plguin_action_links($links){
		$settings_url = admin_url('admin.php?page=fswp');

		$links[] = '<a href="'.esc_url($settings_url).'">'.__('Configurações', 'woo-parcelas-com-e-sem-juros').'</a>';

		return $links;
	}

	/**
	 * Load plugin Stylesheet and JavaScript
	 */
	public function admin_stylesheet_and_javascript($hook){
		if('woocommerce_page_fswp' != $hook){
			return;
		}

		/**
		 * Call plugin Stylesheet
		 */
		wp_enqueue_style(WC_PARCELAS_SLUG.'-admin', WC_PARCELAS_URL.'admin/css/woo-parcelas-com-e-sem-juros-admin.css', array(), WC_PARCELAS_VERSION, 'all');		
		wp_enqueue_style('wp-color-picker');

		/**
		 * Call plugin JavaScript
		 */
		wp_enqueue_script(WC_PARCELAS_SLUG.'-admin', WC_PARCELAS_URL.'admin/js/woo-parcelas-com-e-sem-juros-admin.js', array('jquery', 'wp-color-picker'), WC_PARCELAS_VERSION, false);	
	}

	/**
	 * Includes
	 */
	private function includes(){
		include_once WC_PARCELAS_PATH.'admin/class-woo-parcelas-com-e-sem-juros-settings.php';

		include_once WC_PARCELAS_PATH.'admin/class-woo-parcelas-com-e-sem-juros-meta-box.php';

		include_once WC_PARCELAS_PATH.'public/class-woo-parcelas-com-e-sem-juros-public.php';
	}

	/**
	 * WooCommerce missing notice
	 */
	public function wc_missing_notice(){
		$class = 'error';
		$message = sprintf(__('Não é possível habilitar o plugin %s enquanto o %s não estiver instalado e ativado.', 'woo-parcelas-com-e-sem-juros'), '<strong>'.WC_PARCELAS_NAME.'</strong>', '<a href="//wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>');

		echo "<div class='$class'>";
		echo 	"<p>$message</p>";
		echo "</div>";
	}
}

endif;

add_action('plugins_loaded', array('WC_Parcelas', 'get_instance'));