	<div class="blog_content blog_content_fullwidth">
		<div class="page">
			
			<div class="page-pad">
				
				<h1><?php _e('We\'re Sorry','ocart'); ?></h1>
						
				<div class="post-content">
					<p><?php _e('We\'re sorry. The page you are attempting to reach does not exist or might have been moved. Here are some useful links that might help you:','ocart'); ?></p>
					<ul>
						<li><?php printf(__('Go to our <a href="%s">store</a> and start shopping for products','ocart'), home_url()); ?></li>
						<?php if (isset($_SESSION['ocart_cart_count']) && $_SESSION['ocart_cart_count'] > 0) { ?>
						<li><?php printf(__('<a href="javascript:lightbox(null, \'%s\');">View</a> or modify your shopping cart <span>(%s items in your cart)</span>','ocart'), get_template_directory_uri().'/ajax/cart.php', $_SESSION['ocart_cart_count']); ?></li>
						<li><?php printf(__('<a href="%s">Checkout</a>','ocart'), get_permalink( get_page_by_path( 'checkout' ) )); ?></li>
						<?php } ?>
						<?php if (is_user_logged_in()) { ?>
						<li><?php printf(__('<a href="%s">View</a> your order history or edit your account settings','ocart'), get_permalink( get_page_by_path( 'myorders' ) )); ?></li>
						<?php } ?>
					</ul>
				</div>
				
			</div>
		
		</div>
	</div>