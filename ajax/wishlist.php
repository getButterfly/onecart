<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $current_user;
$id = $current_user->ID;
$wishlist = get_user_meta($id, 'wishlist', true);

?>

<div class="lightbox">

	<a href="javascript:closeLightbox()" class="close tip" title="<?php _e('Close window','ocart'); ?>"></a>
	
	<h1><?php _e('My Wishlist','ocart'); ?></h1>
	
	<div class="wishlist">
	
		<?php if (!$wishlist) { ?>
		
			<p><?php _e('Your wishlist is empty. You still have not added any product to your wishlist.','ocart'); ?></p>
		
		<?php } else { ?>
		
			<ul>
			
				<?php
				$args = array(
					'post_type' => 'product',
					'orderby' => 'post__in', 
					'post__in' => $wishlist
				); 
				$posts = new WP_Query( $args );
				while( $posts->have_posts() ) : $posts->the_post();
				?>
				<li data-ID="<?php the_ID(); ?>">
				
					<div class="wishlist_thumb">
						<a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', <?php the_ID(); ?>);"><?php ocart_product('small_thumb'); ?></a>
					</div>
					
					<div class="wishlist_title_price">
						<a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', <?php the_ID(); ?>);"><?php the_title(); ?></a>
						<span class="wishlist_prices">
							<?php if (ocart_product('have_original_price')) { ?><span class="wishlist_orig"><?php ocart_product('plain_original_price'); ?></span><?php } ?>
							<span class="wishlist_price"><?php ocart_product('price_in_grid'); ?></span>
						</span>
					</div>
					
					<div class="wishlist_status"><?php ocart_product('status'); ?></div>
					
					<div class="wishlist_remove"><a href="#RemoveFromWishlist" class="removeFromWishlist"><?php _e('Remove','ocart'); ?></a></div>
				
				</li>
				<?php endwhile; ?>
				
			</ul><div class="clear"></div>
		
		<?php } ?>
	
	</div>

</div>