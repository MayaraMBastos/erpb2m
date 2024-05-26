<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wp;
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
if ( is_network_admin() ) {
	$admin_url = admin_url( 'admin.php' );
} else {
	$admin_url = network_admin_url( 'admin.php' );
}
$wcpfc_admin_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( '', '' );
$wcpf_action        = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
$request_post_id    = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
$get_paged          = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
$get_wpnonce        = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
$retrieved_nonce    = isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '';
$wcpfcnonce         = wp_create_nonce( 'wcpfcnonce' );
if ( isset( $wcpf_action ) && 'delete' === $wcpf_action ) {
	if ( ! wp_verify_nonce( $retrieved_nonce, 'wcpfcnonce' ) ) {
		die( 'Failed security check' );
	}
	$request_post_id = isset( $request_post_id ) ? sanitize_text_field( $request_post_id ) : 0;
	$admin_urls      = add_query_arg( array( 'page' => 'wcpfc-pro-list', 'delete' => 'true' ), $admin_url );
	wp_delete_post( $request_post_id );
	wp_redirect( $admin_urls );
	exit;
}
$get_all_fees_args  = array(
	'post_type'      => 'wc_conditional_fee',
	'order'          => 'DESC',
	'posts_per_page' => 10,
	'orderby'        => 'ID',
	'paged'          => $get_paged,
);
$get_all_fees_query = new WP_Query( $get_all_fees_args );
$get_all_fees       = $get_all_fees_query->get_posts();
$get_all_fees_count = $get_all_fees_query->found_posts;
?>
	<div class="wcpfc-section-left">
		<div class="wcpfc-main-table res-cl">
			<?php
			wp_nonce_field( 'multiple_delete_conditional_fee_action', 'multiple_delete_conditional_fee' );
			wp_nonce_field( 'sorting_conditional_fee_action', 'sorting_conditional_fee' );
			wp_nonce_field( 'multiple_disable_enable_conditional_fee_action', 'multiple_disable_enable_conditional_fee' );
			?>
			<div class="product_header_title">
				<h2>
					<?php esc_html_e( 'Product Fees', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
					<a class="add-new-btn"
					   href="<?php echo esc_url( add_query_arg( array( 'page' => 'wcpfc-pro-add-new' ), $admin_url ) ); ?>"><?php esc_html_e( 'Add Product Fees', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
					<?php if ( ! isset( $get_all_fees ) || $get_all_fees_count !== 0 ) { ?>
						<a id="detete-conditional-fee"
						   class="detete-conditional-fee wcpfc-button"><?php esc_html_e( 'Delete (Selected)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
						<a id="disable-conditional-fee"
						   class="disable-enable-conditional-fee wcpfc-button"><?php esc_html_e( 'Disable', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
						<a id="enable-conditional-fee"
						   class="disable-enable-conditional-fee wcpfc-button"><?php esc_html_e( 'Enable', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
					<?php } ?>
				</h2>
			</div>
			<div class="wcpfc-conditional-fee-listing-section">
				<table id="conditional-fee-listing" class="table-outer form-table conditional-fee-listing">
					<!--tablesorter -->
					<thead>
					<tr class="wcpfc-head">
						<th><input type="checkbox" name="check_all" class="condition-check-all"></th>
						<th><?php esc_html_e( 'Name', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
						<th><?php esc_html_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
						<th><?php esc_html_e( 'Status', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
						<th><?php esc_html_e( 'Action', 'woocommerce-conditional-product-fees-for-checkout' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ( $get_all_fees_count > 0 ) {
						$sort_order   = array();
						$getSortOrder = get_option( 'sm_sortable_order' );
						if ( isset( $getSortOrder ) && ! empty( $getSortOrder ) ) {
							foreach ( $getSortOrder as $sort ) {
								$sort_order[ $sort ] = array();
							}
						}
						foreach ( $get_all_fees as $carrier_id => $carrier ) {
							$carrier_name = $carrier->ID;
							if ( array_key_exists( $carrier_name, $sort_order ) ) {
								$sort_order[ $carrier_name ][ $carrier_id ] = $get_all_fees[ $carrier_id ];
								unset( $get_all_fees[ $carrier_id ] );
							}
						}
						foreach ( $sort_order as $carriers ) {
							$get_all_fees = array_merge( $get_all_fees, $carriers );
						}
						$i = 1;
						foreach ( $get_all_fees as $fees ) {
							$get_fees_id         = $fees->ID;
							$get_fees_menu_order = $fees->menu_order;
							$condition_title     = get_the_title( $get_fees_id ) ? get_the_title( $get_fees_id ) : 'Fee';
							$get_fee_type        = get_post_meta( $get_fees_id, 'fee_settings_select_fee_type', true );
							$get_fee_type        = ( isset( $get_fee_type ) && ! empty( $get_fee_type ) ) ? $get_fee_type : '';
							$getFeesCost         = get_post_meta( $get_fees_id, 'fee_settings_product_cost', true );
							$getFeesStatus       = get_post_status( $get_fees_id );
							$fees_status_chk     = ( ( ! empty( $getFeesStatus ) && 'publish' === $getFeesStatus ) || empty( $getFeesStatus ) ) ? 'checked' : '';
							?>
							<tr id="<?php echo esc_attr( $get_fees_id ); ?>"
							    data-menu_order="<?php echo esc_attr( $get_fees_menu_order ); ?>">
								<td width="10%">
									<input type="checkbox" name="multiple_delete_fee[]" class="multiple_delete_fee"
									       value="<?php echo esc_attr( $get_fees_id ) ?>">
								</td>
								<td>
									<a href="<?php echo esc_url( add_query_arg( array(
										'page'     => 'wcpfc-pro-edit-fee',
										'id'       => esc_attr( $get_fees_id ),
										'action'   => 'edit',
										'_wpnonce' => esc_attr( $wcpfcnonce ),
									), $admin_url ) ); ?>"><?php echo esc_html( $condition_title, 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
								</td>
								<td>
									<?php
									if ( 'percentage' === $get_fee_type ) {
										echo esc_html( $getFeesCost ) . ' %';
									} else {
										echo esc_html( get_woocommerce_currency_symbol() ) . '&nbsp;' . esc_html( $getFeesCost );
									}
									?>
								</td>
								<td>
									<label class="switch">
										<input type="checkbox" name="fee_settings_status" id="fees_status_id"
										       value="on" <?php echo esc_attr( $fees_status_chk ); ?>
										       data-smid="<?php echo esc_attr( $get_fees_id ); ?>">
										<div class="slider round"></div>
									</label>
								</td>
								<td>
									<a class="fee-action-button button-primary"
									   href="<?php echo esc_url( add_query_arg( array(
										   'page'     => 'wcpfc-pro-edit-fee',
										   'id'       => esc_attr( $get_fees_id ),
										   'action'   => 'edit',
										   '_wpnonce' => esc_attr( $wcpfcnonce ),
									   ), $admin_url ) ); ?>"><?php esc_html_e( 'Edit', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
									<a class="fee-action-button button-primary"
									   href="<?php echo esc_url( add_query_arg( array(
										   'page'     => 'wcpfc-pro-list',
										   'id'       => esc_attr( $get_fees_id ),
										   'action'   => 'delete',
										   '_wpnonce' => esc_attr( $wcpfcnonce ),
									   ), $admin_url ) ); ?>"><?php esc_html_e( 'Delete', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
									<a class="fee-action-button button-primary" href="javascript:void(0);"
									   id="clone_fees"
									   data-attr="<?php echo esc_attr( $get_fees_id ); ?>"><?php esc_html_e( 'Clone', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
								</td>
							</tr>
							<?php
							$i ++;
						}
					}
					wp_reset_postdata();
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
		$page_links = paginate_links(
			array(
				'base'      => add_query_arg( 'paged', '%#%' ),
				'format'    => '',
				'prev_text' => __( '&laquo;' ),
				'next_text' => __( '&raquo;' ),
				'total'     => ceil( $get_all_fees_count / 10 ),
				'current'   => $get_paged,
			)
		);
		if ( $page_links ) {
			?>
			<div class='tablenav-pages'>
				<?php
				echo wp_kses( $page_links, array( 'a' => array( 'href' => array(), 'class' => array( '' ) ) ) );
				?>
			</div>
			<?php
		}
		?>
		<div class="wcpfc-mastersettings">
			<div class="mastersettings-title">
				<h2><?php esc_html_e( 'Master Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
			</div>
			<?php
			$chk_enable_logging         = get_option( 'chk_enable_logging' );
			$chk_enable_logging_checked = ( ( ! empty( $chk_enable_logging ) && 'on' === $chk_enable_logging ) || empty( $chk_enable_logging ) ) ? 'checked' : '';
			?>
			<table class="table-mastersettings table-outer" cellpadding="0" cellspacing="0">
				<tbody>
				<tr valign="top" id="enable_logging">
					<td class="table-whattodo"><?php esc_html_e( 'Enable Logging', 'woocommerce-conditional-product-fees-for-checkout' ); ?></td>
					<td>
						<input type="checkbox" name="chk_enable_logging" id="chk_enable_logging"
						       value="on" <?php echo esc_attr( $chk_enable_logging_checked ); ?>>
					</td>
				</tr>
				<tr>
					<td colspan="2">
                        <span class="button-primary" id="save_master_settings"
                              name="save_master_settings"><?php esc_html_e( 'Save Master Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>