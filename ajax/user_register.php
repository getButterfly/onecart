<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

$user = trim($_POST['r_user']);
$email = trim($_POST['r_email']);

if (empty($user)) {
	$data['error'] = __('Please pick a username to continue.','ocart');
	$data['errorID'] = 'r_user';
} elseif (empty($email)) {
	$data['error'] = __('Please enter your email address.','ocart');
	$data['errorID'] = 'r_email';
} elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
	$data['error'] = __('Please enter a valid email address.','ocart');
	$data['errorID'] = 'r_email';
} else {

	$password = wp_generate_password(12, false);
	$user_id = wp_create_user( $user, $password, $email );
	if ( is_wp_error($user_id) ) {
		if ($user_id->get_error_code() == 'existing_user_login') {
			$data['error'] = __('This username is already registered.','ocart');
			$data['errorID'] = 'r_user';
		}
		if ($user_id->get_error_code() == 'existing_user_email') {
			$data['error'] = __('This email address is already registered.','ocart');
			$data['errorID'] = 'r_email';
			$data['validID'] = 'r_user';
		}
	} else {
		wp_new_user_notification( $user_id, $password );
		ocart_auto_login( $user_id );
		$data['ok'] = true;
	}

}

echo json_encode($data);

?>