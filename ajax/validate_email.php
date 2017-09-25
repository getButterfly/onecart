<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

$email = trim($_POST['email']);

if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
	$data['field'] = 'cform_email';
	$data['msg'] = __('Please enter a valid e-mail address.','ocart');
	$data['gravatar'] = '';
} elseif (email_exists($email)) {
	$data['field'] = 'cform_email';
	$data['msg'] = sprintf(__('This email is already registered. If that is you, please <a href="javascript:lightbox(null, \'%s\');">login</a> to checkout.','ocart'), get_template_directory_uri().'/ajax/login.php');
	$data['gravatar'] = '';
} else {
	// update user avatar (we will register this as new customer)
	$data['gravatar'] = get_avatar( $email, $size = '58', $d = get_template_directory_uri() . '/img/assets/default-user.png' );
}

echo json_encode($data);

?>