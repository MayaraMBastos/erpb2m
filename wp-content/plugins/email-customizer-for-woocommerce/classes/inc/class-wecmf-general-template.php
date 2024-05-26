<?php
/**
 * Woo Email Customizer
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_General_Template')):
class WECMF_General_Template{
	protected static $_instance = null;
	private $wecmf_builder = null;
	private $template_wrapper_styles = array();

	public function __construct() {
		$this->wecmf_builder = WECMF_Builder_Settings::instance();
		add_action('wp_ajax_thwecmf_template_actions', array($this,'save_template_content'));
		add_action('wp_ajax_nopriv_thwecmf_template_actions', array($this,'save_template_content'));

		$this->init_constants();
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init_constants(){
		$this->temp_wrapper_styles = array('bg' => '#f7f7f7', 'padding' => '70px 0');
	}

	public function render_page(){
		$this->render_content();
	}
	
	private function render_content(){
		if ( ! empty( $_POST ) && check_admin_referer( 'thwecmf_edit_template_action', 'thwecmf_edit_template'  ) && WECMF_Email_Customizer_Utils::is_user_capable() ){
			$this->wecmf_builder->render_template_builder();
		}
    }

	public function save_template_content(){
		$ajax_ref = check_ajax_referer( 'thwecmf_ajax_security', 'thwecmf_security', false);
		$user_can = WECMF_Email_Customizer_Utils::is_user_capable();
		$response = '';
		if($ajax_ref && $user_can){
			$template_display_name = isset($_POST['template_name']) ? sanitize_text_field($_POST['template_name']) : "";
			$action_index = isset($_POST['thwecmf_action_index']) ? sanitize_text_field($_POST['thwecmf_action_index']) : false;

			if($action_index && $action_index == 'settings'){
				$render_data = isset($_POST['template_render_data']) ? wp_kses(trim(stripslashes($_POST['template_render_data'])),wp_kses_allowed_html('post')) : false;
				$render_css = isset($_POST['template_render_css']) ? sanitize_textarea_field(stripslashes($_POST['template_render_css']) ) : '';
				$template_json = isset($_POST['template_json_tree']) ?  wp_kses(trim(stripslashes($_POST['template_json_tree'])),wp_kses_allowed_html('post')) : ''; 
				if($render_data){
					$save_meta = false;						
					$template_name = WECMF_Email_Customizer_Utils::prepare_template_name($template_display_name);
					$save_files = $this->save_template_files($template_name, $render_data, $render_css);
					if($save_files){
						$save_meta = $this->save_settings($template_name, $template_display_name, $template_json);
					}
					wp_send_json($save_files);
				}else if(isset($_POST['test_mail_data'])){
					$response = $this->prepare_template_test_mail();
				}
			}
			wp_send_json($response);
		}else{
			wp_die();
		}
	}	

	public function save_settings($template_name, $display_name, $template_json){
		$settings = $this->prepare_settings($template_name, $display_name, $template_json);
		$result = WECMF_Email_Customizer_Utils::thwecmf_save_template_settings($settings);
		return $result;
	}

	public function save_template_files($template_name, $render_data, $render_css){
		$path_template = THWECMF_CUSTOM_T_PATH.$template_name.'.php';
		$template_html_final = $this->prepare_email_content_wrapper($render_data);
		$content = $this->create_inline_styles( $template_html_final, $render_css );
		$template_html_final = $this->insert_dynamic_data($content);
		$save_render_file = $this->save_template_file($template_html_final, $path_template);
		return $save_render_file;
	}

	public function prepare_template_test_mail(){
		$test_mail_id = isset($_POST['test_mail_id']) ? sanitize_email($_POST['test_mail_id']) : "";
		$test_mail_content = isset($_POST['test_mail_data']) ?  wp_kses(trim(stripslashes($_POST['test_mail_data'])),wp_kses_allowed_html('post')) : '';
		$test_mail_content = $this->replace_woocommerce_hooks_contents($test_mail_content,'test-mail');
		$test_mail_css = isset($_POST['test_mail_css']) ? sanitize_textarea_field( stripslashes($_POST['test_mail_css']) ) : '';
		$test_mail_content = $this->prepare_email_content_wrapper($test_mail_content);
		$email_content = $this->create_inline_styles($test_mail_content, $test_mail_css);
		$email_subject = 'Test Email';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$send_mail = wp_mail($test_mail_id, $email_subject, $email_content, $headers);
		return $send_mail;
	}

	function prepare_email_content_wrapper($content){
		$wrap_css_arr = apply_filters('thwecmf_template_wrapper_style_override', $this->temp_wrapper_styles);
		$wrap_css = 'background-color:'.$wrap_css_arr['bg'].';'.'padding:'.$wrap_css_arr['padding'].';';
		$wrapper = '<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">';
		$wrapper .= '<tr>';
		$wrapper .= '<td align="center" valign="top" style="'.$wrap_css.'">';
		$wrapper .= $content;
		$wrapper .= '</td>';
		$wrapper .= '</tr>';
		$wrapper .= '</tr></table>';									
		return $wrapper;
	}

	public function save_template_file($content, $path, $css=false){
		$saved = false;
		$myfile_template = fopen($path, "w") or die("Unable to open file!");
		if(false !== $myfile_template){
			fwrite($myfile_template, $content);
			fclose($myfile_template);
			$saved = true; 
		}
		return $saved;
	}

	public function create_inline_styles( $content, $css ) {
		$emogrifier_support = class_exists( 'DOMDocument' ) && version_compare( PHP_VERSION, '5.5', '>=' );
		if ( $content && $css && $emogrifier_support) {
			$emogrifier_class = '\\Pelago\\Emogrifier';
			$emogrifier_class = WECMF_Email_Customizer_Utils::thwecmf_emogrifier_version_check() ? '\\Pelago\\Emogrifier' : 'Emogrifier';
			if ( ! class_exists( $emogrifier_class ) ) {
				require_once(WP_PLUGIN_DIR.'/woocommerce/includes/libraries/class-emogrifier.php');
			}
			try {
				$emogrifier = new $emogrifier_class( $content, $css );
				$content    = $emogrifier->emogrify();
				$content    = htmlspecialchars_decode($content);
			} catch ( Exception $e ) {
			}
		}
		return $content;
	}


	public function prepare_settings($template_name, $display_name, $template_json){
		$settings = WECMF_Email_Customizer_Utils::thwecmf_get_template_settings();
		$data = $this->prepare_template_meta_data($template_name, $display_name, $template_json);
		$settings['templates'][$template_name] = $data;
		return $settings;
	}

	public function prepare_template_meta_data($template_name, $display_name, $template_json){
		$file_name = $template_name ? $template_name.'.php' : false;
		$data = array();
		$data['file_name'] = $file_name;
		$data['display_name'] = $display_name;
		$data['template_data'] = $template_json;
		return $data;
	}

	public function insert_dynamic_data($template_html_final){
		$modified_data = $template_html_final;
		$modified_data = $this->replace_thwecmf_placeholder_data($modified_data);
		$modified_data = str_replace('<span>{billing_address}</span>', $this->billing_data(), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_before_shipping_address}</span>', $this->shipping_data_additional(true), $modified_data);
		$modified_data = str_replace('<span>{thwecmf_after_shipping_address}</span>', $this->shipping_data_additional(false), $modified_data);
		$modified_data = str_replace('<span>{shipping_address}</span>', $this->shipping_data(), $modified_data);
		$modified_data = str_replace('<span class="thwecmf_before_shipping_table"></span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span class="thwecmf_after_shipping_table"></span>', $this->add_order_foot(), $modified_data);
		$modified_data = str_replace('<span class="thwecmf_before_billing_table"></span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span class="thwecmf_after_billing_table"></span>', $this->add_order_foot(), $modified_data);
		$modified_data = $this->replace_woocommerce_hooks_contents($modified_data);
		return $modified_data;
	}

	public function replace_thwecmf_placeholder_data($modified_data){
		$modified_data = str_replace('{th_customer_name}', $this->wecmf_get_customer_name(), $modified_data);
		$modified_data = str_replace('{th_site_name}', $this->wecmf_get_site_name(), $modified_data);
		$modified_data = str_replace('{th_account_area_url}', $this->wecmf_get_account_area_url(), $modified_data);
		$modified_data = str_replace('{th_user_login}', $this->wecmf_get_user_login(), $modified_data);
		$modified_data = str_replace('{th_user_pass}', $this->wecmf_get_user_pass(), $modified_data);
		/**
		* Placeholders made compatible with the premuim version
 		* @version 3.7.0
 		*/
 		$modified_data = str_replace('{customer_name}', $this->wecmf_get_customer_name(), $modified_data);
		$modified_data = str_replace('{site_name}', $this->wecmf_get_site_name(), $modified_data);
		$modified_data = str_replace('{account_area_url}', $this->wecmf_get_account_area_url(), $modified_data);
		$modified_data = str_replace('{user_login}', $this->wecmf_get_user_login(), $modified_data);
		$modified_data = str_replace('{user_pass}', $this->wecmf_get_user_pass(), $modified_data);
		return $modified_data;
	}

	public function replace_woocommerce_hooks_contents($modified_data,$mail_type=false){
		$modified_data = str_replace('<p class="thwecmf-hook-code">{email_header_hook}</p>', $this->thwecmf_email_hooks('{email_header_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{email_order_details_hook}</p>', $this->thwecmf_email_hooks('{email_order_details_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{before_order_table_hook}</p>', $this->thwecmf_email_hooks('{before_order_table_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{after_order_table_hook}</p>', $this->thwecmf_email_hooks('{after_order_table_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{order_meta_hook}</p>', $this->thwecmf_email_hooks('{order_meta_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{customer_details_hook}</p>', $this->thwecmf_email_hooks('{customer_details_hook}'), $modified_data);
		$modified_data = str_replace('<p class="thwecmf-hook-code">{email_footer_hook}</p>', $this->thwecmf_email_hooks('{email_footer_hook}'), $modified_data);
		return $modified_data;
	}

	public function wecmf_get_customer_name(){
		$customer_name = '<?php if(isset($order)) : ?>';
		$customer_name.= '<?php echo esc_html($order->get_billing_first_name()).\' \'.esc_html($order->get_billing_last_name()); ?>';
		$customer_name.= '<?php elseif(isset($user_login)): ?>';
		$customer_name.= '<?php echo esc_html($user_login); ?>'; 
		$customer_name.= '<?php endif; ?>';
		return $customer_name;
	}

	public function wecmf_get_site_name(){
		$site_name = '<?php echo esc_html( get_bloginfo() );?>';
		return $site_name;
	}

	public function wecmf_get_account_area_url(){
		$account_area_url = '<?php echo make_clickable( esc_url( wc_get_page_permalink( \'myaccount\' ) ) ); ?>';
		return $account_area_url;
	}

	public function wecmf_get_user_login(){
		$user_login = '<?php if(isset($user_login)){ ?>';
		$user_login .= '<?php echo \'<strong>\' . esc_html( $user_login ) . \'</strong>\' ?>';
		$user_login .= '<?php } ?>';
		return $user_login;
	}

	public function wecmf_get_user_pass(){
		$user_pass = '<?php if ( \'yes\' === get_option( \'woocommerce_registration_generate_password\' ) && isset($password_generated) ) : ?>';
		$user_pass.= '<?php echo \'<strong>\' . esc_html( $user_pass ) . \'</strong>\' ?>';
		$user_pass.= '<?php endif; ?>';
		return $user_pass;
	}

	public function billing_data(){
		$address = '<?php echo ( $address = $order->get_formatted_billing_address() ) ? esc_html( $address ) : __( "N/A", "woocommerce"); ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<br><?php echo esc_html( $order->get_billing_phone() ); ?>
				<?php endif; ?>
				<?php if ( $order->get_billing_email() ) : ?>
					<br><span style="color:inherit;"><?php echo esc_html( $order->get_billing_email() ); ?></span>
				<?php endif; ?>';
		return $address;
	}

	public function shipping_data_additional($position){
		$additional = '';
		if($position){
			$additional .= '<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>';
		}else{
			$additional .= '<?php endif; ?>';
		}
		return $additional;
	}

	public function shipping_data(){
		$address = '<?php echo esc_html( $order->get_formatted_shipping_address() ); ?>';
		return $address;
	}

	public function add_order_head(){
		$order_head = '<?php if(isset($order)){?>';
		return $order_head;
	}
	
	public function add_order_foot(){
		$order_foot = '<?php } ?>';
		return $order_foot;
	}

	public function thwecmf_email_hooks($hook){
		switch($hook){
			 case '{email_header_hook}':
                $hook ='<?php do_action( \'woocommerce_email_header\', $email_heading, $email ); ?>'; 
                break;
 			case '{email_order_details_hook}': 
 				$hook = '<?php if(isset($order)){ 
 					do_action( \'woocommerce_email_order_details\', $order, $sent_to_admin, $plain_text, $email ); 
 				}?>';
 				break;
  			case '{before_order_table_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action(\'woocommerce_email_before_order_table\', $order, $sent_to_admin, $plain_text, $email); 
  				}?>';
 				break;
  			case '{after_order_table_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action(\'woocommerce_email_after_order_table\', $order, $sent_to_admin, $plain_text, $email); 
  				}?>';
 				break;
  			case '{order_meta_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action( \'woocommerce_email_order_meta\', $order, $sent_to_admin, $plain_text, $email ); 
  				}?>';
 				break;
  			case '{customer_details_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action( \'woocommerce_email_customer_details\', $order, $sent_to_admin, $plain_text, $email ); 
  				}?>';
 				break;
 			case '{email_footer_hook}':
                $hook = '<?php do_action( \'woocommerce_email_footer\', $email ); ?>';
                break;
            case '{email_footer_blogname}':
            $hook = '<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( \'woocommerce_email_footer_text\', \'\' ) ) ) ); ?>';
            default:
                $hook = '';
		}
		return $hook;
	}
}
endif;