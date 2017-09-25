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
		<?php if (ocart_get_option('show_gridbtn')) { ?>
		<a id="switchToGrid" href="<?php echo home_url(); ?>#grid"><?php _e('Switch to Grid View','ocart'); ?></a>
		<?php } ?>
		<!-- done -->
		
	</div>
		
</div>

<div id="catalog">

	<div class="wrap">
	
		<div class="catalogWrapper">

		</div>
	
	</div>
	
</div>