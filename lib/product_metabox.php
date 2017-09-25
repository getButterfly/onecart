<?php

// add meta box
add_action( 'add_meta_boxes', 'pd_meta_box_add' );
function pd_meta_box_add() {
	global $post;
    add_meta_box( 'product-meta-box', __('Setup Your Product','ocart'), 'pd_meta_box_cb', 'product', 'normal', 'high' );
}

// render meta box
function pd_meta_box_cb($post) {
	global $ocart;
	$custom = get_post_custom($post->ID);
	if (isset($custom['price'][0])) { $price = $custom['price'][0]; }
	if (isset($custom['regular_price'][0])) { $reg_price = $custom['regular_price'][0]; }
	if (isset($custom['status'][0])) { $status = $custom['status'][0]; }
	if (isset($custom['stock'][0])) { $stock = $custom['stock'][0]; }
	if (isset($custom['sku'][0])) { $sku = $custom['sku'][0]; }
	if (isset($custom['imagesize'][0])) { $imagesize = $custom['imagesize'][0]; }
	if (isset($custom['weight'][0])) { $weight = $custom['weight'][0]; }
	if (isset($custom['sales'][0])) { $sales = $custom['sales'][0]; }
	if (isset($custom['imagecrop_method'][0])) { $imagecrop_method = $custom['imagecrop_method'][0]; }
	if (isset($custom['imagecrop'][0])) { $imagecrop = $custom['imagecrop'][0]; }
	if (isset($custom['hover_image'][0])) { $hover_image = $custom['hover_image'][0]; }
	if (isset($custom['customtab_name'][0])) { $customtab_name = $custom['customtab_name'][0]; }
	if (isset($custom['customtab_content'][0])) { $customtab_content = $custom['customtab_content'][0]; }
	if (isset($custom['customtab_video'][0])) { $customtab_video = $custom['customtab_video'][0]; }
	if (isset($custom['tagline'][0])) { $tagline = $custom['tagline'][0]; }
	if (isset($custom['tagline_term'][0])) { $tagline_term = $custom['tagline_term'][0]; }
	if (isset($custom['mark_as_new'][0])) { $mark_as_new = $custom['mark_as_new'][0]; }
	if (isset($custom['mark_as_onsale'][0])) { $mark_as_onsale = $custom['mark_as_onsale'][0]; }
	if (isset($custom['mark_as_new_text'][0])) { $mark_as_new_text = $custom['mark_as_new_text'][0]; }
	if (isset($custom['mark_as_onsale_text'][0])) { $mark_as_onsale_text = $custom['mark_as_onsale_text'][0]; }
	if (isset($custom['new_start'][0])) { $new_start = $custom['new_start'][0]; }
	if (isset($custom['new_expiry'][0])) { $new_expiry = $custom['new_expiry'][0]; }
	if (isset($custom['instock_text'][0])) { $instock_text = $custom['instock_text'][0]; }
	if (isset($custom['sold_text'][0])) { $sold_text = $custom['sold_text'][0]; }
	
	/* checkboxes */
	$mark_as_onsale = isset( $mark_as_onsale ) ? esc_attr( $mark_as_onsale ) : '';
	$mark_as_new = isset( $mark_as_new ) ? esc_attr( $mark_as_new ) : '';
	
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	
    ?>
	
	<h4 class="subpanel"><?php _e('Pricing','ocart'); ?></h4>
	
	<p>
		<label for="price"><?php _e('Sale Price','ocart'); ?></label>
		<input type="text" name="price" id="price" value="<?php if(!empty($price)) echo $price; ?>" />
		<span class="description"><?php _e('The product current sale price.','ocart'); ?></span>
	</p>
	
	<p>
		<label for="reg_price"><?php _e('Original Price','ocart'); ?></label>
		<input type="text" name="reg_price" id="reg_price" value="<?php if(!empty($reg_price)) echo $reg_price; ?>" />
		<span class="description"><?php _e('Product price before active discounts.','ocart'); ?></span>
	</p>

	<?php
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			?>
				
				<p><?php _e('You can have different prices based on selected product option. Please enter the <strong>change</strong> using (+ / -) for the various product options you have.','ocart'); ?></p>

			<?php
			foreach ($taxonomies as $taxonomy ) {
				if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
				?>
				<?php
					$terms = wp_get_post_terms($post->ID, $taxonomy, array("fields" => "all"));
					if ($terms && ! is_wp_error( $terms )) {
						foreach($terms as $term) {
						?>
						
						<span class="oc_numfield">
							<span class="oc_option"><ins><?php echo $term->name; ?></ins></span>
							<input type="text" name="<?php echo 'product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id; ?>" id="<?php echo 'product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id; ?>" value="<?php if(get_post_meta( $post->ID, 'product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id, true)) {
							echo get_post_meta( $post->ID, 'product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id, true); } ?>" placeholder="<?php _e('Change','ocart'); ?>" />
						</span>
						
						<?php
						}
					}
				}
			}
		}
	?>
	<div class="clear"></div>
	
	<h4 class="subpanel"><?php _e('Product Options','ocart'); ?></h4>
	
	<p>
		<label for="tagline_term"><?php _e('Use Builtin Tagline','ocart'); ?></label>
		<select name="tagline_term" id="tagline_term">
		<?php
				$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			$taxonomies = array_reverse($taxonomies);
			foreach ($taxonomies as $taxonomy ) {
			?>
			<option value="<?php echo $taxonomy; ?>"<?php if (isset($tagline_term) && $taxonomy == $tagline_term) { echo ' selected="selected"'; } ?>><?php echo ocart_get_taxonomy_nicename( $taxonomy ); ?></option>
		<?php
			}
		}
		?>
		</select>
	</p>
	
    <p>
        <label for="tagline"><?php _e('Custom Tagline','ocart'); ?></label> 
        <input type="text" name="tagline" id="tagline" value="<?php if (!empty($tagline)) echo $tagline; ?>" />
		<span class="description"><?php _e('Overrides above setting. The product tagline appears in a dialog style in Grid mode.','ocart'); ?></span>
    </p>
	
	<p class="grid_checkbox">
		<label for="mark_as_onsale"><?php _e('Mark as "On SALE"','ocart'); ?></label>
		<input type="checkbox" id="mark_as_onsale" name="mark_as_onsale" <?php checked( $mark_as_onsale, 'on' ); ?> /><span class="description"><?php _e('Check this box if you want to mark this product as on sale.','ocart'); ?>
	</p>
	
    <p>
        <label for="mark_as_onsale_text"><?php _e('On Sale Sticker Text','ocart'); ?></label> 
        <input type="text" name="mark_as_onsale_text" id="mark_as_onsale_text" value="<?php if (!empty($mark_as_onsale_text)) echo $mark_as_onsale_text; ?>" />
		<span class="description"><?php _e('The text that appears on the sticker. If you leave this blank, the discount value will be shown by default.','ocart'); ?></span>
    </p>
	
	<p class="grid_checkbox">
		<label for="mark_as_new"><?php _e('Mark as a New Product','ocart'); ?></label>
		<input type="checkbox" id="mark_as_new" name="mark_as_new" <?php checked( $mark_as_new, 'on' ); ?> /><span class="description"><?php _e('Check this box if you want to mark this product as new. When checked you can modify date range below.','ocart'); ?>
	</p>
	
    <p>
        <label for="new_start"><?php _e('Start date','ocart'); ?></label> 
        <input type="text" name="new_start" id="new_start" value="<?php if (!empty($new_start)) echo $new_start; ?>" placeholder="<?php _e('Immediate','ocart'); ?>" />
		<span class="description"><?php _e('When does the new sticker begin. Default to immediately.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="new_expiry"><?php _e('Expiry date','ocart'); ?></label> 
        <input type="text" name="new_expiry" id="new_expiry" value="<?php if (!empty($new_expiry)) echo $new_expiry; ?>" placeholder="<?php printf(__('%s Day(s)','ocart'), ocart_get_option('default_expire_for_new'));  ?>" />
		<span class="description"><?php _e('When does the new sticker expire. You can set a default period under theme settings.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="mark_as_new_text"><?php _e('New Sticker Text','ocart'); ?></label> 
        <input type="text" name="mark_as_new_text" id="mark_as_new_text" value="<?php if (!empty($mark_as_new_text)) echo $mark_as_new_text; ?>" placeholder="<?php _e('New!','ocart'); ?>" />
		<span class="description"><?php _e('Customize the text that appears on the new sticker.','ocart'); ?></span>
    </p>
	
	<div class="clear"></div>
	
	<h4 class="subpanel"><?php _e('Stock Options','ocart'); ?></h4>
	
	<p>
		<label for="weight"><?php _e('Weight','ocart'); ?></label>
		<input type="text" name="weight" id="weight" value="<?php if(!empty($weight)) echo $weight; ?>"/>
		<span class="description"><?php _e('Enter item weight without unit. Please set your weight unit in <strong>ocCommerce > Store Settings</strong>','ocart'); ?></span>
	</p>
	
	<p>
		<label for="sku"><?php _e('Product SKU','ocart'); ?></label>
		<input type="text" name="sku" id="sku" value="<?php if(!empty($sku)) echo $sku; ?>"/>
	</p>
	
	<p>
		<label for="sales"><?php _e('Sales','ocart'); ?></label>
		<input type="text" name="sales" id="sales" value="<?php if(!empty($sales)) echo $sales; ?>" />
		<span class="description"><?php _e('You can manually change product sales from this field.','ocart'); ?></span>
	</p>
	
	<p>
		<label for="status"><?php _e('Stock Status','ocart'); ?></label>
		<select name="status" id="status">
			<?php if (!isset($status)) $status = ''; ?>
			<option value="instock"<?php selected("instock", $status); ?>><?php _e('In Stock','ocart'); ?></option>
			<option value="sold"<?php selected("sold", $status); ?>><?php _e('Out of Stock','ocart'); ?></option>
		</select>
	</p>
	
	<p>
		<label for="stock"><?php _e('Stock Quantity','ocart'); ?></label>
		<input type="text" name="stock" id="stock" value="<?php if(!empty($stock)) echo $stock; ?>"/>
		<span class="description"><?php _e('Enter your available stock quantity if applicable here.','ocart'); ?></span>
	</p>
	
    <p>
        <label for="instock_text"><?php _e('In Stock Sticker Text','ocart'); ?></label> 
        <input type="text" name="instock_text" id="instock_text" value="<?php if (!empty($instock_text)) echo $instock_text; ?>" placeholder="<?php _e('In Stock','ocart'); ?>" />
		<span class="description"><?php _e('Customize the text that appears on the sticker when item is marked as in stock.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="sold_text"><?php _e('Out of Stock Sticker Text','ocart'); ?></label> 
        <input type="text" name="sold_text" id="sold_text" value="<?php if (!empty($sold_text)) echo $sold_text; ?>" placeholder="<?php _e('Out of Stock','ocart'); ?>" />
		<span class="description"><?php _e('Customize the text that appears on the sticker when item is marked as out of stock.','ocart'); ?></span>
    </p>
	
	<?php
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			foreach ($taxonomies as $taxonomy ) {
				if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
				?>
				<?php
					$terms = wp_get_post_terms($post->ID, $taxonomy, array("fields" => "all"));
					if ($terms && ! is_wp_error( $terms )) {
						foreach($terms as $term) {
						?>
						
						<span class="oc_numfield">
							<span class="oc_option"><ins><?php echo $term->name; ?></ins></span>
							<input type="text" name="<?php echo 'stock_'.$term->term_id; ?>" id="<?php echo 'stock_'.$term->term_id; ?>" value="<?php echo get_post_meta( $post->ID, 'stock_'.$term->term_id, true); ?>" placeholder="<?php _e('Qty','ocart'); ?>" />
						</span>
						
						<?php
						}
					}
				}
			}
		}
	?>
	<div class="clear"></div>
	
	<h4 class="subpanel"><?php _e('Shipping & Handling','ocart'); ?></h4>
	
	<p>
		<label><?php _e('Cost for Custom Quantity','ocart'); ?></label>
		<span class="subcost"><?php _e('Cost per','ocart'); ?><input type="text" id="add_cost_count" /><?php _e('item(s)','ocart'); ?><input type="text" id="add_cost_price" /><?php echo $ocart['currency']; ?><a href="" id="add_cost" class="button-secondary"><?php _e('Add / Update Cost','ocart'); ?></a></span>
	</p>

	<div id="CustomRates">
	<?php
	foreach($custom as $k=>$v) {
		if (strstr($k, 'cost_per_')) {
	?>
		<span class="subcost current-rate"><?php _e('Cost per','ocart'); ?><input type="text" disabled="disabled" value="<?php echo str_replace('cost_per_','',$k); ?>" /><?php _e('item(s)','ocart'); ?><input type="text" disabled="disabled" value="<?php echo $v[0]; ?>" /><?php echo $ocart['currency']; ?><a href="" class="button-secondary delete_cost" rel="<?php echo $k; ?>"><?php _e('Delete Cost','ocart'); ?></a></span><div class="clear"></div>
	<?php
		}
	}
	?>
	<div class="clear"></div></div><div class="clear"></div>
	<?php include_once get_template_directory().'/lib/js/custom.js.php'; ?>
	
	<h4 class="subpanel"><?php _e('Similar / Related Products','ocart'); ?></h4>
	
	<p><?php _e('Select the products that you want to display in <strong>Similar Products</strong> carousel. If you do not set any products here, the theme will automatically display related products based on relationship with other products.','ocart'); ?></p>

	<div class="boxed_selection">
	
	<?php
	$global_id = $post->ID;
	$randomposts = get_posts( array('post_type' => 'product','showposts' => -1, 'exclude' => $global_id ));
	foreach($randomposts as $random) { setup_postdata($random);
	?>
		<label><input type="checkbox" name="similar_products[]" value="<?php echo $random->ID; ?>" <?php if (get_post_meta($global_id, 'similar_products', true) && in_array($random->ID, get_post_meta($global_id, 'similar_products', true))) { echo "checked=\"checked\""; } ?> />&nbsp;&nbsp;&nbsp;<?php echo $random->post_title; ?></label>
	<?php } wp_reset_query(); wp_reset_postdata(); ?>
	
	</div>
	
	<h4 class="subpanel"><?php _e('Product Image Settings','ocart'); ?></h4>
	
	<p>
		<label for="imagesize"><?php _e('Image Size','ocart'); ?></label>
		<select name="imagesize" id="imagesize">
			<?php if (!isset($imagesize)) $imagesize = ''; ?>
			<option value="pic-default"<?php selected("pic-default", $imagesize); ?>><?php _e('Default','ocart'); ?></option>
			<option value="pic-small"<?php selected("pic-small", $imagesize); ?>><?php _e('Small (100x100)','ocart'); ?></option>
			<option value="pic-medium"<?php selected("pic-medium", $imagesize); ?>><?php _e('Medium (125x125)','ocart'); ?></option>
		</select>
	</p>
	
	<p>
		<label for="imagecrop_method"><?php _e('Zoom and Crop Method','ocart'); ?></label>
		<select name="imagecrop_method" id="imagecrop_method">
			<?php if (!isset($imagecrop_method)) $imagecrop_method = ''; ?>
			<option value="1"<?php selected(1, $imagecrop_method); ?>><?php _e('Crop Images','ocart'); ?></option>
			<option value="0"<?php selected(0, $imagecrop_method); ?>><?php _e('Do Not Crop','ocart'); ?></option>
		</select>
		<span class="description"><?php _e('Disable cropping for this product If you want to show full product image.','ocart'); ?></span>
	</p>

	<p>
		<label for="imagecrop"><?php _e('Image Crop','ocart'); ?></label>
		<select name="imagecrop" id="imagecrop">
			<?php if (!isset($imagecrop)) $imagecrop = ''; ?>
			<option value="c"<?php selected("c", $imagecrop); ?>><?php _e('Default','ocart'); ?></option>
			<option value="t"<?php selected("t", $imagecrop); ?>><?php _e('Top','ocart'); ?></option>
			<option value="b"<?php selected("b", $imagecrop); ?>><?php _e('Bottom','ocart'); ?></option>
		</select>
	</p>
	
	<?php
	$args = array(
				'post_type' => 'attachment',
				'numberposts' => -1,
				'post_parent' => $post->ID,
				'exclude' => get_post_thumbnail_id()
	);
	$attachments = get_posts($args);
	if ($attachments) {
	?>
	<p><strong><?php _e('Setup Product Hover Image','ocart'); ?></strong></p>
			<span class="radiobox">
			<input type="radio" name="hover_image" value="0"<?php if (isset($hover_image)) { checked(0, $hover_image); } ?>> <?php _e('Disable hover image','ocart'); ?>
			</span>
		<?php foreach ($attachments as $attachment) { ?>
			<span class="radiobox">
			<input type="radio" name="hover_image" value="<?php echo $attachment->ID; ?>"<?php if (isset($hover_image)) { checked($attachment->ID, $hover_image); } ?>><?php echo wp_get_attachment_image( $attachment->ID, array(100,100) ); ?>
			</span>
		<?php } ?>
	<?php
	}
	?>
	<div class="clear"></div>
	
	<h4 class="subpanel"><?php _e('Content Tabs','ocart'); ?></h4>
	
    <p>
        <label for="customtab_name"><?php _e('More Info Tab','ocart'); ?></label> 
        <input type="text" name="customtab_name" id="customtab_name" value="<?php if (isset($customtab_name)) { echo $customtab_name; } ?>" />
		<span class="description"><?php _e('Display extra product info in additional tab. Enter tab title here.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="customtab_content"><?php _e('More Info Tab Content','ocart'); ?></label> 
        <textarea name="customtab_content" id="customtab_content"><?php if (isset($customtab_content)) { echo $customtab_content; } ?></textarea>
    </p>
	
	<?php
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			foreach ($taxonomies as $taxonomy ) {
				if ($taxonomy == 'color') { // colors only
					$terms = wp_get_post_terms($post->ID, $taxonomy, array("fields" => "all"));
					if ($terms && ! is_wp_error( $terms )) {
					?>
	<h4 class="subpanel"><?php _e('Set Image for Each Color','ocart'); ?></h4>
	<p><?php _e('Here you can assign a product image for specific color. This helps your customers view the product in the color they select immediately.','ocart'); ?></p>
						<?php
						foreach($terms as $term) {
						?>
						
						<p>
							<label for="<?php echo 'color_'.$term->term_id.'_attachment'; ?>" style="margin:2px 50px 0 0;width: 128px;border: 1px solid #aaa;height:20px;background: <?php echo $term->name; ?>;"></label>
							<select type="text" name="<?php echo 'color_'.$term->term_id.'_attachment'; ?>" id="<?php echo 'color_'.$term->term_id.'_attachment'; ?>">
								<?php
								$args = array(
									'post_type' => 'attachment',
									'numberposts' => -1,
									'post_parent' => $post->ID,
								);
								$attachments = get_posts($args);
								if ($attachments) {
									?>
									<option value=""><?php _e('Choose image...','ocart'); ?></option>
									<?php
									foreach($attachments as $attachment) {
									?>
									<option value="<?php echo $attachment->ID; ?>"<?php selected($attachment->ID, get_post_meta($post->ID, 'color_'.$term->term_id.'_attachment', true)); ?>><?php echo $attachment->post_title; ?></option>
									<?php
									}
								}
								?>
							</select>
						</p>
					
						<?php
						}
					}
				}
			}
		}
	?>
	<div class="clear"></div>
	
	<h4 class="subpanel"><?php _e('Add Product Video','ocart'); ?></h4>
	
    <p>
        <label for="customtab_video"><?php _e('Video Embed Code','ocart'); ?></label> 
        <textarea name="customtab_video" id="customtab_video"><?php if (isset($customtab_video)) { echo $customtab_video; } ?></textarea>
    </p>

<?php
}

// save meta box
add_action( 'save_post', 'pd_meta_box_save' );  
function pd_meta_box_save( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post', $post_id ) ) return;
	
	// defaul fallback for blank fields
	if (empty($_POST['new_start'])) $_POST['new_start'] = date('Y-m-d');
	if (empty($_POST['new_expiry'])) {
		$date = new DateTime(date($_POST['new_start']));
		if (ocart_get_option('default_expire_for_new') == 1) {
			$date->modify("+1 day");
		} else {
			$date->modify("+".ocart_get_option('default_expire_for_new')." days");
		}
		$_POST['new_expiry'] = $date->format("Y-m-d");
	}
	
	// custom fields
	if (isset($_POST['price']))
		update_post_meta( $post_id, 'price', esc_attr( $_POST['price'] ) );
		
	if (isset($_POST['reg_price']))
		update_post_meta( $post_id, 'regular_price', esc_attr( $_POST['reg_price'] ) );
		
	if (isset($_POST['status']))
		update_post_meta( $post_id, 'status', esc_attr( $_POST['status'] ) );
		
	if (isset($_POST['stock']))
		update_post_meta( $post_id, 'stock', esc_attr( $_POST['stock'] ) );
		
	if (isset($_POST['instock_text']))
		update_post_meta( $post_id, 'instock_text', esc_attr( $_POST['instock_text'] ) );
		
	if (isset($_POST['sold_text']))
		update_post_meta( $post_id, 'sold_text', esc_attr( $_POST['sold_text'] ) );
		
	if (isset($_POST['tagline_term']))
		update_post_meta( $post_id, 'tagline_term', esc_attr( $_POST['tagline_term'] ) );
		
	if (isset($_POST['tagline']))
		update_post_meta( $post_id, 'tagline', esc_attr( $_POST['tagline'] ) );
		
	if (isset($_POST['sku']))
		update_post_meta( $post_id, 'sku', esc_attr( $_POST['sku'] ) );
		
	if (isset($_POST['sales']))
		update_post_meta( $post_id, 'sales', esc_attr( $_POST['sales'] ) );
		
	if (isset($_POST['weight']))
		update_post_meta( $post_id, 'weight', esc_attr( $_POST['weight'] ) );
		
	if (isset($_POST['imagesize']))
		update_post_meta( $post_id, 'imagesize', esc_attr( $_POST['imagesize'] ) );
		
	if (isset($_POST['imagecrop_method']))
		update_post_meta( $post_id, 'imagecrop_method', esc_attr( $_POST['imagecrop_method'] ) );
		
	if (isset($_POST['imagecrop']))
		update_post_meta( $post_id, 'imagecrop', esc_attr( $_POST['imagecrop'] ) );
		
	if (isset($_POST['hover_image']))
		update_post_meta( $post_id, 'hover_image', esc_attr( $_POST['hover_image'] ) );

	if (isset($_POST['customtab_name']))
		update_post_meta( $post_id, 'customtab_name', esc_attr( $_POST['customtab_name'] ) );
	
	if (isset($_POST['customtab_content']))
		update_post_meta( $post_id, 'customtab_content', wp_filter_post_kses( $_POST['customtab_content'] ) );
		
	if (isset($_POST['customtab_video']))
		update_post_meta( $post_id, 'customtab_video', $_POST['customtab_video']);
		
	if (isset($_POST['new_start']))
		update_post_meta( $post_id, 'new_start', $_POST['new_start']);
		
	if (isset($_POST['new_expiry']))
		update_post_meta( $post_id, 'new_expiry', $_POST['new_expiry']);
		
	if (isset($_POST['mark_as_new_text']))
		update_post_meta( $post_id, 'mark_as_new_text', $_POST['mark_as_new_text']);
	
	if (isset($_POST['mark_as_onsale_text']))
		update_post_meta( $post_id, 'mark_as_onsale_text', $_POST['mark_as_onsale_text']);

	// checkboxes
    $mark_as_new = isset( $_POST['mark_as_new'] ) && $_POST['mark_as_new'] ? 'on' : 'off';
    update_post_meta( $post_id, 'mark_as_new', $mark_as_new );
    $mark_as_onsale = isset( $_POST['mark_as_onsale'] ) && $_POST['mark_as_onsale'] ? 'on' : 'off';
    update_post_meta( $post_id, 'mark_as_onsale', $mark_as_onsale );

	@update_post_meta( $post_id, 'similar_products', $_POST['similar_products']);
	
	// mark product as out of stock
	if (isset($_POST['status']) && $_POST['status'] == 'sold' || isset($_POST['stock']) && $_POST['stock'] == 0 && $_POST['stock'] != '') {
		update_post_meta( $post_id, 'out_of_stock', 1);
	} else {
		// notify customers that it is available now!
		$subscribers = get_post_meta($post_id, 'get_notified', true);
		// now let us email the remaining users want to be notified
		if (is_array($subscribers)) {
			$post = get_post($post_id);
			$message = sprintf(__('Hi, The product "%s" is now available in stock again. You have subscribed to receive an email notification when this product becomes available again. Thanks!','ocart'), $post->post_title);
			foreach($subscribers as $email) {
				wp_mail($email, sprintf(__('[%1$s] %2$s is Now In Stock!','ocart'), get_bloginfo('name'), $post->post_title), $message, ocart_mail_headers());
			}
			update_post_meta($post_id, 'get_notified', '');
		}
	}
		
	// Set custom pricing for options
	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		foreach ($taxonomies as $taxonomy ) {
			if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
				$terms = wp_get_post_terms($post_id, $taxonomy, array("fields" => "all"));
				if ($terms && ! is_wp_error( $terms )) {
					foreach($terms as $term) {
						if(isset($_POST['product_'.$post_id.'_'.$term->taxonomy.'_'.$term->term_id])) {
							update_post_meta( $post_id, 'product_'.$post_id.'_'.$term->taxonomy.'_'.$term->term_id, $_POST['product_'.$post_id.'_'.$term->taxonomy.'_'.$term->term_id]);
						}
					}
				}
			}
		}
	}
	
	// Set custom stock levels for options
	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		foreach ($taxonomies as $taxonomy ) {
			if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
				$terms = wp_get_post_terms($post_id, $taxonomy, array("fields" => "all"));
				if ($terms && ! is_wp_error( $terms )) {
					foreach($terms as $term) {
						if (isset($_POST['stock_'.$term->term_id])) {
							update_post_meta( $post_id, 'stock_'.$term->term_id, $_POST[ 'stock_'.$term->term_id ]);
						}
					}
				}
			}
		}
	}
	
	// Set custom images for colors
	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		foreach ($taxonomies as $taxonomy ) {
			if ($taxonomy == 'color') { // colors only
				$terms = wp_get_post_terms($post_id, $taxonomy, array("fields" => "all"));
				if ($terms && ! is_wp_error( $terms )) {
					foreach($terms as $term) {
						if(isset($_POST['color_'.$term->term_id.'_attachment'])) {
							update_post_meta( $post_id, 'color_'.$term->term_id.'_attachment', $_POST['color_'.$term->term_id.'_attachment']);
						}
					}
				}
			}
		}
	}

}

?>