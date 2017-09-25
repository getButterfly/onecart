<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

if (!isset($_POST['product_id']))
	return;

// validate user email
$subscribers = get_post_meta($_POST['product_id'], 'get_notified', true);

if (!is_email($_POST['subscribe_to_product'])) {
	$data['fail'] = __('Please enter a valid e-mail address.','ocart');
} elseif (in_array($_POST['subscribe_to_product'], $subscribers)) {
	$data['fail'] = __('You have already subscribed. You will be informed by e-mail when this product becomes available.','ocart');
} else {
	$subscribers[] = $_POST['subscribe_to_product'];
	update_post_meta($_POST['product_id'], 'get_notified', $subscribers);
	$data['pass'] = __('Thank you! You will be informed by email when this product becomes available in stock.','ocart');
}

// end
echo json_encode($data);