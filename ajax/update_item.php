<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if(!isset($_SESSION))
{
	session_start();
}

$session_id = $_GET['session_id'];
$new_quantity = $_GET['new_quantity'];

// current quantity in cart
$cur = $_SESSION['ocart_cart_count'];

// exclude quantity of current session
$new = $cur - $_SESSION['cart'][$session_id]['quantity'];

// update whole quantity
$_SESSION['ocart_cart_count'] = $new + $new_quantity;

// update item quantity
$_SESSION['cart'][$session_id]['quantity'] = $new_quantity;

// update discount
$amounts = 0;
foreach($_SESSION as $sess => $array) {
	if (strstr($sess, 'coupon_')) {
		if ($array['type'] == 2) {
			$discount_amount = number_format(ocart_real_coupon_value($array['amount']), 2);
		}
		if ($array['type'] == 1) {
			$discount_amount = number_format($array['amount'], 2);
		}
		$amounts += $discount_amount;
	}
}
$_SESSION['deduct_from_cart'] = $amounts;

// update the small cart view
ocart_smallcart();

?>

<script>
	// tooltips
	$('.items li div.remove').tipsy({
		fade: true,
		fallback: '<?php _e('Remove from cart','ocart'); ?>',
		gravity: 'w',
		offset: 4
	});
</script>