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

echo ocart_get_shipping($_SESSION['zonedata']['fixed_shipping'], $_SESSION['zonedata']['pct_shipping'], $_SESSION['zonedata']['weight'], $_SESSION['zonedata']['handling'], true, $add);

?>