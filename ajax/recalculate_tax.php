<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $ocart;
echo ocart_get_tax($_SESSION['zonedata']['fixed_tax'], $_SESSION['zonedata']['pct_tax'], true);

?>