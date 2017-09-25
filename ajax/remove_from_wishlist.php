<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if (isset($_GET['id'])) {

	global $current_user;
	$id = $current_user->ID;
	$wishlist = get_user_meta($id, 'wishlist', true);
	
	// make sure the ID exists
	if(($key = array_search($_GET['id'], $wishlist)) !== false) {
		unset($wishlist[$key]);
	}
	
	// done updating
	update_user_meta($id, 'wishlist', $wishlist);
	
	echo count($wishlist);

}