<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $ocart;

$data = array();

// check mail for guests only
if (isset($_POST['cform_email'])) {
$email = esc_attr($_POST['cform_email']);
} else {
$email = '';
}
if (!is_user_logged_in()) {
	if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) { // email is not valid
		$data['fields']['cform_email'] = __('Please enter a valid e-mail address.','ocart');
	} elseif (email_exists($email)) {
		$data['fields']['cform_email'] = sprintf(__('This email is already registered. If that is you, please <a href="javascript:lightbox(null, \'%s\');">login</a> to checkout.','ocart'), get_template_directory_uri().'/ajax/login.php');
	}
}

// require billing info
$req_fields = array(
	'cform_fname' => __('Please enter your first name.','ocart'),
	'cform_lname' => __('Please enter your last name.','ocart'),
	'cform_addr1' => __('Please enter your billing address.','ocart'),
	'cform_city' => __('Please enter your city.','ocart'),
	'cform_state' => __('Please enter your state or province.','ocart'),
	'cform_postcode' => __('Please enter your zip or postal code.','ocart'),
	'cform_country' => __('Please select your country.','ocart'),
	'cform_phone' => __('Please enter a valid phone number. We may attempt to reach you.'),
);

// require shipping info
	$req_fields['cform_fname2'] = __('Please enter shipping first name.','ocart');
	$req_fields['cform_lname2'] = __('Please enter shipping last name.','ocart');
	$req_fields['cform_addr12'] = __('Please enter shipping address.','ocart');
	$req_fields['cform_city2'] = __('Please enter shipping city.','ocart');
	$req_fields['cform_state2'] = __('Please enter shipping state or province.','ocart');
	$req_fields['cform_postcode2'] = __('Please enter shipping zip or postal code.','ocart');
	$req_fields['cform_country2'] = __('Please select destination country.','ocart');
	$req_fields['cform_phone2'] = __('We may attempt to contact the recipient of this order.','ocart');

// loop for required fields
foreach($req_fields as $field => $msg) {
	if (isset($_POST["$field"])) {
		$input = esc_attr($_POST["$field"]);
		if (empty($input) && ocart_is_required($field)) {
			$data['fields']["$field"] = $msg;
		}
	}
}

// shipping option
if (isset($_POST['cform_shipping_option'])) {
	$shipping_option = $_POST['cform_shipping_option'];
}
if (!isset($shipping_option) && $ocart['dashboard_shipping_couriers'] != 0) {
	$data['custom_error'] = __('Please select a shipping option before placing your order.','ocart');
	$data['custom_error_field'] = 'radio_shipping_options';
}

// payment option
if (isset($_POST['cform_pay_option'])) {
	$payment_option = $_POST['cform_pay_option'];
}
if (!isset($payment_option)) {
	$data['custom_error'] = __('Please select a payment option first.','ocart');
	$data['custom_error_field'] = 'radio_payment_options';
}

// passed validation
if ((empty($data['fields']) || count($data['fields']) == 0) && !isset($data['custom_error'])) {

	// not logged in? sign up first [get user ID]
	if (!is_user_logged_in()) {
		$password = wp_generate_password(12, false);
		$user_id = wp_create_user( $email, $password, $email );
		if ( !is_wp_error($user_id) ) {
			wp_new_user_notification( $user_id, $password );
			ocart_auto_login( $user_id );
		}
	} else {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}
	
	// update user profile
	update_user_meta($user_id, 'first_name', wp_filter_kses($_POST['cform_fname']));
	update_user_meta($user_id, 'last_name', wp_filter_kses($_POST['cform_lname']));
	update_user_meta($user_id, 'address1', wp_filter_kses($_POST['cform_addr1']));
	update_user_meta($user_id, 'address2', wp_filter_kses($_POST['cform_addr2']));
	update_user_meta($user_id, 'city', wp_filter_kses($_POST['cform_city']));
	update_user_meta($user_id, 'state', wp_filter_kses($_POST['cform_state']));
	update_user_meta($user_id, 'postcode', wp_filter_kses($_POST['cform_postcode']));
	update_user_meta($user_id, 'country', wp_filter_kses($_POST['cform_country']));
	update_user_meta($user_id, 'phone', wp_filter_kses($_POST['cform_phone']));
	if (isset($_POST['cform_ub']) && $_POST['cform_ub'] != 1) {
	update_user_meta($user_id, 'first_name2', wp_filter_kses($_POST['cform_fname2']));
	update_user_meta($user_id, 'last_name2', wp_filter_kses($_POST['cform_lname2']));
	update_user_meta($user_id, 'address12', wp_filter_kses($_POST['cform_addr12']));
	update_user_meta($user_id, 'address22', wp_filter_kses($_POST['cform_addr22']));
	update_user_meta($user_id, 'city2', wp_filter_kses($_POST['cform_city2']));
	update_user_meta($user_id, 'state2', wp_filter_kses($_POST['cform_state2']));
	update_user_meta($user_id, 'postcode2', wp_filter_kses($_POST['cform_postcode2']));
	update_user_meta($user_id, 'country2', wp_filter_kses($_POST['cform_country2']));
	update_user_meta($user_id, 'phone2', wp_filter_kses($_POST['cform_phone2']));
	} else {
	update_user_meta($user_id, 'first_name2', wp_filter_kses($_POST['cform_fname']));
	update_user_meta($user_id, 'last_name2', wp_filter_kses($_POST['cform_lname']));
	update_user_meta($user_id, 'address12', wp_filter_kses($_POST['cform_addr1']));
	update_user_meta($user_id, 'address22', wp_filter_kses($_POST['cform_addr2']));
	update_user_meta($user_id, 'city2', wp_filter_kses($_POST['cform_city']));
	update_user_meta($user_id, 'state2', wp_filter_kses($_POST['cform_state']));
	update_user_meta($user_id, 'postcode2', wp_filter_kses($_POST['cform_postcode']));
	update_user_meta($user_id, 'country2', wp_filter_kses($_POST['cform_country']));
	update_user_meta($user_id, 'phone2', wp_filter_kses($_POST['cform_phone']));
	}
	
	// create an "order" in DB and save order details
	$new_order = array(
		'post_type' => 'orders',
		'post_status' => 'draft',
		'post_author' => 1
	);
	$post_id = wp_insert_post( $new_order );
	
	// update order status, billing, shipping
	update_post_meta($post_id, 'custID', $user_id);
	update_post_meta($post_id, 'order_billing', array( $_POST['cform_fname'], $_POST['cform_lname'], $_POST['cform_addr1'], $_POST['cform_addr2'], $_POST['cform_city'], $_POST['cform_state'],
	$_POST['cform_postcode'], $_POST['cform_country'], $_POST['cform_phone'], $email, $_SERVER['REMOTE_ADDR'] ));
	
	// update shipping address
	if (isset($_POST['cform_ub']) && $_POST['cform_ub'] != 1) {
		update_post_meta($post_id, 'order_shipping', array( $_POST['cform_fname2'], $_POST['cform_lname2'], $_POST['cform_addr12'], $_POST['cform_addr22'], $_POST['cform_city2'], $_POST['cform_state2'], $_POST['cform_postcode2'], $_POST['cform_country2'], $_POST['cform_phone2'] ));
	} else {
		update_post_meta($post_id, 'order_shipping', array( $_POST['cform_fname'], $_POST['cform_lname'], $_POST['cform_addr1'], $_POST['cform_addr2'], $_POST['cform_city'], $_POST['cform_state'],
		$_POST['cform_postcode'], $_POST['cform_country'], $_POST['cform_phone'] ));
	}
	
	// update order note, custom delivery date (if found)
	if (isset($_POST['cform_note'])) {
		update_post_meta($post_id, 'customer_note', wp_filter_nohtml_kses( $_POST['cform_note'] ));
	}
	if (isset($_POST['cform_custom_delivery'])) {
		update_post_meta($post_id, 'custom_delivery_date', wp_filter_nohtml_kses( $_POST['cform_custom_delivery'] ) );
	}
	
	// update shipping data
	if (isset($shipping_option)) {
		$shipping_option = str_replace('courier', '', $shipping_option);
		update_post_meta($post_id, 'courier', $ocart['courier'.$shipping_option.'_label']);
		update_post_meta($post_id, 'shipping_fee', ocart_get_shipping($_SESSION['zonedata']['fixed_shipping'], $_SESSION['zonedata']['pct_shipping'], $_SESSION['zonedata']['weight'], $_SESSION['zonedata']['handling'], false, $ocart['courier'.$shipping_option.'_fee']));
	}
	
	// get $additional fees
	$additional = 0;
	$additional += $ocart['courier'.$shipping_option.'_fee'];
	if (isset($payment_option)) {
		$gateway = str_replace('pay_by_', '', $payment_option);
		if (isset($ocart[$gateway.'_charge']) && $ocart[$gateway.'_charge'] > 0) {
			$additional += $ocart[$gateway.'_charge'];
		}
	}
	
	// store gross total
	update_post_meta($post_id, 'payment_gross_total', ocart_get_total(false, $additional));
	
	// update tax rate
	update_post_meta($post_id, 'order_tax', ocart_get_tax($_SESSION['zonedata']['fixed_tax'], $_SESSION['zonedata']['pct_tax'], false));
	
	// move cart content into order meta
	$cart = array_reverse($_SESSION['cart']);
	foreach($cart as $item) {
		$cart_contents[] = array('id' => $item['id'], 'name' => $item['name'], 'quantity' => $item['quantity'], 'terms' => $item['terms'], 'price' => $item['price']);
		$old_stock = get_post_meta($item['id'], 'stock', true);
		update_post_meta($item['id'], 'stock', $old_stock - $item['quantity']);
		$old_sales = get_post_meta($item['id'], 'sales', true);
		update_post_meta($item['id'], 'sales', $old_sales + $item['quantity']);
		if (get_post_meta($item['id'], 'stock', true) == 0) {
			update_post_meta($item['id'], 'status', 'sold'); // mark item as sold if stock falls to 0
		}
	}
	update_post_meta($post_id, 'order_summary', $cart_contents);
	
	// update order coupons/coupon values
	update_post_meta($post_id, 'order_coupons', ocart_save_coupons());
	update_post_meta($post_id, 'order_coupon_values', ocart_coupon_values_array());
	
	// send mail to customer/admin
	wp_mail($email, sprintf(__('[%s] Order Received','ocart'), get_bloginfo('name')), ocart_email_template('order_received', $post_id), ocart_mail_headers());
	wp_mail(get_option('admin_email'), sprintf(__('[%s] Order Received','ocart'), get_bloginfo('name')), ocart_email_template('admin_order_received', $post_id), ocart_mail_headers());
	
	// reporting: after order is place
	// for reporting purposes maybe
	// update order status, payment status
	do_action('ocart_after_order_placed', $post_id);
	
	// reset cart
	ocart_clear_cart();
	
	// pass payment attr. (payment method, order number)
	$data['order_pay'] = $_POST['cform_pay_option'];
	$data['order_id'] = $post_id;

}

echo json_encode($data);

?>