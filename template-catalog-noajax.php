<?php if (ocart_catalog_version() == 1) { ?>

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
		
			<?php
			$sort = ocart_get_option('sort_products');
			if ($sort == 1) {
			$args = array( 'post_type' => 'product', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC', get_query_var( 'taxonomy' ) => get_query_var( 'term' ) );
			} else {
			$args = array( 'post_type' => 'product', 'numberposts' => -1, get_query_var( 'taxonomy' ) => get_query_var( 'term' ) );
			}
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
					</li>

				<?php endforeach; ?>

			</ul>
			
			<div class="nextItem"></div>
			<div class="prevItem"></div>
					
			<div class="nextproduct"></div>
			<div class="prevproduct"></div>
			
			<script type="text/javascript">
			
				// init carousel
				jQuery('.prods').carouFredSel({
					width: 977,
					height: 277,
					scroll: 1,
					align: "left",
					auto: false,
					direction: "right",
					prev: {
						button: '.prevItem',
						onBefore: function(){
								jQuery('.prods li').removeClass('viewport');
								jQuery('.prevproduct').stop().animate({left: 0});
								jQuery('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var next_item_id = jQuery('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
									jQuery('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
										jQuery('.prevproduct').hide().stop().animate({left: '-200px'});
									});
								});
						},
						onAfter: function(){
								jQuery('.prods li').removeClass('viewport');
								jQuery('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var $img = jQuery('.prods li.viewport:first').last(),
										$prev = $img.prev();
									if (0==$prev.length) {
										$prev = $img.siblings().last();
									}
									var prev_item_id = $prev.attr('id').replace(/[^0-9]/g, '');
									jQuery('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
										jQuery('.prevproduct').show();
									});
								});
						}
					},
					next: {
						button: '.nextItem',
						onBefore: function(){
								jQuery('.prods li').removeClass('viewport');
								jQuery('.nextproduct').stop().animate({right: 0});
								jQuery('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var prev_item_id = jQuery('.prods li.viewport:first').attr('id').replace(/[^0-9]/g, '');
									jQuery('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
										jQuery('.nextproduct').hide().stop().animate({right: '-200px'});
									});
								});
						},
						onAfter: function(){
								jQuery('.prods li').removeClass('viewport');
								jQuery('.prods').trigger("currentVisible", function( items ) {
									items.addClass( 'viewport' );
									var next_item_id = jQuery('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
									jQuery('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
										jQuery('.nextproduct').show();
									});
								});
						}
					}
				});

				// change next/prev product
				if (jQuery('.prods li').size() >= 7 ) {
					jQuery('.prods').trigger("currentVisible", function( items ) {
						items.addClass( 'viewport' );
						var next_item_id = jQuery('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
						jQuery('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id);
						var last_item_id = jQuery('.prods li:last').attr('id').replace(/[^0-9]/g, '');
						jQuery('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + last_item_id);
					});
				}
				
			</script>
			
			<?php } ?>

		</div>
	
	</div>
	
</div>

<?php } else { ?>

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

			<?php $posts = get_posts( array( 'post_type' => 'product', 'numberposts' => -1, get_query_var( 'taxonomy' ) => get_query_var( 'term' ) ) ); ?>
			<div class="catalog_title"><ins id=""><?php if (get_query_var('taxonomy')) { $current_tax = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); echo $current_tax->name; } ?></ins><span><?php if (count($posts) == 1) { printf(__('<span class="totalprod"><span id="products_count">%s</span> Product Found</span>','ocart'), count($posts)); } else { printf(__('<span class="totalprod"><span id="products_count">%s</span> Products Found</span>','ocart'), count($posts)); } ?></span></div>
			
			<ul class="catalog_list" <?php if (get_query_var('taxonomy')) { ?>rel="<?php echo get_query_var( 'taxonomy' ).'-'.get_query_var( 'term' ); ?>"<?php } ?>>
			<!-- data -->
			</ul><div class="clear"></div>
		
		</div><div class="clear"></div>
	
	</div>
</div>

<?php } ?>