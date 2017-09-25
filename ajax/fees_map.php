<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

// get the filter that will be used to calculate
$fields = array('country','state','city','zip');
$zones = get_option('occommerce_zones');

foreach($fields as $field) {
	if (isset($_GET["$field"]) && !empty($_GET["$field"])) {
		
		// get location
		if ($field == 'country') {
			$countries = get_option('occommerce_allowed_countries');
			$c = $_GET["$field"];
			$location = $countries["$c"];
		} else {
			$location = $_GET["$field"];
		}
		
		// search all zones for location
		foreach($zones as $zone) {
			if ( preg_grep( "/$location/i" , $zone["filters"]["regions"] ) && !isset($zone['status']) ) {
				$data['filter'] = $location;
				$data['fixed_tax'] = $zone['pricing']['fixed_tax'];
				$data['pct_tax'] = $zone['pricing']['pct_tax'];
				$data['fixed_shipping'] = $zone['pricing']['fixed_shipping'];
				$data['pct_shipping'] = $zone['pricing']['pct_shipping'];
				$data['weight'] = $zone['pricing']['weight'];
				$data['handling'] = $zone['pricing']['handling'];
			}
		}
		
	}
}

// use default zone settings at last 
if (!isset($data['filter'])) {
			$data['filter'] = 'everywhere';
			$data['fixed_tax'] = $zones[0]['pricing']['fixed_tax'];
			$data['pct_tax'] = $zones[0]['pricing']['pct_tax'];
			$data['fixed_shipping'] = $zones[0]['pricing']['fixed_shipping'];
			$data['pct_shipping'] = $zones[0]['pricing']['pct_shipping'];
			$data['weight'] = $zones[0]['pricing']['weight'];
			$data['handling'] = $zones[0]['pricing']['handling'];
}

// register the new rates in session variable
$_SESSION['zonedata']['location'] = $data['filter'];
$_SESSION['zonedata']['fixed_tax'] = $data['fixed_tax'];
$_SESSION['zonedata']['pct_tax'] = $data['pct_tax'];
$_SESSION['zonedata']['fixed_shipping'] = $data['fixed_shipping'];
$_SESSION['zonedata']['pct_shipping'] = $data['pct_shipping'];
$_SESSION['zonedata']['weight'] = $data['weight'];
$_SESSION['zonedata']['handling'] = $data['handling'];

// additional fees
$add = 0;
if (isset($_GET['shipping_charges']) && $_GET['shipping_charges'] > 0) {
	$add += $_GET['shipping_charges'];
}
$add_total = 0;
if (isset($_GET['payment_charges']) && $_GET['payment_charges'] > 0) {
	$add_total += $_GET['payment_charges'];
}

// new prices
$data['new_shipping'] = ocart_get_shipping($data['fixed_shipping'], $data['pct_shipping'], $data['weight'], $data['handling'], true, $add);
$data['new_tax'] = ocart_get_tax($data['fixed_tax'], $data['pct_tax'], true);
$data['new_total'] = ocart_get_total(true, $add_total);

echo json_encode($data);

?>