<?php

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_YWSBS_VERSION' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Implements YWSBS_Subscription_Cron Class
 *
 * @class   YWSBS_Subscription_Cron
 * @package YITH WooCommerce Subscription
 * @since   1.0.0
 * @author  YITH
 */
if ( ! class_exists( 'YWSBS_Subscription_Cron' ) ) {

	class YWSBS_Subscription_Cron {

		/**
		 * Single instance of the class
		 *
		 * @var \YWSBS_Subscription_Cron
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWSBS_Subscription_Cron
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 * @author Emanuela Castorina
		 */
		public function __construct() {

			add_action( 'wp_loaded', array( $this, 'set_cron' ), 30 );

			if ( yith_check_privacy_enabled( true ) ) {
				add_action( 'ywsbs_trash_pending_subscriptions', array( $this, 'ywsbs_trash_pending_subscriptions' ) );
				add_action( 'ywsbs_trash_cancelled_subscriptions', array( $this, 'ywsbs_trash_cancelled_subscriptions' ) );
			}
		}


		public function set_cron() {

			$ve         = get_option( 'gmt_offset' ) > 0 ? '+' : '-';
			$time_start = strtotime( '00:00 ' . $ve . get_option( 'gmt_offset' ) . ' HOURS' );

			if ( ! wp_next_scheduled( 'ywsbs_renew_orders' ) ) {
				wp_schedule_event( $time_start, 'hourly', 'ywsbs_renew_orders' );
			}

			if ( yith_check_privacy_enabled( true ) ) {
				$trash_pending = get_option( 'ywsbs_trash_pending_subscriptions' );
				if ( isset( $trash_pending['number'] ) && ! empty( $trash_pending['number'] ) && ! wp_next_scheduled( 'ywsbs_trash_cancelled_subscriptions' ) ) {
					wp_schedule_event( $time_start, 'daily', 'ywsbs_trash_pending_subscriptions' );
				}

				$trash_cancelled = get_option( 'ywsbs_trash_cancelled_subscriptions' );
				if ( isset( $trash_cancelled['number'] ) && ! empty( $trash_cancelled['number'] ) && ! wp_next_scheduled( 'ywsbs_trash_cancelled_subscriptions' ) ) {
					wp_schedule_event( $time_start, 'daily', 'ywsbs_trash_cancelled_subscriptions' );
				}
			}
		}


		/**
		 * Renew Order
		 *
		 * Create new order for active or in trial period subscription
		 *
		 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
		 */
		public function renew_orders() {

			global $wpdb;

			$to = current_time( 'timestamp' ) + 86400;

			$query = $wpdb->prepare(
				"SELECT ywsbs_p.ID FROM {$wpdb->prefix}posts as ywsbs_p
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm ON ( ywsbs_p.ID = ywsbs_pm.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm2 ON ( ywsbs_p.ID = ywsbs_pm2.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm3 ON ( ywsbs_p.ID = ywsbs_pm3.post_id )
                 WHERE ( ywsbs_pm.meta_key='status' AND  ywsbs_pm.meta_value = 'active' )
                 AND ( ywsbs_pm3.meta_key='renew_order' AND  ywsbs_pm3.meta_value = 0 ) OR ( ywsbs_pm3.meta_key='_renew_order' AND  ywsbs_pm3.meta_value = 0 )
                 AND ywsbs_p.post_type = %s
                 AND ywsbs_p.post_status = 'publish'
                 AND ( ywsbs_pm2.meta_key='payment_due_date' AND  ( ywsbs_pm2.meta_value  < $to ) )
                 GROUP BY ywsbs_p.ID ORDER BY ywsbs_p.ID DESC
                ",
				'ywsbs_subscription'
			);

			$subscriptions = $wpdb->get_results( $query );
			if ( ! empty( $subscriptions ) ) {
				foreach ( $subscriptions as $subscription ) {
					$sbs           = ywsbs_get_subscription( $subscription->ID );
					$order_pending = $sbs->get( 'renew_order' );
					if ( $order_pending == 0 ) {
						$order_id = YWSBS_Subscription_Order()->renew_order( $subscription->ID );
						update_post_meta( $subscription->ID, 'renew_order', $order_id );
						delete_post_meta( $subscription->ID, '_renew_order' );
					}
				}
			}
		}

		/**
		 * Trash pending subscriptions after a specific time.
		 *
		 * @since 1.4.0
		 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
		 */
		public function ywsbs_trash_pending_subscriptions() {
			global $wpdb;
			$trash_pending = get_option( 'ywsbs_trash_pending_subscriptions' );
			if ( ! isset( $trash_pending['number'] ) || empty( $trash_pending['number'] ) ) {
				return;
			}

			$time = strtotime( '-' . $trash_pending['number'] . ' ' . $trash_pending['unit'] );

			$subscriptions = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT ywsbs_p.ID FROM {$wpdb->prefix}posts as ywsbs_p
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm ON ( ywsbs_p.ID = ywsbs_pm.post_id )
                 WHERE ( ywsbs_pm.meta_key='status' AND  ywsbs_pm.meta_value = 'pending' )
                 AND ywsbs_p.post_type = %s
                 AND ywsbs_p.post_status = 'publish'
                 AND ywsbs_p.post_date < %s 
                 GROUP BY ywsbs_p.ID ORDER BY ywsbs_p.ID DESC
                ",
					'ywsbs_subscription',
					date( 'Y-m-d H:i:s', $time )
				)
			);

			if ( ! empty( $subscriptions ) ) {
				foreach ( $subscriptions as $subscription ) {
					$subscription_id = $subscription->ID;
					wp_trash_post( $subscription_id );
					do_action( 'ywsbs_subscription_trashed', $subscription_id );
				}
			}

		}

		/**
		 * Trash cancelled subscriptions after a specific time.
		 *
		 * @since 1.4.0
		 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
		 */
		public function ywsbs_trash_cancelled_subscriptions() {
			global $wpdb;
			$trash_cancelled = get_option( 'ywsbs_trash_cancelled_subscriptions' );
			if ( ! isset( $trash_cancelled['number'] ) || empty( $trash_cancelled['number'] ) ) {
				return;
			}

			$time = strtotime( '-' . $trash_cancelled['number'] . ' ' . $trash_cancelled['unit'] );

			$subscriptions = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT ywsbs_p.ID FROM {$wpdb->prefix}posts as ywsbs_p
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm ON ( ywsbs_p.ID = ywsbs_pm.post_id )
                 INNER JOIN  {$wpdb->prefix}postmeta as ywsbs_pm2 ON ( ywsbs_p.ID = ywsbs_pm2.post_id )
                 WHERE ( ywsbs_pm.meta_key='status' AND  ywsbs_pm.meta_value = 'cancelled' )
                 AND ywsbs_p.post_type = %s
                 AND ywsbs_p.post_status = 'publish'
                 AND ( ywsbs_pm2.meta_key='cancelled_date' AND  ywsbs_pm2.meta_value  < %d )
                 GROUP BY ywsbs_p.ID ORDER BY ywsbs_p.ID DESC
                ",
					'ywsbs_subscription',
					$time
				)
			);

			if ( ! empty( $subscriptions ) ) {
				foreach ( $subscriptions as $subscription ) {
					$subscription_id = $subscription->ID;
					wp_trash_post( $subscription_id );
					do_action( 'ywsbs_subscription_trashed', $subscription_id );
					YITH_WC_Activity()->add_activity( $subscription_id, 'trashed', 'success', 0, __( 'The subscription was been trashed after the specific duration because was in cancelled status.', 'yith-woocommerce-subscription' ) );
				}
			}

		}
	}
}

/**
 * Unique access to instance of YWSBS_Subscription_Cron class
 *
 * @return \YWSBS_Subscription_Cron
 */
function YWSBS_Subscription_Cron() {
	return YWSBS_Subscription_Cron::get_instance();
}

