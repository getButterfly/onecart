<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

/* globals on every update */
if (isset($_GET['min_price'])) {
	$min = filter_var($_GET['min_price'], FILTER_SANITIZE_NUMBER_INT);
}

if (isset($_GET['max_price'])) {
	$max = filter_var($_GET['max_price'], FILTER_SANITIZE_NUMBER_INT);
}

/*************
		Get taxonomy and term
**************/
if (isset($_GET['taxonomy']) && !empty($_GET['taxonomy'])) {
	$arr = explode('-', $_GET['taxonomy'], 2);
	$taxonomy = $arr[0];
	$slug = $arr[1];
}

/*************
		Filtering: terms, taxonomies
**************/
if (isset($_GET['taxonomies']) && isset($_GET['terms_ids'])) {

	// get terms/tax pairs
	$terms = explode(',', $_GET['terms_ids']);
	$taxonomies = explode(',', $_GET['taxonomies']);
	$arr = array_combine($terms, $taxonomies);
	foreach($arr as $k => $v) {
		if ($k != 0) { // not empty
			$filtered[$k] = $v;
		}
	}
	
	// group / arrange array
	if (isset($filtered) && is_array($filtered)) {
		foreach($filtered as $id => $taxonomy) {
			$term = get_term_by('id', $id, $taxonomy);
			$relations[$term->slug] = $taxonomy;
		}
		$grouped = array();
		foreach ($relations as $choice => $group) {
			$grouped[$group][] = $choice;
		}
		foreach($grouped as $k => $v) {
			$choices = implode(',',$v);
			$saved[$k] = $choices;
		}
	}
	
	// query
	$saved['post_type'] = 'product';
	$saved['numberposts'] = -1;
	
}

/*************
		Filtering: price range
**************/
if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
	$saved['meta_query'] = array(
			array(
				'key' => 'price',
				'value' => array( $min, $max ),
				'type' => 'DECIMAL',
				'compare' => 'BETWEEN',
			)
	);
}

/*************
		Filtering: custom field status
**************/
if (isset($_GET['cfield']) && in_array($_GET['cfield'], array('instock','new','sale','sold'))) {
	$saved['meta_query'][] = array(
		'key' => 'status',
		'value' => $_GET['cfield'],
		'compare' => '='
	);
}

/*************
		Filtering: sort by
**************/
if (isset($_GET['sortfield']) && in_array($_GET['sortfield'], array('highest_first','lowest_first','most_popular'))) {

	$sort = $_GET['sortfield'];
	
	if ($sort == 'highest_first') {
		$saved['meta_key'] = 'price';
		$saved['orderby'] = 'meta_value_num';
		$saved['order'] = 'DESC';
	} elseif ($sort == 'lowest_first') {
		$saved['meta_key'] = 'price';
		$saved['orderby'] = 'meta_value_num';
		$saved['order'] = 'ASC';
	} elseif ($sort == 'most_popular') {
		$saved['meta_key'] = 'sales';
		$saved['orderby'] = 'meta_value_num';
		$saved['order'] = 'DESC';
	}

}

/*************
		Which loop to use
**************/
if (isset($_GET['use_saved_query']) && $_GET['use_saved_query'] == true) {
	$args = $saved;
} else {
	if (isset($_GET['taxonomy']) && !empty($_GET['taxonomy'])) {
	$args = array( 'post_type' => 'product', 'numberposts' => -1, $taxonomy => $slug );
	} else {
	$args = array( 'post_type' => 'product', 'numberposts' => -1 );
	}
	ocart_add_order_params($args);
	global $args;
}

/* start loop here */
$posts = get_posts( $args );

?>

	<?php if (count($posts) > 0) { ?>
	
	<ul class="prods">
	
		<?php foreach ($posts as $post): setup_postdata($post); ?>

			<li id="item-<?php the_ID(); ?>" rel="<?php the_permalink(); ?>">
				<?php if (!ocart_get_option('disable_cart')) { ?>
				<?php ocart_product('tag'); ?>
				<?php } ?>
				<?php ocart_product('catalog_image'); ?>
				<div class="label">
					<div class="label-content">
						<span class="title"><?php ocart_product('title'); ?></span>
						<?php if (ocart_get_option('disable_cart') && ocart_get_option('disable_prices')) { } else { ?>
						<div class="price"><?php ocart_product('price'); ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="label-320"><?php ocart_product('title'); ?><span><?php ocart_product('plain_price'); ?></span></div>
			</li>

		<?php endforeach; ?>

	</ul>
	
	<div class="nextItem"></div>
	<div class="prevItem"></div>
			
	<div class="nextproduct"></div>
	<div class="prevproduct"></div>
	
	<script type="text/javascript">
	
		// init carousel
		$('.prods').carouFredSel({
			width: 977,
			height: <?php echo ocart_get_option('catalog_image_height') + 100; ?>,
			scroll: 1,
			align: "left",
			auto: false,
			direction: "right",
			prev: {
				button: '.prevItem',
				onBefore: function(){
						$('.prods li').removeClass('viewport');
						$('.prevproduct').stop().animate({left: 0});
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
							$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
								$('.prevproduct').hide().stop().animate({left: '-200px'});
							});
						});
				},
				onAfter: function(){
						$('.prods li').removeClass('viewport');
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var $img = $('.prods li.viewport:first').last(),
								$prev = $img.prev();
							if (0==$prev.length) {
								$prev = $img.siblings().last();
							}
							var prev_item_id = $prev.attr('id').replace(/[^0-9]/g, '');
							$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
								$('.prevproduct').show();
							});
						});
				}
			},
			next: {
				button: '.nextItem',
				onBefore: function(){
						$('.prods li').removeClass('viewport');
						$('.nextproduct').stop().animate({right: 0});
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var prev_item_id = $('.prods li.viewport:first').attr('id').replace(/[^0-9]/g, '');
							$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
								$('.nextproduct').hide().stop().animate({right: '-200px'});
							});
						});
				},
				onAfter: function(){
						$('.prods li').removeClass('viewport');
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
							$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
								$('.nextproduct').show();
							});
						});
				}
			}
		});

		// change next/prev product
		if ($('.prods li').size() >= 7 ) {
			$('.prods').trigger("currentVisible", function( items ) {
				items.addClass( 'viewport' );
				var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
				$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id);
				var last_item_id = $('.prods li:last').attr('id').replace(/[^0-9]/g, '');
				$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + last_item_id);
			});
		}
		
	</script>
	
	<?php } ?>