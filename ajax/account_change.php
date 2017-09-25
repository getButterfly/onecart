<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if (!is_user_logged_in()) die();

// global variables
$current_user = wp_get_current_user();
$data = array();

if (isset($_POST['change_mail'])) {
	$email = esc_attr($_POST['change_mail']);
} else {
	$email = '';
}

if (isset($_POST['password_current'])) {
	$password_current = esc_attr($_POST['password_current']);
} else {
	$password_current = '';
}

if (isset($_POST['password_new'])) {
	$password_new = esc_attr($_POST['password_new']);
} else {
	$password_new = '';
}

if (isset($_POST['password_confirm'])) {
	$password_confirm = esc_attr($_POST['password_confirm']);
} else {
	$password_confirm = '';
}

// validate form change
if ($email != $current_user->user_email || !empty($password_new) || !empty($password_confirm)) { // do change
	if (empty($password_current)) {
		$data['fields']['password_current'] = __('Please enter your current password to continue.','ocart');
		echo json_encode($data);
		exit;
	} elseif (wp_check_password($password_current, $current_user->user_pass, $current_user->ID) != true) {
		$data['fields']['password_current'] = __('The password you entered is invalid.','ocart');
		echo json_encode($data);
		exit;
	} else {
		
		// validate email
		if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
			$data['fields']['change_mail'] = __('Please enter a valid e-mail address.','ocart');
			echo json_encode($data);
			exit;
		} elseif (email_exists($email) && $email != $current_user->user_email) {
			$data['fields']['change_mail'] = __('This email is already used by another account.','ocart');
			echo json_encode($data);
			exit;
		} else {
		// update email
		$update_email = wp_update_user( array ('ID' => $current_user->ID, 'user_email' => $email	) );
		}
		
		// validate password change
		if (empty($password_new) && !empty($password_confirm)) {
			$data['fields']['password_new'] = __('Please enter your new password.','ocart');
			echo json_encode($data);
			exit;
		} elseif (empty($password_confirm) && !empty($password_new)) {
			$data['fields']['password_confirm'] = __('Please enter your new password again.','ocart');
			echo json_encode($data);
			exit;
		} elseif ($password_new !== $password_confirm) {
			$data['fields']['password_new'] = __('Your passwords do not match.','ocart');
			echo json_encode($data);
			exit;	
		} else {
		// update password
		$update_pass = wp_update_user( array ('ID' => $current_user->ID, 'user_pass' => $password_new	) );
		}

	}
}

// display success messages
if (isset($update_email) || isset($update_pass)) {
	$data['success'] = __('Your account has been saved.','ocart');
	echo json_encode($data);
	exit;
} else {
	$data['empty'] = __('Nothing was changed.','ocart');
	echo json_encode($data);
	exit;
}

?>