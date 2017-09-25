<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

/********************************
Prepare related items / products
********************************/

global $ocart;

$baseProd = $_GET['product'];

if (get_post_meta($baseProd, 'similar_products', true) && get_post_meta($baseProd, 'similar_products', true) != '') { // set via products page

	$similar = ocart_related_products($baseProd);
	
} else {

	$similar = ocart_related_products_by_taxonomy($baseProd,ocart_get_option('related_tax'));

}

?>

<ul>

<?php while ($similar->have_posts()): $similar->the_post(); if ($post->ID == $baseProd) continue; ?>

		<li>
		
			<?php if (!ocart_product_in_stock()) { ?>
				<span class="catalog_item_status catalog_item_status_<?php echo get_post_meta($post->ID, 'status', true); ?>"><?php _e('Sold Out!','ocart'); ?></span>
			<?php } elseif (get_post_meta($post->ID, 'status', true) == 'sale' && ocart_has_discount() ) { ?>
				<span class="catalog_item_status catalog_item_status_<?php echo get_post_meta($post->ID, 'status', true); ?>"><?php ocart_product_discount(); ?></span>
			<?php } elseif (get_post_meta($post->ID, 'status', true) == 'new') { ?>
				<span class="catalog_item_status catalog_item_status_<?php echo get_post_meta($post->ID, 'status', true); ?>"><?php _e('New!','ocart'); ?></span>
			<?php } ?>
			
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="tooltip" id="product-<?php the_ID(); ?>">
				<?php the_post_thumbnail( 'similar-thumb', array('title' => '', 'class' => '') ); ?>
			</a>
			
		</li>

<?php endwhile; ?>

</ul>

<div class="snextItem"></div>
<div class="sprevItem"></div>