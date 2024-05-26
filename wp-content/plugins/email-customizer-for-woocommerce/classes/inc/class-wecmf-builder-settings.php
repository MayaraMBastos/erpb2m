<?php
/**
 * Email Customizer for WooCommerce Builder Functions
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Builder_Settings')):
class WECMF_Builder_Settings extends WECMF_Settings_Page {
	protected static $_instance = null;
	private $cell_props_T = array();
	private $cell_props_FT = array();
	private $cell_props_4T = array();
	private $cell_props_S  = array();
	private $json_css_class = array();
	private $template_json_css = '';
	private $default_css = array();
	private $css_props = array();
	private $css_elm_props_map = array();
	private $field_props = array();
	private $template_display_name = '';
	private $thwecmf_templates = array();

	public function __construct() {
		parent::__construct();
		$this->get_field_form_props();
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}	

	public function get_field_form_props(){
		$this->cell_props_T = array( 
			'input_width' => '136px',
			'input_height' => '30px',
			'input_b_r' => '4px',
			'input_font_size' => '13px',  
		);

		$this->cell_props_T = array( 
			'input_width' => '279px',
			'input_height' => '30px',
			'input_b_r' => '4px',
			'input_font_size' => '13px',  
		);

		$this->cell_props_4T = array( 
			'label_cell_props' => 'style="width:13%"', 
			'input_cell_props' => 'style="width:34%"', 
		);
		
		$this->cell_props_S = array( 
			'input_width' => '136px', 
			'input_b_r' => '4px', 
			'input_font_size' => '13px', 
		);

		$this->json_css_class = array(
			'row'		=> 'thwecmf-row',
			'column'	=> 'thwecmf-column-padding',
		);

		$this->template_json_css = '';

		$this->default_css = array( 
        	'color' 			=> 'transparent',
        	'background-color' 	=> 'transparent',
        	'border-color'		=> 'transparent',
        	'background-color' 	=> 'transparent',
        	'padding-top' 		=> '0px',
        	'padding-right'  	=> '0px',
        	'padding-bottom' 	=> '0px',
        	'padding-left' 		=> '0px',
        	'background-image' 	=> 'none',
        );

		$this->css_props = array(  
	        'p_t'=>'padding-top',
	        'p_r'=>'padding-right',
	        'p_b'=>'padding-bottom',
	        'p_l'=>'padding-left', 
	        'm_t'=>'margin-top',
	        'm_r'=>'margin-right',
	        'm_b'=>'margin-bottom',
	        'm_l' => 'margin-left',
	        'width' => 'width',
	        'height' => 'height',
	        'size_width' => 'width',
	        'size_height' => 'height',
	        'b_t' => 'border-top',
	        'b_r' => 'border-right',
	        'b_b' => 'border-bottom',
	        'b_l' => 'border-left',
	        'border_style' => 'border-style',
	        'border_color' => 'border-color',
	        'bg_color'	=> 'background-color',
	        'upload_img_url' => 'display',
	        'color' => 'color',
	        'font_size' => 'font-size',
	        'text_align' => 'text-align',
	        'align' => 'float',
	        'content_align' => 'text-align',
	        'img_width' => 'width',
	        'img_height' => 'height',
	        'img_size_width' => 'width',
	        'img_size_height' => 'height',
	        'details_color' => 'color',
	        'details_font_size' => 'font-size',
	        'details_text_align' => 'text-align',
	        'divider_height' => 'border-top-width',
	        'divider_color' => 'border-top-color',
	        'divider_style' => 'border-top-style',
	        'url' => 'display',
    	);

		$this->css_elm_props_map = WECMF_Email_Customizer_Utils::css_elm_props_mapping();
		$text_align = array('left' => 'Left','center' => 'Center','right' => 'Right');
		$float_align = array('left' => 'Left', 'right' => 'Right', 'none' => 'Center');
		$divider_options = array('dotted' => 'Dotted','solid' => 'Line','dashed' => 'Dashed');
		$border_style = array(
			'solid'=>'solid', 
			'dotted'=>'dotted', 
			'dashed'=>'dashed', 
			'none'=>'none',
		);
		$rad_options = array(
			'text'=>'Text',
			'html'=>'Html',
		);
		$this->field_props = array(
			'width' => array('type'=>'text', 'name'=>'width', 'label'=>'Width', 'value'=>''),
			'height' => array('type'=>'text', 'name'=>'height', 'label'=>'Height', 'value'=>''),
			'padding' => array('type'=>'fourside', 'name'=>'padding', 'label'=>'Padding', 'value'=>''),
			'margin' => array('type'=>'fourside', 'name'=>'margin', 'label'=>'Margin', 'value'=>''),			
			'img_width' => array('type'=>'text', 'name'=>'img_width', 'label'=>'Image Width', 'value'=>''),
			'img_height' => array('type'=>'text', 'name'=>'img_height', 'label'=>'Image Height', 'value'=>''),
			'img_size' => array('type'=>'twoside', 'name'=>'img_size', 'label'=>'Size', 'value'=>''),
			'img_size_range' => array('type'=>'range', 'name'=>'img_size_range', 'label'=>'Size', 'min'=>'10', 'max' => '100', 'value' => '50', 'class' => 'thwecmf-slider'),
			'icon_padding' => array('type'=>'fourside', 'name'=>'icon_padding', 'label'=>'Icon Padding', 'value'=>''),
			'img_border_radius' => array('type'=>'text', 'name'=>'img_border_radius', 'label'=>'Border Radius', 'value'=>''),
			'border_width' => array('type'=>'fourside', 'name'=>'border_width', 'label'=>'Border Width', 'value'=>''),
			'border_style' => array('type'=>'select', 'name'=>'border_style', 'label'=>'Border Style', 'options'=>$border_style),
			'border_color' => array('type'=>'colorpicker', 'name'=>'border_color', 'label'=>'Border Color', 'value'=>'','placeholder'=>'Color'),
			'border_radius' => array('type'=>'text', 'name'=>'border_radius', 'label'=>'Border Radius', 'value'=>''),
			'divider_height' => array('type'=>'text', 'name'=>'divider_height', 'label'=>'Divider Height', 'value'=>''),
			'divider_color' => array('type'=>'colorpicker', 'name'=>'divider_color', 'label'=>'Divider Color', 'value'=>'','placeholder'=>'Color'),
			'divider_style' => array('type'=>'select', 'name'=>'divider_style', 'label'=>'Divider Style', 'options'=>$border_style),
			'bg_color' => array('type'=>'colorpicker', 'name'=>'bg_color', 'label'=>'Color', 'placeholder'=>'Color', 'value'=>''),
			'url' => array('type'=>'text', 'name'=>'url', 'label'=>'URL', 'value'=>''),
			'upload_bg_url' => array('type'=>'hidden', 'name'=>'upload_bg_url', 'label'=>'', 'value'=>'','class'=>'thwecmf-upload-url'),
			'upload_img_url' => array('type'=>'hidden', 'name'=>'upload_img_url', 'label'=>'', 'value'=>'','class'=>'thwecmf-upload-url'),
			'title' => array('type'=>'text', 'name'=>'title', 'label'=>'Title', 'value'=>''),
			'content' => array('type'=>'text', 'name'=>'content', 'label'=>'Content', 'value'=>''),
			'textarea_content' => array('type'=>'textarea', 'name'=>'textarea_content', 'label'=>'Content', 'value'=>''),
			'color' => array('type'=>'colorpicker', 'name'=>'color', 'label'=>'Color', 'value'=>'', 'placeholder'=>'Color',),
			'font_size' => array('type'=>'text', 'name'=>'font_size', 'label'=>'Size', 'value'=>'', 'placeholder'=>'Size'),
			'details_color' => array('type'=>'colorpicker', 'name'=>'details_color', 'label'=>'Color', 'value'=>'','placeholder'=>'Color'),
			'details_font_size' => array('type'=>'text', 'name'=>'details_font_size', 'label'=>'Font Size', 'value'=>'','placeholder'=>'Size'),
			'content_align' => array('type'=>'alignment-icons', 'name'=>'content_align', 'label'=>'Alignment', 'class'=>'thwecmf-text-align-input', 'options'=>$float_align,'icon_flag'=>false),
			'text_align' => array('type'=>'alignment-icons', 'name'=>'text_align', 'label'=>'Text align', 'class'=>'thwecmf-text-align-input', 'icon_flag'=>true, 'options'=>$text_align),
			'details_text_align' => array('type'=>'alignment-icons', 'name'=>'details_text_align', 'label'=>'Text align', 'class'=>'thwecmf-text-align-input','icon_flag'=>true, 'options'=>$text_align),
			'textareacontent' => array('type'=>'textarea', 'name'=>'textareacontent', 'label'=>'Content', 'value'=>''),
			'size' => array('type'=>'twoside', 'name'=>'size', 'label'=>'Size', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input'),
			'content_size' => array('type'=>'twoside', 'name'=>'content_size', 'label'=>'Size', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'vertical_align' => array('type'=>'select', 'name'=>'vertical_align', 'label'=>'Vertical Align', 'options'=>array('top'=>'Top'), 'class' => 'thwecmf-premium-disabled-input'),
			'img_padding' => array('type'=>'fourside', 'name'=>'img_padding', 'label'=>'Image Padding', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input'),
			'img_margin' => array('type'=>'fourside', 'name'=>'img_margin', 'label'=>'Image Margin', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input'),
			'img_border_width' => array('type'=>'fourside', 'name'=>'img_border_width', 'label'=>'Border Width', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input'),
			'img_border_style' => array('type'=>'select', 'name'=>'img_border_style', 'label'=>'Border Style', 'class' => 'thwecmf-premium-disabled-input', 'options'=>$border_style),
			'img_border_color' => array('type'=>'colorpicker', 'name'=>'img_border_color', 'label'=>'Border Color', 'value'=>'','placeholder'=>'Color', 'class' => 'thwecmf-premium-disabled-input'),
			'img_bg_color' => array('type'=>'colorpicker', 'name'=>'img_bg_color', 'label'=>'BG Color', 'value'=>'','placeholder'=>'Color', 'class' => 'thwecmf-premium-disabled-input'),
			'bg_image' => array('type'=>'text', 'name'=>'bg_image', 'label'=>'Image', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'bg_position' => array('type'=>'text', 'name'=>'bg_position', 'label'=>'Position', 'placeholder'=>'Position', 'value'=>'','hint_text'=>'left top | x% y% | xpos ypos etc.', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'bg_size' => array('type'=>'text', 'name'=>'bg_size', 'label'=>'Size', 'placeholder'=>'Size', 'value'=>'', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'bg_repeat' => array('type'=>'select', 'name'=>'bg_repeat', 'label'=>'Repeat', 'options'=>array('no-repeat' => 'no-repeat'),'hint_text'=>'image should be repeated or not','class'=>'thwecmf-bg-repeat', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'font_weight' => array('type'=>'text', 'name'=>'font_weight', 'label'=>'Weight', 'value'=>'','placeholder'=>'Font Weight', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'font_family' => array('type'=>'select', 'name'=>'font_family', 'label'=>'Family', 'options'=> array('helvetica'=>'Helvetica'), 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'line_height' => array('type'=>'text', 'name'=>'line_height', 'label'=>'Line Height', 'value'=>'','placeholder'=>'Line height', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'details_font_weight' => array('type'=>'text', 'name'=>'details_font_weight', 'label'=>'Font Weight', 'value'=>'','placeholder'=>'Font weight', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'details_line_height' => array('type'=>'text', 'name'=>'details_line_height', 'label'=>'Line Height', 'value'=>'','placeholder'=>'Line height', 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'details_font_family' => array('type'=>'select', 'name'=>'details_font_family', 'label'=>'Font Family', 'options'=>array('helvetica'=>'Helvetica'), 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'align' => array('type'=>'alignment-icons', 'name'=>'align', 'label'=>'Alignment', 'class'=>'thwecmf-text-align-input', 'icon_flag'=>false, 'class' => 'thwecmf-premium-disabled-input', 'hint_text'=>'Premium Feature'),
			'border_spacing' => array('type'=>'text', 'name'=>'border_spacing', 'label'=>'Column Spacing', 'class' => 'thwecmf-premium-disabled-input'),
		);

		$this->thwecmf_templates = array('customer_processing_order', 'customer_new_account');
	}

	private function get_template_data($t_name){
		$template_data = false;
		if($t_name){
			$t_list = WECMF_Email_Customizer_Utils::thwecmf_get_template_settings();
			if(in_array( $t_name, $this->thwecmf_templates )){
				$template_data = isset($t_list['templates'][$t_name]) ? $t_list : WECMF_Email_Customizer_Utils::get_default_templates_json($t_name);
				if(apply_filters('thwecmf_force_settings_from_plugin',false)){
					$template_data = WECMF_Email_Customizer_Utils::get_default_templates_json($t_name);
				}
			}else{
				$url =  admin_url('admin.php?page=thwecmf_email_customizer_templates&feature=premium');
				wp_redirect($url); 
			}
			$this->template_display_name = $template_data['templates'][$t_name]['display_name'];
			$template_data = $template_data['templates'][$t_name]['template_data'];
			if($template_data == ''){
				$url = admin_url('admin.php?page=thwecmf_email_customizer_templates&feature=premium');
				wp_redirect($url);
			}
		}
		return $template_data;
	}

	private function get_temp_display_name($t_name){
		return $file_name = $t_name ? $t_name : "";
	}

	public function render_template_builder(){
		$template_details = isset($_POST['i_edit_template']) ? strtolower(preg_replace("/[^a-z_]+/i", "_", sanitize_text_field($_POST['i_edit_template']))): false;
		$template_json = '';
		$template_name = '';
		if($template_details){
			$template_json = $this->get_template_data($template_details);
			$template_name = $this->get_temp_display_name($template_details);
		}
		?>
		<div id="thwecmf-template-builder-wrapper" class="thwecmf-tbuilder-wrapper">
			<div class="thwecmf-tbuilder-header-panel">
				<div class="thwecmf-tbuilder-main-actions">
					<?php $this->render_builder_header_panel($this->template_display_name); ?>
				</div>
			</div>
			<div class="thwecmf-tbuilder-editor-wrapper thwecmf-tbuilder-sub-wrapper">
				<?php $this->output_builder_editor_panel($template_json); ?>
			</div>
			<div id="thwecmf-sidebar-element-wrapper" class="thwecmf-tbuilder-elm-wrapper thwecmf-tbuilder-sub-wrapper">
				<?php
				$this->output_builder_sidebar($template_json);
				?>
			</div>
		</div>
		<div id="thwecmf-ajax-load-modal"></div>
		<div id="thwecmf_builder_save_messages"></div>		
		<?php
	    $this->output_builder_sidebar_layout();
	    $this->output_builder_sidebar_layout_element();
	    $this->output_builder_sidebar_layout_settings();
		$this->render_template_elements();
	}

	private function render_builder_header_panel($template_name){
		$input_class = $template_name ? 'has-value' : '';
		$user_email = apply_filters('thwecmf_set_testmail_recepient', true) ? THWECMF_LOGIN_USER : "";
		?>
		<div class="thwecmf-tbuilder-header-grid">
			<div class="thwecmf-input-wrapper wecmf-input-effect">
			    <input class="thwecmf-template-name <?php echo esc_attr( $input_class ); ?>" type="text" name="thwecmf_template_name" id="thwecmf_template_save_name" value="<?php echo esc_attr( $template_name ); ?>" autocomplete="off" disabled>
			    <label>Template Name</label>
			    <span class="wecmf-focus-border"></span>
       		</div>
       		<div class="thwecmf-save-action-notices"></div>
       		<div class="thwecmf-test-mail-icon thwecmf-header-icons">
				<div class="thwecmf-header-icons-holder" onclick="thwecmfClickTestMail(this)">
					<img src="<?php echo esc_url(TH_WECMF_ASSETS_URL.'images/paper-plane.svg');?>" alt="Test Email" title="Test Email">
				</div>
				<div class="thwecmf-test-mail-wrapper">
					<div class="thwecmf-test-mail-header-actions">
						<span class="dashicons dashicons-no-alt" onclick="thwecmfCloseTestMail(this)"></span>
					</div>
					<div class="thwecmf-test-mail-popup-actions">
						<?php
						$tooltip = 'Enter an email id and click Send button, to see how the email template looks in email clients';
						?>
						<div class="thwecmf-test-mail-info">Send a Test <?php $this->render_form_element_tooltip($tooltip);?></div>
							<input type="text" class="thwecmf-test-mail-input" name="thwecmf-test-mail-id" placeholder="Enter an email id" value="<?php echo esc_attr( $user_email ); ?>">
						<input type="button" value="Send" class="thwecmf-btn-send-mail button" onclick="thwecClickTestMailAction(this)">
						<br>
						<div class="thwecmf-test-mail-validate-notice"></div> 
						
					</div>
				</div>
			</div>
			<div class="thwecmf-new-template-icon thwecmf-header-icons">
				<div class="thwecmf-header-icons-holder thwecmf-premium-disabled-feature">
					<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/plus-button.svg' );?>" alt="New Template" title="This feature is available only in the premium version">
				</div>
			</div>
		</div>
	<?php
	}

	private function output_builder_editor_panel($template_json) {
		?>
		<table class="thwecmf-tbuilder-editor-grid">
			<tr>
				<td class="thwecmf-tbuilder-editor">
					<div id="thwecmf_drag_n_drop" class="thwecmf-dropable-wrapper">
						<?php 
						if($template_json){
							$this->render_template_blocks_json($template_json);
						}else { ?>
							<table cellpadding="0" cellspacing="0" border="0" width="600" id="tbf_t_builder" class="thwecmf-dropable sortable thwecmf-main-builder" data-global-id="1000" data-track-save="1000" data-css-change="true" data-css-props='{"b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"solid","border_color":"#dedede","bg_color":"#ffffff"}'>
								<tr>
									<td class="thwecmf-builder-column"></td>
								</tr>
							</table>
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>
		<?php
		$this->render_template_builder_css_section('thwecmf_template_css');
		?>
		<div id="thwecmf_tbuilder_editor_preview" class="thwecmf-tbuilder-editor-preview" style="display: none;"></div>
		<?php
	}

	private function output_builder_sidebar($template_json){
		$this->output_builder_sidebar_header();
		$this->output_builder_sidebar_body($template_json);
	}

	private function output_builder_sidebar_layout(){
	?>
		<div id="thwecmf_builder_panel_layout" style="display:none;">
			<div class="wecmf-layout-panel-outer-wrapper">
				<div class="wecmf-layout-panel-inner-wrapper">
					<table class="thwecmf-tbuilder-elm-grid">
						<tbody>
							<tr>
								<td class="thwecmf-layout-note"><p>Pick the column layout for the row</p></td>
							</tr>
							<tr>
								<td class="thwecmf-elm-col">
									<div id="thwecmf-one-column" class="thwecmf-tbuilder-elm column_layout" data-block-name="one_column">
										<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/one_column.png' );?>" alt="One column">
										<p>1 Column</p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="thwecmf-elm-col">
									<div id="thwecmf-two-column" class="thwecmf-tbuilder-elm thwecmf_column_layout" data-block-name="two_column">
										<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/two_column.png' );?>" alt="Two column">
										<p>2 Column</p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="elm-col-premium">
									<div id="thwecmf-three-column" class="thwecmf-tbuilder-elm thwecmf_column_layout thwecmf-premium-disabled-feature" data-block-name="three_column">
										<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/three_column.png' );?>" alt="Three column">
										<p>3 Column</p>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-layout">Upgrade</span>	
								</td>
							</tr>
							<tr>
								<td class="elm-col-premium">
									<div id="thwecmf-four-column" class="thwecmf-tbuilder-elm thwecmf_column_layout thwecmf-premium-disabled-feature" data-block-name="four_column">
										<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/four_column.png' );?>" alt="Four column">
										<p>4 Column</p>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-layout">Upgrade</span>	
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php
	}

	private function output_builder_sidebar_layout_element(){
	?>
		<div id="thwecmf_template_builder_panel_layout_element" style="display:none;">
			<form id="thwecmf_tbuilder_layout_elm_form" method ="post" action="">
				<input type="hidden" name="i_thwecmf_block_id" value="">
				<input type="hidden" name="i_thwecmf_block_name" value="">
				<input type="hidden" name="i_thwecmf_col_count" value="">
				<div class="outer-wrapper">
					<div class="inner-wrapper">
					<?php $this->panel_layout_elements_list(); ?>
					</div>
				</div>
			</form>
		</div>
	<?php
	}

	private function output_builder_sidebar_layout_settings(){
		?>
		<div id="thwecmf_builder_block_edit_form" class="thwecmf-tbuilder-elm-edit" style="display: none;">
			<form id="thwecmf_builder_block_form" class="popup_form_class">
				<input type="hidden" name="i_thwecmf_block_id" value="">
				<input type="hidden" name="i_thwecmf_block_name" value="">
				<input type="hidden" name="i_thwecmf_block_props" value="">
				<input type="hidden" name="i_thwecmf_popup_flag" value="">
				<div class="thwecmf_field_form_outer_wrapper">
					<div class="thwecmf_field_form_inner_wrapper">
						<table class="thwecmf_field_form_general" cellspacing="10px">
							<tr>
								<td class="thwecmf-general-form-td"></td>
							</tr>
						</table>
					</div>
				</div>
				<table id="thwecmf_save_settings_button">
					<tr>
						<td>
							<button type="button" class="thwecmf_save_form button-primary" onclick="thwecmfSidebarEditElementForm(this)">Save</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<?php
	}

	private function render_template_elements(){
		$this->render_elm_pp_layout_rows();
		$this->render_elm_pp_layout_cols(); 
		$this->render_elm_pp_image();
		$this->render_elm_pp_billing();
		$this->render_elm_pp_shipping();
		$this->render_elm_pp_divider();
		$this->render_elm_pp_text();
		$this->render_elm_pp_gap();
		$this->render_elm_pp_builder();
		$this->render_elm_1_column_layout();
		$this->render_elm_2_column_layout();
		$this->render_elm_billing();
		$this->render_elm_shipping();
		$this->render_elm_text();
		$this->render_elm_image();
		$this->render_elm_divider();
		$this->render_elm_gap();
		$this->render_hook_email_header();
		$this->render_hook_email_order_details();
		$this->render_hook_before_order_table();
		$this->render_hook_after_order_table();
		$this->render_hook_order_meta();
		$this->render_hook_customer_address();
		$this->render_hook_email_footer();
		$this->render_track_row_content();
		$this->render_track_col_content();
		$this->render_track_elm_content();
		$this->render_track_hook_content();
		$this->render_pp_confirmation_alerts();
		$this->render_pp_confirmation_msg_line();
	}

	private function render_builder_elm_pp_fragment_border($content=false,$prefix='',$toggle=false, $premium_disable=false){
		$atts = array('content' => 'Border', 'padding-top' => '10px');
		if($content){
			$atts['content'] = $content;
		}
		$toggle_class = $toggle ? '' : 'thwecmf-toggle-edit-section';
		?>
		<table class="thwecmf-edit-form <?php echo esc_attr( $toggle_class ); ?>">
			<thead class="thwecmf-toggle-section">
			<?php
			$this->render_form_fragment_h_separator($atts,true);
			?>
			</thead>
			<tbody>
				<?php
				if($premium_disable){
					$bd_width = $this->field_props[$prefix.'border_width'];
					$bd_width['class'] = 'thwecmf-premium-disabled-input';
					$bd_width['hint_text'] = 'Premium Feature';
					$bd_color = $this->field_props[$prefix.'border_style'];
					$bd_color['class'] = 'thwecmf-premium-disabled-input';
					$bd_color['hint_text'] = 'Premium Feature';
					$bd_style = $this->field_props[$prefix.'border_color'];
					$bd_style['class'] = 'thwecmf-premium-disabled-input';
					$bd_style['hint_text'] = 'Premium Feature';
					$this->render_form_fields($bd_width, $this->cell_props_T);  

					$this->render_form_fields($bd_color, $this->cell_props_S); 
					$this->render_form_fields($bd_style, $this->cell_props_T);

				}else{
					$this->render_form_fields($this->field_props[$prefix.'border_width'], $this->cell_props_T);  

					$this->render_form_fields($this->field_props[$prefix.'border_style'], $this->cell_props_S); 
					$this->render_form_fields($this->field_props[$prefix.'border_color'], $this->cell_props_T);
				}
				?>
			</tbody>
		</table>
	<?php
	}

	private function render_builder_elm_pp_fragment_bg($content=false,$prefix=''){
		$atts = array('content' => 'Background', 'padding-top' => '10px');
		if($content){
			$atts['content'] = $content;
		}
		$cell_props = array('input_width' => '100px');
		$cell_props_combo = array('input_width' => '89px','input_margin' => '0px 6px 0px 0px', 'input_height' => '30px', 'input_b_r' => '4px','input_font_size' => '13px');
		$cell_props_combo_S = array('input_width' => '89px','input_margin' => '-4px 0px 0px 0px', 'input_height' => '30px', 'input_b_r' => '4px', 'input_font_size' => '13px');
		?>
		<table class="thwecmf-edit-form thwecmf-toggle-edit-section">
			<thead class="thwecmf-toggle-section">
				<?php
				$this->render_form_fragment_h_separator($atts,true);
				?>
			</thead>
			<tbody>
				<?php
				$this->render_form_fields($this->field_props[$prefix.'bg_color']);
				?>
				<tr class="thwecmf-input-spacer"><td></td></tr>
				<?php
				$this->render_builder_elm_pp_fragment_img_upload('bg_image','upload_bg_url');
				?>
				<tr class="thwecmf-input-spacer"><td></td></tr>
				<tr>
					<td>
						<?php
						$this->render_form_fields($this->field_props[$prefix.'bg_size'], $cell_props_combo,false);
						$this->render_form_fields($this->field_props[$prefix.'bg_position'], $cell_props_combo,false);
						$this->render_form_fields($this->field_props[$prefix.'bg_repeat'], $cell_props_combo_S,false);
						?>
					</td>
				</tr>
				<tr class="thwecmf-input-spacer"><td></td></tr>
			</tbody>
		</table>
		<?php
	}

	private function render_builder_elm_pp_fragment_img_upload($props,$url_type){
		$pro_msg = '';
		if($props == 'bg_image'){
			$pro_msg .= '<span class="th-premium-feature-msg">Pro</span>';
		}
		?>
		<tr>
			<td>
				Upload Image <?php echo wp_kses( $pro_msg ,wp_kses_allowed_html('post') ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<div class="thwecmf-upload-action-settings thwecmf-img-preview-<?php echo esc_attr( $props );?>">
					<div class="thwecmf-upload-preview" data-default-url ="<?php echo esc_url(TH_WECMF_ASSETS_URL.'images/placeholder.png' ); ?>">
						<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/placeholder.png' ); ?>" alt="Upload Preview">
					</div>
					<input type="button" name="thwecmf_image_upload" value="Upload" class="thwecmf-upload-button button <?php echo $props=='bg_image' ? 'thwecmf-premium-disabled-input':'';?>" <?php echo $props=='bg_image' ? 'disabled title="Premium Feature"':'';?> <?php echo $props=='bg_image' ? '':'onclick="thwecmfUploadImage(this, \''.esc_attr( $props ).'\')"'?>>
					<input type="button" name="thwecmf_image_upload" value="Remove" class="thwecmf-remove-upload-btn button thwecmf-remove-upload-inactive" data-status="false" <?php echo $props=='bg_image' ? '':'onclick="thwecmfRemoveImgUploaded(this)"'?>>
					<?php
					$this->render_form_fields($this->field_props[$url_type],false,false);
					?>
				</div>
				<div class="thwecmf-upload-notices"></div>
			</td>
		</tr>
		<?php
	}

	private function render_builder_elm_pp_fragment_text($text_flag=true,$prefix=false,$weight=true){
		$cell_props = array('input_width' => '100px');
		$cell_props_combo = array('input_width' => '136px','input_margin' => '0px 6px 0px 0px', 'input_height' => '30px', 'input_b_r' => '4px', 'input_font_size' => '13px');
		$cell_props_combo_L = array('input_width' => '136px', 'input_height' => '30px', 'input_b_r' => '4px', 'input_font_size' => '13px');
		?>   
		<?php 
		if($text_flag){      
			$this->render_form_fields($this->field_props[$prefix.'content'], $this->cell_props_FT);
		}
		?> 
		<tr>
			<td>
				<?php
				$this->render_form_fields($this->field_props[$prefix.'color'], $cell_props_combo, false);
				$this->render_form_fields($this->field_props[$prefix.'font_size'], $cell_props_combo, false);
				?>
			</td>
		</tr>
		<?php       
		$this->render_form_fields($this->field_props[$prefix.'text_align'], $this->cell_props_S);
		?>
		<tr class="thwecmf-input-spacer"><td></td></tr>
		<tr>
			<td>
				<?php
				$this->render_form_fields($this->field_props[$prefix.'line_height'], $cell_props_combo,false);
				if($weight){
					$this->render_form_fields($this->field_props[$prefix.'font_weight'], $cell_props_combo_L,false);
				}
				?>
			</td>
		</tr>
		<?php 
		$this->render_form_fields($this->field_props[$prefix.'font_family'], $this->cell_props_S);
		?>
		<tr class="thwecmf-input-spacer"><td></td></tr>
		<?php
	}

	private function render_builder_elm_pp_fragment_img($content=false, $prefix='img_',$type='image'){
		$atts = array('content' => 'Image', 'padding-top' => '10px');
		if($content){
			$atts['content']=$content;
			$this->render_form_fragment_h_separator($atts);
		}
		$this->render_builder_elm_pp_fragment_img_upload($type,'upload_img_url');       
		$this->render_form_fields($this->field_props['img_size'], $this->cell_props_T);
		$this->render_form_fields($this->field_props['content_align'], $this->cell_props_S);
	}

	private function render_elm_pp_layout_rows(){
		?>
		<div id="thwecmf_field_form_id_row" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none;">
			<table class="thwecmf-edit-form">  
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Row', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<?php       
				$this->render_form_fields($this->field_props['height'], $this->cell_props_T);
				$this->render_form_fields($this->field_props['border_spacing'],$this->cell_props_T);	
				$this->render_form_fields($this->field_props['padding'], $this->cell_props_4T);
				$this->render_form_fields($this->field_props['margin'], $this->cell_props_4T, true ,true);
				?>
			</table>
			<?php
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			?>
		</div>
        <?php   
	}

	private function render_elm_pp_layout_cols(){
		?>
		<div id="thwecmf_field_form_id_col" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none">
			<table class="thwecmf-edit-form">  
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Column', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<?php
				$this->render_form_fields($this->field_props['width'], $this->cell_props_T);
				$this->render_form_fields($this->field_props['padding'], $this->cell_props_T);
				$this->render_form_fields($this->field_props['text_align'], $this->cell_props_T);
				$this->render_form_fields($this->field_props['vertical_align'], $this->cell_props_T);
				?>
			</table>
			<?php       
				$this->render_builder_elm_pp_fragment_border(); 
				$this->render_builder_elm_pp_fragment_bg(); 
			?>
		</div>
        <?php   
	}

	private function render_elm_pp_divider(){
		?>
        <div id="thwecmf_field_form_id_divider" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none;">
			<table class="thwecmf-edit-form">  
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Divider', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php
					$this->render_form_fields($this->field_props['width'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['divider_height'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['divider_color'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['divider_style'], $this->cell_props_S);       
					?>
				</tbody>
			</table>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
  				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Additional', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts, true);
					?>
				</thead>
				<tbody>
					<?php
					$this->render_form_fields($this->field_props['content_align'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['margin'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['padding'], $this->cell_props_T);
					?>
				</tbody>
			</table>
        </div>
        <?php   
	}

	private function render_elm_pp_text(){
		?>
		<div id="thwecmf_field_form_id_text" class="thpl-admin-form-table thwecmf-admin-form-table" style="display: none;">
			<table class="thwecmf-edit-form">
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Content', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<tr>
						<td style="text-align:center;">
							<?php
							echo '<textarea name="i_textarea_content" rows="12" cols="37" style="border-radius:4px;"></textarea>';
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Font', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php
					$this->render_builder_elm_pp_fragment_text(false,false);
					?>
				</tbody>
			</table>
			<?php
			$this->render_builder_elm_pp_fragment_border();
			$this->render_builder_elm_pp_fragment_bg();
			?>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Additional', 'padding-top' => '10px', 'class' => 'thwecmf-seperator-heading');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>   
				<?php       
				$this->render_form_fields($this->field_props['padding'], $this->cell_props_T);
				$this->render_form_fields($this->field_props['margin'], $this->cell_props_T);
				$this->render_form_fields($this->field_props['size'], $this->cell_props_T);
				?>
			</table>
		</div>
        <?php   
	}

	private function render_elm_pp_image(){
		?>
		<div id="thwecmf_field_form_id_image" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none">
  			<table class="thwecmf-edit-form">	
  				<thead class="thwecmf-toggle-section">
  					<?php
  					$atts = array('content' => 'Image', 'padding-top' => '10px', 'class' => 'thwecmf-seperator-heading');
					$this->render_form_fragment_h_separator($atts,true);
  					?>
  				</thead>   
				<?php       
				$this->render_builder_elm_pp_fragment_img(false); 
				?>
       		</table>
       		<?php
       		$this->render_builder_elm_pp_fragment_border('Image Border','img_'); 
       		?>
       		<table class="thwecmf-edit-form thwecmf-toggle-edit-section">
       			<thead class="thwecmf-toggle-section">
					<?php
  					$atts = array('content' => 'Additional', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php
					$this->render_form_fields($this->field_props['img_padding'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['img_margin'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['img_bg_color'], $this->cell_props_T);
					?>
				</tbody>
  			</table>
		</div>
        <?php   
	}

	private function render_elm_pp_billing(){
		?>
        <div id="thwecmf_field_form_id_billing_address" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none">
        	<table class="thwecmf-edit-form">	
				<thead class="thwecmf-toggle-section">
					<?php
  					$atts = array('content' => 'Heading', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php
					$this->render_builder_elm_pp_fragment_text(true,false); 
					?>
				</tbody>
			</table>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Details', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
  					?>
				</thead>
				<tbody>
					<?php
					$this->render_builder_elm_pp_fragment_text(false,'details_'); 
  					?>
				</tbody>
			</table>
			<?php       
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			?>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Additional', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php         
					$this->render_form_fields($this->field_props['padding'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['margin'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['align'], $this->cell_props_S);
					$this->render_form_fields($this->field_props['size'], $this->cell_props_T); 
					?>   
				</tbody>
			</table>
        </div>
        <?php   
	}

	private function render_elm_pp_shipping(){
		?>
        <div id="thwecmf_field_form_id_shipping_address" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none">
        	<table class="thwecmf-edit-form">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Heading', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php       
					$this->render_builder_elm_pp_fragment_text(true,false); 
					?>   
				</tbody>
			</table>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Details', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php       
					$this->render_builder_elm_pp_fragment_text(false,'details_'); 
					?>   
				</tbody>
			</table>
  			<?php       
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			?>
			<table class="thwecmf-edit-form thwecmf-toggle-edit-section">	
				<thead class="thwecmf-toggle-section">
					<?php
					$atts = array('content' => 'Additional', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
				</thead>
				<tbody>
					<?php       
					$this->render_form_fields($this->field_props['padding'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['margin'], $this->cell_props_T);
					$this->render_form_fields($this->field_props['align'], $this->cell_props_S);
					$this->render_form_fields($this->field_props['size'], $this->cell_props_T);
					?>   
				</tbody>
			</table>
        </div>
        <?php   
	}

	private function render_elm_pp_gap(){
		?>
        <div id="thwecmf_field_form_id_gap" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none">
        	<table class="thwecmf-edit-form">
				<thead class="thwecmf-toggle-section">
        			<?php
  					$atts = array('content' => 'Gap', 'padding-top' => '10px');
					$this->render_form_fragment_h_separator($atts,true);
					?>
        		</thead>
        		<tbody>
        			<?php
        			$this->render_form_fields($this->field_props['height'], $this->cell_props_T); 
					?>
        		</tbody>      
			</table>
			<?php       
        	$this->render_builder_elm_pp_fragment_border();
        	$this->render_builder_elm_pp_fragment_bg();
        	?>
        </div>
        <?php   
	}

	private function render_elm_pp_builder(){
		?>
		<div id="thwecmf_field_form_id_t_builder" class=" thpl-admin-form-table thwecmf-admin-form-table" style="display:none">
			<?php
			$this->render_builder_elm_pp_fragment_border(false,'',true);
        	$this->render_builder_elm_pp_fragment_bg();
        	?>
        </div>
        <?php
	}


	private function render_elm_1_column_layout(){
		?>
		<div id="thwecmf_template_layout_1_col" style="display:none;">
			<table class="thwecmf-row thwecmf-block-one-column thwecmf-builder-block" id="one_column" data-elm="row-1-col" data-css-props='{"height":"","p_t":"0px","p_r":"0px","p_b":"0px","p_l":"0px","m_t":"0px","m_r":"auto","m_b":"0px","m_l":"auto","b_t":"0px","b_r":"0px","b_b":"0px","b_l":"0px","border_style":"none","border_color":"","bg_color":""}' data-column-count="1" cellpadding="0" cellspacing="0">
				<tr>
					<td class="thwecmf-column-padding thwecmf-col thwecmf-columns" id="one_column_1" data-css-props='{"width":"100%","p_t":"10px","p_r":"10px","p_b":"10px","p_l":"10px","text_align":"center","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd","bg_color":""}'>
						<span class="thwecmf-builder-add-btn thwecmf-btn-add-element">+ Add Element</span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	private function render_elm_2_column_layout(){
		?>
		<div id="thwecmf_template_layout_2_col" style="display:none;">
			<table class="thwecmf-row thwecmf-block-two-column thwecmf-builder-block" id="two_column" data-elm="row-2-col" cellpadding="0" cellspacing="0" data-css-props='{"height":"","p_t":"","p_r":"","p_b":"","p_l":"","m_t":"0px","m_r":"auto","m_b":"0px","m_l":"auto","b_t":"0px","b_r":"0px","b_b":"0px","b_l":"0px","border_style":"none","border_color":"","bg_color":""}' data-column-count="2"  cellpadding="0" cellspacing="0">
				<tr>
					<td class="thwecmf-column-padding thwecmf-col thwecmf-columns" id="two_column_1"  data-css-props='{"width":"50%","p_t":"10px","p_r":"10px","p_b":"10px","p_l":"10px","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd","bg_color":"","text_align":"center"}'>
						<span class="thwecmf-builder-add-btn thwecmf-btn-add-element">+ Add Element</span>
					</td>
					<td class="thwecmf-column-padding thwecmf-col thwecmf-columns" id="two_column_2" data-css-props='{"width":"50%","p_t":"10px","p_r":"10px","p_b":"10px","p_l":"10px","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd","bg_color":"","text_align":"center"}'>
						<span class="thwecmf-builder-add-btn thwecmf-btn-add-element">+ Add Element</span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	private function render_elm_billing(){
		?>
		<div id="thwecmf_template_elm_billing_address" style="display:none;">
			<span class="thwecmf_before_billing_table"></span>
			<table class="thwecmf-block thwecmf-block-billing thwecmf-builder-block" id="{billing_address}" data-block-name="thwecmf-billing_address" cellpadding="0" cellspacing="0" data-css-props='{"align":"center","color":"#0099ff","text_align":"center","font_size":"18px","details_color":"#444444","details_text_align":"center","details_font_size":"13px","size_width":"100%","size_height":"115px","p_t":"5px","p_r":"0px","p_b":"2px","p_l":"0px","m_t":"0px","m_r":"0px","m_b":"0px","m_l":"0px","b_t":"0px","b_r":"0px","b_b":"0px","b_l":"0px","border_style":"none","border_color":"","bg_color":"","content":""}' data-text-props='{"content":"Billing Details"}'>
      			<tr>
      				<td class="thwecmf-address-alignment" align="center">  	
      					<table class="thwecmf-address-wrapper-table" cellpadding="0" cellspacing="0">
      						<tr>
      							<td  class="thwecmf-billing-padding">
      								<h2 class="thwecmf-billing-header">Billing Details</h2>
			      					<p class="address thwecmf-billing-body">
			      						John Smith<br>
			     						252  Bryan Avenue<br>
			     						Minneapolis, MN 55412<br>
			     						United States (US)
			     						<br>333-6457<br><a href="#">johnsmith@gmail.com</a>
			      					</p>
      							</td>
      						</tr>
      					</table>
      				</td>
      			</tr>
      		</table>
      		<span class="thwecmf_after_billing_table"></span>
		</div>
		<?php
	}

	private function render_elm_shipping(){
		?>
		<div id="thwecmf_template_elm_shipping_address" style="display:none;">
			<span class="thwecmf_before_shipping_table"></span>
			<table class="thwecmf-block thwecmf-block-shipping thwecmf-builder-block" id="{shipping_address}" data-block-name="shipping_address" cellpadding="0" cellspacing="0" data-css-props='{"align":"center","color":"#0099ff","text_align":"center","font_size":"18px","details_color":"#444444","details_text_align":"center","details_font_size":"13px","size_width":"100%","size_height":"115px","p_t":"5px","p_r":"0px","p_b":"2px","p_l":"0px","m_t":"0px","m_r":"0px","m_b":"0px","m_l":"0px","b_t":"0px","b_r":"0px","b_b":"0px","b_l":"0px","border_style":"none","border_color":"","bg_color":"","content":""}' data-text-props='{"content":"Shipping Details"}'>
      			<tr>
      				<td class="thwecmf-address-alignment" align="center">
      					<table class="thwecmf-address-wrapper-table" cellpadding="0" cellspacing="0">
      						<tr>
      							<td class="thwecmf-shipping-padding">      
     	 							<h2 class="thwecmf-shipping-header">Shipping Details</h2>
      								<p class="address thwecmf-shipping-body">
     								John Smith<br>
     								252  Bryan Avenue<br>
     								Minneapolis, MN 55412<br>
     								United States (US)
      								</p>
      							</td>
      						</tr>
      					</table>
      				</td>
      			</tr>
      		</table>
      		<span class="thwecmf_after_shipping_table"></span>
		</div>
		<?php
	}

	private function render_elm_text(){
		?>
		<div id="thwecmf_template_elm_text" style="display:none;">
			<table class="thwecmf-block thwecmf-block-text thwecmf-builder-block" id="{text}" data-block-name="text" data-css-props='{"color":"#636363", "align":"center", "font_size":"13px","bg_color":"", "b_t":"0px", "b_r":"0px", "b_b":"0px", "b_l":"0px", "border_color":"", "border_style":"none", "size_width":"100%", "size_height":"", "m_t":"0px", "m_r":"auto", "m_b":"0px", "m_l":"auto", "p_t":"15px", "p_r":"15px", "p_b":"15px", "p_l":"15px", "text_align":"center","textarea_content":""}' data-text-props='{"textarea_content":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500."}' cellspacing="0" cellpadding="0">
				<tr>
					<td class="thwecmf-block-text-holder">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500.</td>
				</tr>
			</table>
		</div>
		<?php
	}


	private function render_elm_image(){
		?>
		<div id="thwecmf_template_elm_image" style="display:none;"> 
		    <table class=" thwecmf-block thwecmf-block-image thwecmf-builder-block" id="{image}" cellpadding="0" cellspacing="0" data-block-name="image" align="center" data-css-props='{"img_size_width":"50%","img_size_height":"","align":"","upload_img_url":"","content_align":"center"}' data-text-props='{"upload_img_url":""}'>
		    	<tr>
		    		<td class="thwecmf-image-column">
						<p>
							<img src="<?php echo esc_url(TH_WECMF_ASSETS_URL.'images/placeholder.png' ); ?>" alt="Default Image" width="288" height="186" />
						</p>
      				</td>
      			</tr>

      		</table>
		</div>
		<?php
	}

	private function render_elm_divider(){
		?>
		<div id="thwecmf_template_elm_divider" style="display:none;">
      		<table cellspacing="0" cellpadding="0" class="thwecmf-block thwecmf-builder-block thwecmf-block-divider" id="{divider}" data-block-name="divider" data-css-props='{"width":"70%","divider_height":"2px","divider_color":"#808080","divider_style":"solid","m_t":"0px","m_r":"auto","m_b":"0px","m_l":"auto","p_t":"20px","p_r":"0px","p_b":"20px","p_l":"0px","content_align":"center"}'>
      			<tr><td><hr></td></tr>
      		</table>
		</div>
		<?php
	}

	private function render_elm_gap(){
		?>
		<div id="thwecmf_template_elm_gap" style="display:none;">
      		<p class="thwecmf-block thwecmf-block-gap thwecmf-builder-block" id="{gap}" data-block-name="gap" data-css-props='{"height":"48px","b_t":"0px","b_r":"0px","b_b":"0px","b_l":"0px","border_style":"none","border_color":"","bg_color":""}'></p>
		</div>
		<?php
	}

	private function render_hook_email_header(){
		?>
		<div id="thwecmf_template_hook_email_header" style="display:none;">
			<p class="thwecmf-hook-code" id="{email_header}">{email_header_hook}</p>
		</div>
		<?php
	}

	private function render_hook_email_order_details(){
		?>
		<div id="thwecmf_template_hook_order_details" style="display:none;">
			<p class="thwecmf-hook-code" id="{email_order_details}">{email_order_details_hook}</p>
		</div>
		<?php		
	}

	private function render_hook_before_order_table(){
		?>
		<div id="thwecmf_template_hook_before_order_table" style="display:none;">
			<p class="thwecmf-hook-code" id="{before_order_table}">{before_order_table_hook}</p>
		</div>
		<?php		
	}

	private function render_hook_after_order_table(){
		?>
		<div id="thwecmf_template_hook_after_order_table" style="display:none;">
			<p class="thwecmf-hook-code" id="{after_order_table}">{after_order_table_hook}</p>
		</div>
		<?php		
	}

	private function render_hook_order_meta(){
		?>
		<div id="thwecmf_template_hook_order_meta" style="display:none;">
			<p class="thwecmf-hook-code" id="{order_meta}">{order_meta_hook}</p>
		</div>
		<?php		
	}

	private function render_hook_customer_address(){
		?>
		<div id="thwecmf_template_hook_customer_address" style="display:none;">
			<p class="thwecmf-hook-code" id="{customer_details}">{customer_details_hook}</p>
		</div>
		<?php		
	}

	private function render_hook_email_footer(){
		?>
		<div id="thwecmf_template_hook_email_footer" style="display:none;">
			<p class="thwecmf-hook-code" id="{email_footer}">{email_footer_hook}</p>
		</div>
		<?php		
	}

	private function render_track_row_content(){
		?>
		<div id="thwecmf_tracking_panel_row_html" style="display:none;">	
			<div class="thwecmf-layout-lis-item thwecmf-sortable-row-handle">
				<span class="thwecmf-row-name">Row</span>
				<div class="thwecmf-block-settings">
					<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, {bl_id}, '{bl_name}')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' );?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="{bl_name}">
					<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url(TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
				</div>
			</div>
		</div>
		<?php
	}

	private function render_track_col_content(){
		?>
		<div id="thwecmf_tracking_panel_col_html" style="display:none;">	
			<div class="thwecmf-layout-lis-item sortable-col-handle">
				<span class="thwecmf-column-name" title="Click here to toggle">Column</span>
				<div class="thwecmf-block-settings">
					<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, {bl_id}, '{bl_name}')" src="<?php echo esc_url(TH_WECMF_ASSETS_URL.'images/pencil.png');?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="{bl_name}">
					<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url(TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
				</div>
			</div>
		</div>
		<?php
	}

	private function render_track_elm_content(){
		?>
		<div id="thwecmf_tracking_panel_elm_html" style="display:none;">	
			<div class="thwecmf-layout-lis-item sortable-elm-handle">
				<span class="thwecmf-element-name" title="Click here to toggle">{name}</span>
				<div class="thwecmf-block-settings">
					<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, {bl_id}, '{bl_name}')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' );?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="{bl_attr_name}">
					<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
				</div>
			</div>
		</div>
		<?php
	}
	private function render_track_hook_content(){
		?>
		<div id="thwecmf_tracking_panel_hook_html" style="display:none;">	
			<div class="thwecmf-layout-lis-item sortable-elm-handle">
				<span class="thwecmf-hook-name" title="Click here to toggle">{name}</span>
				<div class="thwecmf-block-settings">
					<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
				</div>
			</div>
		</div>
		<?php
	}

	private function render_pp_confirmation_alerts(){
		?>
		<div id="thwecmf_confirmation_alerts" style="display: none;">
			<form name="thwecmf_confirmation_alert_form" id="thwecmf_confirmation_alert_form">
				<input type="hidden" name="i_thwecmf_column_reference" class="thwecmf-column-reference" value="">
				<input type="hidden" name="i_thwecmf_flag_reference" class="thwecmf-flag-reference" value="">
				<input type="hidden" name="i_thwecmf_column_id" class="thwecmf-column-id-reference" value="">
				<div class="thwecmf-confirmation-message-wrapper">
				<div class="thwecmf-messages"></div>
			</div>
			</form>
		</div>
		<?php
	}

	private function render_pp_confirmation_msg_line(){
		?>
		<div id="thwecmf_clear_builder_confirm" style="display: none;">
			All the unsaved changes will be lost. <br>Are you sure ?
		</div>
		<?php
	}


	private function panel_layout_elements_list(){
		?>
		<table class="thwecmf-tbuilder-elm-grid-layout-element">
			<tbody>
				<!-- Layouts  -->
				<tr>
					<td class="column-layouts">
						<div class="grid-category category-collapse">
							<p class="grid-title" onclick="thwecmfCollapseCategory(this)">Layouts<span class="dashicons dashicons-arrow-down-alt2 thwecmf-direction-arrow"></span>
							<div class="grid-content">
								<div class="elm-col-premium">
									<div id="thwecmf-one-column" class="thwecmf-tbuilder-elm thwecmf_column_layout thwecmf-premium-disabled-feature" data-block-name="one_column">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/one_column.png') ;?>" alt="One column">
										</div>
										<div class="thwecmf-elm-icon-text">1 Column</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-layout">Upgrade</span>	
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf-two-column" class="thwecmf-tbuilder-elm column_layout thwecmf-premium-disabled-feature" data-block-name="two_column">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/two_column.png') ;?>" alt="Two column">
										</div>
										<div class="thwecmf-elm-icon-text">
											<p>2 Column</p>
										</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-layout">Upgrade</span>	
								</div>

								<div class="elm-col-premium">
									<div id="thwecmf-three-column" class="thwecmf-tbuilder-elm column_layout thwecmf-premium-disabled-feature" data-block-name="three_column">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/three_column.png' ); ?>" alt="Three column">
										</div>
										<div class="thwecmf-elm-icon-text">
											<p>3 Column</p>
										</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-layout">Upgrade</span>	
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf-four-column" class="thwecmf-tbuilder-elm column_layout thwecmf-premium-disabled-feature" data-block-name="four_column">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/four_column.png'); ?>" alt="Four column">
										</div>
										<div class="thwecmf-elm-icon-text">
											<p>4 Column</p>
										</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-layout">Upgrade</span>	
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr class="section-gap"><td></td></tr>
				<tr>
					<td class="column-basic-elements">
						<div class="grid-category">
							<p class="grid-title" onclick="thwecmfCollapseCategory(this)">Basic Elements<span class="dashicons dashicons-arrow-down-alt2 thwecmf-direction-arrow"></span></p>
							<div class="grid-content">
								<div class="thwecmf-elm-col">
									<div id="thwecmf_text" class="thwecmf-tbuilder-elm block_element" data-block-name="text">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/text.svg'); ?>" alt="Text">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Text</div>
									</div>
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_image" class="thwecmf-tbuilder-elm block_element" data-block-name="image">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/image.svg'); ?>" alt="Image">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Image</div>
									</div>
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_divider" class="thwecmf-tbuilder-elm block_element" data-block-name="divider">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/divider.svg'); ?>" alt="Divider">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Divider</div>
									</div>
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_gap" class="thwecmf-tbuilder-elm block_element" data-block-name="gap">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/gap.svg' ); ?>" alt="Gap">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Gap</div>
									</div>
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_social" class="thwecmf-tbuilder-elm block_element thwecmf-premium-disabled-feature" data-block-name="social">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/social.svg' );?>" alt="Social icons">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Social</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>	
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_button" class="thwecmf-tbuilder-elm block_element thwecmf-premium-disabled-feature" data-block-name="button">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/button.svg') ;?>" alt="Button">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Button</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>	
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_gif" class="thwecmf-tbuilder-elm block_element thwecmf-premium-disabled-feature" data-block-name="gif">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url(TH_WECMF_ASSETS_URL.'images/gif.svg') ;?>" alt="Gif">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Gif</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>	
								</div>
								
							</div>
						</div>
					</td>
				</tr>
				<tr class="section-gap"><td></td></tr>
				<tr>
					<td class="woocommerce-elements">
						<div class="grid-category category-collapse">
							<p class="grid-title" onclick="thwecmfCollapseCategory(this)">WooCommerce Elements<span class="dashicons dashicons-arrow-down-alt2 thwecmf-direction-arrow"></span></p>
							<div class="grid-content">
								<div class="elm-col-premium">
									<div id="thwecmf_header_details" class="thwecmf-tbuilder-elm block_element thwecmf-premium-disabled-feature" data-block-name="header_details">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/header.svg' );?>" alt="Header">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Header</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_customer_address" class="thwecmf-tbuilder-elm block_element thwecmf-premium-disabled-feature" data-block-name="customer_address">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/customer-details.svg' );?>" alt="Customer details">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Customer</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>	
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_order_details" class="thwecmf-tbuilder-elm block_element thwecmf-premium-disabled-feature" data-block-name="order_details">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/order.svg'); ?>" alt="Order table">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Order</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_billing_address" class="thwecmf-tbuilder-elm block_element" data-block-name="billing_address">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url( TH_WECMF_ASSETS_URL.'images/billing-details.svg' );?>" alt="Blling details">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Billing</div>
									</div>
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_shipping_address" class="thwecmf-tbuilder-elm block_element" data-block-name="shipping_address">
										<div class="thwecmf-elm-icon">
											<img src=" <?php echo esc_url(TH_WECMF_ASSETS_URL.'images/shipping-details.svg' );?>" alt="Shipping details">
										</div>
										<div class="thwecmf-elm-icon-text"><br>Shipping</div>
									</div>
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_woocommerce_email_footer" class="thwecmf-tbuilder-elm block_element hook_element thwecmf-premium-disabled-feature" data-block-name="downloadable_product">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/downloadable-product.svg' );?>" alt="Downloadable product" >
										</div>
										<div class="thwecmf-elm-icon-text">Downloadable Product</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr class="section-gap"><td></td></tr>
				<tr>
					<td class="woocommerce-hooks">
						<div class="grid-category category-collapse">
							<p class="grid-title" onclick="thwecmfCollapseCategory(this)">WooCommerce Hooks<span class="dashicons dashicons-arrow-down-alt2 thwecmf-direction-arrow"></span></p>
							<div class="grid-content">
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_header" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="email_header">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/email-header-hook.svg' );?>" alt="Email header hook" >
										</div>
										<div class="thwecmf-elm-icon-text"><br>Email Header</div>
									</div>			
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_order_details" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="email_order_details">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/email-order-details-hook.svg') ;?>" alt="Order details hook" >
										</div>
										<div class="thwecmf-elm-icon-text">Email Order Details</div>
									</div>	
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_before_order_table" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="before_order_table">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/before-order-table-hook.svg') ;?>" alt="Before Order table hook" >
										</div>
										<div class="thwecmf-elm-icon-text">Before <br>Order Table</div>
									</div>		
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_after_order_table" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="after_order_table">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/after-order-table-hook.svg' );?>" alt="After order table hook" >
										</div>
										<div class="thwecmf-elm-icon-text">After <br>Order Table</div>
									</div>		
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_order_meta" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="order_meta">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/order-meta.svg' );?>" alt="Order meta hook" >
										</div>
										<div class="thwecmf-elm-icon-text"><br>Order Meta</div>
									</div>
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_customer_address" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="customer_details">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/customer-details-hook.svg' );?>" alt="Customer details" >
										</div>
										<div class="thwecmf-elm-icon-text"><br>Customer Details</div>
									</div>		
								</div>
								<div class="thwecmf-elm-col">
									<div id="thwecmf_woocommerce_email_footer" class="thwecmf-tbuilder-elm block_element hook_element" data-block-name="email_footer">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/email-footer-hook.svg' );?>" alt="Email footer hook" >
										</div>
										<div class="thwecmf-elm-icon-text"><br>Email Footer</div>
									</div>		
								</div>
								<div class="elm-col-premium">
									<div id="thwecmf_woocommerce_email_custom_hook" class="thwecmf-tbuilder-elm block_element hook_element thwecmf-premium-disabled-feature" data-block-name="custom_hook">
										<div class="thwecmf-elm-icon">
											<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/custom-hook.svg' );?>" alt="Custom Hook" >
										</div>
										<div class="thwecmf-elm-icon-text"><br>Custom Hook</div>
									</div>
									<span class="th-premium-feature-msg th-premium-feature-msg-elements">Upgrade</span>		
								</div>

							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	private function output_builder_sidebar_header(){
		?>
		<div class="thwecmf-sidebar-header">
			<div class="thwecmf-sidebar-header-title">
				<div class="thwecmf-nav-previous thwecmf-icon-wrapper thwecmf-configure-page-index">
					<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/back-button.svg' );?>" onclick="thwecmfSidebarBackNavigation(this)" data-nav="layout" alt="Back">
				</div>
				<div class="thwecmf-header-plguin-name">Customizer</div>
				<div class="thwecmf-template-wrapper-settings thwecmf-icon-wrapper">
					<img src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/settings.svg' );?>" onclick="thwecmfEditBuilderBlocks(this, 't_builder', 't_builder')" alt="Template Builder Settings">
				</div>
				<div class="thwecmf-template-nav-preview thwecmf-icon-wrapper"></div>
			</div>
		</div>
		<?php
	}

	private function output_builder_sidebar_body($template_json){
		?>
		<div class="thwecmf-sidebar-body-wrapper">
			<div id="thwecmf-sidebar-configure" class="thwecmf-settings-panel-tabs thwecmf-active-tab">
				<?php $this->render_template_builder_panel_configure($template_json); ?>
			</div>
			<div id="thwecmf-sidebar-settings" class="thwecmf-settings-panel-tabs thwecmf-inactive-tab"></div>
		</div>
		<?php
	}	

	private function render_template_builder_panel_configure($template_json){
		$toggle_layers = $template_json ? 'thwecmf-layers-toggle' : '';
		?>
		<div class="thwecmf-layers-outer-wrapper">
			<div class="thwecmf-layers-inner-wrapper">
				<table class="thwecmf-sidebar-config-elm-layers">
					<thead>
						<tr>
							<td class="thwecmf-configure-title"><b>Configure your email template</b></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<p class="thwecmf-empty-layer-msg <?php echo esc_attr( $toggle_layers ); ?>">Click on <strong>Add Row</strong> button to start building your email template.</p>
								<div class="thwecmf-builder-elm-layers">
									<?php 
									if($template_json){
										$this->thwecmf_json_tree_creator($template_json); 
									}
									?>
								</div>
							</td>
						</tr>	
						<tr>
							<td>
								<button type="button" onclick="thwecmfClickAddRow(this)" class="thwecmf-sidebar-add-row">Click to add a row</button>						
							</td>
						</tr>												
					</tbody>
				</table>
			</div>
		</div>
		<table class="thwecmf-tbuilder-configure-actions">
			<tfoot>
				<tr>
					<td class="thwecmf-tbuilder-footer">
						<button type="button" class="button-primary thwecmf-btn-clear" onclick="thwecmfClearTemplateBuilder(this)">Clear</button>
						<button type="button" class="button-primary thwecmf-btn-save" name="save_template" value="Save" onclick="thwecmfSaveTemplate(this)">Save</button>
					</td>
				</tr>
			</tfoot>
		</table>
		<?php
	}

	private function thwecmf_json_tree_creator($template_json){
		$json_row = json_decode($template_json);
		$row_count = $json_row->row;
		if($json_row->row){
			foreach ($json_row->row as $row_child) {
				?>
				<div id="<?php echo esc_attr( $row_child->data_id ); ?>" class="thwecmf-rows thwecmf-panel-builder-block" data-columns="<?php echo esc_attr( $row_child->data_count );?>">
					<div class="thwecmf-layout-lis-item thwecmf-sortable-row-handle">
						<span class="thwecmf-row-name">Row</span>
						<div class="thwecmf-block-settings">
							<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, <?php echo esc_attr( $row_child->data_id ); ?>, '<?php echo esc_attr( $row_child->data_name ); ?>')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' );?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="<?php echo esc_attr( $row_child->data_name ); ?>">
							<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
						</div>
					</div>
					<div class="thwecmf-column-set">
					<?php 
						if(count($row_child->child) > 0 && $row_child->child[0]->data_type =='column'){
							foreach ($row_child->child as $child_col) {
								?>
								<div id="<?php echo esc_attr( $child_col->data_id ); ?>" class="thwecmf-columns thwecmf-panel-builder-block" data-parent="<?php echo esc_attr( $row_child->data_id ); ?>">	
									<div class="thwecmf-layout-lis-item sortable-col-handle">
										<span class="thwecmf-column-name" title="Click here to toggle">Column</span>
										<div class="thwecmf-block-settings">
											<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, <?php echo esc_attr( $child_col->data_id ); ?>, '<?php echo esc_attr( $child_col->data_name ); ?>')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' ); ?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="<?php echo esc_attr( $child_col->data_name ); ?>">
											<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' ); ?>" alt="Delete">
										</div>
									</div>
									<div class="thwecmf-element-set">
										<div class="thwecmf-hidden-sortable thwecmf-elements"></div>
										<?php
										if(count($child_col->child) > 0){
											foreach ($child_col->child as $child_elm) {
												if($child_elm->data_type == 'element'){
												?>
													<div id="<?php echo esc_attr( $child_elm->data_id ); ?>" class="thwecmf-elements thwecmf-panel-builder-block">	
														<div class="thwecmf-layout-lis-item sortable-elm-handle">
															<span class="thwecmf-element-name" title="Click here to toggle"><?php echo esc_attr( $child_elm->child ); ?></span>
															<div class="thwecmf-block-settings">
																<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, <?php echo esc_attr( $child_elm->data_id ); ?>, '<?php echo esc_attr( $child_elm->data_name ); ?>')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' ); ?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="<?php echo esc_attr( $child_elm->data_name ); ?>">
																<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
															</div>
														</div>
													</div>
												<?php
												}else if($child_elm->data_type == 'hook'){
												?>
													<div id="<?php echo esc_attr( $child_elm->data_id );?>" class="thwecmf-hooks thwecmf-panel-builder-block">
														<div class="thwecmf-layout-lis-item sortable-elm-handle">
															<span class="thwecmf-hook-name" title="Click here to toggle"><?php echo esc_attr( $child_elm->child ); ?></span>
															<div class="thwecmf-block-settings">
																<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
															</div>
														</div>
													</div>
												<?php
												}
											}
										}
										?>
										<div class="thwecmf-btn-add-element panel-add-btn panel-add-element"><a href="#">Add Element</a></div>
									</div>
								</div>
								<?php
							}
						}
						?>
						<div class="btn-add-column-wrap">
							<div class="panel-add-btn btn-add-column thwecmf-premium-disabled-feature" data-parent="<?php echo esc_attr( $row_child->data_id ); ?>">
								<a>Add Column</a>
							</div>
							<span class="th-premium-feature-msg th-premium-feature-msg-add-column">Upgrade</span>
						</div>
					</div>
				</div>
			<?php
			}
		}
	}

	private function layout_layers_from_json($row_obj){
		?>
		<div id="<?php echo esc_attr( $row_obj[0]->data_id ); ?>" class="thwecmf-rows thwecmf-panel-builder-block" data-columns="<?php echo esc_attr( $row_obj[0]->data_count );?>">	
			<div class="thwecmf-layout-lis-item thwecmf-sortable-row-handle">
				<span class="thwecmf-row-name">Row</span>
				<div class="thwecmf-block-settings">
					<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, <?php echo esc_attr( $row_obj[0]->data_id ); ?>, '<?php echo esc_attr( $row_obj[0]->data_name ); ?>')" src="<?php echo esc_url ( TH_WECMF_ASSETS_URL.'images/pencil.png' );?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="<?php echo esc_attr( $row_obj[0]->data_name ); ?>">
					<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
				</div>
			</div>
			<div class="thwecmf-column-set">
				<?php
				if(count($row_obj[0]->child) > 0){
					foreach ($row_obj[0]->child as $col_key) {
						if($col_key->data_type == 'column'){
						?>
							<div id="<?php echo esc_attr( $col_key->data_id ); ?>" class="thwecmf-columns thwecmf-panel-builder-block" data-parent="<?php echo esc_attr( $col_key->data_id ); ?>">	
								<div class="thwecmf-layout-lis-item sortable-col-handle">
									<span class="thwecmf-column-name" title="Click here to toggle">Column</span>
									<div class="thwecmf-block-settings">
										<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, <?php echo esc_attr( $col_key->data_id ); ?>, '<?php echo esc_attr( $col_key->data_name ); ?>')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' );?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="<?php echo esc_attr( $col_key->data_name ); ?>">
										<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
									</div>
								</div>
								<div class="thwecmf-element-set">
									<div class="thwecmf-hidden-sortable thwecmf-elements"></div>
									<?php
									if(count($col_key->child) > 0){
										foreach ($col_key->child as $elm_key) {
											if(isset($elm_key->row) && count($elm_key->row[0]->child) > 0){
												$this->layout_layers_from_json($elm_key->row);
											}else if($elm_key->data_type == 'element'){
												
											?>
												<div id="<?php echo esc_attr( $elm_key->data_id ); ?>" class="thwecmf-elements thwecmf-panel-builder-block">	
													<div class="thwecmf-layout-lis-item sortable-elm-handle">
														<span class="thwecmf-element-name" title="Click here to toggle"><?php echo esc_attr( $elm_key->data_name ); ?></span>
														<div class="thwecmf-block-settings">
															<img class="thwecmf-template-action-edit" onclick="thwecmfEditBuilderBlocks(this, <?php echo esc_attr( $elm_key->data_id ); ?>, '<?php echo esc_attr( $elm_key->data_name ); ?>')" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/pencil.png' );?>" style="margin-right: 1px;" alt="Edit" data-icon-attr="<?php echo esc_attr( $elm_key->data_name); ?>">
															<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
														</div>
													</div>
												</div>
												<?php
											}else{
												?>
												<div id="<?php echo esc_attr( $elm_key->data_id ); ?>" class="thwecmf-hooks thwecmf-panel-builder-block">
													<div class="thwecmf-layout-lis-item sortable-elm-handle">
														<span class="thwecmf-hook-name" title="Click here to toggle"><?php echo esc_attr( $elm_key->child ); ?></span>
														<div class="thwecmf-block-settings">
															<img class="thwecmf-template-action-delete" onclick="thwecmfDeleteBuilderBlocks(this)" src="<?php echo esc_url( TH_WECMF_ASSETS_URL.'images/delete-button.png' );?>" alt="Delete">
														</div>
													</div>
												</div>
												<?php
											}
										}
									}
									?>
									<div class="thwecmf-btn-add-element panel-add-btn panel-add-element">
										<a href="#">Add Element</a>
									</div>
								</div>
							</div>
						<?php
						}
					}
				}
				?>
				<div class="btn-add-column-wrap">
					<div class="panel-add-btn btn-add-column thwecmf-premium-disabled-feature" data-parent="<?php echo esc_attr( $row_obj[0]->data_id ); ?>">
						<a>Add Column</a>
					</div>
					<span class="th-premium-feature-msg th-premium-feature-msg-add-column">Upgrade</span>
				</div>
			</div>
		</div>
		<?php
	}

	private function render_template_blocks_json($template_json){
		$this->template_json_css = '';
		$builder_data = json_decode($template_json);
		$this->template_json_css .= $this->prepare_css_from_json($builder_data);
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="600" id="tbf_t_builder" class="thwecmf-dropable sortable thwecmf-main-builder" data-global-id="<?php echo esc_attr( $builder_data->track_save ); ?>" data-track-save="<?php echo esc_attr( $builder_data->track_save ); ?>" data-css-change="true" data-css-props='<?php echo esc_attr( $builder_data->data_css );?>'>
			<tr>
				<td class="thwecmf-builder-column">
					<?php 
					$builder_row = $builder_data->row;
					if($builder_data->row){
						foreach ($builder_data->row as $row_child) {
							$this->template_json_css .= $this->prepare_css_from_json($row_child);
							?>
							<table class="thwecmf-row thwecmf-block-<?php echo esc_attr(str_replace('_', '-', $row_child->data_name ) ); ?> thwecmf-builder-block" id="tbf_<?php echo esc_attr( $row_child->data_id ); ?>" data-css-props='<?php echo esc_attr( $row_child->data_css );?>' data-name="<?php echo esc_attr( $row_child->data_name );?>" data-column-count="<?php echo esc_attr( $row_child->data_count ); ?>"  cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<?php
										if(count($row_child->child) > 0 && $row_child->child[0]->data_type =='column'){
											foreach ($row_child->child as $child_col) {
												$this->template_json_css .= $this->prepare_css_from_json($child_col);
											?>
												<td class="thwecmf-column-padding thwecmf-col thwecmf-columns" id="tbf_<?php echo esc_attr( $child_col->data_id );?>" data-css-props='<?php echo esc_attr( $child_col->data_css ); ?>' data-name="<?php echo esc_attr( $child_col->data_name );?>">
													<?php if(count($child_col->child) > 0){
														foreach ($child_col->child as $child_elm) {
															if(isset($child_elm->row) && count($child_elm->row[0]->child) > 0){
																$this->render_template_blocks_layout_json($child_elm->row);
															}else if($child_elm->data_type == 'element'){
																$this->template_json_css .= $this->prepare_css_from_json($child_elm);
																$this->render_builder_element_blocks($child_elm, $child_elm->data_name);
															}else{
																$this->render_builder_element_blocks($child_elm, $child_elm->data_name);
															}	
														}
													}else{
														echo '<span class="thwecmf-builder-add-btn thwecmf-btn-add-element">+ Add Element</span>';
													}?>
												</td>
											<?php
											}
										}
										?>
									</tr>
								</tbody>
							</table>
							<?php
						}
					}
					?>
				</td>
			</tr>
		</table>
	<?php
	}


	private function render_template_blocks_layout_json($row_obj){
		?>
		<table class="thwecmf-row thwecmf-block-<?php echo str_replace('_', '-', esc_attr( $row_obj[0]->data_name ) ); ?> thwecmf-builder-block" id="tbf_<?php echo esc_attr( $row_obj[0]->data_id ); ?>" data-css-props='<?php echo esc_attr( $row_obj[0]->data_css );?>' data-name="<?php echo esc_attr( $row_obj[0]->data_name );?>" data-column-count="<?php echo esc_attr( $row_obj[0]->data_count ); ?>"  cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<?php
					$this->template_json_css .= $this->prepare_css_from_json($row_obj[0]);
					if(count($row_obj[0]->child) > 0){
						foreach ($row_obj[0]->child as $col_key) {
							$this->template_json_css .= $this->prepare_css_from_json($col_key);
							?>
							<td class="thwecmf-column-padding thwecmf-col thwecmf-columns" id="tbf_<?php echo esc_attr( $col_key->data_id );?>" data-css-props='<?php echo esc_attr( $col_key->data_css ); ?>' data-name="<?php echo esc_attr( $col_key->data_name );?>">
								<?php
								if(count($col_key->child) > 0){
									foreach ($col_key->child as $elm_key) {
										if(isset($elm_key->row) && count($elm_key->row[0]->child) > 0){
											$this->render_template_blocks_layout_json($elm_key->row);
										}else if($elm_key->data_type == 'element'){
											$this->template_json_css .= $this->prepare_css_from_json($elm_key);
											$this->render_builder_element_blocks($elm_key, $elm_key->data_name);
										}else{
											$this->render_builder_element_blocks($elm_key, $elm_key->data_name);
										}
									}
								}else{
									echo '<span class="thwecmf-builder-add-btn thwecmf-btn-add-element">+ Add Element</span>';
								}?>	
							</td>
						<?php
						}
					}
					?>
				</tr>
			</tbody>
		</table>
		<?php
	}


	private function render_builder_element_blocks($elm, $elm_name){
		$content = '';
		switch (strtolower($elm_name)) {
			case 'text':
				$content = $this->render_builder_element_block_details_text($elm, $elm_name);
				break;
			
			case 'image':
				$content = $this->render_builder_element_block_details_image($elm, $elm_name);
				break;

			case 'divider':
				$content = $this->render_builder_element_block_details_divider($elm, $elm_name);
				break;

			case 'gap':
				$content = $this->render_builder_element_block_details_gap($elm, $elm_name);
				break;

			case 'billing_address':
				$content = $this->render_builder_element_block_details_billing($elm, $elm_name);
				break;

			case 'shipping_address':
				$content = $this->render_builder_element_block_details_shipping($elm, $elm_name);
				break;

			case 'order_details':
				$content = $this->render_builder_element_block_details_order($elm, $elm_name);
				break;

			case 'email_header_hook':
				$content = $this->render_builder_element_block_details_email_header_hook($elm, $elm_name);
				break;

			case 'email_order_details_hook':
				$content = $this->render_builder_element_block_details_email_order_details_hook($elm, $elm_name);
				break;

			case 'before_order_table_hook':
				$content = $this->render_builder_element_block_details_before_order_table_hook($elm, $elm_name);
				break;

			case 'after_order_table_hook':
				$content = $this->render_builder_element_block_details_after_order_table_hook($elm, $elm_name);
				break;

			case 'order_meta_hook':
				$content = $this->render_builder_element_block_details_order_meta_hook($elm, $elm_name);
				break;

			case 'customer_details_hook':
				$content = $this->render_builder_element_block_details_customer_address_hook($elm, $elm_name);
				break;

			case 'email_footer_hook':
				$content = $this->render_builder_element_block_details_email_footer_hook($elm, $elm_name);
				break;

			default:$content ='';
				break;
		}
		echo wp_kses( trim( $content ),wp_kses_allowed_html('post') );
	}

	private function wrapper_textarea_content($content){
		$data = '';
		$content_arr = preg_split("/(\r\n|\n|\r)/",$content);
		foreach ($content_arr as $key => $value) {
			$data .= '<div class="wecmf-txt-wrap">'.wp_kses( trim( $value ),wp_kses_allowed_html('post') ).'<br></div>';
		}
		return $data;
	}

	private function prepare_css_from_json($block_obj){
		$block_css = '';
		$type = isset($block_obj->data_type) ? $block_obj->data_type : false ;
		$json_css = isset($block_obj->data_css) ? json_decode($block_obj->data_css,true) : false;
		$id = '#tbf_'.$block_obj->data_id;
		if($json_css && $type){
			if($type == 'builder'){
				$block_css.= $id.' .thwecmf-builder-column{';
				foreach($json_css as $key => $value){
					if(isset($this->css_props[$key])){
						$property = $this->css_props[$key];
						if( empty($value) && array_key_exists($property, $this->default_css)){
							$value = $this->default_css[$property]; 
						}
						$block_css.= $property.':'.$value.';';
					}
				}
				$block_css.= '}';
			}else{
				$name = isset($block_obj->data_name) ? $block_obj->data_name : '';
				if($json_css){
					if(array_key_exists($type, $this->json_css_class)){
						$css_name = $this->json_css_class[$type];
						$block_css.= $id.'.'.$css_name.'{';
						foreach($json_css as $key => $value){
							if(isset($this->css_props[$key])){
								$property = $this->css_props[$key];
								if( empty($value) && array_key_exists($property, $this->default_css)){
									$value = $this->default_css[$property]; 
								}
								$block_css.= $property.':'.$value.';';
							}
						}
						$block_css.= '}';
					}else{
						if(isset($this->css_elm_props_map[$name])){
							foreach ($this->css_elm_props_map[$name] as $child_class => $index_value) {
								$block_css.= $id.$child_class.'{';
								foreach ($index_value as $css_attr) {
									$property = '';
									$css_value = ''; 
									if( isset($json_css[$css_attr]) && isset($this->css_props[$css_attr]) ){
										$property = $this->css_props[$css_attr];
										$css_value = $json_css[$css_attr];
										if( empty($css_value) && array_key_exists($property, $this->default_css)){
											$css_value = $this->default_css[$property]; 
										}
										$block_css.= $this->css_props[$css_attr].':'.$css_value.';';
									}
								}
								$block_css.= '}';
							}
						}
					}
				}
			}
		}
		return $block_css;
	}

	private function clean_textarea_contents_for_html($obj){
		$formatted_text = '';
		if($obj){
			$formatted_text = WECMF_Email_Customizer_Utils::thwecmf_is_json_decode($obj);
			if($formatted_text && isset($formatted_text->textarea_content)){
				$f_text = $formatted_text->textarea_content;
				$f_text = str_replace("'","&#39;", $f_text);
				$f_text = str_replace('"',"&quot;", $f_text);
				$formatted_text->textarea_content = $f_text;
			}
			$formatted_text = json_encode($formatted_text);
		}
		return $formatted_text;
	}

	private function render_builder_element_block_details_text($elm, $elm_name){
		$content = '';
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_css = isset($elm->data_css) ? $elm->data_css : "";
		$data_text_props = isset($elm->data_text) ? $elm->data_text: "";
		$json_txt_props = $data_text_props ? WECMF_Email_Customizer_Utils::thwecmf_is_json_decode($data_text_props) : false; 
		if(json_last_error() !== 0 && !$json_txt_props){
			$data_text_props = str_replace("\n","\\n",$data_text_props);
			$json_text = json_decode($data_text_props);
			$content .= $json_text->textarea_content;
		}else{
			$content .= isset($json_txt_props->textarea_content) ? $json_txt_props->textarea_content : "";
		}
		$content = $content ? $this->wrapper_textarea_content($content) : "";
		?>
		<table class="thwecmf-block thwecmf-block-text thwecmf-builder-block" id="tbf_<?php echo esc_attr($data_id); ?>" data-block-name="<?php echo esc_attr( $elm_name );?>" data-css-props='<?php echo esc_attr( $data_css );?>' data-text-props='<?php echo htmlentities($data_text_props, ENT_QUOTES);?>' cellspacing="0" cellpadding="0">
			<tr>
				<td class="thwecmf-block-text-holder">
					<?php echo wp_kses( nl2br( $content ),wp_kses_allowed_html('post') ) ?>
				</td>
			</tr>
		</table>
		<?php
	}

	private function render_builder_element_block_details_image($elm, $elm_name){
		if(isset($elm->data_text)){
			$content = WECMF_Email_Customizer_Utils::thwecmf_is_json_decode($elm->data_text);
			if($content){
				$image = isset($content->upload_img_url) && $content->upload_img_url !=='' ? $content->upload_img_url : esc_url( TH_WECMF_ASSETS_URL.'/images/placeholder.png' );
			}
		}
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_css = isset($elm->data_css) ? $elm->data_css : "";
		$misc_css = isset($elm->data_misc) ? $elm->data_misc : false;
		$css_decode = !empty($elm->data_css) ? json_decode($elm->data_css) : false;
		$content_align = $css_decode ? $css_decode->content_align : "";
		$misc_obj = $misc_css ? WECMF_Email_Customizer_Utils::thwecmf_is_json_decode($misc_css) : false;
		$compatible_w = $misc_obj && isset($misc_obj->c_width)? $misc_obj->c_width : "";
		$compatible_h = $misc_obj && isset($misc_obj->c_height) ? $misc_obj->c_height : "";
		$data_text_props = isset($elm->data_text) ? $elm->data_text: "";

		?>
		<table class="thwecmf-block thwecmf-block-image thwecmf-builder-block" id="tbf_<?php echo esc_attr( $data_id ); ?>" data-block-name="<?php echo esc_attr( $elm_name );?>" data-css-props='<?php echo esc_attr( $data_css ); ?>' data-text-props='<?php echo esc_attr( $data_text_props ); ?>' cellpadding="0" cellspacing="0" align="center" data-misc='<?php echo esc_attr( $misc_css );?>'>
		    <tr>
		    	<td class="thwecmf-image-column">
					<p>
						<img src="<?php echo esc_url( $image ); ?>" alt="Default Image" width="<?php echo esc_attr( $compatible_w ); ?>" height="<?php echo esc_attr( $compatible_h ); ?>"  width="288" height="186"/>
					</p>
      			</td>
      		</tr>
      	</table>
		<?php
	}

	private function render_builder_element_block_details_divider($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_css = isset($elm->data_css) ? $elm->data_css : "";
		$data_text_props = isset($elm->data_text) ? $elm->data_text: "";
		?>
		<table class="thwecmf-block thwecmf-builder-block thwecmf-block-divider" id="tbf_<?php echo esc_attr( $data_id );?>" data-block-name='<?php echo esc_attr( $elm_name );?>' data-css-props='<?php echo esc_attr( $data_css ); ?>' cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<hr>
				</td>
			</tr>
		</table>
		<?php
	}

	private function render_builder_element_block_details_gap($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_css = isset($elm->data_css) ? $elm->data_css : "";
		$data_text_props = isset($elm->data_text) ? $elm->data_text: "";
		?>
		<p class="thwecmf-block thwecmf-block-gap thwecmf-builder-block" id="tbf_<?php echo esc_attr( $data_id );?>" data-block-name='<?php echo esc_attr( $elm_name );?>' data-css-props='<?php echo esc_attr( $data_css );?>'></p>
		<?php
	}


	private function render_builder_element_block_details_billing($elm, $elm_name){
		if(isset($elm->data_text)){
			$details = WECMF_Email_Customizer_Utils::thwecmf_is_json_decode($elm->data_text);
			if($details){
				$details = isset($details->content) && !empty($details->content) ? $details->content : "";
				$data_css = !empty($elm->data_css) ? json_decode($elm->data_css) : '';
				$align = !empty($data_css->align) ? $data_css->align : '';
			}
		}
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_css = isset($elm->data_css) ? $elm->data_css : "";
		$data_text_props = isset($elm->data_text) ? $elm->data_text: "";
		?>
		<span class="thwecmf_before_billing_table"></span>
		<table class="thwecmf-block thwecmf-block-billing thwecmf-builder-block" cellpadding="0" cellspacing="0"  id="tbf_<?php echo esc_attr( $data_id );?>" data-block-name='<?php echo esc_attr( $elm_name );?>' data-css-props='<?php echo esc_attr( $data_css );?>' data-text-props='<?php echo esc_attr( $data_text_props );?>'>
  			<tr>
      			<td class="thwecmf-address-alignment" align="<?php echo esc_attr( $align ); ?>">
      				<table class="thwecmf-address-wrapper-table" cellpadding="0" cellspacing="0">
      					<tr>
      						<td class="thwecmf-billing-padding">      
  								<h2 class="thwecmf-billing-header"><?php echo wp_kses_post( $details ); ?></h2>
  								<p class="address thwecmf-billing-body">
  									John Smith<br>
 									252  Bryan Avenue<br>
 									Minneapolis, MN 55412<br>
 									United States (US)
 									<br>333-6457<br><a href="#">johnsmith@gmail.com</a>
  								</p>
  							</td>
  						</tr>
  					</table>
  				</td>
  			</tr>
  		</table>
      	<span class="thwecmf_after_billing_table"></span>
		<?php
	}
	
	private function render_builder_element_block_details_shipping($elm, $elm_name){
		if(isset($elm->data_text)){
			$details = WECMF_Email_Customizer_Utils::thwecmf_is_json_decode($elm->data_text);
			if($details){
				$details = isset($details->content) && !empty($details->content) ? $details->content : "";
				$data_css = !empty($elm->data_css) ? json_decode($elm->data_css) : '';
				$align = !empty($data_css->align) ? $data_css->align : '';
			}
		}
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_css = isset($elm->data_css) ? $elm->data_css : "";
		$data_text_props = isset($elm->data_text) ? $elm->data_text: "";
		?>
		<span class="thwecmf_before_shipping_table"></span>
		<table class="thwecmf-block thwecmf-block-shipping thwecmf-builder-block" cellpadding="0" cellspacing="0" id="tbf_<?php echo esc_attr( $data_id );?>" data-block-name='<?php echo esc_attr( $elm_name );?>' data-css-props='<?php echo esc_attr( $data_css );?>' data-text-props='<?php echo esc_attr( $data_text_props );?>'>
  			<tr>
  				<td class="thwecmf-address-alignment" align="<?php echo esc_attr( $align ); ?>">
  					<table class="thwecmf-address-wrapper-table" cellpadding="0" cellspacing="0">
  						<tr>
  							<td class="thwecmf-shipping-padding">          
			 	 				<h2 class="thwecmf-shipping-header"><?php echo wp_kses_post( $details ); ?></h2>
			  					<p class="address thwecmf-shipping-body">
			 						John Smith<br>
			 						252  Bryan Avenue<br>
			 						Minneapolis, MN 55412<br>
			 						United States (US)
			  					</p>
			  				</td>
			  			</tr>
			  		</table>
  				</td>
  			</tr>
  		</table>
      	<span class="thwecmf_after_shipping_table"></span>
		<?php		
	}


	private function render_builder_element_block_details_email_header_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name ); ?>}</p>
		<?php
	}
	private function render_builder_element_block_details_email_order_details_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name );?>}</p>
		<?php		
	}
	private function render_builder_element_block_details_before_order_table_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name );?>}</p>
		<?php		
	}
	private function render_builder_element_block_details_after_order_table_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>	
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name );?>}</p>
		<?php		
	}
	private function render_builder_element_block_details_order_meta_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>	
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name );?>}</p>
		<?php		
	}
	private function render_builder_element_block_details_customer_address_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name );?>}</p>
		<?php		
	}
	private function render_builder_element_block_details_email_footer_hook($elm, $elm_name){
		$data_id = isset($elm->data_id) ? $elm->data_id : "";
		$data_name = isset($elm->data_name) ? $elm->data_name : "";
		?>
			<p class="thwecmf-hook-code" id="tbf_<?php echo esc_attr( $data_id );?>">{<?php echo esc_html( $data_name );?>}</p>
		<?php		
	}

	private function render_template_builder_css_section($wrapper_id) {
		?>

		<style id="<?php echo esc_attr( $wrapper_id ); ?>" type="text/css">
			.thwecmf-main-builder{
				max-width:600px;
				width:600px;
				margin: auto; 
			}

			.thwecmf-main-builder .thwecmf-builder-column{
				background-color:#ffffff;
				vertical-align: top;
				border:1px solid #dedede; 
				border-radius: 2px;
			}
			.thwecmf_wrapper{
				background-color: #f7f7f7;
				margin: 0; 
				width: 100%;
			}
			.thwecmf-row{
				border-spacing: 0px;
			}

			.thwecmf-row,
			.thwecmf-block{
				width:100%;
				table-layout: fixed;
			}
			.thwecmf-block td{
				padding: 0;
			}
			.thwecmf-layout-block{
				overflow: hidden;
			}
			.thwecmf-row td{
				vertical-align: top;
				box-sizing: border-box;
			}
			.thwecmf-block-one-column,
			.thwecmf-block-two-column,
			.thwecmf-block-three-column,
			.thwecmf-block-four-column{
				max-width: 100%;
				margin: 0 auto;
				border:0px solid transparent;
			}

			.thwecmf-row .thwecmf-columns{
				border: 1px dotted #dddddd;
				word-break: break-word;
				padding: 10px 10px;
			}
			.thwecmf-block-one-column td{
				width: 100%;				
			}
			.thwecmf-block-two-column td{
				width: 50%;				
			}
			.thwecmf-block-three-column td{
				width: 33%;				
			}
			.thwecmf-block-four-column td{
				width: 25%;				
			}

			.thwecmf-block-divider{
				margin: 0;
			}

			.thwecmf-block-divider td{
				padding: 20px 0px;
				text-align: center;
			}

			.thwecmf-block-divider hr{
				display: inline-block;
				border:none;
				border-top: 2px solid transparent;
				border-color: gray;
				width:70%;
				height: 2px;
				margin: 0 auto;
			}

			.thwecmf-block-text{
				width: 100%;
				color: #636363;
				font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				line-height: 22px;
				text-align:center;
				margin: 0 auto;
				box-sizing: border-box;
			}

			.thwecmf-block-text .thwecmf-block-text-holder{
				color: #636363;
				font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				line-height: 22px;
				text-align: center;
				padding: 15px 15px;
			}

			.thwecmf-block-text .thwecmf-block-text-holder a{
				color: #1155cc !important;
			}

			.thwecmf-block-image{
				width: auto;
				height: auto;
				max-width: 600px;
				box-sizing: border-box;
				width: 100%;
			}

			.thwecmf-block-image td.thwecmf-image-column{
				text-align: center;
				padding: 5px 5px;
				vertical-align: top;
			}

			.thwecmf-block-image p{
				margin: 0;
				display: inline-block;
				width: 50%;
			}

			.thwecmf-block-image img{
				width:100%;
				height:100%;
				max-width: 100%;
				display:block;
			}

			.thwecmf-block-shipping .thwecmf-shipping-padding,
			.thwecmf-block-billing .thwecmf-billing-padding{
				padding: 5px 0px 2px 0px;
			}

			.thwecmf-block-billing,
			.thwecmf-block-shipping,
			.thwecmf-block-shipping .thwecmf-address-alignment,
			.thwecmf-block-billing .thwecmf-address-alignment{
				margin: 0;
				padding:0;
				border: 0px none transparent;
				border-collapse: collapse;
				box-sizing: border-box;
			}

			.thwecmf-block-billing .thwecmf-address-wrapper-table,
			.thwecmf-block-billing .thwecmf-address-wrapper-table,
			.thwecmf-block-billing .thwecmf-address-wrapper-table{
				width:100%;
				height: 115px;
			}

			.thwecmf-block-billing .thwecmf-billing-header,
			.thwecmf-block-shipping .thwecmf-shipping-header {
				color:#0099ff;
				display:block;
				font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size:18px;
				font-weight:bold;
				line-height:170%;
				text-align:center;
				margin: 0px;
			}

			.thwecmf-block-billing .thwecmf-billing-body,
			.thwecmf-block-shipping .thwecmf-shipping-body {
				text-align:center;
				line-height:150%;
				border:0px !important;
				font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				padding: 0px 0px 0px 0px
			}

			.thwecmf-block-billing .thwecmf-billing-body a,
			.thwecmf-block-shipping .thwecmf-shipping-body a{
				color: #0073aa;
			}

			.thwecmf-block-custom-hook{
				margin: 0;
				line-height: 0;
			}

			.thwecmf-block-gap{
				height:48px;
				margin: 0;
				box-sizing: border-box;
			}

		</style>
		<style id="<?php echo esc_attr( $wrapper_id ); ?>_override" type="text/css">
			<?php if($this->template_json_css !== ''){
				echo esc_html( $this->template_json_css );
			}?>

		</style>
		<style id="<?php echo esc_attr( $wrapper_id ); ?>_preview_override" type="text/css">
			<?php if($this->template_json_css){
				$json_css_override = str_replace('tbf_', 'tpf_', $this->template_json_css);
				echo esc_html( $json_css_override );
			}?>
		</style>
		<?php
	}

	public function render_form_fragment_h_separator($atts = array(),$icon=false){
		$args = shortcode_atts( array(
			'colspan' 	     => '',
			'padding-top'    => '5px',
			'padding-bottom' => '',
			'border-style'   => 'dashed',
    		'border-width'   => '1px',
			'border-color'   => '#e6e6e6',
			'content'	     => '',
			'class'			 => 'thwecmf-seperator-heading',
			'padding'  		 => '8px 0px 6px 5px',
			'font-size'  	 => '13px'
		), $atts );
		$style  = $args['padding-bottom'] ? 'padding-bottom:'.$args['padding-bottom'].';' : '';
		$style .= $args['padding'] ? 'padding:'.$args['padding'].';' : '';
		$style .= $args['border-style'] ? ' border-bottom:'.$args['border-width'].' '.$args['border-style'].' '.$args['border-color'].';' : '';
		?>
		<tr class="thwecmf-spacer"><td></td></tr>
        <tr><td colspan="<?php echo esc_attr( $args['colspan'] ); ?>" style="<?php echo esc_attr( $style ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>"><?php echo esc_attr( $args['content'] ); 
        if($icon){
        	echo '<span class="dashicons dashicons-arrow-down-alt2 thwecmf-direction-arrow"></span>';
        }
        ?>	
        </td></tr>
        <tr class="thwecmf-spacer"><td></td></tr>
        <?php
	}

	public function render_form_element_tooltip($tooltip){
        $tooltip_html = '';
        if($tooltip){
            $tooltip_html = '<a href="javascript:void(0)" title="'. esc_attr( $tooltip ).'" class="thpladmin_tooltip"><span class="dashicons dashicons-editor-help"></span></a>';
        }
        ?>
        <td style="width: 26px; padding:0px;"><?php echo wp_kses( $tooltip_html ,wp_kses_allowed_html('post') ); ?></td>
        <?php
    }

    public function render_form_fields($field, $atts = array(), $render_cell = true, $bypass_premium = false){
		$premium_class = false;
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_cell_props' => '',
				'input_cell_props' => '',
				'label_cell_colspan' => '',
				'input_cell_colspan' => '',
			), $atts );

			$pro_feature = '';
			
			$ftype     = isset($field['type']) ? $field['type'] : 'text';
			$flabel    = isset($field['label']) && !empty($field['label']) ? __($field['label']) : '';
			$sub_label = isset($field['sub_label']) && !empty($field['sub_label']) ? __($field['sub_label']) : '';
			$tooltip   = isset($field['hint_text']) && !empty($field['hint_text']) ? __($field['hint_text']) : '';
			$template_error = isset($field['template_error']) && !empty($field['template_error']) ? $field['template_error'] : '';
			$fclass    = isset($field['class']) && !empty($field['class']) ? $field['class'] : false;
			$bypass_premium ? $field['class'] = 'thwecmf-premium-disabled-input' : '';
			if($fclass && $fclass == 'thwecmf-premium-disabled-input' || $bypass_premium){
				$pro_feature .= '<span class="th-premium-feature-msg">Pro</span>';
				$premium_class = true;
			}

			$field_html = '';
			$additional_data = '';

			if($ftype == 'text'){
				$field_html = $this->render_form_fields_inputtext($field, $atts);
				
			}
			else if($ftype == 'hidden'){
				$field_html = $this->render_form_fields_hidden($field,$atts);
			}else if($ftype == 'textarea'){
				$field_html = $this->render_form_fields_textarea($field, $atts);
				   
			}else if($ftype == 'select'){
				$field_html = $this->render_form_fields_select($field, $atts);     
				
			}else if($ftype == 'multiselect'){
				$field_html = $this->render_form_fields_multiselect($field, $atts);     
				
			}else if($ftype == 'colorpicker'){
				$field_html = $this->render_form_fields_colorpicker($field, $atts);
				$additional_data .= 'class="thwecmf-color-picker-wrapper"';              
            
			}else if($ftype == 'twoside'){
				$field_html = $this->render_form_fields_twoside($field, $atts);              
            
			}else if($ftype == 'fourside'){
				$field_html = $this->render_form_fields_fourside($field, $atts);              
            
			}else if($ftype == 'alignment-icons'){
				$field_html = $this->render_form_fields_alignment_icon($field, $atts);
			}else if($ftype == 'checkbox'){
				$field_html = $this->render_form_fields_checkbox($field, $atts, $render_cell);   
			}else if($ftype == 'radio'){
				$field_html = $this->render_form_fields_radio($field, $atts, $render_cell);
			}else if($ftype == 'number'){
				$field_html = $this->render_form_fields_number($field, $atts);   
			}else if($ftype == 'range'){
				$field_html = $this->render_form_fields_range_slider($field, $atts);
			}

			if($render_cell && $render_cell !== 'template-map'){
				$color_picker_class="thwecmf-color-picker-wrapper";
				$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';
				$label_cell_props = !empty($args['label_cell_props']) ? $args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? $args['input_cell_props'] : '';
				echo '<tr class="thwecmf-input-spacer"><td></td></tr>';
				if($flabel){
					$flabel = $premium_class ? $flabel = $flabel.$pro_feature : $flabel;
				?>
				<tr>
					<td <?php echo esc_attr( $label_cell_props ); ?> >
						<?php 
						echo $flabel;
						echo $required_html;
						if($sub_label){
							?>
							<br/><span class="thpladmin-subtitle"><?php echo $sub_label; ?></span>
							<?php 
						}
						?>
					</td>
				</tr>
				<?php 
				}
				?>
				<tr>
					<td <?php echo esc_attr( $additional_data ); ?> >
						<?php echo $field_html; ?>
					</td>
				</tr>
				<?php
				if($template_error){
					echo '<tr><td><span class="thwecmf-missing-template-warning">Template not found</span></td></tr>';
				}
			}else if($render_cell=='template-map'){
				$color_picker_class="thwecmf-color-picker-wrapper";
				$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';
				$label_cell_props = !empty($args['label_cell_props']) ? $args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? $args['input_cell_props'] : '';
				?>
				<tr>
					<td <?php echo esc_attr( $label_cell_props ); ?> >
						<?php 
						echo wp_kses( $flabel ,wp_kses_allowed_html('post') ); 
						echo wp_kses( $required_html ,wp_kses_allowed_html('post') ); 
						if($sub_label){
							?>
							<br/><span class="thpladmin-subtitle"><?php echo wp_kses( $sub_label ,wp_kses_allowed_html('post') ); ?></span>
							<?php 
						}
						?>
					</td>
					<td <?php echo esc_attr( $additional_data ); ?> >
						<?php echo $field_html; ?>

					</td>
				</tr>
				<?php
			}
			else{
				echo '<div class="thpladmin-input-wrapper">';
				$title = '<span class="thpladmin-title">'.$flabel.$pro_feature.'</span>';
				echo $title.$field_html;
				echo '</div>';
			}
		}
	}

	private function render_form_fields_inputtext($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$fclass = isset($field['class']) ? $field['class'] : '';
			$field_html = '<input type="text" class="'.esc_attr( $fclass ).'" '. $field_props .' />';
		}
		return $field_html;
	}
	
	private function render_form_fields_hidden($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="hidden" '. $field_props .' />';
		}
		return $field_html;
	}

	private function render_form_fields_textarea($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'rows' => '4',
				'cols' => '100',
			), $atts );
		
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<textarea '. $field_props .' rows="'.esc_attr( $args['rows'] ).'" cols="'.esc_attr( $args['cols'] ).'" >'.$fvalue.'</textarea>';
		}
		return $field_html;
	}
	
	private function render_form_fields_select($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html = '<select '. $field_props .' >';
			foreach($field['options'] as $value => $label){
				$selected = $value == $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. esc_attr( trim($value) ).'" '.$selected.'>'. __($label) .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}
	
	private function render_form_fields_multiselect($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html = '<select multiple="multiple" '. $field_props .' class="thpladmin-enhanced-multi-select" >';
			foreach($field['options'] as $value => $label){
				$field_html .= '<option value="'. esc_attr( trim($value) ).'" >'. __($label) .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}
	
	private function render_form_fields_radio($field, $atts = array(), $render_cell = true){
		$field_html = '';
		$args = shortcode_atts( array(
			'label_props' => '',
			'cell_props'  => 3,
			'render_input_cell' => false,
			'render_label_cell' => false,
			), $atts );

		$atts = array(
		'input_width' => 'auto',
		);

		if($field && is_array($field)){
			$fvalue = isset($field['value']) ? $field['value'] : '';	
			$fclass = isset($field['class']) && !empty($field['class']) ? 'r_f'. $field['class'] : '';
			$onchange = isset($field['onchange']) && !empty($field['onchange']) ? ' onchange="'.$field['onchange'].'"' : '';
			foreach ($field['options'] as $value => $label) {
				$checked ='';
				$flabel = isset($label['name']) && !empty($label['name']) ? __($label['name'],'') : '';
				$checked = $value === $fvalue ? 'checked' : '';
				$selected = $value === $fvalue ? 'rad-selected' : '';	
				$field_html .= '<input type="radio" name="i_' . esc_attr( $field['name'] ) . '" class="'.esc_attr( $fclass ).'"value="'. esc_attr( trim($value) ).'" ' . $checked . $onchange . '/>'.$label;
			}
		}	
		return $field_html;
	}


	private function render_form_fields_checkbox($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_props' => '',
				'cell_props'  => 3,
				'render_input_cell' => false,
			), $atts );
		
			$fid 	= 'a_f'. $field['name'];
			$fclass = isset($field['class']) && !empty($field['class']) ? 'c_f'. $field['class'] : '';
			$flabel = isset($field['label']) && !empty($field['label']) ? __($field['label']) : '';
			
			$field_props  = $this->prepare_form_field_props($field, $atts);
			$field_props .= $field['checked'] ? ' checked' : '';
			$field_html  = '<input type="checkbox" id="'. $fid .'" class="'.esc_attr( $fclass ).'" '.$field_props .' />';
			$field_html .= '<label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label>';
		}
		if(!$render_cell && $args['render_input_cell']){
			return '<td '. $args['cell_props'] .' >'. $field_html .'</td>';
		}else{
			return $field_html;
		}
	}
	
	private function render_form_fields_number($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$flabel = isset($field['label']) && !empty($field['label']) ? __($field['label']) : '';
			$fmin = isset($field['min']) ? __($field['min']) : '';			
			$fmax = isset($field['max']) && !empty($field['max']) ? __($field['max']) : '';
			$fstep = isset($field['step']) && !empty($field['step']) ? __($field['step']) : '';
			$field_props  = 'min="'.$fmin.'" max="'.$fmax.'" step="'.$fstep.'"';
			$field_props .= $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="number" '. $field_props .' />';
		}
		return $field_html;
	}

	private function render_form_fields_range_slider($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$flabel = isset($field['label']) && !empty($field['label']) ? __($field['label']) : '';
			$fmin = isset($field['min']) ? __($field['min']) : '';			
			$fmax = isset($field['max']) && !empty($field['max']) ? __($field['max']) : '';
			$fstep = isset($field['step']) && !empty($field['step']) ? __($field['step']) : '';
			$field_props  = 'min="'.$fmin.'" max="'.$fmax.'" step="'.$fstep.'"';
			$field_props .= $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="range" '. $field_props .' />';
		}
		return $field_html;
	}
	

	private function render_form_fields_colorpicker($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$margin = isset($atts['input_margin']) ? $atts['input_margin'] : '';
			$atts = array('input_width' => '105px','input_height'=>'30px','input_font_size' => '13px');
			$atts['input_margin'] = $margin;

			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html  = '<span class="thpladmin-colorpickpreview '.$field['name'].'_preview" style=""></span>';
            $field_html .= '<input type="text" '. $field_props .' class="thpladmin-colorpick" size="8" autocomplete="off"/>';
		}
		return $field_html;
	}

	private function render_form_fields_twoside($field, $atts = array()){
				$field_html = '';
		if($field && is_array($field)){
			$fclass  = isset($field['class']) ? $field['class'] : '';
			$fclass .= ' size-input-group';
			$atts_width = array('input_name_suffix' => '_width', 'input_margin' => '0 6px 0 0', 'input_width'=>'136px', 'input_height'=>'30px', 'input_b_r' => '4px', 'input_font_size' => '13px');
			$atts_height = array('input_name_suffix' => '_height','input_width'=>'136px', 'input_height'=>'30px', 'input_b_r' => '4px', 'input_font_size' => '13px');
			$field_props_width = $this->prepare_form_field_props($field, $atts_width);
			$field_props_height = $this->prepare_form_field_props($field, $atts_height);
			$field_html  = '<input type="text" placeholder="Width" class="'.esc_attr( $fclass ).'" '. $field_props_width .' />';
			$field_html .= '<input type="text" placeholder="Height" class="'.esc_attr( $fclass ).'" '. $field_props_height .' />';
		}
		return $field_html;
	}

	private function render_form_fields_fourside($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$fclass  = isset($field['class']) ? $field['class'] : '';
			$fclass .= ' input-group';
			$atts_top = array('input_name_suffix' => '_top', 'input_margin' => '0 6px 0 0','input_width'=>'65px','input_height'=>'30px','input_b_r' => '4px', 'input_font_size' => '13px');
			$atts_right = array('input_name_suffix' => '_right', 'input_margin' => '0 6px 0 0','input_width'=>'65px','input_height'=>'30px','input_b_r' => '4px', 'input_font_size' => '13px');
			$atts_bottom = array('input_name_suffix' => '_bottom', 'input_margin' => '0 6px 0 0','input_width'=>'65px','input_height'=>'30px','input_b_r' => '4px', 'input_font_size' => '13px');
			$atts_left = array('input_name_suffix' => '_left','input_width'=>'65px','input_height'=>'30px','input_b_r' => '4px', 'input_font_size' => '13px');
			$field_props_top = $this->prepare_form_field_props($field, $atts_top);
			$field_props_right = $this->prepare_form_field_props($field, $atts_right);
			$field_props_bottom = $this->prepare_form_field_props($field, $atts_bottom);
			$field_props_left = $this->prepare_form_field_props($field, $atts_left);
			$field_html  = '<input type="text" placeholder="Top" class="'.esc_attr( $fclass ).'" '. $field_props_top .' />';
			$field_html .= '<input type="text" placeholder="Right" class="'.esc_attr( $fclass ).'" '. $field_props_right .' />';
			$field_html .= '<input type="text" placeholder="Bottom" class="'.esc_attr( $fclass ).'" '. $field_props_bottom .' />';
			$field_html .= '<input type="text" placeholder="Left" class="'.esc_attr( $fclass ).'" '. $field_props_left .' />';
		}
		return $field_html;
	}

	public function render_form_fields_alignment_icon($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$fclass = isset($field['class']) ? $field['class'] : '';
			$wrap_suffix = $fclass === 'thwecmf-premium-disabled-input' ? "icon-wrap" : "img-wrapper"; 
			$active_icon = $fclass === 'thwecmf-premium-disabled-input' ? " thwecmf-active-icon" : ""; 
			$field_html = '<div class="thwecmf-aligment-icon-wrapper '.$fclass.'">';
			$field_html .= '<div class="'.$wrap_suffix.'" data-align="left" style="margin-right:6px;"><img src='.TH_WECMF_ASSETS_URL.'images/align-left.svg alt="left"></div>';
			$field_html .= '<div class="'.$wrap_suffix.$active_icon.'" data-align="center" style="margin-right:6px;"><img src='.TH_WECMF_ASSETS_URL.'images/align-center.svg alt="center"></div>';
			$field_html .= '<div class="'.$wrap_suffix.'" data-align="right" style="margin-right:6px;"><img src='.TH_WECMF_ASSETS_URL.'images/align-right.svg alt="right"></div>';
			if(isset($field['icon_flag']) && $field['icon_flag']){
				$field_html .= '<div class="'.$wrap_suffix.'" data-align="justify"><img src='.TH_WECMF_ASSETS_URL.'images/align-justify.svg alt="justify"></div>';
			}
			if($fclass !== 'thwecmf-premium-disabled-input'){
				$field_html .= '<br><input type="hidden" class="'.$fclass.'" '. $field_props .' />';
			}
			$field_html .= '</div>';
		}
		return $field_html;
	}

	private function prepare_form_field_props($field, $atts = array()){
		$field_props = '';
		$pro_feature = '';
		$args = shortcode_atts( array(
			'input_width' => '',
			'input_height' => '',
			'input_name_prefix' => 'i_',
			'input_name_suffix' => '',
			'input_margin' => '',
			'input_b_r' => '',
			'input_font_size' => '',
		), $atts );
		
		$ftype = isset($field['type']) ? $field['type'] : 'text';
		$fclass    = isset($field['class']) && !empty($field['class']) ? $field['class'] : false;
		if($fclass && $fclass == 'thwecmf-premium-disabled-input'){
			$pro_feature .= '<span class="th-premium-feature-msg">Pro</span>';
		}
		if($ftype == 'multiselect'){
			$args['input_name_suffix'] = $args['input_name_suffix'].'[]';
		}
		$fname  = $args['input_name_prefix'].$field['name'].$args['input_name_suffix'];
		$fvalue = isset($field['value']) ? $field['value'] : '';
		$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
		$input_height  = $args['input_height'] ? 'height:'.$args['input_height'].';' : '';
		$input_margin  = $args['input_margin'] ? 'margin:'.$args['input_margin'].';' : '';
		$input_b_r = $args['input_b_r'] ? 'border-radius:'.$args['input_b_r'].';' : '';
		$input_font_size = $args['input_font_size'] ? 'font-size:'.$args['input_font_size'].';' : '';
		$field_props  = 'name="'. $fname .'" style="'. $input_width.$input_height.$input_b_r.$input_margin.$input_font_size .'"';
		if($ftype !== 'select'){
			$field_props  .= 'value="'. $fvalue .'"';
		}
		$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
		$field_props .= ( isset($field['class']) && !empty($field['class']) ) ? ' class="'.$field['class'].'"' : '';
		$field_props .= ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onchange="'.$field['onchange'].'"' : '';
		$field_props .= ( isset($field['class']) && !empty($field['class']) && $field['class'] == 'thwecmf-premium-disabled-input' ) ? 'disabled' : '';
		return $field_props;
	}

	public function get_admin_url($tab = false, $section = false){
		$url = 'admin.php?page=thwecmf_email_customizer';
		if($tab && !empty($tab)){
			$url .= '&tab='. $tab;
		}
		if($section && !empty($section)){
			$url .= '&section='. $section;
		}
		return admin_url($url);
	}

	public function render_section_separator($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo esc_attr( $props['colspan'] ); ?>" style="height:10px;"></td></tr>
		<tr valign="top"><td colspan="<?php echo esc_attr( $props['colspan'] ); ?>" class="thpladmin-form-section-title" ><?php echo esc_attr( $props['title'] ); ?>
			<?php 
			if(isset($props['dashicons'])){
				?>

				<a href="javascript:void(0)" title="<?php echo isset($props['dashicon-title']) ? esc_attr( $props['dashicon-title'] ) : ''; ?>" class="thwecmf_admin_dashicon_tooltip thpladmin_tooltip"><span class="dashicons <?php echo esc_attr( $props["dashicons"] );?> thwecmf-seperator-dashicons"></span></a>
			<?php } ?>
			</td>
		</tr>
		<tr valign="top">
			<td colspan="<?php echo esc_attr( $props['colspan'] ); ?>" style="height:0px;"></td>
		</tr>
		<?php
	}
}
endif;