<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

// offset
$offset = $_POST['offset'];

// num of posts
$num = ocart_get_option('grid_prod_num');

// query
$args = array( 'post_type' => 'product', 'numberposts' => $num, 'offset' => $offset );
ocart_add_order_params($args);
global $args;

// taxonomy page / archive
if (isset($_POST['taxonomy_page'])) {
	$arr = explode('-', $_POST['taxonomy_page'], 2);
	$taxonomy = $arr[0];
	$term = $arr[1];
	$args[$taxonomy] = $term;
}

// find taxonomies
if (isset($_POST['taxonomies']))
	$taxonomies = $_POST['taxonomies'];

// if brand=adidas
if (isset($taxonomies)) {
	$taxonomies = explode(',', $_POST['taxonomies']);
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
}

///**** price filter *****/
if (isset($_POST['pricemin']) && isset($_POST['pricemax'])) {
$min = $_POST['pricemin'];
$max = $_POST['pricemax'];
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
}

$posts = get_posts( $args );

foreach ($posts as $post): setup_postdata($post);

?>

				<li id="product-<?php the_ID(); ?>">
					
					<?php if (!ocart_get_option('disable_cart') && ocart_product_in_stock()) { ?>
					<div class="catalog_quickadd"><span><?php _e('Select Options','ocart'); ?></span></div>
					<?php } ?>
					
					<a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', '<?php the_ID(); ?>', '<?php echo get_permalink($post->ID); ?>');">
						
						<?php ocart_product('product_hover'); ?>
						<?php the_post_thumbnail( 'catalog-thumb', array('title' => '', 'class' => 'preload') ); ?>
						
						<?php if (!ocart_get_option('disable_cart')) { ?>
						
						<?php
						$status = get_post_meta($post->ID, 'status', true);
						$mark_as_onsale = get_post_meta($post->ID, 'mark_as_onsale', true);
						$mark_as_new = get_post_meta($post->ID, 'mark_as_new', true);
						if ($status == 'sold') {
						echo "<span class='catalog_item_status catalog_item_status_sold'>".__('Sold Out!','ocart')."</span>";
						} elseif (isset($mark_as_onsale) && $mark_as_onsale == 'on') {	
						echo "<span class='catalog_item_status catalog_item_status_sale'>".ocart_sticker_text('sale')."</span>";
						} elseif (isset($mark_as_new) && $mark_as_new == 'on' && ocart_is_new_product() ) {
						echo "<span class='catalog_item_status catalog_item_status_new'>".ocart_sticker_text('new')."</span>";
						}
						if (isset($mark_as_new) && isset($mark_as_onsale) && $mark_as_new == 'on' && $mark_as_onsale == 'on' && ocart_is_new_product() ) {
							echo '<div class="sticker_new">'.ocart_sticker_text('new', $wrap='span').'</div>';
						}
						?>
						
						<?php } ?>
		
						<span class="catalog_item_title">
							<span class="title"><?php the_title(); ?></span>
							
							<?php if (ocart_get_option('disable_cart') && ocart_get_option('disable_prices')) { } else { ?>
							<span class="price_orig"><?php ocart_product('plain_original_price'); ?></span>
							<span class="price">
								<?php ocart_product('price_in_grid'); ?>
								<?php if (ocart_has_product_tag()) { ?>
								<span class="catalog_item_options">
									<span class="catalog_item_options_div">
										<span class="arr"></span>
										<?php ocart_list_product_tag() ?>
									</span>
								</span>
								<?php } ?>
							</span>
							<?php } ?>
							
						</span>
					</a>
				</li>

<?php endforeach; ?>

<script type="text/javascript">
$(function(){

	// preload images
	$("img.preload").each(function(){
		var element = $(this);
			 
		// Store the original src
		var originalSrc = element.attr("src");
			 
		// Replace the image with a spinner
		element.attr("src", "<?php echo get_template_directory_uri(); ?>/img/loader.gif");
			 
		// Show spinner
		element.show();

		// Load the original image
		$('<img />').attr('src', originalSrc).load(function(){
			// Image is loaded, replace the spinner with the original
			element.attr("src", originalSrc).hide().fadeIn();
		});

	});
	
	// catalog image effect
	$('.catalog_list li').live('mouseenter',function(){
		$(this).find('.catalog_item_status').stop().animate({top: '-30px'});
		$(this).find('.catalog_quickadd').fadeIn();
		$(this).find('.catalog_item_options').fadeIn();
		$(this).find('img').css({'border-color': '#ccc'});
	}).live('mouseleave',function(){
		$(this).find('.catalog_item_status').stop().animate({top: '-20px'});
		$(this).find('.catalog_quickadd').fadeOut();
		$(this).find('.catalog_item_options').fadeOut();
		$(this).find('img').css({'border-color': '#fff'});
	});
	
	// product image hover v2
	$('.catalog_list li').live('mouseenter',function() {
		if ($(this).children('a').children('.product_hover').length) {
			$(this).children('a').children('.product_hover').show();
			$(this).children('a').children('.preload').hide();
		}
	}).live('mouseleave',function() {
		if ($(this).children('a').children('.product_hover').length) {
			$(this).children('a').children('.product_hover').hide();
			$(this).children('a').children('.preload').show();
		}
    });
	
	// load more results on scroll v2
	if ($('.catalog').length > 0) { // Run the ajax request only when .catalog exists!
	products_count = $('#products_count').html();
	show_button_limit = '<?php echo ocart_get_option('max_grid_prods'); ?>';
	$(window).scroll(function () {
		var distance = '<?php echo ocart_get_option('scroll_distance'); ?>';
		if ($(window).scrollTop() + $(window).height() > $(document).height() - distance) {
			if ($('.catalog_list li').size() < products_count && canScroll == true) {
				// add new results
				var taxonomies = '';
				$('.filter ul a.selected').each( function() {
					taxonomies = taxonomies + $(this).attr('id') + ',';
				});
				canScroll = false;
				$("body").prepend("<div id='loading-results' style='display:none;'></div>");
				$('#loading-results').center().show();
				$('.catalog').css({opacity: 0.2});
				$.ajax({
					type: 'post',
					url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
					data: {taxonomies: taxonomies, offset: $('.catalog_list li').size()},
					success: function(res) {
						// add results
						$(".catalog_list").append(res);
						// enable scroll again
						canScroll = true;
						// remove loader
						$('#loading-results').remove();
						$('.catalog').css({opacity: 1});
					}
				});
			}
		}
	});
	}

});
</script>