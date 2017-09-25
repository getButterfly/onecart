<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

// simple validation
$req = array('contact_name','contact_email','contact_message');
foreach($req as $field) {
	if (isset($_POST["$field"])) {
	$value = trim($_POST["$field"]);
	} else {
	$value = '';
	}
	if (empty($value)) {
		$data['fields']["$field"] = true;
	}
}

// send mail
if (!isset($_POST['contact_subject'])) {
	$subject = __('No subject','ocart');
} else {
	$subject = $_POST['contact_subject'];
}
if (!isset($data['fields'])) {
	$headers = 'From: '.$_POST['contact_name'].' <'.$_POST['contact_email'].'>' . "\r\n";
	if ( wp_mail(get_option('admin_email'), $_POST['contact_subject'], $_POST['contact_message'], $headers) )
		$data['thankyou'] = __('Your message has been sent. Thank you!','ocart');
}

echo json_encode($data);

?>