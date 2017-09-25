<?php global $ocart; ?>

<div class="clear"></div>

<div id="nav">
	<div class="wrap">
	
		<?php if (ocart_get_option('menu_style')) { ?>
		
		<ul id="supermenu"><?php wp_nav_menu( array( 'theme_location' => 'nav_menu', 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false ) ); ?></ul>
		
		<?php } else { ?>
		
		<div id="browser"><?php ocart_store_nav(); ?></div>
		
		<?php } ?>

		<div id="browser-320">
			<select>
				<option value="">-<?php _e('Home','ocart'); ?></option>
				<?php
				$parents = get_terms( 'product_category', 'orderby=name&hide_empty='.$ocart['emptyterms']);
				if ($parents) {
					echo '<optgroup label="'.__('Shop by Category','ocart').'">';
					foreach($parents as $parent) {
						if ($parent->parent == 0) {
							echo '<option value="'.$parent->taxonomy.'-'.$parent->slug.'">-'.$parent->name.'</option>';
							$child = get_terms('product_category', 'orderby=name&hide_empty='.$ocart['emptyterms'].'&child_of='.$parent->term_id);
							foreach($child as $sub) {
								echo '<option value="'.$sub->taxonomy.'-'.$sub->slug.'">--'.$sub->name.'</option>';
							}
						}
					}
					echo '</optgroup>';
				}
				$brands = get_terms( 'brand', 'orderby=name&hide_empty='.$ocart['emptyterms']);
				if ($brands) {
					echo '<optgroup label="'.__('Shop by Brand','ocart').'">';
					foreach($brands as $brand) {
							echo '<option value="'.$brand->taxonomy.'-'.$brand->slug.'">-'.$brand->name.'</option>';
					}
					echo '</optgroup>';
				}
				?>
			</select>
		</div>
	
	</div>
</div>