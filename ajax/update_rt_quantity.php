<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $ocart;

$data = array();

$current_q = get_post_meta($_GET['item_id'], 'stock', true);
$data['new_product_quantity'] = $current_q;

	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies=get_taxonomies($args,$output,$operator);
	if (isset($ocart['product_attr']) && is_array($ocart['product_attr'])) {
		$product_attr = $ocart['product_attr']; // use product options from theme panel
	} else {
		$product_attr = array('color', 'size'); // default fallback
	}
	if  ($taxonomies) {
		$taxonomies = $product_attr;
		foreach ($taxonomies as $taxonomy ) {
			$terms = get_the_terms($_GET['item_id'], $taxonomy);
			if ($terms && ! is_wp_error( $terms )) {
				$terms =  wp_get_post_terms($_GET['item_id'], $taxonomy, $args = array('orderby' => 'term_id'));
				if ($terms && ! is_wp_error( $terms )) { // make sure we have options
					foreach($terms as $term) {
						$term_qty = get_post_meta($_GET['item_id'], 'stock_'.$term->term_id, true);
						if ($term_qty == '') {
						$qty = 999;
						} else {
						if ($term_qty > 0) { $qty = $term_qty; } else if ($term_qty == 0) { $qty = 0; } else if ($current_q > 0) { $qty = $stock; } else { $qty = 999; }
						}
						$data['stock_'.$term->term_id] = array( 'term' => 'stock_'.$term->term_id, 'qty' => $qty );
					}
				}
			}
		}
	}

echo json_encode($data);