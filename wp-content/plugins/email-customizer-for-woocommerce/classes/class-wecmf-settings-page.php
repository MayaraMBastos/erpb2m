<?php
/**
 * Woo Email Customizer Setting Page
 *
 * @author   ThemeHiGH
 * @category Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('WECMF_Settings_Page')) :
abstract class WECMF_Settings_Page{
	protected $page_id = '';		
	protected $tabs = '';
    protected $get_params = '';

	public function __construct() {
		$this->tabs = array( 'fields' => 'Manage Product Options');
        $this->get_params = array(
            'premium' =>'Unlock Premium to Create Unlimited Templates. You can edit the available templates and create any designs', 
            'datamissing' => 'Something went wrong. Please try again.');
	}

	public function get_tabs(){
		return $this->tabs;
	}
	
	public function get_current_tab(){
		return $this->page_id;
	}
		
	public function output_tabs(){
		$current_tab = $this->get_current_tab();
		$tabs = $this->get_tabs();
		if(empty($tabs)){
			return;
		}
		$this->output_premium_version_notice();	
	}	
	
	public function output_premium_version_notice(){
		?>
        <div id="message" class="wc-connect updated thwecmf-notice">
            <div class="squeezer">
            	<table>
                	<tr>
                    	<td width="70%">
                        	<p><strong><i>Email Customizer For WooCommerce</i></strong> premium version provides more features to customise product page.</p>
                            <ul>
                            	<li>15+ elements available,<br/>(<i>Text, Image, Social Icons, Divider, Gap, etc.	</i>).</li>
                                <li>Multiple column layouts including option to add additional columns</li>
                                <li>Option to add additional placeholders to set dynamic data</li>
                                <li>Option to edit WooCommerce blocks</li>
                            </ul>
                        </td>
                        <td>
                        	<a target="_blank" href="https://www.themehigh.com/product/woocommerce-email-customizer/" class="">
                            	<img src="<?php echo plugins_url( '../assets/images/upgrade-btn.png', __FILE__ ); ?>" />
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
	}

	public function output_feature_notices(){
        $feature = isset($_GET['feature']) ?  esc_attr( $_GET['feature'] ) : "";
        if(array_key_exists($feature, $this->get_params)){
            ?>
    		<div id="message" class="wecm-pro-content-msg">
                <div class="squeezer">
                	<table>
                    	<tr>
                        	<td width="70%">
                            	<p><span class="dashicons dashicons-info"></span><b><?php echo esc_attr( $this->get_params[$feature] ); ?></b></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
	}
}
endif;