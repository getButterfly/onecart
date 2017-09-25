<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

	$product_id = $_GET['p'];
	$post = get_post($product_id);
	setup_postdata($post);

?>

<div class="lightbox lightbox-large lightbox-product">

	<a href="javascript:closeLightbox(<?php the_ID(); ?>)" class="close tip" title="<?php _e('Close window','ocart'); ?>"></a>
	
	<div class="product" id="item-<?php the_ID(); ?>" name="qty-<?php ocart_product_quantity(); ?>">

		<div class="product-img">
		
			<div class="share">
				<div class="fb-like" data-href="<?php echo get_permalink($post->ID); ?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
				<div><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo urlencode(get_permalink($post->ID)); ?>">Tweet</a></div>
				<div class="g-plusone" data-size="medium" data-href="<?php echo urlencode(get_permalink($post->ID)); ?>"></div>
				<div class="pinit"><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($post->ID)); ?>&media=<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); echo urlencode($image[0]); ?>&description=<?php echo urlencode($post->post_title); ?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>
				<script type="text/javascript">
				  (function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
				<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
			</div>
		
			<!-- navigator -->
			<?php if (ocart_get_option('show_product_breadcrumb')) { ocart_product_breadcrumb(); } ?>
		
			<?php if (ocart_has_images() && ocart_get_option('main_image_nav')) { ?>
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
				
				<div class="attr_rating">

					<?php if (!ocart_get_option('disable_cart')) { ?>
					<div class="product-rating">
						<?php ocart_product_avg_rating() ?>
					</div>
					<?php } ?>
				
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
				
				<div class="product-about">
					<div class="tabbed">
							
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
								<?php ocart_the_content(250,'...','',get_the_ID(),'char'); ?>
							</div>
							<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>
						</div>
						
					</div>
				</div>
				
			</div>
		
		</div><div class="clear"></div>
		
		<?php echo ocart_new_collection($auto='related', $slug='', $override_title= __('You may also like','ocart') ); ?>

	</div>

	<script type="text/javascript">
		$('.infotabs li a:first').css({'border-radius' : '5px 0 0 0'});
		$('.navi li:last').remove();
		$('input[type="text"]').not('#min_price, #max_price').clearOnFocus();
		if (deviceWidth > 800) {
				$('.btn-quantity').tipsy({
					trigger: 'focus',
					gravity: 'w',
					offset: 18
				});
		}
		if (deviceWidth > 800) {
				$('.tip').tipsy({
					delayIn: 200,
					gravity: 'n',
					offset: 8
				});
		}
		$('.optionprice').tipsy({
				trigger: 'hover',
				gravity: 'w',
				offset: 4
		});
		// reinstate carousel
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(reinitCarousel, 100);
			$('.main-image .zoom:first').fadeIn(800, function(){
				if (deviceWidth > 766) {
				$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
		});
		$('.thumbs a, .thumbs2 a').click(function(){
				var rel = $(this).attr('rel');
				var currentID = $('.main-image .zoom:visible').attr('id');
				if (rel !== currentID && rel != 'video') {
					$('.main-image .zoom').fadeOut(800);
					$(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
						if (deviceWidth > 766) {
						$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
						}
					});
				}
		});
	</script>

</div>