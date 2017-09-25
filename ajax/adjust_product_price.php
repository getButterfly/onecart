<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if (isset( $_GET['change'] )) {

	$sale_price = get_post_meta($_GET['product_id'], 'price', true);

	// explode changes
	$changes = $_GET['change'];
	$changes = rtrim($changes, ':');
	$ex = explode(':',$changes);
	$adjust = 0;
	foreach($ex as $new_adjust) {
		$adjust += $new_adjust;
	}
	
	$new_price = $sale_price + $adjust;
	
	// show the price
	echo ocart_format_currency( ocart_show_price ($new_price) );
	
}