<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$plugin_name = WCPFC_PRO_PLUGIN_NAME;
$plugin_version = WCPFC_PRO_PLUGIN_VERSION;
$wcpfc_admin_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( '', '' );
?>
<div id="dotsstoremain">
	<div class="all-pad">
		<header class="dots-header">
			<div class="dots-logo-main">
				<img
					src="<?php 
echo  esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/wc-conditional-product-fees.png' ) ;
?>">
			</div>
			<div class="dots-header-right">
				<div class="logo-detail">
					<strong><?php 
esc_html_e( $plugin_name, 'woocommerce-conditional-product-fees-for-checkout' );
?> </strong>
					<span><?php 
esc_html_e( WCPFC_PRO_PREMIUM_VERSION, 'woocommerce-conditional-product-fees-for-checkout' );
echo  esc_html( $plugin_version ) ;
?></span>
				</div>
				<div class="button-group">
					<div class="button-dots-left">
						<?php 
?>
							<span>
                                <a target="_blank"
                                   href="<?php 
echo  esc_url( 'www.thedotstore.com/woocommerce-conditional-product-fees-checkout' ) ;
?>">
                                    <img
	                                    src="<?php 
echo  esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/upgrade_new.png' ) ;
?>">
                                </a>
                            </span>
							<?php 
?>
					</div>
					<div class="button-dots">
                        <span class="support_dotstore_image">
                            <a target="_blank" href="<?php 
echo  esc_url( 'http://www.thedotstore.com/support/' ) ;
?>">
                                <img
	                                src="<?php 
echo  esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/support_new.png' ) ;
?>">
                            </a>
                        </span>
					</div>
				</div>
			</div>
			
			<?php 
$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
$wcpfc_admin_object->wcpfc_pro_menus( $current_page );
?>
		</header>