<?php

/************************************************************
email tags to be used
************************************************************/
function ocart_email_template_tags() {
	$email_tags = array(
		'admin_orders_dashboard',
		'store_url',
		'store_name',
		'customer_orders_link',
		'order_id',
		'cart_items',
		'coupon_codes',
		'customer_name',
		'customer_address',
		'customer_city',
		'customer_state',
		'customer_postcode',
		'customer_country',
		'customer_phone',
		'customer_email',
		'shipping_name',
		'shipping_address',
		'shipping_city',
		'shipping_state',
		'shipping_postcode',
		'shipping_country',
		'cart_subtotal',
		'tax_fee',
		'cart_total',
		'shipping_fee',
		'order_comments',
		'shipping_courier',
		'tracking_information'
	);
	asort($email_tags);
	foreach($email_tags as $key) {
		print "<code>{".$key."}</code> ";
	}
}

/************************************************************
process HTML email template $tpl = template name, $id = order id
************************************************************/
function ocart_email_template($tpl, $id, $notes = '') {

	global $ocart;
	
	$countries = get_option('occommerce_all_countries');
	
	// get post meta : order summary, billing and shipping addresses
	$order_summary = get_post_meta($id, 'order_summary', true);
	$billing = get_post_meta($id, 'order_billing', true);
	$shipping = get_post_meta($id, 'order_shipping', true);
	$shipping_fee = get_post_meta($id, 'shipping_fee', true);
	$shipping_courier = get_post_meta($id, 'courier', true);
	$tracking = get_post_meta($id, 'order_tracking', true);
	$tracking_url = get_post_meta($id, 'order_tracking_url', true);
	$total = get_post_meta($id, 'payment_gross_total', true);
	
	// tax fee
	if (ocart_get_option('enable_tax')) {
		$tax_fee = get_post_meta($id, 'order_tax', true);
	} else {
		$tax_fee = 0;
	}
	
	// loop thru order summary for multi-order details [name, quantity, item price]
	$cart_subtotal = '';
	$cart_items = '';
	foreach($order_summary as $order) {
		$price = get_post_meta($order['id'], 'price', true) * $order['quantity'];
		$cart_subtotal += $price;
		$cart_items .= '<tr>
		<td style="border:solid 1px #ddd;padding: 10px;">'.$order['name'].ocart_get_incart_product_terms($order['terms']).'</td>
		<td style="border:solid 1px #ddd;padding: 10px;">'.$order['quantity'].'</td>
		<td style="border:solid 1px #ddd;padding: 10px;">'.ocart_format_currency( ocart_show_price($price) ).'</td>
		</tr>';
	}
	
	// display coupon codes if any
	$coupon_codes = '';
	$coupons = get_post_meta($id, 'order_coupons', true);
	$coupon_values = get_post_meta($id, 'order_coupon_values', true);
	if (is_array($coupons)) {
		foreach($coupons as $coupon) {
			$coupon_codes .= '<tr>
			<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>'.sprintf(__('Discount Coupon: %s','ocart'), $coupon['code']).'</b></td>
			<td style="border:solid 1px #ddd;padding: 10px;">-'.ocart_format_currency( ocart_show_price( $coupon_values[$coupon['id']] ) ).'</td>
			</tr>';
		}
	}
	
	// tracking information
	if ($tracking_url) {
		$tracking_information = '<a href="'.$tracking_url.'" style="color:#ea6ea0">'.$tracking.'</a>';
	} else {
		$tracking_information = $tracking;
	}
	
	// email tags
	$email_tags = array(
		'admin_orders_dashboard' => admin_url().'edit.php?post_type=orders',
		'store_url' => home_url().'/',
		'store_name' => get_bloginfo('name'),
		'customer_orders_link' => get_permalink( get_page_by_path( 'myorders' ) ),
		'order_id' => $id,
		'cart_items' => $cart_items,
		'coupon_codes' => $coupon_codes,
		'customer_name' => $billing[0].' '.$billing[1],
		'customer_address' => $billing[2],
		'customer_city' => $billing[4],
		'customer_state' => $billing[5],
		'customer_postcode' => $billing[6],
		'customer_country' => $countries["$billing[7]"],
		'customer_phone' => $billing[8],
		'customer_email' => $billing[9],
		'shipping_name' => $shipping[0].' '.$shipping[1],
		'shipping_address' => $shipping[2],
		'shipping_city' => $shipping[4],
		'shipping_state' => $shipping[5],
		'shipping_postcode' => $shipping[6],
		'shipping_country' => $countries["$shipping[7]"],
		'cart_subtotal' => ocart_format_currency( ocart_show_price($cart_subtotal) ),
		'tax_fee' => ocart_format_currency( ocart_show_price($tax_fee) ),
		'cart_total' => ocart_format_currency( ocart_show_price($total) ),
		'shipping_fee' => ocart_format_currency( ocart_show_price($shipping_fee) ),
		'order_comments' => $notes,
		'shipping_courier' => $shipping_courier,
		'tracking_information' => $tracking_information
	);
	
	// process html template
	$html = $ocart['html_'.$tpl];
	
	foreach ($email_tags as $key => $value) {
		$html = str_replace("{" . $key . "}", $value, $html);
	}
	
	return $html;

}

?>