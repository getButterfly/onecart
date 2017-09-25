<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if(!isset($_SESSION))
{
	session_start();
}

$data = array();

// validate coupon
if (isset($_POST['coupon_code'])) {

	// is coupon empty
	if (!esc_attr($_POST['coupon_code'])) {
		$data['err'] = __('Coupon code is empty.','ocart');
		echo json_encode($data);
		exit;
	}
	
	// does coupon exist
	if (!ocart_coupon_id($_POST['coupon_code'])) {
		$data['err'] = sprintf(__('"%s" is not a valid coupon code.','ocart'), $_POST['coupon_code']);
		echo json_encode($data);
		exit;
	}
	
	// if coupon exists
	if (ocart_coupon_id($_POST['coupon_code'])) {
	
		// meta fields
		$type = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'discount_type', true);
		$amount = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'discount_amount', true);
		$minimum = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'min_subtotal', true);
		$free_shipping = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'free_shipping', true);
		$must_login = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'must_login', true);
		$user_emails = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'user_emails', true);
		$usage_limit = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'usage_limit', true);
		$expiry = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'expiry', true);
		$individual_use = get_post_meta(ocart_coupon_id($_POST['coupon_code']), 'individual_use', true);
		
		// check if coupon is already used
		if (isset($_SESSION['coupon_'.ocart_coupon_id($_POST['coupon_code'])])) {
			$data['err'] = sprintf(__('"%s" has been added to cart already.','ocart'), $_POST['coupon_code']);
			echo json_encode($data);
			exit;
		}
		
		// check if coupon expiration date has passed
		$coupon_expiry_date = $expiry;
		$current_date = date('Y-m-d');
		if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $coupon_expiry_date)){
		if(strtotime($coupon_expiry_date) < strtotime($current_date)) {
			$data['err'] = __('This coupon has expired.','ocart');
			echo json_encode($data);
			exit;
		}
		}
		
		// check if coupon has expired
		if ($usage_limit == 0) {
			$data['err'] = __('Maximum usage limit has been reached.','ocart');
			echo json_encode($data);
			exit;
		}
		
		// check that coupon must be used individually
		if ($individual_use == 'on') {
			foreach($_SESSION as $sess => $array) {
				if (strstr($sess, 'coupon_')) {
					$data['err'] = __('This coupon cannot be used with other coupons.','ocart');
					echo json_encode($data);
					exit;
				}
			}
		}
		
		// check if coupon requires login
		if ($must_login == 'on' && !is_user_logged_in()) {
			$data['err'] = __('You need to login to use this coupon.','ocart');
			echo json_encode($data);
			exit;
		}
		
		// valid to specific user emails
		if ($user_emails != '') {
			$current_user = wp_get_current_user();
			$current_user_email = $current_user->user_email;
			$user_emails = str_replace(' ','',$user_emails);
			$user_emails = trim($user_emails);
			$allowed_emails = explode(',',$user_emails);
			if (!in_array($current_user_email, $allowed_emails)) {
				$data['err'] = __('You are not eligible to use this coupon.','ocart');
				echo json_encode($data);
				exit;
			}
		}
		
		// check if coupon requires minimum subtotal
		if ($minimum > ocart_total_cart_only()) {
			$data['err'] = sprintf(__('A minimum subtotal of %s is required to use this coupon.','ocart'), ocart_format_currency($minimum));
			echo json_encode($data);
			exit;
		}
		
		// if this coupon waives shipping fee
		if ($free_shipping == 'on') {
			$_SESSION['coupon_'.ocart_coupon_id($_POST['coupon_code'])] = array(
				'id' => ocart_coupon_id($_POST['coupon_code']),
				'code' => $_POST['coupon_code'],
				'amount' => $amount,
				'type' => $type
			);
			$_SESSION['force_free_shipping'] = 1;
			$data['discount'] = __('Your shipping fee is waived!','ocart');
			$data['new_shipping'] = 0;
			$data['new_total'] = ocart_total();
			echo json_encode($data);
			exit;
		}
		
		// discount from cart subtotal
		if ($type == 1) {
			$_SESSION['coupon_'.ocart_coupon_id($_POST['coupon_code'])] = array(
				'id' => ocart_coupon_id($_POST['coupon_code']),
				'code' => $_POST['coupon_code'],
				'amount' => $amount,
				'type' => 1
			);
			$data['code'] = $_POST['coupon_code'];
			$data['coupon_realvalue'] = number_format($amount, 2);
			$data['discount'] = __('Your coupon has been added to cart.','ocart');
			$data['new_subtotal'] = ocart_recalculate_subtotal($amount, $amount_type='fixed');
			$_SESSION['deduct_from_cart'] += $amount;
			echo json_encode($data);
			exit;
		}
		
		// discount from cart as percent
		if ($type == 2) {
			$_SESSION['coupon_'.ocart_coupon_id($_POST['coupon_code'])] = array(
				'id' => ocart_coupon_id($_POST['coupon_code']),
				'code' => $_POST['coupon_code'],
				'amount' => $amount,
				'type' => 2
			);
			$data['code'] = $_POST['coupon_code'];
			$data['coupon_realvalue'] = number_format(ocart_real_coupon_value($amount), 2);
			$data['discount'] = __('Your coupon has been added to cart.','ocart');
			$data['new_subtotal'] = ocart_recalculate_subtotal($amount, $amount_type='percent');
			$_SESSION['deduct_from_cart'] += ocart_real_coupon_value($amount);
			echo json_encode($data);
			exit;
		}
	
	}

}

?>