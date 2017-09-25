<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $ocart;

if (isset($_GET['paymentgateway']) && isset($_GET['orderID'])) {
	$post_id = $_GET['orderID'];
	$payment_type = $_GET['paymentgateway'];
} else {
	die(); // hacking?
}

// store payment type
update_post_meta($post_id, 'payment_type', $payment_type);

// check payment gateways
$gateways = get_option('occommerce_OC_gateways');
foreach($gateways as $gateway => $gateway_arr) {
	if (ocart_enabled_gateway($gateway) && 'pay_by_'.$gateway == $payment_type) {
		
		echo '<div class="result">';
			
			// form redirection
			if (isset($gateway_arr['checkout']['redirect_form']) && $gateway_arr['checkout']['redirect_form'] == 1) {
			
				echo '<h2>'.$gateway_arr['checkout']['title'].'</h2>';
				echo '<p class="redirecting-text">'.sprintf(__('Redirecting to %s...','ocart'), $gateway_arr['name']).'</p>';
				
				/* hook to show the payment/form redirection */
				do_action("ocart_show_payment_form_{$gateway}", $post_id);
				
			} else {
			
				// offline payment methods
				switch ($gateway) {
					case 'cod':
						echo '<h2>'.__('Thank You!','ocart').'</h2>
						<p>'.sprintf(__('Your order has been received in our system. You can view your order details by logging to your <a href="%s">order history</a>. You have selected to pay by cash on delivery. You will receive e-mail notification once we\'ve reviewed your order.','ocart'), get_permalink( get_page_by_path( 'myorders' ))).'</p>
						<p>'.sprintf(__('Thank you for shopping with %s!','ocart'), get_bloginfo('name')).'</p>';
						break;
					case 'bank':
						echo '<h2>'.__('Thank You!','ocart').'</h2>
						<p>'.sprintf(__('Your order has been received in our system. You can view your order details by logging to your <a href="%s">order history</a>. You have selected to pay by a bank transfer for this order.','ocart'), get_permalink( get_page_by_path( 'myorders' ))).'</p>
						<h5>'.__('Payment Instructions','ocart').'</h5>
						<p>'.str_replace(Chr(13),'<br>', $ocart['html_bank']).'</p>
						<p>'.sprintf(__('Thank you for shopping with %s!','ocart'), get_bloginfo('name')).'</p>';
						break;
					case 'cheque':
						echo '<h2>'.__('Thank You!','ocart').'</h2>
						<p>'.sprintf(__('Your order has been received in our system. You can view your order details by logging to your <a href="%s">order history</a>. You have selected to pay by a cheque payment for this order.','ocart'), get_permalink( get_page_by_path( 'myorders' ))).'</p>
						<h5>'.__('Payment Instructions','ocart').'</h5>
						<p>'.str_replace(Chr(13),'<br>', $ocart['html_cheque']).'</p>
						<p>'.sprintf(__('Thank you for shopping with %s!','ocart'), get_bloginfo('name')).'</p>';
						break;
					case 'paylater':
						echo '<h2>'.__('Thank You!','ocart').'</h2>
						<p>'.sprintf(__('Your order has been received in our system. You have selected to pay later for this order. Please log in to your <a href="%s">account</a> when you are ready to complete payment for this order.','ocart'), get_permalink( get_page_by_path( 'myorders' ))).'</p>
						<p>'.sprintf(__('Thank you for shopping with %s!','ocart'), get_bloginfo('name')).'</p>';
						break;
				}
			
			}
		
		echo '</div>';
		
	}
}