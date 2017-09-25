<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if (isset($_GET['id'])) {

	global $current_user;
	$id = $current_user->ID;
	$wishlist = get_user_meta($id, 'wishlist', true);
	
	if (!is_array($wishlist)) {
		$wishlist = array();
	}
	
	// make sure the ID does not exist
	if(($key = array_search($_GET['id'], $wishlist)) !== false) {
		// found, show error
	} else {
		// not found, add it
		array_unshift($wishlist, $_GET['id']);
		update_user_meta($id, 'wishlist', $wishlist);
		echo ocart_wishlist_count();
	}
	
	//delete_user_meta($id, 'wishlist');

}