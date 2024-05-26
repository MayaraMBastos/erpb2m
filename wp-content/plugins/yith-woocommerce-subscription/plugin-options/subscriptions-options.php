<?php

$section = array(
	'subscription_section'     => array(
		'name' => '',
		'type' => 'title',
		'id'   => 'ywsbs_subscription_section',
	),
	'subscription_list_table'  => array(
		'type'                 => 'yith-field',
		'yith-type'            => 'list-table',
		'post_type'            => 'ywsbs_subscription',
		'class'                => 'ywdpd_discount_table',
		'list_table_class'     => 'YITH_YWSBS_Subscriptions_List_Table',
		'list_table_class_dir' => YITH_YWSBS_INIT . 'admin/class.ywsbs-subscriptions-list-table.php',
		'title'                => __( 'Subscriptions', 'yith-woocommerce-subscription' ),
		'search_form'          => array(
			'text'     => 'Search rule',
			'input_id' => 'rule',
		),
		'id'                   => 'ywsbs_subscription_table',
	),
	'subscription_section_end' => array(
		'type' => 'sectionend',
		'id'   => 'ywsbs_subscription_section_end',
	),
);


return apply_filters( 'ywsbs_subscriptions_options', array( 'subscriptions' => $section ) );
