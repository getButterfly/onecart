<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../../wp-load.php');

global $ocart;
$data = array();

if (!$_GET['count']) {
	$data['error'] = '<span class="error">'.__('Please add count of items.','ocart').'</span>';
} elseif (!$_GET['cost']) {
	$data['error'] = '<span class="error">'.__('Please enter cost.','ocart').'</span>';
} elseif (!(int)$_GET['count'] || !(double)$_GET['cost']) {
	$data['error'] = '<span class="error">'.__('Please enter quantity and cost in numeric format.','ocart').'</span>';
} else {

	$post_id = $_GET['post_id'];
	$data['success'] = '<span class="success">'.sprintf(__('You have added/updated the cost of shipping <strong>%s</strong> items to <strong>%s%s</strong>.','ocart'), $_GET['count'], $_GET['cost'], $ocart['currency']).'</span>';
	update_post_meta( $post_id, 'cost_per_'.$_GET['count'], $_GET['cost'] );

}

echo json_encode($data);

?>