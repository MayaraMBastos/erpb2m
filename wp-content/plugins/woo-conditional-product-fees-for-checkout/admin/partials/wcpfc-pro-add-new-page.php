<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
$wcpfc_admin_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( '', '' );
$wcpfc_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro( '', '' );

if ( isset( $_POST['submitFee'], $_POST['wcpfc_pro_fees_conditions_save'] ) && wp_verify_nonce( sanitize_text_field( $_POST['wcpfc_pro_fees_conditions_save'] ), 'wcpfc_pro_fees_conditions_save_action' ) && !empty($_POST['submitFee']) ) {
    $post_data = $_POST;
    $wcpfc_admin_object->wcpfc_pro_fees_conditions_save( $post_data );
}


if ( isset( $_REQUEST['action'], $_REQUEST['id'] ) && 'edit' === $_REQUEST['action'] ) {
    $get_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
    $get_retrieved_nonce = ( isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '' );
    if ( !wp_verify_nonce( $get_retrieved_nonce, 'wcpfcnonce' ) ) {
        die( 'Failed security check' );
    }
    $request_post_id = sanitize_text_field( $_REQUEST['id'] );
    $btnValue = __( 'Update', 'woocommerce-conditional-product-fees-for-checkout' );
    $fee_title = __( get_the_title( $request_post_id ), 'woocommerce-conditional-product-fees-for-checkout' );
    $getFeesCost = __( get_post_meta( $request_post_id, 'fee_settings_product_cost', true ), 'woocommerce-conditional-product-fees-for-checkout' );
    $getFeesType = __( get_post_meta( $request_post_id, 'fee_settings_select_fee_type', true ), 'woocommerce-conditional-product-fees-for-checkout' );
    $wcpfc_tooltip_desc = __( get_post_meta( $request_post_id, 'fee_settings_tooltip_desc', true ), 'woocommerce-conditional-product-fees-for-checkout' );
    $getFeesStartDate = get_post_meta( $request_post_id, 'fee_settings_start_date', true );
    $getFeesEndDate = get_post_meta( $request_post_id, 'fee_settings_end_date', true );
    $getFeesTaxable = __( get_post_meta( $request_post_id, 'fee_settings_select_taxable', true ), 'woocommerce-conditional-product-fees-for-checkout' );
    $getFeesStatus = get_post_status( $request_post_id );
    $productFeesArray = get_post_meta( $request_post_id, 'product_fees_metabox', true );
    
    if ( is_serialized( $productFeesArray ) ) {
        $productFeesArray = maybe_unserialize( $productFeesArray );
    } else {
        $productFeesArray = $productFeesArray;
    }

} else {
    $request_post_id = '';
    $btnValue = __( 'Save', 'woocommerce-conditional-product-fees-for-checkout' );
    $fee_title = '';
    $getFeesCost = '';
    $getFeesType = '';
    $wcpfc_tooltip_desc = '';
    $getFeesStartDate = '';
    $getFeesEndDate = '';
    $getFeesTaxable = '';
    $getFeesStatus = '';
    $productFeesArray = array();
    $getFeesOptional = '';
    $byDefaultChecked = '';
}

$sm_status = ( !empty($getFeesStatus) && 'publish' === $getFeesStatus || empty($getFeesStatus) ? 'checked' : '' );
?>
<div class="text-condtion-is" style="display:none;">
	<select class="text-condition">
		<option
			value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
		<option
			value="less_equal_to"><?php 
esc_html_e( 'Less or Equal to ( <= )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
		<option
			value="less_then"><?php 
esc_html_e( 'Less than ( < )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
		<option
			value="greater_equal_to"><?php 
esc_html_e( 'Greater or Equal to ( >= )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
		<option
			value="greater_then"><?php 
esc_html_e( 'Greater than ( > )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
		<option
			value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
	</select>
	<select class="select-condition">
		<option
			value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
		<option
			value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
	</select>
</div>

<div class="wcpfc-section-left">
	<div class="wcpfc-main-table res-cl">
		<?php 
wp_nonce_field( 'wcpfc_pro_product_fees_conditions_values_ajax_action', 'wcpfc_pro_product_fees_conditions_values_ajax' );
?>
		<h2><?php 
esc_html_e( 'Fee Configuration', 'woocommerce-conditional-product-fees-for-checkout' );
?></h2>
		<form method="POST" name="feefrm" action="">
			<?php 
wp_nonce_field( 'wcpfc_pro_fees_conditions_save_action', 'wcpfc_pro_fees_conditions_save' );
?>
			<input type="hidden" name="post_type" value="wc_conditional_fee">
			<input type="hidden" name="fee_post_id" value="<?php 
echo  esc_attr( $request_post_id ) ;
?>">
			<table class="form-table table-outer product-fee-table">
				<tbody>
				<tr valign="top">
					<th class="titledesc" scope="row"><label
							for="onoffswitch"><?php 
esc_html_e( 'Status', 'woocommerce-conditional-product-fees-for-checkout' );
?></label>
					</th>
					<td class="forminp">
						<label class="switch">
							<input type="checkbox" name="fee_settings_status"
							       value="on" <?php 
echo  esc_attr( $sm_status ) ;
?>>
							<div class="slider round"></div>
						</label>
						<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						<p class="description"
						   style="display:none;"><?php 
esc_html_e( 'Enable or Disable', 'woocommerce-conditional-product-fees-for-checkout' );
?></p>
					</td>
				</tr>

				<tr valign="top">
					<th class="titledesc" scope="row"><label
							for="fee_settings_product_fee_title"><?php 
esc_html_e( 'Product Fee Title', 'woocommerce-conditional-product-fees-for-checkout' );
?>
							<span class="required-star">*</span></label></th>
					<td class="forminp">
						<input type="text" name="fee_settings_product_fee_title" class="text-class"
						       id="fee_settings_product_fee_title"
						       value="<?php 
echo  ( isset( $fee_title ) ? esc_attr( $fee_title ) : '' ) ;
?>" required="1"
						       placeholder="<?php 
esc_html_e( 'Enter product fees title', 'woocommerce-conditional-product-fees-for-checkout' );
?>">
						<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						<p class="description"
						   style="display:none;"><?php 
esc_html_e( 'This product fees title is visible to the customer at the time of checkout.', 'woocommerce-conditional-product-fees-for-checkout' );
?></p>
					</td>

				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label
							for="fee_settings_select_fee_type"><?php 
esc_html_e( 'Select Fee Type', 'woocommerce-conditional-product-fees-for-checkout' );
?></label>
					</th>
					<td class="forminp">
						<select name="fee_settings_select_fee_type" id="fee_settings_select_fee_type" class="">
							<option
								value="fixed" <?php 
echo  ( isset( $getFeesType ) && 'fixed' === $getFeesType ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'Fixed', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
							<option
								value="percentage" <?php 
echo  ( isset( $getFeesType ) && 'percentage' === $getFeesType ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'Percentage', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
						</select>
						<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						<p class="description"
						   style="display:none;"><?php 
esc_html_e( 'you can charges extra fees on fixed price or percentage.', 'woocommerce-conditional-product-fees-for-checkout' );
?></p>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row"><label
							for="fee_settings_product_cost"><?php 
esc_html_e( 'Fees', 'woocommerce-conditional-product-fees-for-checkout' );
?>
							<span class="required-star">*</span></label></th>
					<td class="forminp">
						<div class="product_cost_left_div">
							<input type="text" name="fee_settings_product_cost" required="1" class="text-class"
							       id="fee_settings_product_cost"
							       value="<?php 
echo  ( isset( $getFeesCost ) ? esc_attr( $getFeesCost ) : '' ) ;
?>"
							       placeholder="<?php 
echo  esc_attr( get_woocommerce_currency_symbol() ) ;
?>">
							<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
							<p class="description" style="display:none;">
								<?php 
esc_html_e( 'If you select fixed fees type then : you have to add fixed cost/fees (Eg. 10, 20) ).' );
echo  "<br/>" ;
esc_html_e( 'If you select percentage wise fees type then : you have to add percentage (Eg. 10, 15.20)', 'woocommerce-conditional-product-fees-for-checkout' );
?>
							</p>
						</div>
						<?php 
?>
					</td>
				</tr>
				<?php 
?>
				<tr valign="top">
					<th class="titledesc" scope="row"><label
							for="fee_settings_start_date"><?php 
esc_html_e( 'Start Date', 'woocommerce-conditional-product-fees-for-checkout' );
?></label>
					</th>
					<td class="forminp">
						<input type="text" name="fee_settings_start_date" class="text-class"
						       id="fee_settings_start_date"
						       value="<?php 
echo  ( isset( $getFeesStartDate ) ? esc_attr( $getFeesStartDate ) : '' ) ;
?>"
						       placeholder="<?php 
esc_attr_e( 'Select start date', 'woocommerce-conditional-product-fees-for-checkout' );
?>">
						<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						<p class="description"
						   style="display:none;"><?php 
esc_html_e( 'Select Start date on which you want to enable the method', 'woocommerce-conditional-product-fees-for-checkout' );
?></p>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row"><label
							for="fee_settings_end_date"><?php 
esc_html_e( 'End Date', 'woocommerce-conditional-product-fees-for-checkout' );
?></label>
					</th>
					<td class="forminp">
						<input type="text" name="fee_settings_end_date" class="text-class" id="fee_settings_end_date"
						       value="<?php 
echo  ( isset( $getFeesEndDate ) ? esc_attr( $getFeesEndDate ) : '' ) ;
?>"
						       placeholder="<?php 
esc_html_e( 'Select end date', 'woocommerce-conditional-product-fees-for-checkout' );
?>">
						<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						<p class="description"
						   style="display:none;"><?php 
esc_html_e( 'Select End date on which you want to disable the method', 'woocommerce-conditional-product-fees-for-checkout' );
?></p>
					</td>
				</tr>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label
							for="fee_settings_select_taxable"><?php 
esc_html_e( 'Is Amount Taxable ?', 'woocommerce-conditional-product-fees-for-checkout' );
?></label>
					</th>
					<td class="forminp">
						<select name="fee_settings_select_taxable" id="fee_settings_select_taxable" class="">
							<option
								value="no" <?php 
echo  ( isset( $getFeesTaxable ) && 'no' === $getFeesTaxable ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
							<option
								value="yes" <?php 
echo  ( isset( $getFeesTaxable ) && 'yes' === $getFeesTaxable ? 'selected="selected"' : '' ) ;
?>><?php 
esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' );
?></option>
						</select>

					</td>
				</tr>
				</tbody>
			</table>
			<div class="sub-title">
				<h2><?php 
esc_html_e( 'Conditional Fee Rule', 'woocommerce-conditional-product-fees-for-checkout' );
?></h2>
				<div class="tap">
					<a id="fee-add-field" class="button button-primary button-large" href="javascript:;">
						<?php 
esc_html_e( '+ Add Row', 'woocommerce-conditional-product-fees-for-checkout' );
?>
					</a>
					<?php 
?>
				</div>
			</div>
			<div class="tap">
				<table id="tbl-product-fee" class="tbl_product_fee table-outer tap-cas form-table product-fee-table">
					<tbody>
					<?php 

if ( isset( $productFeesArray ) && !empty($productFeesArray) ) {
    $i = 2;
    foreach ( $productFeesArray as $key => $productfees ) {
        $fees_conditions = ( isset( $productfees['product_fees_conditions_condition'] ) ? $productfees['product_fees_conditions_condition'] : '' );
        $condition_is = ( isset( $productfees['product_fees_conditions_is'] ) ? $productfees['product_fees_conditions_is'] : '' );
        $condtion_value = ( isset( $productfees['product_fees_conditions_values'] ) ? $productfees['product_fees_conditions_values'] : array() );
        ?>
							<tr id="row_<?php 
        echo  esc_attr( $i ) ;
        ?>" valign="top">
								<th class="titledesc th_product_fees_conditions_condition" scope="row">
									<select rel-id="<?php 
        echo  esc_attr( $i ) ;
        ?>"
									        id="product_fees_conditions_condition_<?php 
        echo  esc_attr( $i ) ;
        ?>"
									        name="fees[product_fees_conditions_condition][]"
									        id="product_fees_conditions_condition"
									        class="product_fees_conditions_condition">
										<optgroup
											label="<?php 
        esc_html_e( 'Location Specific', 'woocommerce-conditional-product-fees-for-checkout' );
        ?>">
											<option
												value="country" <?php 
        echo  ( 'country' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Country', 'woocommerce-conditional-product-fees-for-checkout' );
        ?></option>
											<?php 
        ?>
										</optgroup>
										<optgroup
											label="<?php 
        esc_html_e( 'Product Specific', 'woocommerce-conditional-product-fees-for-checkout' );
        ?>">
											<option
												value="product" <?php 
        echo  ( 'product' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart contains product', 'woocommerce-conditional-product-fees-for-checkout' );
        ?></option>
											<?php 
        ?>
											<option
												value="tag" <?php 
        echo  ( 'tag' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart contains tag\'s product', 'woocommerce-conditional-product-fees-for-checkout' );
        ?></option>
										</optgroup>
										<optgroup
											label="<?php 
        esc_html_e( 'User Specific', 'woocommerce-conditional-product-fees-for-checkout' );
        ?>">
											<option
												value="user" <?php 
        echo  ( 'user' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'User', 'woocommerce-conditional-product-fees-for-checkout' );
        ?></option>
											<?php 
        ?>
										</optgroup>
										<optgroup
											label="<?php 
        esc_html_e( 'Cart Specific', 'woocommerce-conditional-product-fees-for-checkout' );
        ?>">
											<?php 
        $currency_symbol = get_woocommerce_currency_symbol();
        $currency_symbol = ( !empty($currency_symbol) ? '(' . $currency_symbol . ')' : '' );
        ?>
											<option
												value="cart_total" <?php 
        echo  ( 'cart_total' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Cart Subtotal (Before Discount) ', 'woocommerce-conditional-product-fees-for-checkout' );
        echo  esc_html( $currency_symbol ) ;
        ?></option>
											<?php 
        ?>
											<option
												value="quantity" <?php 
        echo  ( 'quantity' === $fees_conditions ? 'selected' : '' ) ;
        ?>><?php 
        esc_html_e( 'Quantity', 'woocommerce-conditional-product-fees-for-checkout' );
        ?></option>
											<?php 
        ?>
										</optgroup>
										<?php 
        ?>
									</select>
								</th>
								<td class="select_condition_for_in_notin">
									<?php 
        
        if ( 'cart_total' === $fees_conditions || 'cart_totalafter' === $fees_conditions || 'quantity' === $fees_conditions || 'weight' === $fees_conditions ) {
            ?>
										<select name="fees[product_fees_conditions_is][]"
										        class="product_fees_conditions_is_<?php 
            echo  esc_attr( $i ) ;
            ?>">
											<option
												value="is_equal_to" <?php 
            echo  ( 'is_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
											<option
												value="less_equal_to" <?php 
            echo  ( 'less_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Less or Equal to ( <= )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
											<option
												value="less_then" <?php 
            echo  ( 'less_then' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Less than ( < )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
											<option
												value="greater_equal_to" <?php 
            echo  ( 'greater_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Greater or Equal to ( >= )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
											<option
												value="greater_then" <?php 
            echo  ( 'greater_then' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Greater than ( > )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
											<option
												value="not_in" <?php 
            echo  ( 'not_in' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
										</select>
									<?php 
        } else {
            ?>
										<select name="fees[product_fees_conditions_is][]"
										        class="product_fees_conditions_is_<?php 
            echo  esc_attr( $i ) ;
            ?>">
											<option
												value="is_equal_to" <?php 
            echo  ( 'is_equal_to' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></option>
											<option
												value="not_in" <?php 
            echo  ( 'not_in' === $condition_is ? 'selected' : '' ) ;
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' );
            ?> </option>
										</select>
									<?php 
        }
        
        ?>
								</td>
								<td class="condition-value" id="column_<?php 
        echo  esc_attr( $i ) ;
        ?>">
									<?php 
        $html = '';
        
        if ( 'country' === $fees_conditions ) {
            $html .= $wcpfc_admin_object->wcpfc_pro_get_country_list( $i, $condtion_value );
        } elseif ( 'product' === $fees_conditions ) {
            $html .= $wcpfc_admin_object->wcpfc_pro_get_product_list( $i, $condtion_value, 'edit' );
        } elseif ( 'tag' === $fees_conditions ) {
            $html .= $wcpfc_admin_object->wcpfc_pro_get_tag_list( $i, $condtion_value );
        } elseif ( 'user' === $fees_conditions ) {
            $html .= $wcpfc_admin_object->wcpfc_pro_get_user_list( $i, $condtion_value );
        } elseif ( 'cart_total' === $fees_conditions ) {
            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . $condtion_value . '">';
        } elseif ( 'quantity' === $fees_conditions ) {
            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
        }
        
        echo  wp_kses( $html, Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ) ;
        ?>
									<input type="hidden" name="condition_key[<?php 
        echo  'value_' . esc_attr( $i ) ;
        ?>]"
									       value="">
								</td>
								<td><a id="fee-delete-field" rel-id="<?php 
        echo  esc_attr( $i ) ;
        ?>" class="delete-row"
								       href="javascript:;" title="Delete"><i class="fa fa-trash"></i></a></td>
							</tr>
							<?php 
        $i++;
    }
    ?>
						<?php 
} else {
    $i = 1;
    ?>
						<tr id="row_1" valign="top">
							<th class="titledesc th_product_fees_conditions_condition" scope="row">
								<select rel-id="1" id="product_fees_conditions_condition_1"
								        name="fees[product_fees_conditions_condition][]"
								        id="product_fees_conditions_condition"
								        class="product_fees_conditions_condition">
									<optgroup
										label="<?php 
    esc_html_e( 'Location Specific', 'woocommerce-conditional-product-fees-for-checkout' );
    ?>">
										<option
											value="country"><?php 
    esc_html_e( 'Country', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
										<?php 
    ?>
									</optgroup>
									<optgroup
										label="<?php 
    esc_html_e( 'Product Specific', 'woocommerce-conditional-product-fees-for-checkout' );
    ?>">
										<option
											value="product"><?php 
    esc_html_e( 'Cart contains product', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
										<?php 
    ?>
										<option
											value="tag"><?php 
    esc_html_e( 'Cart contains tag\'s product', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
									</optgroup>
									<optgroup
										label="<?php 
    esc_html_e( 'User Specific', 'woocommerce-conditional-product-fees-for-checkout' );
    ?>">
										<option
											value="user"><?php 
    esc_html_e( 'User', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
										<?php 
    ?>
									</optgroup>
									<optgroup
										label="<?php 
    esc_html_e( 'Cart Specific', 'woocommerce-conditional-product-fees-for-checkout' );
    ?>">
										<?php 
    $currency_symbol = get_woocommerce_currency_symbol();
    $currency_symbol = ( !empty($currency_symbol) ? '(' . $currency_symbol . ')' : '' );
    ?>
										<option
											value="cart_total"><?php 
    esc_html_e( 'Cart Subtotal (Before Discount) ', 'woocommerce-conditional-product-fees-for-checkout' );
    echo  esc_html( $currency_symbol ) ;
    ?></option>
										<?php 
    ?>
										<option
											value="quantity"><?php 
    esc_html_e( 'Quantity', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
										<?php 
    ?>
									</optgroup>
									<?php 
    ?>
								</select>
							</td>
							<td class="select_condition_for_in_notin">
								<select name="fees[product_fees_conditions_is][]"
								        class="product_fees_conditions_is product_fees_conditions_is_1">
									<option
										value="is_equal_to"><?php 
    esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
									<option
										value="not_in"><?php 
    esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' );
    ?></option>
								</select>
							</td>
							<td id="column_1" class="condition-value">
								<?php 
    echo  wp_kses( $wcpfc_admin_object->wcpfc_pro_get_country_list( 1 ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ) ;
    ?>
								<input type="hidden" name="condition_key[value_1][]" value="">
							</td>
						</tr>
					<?php 
}

?>
					</tbody>
				</table>
				<input type="hidden" name="total_row" id="total_row" value="<?php 
echo  esc_attr( $i ) ;
?>">
			</div>
			
			<?php 
?>
			<p class="submit"><input type="submit" name="submitFee" class="button button-primary button-large"
			                         value="<?php 
echo  esc_attr( $btnValue ) ;
?>"></p>
		</form>
	</div>
</div>

<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php';