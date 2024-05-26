<?php
if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWSBS_VERSION' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Implements helper functions for YITH WooCommerce Subscription
 *
 * @package YITH WooCommerce Subscription
 * @since   1.0.0
 * @author  YITH
 */


if ( ! function_exists( 'ywsbs_get_price_per_string' ) ) {

	/**
	 * Return the days from timestamp
	 *
	 * @param $timestamp int
	 *
	 * @return int
	 * @since 1.0.0
	 */

	function ywsbs_get_price_per_string( $price_per, $time_option ) {
		$price_html = ( ( $price_per == 1 ) ? '' : $price_per ) . ' ';

		switch ( $time_option ) {
			case 'days':
				$price_html .= _n( 'day', 'days', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'weeks':
				$price_html .= _n( 'week', 'weeks', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'months':
				$price_html .= _n( 'month', 'months', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'years':
				$price_html .= _n( 'year', 'years', $price_per, 'yith-woocommerce-subscription' );
				break;
			default:
		}

		return $price_html;
	}
}

if ( ! function_exists( 'ywsbs_get_time_options' ) ) {

	/**
	 * Return the list of time options to add in product editor panel
	 *
	 * @return array
	 * @since 1.0.0
	 */

	function ywsbs_get_time_options() {
		$options = array(
			'days'   => __( 'days', 'yith-woocommerce-subscription' ),
			'months' => __( 'months', 'yith-woocommerce-subscription' ),
		);

		return apply_filters( 'ywsbs_time_options', $options );
	}
}

if ( ! function_exists( 'ywsbs_get_price_time_option_paypal' ) ) {

	/**
	 * Return the list of time options to add in product editor panel
	 *
	 * @return array
	 * @since 1.0.0
	 */

	function ywsbs_get_price_time_option_paypal( $time_option ) {
		$options = array(
			'days'   => 'D',
			'months' => 'M',
		);

		return isset( $options[ $time_option ] ) ? $options[ $time_option ] : '';
	}
}

if ( ! function_exists( 'yith_ywsbs_locate_template' ) ) {
	/**
	 * Locate the templates and return the path of the file found
	 *
	 * @param string $path
	 * @param array  $var
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function yith_ywsbs_locate_template( $path, $var = null ) {

		global $woocommerce;

		if ( function_exists( 'WC' ) ) {
			$woocommerce_base = WC()->template_path();
		} elseif ( defined( 'WC_TEMPLATE_PATH' ) ) {
			$woocommerce_base = WC_TEMPLATE_PATH;
		} else {
			$woocommerce_base = $woocommerce->plugin_path() . '/templates/';
		}

		$template_woocommerce_path = $woocommerce_base . $path;
		$template_path             = '/' . $path;
		$plugin_path               = YITH_YWSBS_DIR . 'templates/' . $path;

		$located = locate_template(
			array(
				$template_woocommerce_path, // Search in <theme>/woocommerce/
				$template_path,             // Search in <theme>/
				$plugin_path,                // Search in <plugin>/templates/
			)
		);

		if ( ! $located && file_exists( $plugin_path ) ) {
			return apply_filters( 'yith_ywsbs_locate_template', $plugin_path, $path );
		}

		return apply_filters( 'yith_ywsbs_locate_template', $located, $path );
	}
}

if ( ! function_exists( 'ywsbs_get_timestamp_from_option' ) ) {

	/**
	 * Add a date to a timestamp
	 *
	 * @param int    $time_from
	 * @param int    $qty
	 * @param string $time_opt
	 *
	 * @return string
	 * @since 1.0.0
	 */

	function ywsbs_get_timestamp_from_option( $time_from, $qty, $time_opt ) {

		$timestamp = 0;
		switch ( $time_opt ) {
			case 'days':
				$timestamp = ywsbs_add_date( $time_from, intval( $qty ) );
				break;
			case 'weeks':
				$timestamp = ywsbs_add_date( $time_from, intval( $qty ) * 7 );
				break;
			case 'months':
				$timestamp = ywsbs_add_date( $time_from, 0, intval( $qty ) );
				break;
			case 'years':
				$timestamp = ywsbs_add_date( $time_from, 0, 0, intval( $qty ) );
				break;
			default:
		}

		return $timestamp;
	}
}

if ( ! function_exists( 'ywsbs_get_paypal_limit_options' ) ) {

	/**
	 * Return the list of time options with the max value that paypal accept
	 *
	 * @return array
	 * @since 1.0.0
	 */

	function ywsbs_get_paypal_limit_options() {
		$options = array(
			'days'   => 90,
			'months' => 24,
		);

		return apply_filters( 'ywsbs_paypal_limit_options', $options );
	}
}

if ( ! function_exists( 'ywsbs_get_price_per_string' ) ) {

	/**
	 * Return the days from timestamp
	 *
	 * @param $price_per
	 * @param $time_option
	 *
	 * @return int
	 * @internal param int $timestamp
	 *
	 * @since    1.0.0
	 */

	function ywsbs_get_price_per_string( $price_per, $time_option ) {
		$price_html = ( ( $price_per == 1 ) ? '' : $price_per ) . ' ';

		switch ( $time_option ) {
			case 'days':
				$price_html .= _n( 'day', 'days', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'weeks':
				$price_html .= _n( 'week', 'weeks', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'months':
				$price_html .= _n( 'month', 'months', $price_per, 'yith-woocommerce-subscription' );
				break;
			default:
		}

		return $price_html;
	}
}


if ( ! function_exists( 'ywsbs_get_max_length_period' ) ) {

	/**
	 * Return the max length of period that can be accepted from paypal
	 *
	 * @return string
	 * @internal param int $time_from
	 * @internal param int $qty
	 * @since    1.0.0
	 */

	function ywsbs_get_max_length_period() {

		$max_length = array(
			'days'   => 90,
			'weeks'  => 52,
			'months' => 24,
			'years'  => 5,
		);

		return apply_filters( 'ywsbs_get_max_length_period', $max_length );

	}
}



if ( ! function_exists( 'ywsbs_validate_max_length' ) ) {

	/**
	 * Return the max length of period that can be accepted from paypal
	 *
	 * @param int    $max_length
	 * @param string $time_opt
	 *
	 * @return int
	 * @since    1.0.0
	 */

	function ywsbs_validate_max_length( $max_length, $time_opt ) {

		$max_lengths = ywsbs_get_max_length_period();
		$max_length  = ( $max_length > $max_lengths[ $time_opt ] ) ? $max_lengths[ $time_opt ] : $max_length;

		return $max_length;
	}
}

if ( ! function_exists( 'ywsbs_get_price_per_string' ) ) {


	/**
	 * @param $price_per
	 * @param $time_option
	 *
	 * @return string
	 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
	 */
	function ywsbs_get_price_per_string( $price_per, $time_option ) {
		$price_html = ( ( $price_per == 1 ) ? '' : $price_per ) . ' ';

		switch ( $time_option ) {
			case 'days':
				$price_html .= _n( 'day', 'days', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'weeks':
				$price_html .= _n( 'week', 'weeks', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'months':
				$price_html .= _n( 'month', 'months', $price_per, 'yith-woocommerce-subscription' );
				break;
			case 'years':
				$price_html .= _n( 'year', 'years', $price_per, 'yith-woocommerce-subscription' );
				break;
			default:
		}

		return $price_html;
	}
}



if ( ! function_exists( 'ywsbs_add_date' ) ) {

	/**
	 * Add day, months or year to a date
	 *
	 * @param int $given_date
	 * @param int $day
	 * @param int $mth
	 * @param int $yr
	 *
	 * @return string
	 * @since 1.2.0
	 */

	function ywsbs_add_date( $given_date, $day = 0, $mth = 0, $yr = 0 ) {
		$new_date = $given_date;
		$new_date = strtotime( '+' . $day . ' days', $new_date );
		$new_date = strtotime( '+' . $mth . ' month', $new_date );
		$new_date = strtotime( '+' . $yr . ' year', $new_date );
		return $new_date;
	}
}


if ( ! function_exists( 'yith_check_privacy_enabled' ) ) {

	/**
	 * Check if the tool for export and erase personal data are enabled
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	function yith_check_privacy_enabled( $wc = false ) {
		global $wp_version;
		$enabled = $wc ? version_compare( WC()->version, '3.4.0', '>=' ) && version_compare( $wp_version, '4.9.5', '>' ) : version_compare( $wp_version, '4.9.5', '>' );
		return apply_filters( 'yith_check_privacy_enabled', $enabled, $wc );
	}
}

if ( ! function_exists( 'ywsbs_get_subscription' ) ) {

	/**
	 * Return the subscription object
	 *
	 * @param int $subscription_id
	 *
	 * @return YWSBS_Subscription
	 * @since 1.0.0
	 */

	function ywsbs_get_subscription( $subscription_id ) {
		return new YWSBS_Subscription( $subscription_id );
	}
}

if ( ! function_exists( 'ywsbs_get_status' ) ) {

	/**
	 * Return the list of status available
	 *
	 * @return array
	 * @since 1.0.0
	 */

	function ywsbs_get_status() {
		$options = array(
			'active'    => __( 'active', 'yith-woocommerce-subscription' ),
			'paused'    => __( 'paused', 'yith-woocommerce-subscription' ),
			'pending'   => __( 'pending', 'yith-woocommerce-subscription' ),
			'overdue'   => __( 'overdue', 'yith-woocommerce-subscription' ),
			'trial'     => __( 'trial', 'yith-woocommerce-subscription' ),
			'cancelled' => __( 'cancelled', 'yith-woocommerce-subscription' ),
			'expired'   => __( 'expired', 'yith-woocommerce-subscription' ),
			'suspended' => __( 'suspended', 'yith-woocommerce-subscription' ),
		);

		return apply_filters( 'ywsbs_status', $options );
	}
}

if ( ! function_exists( 'ywsbs_get_max_failed_attemps_list' ) ) {

	/**
	 * Return the list of max failed attempts for each compatible gateways
	 *
	 * @return array
	 */

	function ywsbs_get_max_failed_attemps_list() {
		$arg = array(
			'paypal'      => 3,
			'yith-stripe' => 4,
		);

		return apply_filters( 'ywsbs_max_failed_attemps_list', $arg );
	}
}

if ( ! function_exists( 'ywsbs_get_num_of_days_between_attemps' ) ) {

	/**
	 * Return the list of max failed attemps for each compatible gateways
	 *
	 * @return array
	 */

	function ywsbs_get_num_of_days_between_attemps() {
		$arg = array(
			'paypal'      => 5,
			'yith-stripe' => 5,
		);

		return apply_filters( 'ywsbs_get_num_of_days_between_attemps', $arg );
	}
}

if ( ! function_exists( 'ywsbs_is_an_order_with_subscription' ) ) {
	/**
	 * Checks if in the order there's a subscription product
	 * returns false if is not an order with subscription or
	 * returns the type of subscription order ( parent|renew )
	 *
	 * @param $order
	 *
	 * @return string|bool
	 * @since 1.2.0
	 */
	function ywsbs_is_an_order_with_subscription( $order ) {

		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		$order_subscription_type = false;
		$subscriptions           = yit_get_prop( $order, 'subscriptions' );
		$is_renew                = yit_get_prop( $order, 'is_renew' );

		if ( $subscriptions ) {
			$order_subscription_type = empty( $is_renew ) ? 'parent' : 'renew';
		}

		return $order_subscription_type;

	}
}


if ( ! function_exists( 'ywsbs_coupon_is_valid' ) ) {

	/**
	 * Check if a coupon is valid.
	 *
	 * @param $coupon WC_Coupon
	 * @param array            $object
	 *
	 * @return bool|WP_Error
	 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
	 * @throws Exception
	 */
	function ywsbs_coupon_is_valid( $coupon, $object = array() ) {
		if ( version_compare( WC()->version, '3.2.0', '>=' ) ) {
			$wc_discounts = new WC_Discounts( $object );
			$valid        = $wc_discounts->is_coupon_valid( $coupon );
			$valid        = is_wp_error( $valid ) ? false : $valid;
		} else {
			$valid = $coupon->is_valid();
		}

		return $valid;
	}
}
