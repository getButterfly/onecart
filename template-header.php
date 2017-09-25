<?php if (ocart_get_option('wishlist') || ocart_get_option('ocml') || ocart_get_option('ocmc')) { ?>

<div id="topbar">
	<div class="wrap">
	
		<?php if (ocart_get_option('ocml')) { ?>
		<div class="switchbar switchbar-l">
			<label><?php _e('Choose Language:','ocart'); ?></label>
			<?php ocart_show_languages(); ?>
		</div>
		<?php } ?>
		
		<?php if (ocart_get_option('ocmc')) { ?>
		<div class="switchbar switchbar-c">
			<label><?php _e('Currency:','ocart'); ?></label>
			<?php ocart_show_currencies(); ?>
		</div>
		<?php } ?>
		
		<?php if (ocart_get_option('wishlist')) { ?>
		<div id="wishlist"><a href=""><?php printf(__('My Wishlist (<span id="ajax_wishlist_count">%s</span>)','ocart'), ocart_wishlist_count()); ?></a></div>
		<?php } ?>
		
		<div class="clear"></div>
	
	</div>
</div>

<?php } ?>

<div id="header-holder">
<div id="header">
	<div class="wrap">
	
		<div id="logo"><a href="<?php echo home_url(); ?>/"><?php ocart_logo_img() ?></a></div>
		
		<ul id="toplinks">
			
			<?php if (is_home() || is_single() && get_post_type() == 'product') { ?>
			
			<?php if (ocart_get_option('show_bloglink')) { ?>
			<li><a href="<?php echo get_permalink( get_page_by_path( 'blog' ) ); ?>"><?php echo get_the_title(get_page_by_path('blog')->ID); ?></a></li>
			<?php } ?>
			
			<?php } else { ?>
			<li><a href="<?php echo home_url(); ?>"><?php _e('Shop','ocart'); ?></a></li>
			<?php } ?>
	
			<?php wp_nav_menu( array( 'theme_location' => 'header_menu', 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false ) ); ?>
			
			<?php ocart_login(); ?>
			
		</ul>
		
		<?php if (!ocart_get_option('disable_cart')) { ?>
		<ul id="cart">
			<li class="cart"><a href="#" class="cart-link"><span class="ajax_items_count"><?php ocart_cart_items_count(); ?></span></a>
				<div class="cartpopup">
					<div class="cartpopup-inner">
						
						<?php ocart_smallcart(); ?>
					
					</div>
				</div>
			</li>
		</ul>
		<?php } ?>
		
		<div class="clear"></div>

	</div>
</div>
</div>