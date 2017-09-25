<div id="helpers">

	<div class="wrap">
	
		<div class="ajax-search">
			<div class="ajax-search-relative">
				<input type="text" name="productSearch" id="productSearch" value="" placeholder="<?php _e('Search for a Product','ocart'); ?>" />
				<ul class="ajax-search-results">
					<!-- ajax search results appear here -->
				</ul>
			</div>
		</div>
		
		<!-- switch to grid -->
		<?php if (ocart_get_option('show_sliderbtn')) { ?>
		<a id="switchToSlider" href="<?php echo home_url(); ?>#slider"><?php _e('Switch to Slider View','ocart'); ?></a>
		<?php } ?>
		<!-- done -->
		
	</div>
		
</div>

<div id="index">
	<div class="wrap">
	
		<!-- categories, options -->
		<div class="filter">
			<?php ocart_show_grid_filters() ?>
		</div>
	
		<div class="catalog">

			<?php $posts = get_posts( array( 'post_type' => 'product', 'numberposts' => -1 ) ); ?>
			<div class="catalog_title"><ins><?php _e('All Products','ocart'); ?></ins><span><?php if (count($posts) == 1) { printf(__('<span class="totalprod"><span id="products_count">%s</span> Product Found</span>','ocart'), count($posts)); } else { printf(__('<span class="totalprod"><span id="products_count">%s</span> Products Found</span>','ocart'), count($posts)); } ?></span></div>
			
			<ul class="catalog_list">
			<!-- data -->
			</ul><div class="clear"></div>
		
		</div><div class="clear"></div>
	
	</div>
</div>