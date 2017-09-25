<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

// now we have the filter. search it in zones
$zones = get_option('occommerce_zones');

// get the filter that will be used to calculate
$fields = array('pre_country','pre_region','pre_zip');
foreach($fields as $field) {
	if (isset($_POST["$field"]) && !empty($_POST["$field"])) {
		$location = $_POST["$field"];
		
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


// use everywhere else zone
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
	
// new prices
$data['new_shipping'] = ocart_get_shipping($data['fixed_shipping'], $data['pct_shipping'], $data['weight'], $data['handling'], true);
$data['new_tax'] = ocart_get_tax($data['fixed_tax'], $data['pct_tax'], true);
$data['new_total'] = ocart_get_total(true);

echo json_encode($data);

?>
