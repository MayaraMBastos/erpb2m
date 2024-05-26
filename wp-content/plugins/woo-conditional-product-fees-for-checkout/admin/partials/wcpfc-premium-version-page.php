<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="wcpfc-section-left">
		<div class="wcpfc-main-table res-cl">
			<div class="wcpfc-premium-features">
				<div class="section section-even clear">
					<h1><?php esc_html_e( 'Premium Features', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h1>
					<div class="landing-container">
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/001-extra-fees.png' ); ?>"
								alt="<?php esc_html_e( 'Charge Flexible Extra Fees', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Charge Flexible Extra Fees', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( '1. You can charge fixed amount as fee or a certain percentage of the cart subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<p><?php esc_html_e( '2. Dynamic extra fees can be charged based on total product quantity in cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'You can choose how quantity should be calculated: Calculate for certain products only (based on product-based conditions configured) OR Calculate for the whole cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'For each additional quantity in cart, you can set extra fee to charge. (e.g. if user has added 3 quantities in cart and you have set extra fee=$10 and fee for additional quantity=$5, then total extra fee=$10+$5+$5=$20 will be charged)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="section section-odd clear">
					<div class="landing-container pro-master-settings">
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Conditional fee Based on Geographic Area (location)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( 'Using this feature you can apply conditional fee rule for the geographic area. Like Country, State, Postcode, Zone.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'Add a conditional fee for the UK.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for California and zip code = 90001', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for Eurozone countries', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
							<p><?php esc_html_e( 'E.g. If you want to charge extra fees to customers who want to ship to the Alaska state of USA, you can add $20 as a woocommerce conditional fee.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
						</div>
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/002-country-based-fees.png' ); ?>"
								alt="<?php esc_html_e( 'Conditional fee Based on Geographic Area (location)', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
					</div>
				</div>
				<div class="section section-even clear">
					<div class="landing-container">
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/003-user-role-based-fees.png' ); ?>"
								alt="<?php esc_html_e( 'Conditional fee for a Specific user/user role', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Conditional fee for a Specific user/user role', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( 'Using this feature you can apply conditional fee rule for specific user/user role', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'Add a conditional fee for publisher user role.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific user like "Michael"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
							<p><?php esc_html_e( 'E.g. If you want to charge extra fees to all customers who have basic customer role and who are not Admin or Vendor or Premium customer, you can add $10 as a WooCcommerce conditional fee. for Customer role only.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
						</div>
					</div>
				</div>
				<div class="section section-odd clear">
					<div class="landing-container pro-master-settings">
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Conditional fee Based on Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( 'Using this feature you can apply conditional fee rule for product specific. Like Categories, Product and tags', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Music Category"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Happy Ninja Product"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "TCB 101 Product Tag"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
							<p><?php esc_html_e( 'E.g. If you want to charge extra fees for all products which are in the Shoe category, you can add $15 as a conditional fee for Shoe category. In the same manner, you can charge extra fees for a particular products or all product with a specific tag as well.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
						</div>
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/004-category-based-fees.png' ); ?>"
								alt="<?php esc_html_e( 'Conditional fee Based on Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
					</div>
				</div>
				<div class="section section-even clear">
					<div class="landing-container">
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/005-conditional-fee-based-on-cart.png' ); ?>"
								alt="<?php esc_html_e( 'Conditional fee Based on Cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Conditional fee Based on Cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( 'Using this feature you can apply conditional fee rule based on cart subtotal*, total weight of products or total quantity of products in cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'Add a conditional fee based on total cart subtotal = $500', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee based on total cart weight = 200 lbs', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee based on total cart quantity = 20 qty', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
							<p><?php esc_html_e( 'E.g. If you want to charge $20 when customer adds 1 to 9 pieces of product in cart, and $5 when he adds 10 or more pieces, you can create 2 rules to configure this type of arrangement.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<p><?php esc_html_e( 'E.g. You can charge extra $10 on all those products which have "Fast shipping" shipping class configured.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<p><?php esc_html_e( '*You can have the plugin consider cart subtotal before discount or after discount – there are 2 different parameters available to achieve this.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
						</div>
					</div>
				</div>
				<div class="section section-odd clear">
					<div class="landing-container pro-master-settings">
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Conditional fee Based on Payment Method', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( 'Using this feature you can apply conditional fee rule for payment method specific. Like Paypal, Stripe and Braintree payment.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'Add a conditional fee for specific "PayPal"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Stripe Payment"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Brain-tree payment Method"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
							<p><?php esc_html_e( 'E.g. If you want to charge extra fees for payment method. If customer selectPayPall payment then charges $20 extra fees for paypal payment method. If customer select Stipe payment then charge $30 extra fees for stripe payment method.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
						</div>
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/006-payment-method-based-fee.png' ); ?>"
								alt="<?php esc_html_e( 'Conditional fee Based on Payment Method', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
					</div>
				</div>
				<div class="section section-even clear">
					<div class="landing-container">
						<div class="col-1">
							<img
								src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/premium-version/007-shipping-method-based-fee.png' ); ?>"
								alt="<?php esc_html_e( 'Conditional fee Based on Shipping Method', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"/>
						</div>
						<div class="col-2">
							<div class="section-title">
								<h2><?php esc_html_e( 'Conditional fee Based on Shipping Method', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							</div>
							<p><?php esc_html_e( 'Using this feature you can apply conditional fee rule for shipping method specific. Like flat rate, free shipping, local pickup and custom shipping method', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
							<ul>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Flat rate"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Free Shipping"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Local Pickup"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
								<li><?php esc_html_e( 'Add a conditional fee for specific "Custom Shipping Method"', 'woocommerce-conditional-product-fees-for-checkout' ); ?></li>
							</ul>
							<p><?php esc_html_e( 'E.g. If you want to charge extra fees for specific WooCommerce Conditional Shipping method. If customer select flat rate shipping method then charge $25 extra fees for flat rate shipping method.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></p>
						</div>
					</div>
				</div>
				<div class="section section-cta section-odd">
					<div class="landing-container afsrm_upgrade_to_pro">
						<div class="wcpfc-wishlist-cta">
							<p><?php esc_html_e( "Upgrade to the PREMIUM VERSION to increase your affiliate program bonus!", 'woocommerce-conditional-product-fees-for-checkout' ) ?></p>
							<a target="_blank"
							   href="<?php echo esc_url( 'https://www.thedotstore.com/woocommerce-conditional-product-fees-checkout' ); ?>">
								<img
									src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL . 'admin/images/upgrade_new.png' ); ?>">
							</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>