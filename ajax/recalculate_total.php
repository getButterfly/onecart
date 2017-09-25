<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $ocart;

$add = false;

if (isset($_GET['add'])) {
	if ($_GET['add'] > 0) {
		$add = $_GET['add'];
	}
}

echo ocart_get_total(true, $add);

?>