<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $wpdb;

$data = array();

$login_or_email = $wpdb->escape(trim($_POST['login']));

if(empty($login_or_email)) {
	$data['errorID'] = 'login';
	$data['error'] = __('Please enter your username or email address.','ocart');
} elseif ( filter_var($login_or_email, FILTER_VALIDATE_EMAIL) ) {
	$user_data = get_user_by('email', $login_or_email);
	if(empty($user_data)) {
		$data['error'] = __('This e-mail address does not exist.','ocart');
		$data['errorID'] = 'login';
	}
} elseif ( filter_var($login_or_email, FILTER_VALIDATE_EMAIL) == false ) {
	$user_data = get_userdatabylogin($login_or_email);
	if(empty($user_data)) {
		$data['error'] = __('This username does not exist.','ocart');
		$data['errorID'] = 'login';
	}
}

if(!empty($login_or_email) && !empty($user_data)) {

	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
	if(empty($key)) {
		$key = wp_generate_password(12, false);
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
	}

	$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
	$message .= get_option('siteurl') . "/\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
	$message .=  get_option('siteurl') . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login) . "\r\n";
	
	if ( $message && !wp_mail($user_email, sprintf(__('[%s] Password Reset','ocart'), get_bloginfo('name')), $message, ocart_mail_headers()) ) {
		$data['error'] = __('Failed to send confirmation email for some unknown reason.','ocart');
		$data['errorID'] = 'login';
	} else {
		$data['ok'] = true;
	}
	
}

echo json_encode($data);

?>