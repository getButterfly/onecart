<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

// default args
$args = array( 'post_type' => 'product', 'numberposts' => -1 );

///**** price filter *****/
$min = $_GET['pricemin'];
$max = $_GET['pricemax'];
if (isset($_SESSION['exchange_rate_reverse']) && $_SESSION['exchange_rate_reverse'] > 0) {
	$min = $_SESSION['exchange_rate_reverse'] * $min;
	$max = $_SESSION['exchange_rate_reverse'] * $max;
	$min = floor($min);
	$max = ceil($max);
}
if (isset($min) && isset($max)) {
	$args['meta_query'] = array(
			array(
				'key' => 'price',
				'value' => array( $min, $max ),
				'type' => 'DECIMAL',
				'compare' => 'BETWEEN',
			)
	);
}

// taxonomies passed
if (isset($_GET['taxonomies']) && !empty($_GET['taxonomies']) && $_GET['taxonomies'] !== 'undefined') {

	$taxonomies = $_GET['taxonomies'];
	$taxonomies = explode(',', $_GET['taxonomies']);
	foreach($taxonomies as $k => $v) {
		if ($v != '') {
		list($key, $value) = explode('-', $v, 2);
		if (isset($result[$key])) {
			$result[$key] .= ', ' . $value;
		} else {
			$result[$key] = $value;
		}
		}
	}
	if (isset($result)){
	foreach($result as $k => $v) {
		$args[$k] = $v;
	}
	}
	
	// get terms
	foreach($taxonomies as $k => $v) {
		if ($v != '') {
			$arr = explode('-', $v, 2);
			$term = get_term_by('slug', $arr[1], $arr[0]);
			$filters[] = '<div class="active_filter" rel="'.$term->taxonomy.'-'.$term->slug.'">'.$term->name.'</div>';
		}
	}
	if (isset($filters)) {
		foreach($filters as $filter) {
			echo $filter;
		}
	}

?>

<?php $posts = get_posts( $args ); ?>
<ins id=""><?php //ocart_breadcrumb($term->term_id, $term->taxonomy) ?></ins><span><?php if (count($posts) == 1) { printf(__('<span class="totalprod"><span id="products_count">%s</span> Product Found</span>','ocart'), count($posts)); } else { printf(__('<span class="totalprod"><span id="products_count">%s</span> Products Found</span>','ocart'), count($posts)); } ?></span>

<?php } else { ?>

<?php $posts = get_posts( $args ); ?>
<ins><?php _e('All Products','ocart'); ?></ins><span><?php if (count($posts) == 1) { printf(__('<span class="totalprod"><span id="products_count">%s</span> Product Found</span>','ocart'), count($posts)); } else { printf(__('<span class="totalprod"><span id="products_count">%s</span> Products Found</span>','ocart'), count($posts)); } ?></span>

<?php } ?>