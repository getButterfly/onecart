<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if (isset($_GET['taxonomy'])) {
	$taxonomy = $_GET['taxonomy'];
	$taxonomy_arr = explode('-', $taxonomy, 2);
	$target = $taxonomy_arr[0];
	$term = $taxonomy_arr[1];
	$custom = get_term_by('slug', $term, $target);
	$custom_id = $custom->term_id;
	ocart_store_nav($target, $custom_id);
} else {
	ocart_store_nav();
}

?>

<script>
	$('ul.list').carouFredSel({
		width: 770,
		height: 30,
		scroll: 1,
		align: "left",
		auto: false,
		direction: "right",
		prev: "#browser .prev",
		next: "#browser .next"
	});
	
	$('.options a:last').css({'border-bottom': 'none'});
</script>