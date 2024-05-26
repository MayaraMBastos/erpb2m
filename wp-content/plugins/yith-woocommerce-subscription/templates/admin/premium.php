<style>
	.section {
		margin-left: -20px;
		margin-right: -20px;
		font-family: "Raleway", san-serif;
		background-repeat: no-repeat;
	}

	.section h1 {
		text-align: center;
		text-transform: uppercase;
		color: #808a97;
		font-size: 35px;
		font-weight: 700;
		line-height: normal;
		display: inline-block;
		width: 100%;
		margin: 50px 0 0;
	}

	.section ul {
		list-style-type: disc;
		padding-left: 15px;
	}

	.section-even {
		background-color: #fff;
		background-position: 85% 100%;
	}

	.section:nth-child(odd) {
		background-color: #f1f1f1;
		background-position: 15% 100%;
	}

	.section .section-title img {
		display: table-cell;
		vertical-align: middle;
		width: auto;
		margin-right: 15px;
	}

	.section h2,
	.section h3 {
		display: inline-block;
		vertical-align: middle;
		padding: 0;
		font-size: 24px;
		font-weight: 700;
		color: #808a97;
		text-transform: uppercase;
	}

	.section .section-title h2 {
		display: table-cell;
		vertical-align: middle;
		line-height: 25px;
	}

	.section-title {
		display: table;
	}

	.section h3 {
		font-size: 14px;
		line-height: 28px;
		margin-bottom: 0;
		display: block;
	}

	.section p {
		font-size: 13px;
		margin: 25px 0;
	}

	.section ul li {
		margin-bottom: 4px;
	}

	.landing-container {
		max-width: 750px;
		margin-left: auto;
		margin-right: auto;
		padding: 50px 0 30px;
	}

	.landing-container:after {
		display: block;
		clear: both;
		content: '';
	}

	.landing-container .col-1,
	.landing-container .col-2 {
		float: left;
		box-sizing: border-box;
		padding: 0 15px;
	}

	.landing-container .col-1 img {
		width: 100%;
	}

	.landing-container .col-1 {
		width: 55%;
	}

	.landing-container .col-2 {
		width: 45%;
	}

	.premium-cta {
		background-color: #808a97;
		color: #fff;
		border-radius: 6px;
		padding: 20px 15px;
	}

	.premium-cta:after {
		content: '';
		display: block;
		clear: both;
	}

	.premium-cta p {
		margin: 7px 0;
		font-size: 14px;
		font-weight: 500;
		display: inline-block;
		width: 60%;
	}

	.premium-cta a.button {
		border-radius: 6px;
		height: 60px;
		float: right;
		background: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
		border-color: #ff643f;
		box-shadow: none;
		outline: none;
		color: #fff;
		position: relative;
		padding: 9px 50px 9px 70px;
	}

	.premium-cta a.button:hover,
	.premium-cta a.button:active,
	.premium-cta a.button:focus {
		color: #fff;
		background: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
		border-color: #971d00;
		box-shadow: none;
		outline: none;
	}

	.premium-cta a.button:focus {
		top: 1px;
	}

	.premium-cta a.button span {
		line-height: 13px;
	}

	.premium-cta a.button .highlight {
		display: block;
		font-size: 20px;
		font-weight: 700;
		line-height: 20px;
	}

	.premium-cta .highlight {
		text-transform: uppercase;
		background: none;
		font-weight: 800;
		color: #fff;
	}

	.section.one {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/01-bg.png);
	}

	.section.two {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/02-bg.png);
	}

	.section.three {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/03-bg.png);
	}

	.section.four {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/04-bg.png);
	}

	.section.five {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/05-bg.png);
	}

	.section.six {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/06-bg.png);
	}

	.section.seven {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/07-bg.png);
	}

	.section.eight {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/08-bg.png);
	}

	.section.nine {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/09-bg.png);
	}

	.section.ten {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/10-bg.png);
	}

	.section.eleven {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/11-bg.png);
	}

	.section.twelve {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/12-bg.png);
	}

	.section.thirteen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/13-bg.png);
	}

	.section.fourteen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/14-bg.png);
	}

	.section.fifteen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/15-bg.png);
	}

	.section.sixteen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/16-bg.png);
	}

	.section.seventeen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/17-bg.png);
	}

	.section.eighteen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/18-bg.png);
	}

	.section.nineteen {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/19-bg.png);
	}

	.section.twenty {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/20-bg.png);
	}

	.section.twenty-one {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/21-bg.png);
	}

	.section.twenty-two {
		background-image: url(<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/22-bg.png);
	}

	@media (max-width: 768px) {
		.section {
			margin: 0
		}

		.premium-cta p {
			width: 100%;
		}

		.premium-cta {
			text-align: center;
		}

		.premium-cta a.button {
			float: none;
		}
	}

	@media (max-width: 480px) {
		.wrap {
			margin-right: 0;
		}

		.section {
			margin: 0;
		}

		.landing-container .col-1,
		.landing-container .col-2 {
			width: 100%;
			padding: 0 15px;
		}

		.section-odd .col-1 {
			float: left;
			margin-right: -100%;
		}

		.section-odd .col-2 {
			float: right;
			margin-top: 65%;
		}
	}

	@media (max-width: 320px) {
		.premium-cta a.button {
			padding: 9px 20px 9px 70px;
		}

		.section .section-title img {
			display: none;
		}
	}
</style>
<div class="landing">
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php echo sprintf( esc_html( __( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Subscription%2$s to benefit from all features!', 'yith-woocommerce-subscription' ) ), '<span class="highlight">', '</span>' ); ?>
				</p>
				<a href="<?php echo esc_url( $this->get_premium_landing_uri() ); ?>" target="_blank"
					class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-subscription' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-subscription' ); ?></span>
				</a>
			</div>
		</div>
	</div>
	<div class="one section section-even clear">
		<h1><?php esc_html_e( 'Premium Features', 'yith-woocommerce-subscription' ); ?></h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/01.png" alt="Feature 01"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Support to variable products', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Extend subscription possibilities to every product in your shop! With the premium version of this plugin you can configure %1$sdifferent types of subscription%2$s for the same product and exploit the potential of WooCommerce variable products. Free to act as you wish!', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="two section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Pause the subscription', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Users can pause their own subscription from their reserved area: easy! This way, they can benefit from contents they are paying for in the best way and will be able to suspend the subscription for the time they cannot use it. %3$s Consequently %1$ssubscription expiry date will be postponed as well%2$s.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/02.png" alt="feature 02"/>
			</div>
		</div>
	</div>
	<div class="three section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/03.png" alt="Feature 03"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Defer payment', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Allow payment deferment if you do not want that the subscription is deleted as soon as a fee is not paid. Whatever could be the reason for a missing payment. Set the duration of a deferment period during which users will be able to %1$scatch up missed payments%2$s: during this time, you can still allow access to content or hide it temporarily. ', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="four section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Trial period', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Use the most common strategy on the market to convince your customers about what you offer.%1$s Give them the opportunity to access at no costs%2$s. When trial period ends and if users are interested in it, they will be able to subscribe.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/04.png" alt="Feature 04"/>
			</div>
		</div>
	</div>
	<div class="five section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/05.png" alt="Feature 05"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Sign-up fee', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'All users willing to subscribe will pay a one-time fee when signing up in addition to periodical fees. You can choose to ask for a %1$ssign-up fee%2$s or ask only for the periodical fee.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="six section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Cancel and renew subscription', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'From "My Account" page, every user will be able to %1$smanage cancellation and/or renewal%2$s of the active subscription.%3$s Give your users as many tools as you can to improve services in your shop.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/06.png" alt="Feature 06"/>
			</div>
		</div>
	</div>
	<div class="seven section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/07.png" alt="Feature 07"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Subscription detail page', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'For each subscription plan both users and admin can access to %1$sdetailed information%2$s, such as subscription start and end date or info about products associated to the subscription.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="eight section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Upgrade and downgrade', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Create subscription plans with different content and give users the possibility to %1$sswitch from a plan to another%2$s. They just have to click on the specific button available in "My Account" page and, starting from the following month, they will begin paying for and benefit from the new plan they have subscribed.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/08.png" alt="Feature 08"/>
			</div>
		</div>
	</div>
	<div class="nine section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/09.png" alt="Feature 09"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Send emails automatically ', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( '%1$sGet emails each time a subscription plan is paused or cancelled%2$s. At the same time, let your customers receive emails also when the payment is made, when the subscription is going to expire or when it is stopped, cancelled or resumed.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="ten section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Coupons', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Two new types of coupons have been added to the standard ones provided by WooCommerce. Configure specific coupons that give your users discounts on %1$ssign-up fee%2$s or on the %1$srecurring subscription fee.%2$s', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/10.png" alt="Feature 10"/>
			</div>
		</div>
	</div>
	<div class="eleven section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/11.png" alt="Feature 11"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Activity list', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'All activities made on %1$ssubscriptions plans are recorded and added in a related table%2$s. You will have control of every change, tracking all subscriptions made in your shop.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="twelve section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Integration with YITH WooCommerce Membership', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'By combining these two powerful plugins, %1$syou will be able to create different membership plans and associate them to different subscription plans%2$s. What\'s the difference? Subscription gives temporary and periodical access to contents, membership gives access to different content elements in your site. A perfect combination!', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/12.png" alt="Feature 12"/>
			</div>
		</div>
	</div>
	<div class="thirteen section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/13.png" alt="Feature 13"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Cancel subscription', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'The subscription is automatically removed from database in case the order associated to it is cancelled by the admin. %1$sNo management problems%2$s, the plugin does all for you.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="fourteen section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Suspend subscription automatically', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Did your subscription %1$srecurring spayment fail?%2$s%3$s Consider also the possibility for this to happen and %1$senable subscription automatic suspension%2$s.%3$s As soon as the payment succeeds the active status will be automatically restored', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/14.png" alt="Feature 14"/>
			</div>
		</div>
	</div>
	<div class="fifteen section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/15.png" alt="Feature 15"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Three failed payment attempts', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'No subscription will be set to "Pending" for too long: after three failed payment attempts, the subscription will be %1$sautomatically switched to "cancelled"%2$s and no possibility to restore it is given. This behaviour is applicable to payments made via Stripe or PayPal.' ), 'yith-woocommerce-subscription' ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="sixteen section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Postpone status switch', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Some issues related to correct %1$ssyncronisation with PayPal and Stripe service%2$s could cause delays in receiving subscription payments.%3$s To consider also this possibility, you can set a grace period (set in hours) before the current status is switched to "overdue", "suspended" or "cancelled".', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/16.png" alt="Feature 16"/>
			</div>
		</div>
	</div>

	<div class="seventeen section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/17.png" alt="Feature 17"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Charge shipping fees only once', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Take advantage of this feature if you need to ship documents or other stuff only when users subscribe. Suppose the service you sell is entirely handled digitally, except for the first time, when you have to ship gadgets or necessary documents. No need to charge them other costs or give coupons, %1$scharge the shipping fees only once as part of the first subscription order%2$s.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="eighteen section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Edit and load Billing and shipping address', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Your users may need to change some of their details, like the name, the shipping address or their telephone number while their subscription keeps running. %3$sBut if they update their details, how can you be sure they match what has been stored in the subscription detail? Just click on %1$sLoad%2$s or %1$sEdit%2$s (if they have just informed you via email about the change they want to apply) and you’ll be sure this information is updated. This way, you make sure that %1$swhat you see are always the correct data%2$s.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/18.png" alt="Feature 18"/>
			</div>
		</div>
	</div>
	<div class="nineteen section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/19.png" alt="Feature 17"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Let your customers edit the address for one or all subscriptions', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Suppose your users have more than one subscription running on your store at the same time, but they want the items to be shipped to different addresses, how can they differentiate all this? They can open the subscription details in My Account and edit one by one.%3$sBut, if they move to another place, and want to %1$supdate the billing address of all subscriptions%2$s? Should they do the same operation for every subscription? No, they don’t have to. They can %1$sedit the address only once and have the same change applied to all their subscription plans%2$s.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="twenty section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'Handle personal data requests and anonymisation process', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'The GDPR introduces important changes to the privacy options. Your store collects data, your customers may want their anonymisations, but you may need to keep this information for as long as the law requires to you as merchant.%3$s%1$sHandle the anonymasation requests%2$s and %1$sthe time customers’ information have to be retained in the subscriptions%2$s from a dedicated panel in the plugin options.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/20.png" alt="Feature 18"/>
			</div>
		</div>
	</div>

	<div class="twenty-one section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/21.png" alt="Feature 21"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'ADD MULTIPLE SUBSCRIPTIONS TO THE SAME CART', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Forget about annoyed customers stopped by error messages that warn them about the fact they can only buy one subscription product at a time.%3$s If you\'re using one among %1$sYITH Stripe%2$s, %1$sYITH Stripe Connect%2$s and %1$sYITH PayPal Express Checkout%2$s plugins, your customers be able to add more than one subscription product to the same cart and check out faster.', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
		</div>
	</div>
	<div class="twenty-two section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php esc_html_e( 'EDIT DETAILS OF ONGOING SUBSCRIPTIONS', 'yith-woocommerce-subscription' ); ?></h2>
				</div>
				<p>
					<?php echo sprintf( esc_html( __( 'Any issues during the subscription, personal problems, change of address or simply any change of mind? If you want to meet your customers half-way, you can now edit the details of ongoing subscriptions: %1$sbilling date, expiry date, billing cycle and amount%2$s.%3$sDiscover more about which features are supported by which payment gateway here ', 'yith-woocommerce-subscription' ) ), '<b>', '</b>', '<br>' ); ?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWSBS_ASSETS_URL ); ?>/images/22.png" alt="Feature 22"/>
			</div>
		</div>
	</div>
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php echo sprintf( esc_html( __( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Subscription%2$s to benefit from all features!', 'yith-woocommerce-subscription' ) ), '<span class="highlight">', '</span>' ); ?>
				</p>
				<a href="<?php echo esc_url( $this->get_premium_landing_uri() ); ?>" target="_blank"
					class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-subscription' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-subscription' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>
