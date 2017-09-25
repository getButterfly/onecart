<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

$log = trim($_POST['c_log']);
$pwd = trim($_POST['c_pwd']);

$creds = array();
$creds['user_login'] = $log;
$creds['user_password'] = $pwd;
$creds['remember'] = true;

if (empty($log)) {
	$data['error'] = __('Please enter your username.','ocart');
	$data['errorID'] = 'c_log';
} elseif (empty($pwd)) {
	$data['error'] = __('Please enter your password.','ocart');
	$data['errorID'] = 'c_pwd';
} else {

	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) ) {
		if ($user->get_error_code() == 'invalid_username') {
			$data['error'] = __('Invalid Username was entered.','ocart');
			$data['errorID'] = 'c_log';
		}
		if ($user->get_error_code() == 'incorrect_password') {
			$data['error'] = __('Incorrect password was entered.','ocart');
			$data['errorID'] = 'c_pwd';
			$data['validID'] = 'c_log';
		}
	} else {
		$data['ok'] = true;
	}

}

echo json_encode($data);

?>