<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if(!isset($_SESSION))
{
	session_start();
}

if (isset($_SESSION['ocart_cart_count'])) {
	echo $_SESSION['ocart_cart_count'];
} else {
	echo 0;
}

?>