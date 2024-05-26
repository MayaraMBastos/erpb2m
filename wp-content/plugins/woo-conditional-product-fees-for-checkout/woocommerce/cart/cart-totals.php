<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) {
	echo 'calculated_shipping';
} ?>">
	
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2><?php esc_html_e( 'Cart Totals', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
	<table cellspacing="0" class="shop_table shop_table_responsive">

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		
		<?php
		$cart_get_coupons = WC()->cart->get_coupons();
		foreach ( $cart_get_coupons as $code => $coupon ) :
			?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>
		
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			
			<?php wc_cart_totals_shipping_html(); ?>
			
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		
		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php esc_html_e( 'Shipping', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>
		
		<?php endif; ?>
		
		<?php
		$wcpfc_obj = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public( '', '' );
		$fees      = $wcpfc_obj->wcpfc_pro_get_applied_fees();
		foreach ( $fees as $fee ) :
			?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>">
					<?php
					$final_fees_label = get_post_meta( $fee->id, 'fee_settings_tooltip_desc', true );
					if ( ! empty( $final_fees_label ) ) {
						echo '<div class="extra-flate-tool-tip"><a data-tooltip="' . esc_attr( $final_fees_label ) . '"><i class="fa fa-question-circle fa-lg"></i></a></div>';
					}
					?>
					<?php wc_cart_totals_fee_html( $fee ); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		
		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' <small>' . __( '(estimated for %s)', 'woocommerce-conditional-product-fees-for-checkout' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
				: '';
			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $get_tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $get_tax->label ) . wp_kses_post( $estimated_text ); ?></th>
						<td data-title="<?php echo esc_attr( $get_tax->label ); ?>"><?php echo wp_kses_post( $get_tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . wp_kses_post( $estimated_text ); ?></th>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
		<tr class="loader-image-checkout" id="loader_image_checkout" style="display:none;">
			<td>
				<img src="<?php echo esc_url( WCPFC_PRO_PLUGIN_URL ) . 'public/images/custom_checkout_loading.gif' ?>"/>
			</td>
		</tr>
		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Total', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>
		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</table>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
	
	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>