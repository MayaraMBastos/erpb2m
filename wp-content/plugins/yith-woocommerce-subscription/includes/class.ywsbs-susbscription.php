<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWSBS_VERSION' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Implements YWSBS_Subscription Class
 *
 * @class   YWSBS_Subscription
 * @package YITH WooCommerce Subscription
 * @since   1.0.0
 * @author  YITH
 */
if ( ! class_exists( 'YWSBS_Subscription' ) ) {

	class YWSBS_Subscription {



		protected $subscription_meta_data = array(
			'status'                  => 'pending',
			'start_date'              => '',
			'payment_due_date'        => '',
			'expired_date'            => '',
			'cancelled_date'          => '',
			'payed_order_list'        => array(),
			'product_id'              => '',
			'variation_id'            => '',
			'product_name'            => '',
			'quantity'                => '',
			'line_subtotal'           => '',
			'line_total'              => '',
			'line_subtotal_tax'       => '',
			'line_tax'                => '',
			'line_tax_data'           => '',

			'cart_discount'           => '',
			'cart_discount_tax'       => '',

			'order_total'             => '',
			'order_currency'          => '',
			'renew_order'             => 0,

			'prices_include_tax'      => '',

			'payment_method'          => '',
			'payment_method_title'    => '',

			'subscriptions_shippings' => '',

			'price_is_per'            => '',
			'price_time_option'       => '',
			'max_length'              => '',

			'order_ids'               => array(),
			'order_id'                => '',
			'user_id'                 => 0,
			'customer_ip_address'     => '',
			'customer_user_agent'     => '',

			'billing_first_name'      => '',
			'billing_last_name'       => '',
			'billing_company'         => '',
			'billing_address_1'       => '',
			'billing_address_2'       => '',
			'billing_city'            => '',
			'billing_state'           => '',
			'billing_postcode'        => '',
			'billing_country'         => '',
			'billing_email'           => '',
			'billing_phone'           => '',

			'shipping_first_name'     => '',
			'shipping_last_name'      => '',
			'shipping_company'        => '',
			'shipping_address_1'      => '',
			'shipping_address_2'      => '',
			'shipping_city'           => '',
			'shipping_state'          => '',
			'shipping_postcode'       => '',
			'shipping_country'        => '',

			'ywsbs_free_version'      => YITH_YWSBS_VERSION,
		);

		/**
		 * The subscription (post) ID.
		 *
		 * @var int
		 */
		public $id = 0;


		/**
		 * @var string
		 */
		public $price_time_option;

		/**
		 * @var int
		 */
		public $variation_id;

		/**
		 * $post Stores post data
		 *
		 * @var $post WP_Post
		 */
		public $post = null;

		/**
		 * $post Stores post data
		 *
		 * @var string
		 */
		public $status;


		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 */
		public function __construct( $subscription_id = 0, $args = array() ) {
			add_action( 'init', array( $this, 'register_post_type' ) );

			// populate the subscription if $subscription_id is defined
			if ( $subscription_id ) {
				$this->id = $subscription_id;
				$this->populate();
			}

			// add a new subscription if $args is passed
			if ( $subscription_id == '' && ! empty( $args ) ) {
				$this->add_subscription( $args );
			}

		}

		/**
		 * __get function.
		 *
		 * @param string $key
		 *
		 * @return mixed
		 */
		public function __get( $key ) {
			$value = get_post_meta( $this->id, $key, true );

			if ( ! empty( $value ) ) {
				$this->$key = $value;
			}

			return $value;
		}


		/**
		 * set function.
		 *
		 * @param string $property
		 * @param mixed  $value
		 *
		 * @return bool|int
		 */
		public function set( $property, $value ) {

			$this->$property = $value;

			return update_post_meta( $this->id, $property, $value );
		}

		/**
		 * get function.
		 *
		 * @param string $property
		 * @param mixed  $value
		 *
		 * @return bool|int
		 */
		public function get( $property ) {
			return isset( $this->$property ) ? $this->$property : '';
		}

		public function __isset( $key ) {
			if ( ! $this->id ) {
				return false;
			}

			return metadata_exists( 'post', $this->id, $key );
		}

		/**
		 * Populate the subscription
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function populate() {

			$this->post = get_post( $this->id );

			foreach ( $this->get_subscription_meta() as $key => $value ) {
				$this->$key = $value;
			}

			do_action( 'ywsbs_subscription_loaded', $this );
		}

		/**
		 * @param $args
		 *
		 * @return int|WP_Error
		 */
		public function add_subscription( $args ) {

			$subscription_id = wp_insert_post(
				array(
					'post_status' => 'publish',
					'post_type'   => 'ywsbs_subscription',
				)
			);

			if ( $subscription_id ) {
				$this->id = $subscription_id;
				$meta     = apply_filters( 'ywsbs_add_subcription_args', wp_parse_args( $args, $this->get_default_meta_data() ), $this );
				$this->update_subscription_meta( $meta );
				$this->populate();
			}

			return $subscription_id;
		}

		/**
		 * Update post meta in subscription
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 * @return void
		 */
		function update_subscription_meta( $meta ) {
			foreach ( $meta as $key => $value ) {
				update_post_meta( $this->id, $key, $value );
			}
		}

		/**
		 * @param $order_id
		 *
		 * @internal param $subscription_id
		 */
		public function start_subscription( $order_id ) {

			$payed = $this->payed_order_list;

			// do not nothing if this subscription has payed with this order
			if ( ! empty( $payed ) && is_array( $payed ) && in_array( $order_id, $payed ) ) {
				return;
			}

			$payed = empty( $payed ) ? array() : $payed;

			$order       = wc_get_order( $order_id );
			$new_status  = 'active';
			$rates_payed = 1;
			if ( $this->start_date == '' ) {
				$this->set( 'start_date', current_time( 'timestamp' ) );
			}

			if ( $this->payment_due_date == '' ) {
				// Change the next payment_due_date
				$this->set( 'payment_due_date', $this->get_next_payment_due_date( 0, $this->start_date ) );
			}

			if ( $this->expired_date == '' && $this->max_length != '' ) {
				$timestamp = ywsbs_get_timestamp_from_option( current_time( 'timestamp' ), $this->max_length, $this->price_time_option );
				$this->set( 'expired_date', $timestamp );
			}

			$this->set( 'status', $new_status );

			do_action( 'ywsbs_customer_subscription_payment_done_mail', $this );

			$payed[] = $order_id;

			$this->set( 'rates_payed', $rates_payed );
			$this->set( 'payed_order_list', $payed );

		}


		/**
		 * Update the subscription if a payment is done manually from user
		 *
		 * order_id is the id of the last order created
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 * @return void
		 */
		public function update_subscription( $order_id ) {

			$payed = $this->payed_order_list;
			// do not nothing if this subscription has payed with this order
			if ( ! empty( $payed ) && is_array( $payed ) && in_array( $order_id, $payed ) ) {
				return;
			}

			// Change the status to active
			$this->set( 'status', 'active' );

			// Change the next payment_due_date
			$price_is_per      = $this->price_is_per;
			$price_time_option = $this->price_time_option;
			$timestamp         = ywsbs_get_timestamp_from_option( current_time( 'timestamp' ), $price_is_per, $price_time_option );

			$this->set( 'payment_due_date', $timestamp );
			// update _payed_order_list
			$payed[] = $order_id;
			$this->set( 'payed_order_list', $payed );
			$this->set( 'renew_order', 0 );

		}


		/**
		 * @return array
		 * @internal param $subscription_id
		 */
		function get_subscription_meta() {
			$subscription_meta = array();
			foreach ( $this->get_default_meta_data() as $key => $value ) {
				$meta_value                = get_post_meta( $this->id, $key, true );
				$subscription_meta[ $key ] = empty( $meta_value ) ? get_post_meta( $this->id, '_' . $key, true ) : $meta_value;
			}

			return $subscription_meta;
		}

		/**
		 * Return an array of all custom fields subscription
		 *
		 * @return array
		 * @since  1.0.0
		 */
		private function get_default_meta_data() {
			return $this->subscription_meta_data;
		}


		/**
		 * @internal param $subscription_id
		 */
		function cancel_subscription() {

			// Change the status to active

			$this->set( 'status', 'cancelled' );
			$this->set( 'cancelled_date', date( 'Y-m-d H:i:s' ) );

			do_action( 'ywsbs_subscription_cancelled', $this->id );

			// if there's a pending order for this subscription change the status of the order to cancelled
			$order_in_pending = $this->renew_order;
			if ( $order_in_pending ) {
				$order = wc_get_order( $order_in_pending );
				if ( $order ) {
					$order->update_status( 'failed' );
				}
			}

		}

		/**
		 * Return the next payment due date if there are rates not payed
		 *
		 * @param int $trial_period
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 * @return array
		 */
		public function get_next_payment_due_date( $trial_period = 0, $start_date = 0 ) {

			$start_date = ( $start_date ) ? $start_date : current_time( 'timestamp' );
			if ( $this->num_of_rates == '' || ( intval( $this->num_of_rates ) - intval( $this->rates_payed ) ) > 0 ) {
				$payment_due_date = ( $this->payment_due_date == '' ) ? $start_date : $this->payment_due_date;
				if ( $trial_period != 0 ) {
					$timestamp = $start_date + $trial_period;
				} else {
					$timestamp = ywsbs_get_timestamp_from_option( $payment_due_date, $this->price_is_per, $this->price_time_option );
				}

				return $timestamp;
			}

			return false;

		}

		/**
		 * Get the order object.
		 *
		 * @return
		 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
		 */
		public function get_order() {
			$this->order = ( $this->order instanceof WC_Order ) ? $this->order : wc_get_order( $this->_order_id );

			return $this->order;
		}

		/**
		 * Get billing customer email
		 *
		 * @return string
		 */
		public function get_billing_email() {
			$billing_email = ! empty( $this->_billing_email ) ? $this->_billing_email : yit_get_prop( $this->get_order(), '_billing_email' );
			return apply_filters( 'ywsbs_customer_billing_email', $billing_email, $this );
		}

		/**
		 * Get billing customer email
		 *
		 * @return string
		 */
		public function get_billing_phone() {
			$billing_phone = ! empty( $this->_billing_phone ) ? $this->_billing_phone : yit_get_prop( $this->get_order(), '_billing_phone' );
			return apply_filters( 'ywsbs_customer_billing_phone', $billing_phone, $this );
		}

		/**
		 * Get subscription customer billing or shipping fields.
		 *
		 * @param string  $type
		 * @param boolean $no_type
		 *
		 * @return array
		 */
		public function get_address_fields( $type = 'billing', $no_type = false, $prefix = '' ) {

			$fields = array();

			$value_to_check = '_' . $type . '_first_name';
			if ( ! isset( $this->$value_to_check ) ) {
				$fields = $this->get_address_fields_from_order( $type, $no_type, $prefix );
			} else {
				$order       = $this->get_order();
				$meta_fields = $order->get_address( $type );

				foreach ( $meta_fields as $key => $value ) {
					$field_name                     = '_' . $type . '_' . $key;
					$field_key                      = $no_type ? $key : $type . '_' . $key;
					$fields[ $prefix . $field_key ] = $this->$field_name;
				}
			}

			return $fields;
		}

		/**
		 * Return the fields billing or shipping of the parent order
		 *
		 * @param string $type
		 * @param bool   $no_type
		 *
		 * @return array
		 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
		 */
		public function get_address_fields_from_order( $type = 'billing', $no_type = false, $prefix = '' ) {
			$fields = array();
			$order  = $this->get_order();
			if ( $order ) {
				$meta_fields = $order->get_address( $type );

				if ( is_array( $meta_fields ) ) {
					foreach ( $meta_fields as $key => $value ) {
						$field_key                      = $no_type ? $key : $type . '_' . $key;
						$fields[ $prefix . $field_key ] = $value;
					}
				}
			}

			return $fields;
		}

		/**
		 * Return if the subscription can be cancelled by user
		 *
		 * @return  bool
		 * @since   1.0.0
		 */
		public function can_be_cancelled() {
			$status = array( 'pending', 'cancelled' );

			// the administrator and shop manager can switch the status to cancelled
			$post_type_object = get_post_type_object( YITH_WC_Subscription()->post_name );
			if ( current_user_can( $post_type_object->cap->delete_post, $this->ID ) ) {
				$return = true;
			} elseif ( ! in_array( $this->status, $status ) && get_option( 'ywsbs_allow_customer_cancel_subscription' ) == 'yes' ) {
				$return = true;
			} else {
				$return = false;
			}

			return apply_filters( 'ywsbs_can_be_cancelled', $return, $this );
		}

		/**
		 * Return if the subscription can be reactivate by user
		 *
		 * @return  bool
		 * @since   1.0.0
		 */
		public function can_be_create_a_renew_order() {
			$status = array( 'pending', 'expired' );

			// exit if no valid subscription status
			if ( in_array( $this->status, $status ) || $this->payment_due_date == $this->expired_date ) {
				return false;
			}

			// check if the subscription have a renew order
			$renew_order = $this->has_a_renew_order();

			// if order doesn't exist, or is cancelled, we create order
			if ( ! $renew_order || ( $renew_order && ( $renew_order->get_status() == 'cancelled' ) ) ) {
				$result = true;
			} // otherwise we return order id
			else {
				$result = yit_get_order_id( $renew_order );
			}

			return apply_filters( 'ywsbs_can_be_create_a_renew_order', $result, $this );
		}

		/**
		 * Return the renew order if exists
		 *
		 * @return  bool|WC_Order
		 * @since   1.1.5
		 */
		public function has_a_renew_order() {

			$return         = false;
			$renew_order_id = $this->renew_order;

			if ( $renew_order_id ) {
				$order            = wc_get_order( $renew_order_id );
				$order && $return = $order;
			}

			return $return;
		}

		/**
		 * Add failed attemp
		 *
		 * @param bool $attempts
		 * @param bool $latest_attemp if is the last attemp doesn't send email
		 *
		 * @since  1.1.3
		 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
		 */
		public function register_failed_attemp( $attempts = false, $latest_attemp = false ) {

			$order = wc_get_order( $this->order_id );

			if ( false === $attempts ) {
				$failed_attemp = yit_get_prop( $order, 'failed_attemps' );
				$attempts      = intval( $failed_attemp ) + 1;
			}

			if ( ! $latest_attemp ) {
				yit_save_prop( $order, 'failed_attemps', $attempts, false, true );
				do_action( 'ywsbs_customer_subscription_payment_failed_mail', $this );
			}
		}


	}




}

