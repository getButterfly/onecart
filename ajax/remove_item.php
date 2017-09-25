<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if(!isset($_SESSION))
{
	session_start();
}

$session_id = $_GET['session_id'];

// update total items in cart 'ocart_cart_count'
if (isset($session_id) && isset($_SESSION['cart'][$session_id])) {

if (isset($_SESSION['cart'][$session_id]['terms'])){
	$terms = $_SESSION['cart'][$session_id]['terms'];
}

$quantity = $_SESSION['cart'][$session_id]['quantity'];
$current = $_SESSION['ocart_cart_count'];
$_SESSION['ocart_cart_count'] = ($current - $quantity);

$item_id = $_SESSION['cart'][$session_id]['id'];

// unset the session order (ID)
unset($_SESSION['cart'][$session_id]);

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

}

// modify the quantity
$currstock = get_post_meta($item_id, 'stock', true);
if ($currstock != 0 && $currstock == '') {
} else {
	$left = $currstock + $quantity;
	update_post_meta($item_id, 'stock', $left);
	if ($left > 0) {
		update_post_meta($item_id, 'status', 'instock');
	}
}

// modify subquantity
if (isset($terms)) {

	$parts = explode(":", $terms);
	foreach($parts as $firstlevel){
		if (!empty($firstlevel)){
			$terms = explode('-', $firstlevel, 2);
			$tax = $terms[0];
			$term = $terms[1];
			$uiterm = get_term_by('slug', $term, $tax);
			$uiterm_qty = get_post_meta($item_id, 'stock_'.$uiterm->term_id, true);
			$uiterm_qty_left = $uiterm_qty + $quantity;
			if ($uiterm_qty == '') {
			
			} else {
			update_post_meta($item_id, 'stock_'.$uiterm->term_id, $uiterm_qty_left);
			}
		}
	}

}

// open the small cart view
ocart_smallcart();

?>

<script type="text/javascript">

	current_stock_cookie = jQuery.cookies.get('saved_quantity_<?php echo $item_id; ?>');
	jQuery.cookies.set('saved_quantity_<?php echo $item_id; ?>', current_stock_cookie - <?php echo $quantity; ?>);

	// tooltips
	$('.items li div.remove').tipsy({
		fade: true,
		fallback: '<?php _e('Remove from cart','ocart'); ?>',
		gravity: 'w',
		offset: 4
	});

</script>