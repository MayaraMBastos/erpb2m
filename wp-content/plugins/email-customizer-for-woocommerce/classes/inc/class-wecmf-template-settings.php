<?php
/**
 * Woo Email Customizer
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Template_Settings')):
class WECMF_Template_Settings extends WECMF_Builder_Settings {
	protected static $_instance = null;
	private $cell_props_L = array();
	private $cell_props_CB = array();
	private $section_props = array();
	private $edit_template_form_props = array();
	private $map_template_form_props = array();
	private $field_props_display = array();
	private $image_props;
	private $settings = '';
	private $default_settings = array();
	private $edit_url;
	private $template_status = array();
	private $template_list = array();
	private $map_msgs = array();
	private $missing_templates = array();
	private $temp_f_new_order = array();
	private $temp_f_new_account = array();


	public function __construct() {
		parent::__construct('template_settings', '');
		$this->init_constants();
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init_constants(){
		$this->cell_props = array( 
			'label_cell_props' => 'width="18%"', 
			'input_width' => '350px',  
		);
		$this->cell_props_L = array( 
			'label_cell_props' => 'width="25%"', 
			'input_cell_props' => 'width="34%"', 
			'input_width' => '350px',  
		);
		
		$this->cell_props_R = array( 
			'label_cell_props' => 'width="13%"', 
			'input_cell_props' => 'width="34%"', 
			'input_width' => '250px', 
		);

		$this->cell_props_CB = array( 
			'label_cell_props' => 'width="3%"', 
			'input_cell_props' => 'width="3%"', 
		);

		$this->image_props = 'style="width:100%;height:100%;"'; 

		$this->default_settings = array('default'=>'Default');
		$this->edit_url = $this->get_admin_url();
		$this->temp_f_new_order = array('customer_processing_order' => 'Customer Processing Order');
		$this->temp_f_new_account = array('customer_new_account' => 'Customer New Account');
		$this->template_status =array(
			'0'=>'customer-processing-order',
			'1'=>'customer-new-account',
		);
		$this->map_msgs = array(
			true	=> array(
				'msg' 	=> 	array(
					'save'		=>	'Settings Saved',
					'reset'		=>	'Template Settings Successfully Reset',
					'delete'	=>	'Template Successfully Deleted',
					'delete'	=>	'Template Successfully Deleted',
				),
				'class'		=>	'thwecmf-save-success',
			),
			false	=> array(
				'msg' 	=> 	array(
					'save'				=>	'Your changes were not saved due to an error (or you made none!).',
					'reset'				=>	'Reset not done due to an error (or nothing to reset!).',
					'delete'			=>	'An error occured or (or template file doesn\'t exist!).',
					'template-missing'	=>  'Your changes were not saved due to missing template files'
				),
				'class'		=>	'thwecmf-save-error',
			),
		);
	}
	
	public function init_field_form_props(){
		$this->template_list = WECMF_Email_Customizer_Utils::thwecmf_get_template_list();
		$temp_f_pro = array('' => 'Default Template');
		foreach($this->template_list as $key => $value){
			$display_name = isset($value['display_name']) ? $value['display_name'] : $key;
			$file_name = isset($value['file_name']) ? $value['file_name'] : $key;
			$template_files[$key] = $display_name;
		}		
		$c_n_order = array_merge(array('' => 'Default Template'),$this->temp_f_new_order);
		$c_n_account = array_merge(array('' => 'Default Template'), $this->temp_f_new_account);
		$this->map_template_form_props = array(
			'section_map_templates' => array('title'=>'Email Notification Mapping', 'type'=>'separator', 'colspan'=>'2','sub_label'=>'Choose the templates for woocommerce order notifications from the list'),
			'customer-processing-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Processing Order', 'value'=>'','class'=>'thwecmf-template-map-select2','options'=>$c_n_order),
			'customer-new-account'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'New Account', 'value'=>'','class'=>'thwecmf-template-map-select2','options'=>$c_n_account),
			'admin-new-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin New Order Email', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'admin-cancelled-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin Cancelled Order Email', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'admin-failed-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin Failed Order Email', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'customer-completed-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Completed Order', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'customer-on-hold-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer On Hold Order Email', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'customer-refunded-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Refund Order', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'customer-invoice'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer invoice / Order details ', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'customer-note'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Note', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),
			'customer-reset-password'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Reset Password', 'value'=>'','class'=>'thwecmf-template-map-select2 thwecmf-premium-disabled-input','options'=>$temp_f_pro),	
		);
	}
	
	public function set_edit_form_fields(){
		$template_files_edit = array_merge(array('' => 'Select Template'),$this->temp_f_new_order,$this->temp_f_new_account);
		$this->edit_template_form_props = array(
			'section_edit_templates' => array('title'=>'Edit Template', 'type'=>'separator', 'colspan'=>'2','dashicons'=>'dashicons-info','dashicon-title'=>'If any of the template file is missing, choose the missing template from the list and click Edit button to edit the template. The missing template will be opened in the builder. Save the template again to create the template file'),
			'edit_template'		=> array('type'=>'select', 'name'=>'edit_template', 'label'=>'Choose Template', 'value'=>'','class'=>'thwecmf-template-map-select2','options'=>$template_files_edit),
			);
	}

	public function render_page(){
		$this->output_tabs();
		$this->output_feature_notices();
		$this->render_content();
	}
	
	public function reset_to_default() {
		$settings = WECMF_Email_Customizer_Utils::thwecmf_reset_template_map();
		$result = WECMF_Email_Customizer_Utils::thwecmf_save_template_settings($settings);
		return $result;
	}

	private function save_settings(){
		$result = false;
   		if(isset($_POST['i_template-list'])){
   			$temp_data = array();
   			$settings = $this->prepare_settings();
   			$result = WECMF_Email_Customizer_Utils::thwecmf_save_template_settings($settings);
   		}
		return $result;
	}

	private function prepare_settings(){
		$settings = WECMF_Email_Customizer_Utils::thwecmf_get_template_settings();
		$template_map = $settings[WECMF_Email_Customizer_Utils::SETTINGS_KEY_TEMPLATE_MAP];
		$file_ext = 'php';
		foreach ($_POST['i_template-list'] as $key => $value) {
			$template_map[$this->template_status[sanitize_text_field( $key )]] = sanitize_text_field($value);
			if($value != ''){
				if(!WECMF_Email_Customizer_Utils::do_file_exist($value,$file_ext)){
					array_push($this->missing_templates, $value);
				}
			}
		}
		$settings[WECMF_Email_Customizer_Utils::SETTINGS_KEY_TEMPLATE_MAP] = $template_map;
		return $settings;
	}


	private function render_content(){
		$map_result = 'onload';
		$map_action = false;
		if(isset($_POST['save_settings'])){
			if ( ! empty( $_POST ) && check_admin_referer( 'reset_template_map', 'thwecmf_reset_template_map' ) && WECMF_Email_Customizer_Utils::is_user_capable()) {
   				$save_status = $this->save_settings();
				if(isset($save_status['error'])){
					$map_result = false;
					$map_action = 'template-missing';
				}else{
					$map_result = $save_status;
					$map_action = 'save';
				}
			}
		}
		else if(isset($_POST['reset_settings'])){
			if ( ! empty( $_POST ) && check_admin_referer( 'reset_template_map', 'thwecmf_reset_template_map' ) && WECMF_Email_Customizer_Utils::is_user_capable()) {
				$map_result = $this->reset_to_default();
				$map_action = 'reset';
			}
		}
		if($map_result !== 'onload' && $map_action){
			$class = isset($this->map_msgs[$map_result]['class']) ? $this->map_msgs[$map_result]['class'] : '';
			$msg = isset($this->map_msgs[$map_result]['msg'][$map_action]) ? $this->map_msgs[$map_result]['msg'][$map_action] : '';
			?>	
			<div id="thwecmf_temp_map_save_messages" class="thwecmf-show-save <?php echo esc_attr($class); ?>">
				<?php echo esc_html($msg); ?>
			</div>
			<script type="text/javascript">
				$(function() {
				    setTimeout(function(){
						$("#thwecmf_temp_map_save_messages").remove();
					}, 2500);
				});
			</script>
		<?php
		}
		$this->init_field_form_props();
		$this->set_edit_form_fields();
		$template_map = WECMF_Email_Customizer_Utils::thwecmf_get_template_map();
		$choose_template_fields = $this->map_template_form_props;
		$this->render_edit_template_form();
		$this->render_map_template_form($choose_template_fields, 'template_map_form', '', $template_map);
    }
	
    private function show_missing_template_info(){
    	$index=0;
    	if(!empty($this->missing_templates)){
    	?>
    		<div id="thwec-missing-template-notices">
	    		<p>The following template files are not found</p>
    			<?php
    			foreach ($this->missing_templates as $key => $value) {
    				$index= $key++;
    				echo '<p class="thwec-missing-temp-names">'.esc_html($key).'. '.esc_html($value).'</p>';
    			}
	    		?>
    			<span class="thwec-missing-temp-warning">Note: Please choose the missing template from <b>Edit Template</b> section and edit the template. Save template again to create the missing template file</span>
    		</div>
    	<?php
    	}
    }

	private function render_edit_template_form(){
		$fields = $this->edit_template_form_props;
		?>            
        <div style="padding-left: 30px;">               
		    <form name="thwecmf_edit_template_form" id="thwecmf_edit_template_form" action="" method="POST" >
		    	<?php
		    	if ( function_exists('wp_nonce_field') ){
					wp_nonce_field( 'thwecmf_edit_template_action', 'thwecmf_edit_template' );
		    	}
		    	?>
                <table class="form-table thpladmin-form-table thwec-template-action-tb">
                    <tbody>
                    	<?php 
                    	$this->render_section_separator($fields['section_edit_templates']);
                    	$this->render_form_fields($fields['edit_template'], $this->cell_props_L);
						?>
						<tr class="thwec-spacer"><td></td></tr>
						<tr>
							<td>
								<input type="submit" name="edit_template" formaction="<?php echo esc_url($this->edit_url); ?>" onclick="thwecmfTemplateEditListner(this)" class="button-primary" value="Edit">
								<input type="button" class="button-primary thwecmf-premium-disabled-input" value="Delete" disabled title="This feature is available only in premium version">
							</td>
						</tr>
                    </tbody>
                </table> 
            </form>
    	</div>       
    	<?php
	}

	private function render_map_template_form($fields, $form_name, $form_action='', $settings=false){
		?>            
        <div style="padding-left: 30px;">               
		    <form name="<?php echo esc_attr( $form_name ); ?>" action="<?php echo esc_attr( $form_action ); ?>" id="template_map_form" method="POST">
		    	<?php
		    	if ( function_exists('wp_nonce_field') ){
					wp_nonce_field( 'reset_template_map', 'thwecmf_reset_template_map' );
		    	}
		    	?>
                <table class="form-table thpladmin-form-table">
                    <?php 
                    $this->render_section_separator($this->map_template_form_props['section_map_templates']);
                    ?>
                    <tr><td><span class="thpladmin-subtitle"><?php echo esc_html(  $this->map_template_form_props['section_map_templates']['sub_label'] )?></span></td></tr>
                    <tr class="thwec-spacer"><td></td></tr>
                    <?php
                    $this->render_woocommerce_email_notificaiton_table($fields, $form_name, $form_action='', $settings);?>
                </table> 
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes" onclick="return thwecmfTemplateMapValidation(this)">
                    <input type="submit" name="reset_settings" class="button" value="Reset to default" 
					onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>       
    	<?php
    }

    private function render_woocommerce_email_notificaiton_table($fields, $form_name, $form_action='', $settings=false){
    	?>
    	<table class="wc_emails widefat" id="thpladmin-form-email-notification-table" cellspacing="0">
			<thead>
				<tr>
					<?php
					$columns =  array(
							'name'       => __( 'Email', 'woocommerce' ),
							'email_type' => __( 'Template', 'woocommerce' ),
					);
					foreach ( $columns as $key => $column ) {
						echo '<th class="wc-email-settings-table-' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($this->map_template_form_props as $key => $value) {
						if($key !=='section_map_templates'){
							if(is_array($settings) && isset($settings[$key])){
								if($value['type'] === 'checkbox'){
									if($value['value'] === $settings[$key]){
										$value['checked'] = 1;
									}
								}else{
									$value['value'] = $settings[$key];
									
								}
							}
							$this->render_form_fields($value, $this->cell_props_L,'template-map');
						}
					}
				?>
			</tbody>
		</table>
    	<?php
    }

    private function render_map_template_form_old($fields, $form_name, $form_action='', $settings=false){
		?>            
        <div style="padding-left: 30px;">               
		    <form name="<?php echo esc_attr($form_name); ?>" action="<?php echo esc_attr($form_action); ?>" id="template_map_form" method="POST">
                <table class="form-table thpladmin-form-table">
                    <tbody>
	                    <?php 
						foreach( $fields as $name => $field ) { 
							if($field['type'] === 'separator'){
								$this->render_section_separator($field);
							}else {
							?>
		                        <tr valign="top">
		                        	<?php
									if(is_array($settings) && isset($settings[$name])){
										if($field['type'] === 'checkbox'){
											if($field['value'] === $settings[$name]){
												$field['checked'] = 1;
											}
										}else{
											$field['value'] = $settings[$name];
											if(in_array($settings[$name], $this->missing_templates)){
												$field['class'] = 'thwec-missing-file-input';
												$field['template_error'] = true;
											}
										}
									}
									if($field['type'] === 'checkbox'){
										$this->render_form_fields($field, $this->cell_props_CB, false);
									}else if($field['type'] === 'multiselect' || $field['type'] === 'textarea'){
										$this->render_form_fields($field, $this->cell_props);
									}else{
										$this->render_form_fields($field, $this->cell_props);
									} 
								?>
		                        </tr>
                    		<?php 
							}
						} 
						?>
                    </tbody>
                </table> 
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes" onclick="return thwecTemplateMapValidation(this)">
                    <input type="submit" name="reset_settings" class="button" value="Reset to default" 
					onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>       
    	<?php
    }
}
endif;