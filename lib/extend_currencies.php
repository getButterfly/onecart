<?php

/*************************************************
add more currencies
*************************************************/
function ocart_add_currency($currency) {
	$currencies = get_option('occommerce_currencies');
	if (!in_array($currency, $currencies)) {
		array_push($currencies, $currency);
		update_option('occommerce_currencies', $currencies);
	}
}