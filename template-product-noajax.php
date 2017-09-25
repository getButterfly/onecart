<div id="details">

<div class="product" id="item-<?php the_ID(); ?>" name="qty-<?php ocart_product_quantity(); ?>">

	<!-- close product detail -->
	<a href="#return" id="closeProductdetail"></a>
	
	<!-- pinit -->
	<?php if (ocart_get_option('show_pinit')) { ?>
	<div class="product-pin-it"><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($post->ID)); ?>&media=<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); echo urlencode($image[0]); ?>&description=<?php echo urlencode($post->post_title); ?>" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	<?php } ?>
	<!-- pinit -->
	
	<!-- you may also like module -->
	<?php if (ocart_has_similar_products()) { ?>
	<a href="#similar-products" class="recommend-btn"><?php _e('You may also like','ocart'); ?></a>
	<?php } ?>
	<!-- end you may also like -->

	<div class="product-img">
	
		<!-- navigator -->
		<?php if (ocart_get_option('show_product_breadcrumb')) { ocart_product_breadcrumb(); } ?>
		
		<?php if (ocart_has_images() && ocart_get_option('main_image_nav') ) { ?>
		<div class="nextImage"></div>
		<div class="prevImage"></div>
		<?php } ?>
		
		<!-- mini thumbs -->
		<?php if (ocart_get_option('product_thumbs') == 'default') { ocart_product('thumbs'); } ?>
		<?php if (ocart_get_option('product_thumbs') == 'below') { ocart_product('thumbs2'); } ?>
		
		<!-- main image -->
		<div class="main-image">
			<?php ocart_product('main_image'); ?>
			<?php ocart_product('images'); ?>
		</div>

	</div>

	<div class="product-info">
	
		<div class="product-div">
		
			<?php ocart_wishlist_button(); ?>
	
			<?php ocart_show_product_title(); ?>
			<?php ocart_show_price_and_status(); ?>
			
			<div class="product-about">
				<div class="tabbed">
				
					<?php if (!ocart_get_option('disable_cart')) { ?>
					<div class="product-rating">
						<?php ocart_product_avg_rating() ?>
					</div>
					<?php } ?>
						
					<ul class="infotabs">
						<li><a href="" rel="tab_content" class="current"><?php _e('Description','ocart'); ?></a></li>
						<?php
						$tab_name = get_post_meta($post->ID, 'customtab_name', true);
						$video = get_post_meta($post->ID, 'customtab_video', true);
						?>
						<?php if (!empty($tab_name)) { ?><li><a href="" rel="tab_custom"><?php echo $tab_name; ?></a></li><?php } ?>
						<?php if (!empty($video)) { ?><li><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/playvideo.php', '', '<?php echo $post->ID; ?>');" rel="tab_video"><?php _e('Video','ocart'); ?></a></li><?php } ?>
						<?php if (!ocart_get_option('disable_cart')) { ?>
						<li><a href="" rel="tab_reviews"><?php printf(__('Reviews (<span class="ajax_reviews_count">%s</span>)','ocart'), ocart_reviews_count()); ?></a></li>
						<?php } ?>
					</ul>
					<div class="infotab">
						<div class="infotab_div_default">
							<?php ocart_the_content(130,'...','',get_the_ID(),'char'); ?>
						</div>
						<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>
					</div>
					
				</div>
			</div>
			
			<?php if (ocart_product_in_stock()) { ?>
			
			<div class="product-var">
				<?php ocart_product_taxonomy(); ?>
			</div>
			
			<?php if (!ocart_get_option('disable_cart')) { ?>
			<div class="product-add">
				<form action="/" class="addtocart">
					<input type="submit" value="<?php _e('Add to Cart','ocart'); ?>" class="btn-add" />
					<input type="text" value="<?php _e('Qty','ocart'); ?>" class="btn-quantity" title="<?php _e('Please enter a quantity','ocart'); ?>" />
				</form>
			</div><div class="clear"></div>
			<?php } ?>
			
			<?php } else { ?>
			
			<?php ocart_notify_me_stock(); ?>
			
			<?php } ?>
			
		</div>
	
	</div>

</div>

</div>