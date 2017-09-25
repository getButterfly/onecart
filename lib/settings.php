<?php

// filter empty options
function ocart_filter_empty_option($o) {
	if (isset($o)) {
		return $o;
	} else {
		return '';
	}
}

// update theme logo
if (isset($_POST['save_logo'])) {
	$allowed_file_types = array('jpg' =>'image/jpg','jpeg' =>'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png');
	$overrides = array( 'test_form' => false, 'mimes' => $allowed_file_types );
	$file = wp_handle_upload($_FILES['file_logo'], $overrides);
	if (isset($file['error'])) {
		$err = $file['error'];
	} else {
		// save logo file
		update_option('occommerce_logo_url', $file['url']);
		$upload_ok = __('Your file has been uploaded successfully!','ocart');
	}
}

// remove attribute
if (isset($_POST['remove_attr'])) {
	$attrs = get_option('occommerce_custom_attributes');
	foreach ($attrs as $key => $attr) {
		unset($attrs[$key]);
		$attr_file = get_template_directory().'/lib/custom/'.$attr['slug'].'.php';
		if (file_exists($attr_file)) {
			unlink($attr_file);
		}
	}
	update_option('occommerce_custom_attributes', $attrs);
}

// reset skins
if (isset($_POST['reset_skin'])) {

	// basically we need to delete all options for that skin
	if (!isset($this->options['skin'])) {
		$skin = 'default';
	} else {
		$skin = $this->options['skin'];
	}
	$default_skin_array = get_option('occommerce_skin_'.$skin);
	foreach($default_skin_array as $rule => $value) {
		unset($this->options[$this->options['skin'].'_'.$rule]);
	}
	
	// update options
	update_option('ocart', $this->options);
	$savedmsg = __('Your changes have been saved.','ocart');

}

// reset individual skin settings
if (!isset($this->options['skin'])) {
	$skin = 'default';
} else {
	$skin = $this->options['skin'];
}
$default_skin_array = get_option('occommerce_skin_'.$skin);
foreach($default_skin_array as $rule => $value) {
	if (isset($_POST['reset_'.$rule])) {
		// unset that rule
		unset($this->options[$this->options['skin'].'_'.$rule]);
		update_option('ocart', $this->options);
		$savedmsg = __('Your changes have been saved.','ocart');
	}
}

// add new zone
if (isset($_POST['new_zone'])) {

	// condition
	if (!empty($_POST['name_new_zone'])) {
	
	$zones = get_option('occommerce_zones');
	$count = count($zones)-1;
	
	// weight
	if (!empty($_POST['weight_new_zone'])) {
		$weight = explode(PHP_EOL, $_POST['weight_new_zone']);
	} else {
		$weight = $zones[0]['pricing']['weight']; // default
	}
	
	// handling
	if (!empty($_POST['handling_new_zone'])) {
		$handling = explode(PHP_EOL, $_POST['handling_new_zone']);
		foreach($handling as $rule) {
			$explode_rule = explode('|', $rule);
			$handling_rules[$explode_rule[0]] = $explode_rule[1];
		}
	} else {
		$handling_rules = $zones[0]['pricing']['handling']; // default
	}

	$zones[] = array(
			'name' => $_POST['name_new_zone'],
			'filters' => array(
				'regions' => ocart_insert_zone_setting_filters('regions_new_zone', $_POST['regions_new_zone'])
			),
			'pricing' => array(
				'fixed_tax' => ocart_insert_zone_setting('fixed_tax', $_POST['fixed_tax_new_zone']),
				'pct_tax' => ocart_insert_zone_setting('pct_tax', $_POST['pct_tax_new_zone']),
				'fixed_shipping' => ocart_insert_zone_setting('fixed_shipping', $_POST['fixed_shipping_new_zone']),
				'pct_shipping' => ocart_insert_zone_setting('pct_shipping', $_POST['pct_shipping_new_zone']),
				'weight' => $weight,
				'handling' => $handling_rules
			)
	);
	
	update_option('occommerce_zones',$zones);
	
	$savedmsg = __('Your new zone has been created.','ocart');
	
	}

}

// remove a zone
$zones = get_option('occommerce_zones');
for ($i = -1; $i <= count($zones); $i++) {
	if (isset($_POST['remove_zone_'.$i])) {
		$zones[$i]['status'] = 'disable';
	}
}
update_option('occommerce_zones', $zones);

// update theme options
if (isset($_POST['save'])) {

	// post fields
	foreach($_POST as $key => $value) {
		if ($key != 'save') {
			// save fees
			if (strstr($key, '_fee')) {
				$this->options["$key"] = (double)$_POST["$key"];
			// save html content
			} elseif (strstr($key, 'html_')) {
				$this->options["$key"] = stripslashes($_POST["$key"]);
			// do not save these options
			} elseif ($key == 'paymethods' || $key == 'product_attr' || $key == 'grid_attr' || $key == 'scroll_attr' || $key == 'browser_attr' || $key == 'occommerce_allowed_countries' || $key == 'occommerce_disallowed_countries' || $key == 'occommerce_allowed_shipping_destinations' || $key == 'occommerce_disallowed_shipping_destinations' || $key == 'create_attribute_name' ) {

			// standard save
			} else {
				$this->options["$key"] = esc_attr($_POST["$key"]);
			}
		}
	}
	
	// save zones to occommerce_zones dynamically (edit zone only)
	$editzones = get_option('occommerce_zones');
	foreach($_POST as $key => $value) {
		if ($key != 'save') {
			if (!in_array($key, array('regions_new_zone','fixed_tax_new_zone','pct_tax_new_zone','fixed_shipping_new_zone','pct_shipping_new_zone',
			'weight_new_zone','handling_new_zone','name_new_zone'))) { // edit zones
				if (strstr($key, 'regions_') || strstr($key, 'fixed_tax_') || strstr($key, 'pct_tax_') || strstr($key, 'fixed_shipping_') || strstr($key, 'pct_shipping_') || strstr($key, 'weight_') || strstr($key, 'handling_')) {
					$indexi = explode('_',$key);
					$index = end($indexi);
					// save filters
					if (isset($_POST['regions_'.$index])) { $editzones[$index]['filters']['regions'] = explode(',', $_POST['regions_'.$index]); }
					if (isset($_POST['fixed_tax_'.$index])) { $editzones[$index]['pricing']['fixed_tax'] = $_POST['fixed_tax_'.$index]; }
					if (isset($_POST['pct_tax_'.$index])) { $editzones[$index]['pricing']['pct_tax'] = $_POST['pct_tax_'.$index]; }
					if (isset($_POST['fixed_shipping_'.$index])) { $editzones[$index]['pricing']['fixed_shipping'] = $_POST['fixed_shipping_'.$index]; }
					if (isset($_POST['pct_shipping_'.$index])) { $editzones[$index]['pricing']['pct_shipping'] = $_POST['pct_shipping_'.$index]; }
					// weight rules
					if (!empty($_POST['weight_'.$index])) {
						$weight = explode(PHP_EOL, $_POST['weight_'.$index]);
						$editzones[$index]['pricing']['weight'] = $weight;
					} else {
						$editzones[$index]['pricing']['weight'] = array();
					}
					// handling rules
					if (!empty($_POST['handling_'.$index])) {
						$handling = explode(PHP_EOL, $_POST['handling_'.$index]);
						foreach($handling as $rule) {
							$explode_rule = explode('|', $rule);
							$handling_rules[$explode_rule[0]] = $explode_rule[1];
						}
						$editzones[$index]['pricing']['handling'] = $handling_rules;
					} else {
						$editzones[$index]['pricing']['handling'] = array();
					}
				}
			}
		}
	}

	update_option('occommerce_zones', $editzones);
	
	// enter new attributes
	if (isset($_POST['create_attribute_slug']) && isset($_POST['create_attribute_plural']) && isset($_POST['create_attribute_single'])) {
		// create taxonomy file
		$slug = $_POST['create_attribute_slug'];
		$plural = $_POST['create_attribute_plural'];
		$single = $_POST['create_attribute_single'];
		if (ctype_lower($slug) && ctype_lower($plural) && ctype_lower($single)) {
		
			// custom attr
			$attrs = get_option('occommerce_custom_attributes');
			
			// create new custom attributes file
			file_put_contents(get_template_directory().'/lib/custom/'.$slug.'.php', "<?php
		
			add_action( 'init', 'create_".$slug."_taxonomies', 0 );
			function create_".$slug."_taxonomies() {
			  register_taxonomy('$slug','product',array(
				'hierarchical' => false,
				'labels' => array(
				'name' => _x( '".ucfirst($plural)."', 'taxonomy general name' ),
				'singular_name' => _x( '".ucfirst($single)."', 'taxonomy singular name' ),
				'search_items' =>  __( 'Search ".ucfirst($plural)."' ),
				'popular_items' => __( 'Popular ".ucfirst($plural)."' ),
				'all_items' => __( 'All ".ucfirst($plural)."' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit ".ucfirst($single)."' ), 
				'update_item' => __( 'Update ".ucfirst($single)."' ),
				'add_new_item' => __( 'Add New ".ucfirst($single)."' ),
				'new_item_name' => __( 'New ".ucfirst($single)." Name' ),
				'separate_items_with_commas' => __( 'Separate ".$plural." with commas' ),
				'add_or_remove_items' => __( 'Add or remove ".$plural."' ),
				'choose_from_most_used' => __( 'Choose from the most used ".$plural."' ),
				'menu_name' => __( '".ucfirst($plural)."' ),
			  ),
				'show_ui' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array( 'slug' => '".$slug."' ),
			  ));
			}
		
			?>");
		
			// save the new custom attribute
			$attrs[] = array( 'slug' => $slug, 'single' => $single, 'plural' => $plural );
			update_option('occommerce_custom_attributes', $attrs);
			
		} else {
			if (!ctype_lower($slug)) {
				$err_slug = __('Please enter a valid attribute with all lowercase letters.','ocart');
			} elseif (!ctype_lower($plural)) {
				$err_plural = __('Please enter a valid attribute with all lowercase letters.','ocart');
			} elseif (!ctype_lower($single)) {
				$err_single = __('Please enter a valid attribute with all lowercase letters.','ocart');
			}
		}
	}
	
	// update country lists
	if (isset( $_POST['occommerce_disallowed_countries'] ) ) {
		update_option('occommerce_disallowed_countries', $_POST['occommerce_disallowed_countries']);
		$all = get_option('occommerce_all_countries');
		foreach($all as $k => $v) {
			if (!in_array($k, $_POST['occommerce_disallowed_countries'])) {
				$updated_countries["$k"] = $v;
			}
		}
		update_option('occommerce_allowed_countries', $updated_countries);
	} else {
	// all allowed
	update_option('occommerce_disallowed_countries', '');
	update_option('occommerce_allowed_countries', get_option('occommerce_all_countries'));
	}
	
	// update shipping zones
	if (isset( $_POST['occommerce_disallowed_shipping_destinations'] ) ) {
		update_option('occommerce_disallowed_shipping_destinations', $_POST['occommerce_disallowed_shipping_destinations']);
		$all = get_option('occommerce_all_countries');
		foreach($all as $k => $v) {
			if (!in_array($k, $_POST['occommerce_disallowed_shipping_destinations'])) {
				$updated_countries_s["$k"] = $v;
			}
		}
		update_option('occommerce_allowed_shipping_destinations', $updated_countries_s);
	} else {
	// all allowed
	update_option('occommerce_disallowed_shipping_destinations', '');
	update_option('occommerce_allowed_shipping_destinations', get_option('occommerce_all_countries'));
	}
	
	// save checkbox
	$arr = array ( 'paymethods', 'product_attr', 'grid_attr', 'scroll_attr', 'browser_attr' );
	foreach($arr as $check ) {
		if (isset($_POST["$check"]) && is_array($_POST["$check"])) {
			$this->options["$check"] = $_POST["$check"];
			update_option('occommerce_'.$check.'_sortables', $_POST["$check"]);
		} else {
			$this->options["$check"] = null;
		}
	}
	
	// add custom currencies dynamically
	if (isset($this->options['multi_currency'])) {
	$supported_c = $this->options['multi_currency'];
	$supported_c = str_replace(' ','', $supported_c);
	$currencies = explode(',', $supported_c);
	$builtincurrencies = get_option('occommerce_currencies');
	foreach($currencies as $currency) {
		if (!in_array($currency, $builtincurrencies)) {
			ocart_add_currency($currency);
		}
	}
	}

	// update options
	update_option('ocart', $this->options);
	$savedmsg = __('Your changes have been saved.','ocart');

}

?>

<?php if (isset($savedmsg)) { ?>
<script>
jQuery(document).ready(function(){
	jQuery('.dashboard_save').fadeOut(function(){
		jQuery(this).prepend('<span><?php echo $savedmsg; ?></span>').fadeIn()
	});
});
</script>
<?php } ?>

<form action="" method="post" enctype="multipart/form-data" id="cp-form"><!--start main form-->

<div class="dashboard">

	<div class="dashboard_top">
		<div class="dashboard_name"><?php printf(__('<abbr>ocCommerce</abbr> Framework<span>v%s</span>','ocart'), wp_get_theme()->Version); ?></div>
		<div class="dashboard_save"><input type="submit" name="save" id="save" class="button button-primary" value="<?php _e('Save changes','ocart'); ?>" /></div>
	</div>
	
	<ul class="dashboard_tabs">
		<li><a href="#" rel="tab1"><?php _e('General Settings','ocart'); ?></a></li>
		<li><a href="#" rel="tab8"><?php _e('SEO Panel','ocart'); ?></a></li>
		<li><a href="#" rel="tab9"><?php _e('Store Settings','ocart'); ?></a></li>
		<li><a href="#" rel="tab12"><?php _e('Product Attributes','ocart'); ?></a></li>
		<li><a href="#" rel="tab2"><?php _e('Appearance','ocart'); ?></a></li>
		<li><a href="#" rel="tab3"><?php _e('Customization','ocart'); ?></a></li>
		<li><a href="#" rel="tab10"><?php _e('Customize Checkout','ocart'); ?></a></li>
		<li><a href="#" rel="tab4"><?php _e('Setup Payments','ocart'); ?></a></li>
		<li><a href="#" rel="tab5"><?php _e('Shipping and Tax','ocart'); ?></a></li>
		<li><a href="#" rel="tab6"><?php _e('Email Templates','ocart'); ?></a></li>
		<li><a href="#" rel="tab7"><?php _e('Social Settings','ocart'); ?></a></li>
		<?php /** pluggable allow to add more admin tabs **/
			do_action('ocart_admin_tabs_hook');
		?>
	</ul>
	
	<?php do_action('ocart_admin_settings_hook', $this->options); ?>
	
	<div class="dashboard_body" id="tab8"><?php ocart_print_update_notice() ?>
	
		<h1><?php _e('Global SEO Options','ocart'); ?></h1>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_hometitle"><?php _e('Homepage Title','ocart'); ?></label>
				<input type="text" name="seo_hometitle" id="seo_hometitle" value="<?php if (isset($this->options['seo_hometitle'])) echo $this->options['seo_hometitle']; ?>" />
				<span class="usage"><?php _e('This is the title of your store homepage.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_seperator"><?php _e('Homepage Title Seperator','ocart'); ?></label>
				<input type="text" name="seo_seperator" id="seo_seperator" value="<?php if (isset($this->options['seo_seperator'])) echo $this->options['seo_seperator']; ?>" />
				<span class="usage"><?php _e('This is the seperator which can be displayed before or after page title. e.g. <code>| My Store</code>','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_seperator_position"><?php _e('Seperator Position','ocart'); ?></label>
				<select name="seo_seperator_position" id="seo_seperator_position">
					<option value="1"<?php selected(1, $this->options['seo_seperator_position']); ?>><?php _e('After Title','ocart'); ?></option>
					<option value="2"<?php selected(2, $this->options['seo_seperator_position']); ?>><?php _e('Before Title','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('This is to show the seperator text after or before the main title.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_homedesc"><?php _e('Homepage Meta Description','ocart'); ?></label>
				<input type="text" name="seo_homedesc" id="seo_homedesc" value="<?php if (isset($this->options['seo_homedesc'])) echo $this->options['seo_homedesc']; ?>" />
				<span class="usage"><?php _e('This is the description of your store homepage.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Search SEO Options','ocart'); ?></h1>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_hometitle_search"><?php _e('Search Page Title','ocart'); ?></label>
				<input type="text" name="seo_hometitle_search" id="seo_hometitle_search" value="<?php if (isset($this->options['seo_hometitle_search'])) echo $this->options['seo_hometitle_search']; ?>" />
				<span class="usage"><?php _e('This is the title of your store search results page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_seperator_search"><?php _e('Search Page Title Seperator','ocart'); ?></label>
				<input type="text" name="seo_seperator_search" id="seo_seperator_search" value="<?php if (isset($this->options['seo_seperator_search'])) echo $this->options['seo_seperator_search']; ?>" />
				<span class="usage"><?php _e('This is the seperator which can be displayed before or after page title. e.g. <code>| My Store</code>','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_seperator_position_search"><?php _e('Searc Page Seperator Position','ocart'); ?></label>
				<select name="seo_seperator_position_search" id="seo_seperator_position_search">
					<option value="1"<?php selected(1, $this->options['seo_seperator_position_search']); ?>><?php _e('After Title','ocart'); ?></option>
					<option value="2"<?php selected(2, $this->options['seo_seperator_position_search']); ?>><?php _e('Before Title','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('This is to show the seperator text after or before the main title.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_homedesc_search"><?php _e('Search Page Meta Description','ocart'); ?></label>
				<input type="text" name="seo_homedesc_search" id="seo_homedesc_search" value="<?php if (isset($this->options['seo_homedesc_search'])) echo $this->options['seo_homedesc_search']; ?>" />
				<span class="usage"><?php _e('This is the description of your store search results page.','ocart'); ?></span>
			</div>
		</div>
	
		<div class="clear"></div>
		
		<h1><?php _e('404 Page SEO Options','ocart'); ?></h1>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_hometitle_404"><?php _e('404 Page Title','ocart'); ?></label>
				<input type="text" name="seo_hometitle_404" id="seo_hometitle_404" value="<?php if (isset($this->options['seo_hometitle_404'])) echo $this->options['seo_hometitle_404']; ?>" />
				<span class="usage"><?php _e('This is the title of your store 404 page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_seperator_404"><?php _e('404 Page Title Seperator','ocart'); ?></label>
				<input type="text" name="seo_seperator_404" id="seo_seperator_404" value="<?php if (isset($this->options['seo_seperator_404'])) echo $this->options['seo_seperator_404']; ?>" />
				<span class="usage"><?php _e('This is the seperator which can be displayed before or after page title. e.g. <code>| My Store</code>','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_seperator_position_404"><?php _e('404 Page Seperator Position','ocart'); ?></label>
				<select name="seo_seperator_position_404" id="seo_seperator_position_404">
					<option value="1"<?php selected(1, $this->options['seo_seperator_position_404']); ?>><?php _e('After Title','ocart'); ?></option>
					<option value="2"<?php selected(2, $this->options['seo_seperator_position_404']); ?>><?php _e('Before Title','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('This is to show the seperator text after or before the main title.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="seo_homedesc_404"><?php _e('404 Page Meta Description','ocart'); ?></label>
				<input type="text" name="seo_homedesc_404" id="seo_homedesc_404" value="<?php if (isset($this->options['seo_homedesc_404'])) echo $this->options['seo_homedesc_404']; ?>" />
				<span class="usage"><?php _e('This is the description of your store 404 page.','ocart'); ?></span>
			</div>
		</div>

	</div>
	
	<div class="dashboard_body" id="tab12"><?php ocart_print_update_notice() ?>
	
		<h1><?php _e('Default Product Attributes','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if ($taxonomies) {
					foreach($taxonomies as $taxonomy) {
						if (in_array($taxonomy, array('product_category', 'brand', 'color', 'size', 'collection' ) ) ) {
							$the_tax = get_taxonomy( $taxonomy );
							echo '<span class="dashboard-attr">'.$the_tax->labels->name.'</span>';
						}
					}
				}
				?>
				<span class="usage"><?php _e('The default product attributes which come with theme (Product options). They cannot be removed, but you can add unlimited attributes below.','ocart'); ?></span>
			</div>
		</div>
	
		<div class="clear"></div>
	
		<h1><?php _e('Custom Product Attributes','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if ($taxonomies) {
					foreach($taxonomies as $taxonomy) {
						if (!in_array($taxonomy, array('product_category', 'brand', 'color', 'size', 'collection' ) ) ) { // built-in, non custom
						if (file_exists(get_template_directory().'/lib/custom/'.$taxonomy.'.php')) {
							$the_tax = get_taxonomy( $taxonomy );
							echo '<span class="dashboard-attr">'.$the_tax->labels->name.'&nbsp;&nbsp;<input type="submit" name="remove_attr" id="remove_attr" value="'.$taxonomy.'" class="button button-primary" style="text-indent: -9999px;background: url('.get_template_directory_uri().'/lib/img/remove.png) no-repeat center;" /></span>';
							$custom_tax = true;
						}
						}
					}
				}
				if (!isset($custom_tax)) {
					echo '<p>'.__('You did not add any custom taxonomies yet.','ocart').'</p>';
				}
				?>
				<span class="usage"><?php _e('The product attributes that you have added to ocCommerce system will be displayed here.','ocart'); ?></span>
			</div>
		</div>
	
		<div class="clear"></div>
		
		<h1><?php _e('Add a New Attribute','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="create_attribute_slug"><?php _e('New Attribute Slug/Shortname','ocart'); ?></label>
				<input type="text" name="create_attribute_slug" id="create_attribute_slug" value="<?php if (isset($_POST['create_attribute_slug'])) echo $_POST['create_attribute_slug']; ?>" />
				<span class="usage"><?php _e('Enter an all <strong>lowercase</strong> short name for your new attribute. It will be used as a <strong>slug</strong> in your URLs, so please make it meaningful. <code>Examples: style, type, metal, model</code>','ocart'); ?></span>
				<?php if (isset($err_slug)) { ?><span class="err"><?php echo $err_slug; ?></span><?php } ?>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="create_attribute_plural"><?php _e('New Attribute Plural Word','ocart'); ?></label>
				<input type="text" name="create_attribute_plural" id="create_attribute_plural" value="<?php if (isset($_POST['create_attribute_plural'])) echo $_POST['create_attribute_plural']; ?>" />
				<span class="usage"><?php _e('Enter an all <strong>lowercase</strong> plural form of your new attribute. <code>Examples: styles, types, metals, models</code>','ocart'); ?></span>
				<?php if (isset($err_plural)) { ?><span class="err"><?php echo $err_plural; ?></span><?php } ?>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="create_attribute_single"><?php _e('New Attribute Single Word','ocart'); ?></label>
				<input type="text" name="create_attribute_single" id="create_attribute_single" value="<?php if (isset($_POST['create_attribute_single'])) echo $_POST['create_attribute_single']; ?>" />
				<span class="usage"><?php _e('Enter an all <strong>lowercase</strong> single form of your new attribute. <code>Examples: style, type, metal, model</code>','ocart'); ?></span>
				<?php if (isset($err_single)) { ?><span class="err"><?php echo $err_single; ?></span><?php } ?>
			</div>
		</div>
		
	</div>
	
	<div class="dashboard_body" id="tab10"><?php ocart_print_update_notice() ?>
	
		<h1><?php _e('Accepted Orders','ocart'); ?></h1>
		
		<div class="ocfield ocfield_multi">
			
			<div class="subfield subfield_multi">
				<label><?php _e('Allowed Countries','ocart'); ?></label>
				<select name="occommerce_allowed_countries[]" size="10" multiple="true" id="select1">
				<?php $countries = get_option('occommerce_allowed_countries');
				foreach($countries as $code => $val) { ?>
					<option value="<?php echo $code; ?>"><?php echo $val; ?></option>
				<?php } ?>
				</select>
				<a href="#" id="add_country"><?php _e('Add to Disallowed Countries','ocart'); ?></a>
			</div>
			
			<div class="subfield subfield_multi">
				<label><?php _e('Disallowed Countries','ocart'); ?></label>
				<select name="occommerce_disallowed_countries[]" size="10" multiple="true" id="select2">
				<?php $countries = get_option('occommerce_disallowed_countries'); $all = get_option('occommerce_all_countries');
				if ($countries) {
				foreach($countries as $country) { ?>
					<option value="<?php echo $country; ?>"><?php echo $all["$country"]; ?></option>
				<?php } } ?>
				</select>
				<a href="#" id="remove_country"><?php _e('Remove from Disallowed Countries','ocart'); ?></a>
			</div>
			
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Shipping Destinations','ocart'); ?></h1>
		
		<div class="ocfield ocfield_multi">
			
			<div class="subfield subfield_multi">
				<label><?php _e('Allowed Countries','ocart'); ?></label>
				<select name="occommerce_allowed_shipping_destinations[]" size="10" multiple="true" id="select3">
				<?php $countries = get_option('occommerce_allowed_shipping_destinations');
				if ($countries) {
				foreach($countries as $code => $val) { ?>
					<option value="<?php echo $code; ?>"><?php echo $val; ?></option>
				<?php } } ?>
				</select>
				<a href="#" id="add_country2"><?php _e('Add to Disallowed Countries','ocart'); ?></a>
			</div>
			
			<div class="subfield subfield_multi">
				<label><?php _e('Disallowed Countries','ocart'); ?></label>
				<select name="occommerce_disallowed_shipping_destinations[]" size="10" multiple="true" id="select4">
				<?php $countries = get_option('occommerce_disallowed_shipping_destinations'); $all = get_option('occommerce_all_countries');
				if ($countries) {
				foreach($countries as $country) { ?>
					<option value="<?php echo $country; ?>"><?php echo $all["$country"]; ?></option>
				<?php } } ?>
				</select>
				<a href="#" id="remove_country2"><?php _e('Remove from Disallowed Countries','ocart'); ?></a>
			</div>
			
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="page_terms"><?php _e('Require Tems & Conditions agreement','ocart'); ?></label>
				<select name="page_terms" id="page_terms">
					<option value="0"<?php selected(0, $this->options['page_terms']); ?>><?php _e('Do not require','ocart'); ?></option>
					<?php $pages = get_pages();
					foreach($pages as $page) {
					?>
					<option value="<?php echo $page->ID; ?>"<?php selected($page->ID, $this->options['page_terms']); ?>><?php echo $page->post_title; ?></option>
					<?php } ?>
				</select>
				<span class="usage"><?php _e('If you created a <strong>Terms and Conditions</strong> page, you can select it from Pages list here. If you turn this option off, customers will not be prompted to accept terms before purchase.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="checkout_extras"><?php _e('Custom Delivery Date Field','ocart'); ?></label>
				<select name="checkout_extras" id="checkout_extras">
					<option value="1"<?php selected(1, $this->options['checkout_extras']); ?>><?php _e('Active','ocart'); ?></option>
					<option value="0"<?php selected(0, $this->options['checkout_extras']); ?>><?php _e('Inactive','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('If you enable this module, customers will be able to choose <strong>custom delivery date</strong> in checkout step.','ocart'); ?></span>
			</div>
		</div>
		
	</div>
	
	<div class="dashboard_body" id="tab1"><?php ocart_print_update_notice() ?>
		
		<!-- logo upload -->
		<div class="ocfield">
			<div class="subfield">
				<label><?php _e('Upload Logo','ocart'); ?></label>
				<div class="file_wrapper">
					<input type="file" name="file_logo" size="30" />
				</div>
				<input type="submit" name="save_logo" value="<?php _e('Upload','ocart'); ?>" class="button button-primary" />
				<span class="usage"><?php _e('Use this form to upload/change your site logo. Here is your currently <b>active logo</b>:','ocart'); ?>
				<?php ocart_logo_img(); ?></span>
				<?php if (isset($err)) { ?><span class="err"><?php echo $err; ?></span><?php } ?>
				<?php if (isset($upload_ok)) { ?><span class="upload_ok"><?php echo $upload_ok; ?></span><?php } ?>
			</div>
		</div>
		<!-- logo upload -->
		
		<div class="ocfield">
			<div class="subfield">
				<label for="wishlist"><?php _e('Enable Wishlist','ocart'); ?></label>
				<select name="wishlist" id="wishlist">
					<option value="1"<?php selected('1', $this->options['wishlist']); ?>><?php _e('Enabled','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['wishlist']); ?>><?php _e('Disabled','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Customers can have their wishlists. They can add/remove products from their wishlist easily.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="ocml"><?php _e('Enable Multi-Language Module','ocart'); ?></label>
				<select name="ocml" id="ocml">
					<option value="1"<?php selected('1', $this->options['ocml']); ?>><?php _e('Enabled','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['ocml']); ?>><?php _e('Disabled','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Enable <strong>multi-language</strong> store by activating this option. All languages should be stored in <code>/lang</code> folder.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="ocmc"><?php _e('Enable Multi-Currency Module','ocart'); ?></label>
				<select name="ocmc" id="ocmc">
					<option value="1"<?php selected('1', $this->options['ocmc']); ?>><?php _e('Enabled','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['ocmc']); ?>><?php _e('Disabled','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Enable multi-currency store by activating this option.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="emptyterms"><?php _e('Empty Store Categories & Terms','ocart'); ?></label>
				<select name="emptyterms" id="emptyterms">
					<option value="0"<?php selected('0', $this->options['emptyterms']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="1"<?php selected('1', $this->options['emptyterms']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Choose whether to display or hide empty categories in your store navigation and browser.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="showcount"><?php _e('Show Products Count in Filter Popup','ocart'); ?></label>
				<select name="showcount" id="showcount">
					<option value="1"<?php selected('1', $this->options['showcount']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['showcount']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Display or hide the <b>count of products</b> inside each category/custom taxonomy. The product count can be seen in filter/product browser.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="paymethods"><?php _e('Footer Payment Logos','ocart'); ?></label>
				<div class="paymethods">
				<?php
				$payments = get_option('occommerce_payment_logos');
				foreach($payments as $pay) {
				?>
				<div class="paymethod <?php echo $pay; ?>"><input type="checkbox" name="paymethods[]" value="<?php echo $pay; ?>" <?php if (!empty($this->options['paymethods']) && in_array($pay, $this->options['paymethods'])) { echo "checked=\"checked\""; } ?> /></div>
				<?php } ?>
				</div>
				<span class="usage"><?php _e('Select payment logos you want to display in your store footer. <b>Please note:</b> If you select a lot of payment logos, your footer may not look proper.','ocart'); ?></span>
			</div>
		</div>
		
	</div>
	
	<div class="dashboard_body" id="tab9"><?php ocart_print_update_notice() ?>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="sort_products"><?php _e('Sorting Products','ocart'); ?></label>
				<select name="sort_products" id="sort_products">
					<option value="1"<?php selected(1, $this->options['sort_products']); ?>><?php _e('Menu Order','ocart'); ?></option>
					<option value="2"<?php selected(2, $this->options['sort_products']); ?>><?php _e('Date','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Choose how to sort/order products in the store.','ocart'); ?></span>
			</div>
		</div>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="catalog_version"><?php _e('Catalog View','ocart'); ?></label>
				<select name="catalog_version" id="catalog_version">
					<option value="1"<?php selected(1, $this->options['catalog_version']); ?>><?php _e('Slider','ocart'); ?></option>
					<option value="2"<?php selected(2, $this->options['catalog_version']); ?>><?php _e('Grid','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Choose the default products display mode here. The theme supports <b>Slider and Grid</b> view.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="force_lightbox"><?php _e('Open Products in Lightbox by default','ocart'); ?></label>
				<select name="force_lightbox" id="force_lightbox">
					<option value="0"<?php selected(0, $this->options['force_lightbox']); ?>><?php _e('No','ocart'); ?></option>
					<option value="1"<?php selected(1, $this->options['force_lightbox']); ?>><?php _e('Yes','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Choose the default products display mode here. The theme supports <b>Slider and Grid</b> view.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="single_product_in_popup"><?php _e('Open Single Product in Lightbox','ocart'); ?></label>
				<select name="single_product_in_popup" id="single_product_in_popup">
					<option value="0"<?php selected(0, $this->options['single_product_in_popup']); ?>><?php _e('No','ocart'); ?></option>
					<option value="1"<?php selected(1, $this->options['single_product_in_popup']); ?>><?php _e('Yes','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('If you choose yes here, single product pages will always open via lightbox popup.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="tax_included"><?php _e('Sales Tax Inclusion','ocart'); ?></label>
				<select name="tax_included" id="tax_included">
					<option value="0"<?php selected('0', $this->options['tax_included']); ?>><?php _e('Tax not included in Price','ocart'); ?></option>
					<option value="1"<?php selected('1', $this->options['tax_included']); ?>><?php _e('Tax included in Price','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('If tax is included in price, it will be shown but it will not be added to cart totals.','ocart'); ?></span>
			</div>
		</div>

		<div class="ocfield">
			<div class="subfield">
				<label for="disable_cart"><?php _e('Disable Cart/Purchase','ocart'); ?></label>
				<select name="disable_cart" id="disable_cart">
					<option value="0"<?php selected('0', $this->options['disable_cart']); ?>><?php _e('No','ocart'); ?></option>
					<option value="1"<?php selected('1', $this->options['disable_cart']); ?>><?php _e('Yes','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('You may select <b>\'Yes\'</b> to completely deactivate the cart/purchase module. For example: If you want to display or showcase your items only and do not want your clients to purchase your products.','ocart'); ?></span>
			</div>
		</div>

		<div class="ocfield">
			<div class="subfield">
				<label for="disable_prices"><?php _e('Disable Prices in Catalog Mode','ocart'); ?></label>
				<select name="disable_prices" id="disable_prices">
					<option value="0"<?php selected('0', $this->options['disable_prices']); ?>><?php _e('No','ocart'); ?></option>
					<option value="1"<?php selected('1', $this->options['disable_prices']); ?>><?php _e('Yes','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Product Browser Popup','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label><?php _e('Browse Products Filters','ocart'); ?></label>
				<div class="sortable-attributes">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					$taxonomies = sortArrayByArray($taxonomies, get_option('occommerce_browser_attr_sortables'));
					foreach ($taxonomies as $taxonomy ) {
						$the_tax = get_taxonomy( $taxonomy );
				?>
				<label class="checkbox_1"><input type="checkbox" name="browser_attr[]" value="<?php echo $taxonomy; ?>" <?php if (!empty($this->options['browser_attr']) && in_array($taxonomy, $this->options['browser_attr'])) { echo "checked=\"checked\""; } ?> /> <?php echo $the_tax->labels->name; ?></label>
				<?php
					}
				}
				?>
				</div>
				<span class="usage"><?php _e('Choose which options or attributes will build the filters in <b>Browser Popup</b>.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Grid View Settings','ocart'); ?></h1>

		<div class="ocfield">
			<div class="subfield">
				<label for="grid_default_tagline_attribute"><?php _e('Global Tagline Attribute','ocart'); ?></label>
				<select name="grid_default_tagline_attribute" id="grid_default_tagline_attribute">
				<?php
						$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					$taxonomies = array_reverse($taxonomies);
					foreach ($taxonomies as $taxonomy ) {
					?>
					<option value="<?php echo $taxonomy; ?>"<?php if (isset($this->options['grid_default_tagline_attribute']) && $taxonomy == $this->options['grid_default_tagline_attribute']) { echo ' selected="selected"'; } ?>><?php echo ocart_get_taxonomy_nicename( $taxonomy ); ?></option>
				<?php
					}
				}
				?>
				</select>
				<span class="usage"><?php _e('The default attribute that forms a tagline. You can override this setting per product basis, or apply a custom tagline.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="grid_prod_num"><?php _e('Number of Loaded Products','ocart'); ?></label>
				<input type="text" name="grid_prod_num" id="grid_prod_num" value="<?php echo $this->options['grid_prod_num']; ?>" />
				<span class="usage"><?php _e('How many products to show on every load in <strong>Grid</strong> display.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="max_grid_prods"><?php _e('Maximum products per page','ocart'); ?></label>
				<input type="text" name="max_grid_prods" id="max_grid_prods" value="<?php echo $this->options['max_grid_prods']; ?>" />
				<span class="usage"><?php _e('How many products to show before displaying a button to show more products.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label><?php _e('Left Navigation Filters','ocart'); ?></label>
				<div class="sortable-attributes">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					$taxonomies = sortArrayByArray($taxonomies, get_option('occommerce_grid_attr_sortables'));
					foreach ($taxonomies as $taxonomy ) {
						$the_tax = get_taxonomy( $taxonomy );
				?>
				<label class="checkbox_1"><input type="checkbox" name="grid_attr[]" value="<?php echo $taxonomy; ?>" <?php if (!empty($this->options['grid_attr']) && in_array($taxonomy, $this->options['grid_attr'])) { echo "checked=\"checked\""; } ?> /> <?php echo $the_tax->labels->name; ?></label>
				<?php
					}
				}
				?>
				</div>
				<span class="usage"><?php _e('Choose which options or attributes will build the left navigation filters in <b>Grid mode</b>.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label><?php _e('Scrollable Attributes','ocart'); ?></label>
				<div class="not-sortable-attributes">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					foreach ($taxonomies as $taxonomy ) {
						$the_tax = get_taxonomy( $taxonomy );
				?>
				<label class="checkbox_1"><input type="checkbox" name="scroll_attr[]" value="<?php echo $taxonomy; ?>" <?php if (!empty($this->options['scroll_attr']) && in_array($taxonomy, $this->options['scroll_attr'])) { echo "checked=\"checked\""; } ?> /> <?php echo $the_tax->labels->name; ?></label>
				<?php
					}
				}
				?>
				</div>
				<span class="usage"><?php _e('Choose which attributes you want to make scrollable (with auto height) in the grid view filters.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Product Settings','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label><?php _e('Product Page Attributes','ocart'); ?></label>
				<div class="sortable-attributes">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					$taxonomies = sortArrayByArray($taxonomies, get_option('occommerce_product_attr_sortables'));
					foreach ($taxonomies as $taxonomy ) {
						$the_tax = get_taxonomy( $taxonomy );
				?>
				<label class="checkbox_1"><input type="checkbox" name="product_attr[]" value="<?php echo $taxonomy; ?>" <?php if (!empty($this->options['product_attr']) && in_array($taxonomy, $this->options['product_attr'])) { echo "checked=\"checked\""; } ?> /> <?php echo $the_tax->labels->name; ?></label>
				<?php
					}
				}
				?>
				</div>
				<span class="usage"><?php _e('Choose which options or attributes you want to display in <b>Product</b> details page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="attr_select"><?php _e('Product Attributes Selection','ocart'); ?></label>
				<select name="attr_select" id="attr_select">
					<option value="0"<?php selected(0, $this->options['attr_select']); ?>><?php _e('Unordered List','ocart'); ?></option>
					<option value="1"<?php selected(1, $this->options['attr_select']); ?>><?php _e('Select Dropdown','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Select the method to display your product attributes. You can display them via <strong>select dropdown</strong> or <strong>unordered list</strong>.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="weightunit"><?php _e('Item Weight Unit','ocart'); ?></label>
				<select name="weightunit" id="weightunit">
					<option value="lbs"<?php selected('lbs', $this->options['weightunit']); ?>>lbs</option>
					<option value="g"<?php selected('g', $this->options['weightunit']); ?>>g</option>
					<option value="kg"<?php selected('kg', $this->options['weightunit']); ?>>kg</option>
				</select>
				<span class="usage"><?php _e('Choose a universal unit that will be used to calculate your items weight across the store.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Similar Products Module','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="related_tax"><?php _e('Taxonomy to Display Similar Products','ocart'); ?></label>
				<select name="related_tax" id="related_tax">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					foreach ($taxonomies as $taxonomy ) {
				?>
					<option value="<?php echo $taxonomy; ?>"<?php selected($taxonomy, $this->options['related_tax']); ?>><?php echo $taxonomy; ?></option>
				<?php
					}
				}
				?>
				</select>
				<span class="usage"><?php _e('If you did not choose <b>similar products</b> in Product page, the theme will automatically display related products based on the selected relationship.','ocart'); ?></span>
			</div>
		</div>
		
	</div>
	
	<div class="dashboard_body" id="tab2"><?php ocart_print_update_notice() ?>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="skin"><?php _e('Preset Skins','ocart'); ?></label>
				<select name="skin" id="skin">
					<?php
					if ($handle = opendir(get_template_directory().'/skins/')) {
						while (false !== ($entry = readdir($handle))) {
							if ($entry != "." && $entry != "..") {
							?>
							<option value="<?php echo $entry; ?>"<?php selected($entry, $this->options['skin']); ?>><?php echo ucfirst($entry); ?></option>
							<?php
							}
						}
						closedir($handle);
					}
					?>
				</select>
				<span class="usage"><?php _e('Change active theme skin. <b>Skins</b> are stored in <code>/skins/</code> folder. Each skin contains a <b>set of images</b> plus <b>CSS rules</b>.','ocart'); ?><span class="sub"><?php _e('You can customize the images found in <code>/skins/your_skin/</code> folder.','ocart'); ?></span>
			</div>
		</div><div class="clear"></div>
		
		<h1><?php _e('General','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_custom_css"><?php _e('Custom CSS','ocart'); ?></label>
				<textarea name="html_custom_css" id="html_custom_css"><?php if (isset($this->options['html_custom_css'])) echo $this->options['html_custom_css']; ?></textarea>
				<span class="usage2"><?php _e('Paste any <b>custom CSS</b> rules in this field so that your CSS tweaks and customizations will not get overriden when the theme is updated.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="theme_usebgcolor"><?php _e('Use Solid Body Background Color','ocart'); ?></label>
				<select name="theme_usebgcolor" id="theme_usebgcolor">
					<option value="0"<?php selected(0, $this->options['theme_usebgcolor']); ?>><?php _e('No','ocart'); ?></option>
					<option value="1"<?php selected(1, $this->options['theme_usebgcolor']); ?>><?php _e('Yes','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('If you set this to <b>YES</b>, the background color you set in your active skin below will be used as a background color.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="scroll_distance"><?php _e('Scroll Distance','ocart'); ?></label>
				<input type="text" name="scroll_distance" id="scroll_distance" value="<?php echo $this->options['scroll_distance']; ?>" />
				<span class="usage"><?php _e('Applies to <b>Grid</b> layout only. Define the <b>required distance</b> in pixels which will trigger loading more products to the catalog page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="lightbox_shadow"><?php _e('Lightbox Shadow','ocart'); ?></label>
				<select name="lightbox_shadow" id="lightbox_shadow">
					<option value="fff"<?php selected('fff', $this->options['lightbox_shadow']); ?>><?php _e('Light','ocart'); ?></option>
					<option value="000"<?php selected('000', $this->options['lightbox_shadow']); ?>><?php _e('Dark','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Header','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="theme_header_height"><?php _e('Header Height','ocart'); ?></label>
				<input type="text" name="theme_header_height" id="theme_header_height" value="<?php echo $this->options['theme_header_height']; ?>" />
				<span class="usage"><?php _e('Override the default theme header height. <code>Use pixels please</code>','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="theme_menu_opacity"><?php _e('Menu Style 2 Opacity','ocart'); ?></label>
				<input type="text" name="theme_menu_opacity" id="theme_menu_opacity" value="<?php echo $this->options['theme_menu_opacity']; ?>" />
				<span class="usage"><?php _e('Enter number from <strong>1 to 100</strong> for opacity. You may want to play with this setting to get best results.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Slideshow','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="theme_slide_opacity"><?php _e('Text Opacity','ocart'); ?></label>
				<input type="text" name="theme_slide_opacity" id="theme_slide_opacity" value="<?php echo $this->options['theme_slide_opacity']; ?>" />
				<span class="usage"><?php _e('Enter number from <strong>1 to 100</strong> for opacity. You may want to play with this setting to get best results.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="theme_slide_usebg"><?php _e('Use Background for Slide Text','ocart'); ?></label>
				<select name="theme_slide_usebg" id="theme_slide_usebg">
					<option value="1"<?php selected(1, $this->options['theme_slide_usebg']); ?>><?php _e('Yes','ocart'); ?></option>
					<option value="0"<?php selected(0, $this->options['theme_slide_usebg']); ?>><?php _e('No','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('You can select no If you want the text to appear without a background color. You can customize slide text background and color using the <strong>Color Customizer</strong> below.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="theme_slide_usebg_image"><?php _e('Use Background Image for Slide Text','ocart'); ?></label>
				<select name="theme_slide_usebg_image" id="theme_slide_usebg_image">
					<option value="1"<?php selected(1, $this->options['theme_slide_usebg_image']); ?>><?php _e('Yes','ocart'); ?></option>
					<option value="0"<?php selected(0, $this->options['theme_slide_usebg_image']); ?>><?php _e('No','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('If you apply this setting, the transparent image will be used as a background instead. <strong>Opacity</strong> will be automatically adjusted when this choice is enabled.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Product Page','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="main_image_width"><?php _e('Main Image Width','ocart'); ?></label>
				<input type="text" name="main_image_width" id="main_image_width" value="<?php if (isset($this->options['main_image_width'])) echo $this->options['main_image_width']; ?>" />
				<span class="usage"><?php _e('The width of main product image in Product page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="main_image_height"><?php _e('Main Image Height','ocart'); ?></label>
				<input type="text" name="main_image_height" id="main_image_height" value="<?php if (isset($this->options['main_image_height'])) echo $this->options['main_image_height']; ?>" />
				<span class="usage"><?php _e('The height of main product image in Product page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="main_image_left_px"><?php _e('Main Image Left Margin','ocart'); ?></label>
				<input type="text" name="main_image_left_px" id="main_image_left_px" value="<?php if (isset($this->options['main_image_left_px'])) echo $this->options['main_image_left_px']; ?>" />
				<span class="usage"><?php _e('The number of pixels to keep from left. This can help you center the product image.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="main_image_nav"><?php _e('Main Image Navigation Controls','ocart'); ?></label>
				<select name="main_image_nav" id="main_image_nav">
					<option value="1"<?php selected(1, $this->options['main_image_nav']); ?>><?php _e('Enable','ocart'); ?></option>
					<option value="0"<?php selected(0, $this->options['main_image_nav']); ?>><?php _e('Disable','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('These controls are next/previous buttons that navigate the main image area (Not mini thumbnails)','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="product_thumbs"><?php _e('Small Thumbnails Style','ocart'); ?></label>
				<select name="product_thumbs" id="product_thumbs">
					<option value="default"<?php selected('default', $this->options['product_thumbs']); ?>><?php _e('Default style - besides main mage','ocart'); ?></option>
					<option value="below"<?php selected('below', $this->options['product_thumbs']); ?>><?php _e('Below product image','ocart'); ?></option>
					<option value="0"<?php selected(0, $this->options['product_thumbs']); ?>><?php _e('Hide Thumbnails','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('If you apply this setting, the transparent image will be used as a background instead. <strong>Opacity</strong> will be automatically adjusted when this choice is enabled.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Products Slider','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="catalog_image_height"><?php _e('Catalog Image Height','ocart'); ?></label>
				<input type="text" name="catalog_image_height" id="catalog_image_height" value="<?php echo $this->options['catalog_image_height']; ?>" />
				<span class="usage"><?php _e('Enter height of your <strong>catalog image</strong> in pixels.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php printf(__('Customize \'%s\' Skin','ocart'), ucfirst($this->options['skin'])); ?></h1>
		
		<p><input type="submit" name="reset_skin" id="reset_skin" value="<?php _e('Reset All','ocart'); ?>" class="button button-primary" /></p>
		
		<div class="ocfield ocfield_clr">
		<?php
		$skin_arr = get_option('occommerce_skin_'.$this->options['skin']);
		$i = 0;
		foreach($skin_arr as $data => $value) {
		$i++;
		?>
			<div class="subfield">
				<label for="<?php echo $this->options['skin']; ?>_<?php echo $data; ?>"><?php ocart_skin_data_option($data); ?></label>
				<input type="submit" name="reset_<?php echo $data; ?>" id="reset_<?php echo $data; ?>" class="button-secondary" value="<?php _e('Reset','ocart'); ?>" />
				<div class="colorSelector color<?php echo $i; ?>"><div style="background-color: <?php if (isset($this->options[$this->options['skin'].'_'.$data])) { echo $this->options[$this->options['skin'].'_'.$data]; } else { echo $skin_arr["$data"]; } ?>"></div></div>
				<input type="hidden" name="<?php echo $this->options['skin']; ?>_<?php echo $data; ?>" id="<?php echo $this->options['skin']; ?>_<?php echo $data; ?>" value="<?php if (isset($this->options[$this->options['skin'].'_'.$data])) { echo $this->options[$this->options['skin'].'_'.$data]; } else { echo $skin_arr["$data"]; } ?>" />
			</div>
		<?php
		}
		?>
		</div>
		
	</div>
	
	<div class="dashboard_body" id="tab3"><?php ocart_print_update_notice() ?>
	
		<h1><?php _e('Customize Header','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_header_code"><?php _e('Tracking Code','ocart'); ?></label>
				<textarea name="html_header_code" id="html_header_code"><?php if (isset($this->options['html_header_code'])) echo $this->options['html_header_code']; ?></textarea>
				<span class="usage2"><?php _e('Paste your <b>Google Analytics</b> or other tracking/javascript code here. Any code you put here will go directly in the <b>header part</b> of your site.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="menu_style"><?php _e('Main Menu Style','ocart'); ?></label>
				<select name="menu_style" id="menu_style">
					<option value="0"<?php selected('0', $this->options['menu_style']); ?>><?php _e('Original Sliding Menu','ocart'); ?></option>
					<option value="1"<?php selected('1', $this->options['menu_style']); ?>><?php _e('Enhanced Multi-level Menu','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('The theme now includes <strong>2 navigation systems</strong> to choose from.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_bloglink"><?php _e('Display Blog Link','ocart'); ?></label>
				<select name="show_bloglink" id="show_bloglink">
					<option value="1"<?php selected('1', $this->options['show_bloglink']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_bloglink']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('You can hide <strong>Blog</strong> link (it will disappear from header menu).','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_login"><?php _e('Display Login/Registration Links','ocart'); ?></label>
				<select name="show_login" id="show_login">
					<option value="1"<?php selected('1', $this->options['show_login']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_login']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('You can show or hide login/registration links from header.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="default_nav_tax"><?php _e('Default Navigation Filter','ocart'); ?></label>
				<select name="default_nav_tax" id="default_nav_tax">
				<?php
				$args=array('public' => true,'_builtin' => false);
				$output = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				$taxonomies = get_taxonomies($args,$output,$operator);
				if  ($taxonomies) {
					foreach ($taxonomies as $taxonomy ) {
				?>
					<option value="<?php echo $taxonomy; ?>"<?php selected($taxonomy, $this->options['default_nav_tax']); ?>><?php echo $taxonomy; ?></option>
				<?php
					}
				}
				?>
				</select>
				<span class="usage"><?php _e('By default, the top navigation shows <strong>Brands</strong>. You can change this default setting here. For example: Select <strong>product_category</strong> to display categories instead.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Enable/Disable Site Features','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="enable_slideshow"><?php _e('Main Slideshow','ocart'); ?></label>
				<select name="enable_slideshow" id="enable_slideshow">
					<option value="1"<?php selected('1', $this->options['enable_slideshow']); ?>><?php _e('Enabled','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['enable_slideshow']); ?>><?php _e('Disabled','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('When disabled, your latest <strong>Slide</strong> will be displayed as a static image.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="hide_slider"><?php _e('Force Hide Slider','ocart'); ?></label>
				<select name="hide_slider" id="hide_slider">
					<option value="0"<?php selected(0, $this->options['hide_slider']); ?>><?php _e('Never','ocart'); ?></option>
					<option value="1"<?php selected(1, $this->options['hide_slider']); ?>><?php _e('Enable','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Choose <strong>Enable</strong> to hide slider from store home (requires Grid mode on. infinite scroll of products)','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="enable_calc"><?php _e('Estimated Shipping/Tax Calculation Form','ocart'); ?></label>
				<select name="enable_calc" id="enable_calc">
					<option value="1"<?php selected('1', $this->options['enable_calc']); ?>><?php _e('Enabled','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['enable_calc']); ?>><?php _e('Disabled','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('When disabled, the form used to calculate shipping/tax based on location will not be available on store cart.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Show/Hide Elements','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_nav_all"><?php _e('Main Left Menu','ocart'); ?></label>
				<select name="show_nav_all" id="show_nav_all">
					<option value="1"<?php selected('1', $this->options['show_nav_all']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_nav_all']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_nav"><?php _e('Main Dropdown Navigation','ocart'); ?></label>
				<select name="show_nav" id="show_nav">
					<option value="1"<?php selected('1', $this->options['show_nav']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_nav']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_product_breadcrumb"><?php _e('Product Breadcrumbs','ocart'); ?></label>
				<select name="show_product_breadcrumb" id="show_product_breadcrumb">
					<option value="1"<?php selected('1', $this->options['show_product_breadcrumb']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_product_breadcrumb']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_gridbtn"><?php _e('Switch to Grid Button','ocart'); ?></label>
				<select name="show_gridbtn" id="show_gridbtn">
					<option value="1"<?php selected('1', $this->options['show_gridbtn']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_gridbtn']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_sliderbtn"><?php _e('Switch to Slider Button','ocart'); ?></label>
				<select name="show_sliderbtn" id="show_sliderbtn">
					<option value="1"<?php selected('1', $this->options['show_sliderbtn']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_sliderbtn']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_pinit"><?php _e('Pin It Button','ocart'); ?></label>
				<select name="show_pinit" id="show_pinit">
					<option value="1"<?php selected('1', $this->options['show_pinit']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_pinit']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="show_backtotop"><?php _e('Back to Top Button','ocart'); ?></label>
				<select name="show_backtotop" id="show_backtotop">
					<option value="1"<?php selected('1', $this->options['show_backtotop']); ?>><?php _e('Show','ocart'); ?></option>
					<option value="0"<?php selected('0', $this->options['show_backtotop']); ?>><?php _e('Hide','ocart'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Customize Footer','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_footer"><?php _e('Footer Text','ocart'); ?></label>
				<textarea name="html_footer" id="html_footer"><?php if (isset($this->options['html_footer'])) echo $this->options['html_footer']; ?></textarea>
				<span class="usage2"><?php _e('Enter your website copyright (appears in footer) here.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<h1><?php _e('Contact Us Template','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_contact_googlemaps"><?php _e('Google Maps Code','ocart'); ?></label>
				<textarea name="html_contact_googlemaps" id="html_contact_googlemaps"><?php if (isset($this->options['html_contact_googlemaps'])) echo $this->options['html_contact_googlemaps']; ?></textarea>
				<span class="usage2"><?php _e('Enter your Google Maps embed code here. It will appear on your <b>Contact Us</b> page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_contact_addr"><?php _e('Company Address','ocart'); ?></label>
				<textarea name="html_contact_addr" id="html_contact_addr"><?php if (isset($this->options['html_contact_addr'])) echo $this->options['html_contact_addr']; ?></textarea>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="contact_phone"><?php _e('Phone Number','ocart'); ?></label>
				<input type="text" name="contact_phone" id="contact_phone" value="<?php echo $this->options['contact_phone']; ?>" />
				<span class="usage"><?php _e('Enter a phone number which presents you or your business. It will be displayed in your Contact page.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="contact_fax"><?php _e('Fax Number','ocart'); ?></label>
				<input type="text" name="contact_fax" id="contact_fax" value="<?php echo $this->options['contact_fax']; ?>" />
				<span class="usage"><?php _e('If you have a fax, enter your fax number here.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="contact_email"><?php _e('Contact Email','ocart'); ?></label>
				<input type="text" name="contact_email" id="contact_email" value="<?php echo $this->options['contact_email']; ?>" />
				<span class="usage"><?php _e('This e-mail address will be displayed under your business <b>Contact Info</b>.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="contact_web"><?php _e('Contact Website','ocart'); ?></label>
				<input type="text" name="contact_web" id="contact_web" value="<?php echo $this->options['contact_web']; ?>" />
				<span class="usage"><?php _e('Your business site domain name. e.g. <code>mywebsite.com</code>','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_contact_text"><?php _e('Contact Us Additional Text','ocart'); ?></label>
				<textarea name="html_contact_text" id="html_contact_text"><?php if (isset($this->options['html_contact_text'])) echo $this->options['html_contact_text']; ?></textarea>
				<span class="usage2"><?php _e('Use the above field to write extra text or HTML in your contact us page. It will be displayed below your contact info/address.','ocart'); ?></span>
			</div>
		</div>
	
	</div>
	
	<div class="dashboard_body" id="tab4"><?php ocart_print_update_notice() ?>

		<h1><?php _e('Currency Settings','ocart'); ?></h1>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="currency_pos"><?php _e('Currency Position','ocart'); ?></label>
				<select name="currency_pos" id="currency_pos">
					<option value="left"<?php selected('left', $this->options['currency_pos']); ?>><?php _e('Left','ocart'); ?></option>
					<option value="right"<?php selected('right', $this->options['currency_pos']); ?>><?php _e('Right','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Display the currency symbol on the left or right of price.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="cur_no_space"><?php _e('Currency symbol Space','ocart'); ?></label>
				<select name="cur_no_space" id="cur_no_space">
					<option value="0"<?php selected(0, $this->options['cur_no_space']); ?>><?php _e('No spaces','ocart'); ?></option>
					<option value="1"<?php selected(1, $this->options['cur_no_space']); ?>><?php _e('One space','ocart'); ?></option>
				</select>
				<span class="usage"><?php _e('Display the currency symbol on the left or right of price.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="currencycode"><?php _e('Primary Currency Code','ocart'); ?></label>
				<select name="currencycode" id="currencycode">
					<?php
					$currencies = get_option('occommerce_currencies'); asort($currencies);
					foreach($currencies as $currency) {
					?>
					<option value="<?php echo $currency; ?>"<?php selected($currency, $this->options['currencycode']); ?>><?php echo $currency; ?></option>
					<?php } ?>
				</select>
				<span class="usage"><?php _e('If you use a currency other than <b>USD</b> please select it from the list of supported currencies.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="multi_currency"><?php _e('Supported Currencies','ocart'); ?></label>
				<input type="text" name="multi_currency" id="multi_currency" value="<?php if (isset($this->options['multi_currency'])) echo $this->options['multi_currency']; ?>" />
				<span class="usage"><?php _e('Add here currency codes for the currencies you want to enable in the <strong>Multi-Currency</strong> storefront. <code>e.g. USD, GBP, EUR, AUD, JPY</code>','ocart'); ?></span>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<?php
		// process payment gateway fields setup
		$gateways = get_option('occommerce_OC_gateways');
		foreach($gateways as $gateway => $array) {
		?>
		
			<div class="clear"></div>
		
			<h1><?php echo $array['name']; ?></h1>
		
			<?php
			foreach($array['options'] as $option) {
				switch ($option) {
					case 'enabled':
					printf('<div class="ocfield"><div class="subfield">
							<label for="%1$s_enabled">%2$s</label>
								<select name="%1$s_enabled" id="%1$s_enabled">
									<option value="1" %5$s>%3$s</option>
									<option value="0" %6$s>%4$s</option>
								</select>
								<span class="usage">%7$s</span>
							</div>
						</div>',
						$gateway,
						sprintf(__('Enable/Disable %s','ocart'), $array['name']),
						__('Enabled','ocart'),
						__('Disabled','ocart'),
						selected($this->options[$gateway.'_enabled'], 1, false),
						selected($this->options[$gateway.'_enabled'], 0, false),
						sprintf(__('Enable or disable %s payments.','ocart'), $array['name'])
						);
						break;
					case 'requirelogin':
					printf('<div class="ocfield"><div class="subfield">
							<label for="%1$s_requirelogin">%2$s</label>
								<select name="%1$s_requirelogin" id="%1$s_enabled">
									<option value="1" %5$s>%3$s</option>
									<option value="0" %6$s>%4$s</option>
								</select>
								<span class="usage">%7$s</span>
							</div>
						</div>',
						$gateway,
						sprintf(__('Require user login','ocart'), $array['name']),
						__('Yes','ocart'),
						__('No','ocart'),
						selected($this->options[$gateway.'_requirelogin'], 1, false),
						selected($this->options[$gateway.'_requirelogin'], 0, false),
						sprintf(__('%s payment option should be available to logged in customers only or all users.','ocart'), $array['name'])
						);
						break;
					case 'receiver_email':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_receiver_email">%2$s</label>
								<input type="text" name="%1$s_receiver_email" id="%1$s_receiver_email" value="%3$s" />
								<span class="usage">%4$s</span>
							</div>
						</div>',
						$gateway,
						__('Merchant E-mail Address','ocart'),
						ocart_filter_empty_option($this->options[$gateway.'_receiver_email']),
						__('Enter e-mail address where you will receive and capture all payments made via this gateway.','ocart')
						);
						break;
					case 'email':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_email">%2$s</label>
								<input type="text" name="%1$s_email" id="%1$s_email" value="%3$s" />
								<span class="usage">%4$s</span>
							</div>
						</div>',
						$gateway,
						__('Merchant E-mail Address','ocart'),
						ocart_filter_empty_option($this->options[$gateway.'_email']),
						__('Enter e-mail address where you will receive and capture all payments made via this gateway.','ocart')
						);
						break;
					case 'api':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_api">%2$s</label>
								<input type="text" name="%1$s_api" id="%1$s_api" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Merchant API Login','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_api'])
						);
						break;
					case 'merchant':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_merchant">%2$s</label>
								<input type="text" name="%1$s_merchant" id="%1$s_merchant" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Merchant Account Number','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_merchant'])
						);
						break;
					case 'merchantid':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_merchantid">%2$s</label>
								<input type="text" name="%1$s_merchantid" id="%1$s_merchantid" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Merchant ID','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_merchantid'])
						);
						break;
					case 'partnerid':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_partnerid">%2$s</label>
								<input type="text" name="%1$s_partnerid" id="%1$s_partnerid" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Partner ID','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_partnerid'])
						);
						break;
					case 'profile_key':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_profile_key">%2$s</label>
								<input type="text" name="%1$s_profile_key" id="%1$s_profile_key" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Profile Key','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_profile_key'])
						);
						break;
					case 'userid':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_userid">%2$s</label>
								<input type="text" name="%1$s_userid" id="%1$s_userid" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s User ID','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_userid'])
						);
						break;
					case 'vendor':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_vendor">%2$s</label>
								<input type="text" name="%1$s_vendor" id="%1$s_vendor" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Vendor','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_vendor'])
						);
						break;
					case 'pwd':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_pwd">%2$s</label>
								<input type="text" name="%1$s_pwd" id="%1$s_pwd" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Password','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_pwd'])
						);
						break;
					case 'key':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_key">%2$s</label>
								<input type="text" name="%1$s_key" id="%1$s_key" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Transaction Key','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_key'])
						);
						break;
					case 'encryption':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_encryption">%2$s</label>
								<input type="text" name="%1$s_encryption" id="%1$s_encryption" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Encryption Key','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_encryption'])
						);
						break;
					case 'encryption_key':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_encryption_key">%2$s</label>
								<input type="text" name="%1$s_encryption_key" id="%1$s_encryption_key" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Encryption Key','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_encryption_key'])
						);
						break;
					case 'secret_word':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_secret_word">%2$s</label>
								<input type="text" name="%1$s_secret_word" id="%1$s_secret_word" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Secret Word','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_secret_word'])
						);
						break;
					case 'secret_key':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_secret_key">%2$s</label>
								<input type="text" name="%1$s_secret_key" id="%1$s_secret_key" value="%3$s" />
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Secret Key','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_secret_key'])
						);
						break;
					case 'charge':
					printf('<div class="ocfield"><div class="subfield">
								<label for="%1$s_charge">%2$s</label>
								<input type="text" name="%1$s_charge" id="%1$s_charge" value="%3$s" />
								<span class="usage">%4$s</span>
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Additional Charge','ocart'), $array['name']),
						ocart_filter_empty_option($this->options[$gateway.'_charge']),
						sprintf(__('If you want to charge extra fee when customers use %s as payment option, please put your extra charge here.','ocart'), $array['name'])
						);
						break;
					case 'testmode':
					printf('<div class="ocfield"><div class="subfield">
							<label for="%1$s_testmode">%2$s</label>
								<select name="%1$s_testmode" id="%1$s_testmode">
									<option value="0" %5$s>%3$s</option>
									<option value="1" %6$s>%4$s</option>
								</select>
								<span class="usage">%7$s</span>
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Test Mode','ocart'), $array['name']),
						__('Disabled','ocart'),
						__('Enabled','ocart'),
						selected($this->options[$gateway.'_testmode'], 0, false),
						selected($this->options[$gateway.'_testmode'], 1, false),
						sprintf(__('If you enable <strong>Test Mode</strong>, your %s transactions will not be live. Enable test mode for testing only.','ocart'), $array['name'])
						);
						break;
					case 'instructions':
					printf('<div class="ocfield"><div class="subfield">
								<label for="html_%1$s">%2$s</label>
								<textarea name="html_%1$s" id="html_%1$s">%3$s</textarea>
								<span class="usage2">%4$s</span>
							</div>
						</div>',
						$gateway,
						sprintf(__('%s Payment Instructions','ocart'), $array['name']),
						ocart_filter_empty_option($this->options['html_'.$gateway]),
						__('Please put your payment instructions in the above field. Please make it clear to customer to make the payment successfully using this offline payment gateway.','ocart')
						);
						break;
				}
			}
			?>
		
		<?php } ?>
		
	</div>
	
	<div class="dashboard_body" id="tab5"><?php ocart_print_update_notice() ?>
	
		<div class="clear"></div>
		
		<?php /* add new zone */ ?>
			<h1><?php _e('Add a New Zone','ocart'); ?></h1>
			
			<div class="ocfield">
			
				<div class="subfield">
					<label for="name_new_zone"><?php _e('Zone Name','ocart'); ?></label>
					<input type="text" name="name_new_zone" id="name_new_zone" value="" />
					<span class="usage"><?php _e('This name is used for zone reference only, It cannot be edited later. The zone name is used to make it easier for you to reference and edit zones only.','ocart'); ?></span>
				</div>
		
				<div class="subfield">
					<label><?php _e('Add countries to this zone','ocart'); ?></label>
					<select class="country_select">
						<option value="" selected="selected"><?php _e('Choose a country','ocart'); ?></option>
						<?php
						$countries = get_option('occommerce_allowed_countries');
						foreach($countries as $countrycode => $country) {
						?>
							<option><?php echo $country; ?></option>
						<?php } ?>
					</select>
					<span class="usage"><?php _e('<strong>Choose a country</strong> from the dropdown list to add it to this zone dynamically.','ocart'); ?></span>
				</div>
				<div class="subfield">
					<label for="regions_new_zone"><?php _e('Regions','ocart'); ?></label>
					<textarea class="limited" name="regions_new_zone" id="regions_new_zone"></textarea>
					<span class="usage"><?php _e('Enter a comma seperated list of <strong>countries, states, cities, zip codes, and so on</strong> The list represents regions specified to this zone. <code>Example: United States, 10012, NY, New York, Alaska</code>','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="fixed_tax_new_zone"><?php _e('Charge Fixed Tax Rate','ocart'); ?></label>
					<input type="text" name="fixed_tax_new_zone" id="fixed_tax_new_zone" value="" />
					<span class="usage"><?php _e('If you want to charge <strong>fixed tax</strong> fee for this zone, enter it here.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="pct_tax_new_zone"><?php _e('Charge Percentage Tax Rate','ocart'); ?></label>
					<input type="text" name="pct_tax_new_zone" id="pct_tax_new_zone" value="" />
					<span class="usage"><?php _e('If you want to charge <strong>percentage tax</strong> rate for this zone, enter it here. Without the percentage sign please.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="fixed_shipping_new_zone"><?php _e('Charge Fixed Shipping Rate','ocart'); ?></label>
					<input type="text" name="fixed_shipping_new_zone" id="fixed_shipping_new_zone" value="" />
					<span class="usage"><?php _e('If you want to charge <strong>fixed shipping</strong> fee for this zone, enter it here.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="pct_shipping_new_zone"><?php _e('Charge Percentage Shipping Rate','ocart'); ?></label>
					<input type="text" name="pct_shipping_new_zone" id="pct_shipping_new_zone" value="" />
					<span class="usage"><?php _e('If you want to charge <strong>percentage shipping</strong> rate for this zone, enter it here. Without the percentage sign please.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="weight_new_zone"><?php _e('Weight-based Shipping Rules','ocart'); ?></label>
					<textarea class="limited" name="weight_new_zone" id="weight_new_zone"></textarea>
					<span class="usage"><?php _e('Define fees that can be added based on <strong>Weight</strong> of items in cart. You <strong>should enter one rule per line</strong> using this example as a sample rule: <code>0|500|1</code> Each rule must be in a new line. The example means that if weight of cart is within 0 to 500 (weight unit) the charge will be 1 in your set currency.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="handling_new_zone"><?php _e('Add Handling Fees','ocart'); ?></label>
					<textarea class="limited" name="handling_new_zone" id="handling_new_zone"></textarea>
					<span class="usage"><?php _e('Define handling fees here if applicable. For example, add a cost to handle the first-item, second item, or any number of quantity you specify. You <strong>should enter one rule per line</strong> An example rule looks like this: <code>1|2.50</code> Each rule must be in a new line. That example adds a cost of 2.50 per each unit in cart. You can similarly add new rules for other quantities.','ocart'); ?></span>
				</div>
				
				<input type="submit" name="new_zone" id="new_zone" value="<?php _e('Add New Zone','ocart'); ?>" class="button button-primary" />
				
			</div>			
			<?php /* end add new zone */ ?>
			
		<div class="clear"></div>
		
		<h1><?php _e('Edit Zones','ocart'); ?></h1>
		
		<p><?php _e('Zones are used to simplify shipping and sales tax rates and give you maximum flexibility to set different rates based on customer location. By default, the themes comes with a default zone called <strong>Everywhere Else</strong>.','ocart'); ?></p>
		
		<?php
		$zones = get_option('occommerce_zones');
		$i = -1;
		foreach($zones as $zone) {
			$i++;
			if (isset($zone['status']) && $zone['status'] == 'disable') continue;
		?>
		
			<div class="clear"></div>
		
			<h3><?php echo $zone['name']; ?><?php if ($i > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="remove_zone_<?php echo $i; ?>" value="<?php _e('Remove zone','ocart'); ?>" class="button button-secondary" /><?php } ?></h3>
			
			<div class="ocfield">
				
				<?php if ($i > 0) { ?>
				<div class="subfield">
					<label><?php _e('Add countries to this zone','ocart'); ?></label>
					<select class="country_select">
						<option value="" selected="selected"><?php _e('Choose a country','ocart'); ?></option>
						<?php
						$countries = get_option('occommerce_allowed_countries');
						foreach($countries as $countrycode => $country) {
						?>
							<option><?php echo $country; ?></option>
						<?php } ?>
					</select>
					<span class="usage"><?php _e('<strong>Choose a country</strong> from the dropdown list to add it to this zone dynamically.','ocart'); ?></span>
				</div>
				<div class="subfield">
					<label for="regions_<?php echo $i; ?>"><?php _e('Regions','ocart'); ?></label>
					<textarea class="limited" name="regions_<?php echo $i; ?>" id="regions_<?php echo $i; ?>"><?php if ($zones[$i]['filters']['regions']) echo implode(',', $zones[$i]['filters']['regions']); ?></textarea>
					<span class="usage"><?php _e('Enter a comma seperated list of <strong>countries, states, cities, zip codes, and so on</strong> The list represents regions specified to this zone. <code>Example: United States, 10012, NY, New York, Alaska</code>','ocart'); ?></span>
				</div>
				<?php } ?>
				
				<div class="subfield">
					<label for="fixed_tax_<?php echo $i; ?>"><?php _e('Charge Fixed Tax Rate','ocart'); ?></label>
					<input type="text" name="fixed_tax_<?php echo $i; ?>" id="fixed_tax_<?php echo $i; ?>" value="<?php echo $zones[$i]['pricing']['fixed_tax']; ?>" />
					<span class="usage"><?php _e('If you want to charge <strong>fixed tax</strong> fee for this zone, enter it here.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="pct_tax_<?php echo $i; ?>"><?php _e('Charge Percentage Tax Rate','ocart'); ?></label>
					<input type="text" name="pct_tax_<?php echo $i; ?>" id="pct_tax_<?php echo $i; ?>" value="<?php echo $zones[$i]['pricing']['pct_tax']; ?>" />
					<span class="usage"><?php _e('If you want to charge <strong>percentage tax</strong> rate for this zone, enter it here. Without the percentage sign please.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="fixed_shipping_<?php echo $i; ?>"><?php _e('Charge Fixed Shipping Rate','ocart'); ?></label>
					<input type="text" name="fixed_shipping_<?php echo $i; ?>" id="fixed_shipping_<?php echo $i; ?>" value="<?php echo $zones[$i]['pricing']['fixed_shipping']; ?>" />
					<span class="usage"><?php _e('If you want to charge <strong>fixed shipping</strong> fee for this zone, enter it here.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="pct_shipping_<?php echo $i; ?>"><?php _e('Charge Percentage Shipping Rate','ocart'); ?></label>
					<input type="text" name="pct_shipping_<?php echo $i; ?>" id="pct_shipping_<?php echo $i; ?>" value="<?php echo $zones[$i]['pricing']['pct_shipping']; ?>" />
					<span class="usage"><?php _e('If you want to charge <strong>percentage shipping</strong> rate for this zone, enter it here. Without the percentage sign please.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="weight_<?php echo $i; ?>"><?php _e('Weight-based Shipping Rules','ocart'); ?></label>
					<textarea class="limited" name="weight_<?php echo $i; ?>" id="weight_<?php echo $i; ?>"><?php echo ocart_convert_weight_array($zones[$i]['pricing']['weight']); ?></textarea>
					<span class="usage"><?php _e('Define fees that can be added based on <strong>Weight</strong> of items in cart. You <strong>should enter one rule per line</strong> using this example as a sample rule: <code>0|500|1</code> Each rule must be in a new line. The example means that if weight of cart is within 0 to 500 (weight unit) the charge will be 1 in your set currency.','ocart'); ?></span>
				</div>
				
				<div class="subfield">
					<label for="handling_<?php echo $i; ?>"><?php _e('Add Handling Fees','ocart'); ?></label>
					<textarea class="limited" name="handling_<?php echo $i; ?>" id="handling_<?php echo $i; ?>"><?php echo ocart_convert_handling($zones[$i]['pricing']['handling']); ?></textarea>
					<span class="usage"><?php _e('Define handling fees here if applicable. For example, add a cost to handle the first-item, second item, or any number of quantity you specify. You <strong>should enter one rule per line</strong> An example rule looks like this: <code>1|2.50</code> Each rule must be in a new line. That example adds a cost of 2.50 per each unit in cart. You can similarly add new rules for other quantities.','ocart'); ?></span>
				</div>
				
			</div>
	
		<?php
		} ?>
		<?php /* end zones here */ ?>
		
		<div class="clear"></div>
	
		<h1><?php _e('Shipping Labels','ocart'); ?></h1>
	
		<?php for ($i = 1; $i <= 5; $i++) { // loop courier options ?>
		<div class="ocfield">
			<div class="subfield">
				<label for="courier<?php echo $i; ?>_label"><?php printf(__('Shipping Option %s Label','ocart'), $i); ?></label>
				<input type="text" name="courier<?php echo $i; ?>_label" id="courier<?php echo $i; ?>_label" value="<?php if (isset($this->options['courier'.$i.'_label'])) echo $this->options['courier'.$i.'_label']; ?>" />
				<?php if ($i == 1) { ?><span class="usage"><?php _e('You can manage your supported <b>Shipping Options</b> here. Setup shipping option label in this field <code>e.g. FedEx Overnight</code>','ocart'); ?></span><?php } ?>
			</div>
			<div class="subfield">
				<label for="courier<?php echo $i; ?>_fee"><?php printf(__('Shipping Option %s Fee','ocart'), $i); ?></label>
				<input type="text" name="courier<?php echo $i; ?>_fee" id="courier<?php echo $i; ?>_fee" value="<?php if (isset($this->options['courier'.$i.'_fee'])) echo $this->options['courier'.$i.'_fee']; ?>" />
				<?php if ($i == 1) { ?><span class="usage"><?php _e('Enter the fee you will charge for using this shipping option. Please do not include any currency symbol. <b>Leave blank for Free Shipping</b>.','ocart'); ?></span><?php } ?>
			</div>
			<div class="subfield">
				<label for="courier<?php echo $i; ?>_est"><?php printf(__('Shipping Option %s Est. Delivery (in days)','ocart'), $i); ?></label>
				<input type="text" name="courier<?php echo $i; ?>_est" id="courier<?php echo $i; ?>_est" value="<?php if (isset($this->options['courier'.$i.'_est'])) echo $this->options['courier'.$i.'_est']; ?>" />
				<?php if ($i == 1) { ?><span class="usage"><?php _e('Enter estimated delivery days range here. For example, enter <code>1-3</code> if shipping by this method generally takes between 1 to 3 business days.','ocart'); ?></span><?php } ?>
			</div>
		</div>
		<?php } ?>
	
	</div>
	
	<div class="dashboard_body" id="tab6"><?php ocart_print_update_notice() ?>

		<h1><?php _e('E-mail Settings','ocart'); ?></h1>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="mail_name"><?php _e('Mail From: (Name)','ocart'); ?></label>
				<input type="text" name="mail_name" id="mail_name" value="<?php if (isset($this->options['mail_name'])) echo $this->options['mail_name']; ?>" />
				<span class="usage"><?php _e('The name you enter here will appear to customers in the mail header when e-mails are sent to them.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="mail_address"><?php _e('Mail From: (E-mail Address)','ocart'); ?></label>
				<input type="text" name="mail_address" id="mail_address" value="<?php if (isset($this->options['mail_address'])) echo $this->options['mail_address']; ?>" />
				<span class="usage"><?php _e('The e-mail address you enter here will appear to customers in the mail header when e-mails are sent to them.','ocart'); ?></span>
			</div>
		</div>
		
		<?php
		/** allow hooking to add more customized e-mail templates **/
		do_action('ocart_pre_email_templates_admin', $this->options);
		?>
	
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_received"><?php _e('Order Received','ocart'); ?></label>
				<textarea name="html_order_received" id="html_order_received"><?php echo $this->options['html_order_received']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when he places a new order. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?><span class="sub"><?php ocart_email_template_tags(); ?></span></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_awaiting_payment"><?php _e('Order is Awaiting Payment','ocart'); ?></label>
				<textarea name="html_order_awaiting_payment" id="html_order_awaiting_payment"><?php echo $this->options['html_order_awaiting_payment']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when admin changes the status of order to <b>awaiting payment</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_pending"><?php _e('Order is Pending','ocart'); ?></label>
				<textarea name="html_order_pending" id="html_order_pending"><?php echo $this->options['html_order_pending']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when admin changes the status of order to <b>pending</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_processing"><?php _e('Order is being Processed','ocart'); ?></label>
				<textarea name="html_order_processing" id="html_order_processing"><?php echo $this->options['html_order_processing']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when admin changes the status of order to <b>processing</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_shipped"><?php _e('Order has been Shipped','ocart'); ?></label>
				<textarea name="html_order_shipped" id="html_order_shipped"><?php echo $this->options['html_order_shipped']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when admin changes the status of order to <b>shipped</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_cancelled"><?php _e('Order has been Cancelled','ocart'); ?></label>
				<textarea name="html_order_cancelled" id="html_order_cancelled"><?php echo $this->options['html_order_cancelled']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when an order has been <b>cancelled</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_declined"><?php _e('Order has been Declined','ocart'); ?></label>
				<textarea name="html_order_declined" id="html_order_declined"><?php echo $this->options['html_order_declined']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when an order has been <b>declined</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_tracking"><?php _e('Order Tracking','ocart'); ?></label>
				<textarea name="html_order_tracking" id="html_order_tracking"><?php echo $this->options['html_order_tracking']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when admin submits <b>tracking information</b> for an order. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_order_comments"><?php _e('Order Comments','ocart'); ?></label>
				<textarea name="html_order_comments" id="html_order_comments"><?php echo $this->options['html_order_comments']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be sent to customer when admin submits <b>order comments</b>. You can use the following <b>template tags</b> where applicable in any of your email templates to display shortcuts, order and customer information.','ocart'); ?></span>
			</div>
		</div>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="html_admin_order_received"><?php _e('Admin Order Received','ocart'); ?></label>
				<textarea name="html_admin_order_received" id="html_admin_order_received"><?php echo $this->options['html_admin_order_received']; ?></textarea>
				<span class="usage2"><?php _e('This e-mail template will be <b>sent to the administrator</b> when someone places a new order.','ocart'); ?></span>
			</div>
		</div>
	
	</div>
	
	<div class="dashboard_body" id="tab7"><?php ocart_print_update_notice() ?>
		
		<h1><?php _e('Setup your Social Profiles','ocart'); ?></h1>
		<p><?php _e('Your social profiles are links to different social bookmarking sites like <b>Facebook or Twitter</b>. If your store has any of these social profiles, please put it in. They will be used to link to your social profiles when you create a <b>Social Widget</b>. <b>Please note:</b> Enter the complete URL to your social profile, and not just your username.','ocart'); ?></p>
	
		<?php
		$bookmarks = get_option('occommerce_social_bookmarks');
		foreach($bookmarks as $bookmark) {
		?>
		
		<div class="ocfield">
			<div class="subfield">
				<label for="<?php echo $bookmark; ?>"><?php echo ucfirst($bookmark); ?></label>
				<input type="text" name="<?php echo $bookmark; ?>" id="<?php echo $bookmark; ?>" value="<?php echo $this->options["$bookmark"]; ?>" />
				<span class="usage"><?php printf(__('Enter your <b>%s</b> page or URL here or leave blank to disable this social profile.','ocart'), ucfirst($bookmark)); ?></span>
			</div><div class="clear"></div>
		</div>
		
		<?php
		}
		?>
		
	</div>
	
<div class="clear"></div>
</div>
	
</form><!--end main form-->