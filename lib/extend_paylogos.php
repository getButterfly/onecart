<?php

/*************************************************
hook in to add supported logos
*************************************************/
function ocart_add_paymentlogo($logo) {
	$logos = get_option('occommerce_payment_logos');
	if (!in_array($logo, $logos)) {
		array_push($logos, $logo);
		update_option('occommerce_payment_logos', $logos);
	}
}

/*************************************************
hook to remove a pay logo
*************************************************/
function ocart_remove_paymentlogo($logo) {
	$logos = get_option('occommerce_payment_logos');
	if (in_array($logo, $logos)) {
		foreach($logos as $v) {
			if($v != $logo) {
				$new_logos[] = $v;
			}
		}
		update_option('occommerce_payment_logos', $new_logos);
	}
}

/*************************************************
add more payment logos
*************************************************/

ocart_add_paymentlogo('maybank');

ocart_add_paymentlogo('cimb');

ocart_add_paymentlogo('rhb');