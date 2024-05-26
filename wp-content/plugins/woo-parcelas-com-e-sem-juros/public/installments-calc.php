<?php
/**
* Do installments calculation
*/
if(!defined('ABSPATH')){
	exit;
}

$prefix         = $this->settings['installment_prefix'];
$installments   = $this->settings['installment_qty'];
$suffix         = $this->settings['installment_suffix'];
$min_value      = isset($this->settings['installment_minimum_value']) ? str_replace(',', '.', $this->settings['installment_minimum_value']) : 0;
$tipo_pagamento = $this->settings['tipo_pagamento']; 

///$fator_MP = array(1,2.39,4.78,7.17,9.15,10.72,12.31,13.92,15.54,17.17,18.82,20.48);
$fator_MP = array(1,1.0239,1.0478,1.0717,1.0899,1.1049,1.1183,1.1249,1.1321,1.1459,1.1600,1.1741);
$fator = array(1,0.52255,0.35347,0.26898,0.21830,0.18453,0.16044,0.14240,0.12838,0.11717,0.10802,0.10040);


if('variable' == $product->get_type() && $tipo_pagamento == 0){
/**
* Deal with installments in single variable product
* that has children with different prices
*/
if($product->get_variation_price('min') != $product->get_variation_price('max')){
/**
* Change prefix name for variable product
*
* @var     string  $prefix
*/
$prefix = apply_filters('fswp_variable_with_different_prices_prefix', __('A partir de', 'woo-parcelas-com-e-sem-juros'));   
}

if(is_product()){
/**
* Calculate and display installmentes for each child in variation product
*/
add_action('woocommerce_before_single_variation', array($this, 'fswp_variable_installment_calculation'), 99);
}
}

else if('variable' == $product->get_type() && $tipo_pagamento == 1){
/**
* Deal with installments in single variable product
* that has children with different prices (PagSeguro)
*/
if($product->get_variation_price('min') != $product->get_variation_price('max')){
/**
* Change prefix name for variable product
*
* @var     string  $prefix
*/
$prefix = apply_filters('fswp_variable_with_different_prices_prefix', __('A partir de', 'woo-parcelas-com-e-sem-juros'));   
}

if(is_product()){
/**
* Calculate and display installmentes for each child in variation product
*/
add_action('woocommerce_before_single_variation', array($this, 'fswp_variable_installment_calculation_PS'), 99);
}
}

else if('variable' == $product->get_type() && $tipo_pagamento == 2){
/**
* Deal with installments in single variable product
* that has children with different prices (MercadoPago)
*/
if($product->get_variation_price('min') != $product->get_variation_price('max')){
/**
* Change prefix name for variable product
*
* @var     string  $prefix
*/
$prefix = apply_filters('fswp_variable_with_different_prices_prefix', __('A partir de', 'woo-parcelas-com-e-sem-juros'));   
}

if(is_product()){
/**
* Calculate and display installmentes for each child in variation product
*/
add_action('woocommerce_before_single_variation', array($this, 'fswp_variable_installment_calculation_MP'), 99);
}
}
else if('grouped' == $product->get_type()){
/**
* Change prefix name for grouped product
*
* @var     string  $prefix
*/
$prefix = apply_filters('fswp_grouped_prefix', __('A partir de', 'woo-parcelas-com-e-sem-juros'));   
}

/**
* Get product final price
*
* @var     string  $price
*/
$price = wc_get_price_including_tax($product);

if($price <= $min_value){
	$installments_html = '';
}
else if ($tipo_pagamento == 1){
	if($price > $min_value){
		$installments_price = $price*($fator[$installments-1]);
		$formatted_installments_price = wc_price($price *$fator[$installments-1]);

		if($installments_price < $min_value){
			while($installments > 2 && $installments_price < $min_value){
				$installments--;
				$installments_price = $price*($fator[$installments-1]);
				$formatted_installments_price = wc_price($price *$fator[$installments-1]);
			}

			if($installments_price >= $min_value){
				$installments_html  = "<div class='fswp_installments_price $class'>";
				$installments_html .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_installment_prefix">%s %sx de</span> ', 'woo-parcelas-com-e-sem-juros'), $prefix, $installments).$formatted_installments_price." <span class='fswp_installment_suffix'>".$suffix."</span></p>";
				$installments_html .= "</div>";                    
			}
			else{
				$installments_html = '';
			}
		}
		else{
			$installments_html  = "<div class='fswp_installments_price $class'>";
			$installments_html .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_installment_prefix">%s %sx de </span>', 'woo-parcelas-com-e-sem-juros'), $prefix, $installments).$formatted_installments_price." <span class='fswp_installment_suffix'>".$suffix."</span></p>";
			$installments_html .= "</div>";
		}      
	}
}
else if ($tipo_pagamento == 2){
	if($price > $min_value){
		$installments_price = ($price*$fator_MP[$installments-1]) / $installments;
		$formatted_installments_price = wc_price(($price*$fator_MP[$installments-1]) / $installments);

		if($installments_price < $min_value){
			while($installments > 2 && $installments_price < $min_value){
				$installments--;
				$installments_price = ($price*$fator_MP[$installments-1]) / $installments;
				$formatted_installments_price = wc_price(($price*$fator_MP[$installments-1]) / $installments);
			}

			if($installments_price >= $min_value){
				$installments_html  = "<div class='fswp_installments_price $class'>";
				$installments_html .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_installment_prefix">%s %sx de</span> ', 'woo-parcelas-com-e-sem-juros'), $prefix, $installments).$formatted_installments_price." <span class='fswp_installment_suffix'>".$suffix."</span></p>";
				$installments_html .= "</div>";                    
			}
			else{
				$installments_html = '';
			}
		}
		else{
			$installments_html  = "<div class='fswp_installments_price $class'>";
			$installments_html .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_installment_prefix">%s %sx de </span>', 'woo-parcelas-com-e-sem-juros'), $prefix, $installments).$formatted_installments_price." <span class='fswp_installment_suffix'>".$suffix."</span></p>";
			$installments_html .= "</div>";
		}      
	}
}
else{
	if($price > $min_value){
		$installments_price = $price / $installments;
		$formatted_installments_price = wc_price($price / $installments);

		if($installments_price < $min_value){
			while($installments > 2 && $installments_price < $min_value){
				$installments--;
				$installments_price = $price / $installments;
				$formatted_installments_price = wc_price($price / $installments);
			}

			if($installments_price >= $min_value){
				$installments_html  = "<div class='fswp_installments_price $class'>";
				$installments_html .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_installment_prefix">%s %sx de</span> ', 'woo-parcelas-com-e-sem-juros'), $prefix, $installments).$formatted_installments_price." <span class='fswp_installment_suffix'>".$suffix."</span></p>";
				$installments_html .= "</div>";                    
			}
			else{
				$installments_html = '';
			}
		}
		else{
			$installments_html  = "<div class='fswp_installments_price $class'>";
			$installments_html .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_installment_prefix">%s %sx de </span>', 'woo-parcelas-com-e-sem-juros'), $prefix, $installments).$formatted_installments_price." <span class='fswp_installment_suffix'>".$suffix."</span></p>";
			$installments_html .= "</div>";
		}      
	}	
}
echo apply_filters('fswp_installments_calc_output', $installments_html, $prefix, $installments, $formatted_installments_price, $suffix);