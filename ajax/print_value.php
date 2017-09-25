<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$value = ltrim ($_GET['value'],'.');
$item_subtotal = $value * $_GET['quantity'];
echo ocart_format_currency( ocart_show_price ($item_subtotal, false) );

?>