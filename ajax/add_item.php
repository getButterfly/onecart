<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if(!isset($_SESSION))
{
	session_start();
}

// get item information
$item_id = $_GET['item_id'];
$item_name = $_GET['item_name'];
$quantity = $_GET['quantity'];
$terms = $_GET['terms'];

// get total items in cart
if (!isset($_SESSION['ocart_cart_count'])) {
	$_SESSION['ocart_cart_count'] = $quantity;
} else {
	$current = $_SESSION['ocart_cart_count'];
	$total = $current+$quantity;
	$_SESSION['ocart_cart_count'] = $total;
}

// default session cart
if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = array();
}

// default terms (quick add)
if ($terms == 'default') {
		$terms2 = '';
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			foreach ($taxonomies as $taxonomy ) {
				if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
					$terms = wp_get_post_terms($item_id, $taxonomy, $args = array('fields' => 'all', 'orderby' => 'term_id'));
					if ($terms && ! is_wp_error( $terms )) {
						$terms2 .= $terms[0]->taxonomy.'-'.$terms[0]->slug.':';
					}
				}
			}
		}
}

// terms/term2
if (isset($terms2) != '') {
	$terms = $terms2;
}

// price specification
$sale_price = get_post_meta($item_id, 'price', true);
$passed = explode(":", $terms);
$adjust = 0;
foreach($passed as $firstlevel){
	if (!empty($firstlevel)){
		$get_terms = explode('-', $firstlevel, 2);
		$term = get_term_by('slug', $get_terms[1], $get_terms[0]);
		// find custom price
		$custom_price_change = get_post_meta($item_id, 'product_'.$item_id.'_'.$term->taxonomy.'_'.$term->term_id, true);
		if ( !empty($custom_price_change) ) {
			$adjust += $custom_price_change;
		}
	}
}
if (isset($adjust)) {
	$price = $sale_price + $adjust;
} else {
	$price = $sale_price;
}

// find a similar product in cart
function ocart_search_cart($array, $key, $value)
{
    $results = array();
    if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value)
            $results[] = $array;

        foreach ($array as $subarray)
            $results = array_merge($results, ocart_search_cart($subarray, $key, $value));
    }

    return $results;
}

// store item in cart
$duplicate = false;
if (isset($_SESSION['cart'])) {
	$product_exists_in_cart = ocart_search_cart($_SESSION['cart'], 'id', $item_id);
	if ($product_exists_in_cart) {
		foreach($product_exists_in_cart as $k => $v) {
			if ($terms == $v['terms']) {
				$id = $v['session_id']; // update session id
				$current_quantity = $_SESSION['cart'][$id]['quantity'];
				$_SESSION['cart'][$id]['quantity'] = $current_quantity + $quantity;
				$duplicate = true; // duplicate product
			}
		}
	}
}

// store item in unique session
if ($duplicate == false) {
	$_SESSION['cart'][] = array(
		'session_id' => count($_SESSION['cart']),
		'id' => $item_id,
		'name' => $item_name,
		'quantity' => $quantity,
		'terms' => $terms,
		'price' => $price
	);
}

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

// modify the quantity
$currstock = get_post_meta($item_id, 'stock', true);
if ($currstock != 0 && $currstock == '') {
} else {
	$left = $currstock - $quantity;
	update_post_meta($item_id, 'stock', $left);
	if ($left == 0) {
		update_post_meta($item_id, 'status', 'sold');
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
			if ($uiterm_qty == 0) {
			// do nothing
			} elseif ($uiterm_qty == '') {
			// do nothing
			} else {
				$uiterm_qty_left = $uiterm_qty - $quantity;
				update_post_meta($item_id, 'stock_'.$uiterm->term_id, $uiterm_qty_left);
			}
		}
	}

}

// open the small cart view
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