<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION)) {
    session_start();
}

add_action('after_setup_theme', 'onecart_setup');
function onecart_setup() {
    load_theme_textdomain('ocart', get_template_directory() . '/lang/');

    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');

    // Custom image sizes
    add_image_size('catalog-thumb', 180, 240, true);
    add_image_size('similar-thumb', 150, 150, true);
    add_image_size('collection-thumb', 120, 160, true);
    add_image_size('size-50', 50, 50, true);
}

if (!isset($_SESSION['zonedata'])) {
    $zones = get_option('occommerce_zones');

    $_SESSION['zonedata']['location'] = 'everywhere';
    $_SESSION['zonedata']['fixed_tax'] = $zones[0]['pricing']['fixed_tax'];
    $_SESSION['zonedata']['pct_tax'] = $zones[0]['pricing']['pct_tax'];
    $_SESSION['zonedata']['fixed_shipping'] = $zones[0]['pricing']['fixed_shipping'];
    $_SESSION['zonedata']['pct_shipping'] = $zones[0]['pricing']['pct_shipping'];
    $_SESSION['zonedata']['weight'] = $zones[0]['pricing']['weight'];
    $_SESSION['zonedata']['handling'] = $zones[0]['pricing']['handling'];
}

/************************************************************
better way to get theme options
************************************************************/
function ocart_get_option($option) {
    global $ocart;

    // check for cookie first
    if (isset($_COOKIE["$option"]) == 'yes') {
        if (in_array($option, array('show_nav', 'show_nav_all', 'enable_slideshow', 'menu_style'))) {
            return 0;
        } elseif ($option == 'catalog_image_height') {
            return 250;
        } else {
            return 1;
        }
    } else {
        // default behavior!
        if (isset($ocart["$option"]) && $ocart["$option"] != '') {
            return $ocart["$option"];
        } else {
            // fallback
            if ($option == 'related_tax') return 'product_category';
            if ($option == 'default_nav_tax') return 'brand';
            if ($option == 'grid_prod_num') return 9;
            if ($option == 'max_grid_prods') return 18;
            if ($option == 'currency_pos') return 'left';
            if ($option == 'theme_header_height') return 62;
            if ($option == 'scroll_distance') return 400;
            if ($option == 'lightbox_shadow') return 'fff';
            if ($option == 'theme_slide_opacity') return 100;
            if ($option == 'theme_menu_opacity') return 100;
            if ($option == 'catalog_image_height') return 176;
            if ($option == 'mail_name') return get_bloginfo('name');
            if ($option == 'mail_address') return get_option('admin_email');
            if ($option == 'multi_currency') return 'USD, EUR, GBP, AUD, JPY';
            if ($option == 'main_image_width') return 230;
            if ($option == 'main_image_height') return 300;
            if ($option == 'main_image_left_px') return 30;
            if ($option == 'product_thumbs') return 'default';
            if ($option == 'seo_seperator_position') return 1;
            if ($option == 'seo_seperator') return '';
            if ($option == 'seo_hometitle') return get_bloginfo('name');
            if ($option == 'seo_homedesc') return get_bloginfo('description');
            if ($option == 'seo_seperator_position_404') return 1;
            if ($option == 'seo_seperator_404') return '';
            if ($option == 'seo_hometitle_404') return __('404','ocart');
            if ($option == 'seo_homedesc_404') return get_bloginfo('description');
            if ($option == 'seo_seperator_position_search') return 1;
            if ($option == 'seo_seperator_search') return '';
            if ($option == 'seo_hometitle_search') return __('Search Results','ocart');
            if ($option == 'seo_homedesc_search') return get_bloginfo('description');
            if ($option == 'default_expire_for_new') return 15;
            if ($option == 'grid_default_tagline_attribute') return 'size';
            if (in_array($option, array( 'show_gridbtn', 'show_sliderbtn', 'show_backtotop', 'show_nav', 'show_nav_all', 'checkout_extras', 'enable_slideshow', 'enable_calc', 'theme_slide_usebg_image', 'enable_tax', 'show_bloglink', 'show_login', 'show_product_breadcrumb', 'sort_products', 'ocml', 'ocmc', 'main_image_nav', 'wishlist' ))) return 1;
            if (in_array($option, array( 'theme_slide_usebg', 'tax_rate', 'disable_cart', 'disable_prices', 'page_terms', 'tax_included', 'menu_style', 'attr_select', 'force_lightbox', 'cur_no_space', 'single_product_in_popup', 'hide_slider' ) )) return 0;
        }
    }
}

/************************************************************
include libraries
************************************************************/
include_once get_template_directory().'/lib/firstrun.php';
include_once get_template_directory().'/lib/extend_currencies.php';
include_once get_template_directory().'/lib/extend_paylogos.php';
include_once get_template_directory().'/lib/actions.php';
include_once get_template_directory().'/lib/filters.php';
include_once get_template_directory().'/lib/seo_metabox.php';
include_once get_template_directory().'/lib/orders_metabox.php';
include_once get_template_directory().'/lib/product_metabox.php';
include_once get_template_directory().'/lib/slide_metabox.php';
include_once get_template_directory().'/lib/coupon_metabox.php';
include_once get_template_directory().'/lib/register_taxonomy.php';
include_once get_template_directory().'/lib/register_sidebar.php';
include_once get_template_directory().'/lib/register_shortcodes.php';
include_once get_template_directory().'/lib/register_fields.php';
include_once get_template_directory().'/lib/orders.php';
include_once get_template_directory().'/lib/products.php';
include_once get_template_directory().'/lib/coupons.php';
include_once get_template_directory().'/lib/slides.php';
include_once get_template_directory().'/lib/admin-functions.php';
include_once get_template_directory().'/lib/email_templates.php';
include_once get_template_directory().'/lib/admin_menu.php';

/************************************************************
display seo title/meta
************************************************************/
function ocart_seo() {
    $content = '';

    // Homepage
    if (is_home()) {
        $sep = ocart_get_option('seo_seperator');
		$sep_position = ocart_get_option('seo_seperator_position');
		$title = ocart_get_option('seo_hometitle');
		$desc = ocart_get_option('seo_homedesc');
		$content .= '<title>';
		if ($sep && $sep_position == 2) { $content .= $sep; }
		$content .= $title;
		if ($sep && $sep_position == 1) { $content .= $sep; }
		if (!$sep && $title == get_bloginfo('name')) { $content .= ' | '.$desc; }
		$content .= '</title>';
		$content .= '<meta name="description" content="'.$desc.'" />';
	}

	// search
	if (is_search()) {
		$sep = ocart_get_option('seo_seperator_search');
		$sep_position = ocart_get_option('seo_seperator_position_search');
		$title = ocart_get_option('seo_hometitle_search');
		$desc = ocart_get_option('seo_homedesc_search');
		$content .= '<title>';
		if ($sep && $sep_position == 2) { $content .= $sep; }
		$content .= $title;
		if ($sep && $sep_position == 1) { $content .= $sep; }
		if (!$sep && $title == __('Search Results','ocart') ) { $content .= ' | '.get_bloginfo('name'); }
		$content .= '</title>';
		$content .= '<meta name="description" content="'.$desc.'" />';
	}

	// categories, taxonomies
	if (is_tax() || is_tag() || is_category()) {
		global $taxonomy,$term;
		if (is_tag()) { $term = get_query_var('tag'); $taxonomy = 'post_tag'; }
		if (is_category()) { $cat = get_category(get_query_var('cat'),false); $term = $cat->slug; $taxonomy = 'category'; }
		$term = get_term_by('slug', $term, $taxonomy);
		$term_id = $term->term_id;
		$term_meta = get_option( "taxonomy_$term_id" );
		if (isset($term_meta['seocustomsep']) && !empty($term_meta['seocustomsep']) && isset($term_meta['seosep']) && $term_meta['seosep'] == 'custom') {
			$sep = $term_meta['seocustomsep'];
		} elseif (isset($term_meta['seosep']) && $term_meta['seosep'] == 'none') {
			$sep = '';
		} elseif (isset($term_meta['seosep']) && $term_meta['seosep'] == 'default') {
			$sep = ' | '. get_bloginfo('name');
		} else { // no seo seperator
			$sep = ' | '. get_bloginfo('name');
		}
		$sep_position = ocart_get_option('seo_seperator_position');
		if (isset($term_meta['seotitle']) && !empty($term_meta['seotitle'])) {
			$title = $term_meta['seotitle'];
		} else {
			$title = $term->name;
		}
		if (isset($term_meta['seodescription']) && !empty($term_meta['seodescription'])) {
			$desc = $term_meta['seodescription'];
		} else {
			$desc = ocart_get_option('seo_homedesc');
		}
		$content .= '<title>';
		if ($sep && $sep_position == 2) { $content .= $sep; }
		$content .= $title;
		if ($sep && $sep_position == 1) { $content .= $sep; }
		if (!$sep && $title == get_bloginfo('name')) { $content .= ' | '.$desc; }
		$content .= '</title>';
		$content .= '<meta name="description" content="'.$desc.'" />';
	}

	// 404 not found
	if (is_404()) {
		$sep = ocart_get_option('seo_seperator_404');
		$sep_position = ocart_get_option('seo_seperator_position_404');
		$title = ocart_get_option('seo_hometitle_404');
		$desc = ocart_get_option('seo_homedesc_404');
		$content .= '<title>';
		if ($sep && $sep_position == 2) { $content .= $sep; }
		$content .= $title;
		if ($sep && $sep_position == 1) { $content .= $sep; }
		if (!$sep && $title == __('404','ocart') ) { $content .= ' | '.get_bloginfo('name'); }
		$content .= '</title>';
		$content .= '<meta name="description" content="'.$desc.'" />';
	}

	// post/page
	if (is_single() || is_page()) {
		global $post;
		global $taxonomy,$term;
		$seo_title = get_post_meta($post->ID, 'seo_title', true);
		$seo_description = get_post_meta($post->ID, 'seo_description', true);
		$seo_sep = get_post_meta($post->ID, 'seo_sep', true);
		$seo_custom_sep = get_post_meta($post->ID, 'seo_custom_sep', true);
		if (isset($seo_custom_sep) && !empty($seo_custom_sep) && isset($seo_sep) && $seo_sep == 'custom') {
			$sep = $seo_custom_sep;
		} elseif (isset($seo_sep) && $seo_sep == 'none') {
			$sep = '';
		} elseif (isset($seo_sep) && $seo_sep == 'default') {
			$sep = ' | '. get_bloginfo('name');
		} else { // no seo seperator
			$sep = ' | '. get_bloginfo('name');
		}
		$sep_position = ocart_get_option('seo_seperator_position');
		if (isset($seo_title) && !empty($seo_title)) {
			$title = $seo_title;
		} else {
			$title = $post->post_title;
		}
		if (isset($seo_description) && !empty($seo_description)) {
			$desc = $seo_description;
		} else {
			$text = strip_tags($post->post_content);
			$words = preg_split("/[\n\r\t ]+/", $text, 56, PREG_SPLIT_NO_EMPTY);
			if ( count($words) > 55 ) {
				array_pop($words);
				$text = implode(' ', $words);
				$desc = $text;
			} else {
				$text = implode(' ', $words);
				$desc = $text;
			}
			if (!($text)) {
				$desc = get_bloginfo('description');
			}
		}
		$content .= '<title>';
		if ($sep && $sep_position == 2) { $content .= $sep; }
		$content .= $title;
		if ($sep && $sep_position == 1) { $content .= $sep; }
		if (!$sep && $title == $post->post_title && $seo_sep != 'none') { $content .= ' | '.get_bloginfo('name'); }
		$content .= '</title>';
		$content .= '<meta name="description" content="'.$desc.'" />';
	}

	echo $content;

}

/************************************************************
hide slider completely
************************************************************/
function ocart_hide_slider() {
	if (ocart_catalog_version() == 2) {
		if (ocart_get_option('hide_slider')) {
			return true;
		}
	}
	return false;
}

/************************************************************
set active currency code
************************************************************/
if ( isset( $_GET['currency'] ) ) {
	$_SESSION['currency'] = $_GET['currency'];
	$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s='.$ocart['currencycode'].$_SESSION['currency'].'=X';
	$handle = fopen($url, 'r');
	if ($handle) {
		$result = fgetcsv($handle);
		fclose($handle);
	}
	$_SESSION['exchange_rate'] = $result[0];

	// reversed exchange rate
	$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s='.$_SESSION['currency'].$ocart['currencycode'].'=X';
	$handle = fopen($url, 'r');
	if ($handle) {
		$result = fgetcsv($handle);
		fclose($handle);
	}
	$_SESSION['exchange_rate_reverse'] = $result[0];

} else {
	if(isset($_SESSION['currency'])) {
		$_GET['currency'] = $_SESSION['currency'];
	}
}

/************************************************************
display languages
************************************************************/
function ocart_show_languages() {

	global $ocart;

	// fetch languages
	$langs = get_option('occommerce_language_plugins');

	// active language
	if (is_array($langs) && in_array(WPLANG, $langs)){
		$active_lang = WPLANG;
	} else {
		$active_lang = 'en_US'; // fallback
	}

	// add english by default
	if (!is_array($langs)) $langs = array('en_US');
	if (is_array($langs) && !in_array('en_US', $langs)) {
		$langs[] = 'en_US';
	}

?>

<div class="switchbar-inner">
	<ul>
		<li><a href="?lang=<?php echo $active_lang; ?>" class="bar-toggle"><img src="<?php echo ocart_show_flag($active_lang); ?>" /><span><?php ocart_language_label($active_lang); ?></span></a>
			<?php if (count($langs) > 1) { ?>
			<ul>
				<?php foreach($langs as $lang) { if ($lang == $active_lang) continue; ?>
					<li><a href="?lang=<?php echo $lang; ?>"><img src="<?php echo ocart_show_flag($lang); ?>" /><span><?php ocart_language_label($lang); ?></span></a></li>
				<?php } ?>
			</ul>
			<?php } ?>
		</li>
	</ul>
</div>

<?php
}

/************************************************************
show language flag
************************************************************/
function ocart_show_flag($code) {
	$constant = defined($code.'_image') ? constant($code.'_image') : get_template_directory_uri().'/img/default-flag.png';
	echo $constant;
}

/************************************************************
show language label
************************************************************/
function ocart_language_label($code) {
	$constant = defined($code) ? constant($code) : 'English';
	echo $constant;
}

/************************************************************
show currency label
************************************************************/
function ocart_currency_label($code) {
	switch ($code) {
		case 'USD':
			return sprintf(__('US Dollar (%s)','ocart'), $code);
			break;
		case 'EUR':
			return sprintf(__('Euro (%s)','ocart'), $code);
			break;
		case 'GBP':
			return sprintf(__('Sterling Pound (%s)','ocart'), $code);
			break;
		break;
		case 'AUD':
			return sprintf(__('Australian Dollar (%s)','ocart'), $code);
			break;
		case 'JPY':
			return sprintf(__('Japanese Yen (%s)','ocart'), $code);
			break;
		case 'RUB':
			return sprintf(__('Russian Ruble (%s)','ocart'), $code);
			break;
		case 'INR':
			return sprintf(__('Indian Rupee (%s)','ocart'), $code);
			break;
		case 'NOK':
			return sprintf(__('Norwegian Krone (%s)','ocart'), $code);
			break;
		case 'ZAR':
			return sprintf(__('South African rand (%s)','ocart'), $code);
			break;
		default:
			return $code;
			break;
	}
}

/************************************************************
display multi currencies
************************************************************/
function ocart_show_currencies() {

	global $ocart;

	// available currencies
	$supported_c = ocart_get_option('multi_currency');
	$supported_c = str_replace(' ','', $supported_c);
	$currencies = explode(',', $supported_c);

	// add default currency
	if (!in_array($ocart['currencycode'], $currencies)) {
		array_push($currencies, $ocart['currencycode']);
	}

	// make sure the currencies are added to available currencies
	$builtincurrencies = get_option('occommerce_currencies');
	foreach($currencies as $currency) {
		if (!in_array($currency, $builtincurrencies)) {
			ocart_add_currency($currency);
		}
	}

	// active currency
	if (isset($_SESSION['currency'])) {
		$active_curr = $_SESSION['currency'];
	} else {
		$active_curr = $ocart['currencycode'];
	}

?>

<div class="switchbar-inner">
	<ul>
		<li><a href="?currency=<?php echo $active_curr; ?>" class="bar-toggle"><span><?php echo ocart_currency_label($active_curr); ?></span></a>
			<?php if (count($currencies) > 0) { ?>
			<ul>
				<?php foreach($currencies as $curr) { if ($curr == $active_curr) continue; ?>
					<li><a href="?currency=<?php echo $curr; ?>"><span><?php echo ocart_currency_label($curr); ?></span></a></li>
				<?php } ?>
			</ul>
			<?php } ?>
		</li>
	</ul>
</div>

<?php
}

/************************************************************
include custom taxonomies
************************************************************/
$custom_taxonomies = glob(get_template_directory().'/lib/custom/*.php');
if ($custom_taxonomies) {
	foreach ($custom_taxonomies as $custom_taxonomy)
	{
		include $custom_taxonomy;
	}
}

/************************************************************
show products catalog
************************************************************/
function ocart_show_catalog() {
	if (ocart_catalog_version() == 1) {
		get_template_part('template','catalog');
	} else {
		get_template_part('template','catalog-v2');
	}
}

/************************************************************
build a collection slider
************************************************************/
function ocart_new_collection($auto='', $slug='', $override_title='', $count='', $orderby='date', $order='DESC', $exclude='', $autoplay="true", $timer=500, $unique_id='default-carousel') {

	global $post;

	// get collection
	if (!$slug) {
		$terms = get_terms('collection', 'orderby=name&hide_empty=0');
		$count = count($terms);
		if ( $count > 0 ){
			$term = get_term_by('slug', $terms[0]->slug, 'collection');
			$slug = $terms[0]->slug;
		}
	} else {
		$term = get_term_by('slug', $slug, 'collection');
		if (!$term) {
			$term = get_term_by('name', $slug, 'collection');
		}
	}

	// which title to use
	if ($override_title) {
	$name = $override_title;
	} else {
	$name = $term->name;
	}

	// how many products
	if ($count) {
	$num = $count;
	} else {
	$num = -1;
	}

	// pre query
	$args = array( 'post_type' => 'product', 'posts_per_page' => $num, 'collection' => $slug, 'orderby' => $orderby, 'order' => $order );

	// auto collection
	if ($auto) {
		switch ($auto) {
			case 'bestsellers':
				$args = array( 'post_type' => 'product', 'posts_per_page' => $num, 'meta_key' => 'sales', 'orderby' => 'meta_value_num', 'order' => 'DESC' );
				break;
			case 'related':
				if (get_post_meta($post->ID, 'similar_products', true) && get_post_meta($post->ID, 'similar_products', true) != '') { // set via products page
					$post_ids = get_post_meta($post->ID, 'similar_products', true);
					$args = array( 'post_type' => 'product', 'posts_per_page' => $num, 'post__in' => $post_ids, 'orderby' => $orderby, 'order' => $order );
				} else {
					$terms = wp_get_object_terms($post->ID,ocart_get_option('related_tax'));
					if (count($terms)) {
						$post_ids = get_objects_in_term($terms[0]->term_id,ocart_get_option('related_tax'));
						$args = array( 'post_type' => 'product', 'posts_per_page' => $num, 'post__in' => $post_ids, 'taxonomy' => ocart_get_option('related_tax'), 'term' => $terms[0]->slug, 'orderby' => $orderby, 'order' => $order );
					}
				}
				break;
		}
	}

	// check excluded IDs
	if ($exclude) {
		$arr = explode(',', $exclude);
		$args['post__not_in'] = $arr;
	}

	// Get posts assigned with collection
	$collection = new WP_Query( $args );

	$display = '';

$display .= "<div class='wrap'>

<div class='column'>

	<h2>$name</h2>";

	if( $collection->have_posts() ) :

	$display .= "<div class='column-wrap column-$unique_id'>

	<div class='collection-prev'></div>
	<div class='collection-next'></div>

	<ul>";

	while( $collection->have_posts() ) : $collection->the_post();

	$status = get_post_meta($post->ID, 'status', true);
	$mark_as_onsale = get_post_meta($post->ID, 'mark_as_onsale', true);
	$mark_as_new = get_post_meta($post->ID, 'mark_as_new', true);

	$display .= "<li><a href='javascript:lightbox(null, \"". get_template_directory_uri(). "/ajax/product_lightbox.php\", \"\", \"".get_the_ID()."\", \"". get_permalink($post->ID)."\" );' class='column-image'>";

				$display .= get_the_post_thumbnail( get_the_ID(), 'collection-thumb', array('title' => '', 'class' => 'collection_front_image') );
				$display .= ocart_product('collection_hover_image', get_the_ID());

				if (!ocart_get_option('disable_cart')) {

					if ($status == 'sold') {

					$display .= "<span class='catalog_item_status catalog_item_status_sold'>".ocart_sticker_text('sold')."</span>";

					} elseif (isset($mark_as_onsale) && $mark_as_onsale == 'on') {

					$display .= "<span class='catalog_item_status catalog_item_status_sale'>".ocart_sticker_text('sale')."</span>";

					} elseif (isset($mark_as_new) && $mark_as_new == 'on' && ocart_is_new_product() ) {

					$display .= "<span class='catalog_item_status catalog_item_status_new'>".ocart_sticker_text('new')."</span>";

					}

					if (isset($mark_as_new) && isset($mark_as_onsale) && $mark_as_new == 'on' && $mark_as_onsale == 'on' && ocart_is_new_product() ) {
						$display .= '<div class="sticker_new">'.ocart_sticker_text('new', $wrap='span').'</div>';
					}

				}

			$display .= "</a>";

			$display .= "<a href='javascript:lightbox(null, \"". get_template_directory_uri(). "/ajax/product_lightbox.php\", \"\", \"".get_the_ID()."\", \"". get_permalink($post->ID)."\" );' class='column-title'>".get_the_title()."</a>

			<div class='column-price'>".ocart_product('get_price_in_grid')."</div>
			<div class='column-oldprice'>".ocart_product('get_plain_original_price')."</div>
			<div class='clear'></div>

		</li>";

		endwhile;

	$display .= "</ul><div class='clear'></div></div>";

	endif;

$display .= "</div></div>";

$display .= "<script type='text/javascript'>
$(function() {

	$('.column-$unique_id ul').carouFredSel({
		width: '100%',
		height: 'auto',
		scroll: 1,
		align: 'center',
		auto: {
			play: $autoplay,
			duration: $timer,
			pauseOnHover: true,
			pauseOnEvent: true,
			pauseOnResize: true
		},
		direction: 'left',
		prev: {
			button: function() {
				return $(this).parent().siblings('.collection-prev');
			}
		},
		next: {
			button: function() {
				return $(this).parent().siblings('.collection-next');
			}
		}
	});

});
</script>";

	return $display;

}

/************************************************************
return customizable text on sticker
************************************************************/
function ocart_sticker_text($arg, $wrap=false) {
	global $post;
	switch($arg) {
		case 'new':
			$mark_as_new_text = get_post_meta($post->ID, 'mark_as_new_text', true);
			if (!$mark_as_new_text) $mark_as_new_text = __('New!','ocart');
			if ($wrap == 'span') {
			return '<span class="sticker_'.$arg.'">'.$mark_as_new_text.'</span>';
			} else {
			return $mark_as_new_text;
			}
			break;
		case 'instock':
			$instock_text = get_post_meta($post->ID, 'instock_text', true);
			if (!$instock_text) $instock_text = __('In Stock','ocart');
			return $instock_text;
			break;
		case 'sold':
			$sold_text = get_post_meta($post->ID, 'sold_text', true);
			if (!$sold_text) $sold_text = __('Out of Stock','ocart');
			return $sold_text;
			break;
		case 'sale':
			$sale_text = get_post_meta($post->ID, 'mark_as_onsale_text', true);
			if (!$sale_text) {
				if (ocart_has_discount()) {
					$sale_text = sprintf(__('Save %s','ocart'), ocart_has_discount_value());
				} else {
					$sale_text = __('On Sale','ocart');
				}
			}
			return $sale_text;
			break;
	}
}

/************************************************************
return true if product is new
************************************************************/
function ocart_is_new_product() {
	global $post;
	$start_date = get_post_meta($post->ID, 'new_start', true);
	$mark_as_new = get_post_meta($post->ID, 'mark_as_new', true);
	$expiry_date = get_post_meta($post->ID, 'new_expiry', true);

	// marked as new
	if ($mark_as_new != 'on') return false;

	// start date
	if ($start_date != '') {
		$start = strtotime($start_date);
		$now = strtotime(date('Y-m-d'));
		if ($start > $now) return false;
	}

	// expiry date
	if ($expiry_date != '') {
		$expiry = strtotime($expiry_date);
		$now = strtotime(date('Y-m-d'));
		if ($expiry < $now) {
			// expired
			update_post_meta($post->ID, 'mark_as_new', 'off');
			return false;
		}
	}

	return true;

}

/************************************************************
show product title
************************************************************/
function ocart_show_product_title() {
?>

	<div class="product-name"><?php if ( ocart_is_new_product()) echo ocart_sticker_text('new', $wrap = 'span'); ?><?php ocart_product('title'); ?></div>

<?php
}

/************************************************************
show product price and tag
************************************************************/
function ocart_show_price_and_status() {
?>

	<?php if (ocart_get_option('disable_cart') && ocart_get_option('disable_prices')) { } else { ?>
	<div class="product-price"><?php ocart_product('baseprice'); ?></div>
	<?php } ?>
	<?php if (!ocart_get_option('disable_cart')) { ocart_product('status'); } ?><div class="clear"></div>

<?php
}

/************************************************************
set content width
************************************************************/
if ( ! isset( $content_width ) ) $content_width = 614;

/************************************************************
the headers that will be used to send emails from store
************************************************************/
function ocart_mail_headers() {
	$headers = 'From: '.ocart_get_option('mail_name').' <'.ocart_get_option('mail_address').'>' . "\r\n";
	return $headers;
}

/************************************************************
modify args to add sorting parameters
************************************************************/
function ocart_add_order_params($args) {
	global $args;
	$sort = ocart_get_option('sort_products');
	if ($sort == 1) {
		$args['orderby'] = 'menu_order';
		$args['order'] = 'ASC';
	}
	return $args;
}

/************************************************************
display product breadcrumb
************************************************************/
function ocart_product_breadcrumb() {
	global $post;
?>
		<ul class="navi">
			<li><a href="<?php echo home_url(); ?>/" class="navi-home"><?php _e('Home','ocart'); ?></a></li><li>/</li>
			<?php
			$args=array('public' => true,'_builtin' => false);
			$output = 'names'; // or objects
			$operator = 'and'; // 'and' or 'or'
			$taxonomies=get_taxonomies($args,$output,$operator);
			if  ($taxonomies) {
				foreach ($taxonomies as $taxonomy ) {
					if (!in_array($taxonomy, array( 'color', 'size', 'collection'))) {
						$terms =  wp_get_post_terms($post->ID, $taxonomy, $args = array('orderby' => 'term_id'));
						if ($terms && !is_wp_error( $terms )) {
							foreach($terms as $term) {
			?>

			<li><a href="<?php echo get_term_link($term->slug, $taxonomy); ?>" class="navi-tax" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li><li>/</li>

			<?php
							}
						}
					}
				}
			}
			?>
		</ul>
<?php
}

/************************************************************
show notification by email form when product is out of stock
************************************************************/
function ocart_notify_me_stock() {
	global $post;
?>

	<div class="product-text">
		<h3><?php _e('Notify me when this product becomes available','ocart'); ?></h3>
		<form action="/" method="post" id="subscribe_to_form">
			<input type="text" name="subscribe_to_product" id="subscribe_to_product" value="" placeholder="<?php _e('Enter your e-mail...','ocart'); ?>" />
			<input type="hidden" name="product_id" id="product_id" value="<?php the_ID(); ?>" />
			<input type="submit" name="subscribe_to_button" id="subscribe_to_button" value="<?php _e('Notify Me','ocart'); ?>" />
		</form><div class="clear"></div>
		<div class="subs-status">

		</div>
	</div>

<?php
}

/************************************************************
count reviews
************************************************************/
function ocart_reviews_count() {
	global $post;
	$post_comments = get_comments(array( 'type' => 'comment', 'status' => 'approve', 'post_id' => $post->ID ));
	return count($post_comments);
}

/************************************************************
individual user's rating
************************************************************/
function ocart_get_user_rating($comment_id) {
	$rating = get_comment_meta($comment_id, 'rating');
	$stars = $rating[0];
	for($i = 1; $i <= $stars; $i++) {
		echo '<div class="product-rating-star"></div>';
	}
	for($i = $stars; $stars < 5; $stars++) {
		echo '<div class="product-rating-off"></div>';
	}
}

/************************************************************
get catalog version
************************************************************/
function ocart_catalog_version() {
	// search for cookie first
	global $ocart;
	if (isset($_COOKIE['layout'])) {
		if ($_COOKIE['layout'] == 'grid') {
			$catalog_version = 2;
		} else {
			$catalog_version = 1;
		}
	} else {
		if (isset($ocart['catalog_version'])) {
			$catalog_version = $ocart['catalog_version'];
		} else {
			$catalog_version = 1;
		}
	}
	return $catalog_version;
}

/************************************************************
get total product stock level
************************************************************/
function ocart_product_quantity() {
	global $post;
	$stock = get_post_meta($post->ID, 'stock', true);
	if (isset($stock)) {
		if ($stock == 0 && $stock != '') {
			echo 0;
		} elseif ($stock > 0) {
			echo $stock;
		} else {
			echo 999;
		}
	}
}

/************************************************************
get product stock
************************************************************/
function ocart_product_stock() {
	global $post;
	$stock = get_post_meta($post->ID, 'stock', true);
	return $stock;
}

/************************************************************
catalog v2 breadcrumb
************************************************************/
function ocart_breadcrumb($id, $tax) {
	$term = get_term_by('id', $id, $tax);

	if ($tax == 'product_category') {
		if ($term->parent > 0) {
			$parent = get_term_by('id', $term->parent, $tax);
			echo $parent->name.' / '.$term->name;
		} else {
			echo $term->name;
		}
	} else {
		echo ocart_get_taxonomy_nicename($tax).' / '.$term->name;
	}
}

/************************************************************
show the left navigation filters
************************************************************/
function ocart_catalog_filters($tax) {
	global $ocart;
	echo '<div class="header">'.sprintf(__('Shop by %s','ocart'), ocart_get_taxonomy_nicename($tax)).'<a href="#reset">'.__('Reset','ocart').'</a></div>';
	$terms = get_terms( $tax, 'orderby=name&hide_empty='.$ocart['emptyterms']);
	if ($terms && ! is_wp_error( $terms )) {
		echo '<ul class="root-'.$tax.'">';
		foreach($terms as $term) {
			if ($term->parent > 0) { continue; }
			if ($term->parent == 0) { $class = ' class="parent_list"'; } else { $class = ''; }
			if ($term->taxonomy == 'color') {
			echo '<li style="height:34px"'.$class.'><a href="'.get_term_link($term->slug, $term->taxonomy).'" id="'.$term->taxonomy.'-'.$term->slug.'"><span style="margin: 0 0 0 6px;border-radius: 40px; padding: 0 55px;border: 1px solid #eee;background-color: '.$term->name.';">&nbsp;&nbsp;&nbsp;&nbsp;</span></a>';
			} else {
			echo '<li'.$class.'><a href="'.get_term_link($term->slug, $term->taxonomy).'" id="'.$term->taxonomy.'-'.$term->slug.'">'.$term->name.'</a>';
			}
			if ($ocart['showcount'] == true) {
			echo '<span class="cat_count">('.$term->count.')</span>';
			}
			// any children?
			$sub = get_terms( $term->taxonomy, 'orderby=name&child_of='.$term->term_id.'&hide_empty='.$ocart['emptyterms']);
			if ($sub && ! is_wp_error( $sub ) && $tax == 'product_category') {
				echo '<ul class="children">';
				foreach($sub as $subterm) {
					echo '<li><a href="'.get_term_link($subterm->slug, $subterm->taxonomy).'" id="'.$subterm->taxonomy.'-'.$subterm->slug.'">'.$subterm->name.'</a>';
					if ($ocart['showcount'] == true) {
						echo '<span class="cat_count">('.$subterm->count.')</span>';
					}
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
}

/************************************************************
show grid filters
************************************************************/
function ocart_show_grid_filters() {
	global $ocart;

?>

<div class="header" id="price_range_slider"><?php echo __('Price Range','ocart'); ?><a href="#reset"><?php echo __('Reset','ocart'); ?></a></div>
<div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
	<div class="ui-slider-range ui-widget-header"></div>
	<div class="text_min"><?php echo ocart_format_currency( '<ins>0</ins>' ); ?></div>
	<div class="text_max"><?php echo ocart_format_currency( '<ins>'.ocart_show_price_plain( ocart_max_price() ).'</ins>' ); ?></div>
    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#min"></a>
	<a class="ui-slider-handle ui-state-default ui-corner-all" href="#max"></a>
</div>

<?php

	if (isset($ocart['grid_attr']) && is_array($ocart['grid_attr'])) {
		$filters = $ocart['grid_attr'];
	} else {
		$filters = array('product_category', 'brand', 'color', 'size'); // default fallback
	}
	foreach($filters as $filter) {
		ocart_catalog_filters($filter);
	}
}

/************************************************************
show product rating
************************************************************/
function ocart_product_avg_rating() {
	global $post;
	$post_comments = get_comments(array( 'type' => 'comment', 'status' => 'approve', 'post_id' => $post->ID ));
	$avg_total = 0;

	if ($post_comments) {

		foreach($post_comments as $comment) {
			$ratings = get_comment_meta($comment->comment_ID, 'rating');
			foreach($ratings as $rating) {
				$avg_total += $rating;
			}
		}

		$avg = $avg_total / count($post_comments);

		echo '<div class="product-rating-wrapper">';

		for($i = 1; $i <= $avg; $i++) {
			echo '<div class="product-rating-star"></div>';
		}
		for($i = $avg; $avg < 5; $avg++) {
			echo '<div class="product-rating-off"></div>';
		}

		echo '</div><div class="clear"></div>';

		echo '<div class="clear"></div><div class="product-rating-note">'.sprintf(__('Avg. customer rating: <strong>%s</strong> / 5','ocart'), number_format((double)($avg_total / count($post_comments)), 2), count($post_comments)).'</div>';

	} else {

		for($i = 1; $i <= 5; $i++) {
			echo '<div class="product-rating-off"></div>';
		}

		echo '<div class="clear"></div><div class="product-rating-note">'.__('No reviews yet. <a href="#rate" id="rate_product">Be the first!</a>','ocart').'</div>';

	}

}

/************************************************************
return client IP address
************************************************************/
function ocart_visitor_IP() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}

/************************************************************
check for an enabled gateway return true/false
************************************************************/
function ocart_enabled_gateway($gateway) {
	global $ocart;
	if (isset($ocart[$gateway.'_enabled']) && $ocart[$gateway.'_enabled'] == 1) {
		return true;
	}
}

/************************************************************
disable gateway if conditions not met
************************************************************/
function ocart_disable_gateway($gateway) {
	global $ocart;
	if ($gateway == 'cod') {
		if (isset($ocart['cod_requirelogin']) && $ocart['cod_requirelogin'] == 1) {
			if (!is_user_logged_in()) {
				echo 'disabled="disabled"';
			}
		}
	}
}

/************************************************************
display alert when login is required to pay
************************************************************/
function ocart_require_login_to_pay($gateway) {
	global $ocart;
	if ($gateway == 'cod') {
		if (isset($ocart['cod_requirelogin']) && $ocart['cod_requirelogin'] == 1) {
			if (!is_user_logged_in()) {
				echo '<abbr>'.sprintf(__('You must <a href="javascript:lightbox(null, \'%s/ajax/login.php\');">login</a> to pay using this method','ocart'), get_template_directory_uri()).'</abbr>';
			}
		}
	}
}

/************************************************************
does this method require additional charge
************************************************************/
function ocart_require_additional_charge($gateway) {
	global $ocart;
	if (isset($ocart[$gateway.'_charge'])) {
		if (!empty($ocart[$gateway.'_charge'])) {
			printf(__('(Additional Charges: %s)','ocart'), ocart_format_currency( ocart_show_price( $ocart[$gateway.'_charge'] ) ));
		}
	}
}


/************************************************************
get the extra charge input in attribute
************************************************************/
function ocart_get_pay_extra_charge($gateway) {
	global $ocart;
	if (isset($ocart[$gateway.'_charge'])) {
		if (!empty($ocart[$gateway.'_charge'])) {
			echo $ocart[$gateway.'_charge'];
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}
}

/************************************************************
show available payment options in checkout
************************************************************/
function ocart_payment_options() {

	// check payment gateways
	$gateways = get_option('occommerce_OC_gateways');
	$gateways = array_reverse($gateways);
	foreach($gateways as $gateway => $gateway_arr) {
		if (ocart_enabled_gateway($gateway)) {
?>

	<label for="pay_by_<?php echo $gateway; ?>"><input type="radio" data-fee="<?php ocart_get_pay_extra_charge($gateway); ?>" name="cform_pay_option" id="pay_by_<?php echo $gateway; ?>" value="pay_by_<?php echo $gateway; ?>" <?php ocart_disable_gateway($gateway); ?> /><?php echo $gateway_arr['paymentname']; ?> <?php ocart_require_additional_charge($gateway); ?> <?php ocart_require_login_to_pay($gateway); ?>

	<?php do_action("ocart_display_payment_logo_{$gateway}"); ?>

	</label>

<?php
		}
	}
}

/************************************************************
return current skin
************************************************************/
function ocart_current_skin() {
	global $ocart;

	if (isset($_COOKIE['oc_cookie_skin'])) {

		$skin = $_COOKIE['oc_cookie_skin'];

	} else {

		if (!isset($ocart['skin'])) {
			$skin = 'default';
		} else {
			$skin = $ocart['skin'];
		}

	}

	return $skin;
}

/************************************************************
get current skin directory
************************************************************/
function ocart_current_skin_uri() {
	echo get_stylesheet_directory_uri().'/skins/'.ocart_current_skin();
}

/************************************************************
return current skin directory
************************************************************/
function ocart_get_current_skin_uri() {
	return get_stylesheet_directory_uri().'/skins/'.ocart_current_skin();
}

/************************************************************
theme background color
************************************************************/
function ocart_background() {
	global $ocart;
	$cur_skin = ocart_current_skin();
	if (isset($ocart["$cur_skin"."_body_bg"]) && isset($ocart['theme_usebgcolor']) && $ocart['theme_usebgcolor'] == 1) {
		$ocart_skin = $ocart['skin'].'_body_bg';
		echo $ocart["$ocart_skin"];
	} else {
		echo 'url('.ocart_get_current_skin_uri().'/bg.png) repeat';
	}
}

/************************************************************
get skin data
************************************************************/
function ocart_skin_data($data) {

	global $ocart;

	$skin = get_option('occommerce_skin_'.ocart_current_skin());

	$cur_skin = ocart_current_skin();

	if (isset($_COOKIE['oc_cookie_skin'])) {
		echo $skin["$data"];
	} else {

		if (isset($ocart["$cur_skin"."_"."$data"])) {
			$ocart_skin = $ocart['skin'].'_'.$data;
			echo $ocart["$ocart_skin"];
		} else {
			echo $skin["$data"];
		}

	}

}

/************************************************************
dashboard naming purposes
************************************************************/
function ocart_skin_data_option($num) {
	switch ($num) {
		case 'header_bg': _e('Header Background Color','ocart'); break;
		case 'body_bg': _e('Body Background Color','ocart'); break;
		case 'active_color': _e('Active Color','ocart'); break;
		case 'text_color_1': _e('Footer Widgets Text','ocart'); break;
		case 'text_color_2': _e('Secondary Text','ocart'); break;
		case 'text_color_3': _e('Primary Text','ocart'); break;
		case 'text_color_4': _e('Meta and Date Text','ocart'); break;
		case 'text_color_5': _e('Post Author','ocart'); break;
		case 'nav_color': _e('Main Navigation Links','ocart'); break;
		case 'nav_hover_color': _e('Main Navigation Hover','ocart'); break;
		case 'bottom_bg': _e('Footer Widgets Background','ocart'); break;
		case 'catalog_border': _e('Catalog Border Color','ocart'); break;
		case 'header_border': _e('Header Border Color','ocart'); break;
		case 'footer_border': _e('Footer Border Color','ocart'); break;
		case 'common_border_1': _e('Comments Border Color','ocart'); break;
		case 'widget_border': _e('Widget/Box Border Color','ocart'); break;
		case 'comments_color': _e('Comments Text','ocart'); break;
		case 'comments_meta_color': _e('Comments Meta Text','ocart'); break;
		case 'comments_author': _e('Comment Author','ocart'); break;
		case 'button_hover_1': _e('Button 1 Hover','ocart'); break;
		case 'button_hover_2': _e('Button 2 Hover','ocart'); break;
		case 'button_style1_color': _e('Button Style 1 Color','ocart'); break;
		case 'button_style1_hover': _e('Button Style 1 Hover','ocart'); break;
		case 'button_style2_color': _e('Button Style 2 Color','ocart'); break;
		case 'button_style2_hover': _e('Button Style 2 Hover','ocart'); break;
		case 'heading3': _e('Widget Header Text','ocart'); break;
		case 'slide_text_color': _e('Slideshow Text Color','ocart'); break;
		case 'slide_text_bg': _e('Slideshow Text Background','ocart'); break;
		case 'menu_color': _e('Menu Style 2 Primary Color','ocart'); break;
		case 'menu_active_color': _e('Menu Style 2 Active Color','ocart'); break;
		case 'menu_hover_color': _e('Menu Style 2 Hover Color','ocart'); break;
		case 'menu_hover_bg': _e('Menu Style 2 Hover Background','ocart'); break;
		case 'menu_sub_bg': _e('Menu Style 2 Sub Background','ocart'); break;
		case 'menu_sub_color': _e('Menu Style 2 Sub Color','ocart'); break;
		case 'menu_sub_border': _e('Menu Style 2 Border Color','ocart'); break;
	}
}

/************************************************************
get logo image as <img> tag
************************************************************/
function ocart_logo_img() {

	global $ocart;

	// logo url
	$file_loc = get_option('occommerce_logo_url');
	if (isset($file_loc) && !empty($file_loc)) {
		$url = $file_loc;
	} else {
		$url = ocart_get_current_skin_uri().'/logo.png';

	}
	echo '<img src="'.$url.'" alt="'.get_bloginfo('name').'" title="'.get_bloginfo('name').'" />';
}

/************************************************************
logo url
************************************************************/
function ocart_logo_url() {

	global $ocart;

	// logo url
	$file_loc = get_option('occommerce_logo_url');
	if (isset($file_loc) && !empty($file_loc)) {
		$url = $file_loc;
	} else {
		$url = ocart_get_current_skin_uri().'/logo.png';

	}
	return $url;
}

/************************************************************
coupon by coupon title
************************************************************/
function ocart_coupon_id($title) {
    global $wpdb;
	$r_post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type='coupon'", $title));
	if ($r_post_id) {
		return (int)$r_post_id;
	} else {
		return false;
	}
}

/************************************************************
get currency code for transaction
************************************************************/
function ocart_get_currency_code() {
	global $ocart;
	if ( isset( $_GET['currency'] ) ) {
		$_SESSION['currency'] = $_GET['currency'];
		$currency_code = $_SESSION['currency'];
	} else {
		if(isset($_SESSION['currency'])) {
			$_GET['currency'] = $_SESSION['currency'];
			$currency_code = $_SESSION['currency'];
		} else {
			$currency_code = $ocart['currencycode'];
		}
	}
	return $currency_code;
}

/************************************************************
get currency symbol by code
************************************************************/
function ocart_get_currency_symbol() {
    global $ocart;

    if (isset($_GET['currency'])) {
        $_SESSION['currency'] = $_GET['currency'];
        $currency_code = $_SESSION['currency'];
    } else {
        if (isset($_SESSION['currency'])) {
            $_GET['currency'] = $_SESSION['currency'];
            $currency_code = $_SESSION['currency'];
        } else {
            $currency_code = $ocart['currencycode'];
        }
    }

    switch ($currency_code) {
        case 'USD': $code = '$'; break;
        case 'EUR': $code = '€'; break;
        case 'GBP': $code = '£'; break;
        case 'AUD': $code = '$AU'; break;
        case 'JPY': $code = '¥'; break;
        case 'NOK': $code = 'kr'; break;
        case 'INR': $code = '<span class="WebRupee">Rs.</span>'; break;
        default: $code = $currency_code; break;
    }

    // space to currency
    if (ocart_get_option('cur_no_space')) {
        if (ocart_get_option('currency_pos') == 'left') {
            $code = $code . ' ';
        } else {
            $code = ' ' . $code;
        }
    }

    return $code;
}

/************************************************************
format price with currency
************************************************************/
function ocart_format_currency($money, $nosymbol=false) {

	// currency symbol position
	global $ocart;
	if (ocart_get_option('currency_pos') == 'left') {
		if ($nosymbol) {
		$format = $money;
		} else {
		$format = ocart_get_currency_symbol().$money;
		}
	} else {
		if ($nosymbol) {
		$format = $money;
		} else {
		$format = $money.ocart_get_currency_symbol();
		}
	}

	return $format;

}

/************************************************************
print saved coupons
************************************************************/
function ocart_print_saved_coupons() {
	global $ocart;
	foreach($_SESSION as $sess => $array) {
		if (strstr($sess, 'coupon_')) {
			if ($array['type'] == 2) {
				$array['amount'] = number_format(ocart_real_coupon_value($array['amount']), 2);
			}
			if ($array['type'] == 1) {
				$array['amount'] = number_format($array['amount'], 2);
			}
			echo '<div class="calc-coupon">'.$array['code'].'<span>-'.ocart_format_currency( '<ins class="inner_coupon_value">'.$array['amount'].'</ins>').'</span></div>';
		}
	}
}

/************************************************************
show coupon info in cart summary
************************************************************/
function ocart_show_coupons_in_cart() {
	global $ocart;
	foreach($_SESSION as $sess => $array) {
		if (strstr($sess, 'coupon_')) {
			if ($array['type'] == 2) {
				$array['amount'] = number_format(ocart_real_coupon_value($array['amount']), 2);
			}
			if ($array['type'] == 1) {
				$array['amount'] = number_format($array['amount'], 2);
			}
			echo '<div class="checkout_est">'.sprintf(__('Discount code: %s','ocart'), $array['code']).'<span>-'.ocart_format_currency( $array['amount'] ).'</span></div>';
		}
	}
}

/************************************************************
save coupon codes into order
************************************************************/
function ocart_save_coupons() {
	$coupons = array();
	foreach($_SESSION as $sess => $array) {
		if (strstr($sess, 'coupon_')) {
			$usage = get_post_meta($array['id'], 'usage_limit', true);
			$used = (int)get_post_meta($array['id'], 'usage_count', true);
			if ($usage >= 1) {
				update_post_meta($array['id'], 'usage_limit', $usage - 1);
				update_post_meta($array['id'], 'usage_count', $used + 1);
			}
			$coupons[] = $_SESSION['coupon_'.$array['id']];
		}
	}
	return $coupons;
}

/************************************************************
save coupon values in an array
************************************************************/
function ocart_coupon_values_array() {
	global $ocart;
	$coupon_values = array();
	foreach($_SESSION as $sess => $array) {
		if (strstr($sess, 'coupon_')) {
			if ($array['type'] == 2) {
				$coupon_values[$array['id']] = ocart_real_coupon_value($array['amount']);
			}
			if ($array['type'] == 1) {
				$coupon_values[$array['id']] = $array['amount'];
			}
		}
	}
	return $coupon_values;
}

/************************************************************
display content summary /read more (for Archiving purpose)
************************************************************/
function ocart_the_content($excerpt_length = 55, $ending = '...', $superending = '',$post_id=null,$limit=null) {
	if ($post_id) {
		$post = get_post($post_id);
		setup_postdata($post);
	}
	$text = get_the_content('');
	$text = strip_shortcodes( $text );

	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = strip_tags($text);

	if ($limit == 'char') {

		if (!$text) {
			echo '<p>'.__('No description has been entered yet.','ocart').'</p>';
		} else {
			echo '<p>'.substr($text,0,$excerpt_length).$ending.'</p>';
		}

	} else {

		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $ending;
			echo '<p>'.$text.'</p>'.$superending;
		} else {
			$text = implode(' ', $words);
			echo '<p>'.$text.'</p>';
		}

	}

}

/************************************************************
display recent comments without any plugin
************************************************************/
function ocart_recent_comments($no_comments = 5, $comment_len = 100) {
    global $wpdb;
    $request = "SELECT * FROM $wpdb->comments";
    $request .= " JOIN $wpdb->posts ON ID = comment_post_ID";
    $request .= " WHERE comment_approved = '1' AND post_status = 'publish' AND post_password =''";
    $request .= " ORDER BY comment_date DESC LIMIT $no_comments";
    $comments = $wpdb->get_results($request);
    if ($comments) {
        foreach ($comments as $comment) {
            ob_start();
            ?>
                <li class="comment">
                <!-- Comment content -->
				<span class="comment_excerpt"><a href="<?php echo get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID; ?>"><?php echo strip_tags(substr(apply_filters('get_comment_text', $comment->comment_content), 0, $comment_len)); ?></a></span>
				<!-- When -->
				<span class="comment_time"><a href="<?php echo get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID; ?>"><?php echo human_time_diff(get_comment_date('U',$comment->comment_ID), current_time('timestamp')), __(' ago', 'ocart'); ?></a></span>
				<div class="comment_author"><?php echo get_avatar( $comment->comment_author_email, 32, $d = get_template_directory_uri() . '/img/assets/default-user.png'); ?></div>
                </li>
            <?php
            ob_end_flush();
        }
    }
}

/************************************************************
display an appropriate user friendly title
************************************************************/
function ocart_page_title() {
	if (is_home()) {
		printf(__('%s | %s','ocart'), get_bloginfo('name'), get_bloginfo('description'));
	} elseif (is_single() || is_page()) {
		printf(__('%s | %s','ocart'), single_post_title(), get_bloginfo('name'));
	} elseif (is_404()) {
		printf(__('404 | %s','ocart'), get_bloginfo('name'));
	} elseif (is_category()) {
		printf(__('%s | %s','ocart'), single_cat_title(), get_bloginfo('name'));
	} elseif (is_search()) {
		printf(__('Search Results | %s','ocart'), get_bloginfo('name'));
	} elseif (is_tag()) {
		printf(__('Topic | %s | %s','ocart'), get_query_var('tag'), get_bloginfo('name'));
	} elseif (is_tax()) {
		global $taxonomy,$term;
		$term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		printf(__('%s | %s','ocart'), $term->name, get_bloginfo('name'));
	}
}

/************************************************************
payment logos in footer
************************************************************/
function ocart_payment_logos() {
	global $ocart;
	$arr = $ocart['paymethods'];
	if (!empty($arr)) {
		print "<ul class=\"payment-icons\">";
		foreach($arr as $val) {
			print "<li class=\"$val\"></li>";
		}
		print "</ul>";
	}
}

/************************************************************
get nice status for order
************************************************************/
function ocart_order_status($id = '') {
	global $post;
	if (!$id) {
		$id = $post->ID;
	}
	$status = get_post_meta($id, 'order_status', true);
	switch($status) {
		case 'received': _e('Received','ocart'); break;
		case 'awaiting': _e('Awaiting Payment','ocart'); break;
		case 'pending': _e('Pending','ocart'); break;
		case 'processing': _e('Processing','ocart'); break;
		case 'shipped': _e('Shipped','ocart'); break;
		case 'delivered': _e('Delivered','ocart'); break;
		case 'declined': _e('Declined','ocart'); break;
		case 'cancelled': _e('Cancelled','ocart'); break;
	}
}

/************************************************************
get tracking number
************************************************************/
function ocart_order_tracking_number($id = '') {
	global $post;
	if (!$id) {
		$id = $post->ID;
	}
	$track = get_post_meta($id, 'order_tracking', true);
	$track_url = get_post_meta($id, 'order_tracking_url', true);
	if ($track_url && $track) {
		echo '<a href="'.$track_url.'" target="_blank">'.$track.'</a>';
	} elseif ($track) {
		echo $track;
	}
}

/************************************************************
is a required field?
************************************************************/
function ocart_is_required($field) {
	if (in_array($field, array('cform_fname','cform_lname','cform_addr1','cform_city','cform_state','cform_postcode','cform_country','cform_phone','cform_fname2','cform_lname2','cform_addr12','cform_city2','cform_state2','cform_postcode2','cform_country2','cform_phone2'))) {
		if ($field == 'cform_addr1') {
		return '<span class="req">('.__('as it appears on your credit card','ocart').')</span>';
		} elseif ($field == 'cform_phone') {
		return '<span class="req">('.__('required for order verification','ocart').')</span>';
		} elseif ($field == 'cform_addr12') {
		return '<span class="req">('.__('we will ship to this address','ocart').')</span>';
		} else {
		return '<span class="req">('.__('*','ocart').')</span>';
		}
	}
}

/************************************************************
get user fields
************************************************************/
function ocart_get_field($field) {
	$current_user = wp_get_current_user();
	$id = $current_user->ID;
	return get_user_meta($id, $field, true);
}

/************************************************************
show the tagline
************************************************************/
function ocart_list_product_tag() {
	global $post;
	$tagline = get_post_meta($post->ID, 'tagline', true);
	$tagline_term = get_post_meta($post->ID, 'tagline_term', true);
	if (!empty($tagline)) {
		echo '<span class="pre">'.$tagline.'</span>';
	} else {
		if (isset($tagline_term) && !empty($tagline_term)) $tax = $tagline_term; else $tax = ocart_get_option('grid_default_tagline_attribute');
		$terms = wp_get_post_terms($post->ID, $tax, $args = array('fields' => 'names', 'orderby' => 'term_id'));
		if ($terms && ! is_wp_error( $terms )) {
			echo '<span class="pre">'.sprintf(__('%s:','ocart'), ocart_taxonomy_plural_name($tax)).'</span>';
			$new_array = array();
			foreach($terms as $term) {
				array_push($new_array, $term);
			}
			echo implode(", ", $new_array);
		}
	}
}

/************************************************************
check if it has tag
************************************************************/
function ocart_has_product_tag() {
	global $post;
	$tagline = get_post_meta($post->ID, 'tagline', true);
	$tagline_term = get_post_meta($post->ID, 'tagline_term', true);
	if (!empty($tagline)) {
		return true;
	} else {
		if (isset($tagline_term) && !empty($tagline_term)) $tax = $tagline_term; else $tax = ocart_get_option('grid_default_tagline_attribute');
		$terms = wp_get_post_terms($post->ID, $tax, $args = array('fields' => 'names', 'orderby' => 'term_id'));
		if ($terms && ! is_wp_error( $terms )) {
			return true;
		}
	}
	return false;
}

/************************************************************
return current URL in addressbar for referring purposes
************************************************************/
function ocart_current_url() {
    $protocol = 'http';
    return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

/************************************************************
clear session data after placing order
************************************************************/
function ocart_clear_cart() {
	unset($_SESSION['cart']);
	unset($_SESSION['ocart_cart_count']);
	unset($_SESSION['ocart_shipping_fee']);
	unset($_SESSION['ocart_shipping_opt']);
	foreach($_SESSION as $sess => $array) {
		if (strstr($sess, 'coupon_')) {
			unset($_SESSION['coupon_'.$array['id']]);
		}
	}
}

/************************************************************
auto login a user by ID
************************************************************/
function ocart_auto_login($id) {
	$user = get_userdata($id);
	$user_login = $user->user_login;
	wp_set_current_user($id, $user_login);
	wp_set_auth_cookie($id);
	do_action('wp_login', $user_login);
}

/************************************************************
show login or logout link in navbar
************************************************************/
function ocart_login() {
	if (!is_user_logged_in()) {
?>
		<?php if (ocart_get_option('show_login')) { ?>
		<li><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');" class="dark"><?php _e('Login & Register','ocart'); ?></a></li>
		<?php } ?>
<?php } else { ?>
		<?php if (!ocart_get_option('disable_cart')) { ?>
		<li><a href="<?php echo get_permalink( get_page_by_path( 'myorders' ) ); ?>"><?php echo get_the_title(get_page_by_path('myorders')->ID); ?></a></li>
		<?php } ?>
		<?php if (ocart_get_option('show_login')) { ?>
		<li><a href="<?php echo wp_logout_url( ocart_current_url() ); ?>" class="dark"><?php _e('Logout','ocart'); ?></a></li>
		<?php } ?>
<?php
	}
}

/************************************************************
list store options
************************************************************/
function ocart_store_nav($target = '', $custom_id = '') {
	global $ocart;

	// for specific taxonomy archives
	if (get_query_var( 'taxonomy' ) && get_query_var('taxonomy') == 'product_category') {
		$target = get_query_var( 'taxonomy' );
		$target_term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		if ($target_term->parent) {
		$custom_id = $target_term->parent;
		} else {
		$custom_id = $target_term->term_id;
		}
	}

	// terms
	$parents = get_terms( 'product_category', 'orderby=name&hide_empty='.$ocart['emptyterms'].'&exclude='.$custom_id);

	// sort link class
	if (ocart_get_option('show_nav')) {
		$main_class = 'sort-link';
	} else {
		$main_class = 'sort-link-nodd';
	}

?>

	<?php if (ocart_get_option('show_nav_all')) { ?>
	<ul id="options">
		<?php if ($custom_id) { ?>
		<li class="sort"><a href="#" class="<?php echo $main_class; ?>"><?php $name = get_term_by('id', $custom_id, 'product_category'); echo $name->name; ?></a>
		<?php } else { ?>
		<li class="sort"><a href="#" class="<?php echo $main_class; ?>"><?php _e('Shop by Brand','ocart'); ?></a>
		<?php } ?>
			<?php if (ocart_get_option('show_nav')) { ?>
			<ul class="options">

				<?php if ($custom_id) { ?>
				<li><a href="#"><?php _e('Shop by Brand','ocart'); ?></a></li>
				<?php } ?>
				<?php
				foreach ($parents as $term) {
					if ($term->parent > 0) { continue; }
				?>
						<li><a href="#" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
				<?php } ?>

			</ul>
			<?php } ?>
		</li>
	</ul>
	<?php } ?>

	<?php if ($target && (int)$custom_id) { $term = get_term_by('id', $custom_id, $target); // we know it is a product category ?>

	<ul class="list">

			<?php
			if (get_query_var( 'taxonomy' ) == 'product_category' && get_query_var( 'term' )) {
			$term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

			// parent term
			if ($term->parent) {
				$target_term = get_term_by('id', $term->parent, get_query_var( 'taxonomy' ) );
				$target_term_link = $target_term->slug;
			} else {
				$target_term_link = null;
			}

			?>

			<?php if ($target_term_link != null) { ?>
			<li><a href="<?php echo get_term_link($target_term_link, 'product_category'); ?>" id="<?php echo $target_term->taxonomy; ?>-<?php echo $target_term->slug; ?>"><?php _e('All','ocart'); ?></a></li>
			<?php } else { ?>
			<li><a href="<?php echo get_term_link($term->slug, 'product_category'); ?>" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>" class="current"><?php _e('All','ocart'); ?><span></span></a></li>
			<?php } ?>

			<?php } else { ?>

			<?php
			$ii = get_term_by('id', $custom_id, $target);
			?>

			<li><a href="<?php echo get_term_link($ii->slug, $target); ?>" id="<?php echo $ii->taxonomy; ?>-<?php echo $ii->slug; ?>" class="current"><?php _e('All','ocart'); ?><span></span></a></li>

			<?php } ?>

			<?php
			$terms = get_terms( $target, 'orderby=name&hide_empty='.$ocart['emptyterms'].'&child_of='.$custom_id );
			foreach($terms as $term) {
			?>

				<li><a href="<?php echo get_term_link($term->slug, 'product_category'); ?>" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>"<?php if (get_query_var('term') == $term->slug) echo ' class="current"'; ?>><?php echo $term->name; ?><?php if (get_query_var('term') == $term->slug) echo '<span></span>'; ?></a></li>

			<?php } ?>

	</ul><div class="clear"></div>

	<a href="#" class="prev"></a>
	<a href="#" class="next"></a>

	<?php } else { // display brands ?>

	<ul class="list">

			<?php
			if (get_query_var( 'taxonomy' ) == 'brand' && get_query_var( 'term' )) {
			$term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			?>
			<li><a href="<?php echo get_term_link($term->slug, 'brand'); ?>" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>"<?php if (get_query_var('term') == $term->slug) echo 'class="current"'; ?>><?php echo $term->name; ?><?php if (get_query_var('term') == $term->slug) echo '<span></span>'; ?></a></li>
			<li><a href="#"><?php _e('All','ocart'); ?></a></li>
			<?php } else { ?>
			<li><a href="#" class="current"><?php _e('All','ocart'); ?><span></span></a></li>
			<?php } ?>

			<?php
			if (get_query_var( 'taxonomy' ) == 'brand' && get_query_var( 'term' )) {
				$custom_tax = 'brand';
			} else {
				$custom_tax = ocart_get_option('default_nav_tax');
			}
			?>

			<?php
			$terms = get_terms( $custom_tax, 'orderby=name&hide_empty='.$ocart['emptyterms'] );
			foreach($terms as $term) {
				if (get_query_var('term') == $term->slug) continue;
			?>
			<li><a href="<?php echo get_term_link($term->slug, $custom_tax); ?>" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
			<?php } ?>

	</ul><div class="clear"></div>

	<a href="#" class="prev"></a>
	<a href="#" class="next"></a>

	<?php } ?>

<?php
}

/************************************************************
display product metadata
************************************************************/
function ocart_product($data, $id = '') {

	// variables
	global $post, $ocart;
	if (!$id) { $id = $post->ID; }

	$custom = get_post_custom($id);
	$price = $custom['price'][0];
	$reg_price = $custom['regular_price'][0];
	if (isset($custom['hover_image'][0])){
	$hover_image = $custom['hover_image'][0];
	}
	if (isset($custom['imagesize'])) {
	$size = $custom['imagesize'][0];
	}
	$crop = $custom['imagecrop'][0];
	$status = $custom['status'][0];
	if (isset($custom['mark_as_onsale'])) {
	$mark_as_onsale = $custom['mark_as_onsale'][0];
	}
	if (isset($custom['mark_as_new'])) {
	$mark_as_new = $custom['mark_as_new'][0];
	}

	// zc [ 1: 0]
	if (isset($custom['imagecrop_method'][0])) {
		$zc = $custom['imagecrop_method'][0];
	} else {
		$zc = 1;
	}

	// get title
	if ($data == 'title') {
		the_title();
	}

	// get product special tag if any (new/sale/sold)
	if ($data == 'tag') {
		if ($status == 'sold') {
			echo '<div class="c-tag tag-sold"></div>';
		} elseif (isset($mark_as_onsale) && $mark_as_onsale == 'on') {
			echo '<div class="c-tag tag-sale"></div>';
		} elseif (isset($mark_as_new) && $mark_as_new == 'on' && ocart_is_new_product() ) {
			echo '<div class="c-tag tag-new"></div>';
		}
		if ($status == 'sold') {
			echo '<div class="soldout-layer"></div>';
			echo '<div class="soldout">'.ocart_sticker_text('sold').'</div>';
		}
		// two stickers? new and on sale
		if (isset($mark_as_new) && isset($mark_as_onsale) && $mark_as_new == 'on' && $mark_as_onsale == 'on' && ocart_is_new_product() ) {
			echo '<div class="sticker_new">'.ocart_sticker_text('new', $wrap='span').'</div>';
		}
	}

	// get product availability
	if ($data == 'status') {
		if ($status == 'sold') {
			echo '<div class="product-tag product-tag-sold">'.ocart_sticker_text('sold').'</div><div class="clear"></div>';
		} elseif (isset($mark_as_onsale) && $mark_as_onsale == 'on') {
			echo '<div class="product-tag product-tag-sale">'.ocart_sticker_text('sale').'</div><div class="clear"></div>';
		} else {
			echo '<div class="product-tag product-tag-instock">'.ocart_sticker_text('instock').'</div><div class="clear"></div>';
		}
	}

	// get product prices
	if ($data == 'price') {
		if (isset($reg_price) && $reg_price > $price) {
			echo '<span class="price-old">'.ocart_format_currency(ocart_show_price($reg_price)).'</span>';
		}
		echo '<span class="price-now">';
		// find custom prices
		$prices = array();
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			foreach ($taxonomies as $taxonomy ) {
				if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
					$terms = wp_get_post_terms($id, $taxonomy, array("fields" => "all"));
					if ($terms && ! is_wp_error( $terms )) {
						foreach($terms as $term) {
							$custom_price_option = get_post_meta( $id, 'product_'.$id.'_'.$term->taxonomy.'_'.$term->term_id, true);
							if (!empty($custom_price_option)) {
								$prices[] = $custom_price_option + $price;
							}
						}
					}
				}
			}
		}
		$prices[] = $price;
		if (count($prices) > 1) {
			printf(__('from %s','ocart'), ocart_format_currency( ocart_show_price(min($prices)) ) );
		} else {
			echo ocart_format_currency( ocart_show_price($prices[0]) );
		}
		echo '</span>';
	}

	// get base price
	if ($data == 'baseprice') {
		if (isset($reg_price) && $reg_price > $price) {
			echo '<span class="price-old">'.ocart_format_currency(ocart_show_price($reg_price)).'</span>';
		}
		echo '<span class="price-now">';
		echo ocart_format_currency( ocart_show_price($price) );
		echo '</span>';
	}

	// get plain price
	if ($data == 'plain_price') {
		echo ocart_format_currency(ocart_show_price($price));
	}

	// get price in grid view
	if ($data == 'price_in_grid') {
		// find custom prices
		$prices = array();
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			foreach ($taxonomies as $taxonomy ) {
				if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
					$terms = wp_get_post_terms($id, $taxonomy, array("fields" => "all"));
					if ($terms && ! is_wp_error( $terms )) {
						foreach($terms as $term) {
							$custom_price_option = get_post_meta( $id, 'product_'.$id.'_'.$term->taxonomy.'_'.$term->term_id, true);
							if (!empty($custom_price_option)) {
								$prices[] = $custom_price_option + $price;
							}
						}
					}
				}
			}
		}
		$prices[] = $price;
		if (count($prices) > 1) {
			printf(__('<ins>from</ins> %s','ocart'), ocart_format_currency(ocart_show_price(min($prices))));
		} else {
			echo ocart_format_currency( ocart_show_price($prices[0]) );
		}
	}

	// get plain original price
	if ($data == 'plain_original_price') {
		if (isset($reg_price) && $reg_price != '') {
			echo ocart_format_currency( ocart_show_price($reg_price) );
		}
	}

	// return true if original price exists
	if ($data == 'have_original_price') {
		if (isset($reg_price) && $reg_price != '') {
			return true;
		}
	}

	// price no decimal
	if ($data == 'price_no_decimal') {
		echo ocart_format_currency( (int)$price );
	}

	// get product info/summary
	if ($data == 'details') {
		if ($id) {
			$content_post = get_post($id);
			echo wpautop($content_post->post_content);
		} else {
			echo wpautop($post->post_content);
		}
	}

	// show catalog image [variable via product options]
	if ($data == 'catalog_image') {

		if ($size == 'pic-small') { $w = 100; $h = 100; }
		if ($size == 'pic-medium') { $w = 125; $h = 125; }
		if ($size == 'pic-default') { $w = 194; $h = ocart_get_option('catalog_image_height'); }
		if (!isset($size)) { $w = 194; $h = ocart_get_option('catalog_image_height'); }

		$url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');

		// hover image
		if (isset($hover_image)) {
		if ((int)$hover_image && $status != 'sold') {
			$hover = wp_get_attachment_image_src( $hover_image, 'full');
			echo '<div class="producthover"><img src="'.get_template_directory_uri().'/thumb.php?src='.$hover[0].'&amp;w='.$w.'&amp;h='.$h.'&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" class="productfront '.$size.'" alt="" /></div>';
		}
		}

		echo '<img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w='.$w.'&amp;h='.$h.'&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" class="productfront '.$size.'" alt="" />';

	}

	// product hover v2
	if ($data == 'product_hover') {
		// hover image
		if (isset($hover_image)) {
		if ((int)$hover_image) {
			$hover = wp_get_attachment_image_src( $hover_image, 'catalog-thumb');
			echo '<img src="'.$hover[0].'" class="product_hover" alt="" />';
		}
		}
	}

	// product hover image in collection
	if ($data == 'collection_hover_image') {
		// hover image
		if (isset($hover_image)) {
		if ((int)$hover_image) {
			$hover = wp_get_attachment_image_src( $hover_image, 'collection-thumb');
			return '<img src="'.$hover[0].'" class="collection_hover_image" alt="" />';
		}
		}
	}

	// show main image with zooming (width=230)
	if ($data == 'main_image') {

		if (get_post_thumbnail_id($id)) {

			// attachments
			$args = array(
				'post_type' => 'attachment',
				'numberposts' => -1,
				'post_parent' => $id,
				'post_mime_type' => 'image',
				'include' => get_post_thumbnail_id(),
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);
			$attachments = get_posts($args);
			foreach($attachments as $attachment) {
				$title = $attachment->post_title;
			}

			// no title
			if (!isset($title)) $title = null;

			$url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');
			if ($url[0]){
				echo '<a href="'.$url[0].'" title="'.$title.'" class="zoom" id="thumb-'.get_post_thumbnail_id($id).'"><img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w='.ocart_get_option('main_image_width').'&amp;h='.ocart_get_option('main_image_height').'&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" alt="" /></a>';
			}
		}

	}

	// show product thumbnails
	if ($data == 'thumbs') {

		if (ocart_has_images()) { echo '<div class="upImage"></div><div class="dnImage"></div>'; }

		// attachments
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_parent' => $id,
			'post_mime_type' => 'image',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		$attachments = get_posts($args);

		// video thumb
		$has_video = get_post_meta($id, 'customtab_video', true);
		$url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');

		if ($attachments) {
			echo '<ul class="thumbs">';
				if (!empty($has_video)) {
				?>
				<li><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/playvideo.php', '', '<?php echo $id; ?>');" title="<?php _e('Watch Product Video','ocart'); ?>" class="tip video" rel="video"><img src="<?php echo get_template_directory_uri(); ?>/thumb.php?src=<?php echo $url[0]; ?>&amp;w=100&amp;h=100&amp;zc=<?php echo $zc; ?>&amp;a=<?php echo $crop; ?>&amp;q=100" alt="" /><span class="video_icon"></span></a></li>
				<?php
				}
			foreach ($attachments as $attachment) {
				$url = wp_get_attachment_image_src( $attachment->ID, 'full');
				echo '<li><a href="#'.$attachment->ID.'" title="'.$attachment->post_title.'" class="tip" rel="thumb-'.$attachment->ID.'"><img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w=100&amp;h=100&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" alt="" /></a></li>';
			}
			echo '</ul>';
		}

	}

	// show product thumbnails
	if ($data == 'thumbs2') {

		if (ocart_has_images()) { echo '<div class="nextImage2"></div><div class="prevImage2"></div>'; }

		// attachments
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_parent' => $id,
			'post_mime_type' => 'image',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		$attachments = get_posts($args);

		// video thumb
		$has_video = get_post_meta($id, 'customtab_video', true);
		$url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');

		if ($attachments) {
			echo '<ul class="thumbs2">';
				if (!empty($has_video)) {
				?>
				<li><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/playvideo.php', '', '<?php echo $id; ?>');" title="<?php _e('Watch Product Video','ocart'); ?>" class="tip video" rel="video"><img src="<?php echo get_template_directory_uri(); ?>/thumb.php?src=<?php echo $url[0]; ?>&amp;w=75&amp;h=75&amp;zc=<?php echo $zc; ?>&amp;a=<?php echo $crop; ?>&amp;q=100" alt="" /><span class="video_icon"></span></a></li>
				<?php
				}
			foreach ($attachments as $attachment) {
				$url = wp_get_attachment_image_src( $attachment->ID, 'full');
				echo '<li><a href="#'.$attachment->ID.'" title="'.$attachment->post_title.'" class="tip" rel="thumb-'.$attachment->ID.'"><img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w=75&amp;h=75&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" alt="" /></a></li>';
			}
			echo '</ul>';
		}

	}

	// show product images
	if ($data == 'images') {

		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_parent' => $id,
			'exclude' => get_post_thumbnail_id(),
			'post_mime_type' => 'image',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				$url = wp_get_attachment_image_src( $attachment->ID, 'full');
				echo '<a href="'.$url[0].'" title="'.$attachment->post_title.'" class="zoom" id="thumb-'.$attachment->ID.'"><img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w='.ocart_get_option('main_image_width').'&amp;h='.ocart_get_option('main_image_height').'&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" alt="" /></a>';
			}
		}

	}

	// show the small thumb in small cart
	if ($data == 'small_thumb') {
		$url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');
		echo '<img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w=46&amp;h=46&amp;zc='.$zc.'&amp;a='.$crop.'&amp;q=100" alt="" />';
	}

	// get price in grid view
	if ($data == 'get_price_in_grid') {
		// find custom prices
		$prices = array();
		$args=array('public' => true,'_builtin' => false);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args,$output,$operator);
		if  ($taxonomies) {
			foreach ($taxonomies as $taxonomy ) {
				if (!in_array($taxonomy, array('product_category', 'brand'))) { // do not include brand or category!
					$terms = wp_get_post_terms($id, $taxonomy, array("fields" => "all"));
					if ($terms && ! is_wp_error( $terms )) {
						foreach($terms as $term) {
							$custom_price_option = get_post_meta( $id, 'product_'.$id.'_'.$term->taxonomy.'_'.$term->term_id, true);
							if (!empty($custom_price_option)) {
								$prices[] = $custom_price_option + $price;
							}
						}
					}
				}
			}
		}
		$prices[] = $price;
		if (count($prices) > 1) {
			return sprintf(__('<ins>from</ins> %s','ocart'), ocart_format_currency(ocart_show_price(min($prices))));
		} else {
			return ocart_format_currency( ocart_show_price($prices[0]) );
		}
	}

	// get plain original price
	if ($data == 'get_plain_original_price') {
		if (isset($reg_price) && $reg_price != '') {
			return ocart_format_currency( ocart_show_price($reg_price) );
		}
	}

}

/************************************************************
display thumbnails across blog
************************************************************/
function ocart_thumb($w, $h) {
	global $post;
	$url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
	if ($url[0]){
		echo '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w='.$w.'&amp;h='.$h.'&amp;q=100" alt="" /></a>';
	}
}

/************************************************************
display thumbnails across blog (image only)
************************************************************/
function ocart_thumb_only($w, $h) {
	global $post;
	$url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
	if ($url[0]){
		echo '<img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w='.$w.'&amp;h='.$h.'&amp;a=t&amp;q=100" alt="" />';
	}
}

/************************************************************
product has more than 1 image validation
************************************************************/
function ocart_has_images() {
	global $post;
		$args = array(
		'post_type' => 'attachment',
		'numberposts' => -1,
		'post_parent' => $post->ID,
		'post_mime_type' => 'image',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	$attachments = get_posts($args);
	if (count($attachments) >= 2) return true; // scroll between 2 images at least
}

/************************************************************
list a product taxonomy
************************************************************/
function ocart_product_taxonomy(){

	global $post, $ocart;
	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies=get_taxonomies($args,$output,$operator);
	if (isset($ocart['product_attr']) && is_array($ocart['product_attr'])) {
		$product_attr = $ocart['product_attr']; // use product options from theme panel
	} else {
		$product_attr = array('color', 'size'); // default fallback
	}

	if  ($taxonomies) {
		$i = 0;
		$taxonomies = $product_attr;
		$taxonomies = apply_filters('ocart_change_product_attributes', $taxonomies);
		foreach ($taxonomies as $taxonomy ) {
			$terms = get_the_terms($post->ID, $taxonomy);
			if ($terms && ! is_wp_error( $terms )) { $i++;
?>

				<?php if (!ocart_get_option('disable_cart')) { ?>
				<div class="product-tax product-<?php echo $taxonomy; ?>">
				<?php } else { ?>
				<div class="product-tax-nocart product-<?php echo $taxonomy; ?>-nocart">
				<?php } ?>
					<h3><?php if (!ocart_get_option('disable_cart')) { printf(__('Choose %s','ocart'), ocart_get_taxonomy_nicename($taxonomy)); } else { printf(__('%s Options','ocart'), ocart_get_taxonomy_nicename($taxonomy)); } ?></h3>

					<?php if (ocart_get_option('attr_select')) { ?>

					<select>
						<option value="0"><?php _e('Please select','ocart'); ?></option>
						<?php ocart_product_terms_select($taxonomy); ?>
					</select>

					<?php } else { ?>

					<ul>
						<?php ocart_product_terms($taxonomy); ?>
					</ul>

					<?php } ?>

				</div>

<?php
			}
		}
	}
	if ($i % 2 == 0 || $i == 1) {
		echo '<div class="clear"></div>';
	}
	if ($i == 0) { // not even one taxonomy
?>

	<div class="product-tax do-not-count">
		<h3><?php printf(__('Availability','ocart')); ?></h3>
		<ul>
			<li><?php _e('This product is available in default stock only.','ocart'); ?></li>
		</ul>
	</div><div class="clear"></div>

<?php
	}
}

/************************************************************
display price for specific option
************************************************************/
function ocart_option_price($meta) {
	global $post, $ocart;
	$diff_price = get_post_meta($post->ID, $meta, true);
	$plain_diff = str_replace('+','',$diff_price);
	$plain_diff = str_replace('-','',$diff_price);
	if (isset($diff_price) && $plain_diff > 0) {
		echo 'title="'.ocart_format_currency( ocart_show_price( $diff_price ), $nosymbol=true ).'" data-change="'.$diff_price.'"';
	}
}

/************************************************************
used in select dropdown show the change
************************************************************/
function ocart_option_price_change($meta) {
	global $post, $ocart;
	$diff_price = get_post_meta($post->ID, $meta, true);
	$plain_diff = str_replace('+','',$diff_price);
	$plain_diff = str_replace('-','',$diff_price);
	if (isset($diff_price) && $plain_diff > 0) {
		return '('.ocart_format_currency( ocart_show_price( $diff_price ) ).')';
	}
}

/************************************************************
change image based on color selection
************************************************************/
function ocart_color_image_id($id) {
	global $post;
	// check if this color has attached image
	$c = get_post_meta($post->ID, 'color_'.$id.'_attachment', true);
	if (isset($c) && (int)$c) {
		echo 'rel="thumb-'.$c.'"';
	}
}

/************************************************************
display nav else where
************************************************************/
function ocart_display_super_nav() {
?>
<?php if (ocart_get_option('menu_style')) { ?>
<div class="clear"></div>

<div id="nav">
	<div class="wrap">

		<ul id="supermenu"><?php wp_nav_menu( array( 'theme_location' => 'nav_menu', 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => false ) ); ?></ul>

	</div>
</div>
<?php } ?>
<?php
}

/************************************************************
list a product taxonomy terms [e.g. size = 1, 2, 3, 10]
************************************************************/
function ocart_product_terms($tax) {
	global $ocart, $post;
	$stock = get_post_meta($post->ID, 'stock', true);
	$terms =  wp_get_post_terms($post->ID, $tax, $args = array('orderby' => 'term_id'));
	if ($terms && ! is_wp_error( $terms )) { // make sure we have options
		$i = 0; foreach($terms as $term) { $i++;
			$term_qty = get_post_meta($post->ID, 'stock_'.$term->term_id, true);
			if ($tax != 'color') {
?>

		<?php if (!ocart_get_option('disable_cart')) { ?>
		<li><a <?php ocart_option_price('product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id) ?> href="" id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>" class="optionprice" data-termID="<?php echo 'stock_'.$term->term_id; ?>" name="qty-<?php if ($term_qty > 0) { echo $term_qty; } else if ($term_qty == 0 && $term_qty != '') { echo 0; } else if ($stock > 0) { echo $stock; } else { echo 999; } ?>"><?php echo $term->name; ?></a></li>
		<?php } else { ?>
		<li><?php echo $term->name; ?></li>
		<?php } ?>

<?php
			} else {

				$rgb = HTMLToRGB($term->name);
				$hsl = RGBToHSL($rgb);
				if($hsl->lightness > 200 || in_array($term->name, array('white','White'))) {
					$bordercolor = '#bbb';
				} else {
					$bordercolor = $term->name;
				}

?>

		<?php if (!ocart_get_option('disable_cart')) { ?>
		<li><a <?php ocart_option_price('product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id) ?> href="" <?php ocart_color_image_id($term->term_id); ?> id="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>" style="background: <?php echo $term->name; ?>;border: 1px solid <?php echo $bordercolor; ?>;" class="optionprice" data-termID="<?php echo 'stock_'.$term->term_id; ?>" name="qty-<?php if ($term_qty > 0) { echo $term_qty; } else if ($term_qty == 0 && $term_qty != '') { echo 0; } else if ($stock > 0) { echo $stock; } else { echo 999; } ?>"></a></li>
		<?php } else { ?>
		<li style="background: <?php echo $term->name; ?>;border: 1px solid <?php echo $bordercolor; ?>;"></li>
		<?php } ?>

<?php

			}
		}
	}
}

/************************************************************
list a product taxonomy terms [e.g. size = 1, 2, 3, 10]
************************************************************/
function ocart_product_terms_select($tax) {
	global $ocart, $post;
	$terms =  wp_get_post_terms($post->ID, $tax, $args = array('orderby' => 'term_id'));
	if ($terms && ! is_wp_error( $terms )) { // make sure we have options
		$i = 0; foreach($terms as $term) { $i++;
?>

		<?php if (!ocart_get_option('disable_cart')) { ?>

		<?php $opt_qty = get_post_meta($post->ID, 'stock_'.$term->term_id, true);
		if ($opt_qty != '' && $opt_qty == 0) { ?>
		<option disabled="disabled"><?php echo $term->name; ?></option>
		<?php } else { ?>
		<option value="<?php echo $term->taxonomy; ?>-<?php echo $term->slug; ?>" <?php ocart_option_price('product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id) ?>><?php echo $term->name; ?> <?php echo ocart_option_price_change('product_'.$post->ID.'_'.$term->taxonomy.'_'.$term->term_id); ?></option>
		<?php } ?>

		<?php } else { ?>

		<option><?php echo $term->name; ?></option>

<?php
			}
		}
	}
}

/************************************************************
get the count of cart items
************************************************************/
function ocart_cart_items_count() {
	// if cookie is stored
	if (isset($_SESSION['ocart_cart_count'])) {
		echo $_SESSION['ocart_cart_count'];
	} else {
		// reset cart
		echo 0;
	}
}

/************************************************************
display the small cart
************************************************************/
function ocart_smallcart() {
	global $ocart;

	echo '<div class="cartarrow"></div>';

	// if cart count is positive, we have items!
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
?>

	<ul class="items">

		<?php
		$cart_array = array_reverse($_SESSION['cart']);
		$i = 0;
		foreach($cart_array as $k => $v) : $i++;
		if ( $i <= 4 && $v['quantity'] > 0 ) : ?>
		<li id="item-<?php echo $v['id']; ?>" rel="<?php echo get_permalink($v['id']); ?>">
			<div class="remove" id="session-<?php echo $v['session_id']; ?>" rel="quantity-<?php echo $v['quantity']; ?>"></div>
			<div class="thumb"><?php ocart_product('small_thumb', $v['id']); ?></div>
			<div class="info">
				<h3><?php echo $v['name']; ?></h3>
				<span><?php echo ocart_format_currency( ocart_show_price($v['price']) ); ?></span>
				<?php if ($v['quantity'] > 1) { ?><span class="qty"><?php printf(__('qty: %s','ocart'), $v['quantity']); ?></span><?php } ?>
			</div>
			<div class="clear"></div>
		</li>
		<?php endif; endforeach; ?>

	</ul>
	<div class="cartbtn"><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/cart.php');"><?php _e('View Cart','ocart'); ?></a></div>

<?php
	} else {
?>

	<div class="text"><?php _e('Your shopping cart is currently empty.','ocart'); ?></div>
	<div class="cartbtn"><a href="<?php echo home_url(); ?>" id="gotostore"><?php _e('Continue Shopping','ocart'); ?></a></div>

<?php
	}
}

/************************************************************
display numbers on the screen in nice way
************************************************************/
function ocart_show_price($price, $xchange=true) {
	if (isset($price) && $price != '') {
		// run money thru exchange rate filter
		if ($xchange == true) {
			if (isset($_SESSION['exchange_rate']) && $_SESSION['exchange_rate'] > 0 && $price > 0) {
				$price = $_SESSION['exchange_rate'] * $price;
			}
		}
		return number_format_i18n($price, 2);
	} else {
		return number_format_i18n(0, 2);
	}
}

/************************************************************
display numbers on the screen in nice way without format
************************************************************/
function ocart_show_price_plain($price) {
	if (isset($price) && $price != '') {
		// run money thru exchange rate filter
		if (isset($_SESSION['exchange_rate']) && $_SESSION['exchange_rate'] > 0 && $price > 0) {
			$price = $_SESSION['exchange_rate'] * $price;
		}
		return floor($price);
	}
}

/************************************************************
get the subtotal of item added to cart
************************************************************/
function ocart_subtotal($session_id, $quantity) {
	$q = $_SESSION['cart'][$session_id]['quantity'];
	$price = get_post_meta($_SESSION['cart'][$session_id]['id'], 'price', true);
	$subtotal = $price * $q;
	return ocart_show_price($subtotal);
}

function ocart_subtotal_remove_comma($session_id, $quantity) {
	$q = $_SESSION['cart'][$session_id]['quantity'];
	$price = get_post_meta($_SESSION['cart'][$session_id]['id'], 'price', true);
	$subtotal = $price * $q;
	return $subtotal;
}

/************************************************************
convert % to fixed money deducted by coupon
************************************************************/
function ocart_real_coupon_value($amount) {
	$total = '';
	if (isset($_SESSION['cart'])) {
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];;
		}
	}
	$pct = ($amount / 100 * $total);
	return $pct;
}

/************************************************************
cart total without number formatting
************************************************************/
function ocart_clean_total($include_tax = true) {
	$total = 0;
	if (isset($_SESSION['cart'])) {
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];
		}
		if (ocart_shipping_fee_noformat() > 0) {
			if (!isset($_SESSION['force_free_shipping'])) {
				$total = $total + ocart_shipping_fee_noformat();
			}
		}
		if (isset($_SESSION['deduct_from_cart'])) {
			$total = $total - $_SESSION['deduct_from_cart'];
		}
		// should we add tax to total?
		if ($include_tax == true && ocart_get_option('enable_tax') && !ocart_get_option('tax_included')) {
			$tax_rate = ocart_get_option('tax_rate');
			$total = $total + (($tax_rate / 100) * $total);
		}
		return $total;
	}
}

/************************************************************
calculate tax (If enabled)
************************************************************/
function ocart_tax() {
	global $ocart;
	if (ocart_get_option('enable_tax')) {
		$tax_rate = ocart_get_option('tax_rate');
		$total = ocart_clean_total($include_tax = false);
		$tax_value = (($tax_rate / 100) * $total);
		return $tax_value;
	} else {
		return 0;
	}
}

/************************************************************
get the total cart payment
************************************************************/
function ocart_total() {
	$total = 0;
	if (isset($_SESSION['cart'])) {
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];
		}
		if (isset($_SESSION['deduct_from_cart'])) {
			$total = $total - $_SESSION['deduct_from_cart'];
		}
		return ocart_show_price($total);
	}
}

/************************************************************
get the total cart only
************************************************************/
function ocart_cart_contents_only() {
	$total = 0;
	if (isset($_SESSION['cart'])) {
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];
		}
		if (isset($_SESSION['deduct_from_cart'])) {
			$total = $total - $_SESSION['deduct_from_cart'];
		}
		return $total;
	}
}

/************************************************************
recalculate amount after coupon
************************************************************/
function ocart_recalculate_subtotal($amount, $amount_type) {
	$total = '';
	if (isset($_SESSION['cart'])) {
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];
		}
	}

	$amounts = 0;
	foreach($_SESSION as $sess => $array) {
		if (strstr($sess, 'coupon_')) {
			if ($array['type'] == 2) {
				$array['amount'] = number_format(ocart_real_coupon_value($array['amount']), 2);
			}
			if ($array['type'] == 1) {
				$array['amount'] = number_format($array['amount'], 2);
			}
			$amounts += $array['amount'];
		}
	}

	$total = $total - $amounts;

	return $total;
}

/************************************************************
get the total cart payment (cart only)
************************************************************/
function ocart_total_cart_only() {
	$total = 0;
	if (isset($_SESSION['cart'])) {
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];;
		}
		return ocart_show_price($total);
	}
}

/************************************************************
get shipping fees based on inputs
************************************************************/
function ocart_get_shipping($fixed, $pct, $weight, $handling, $format=false, $add=false) {
	$shipping = '';

	// fixed shipping rate
	if ($fixed > 0) {
		$shipping += $fixed;
	}

	// percent rate
	if ($pct > 0) {
		$cart = ocart_cart_contents_only();
		$shipping += (10 / 100) * $cart;
	}

	// add superior handling fees (1 item = 2 items = etc)
	if (is_array($handling) && count($handling) > 0) { // make sure we deal with array
		// get total quantity of items in cart
		$quantity = '';
		if (isset($_SESSION['cart'])) {
			$cart_array = array_reverse($_SESSION['cart']);
			foreach($cart_array as $k => $v) {
				$quantity += $v['quantity'];
			}
		}
		// find the relative price for that quantity
		if (isset($handling["$quantity"]) && $handling["$quantity"] > 0) {
			$shipping += $handling["$quantity"];
		} else {
			$shipping += $quantity * $handling[1]; // first item handling * all quantity
		}
	}

	// add weight based prices
	$total_weight = '';
	if (is_array($weight) && count($weight) > 0) { // weight table exists
		if (isset($_SESSION['cart'])) {
			$cart_array = array_reverse($_SESSION['cart']);
			foreach($cart_array as $k => $v) {
				$item_weight = get_post_meta($v['id'], 'weight', true);
				if (!empty($item_weight)) {
					$total_weight += $item_weight * $v['quantity'];
				}
			}

			// parse weight array
			foreach($weight as $value) {
				$arr = explode('|', $value);
				$min = $arr[0];
				$max = $arr[1];
				// if weight is within table
				if ($min <= $total_weight && $total_weight <= $max) {
					$fee = $arr[2];
					$shipping += $fee;
				}
			}

			if (!isset($fee)) {
				$arr = explode('|', $weight[0]);
				$shipping += (($total_weight / $arr[1]) * $arr[2]);
			}

		}
	}

	// if add is filled
	if ($add) {
		$shipping += $add;
	}

	// if no shipping
	if ($shipping == '') {
		$shipping = 0;
	}

	// display price format or not
	if ($format) {
	return ocart_format_currency ( ocart_show_price ($shipping) );
	} else {
	return $shipping;
	}

}

/************************************************************
get tax fees
************************************************************/
function ocart_get_tax($fixed, $pct, $format=false) {
	$tax = '';

	// fixed tax rate
	if ($fixed > 0) {
		$tax += $fixed;
	}

	// percent rate
	if ($pct > 0) {
		$cart = ocart_cart_contents_only();
		$tax += ($pct / 100) * $cart;
	}

	// if no tax
	if ($tax == '') {
		$tax = 0;
	}

	// display price format or not
	if ($format) {
	return ocart_format_currency ( ocart_show_price ($tax) );
	} else {
	return $tax;
	}

}

/************************************************************
get the total cart payment
************************************************************/
function ocart_get_total($format=false, $add=false) {
	$total = 0;
	if (isset($_SESSION['cart'])) {

		// add products in cart
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];
		}

		/**
			pre calculation
		**/
		$total = apply_filters('ocart_pluggable_cart_total_pre', $total);

		// add shipping
		$total += ocart_get_shipping($_SESSION['zonedata']['fixed_shipping'], $_SESSION['zonedata']['pct_shipping'], $_SESSION['zonedata']['weight'], $_SESSION['zonedata']['handling'], false);

		// add tax
		/* add tax if user choose to add tax to totals */
		if (!ocart_get_option('tax_included')) {
		$total += ocart_get_tax($_SESSION['zonedata']['fixed_tax'], $_SESSION['zonedata']['pct_tax'], false);
		}

		// coupons etc
		if (isset($_SESSION['deduct_from_cart'])) {
			$total = $total - $_SESSION['deduct_from_cart'];
		}

		// if add is filled
		if ($add) {
			$total += $add;
		}

		/**
			after calculation
		**/
		$total = apply_filters('ocart_pluggable_cart_total_after', $total);

		// display price format or not
		if ($format) {
		return ocart_format_currency ( ocart_show_price ($total) );
		} else {
		return $total;
		}

	}
}

/************************************************************
get cart total without hooks
************************************************************/
function ocart_get_total_builtin($mode='pre') {
	$total = 0;
	if (isset($_SESSION['cart'])) {

		// add products in cart
		foreach($_SESSION['cart'] as $order) {
			$total += $order['price'] * $order['quantity'];
		}

		if ($mode != 'pre') {

			// add shipping
			$total += ocart_get_shipping($_SESSION['zonedata']['fixed_shipping'], $_SESSION['zonedata']['pct_shipping'], $_SESSION['zonedata']['weight'], $_SESSION['zonedata']['handling'], false);

			/* add tax if user choose to add tax to totals */
			if (!ocart_get_option('tax_included')) {
			$total += ocart_get_tax($_SESSION['zonedata']['fixed_tax'], $_SESSION['zonedata']['pct_tax'], false);
			}

		}

		// coupons etc
		if (isset($_SESSION['deduct_from_cart'])) {
			$total = $total - $_SESSION['deduct_from_cart'];
		}

		// display price format or not
		return $total;

	}
}

/************************************************************
search array case in-sensitive
************************************************************/
function ocart_array_search($str,$array){
    foreach($array as $key => $value) {
		$arr = explode('|', $value);
		$min = $arr[0];
		$max = $arr[1];
		// if weight is within table
        if ($min <= $str && $str <= $max) {
			$fee = $arr[2];
			return $fee;
		}
    }
	if (!isset($fee)) {
		$arr = explode('|', $array[0]);
		return (($str / $arr[1]) * $arr[2]);
	}
	return false;
}

/************************************************************
get the shipping fee in cart summary
************************************************************/
function ocart_shipping_fee() {
	global $ocart;
	$rate = 0;
	if (isset($_SESSION['ocart_shipping_fee']) && !isset($_SESSION['force_free_shipping'])) {
		$rate += $_SESSION['ocart_shipping_fee'];
	} elseif (!isset($_SESSION['ocart_shipping_fee']) && isset($ocart['courier1_fee']) && !isset($_SESSION['force_free_shipping'])) { // make default courier fee
		$rate += $ocart['courier1_fee'];
	}

	// calculate weight
	$total_weight = 0;
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			$item_weight = get_post_meta($v['id'], 'weight', true);
			if (!empty($item_weight)) {
				$total_weight += $item_weight * $v['quantity'];
			}
		}
	}

	// get pricing based on total weight
	if ($total_weight > 0) {
		$weight = explode(PHP_EOL, ocart_get_option('weight_table'));
		$rate += ocart_array_search($total_weight, $weight);
	}

	// calculate handling fees
	if (isset($ocart['cost_per_item'])) {
	$universal_cost_per_item = $ocart['cost_per_item'];
	} else {
	$universal_cost_per_item = 0;
	}
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			// quantity
			$quantity = $v['quantity'];
			$cost_per = get_post_meta($v['id'], 'cost_per_'.$quantity, true);
			$cost_per_item = get_post_meta($v['id'], 'cost_per_1', true);
			if (!empty($cost_per)) {
				$rate += $cost_per;
			} elseif (!empty($cost_per_item)) { // try to get cost per 1 item
				$rate += ($cost_per_item * $quantity);
			} elseif (!empty($universal_cost_per_item)) { // get universal cost per item
				$rate += ($universal_cost_per_item * $quantity);
			} else {
				$rate += 0; // add nothing
			}
		}
	}

	// return rate
	if ($rate == '') {
		return '0.00';
	} else {
		return ocart_show_price($rate);
	}

}

/************************************************************
get the shipping fee in cart summary (no formatting)
************************************************************/
function ocart_shipping_fee_noformat() {
	global $ocart;
	$rate = 0;
	if (isset($_SESSION['ocart_shipping_fee']) && !isset($_SESSION['force_free_shipping'])) {
		$rate += $_SESSION['ocart_shipping_fee'];
	} elseif (!isset($_SESSION['ocart_shipping_fee']) && isset($ocart['courier1_fee']) && !isset($_SESSION['force_free_shipping'])) { // make default courier fee
		$rate += $ocart['courier1_fee'];
	}

	// calculate weight
	$total_weight = 0;
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			$item_weight = get_post_meta($v['id'], 'weight', true);
			if (!empty($item_weight)) {
				$total_weight += $item_weight * $v['quantity'];
			}
		}
	}

	// get pricing based on total weight
	if ($total_weight > 0) {
		$weight = explode(PHP_EOL, ocart_get_option('weight_table'));
		$rate += ocart_array_search($total_weight, $weight);
	}

	if (isset($ocart['cost_per_item'])) {
	$universal_cost_per_item = $ocart['cost_per_item'];
	} else {
	$universal_cost_per_item = 0;
	}
	// check cart items for rates
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			// quantity
			$quantity = $v['quantity'];
			$cost_per = get_post_meta($v['id'], 'cost_per_'.$quantity, true);
			$cost_per_item = get_post_meta($v['id'], 'cost_per_1', true);
			if (!empty($cost_per)) {
				$rate += $cost_per;
			} elseif (!empty($cost_per_item)) { // try to get cost per 1 item
				$rate += ($cost_per_item * $quantity);
			} elseif (!empty($universal_cost_per_item)) { // get universal cost per item
				$rate += ($universal_cost_per_item * $quantity);
			} else {
				$rate += 0; // add nothing
			}
		}
	}
	if ($rate == '') {
		return '0.00';
	} else {
		return $rate;
	}
}

/************************************************************
get the shipping fee
************************************************************/
function ocart_shipping_rate($f) {
	global $ocart;
	$rate = 0;
	$rate += $f;

	// calculate weight
	$total_weight = 0;
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			$item_weight = get_post_meta($v['id'], 'weight', true);
			if (!empty($item_weight)) {
				$total_weight += $item_weight * $v['quantity'];
			}
		}
	}

	// get pricing based on total weight
	if ($total_weight > 0) {
		$weight = explode(PHP_EOL, ocart_get_option('weight_table'));
		$rate += ocart_array_search($total_weight, $weight);
	}

	if (isset($ocart['cost_per_item'])) {
	$universal_cost_per_item = $ocart['cost_per_item'];
	} else {
	$universal_cost_per_item = 0;
	}
	// check cart items for rates
	if (isset($_SESSION['ocart_cart_count'])) {
		$count = $_SESSION['ocart_cart_count'];
	} else {
		$count = null;
	}
	if ($count > 0 && isset($_SESSION['cart'])) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			// quantity
			$quantity = $v['quantity'];
			$cost_per = get_post_meta($v['id'], 'cost_per_'.$quantity, true);
			$cost_per_item = get_post_meta($v['id'], 'cost_per_1', true);
			if (!empty($cost_per)) {
				$rate += $cost_per;
			} elseif (!empty($cost_per_item)) { // try to get cost per 1 item
				$rate += ($cost_per_item * $quantity);
			} elseif (!empty($universal_cost_per_item)) { // get universal cost per item
				$rate += ($universal_cost_per_item * $quantity);
			} else {
				$rate += 0; // add nothing
			}
		}
	}
	echo ocart_format_currency( ocart_show_price($rate) );
}

/************************************************************
callback function for comment listing
************************************************************/
function ocart_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>

		<div class="comment-author vcard">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'], $d = get_template_directory_uri() . '/img/assets/default-user.png' ); ?>
		</div>

		<div class="comment-content">

		<div class="commenter"><?php echo get_comment_author_link(); ?></div>

		<div class="comment-meta commentmetadata">
			<?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?>
			<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			<?php edit_comment_link(__('Edit','ocart'),'',''); ?>
		</div>

		<?php comment_text() ?>

		<?php if ($comment->comment_approved == '0') : ?>
			<p><em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.','ocart') ?></em></p>
		<?php endif; ?>

		</div><div class="clear"></div>

		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
<?php
        }

/************************************************************
insert zone settings (or default zone)
************************************************************/
function ocart_insert_zone_setting($setting, $postfield) {
	$zones = get_option('occommerce_zones');
	if (!empty($postfield)) {
		return $postfield;
	}
	//return $zones[0]['pricing']["$setting"];
}

/************************************************************
insert zone settings (or default zone)
************************************************************/
function ocart_insert_zone_setting_filters($setting, $postfield) {
	$zones = get_option('occommerce_zones');
	if (!empty($postfield)) {
		return explode(',', $postfield);
	} else { return array(); }
}

/************************************************************
convert weight array
************************************************************/
function ocart_convert_weight_array($array) {
	if (count($array) > 0) {
		return implode(PHP_EOL, $array);
	}
}

/************************************************************
convert handling array
************************************************************/
function ocart_convert_handling($array) {
	if (count($array) > 0) {
		foreach($array as $k => $v) {
			return $k.'|'.$v.PHP_EOL;
		}
	}
}

/************************************************************
get lowest item price
************************************************************/
function ocart_min_price() {
	global $wpdb;
	$min = "SELECT min(cast(meta_value as DECIMAL)) FROM wp_postmeta WHERE meta_key='price'";
	return $wpdb->get_var($min);
}

/************************************************************
get highest item price
************************************************************/
function ocart_max_price() {
	$args = array( 'post_type' => 'product', 'numberposts' => -1, 'meta_key' => 'price', 'orderby' => 'meta_value_num', 'order' => 'DESC' );
	$posts = get_posts($args);
	return (int)get_post_meta($posts[0]->ID, 'price', true);
}

/************************************************************
get a taxonomy nicename
************************************************************/
function ocart_get_taxonomy_nicename($arg) {
	$the_tax = get_taxonomy( $arg );
	return $the_tax->labels->singular_name;
}

/************************************************************
get a taxonomy nicename
************************************************************/
function ocart_taxonomy_plural_name($arg) {
	$the_tax = get_taxonomy( $arg );
	return $the_tax->labels->menu_name;
}

/************************************************************
get a taxonomy title "By ..."
************************************************************/
function ocart_taxonomy_sort_title($arg) {
	$the_tax = get_taxonomy( $arg );
	return printf(__('By %s','ocart'), $the_tax->labels->singular_name);
}

/************************************************************
product 'in cart' show terms/options
************************************************************/
function ocart_incart_product_options($terms) {
	$parts = explode(":", $terms);
	foreach($parts as $firstlevel){
		if (!empty($firstlevel)){
			$terms = explode('-', $firstlevel, 2);
			$tax = $terms[0];
			$term = $terms[1];
			$uiterm = get_term_by('slug', $term, $tax);
			if ($tax == 'color') {
				$rgb = HTMLToRGB($uiterm->name);
				$hsl = RGBToHSL($rgb);
				if($hsl->lightness > 200 || in_array($uiterm->name, array('white','White'))) {
					$bordercolor = '#ddd';
				} else {
					$bordercolor = $uiterm->name;
				}
?>

	<span class="t-option"><?php echo ocart_get_taxonomy_nicename($tax); ?>:<span style="background: <?php echo $uiterm->name; ?>;border: 1px solid <?php echo $bordercolor; ?>;padding: 0 20px;margin: 0 0 0 10px;"></span></span>

<?php } else { ?>

	<span class="t-option"><?php echo ocart_get_taxonomy_nicename($tax); ?>:<span style="margin: 0 0 0 10px;"><?php echo $uiterm->name; ?></span></span>

<?php
			}
		}
	}
}

/************************************************************
product 'in cart' summary terms
************************************************************/
function ocart_incart_product_terms($terms) {
	$parts = explode(":", $terms);
	foreach($parts as $firstlevel){
		if (!empty($firstlevel)){
			$terms = explode('-', $firstlevel, 2);
			$tax = $terms[0];
			$term = $terms[1];
			$uiterm = get_term_by('slug', $term, $tax);
			if ($tax == 'color') {
				$rgb = HTMLToRGB($uiterm->name);
				$hsl = RGBToHSL($rgb);
				if($hsl->lightness > 200 || in_array($uiterm->name, array('white','White'))) {
					$bordercolor = '#ddd';
				} else {
					$bordercolor = $uiterm->name;
				}
?>

	<span><?php echo ocart_get_taxonomy_nicename($tax); ?>:<span style="background: <?php echo $uiterm->name; ?>;border: 1px solid <?php echo $bordercolor; ?>;padding: 0 20px;"></span></span>

<?php } else { ?>

	<span><?php echo ocart_get_taxonomy_nicename($tax); ?>:<span><?php echo $uiterm->name; ?></span></span>

<?php
			}
		}
	}
}

/************************************************************
get product terms in return format
************************************************************/
function ocart_get_incart_product_terms($terms) {
	$parts = explode(":", $terms);
	$res = '';
	foreach($parts as $firstlevel){
		if (!empty($firstlevel)){
			$terms = explode('-', $firstlevel, 2);
			$tax = $terms[0];
			$term = $terms[1];
			$uiterm = get_term_by('slug', $term, $tax);
			if ($tax == 'color') {
				$rgb = HTMLToRGB($uiterm->name);
				$hsl = RGBToHSL($rgb);
				if($hsl->lightness > 200 || in_array($uiterm->name, array('white','White'))) {
					$bordercolor = '#ddd';
				} else {
					$bordercolor = $uiterm->name;
				}

				$res .= '<span style="display:block;color:#999;">'.ocart_get_taxonomy_nicename($tax).':<span style="margin: 0 0 0 5px;padding: 0 20px;background: '.$uiterm->name.';border: 1px solid '.$bordercolor.';"></span></span>';

			} else {

				$res .= '<span style="display:block;color:#999;">'.ocart_get_taxonomy_nicename($tax).':<span style="margin: 0 0 0 5px;">'.$uiterm->name.'</span></span>';

			}
		}
	}
	return $res;
}

/************************************************************
calculate tax/shipping form in cart
************************************************************/
function ocart_calculation_form_pre_cart() {
	if (ocart_get_option('enable_calc')) {
?>

		<div class="loc">
			<?php _e('Enter your region to get estimated shipping and tax fees.','ocart'); ?>
			<form class="loc_fields" action="/">
				<fieldset>
					<p>
						<select name="pre_country" id="pre_country">
							<option value="0"><?php _e('Choose a country','ocart'); ?></option>
							<?php
							$countries = get_option('occommerce_allowed_shipping_destinations');
							foreach($countries as $countrycode => $country) {
							?>
								<option value="<?php echo $country; ?>"><?php echo $country; ?></option>
							<?php } ?>
						</select><input type="submit" value="<?php _e('Calculate','ocart'); ?>" />
					</p>
					<p>
						<input type="text" name="pre_region" id="pre_region" placeholder="<?php _e('Enter state or town','ocart'); ?>" /><span><?php _e('or','ocart'); ?></span><input type="text" name="pre_zip" id="pre_zip" placeholder="<?php _e('ZIP or Postal Code','ocart'); ?>" />
					</p>
				</fieldset>
			</form>
		</div>

<?php
	}
}

/************************************************************
loop thru filters/sorting options
************************************************************/
function ocart_filters() {
	global $ocart;

	// filters/browser attributes
	if (isset($ocart['browser_attr']) && is_array($ocart['browser_attr'])) {
		$filters = $ocart['browser_attr'];
	} else {
		$filters = array('product_category', 'brand', 'color', 'size'); // default fallback
	}

	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies=get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		$taxonomies = $filters;
		foreach ($taxonomies as $taxonomy ) {
?>
			<div class="tax">
				<label><?php ocart_taxonomy_sort_title($taxonomy) ?></label>
				<ul>
					<li class="tax-parent-li"><a href="" class="tax-parent"><?php printf(__('Select %s','ocart'), ocart_get_taxonomy_nicename($taxonomy)); ?></a>
						<ul>
							<li class="default-0"><a href="" rel="default_<?php echo $taxonomy; ?>" class="current"><?php _e('All','ocart'); ?></a></li>
							<?php
							if ($taxonomy == 'color') {
								$terms = get_terms( $taxonomy, 'orderby=name&hide_empty='.$ocart['emptyterms']);
								if ($terms && ! is_wp_error( $terms )) {
									foreach($terms as $term) {
							?>
								<?php
									$rgb = HTMLToRGB($term->name);
									$hsl = RGBToHSL($rgb);
									if($hsl->lightness > 200 || in_array($term->name, array('white','White'))) {
										$bordercolor = '#aaa';
									} else {
										$bordercolor = $term->name;
									}
								?>
								<li class="cat-item cat-item-<?php echo $term->term_id; ?>"><a href="<?php echo get_term_link($term->slug, $taxonomy); ?>" class="colorbox"><span class="colorspan" style="background: <?php echo $term->name; ?>;border: 1px solid <?php echo $bordercolor; ?>;"></span>
								<?php if ($ocart['showcount'] == 1) { ?>
								<span class="colorcount">(<?php echo $term->count; ?>)</span>
								<?php } ?></a></li>
							<?php  }
								}
							} else {
								wp_list_categories('taxonomy='.$taxonomy.'&show_count='.$ocart['showcount'].'&hide_empty='.$ocart['emptyterms'].'&title_li=');
							} ?>
						</ul>
					</li>
				</ul>
			</div>
<?php
		}
	}
?>

			<div class="tax">
				<label><?php _e('Stock Condition','ocart'); ?></label>
				<ul>
					<li class="tax-parent-li"><a href="" class="tax-parent"><?php _e('Select Option','ocart'); ?></a>
						<ul rel="custom_fields">
							<li><a href="" id="stock_all" rel="stock_all" class="current"><?php _e('All','ocart'); ?></a></li>
							<li><a href="" id="instock"><?php _e('In Stock','ocart'); ?></a></li>
							<li><a href="" id="new"><?php _e('New Items','ocart'); ?></a></li>
							<li><a href="" id="sale"><?php _e('On Sale','ocart'); ?></a></li>
							<li><a href="" id="sold"><?php _e('Out of Stock','ocart'); ?></a></li>
						</ul>
					</li>
				</ul>
			</div>

			<div class="tax">
				<label><?php _e('Sort by','ocart'); ?></label>
				<ul>
					<li class="tax-parent-li"><a href="" class="tax-parent"><?php _e('Select Option','ocart'); ?></a>
						<ul rel="sort_by">
							<li><a href="" id="sort_all" rel="sort_all" class="current"><?php _e('Latest Added','ocart'); ?></a></li>
							<li><a href="" id="highest_first"><?php _e('Highest Price First','ocart'); ?></a></li>
							<li><a href="" id="lowest_first"><?php _e('Lowest Price First','ocart'); ?></a></li>
							<li><a href="" id="most_popular"><?php _e('Most Popular Items','ocart'); ?></a></li>
						</ul>
					</li>
				</ul>
			</div>

			<div class="tax">
				<label><?php _e('Price Range','ocart'); ?></label>
				<input type="text" name="min_price" id="min_price" value="<?php echo ocart_format_currency(0); ?>" />
				<input type="text" name="max_price" id="max_price" value="<?php echo ocart_format_currency( ocart_max_price() ); ?>" />
			</div>

			<div class="tax">
			</div>

			<div class="tax">
				<a href="#clear" class="btnstyle4" id="resetfilters"><?php _e('Clear All Filters','ocart'); ?></a>
			</div>

<?php

}

/************************************************************
is it on sale
************************************************************/
function ocart_has_discount() {
	global $post;
	$price = get_post_meta($post->ID, 'price', true);
	$reg_price = get_post_meta($post->ID, 'regular_price', true);
	if (isset($price)) {
	$sale_price = $price;
	}
	if (isset($reg_price)) {
	$original_price = $reg_price;
	}
	if (isset($original_price) && isset($sale_price) && $original_price > $sale_price) {
		return true;
	}
}

/************************************************************
discount value
************************************************************/
function ocart_has_discount_value() {
	global $post;
	$sale_price = get_post_meta($post->ID, 'price', true);
	$original_price = get_post_meta($post->ID, 'regular_price', true);
	$discount = $original_price - $sale_price;
	$pct = $discount / $original_price * 100;
	return (int)$pct.'&#37;';
}

/************************************************************
return discount value of product
************************************************************/
function ocart_product_discount() {
	global $post;
	$price = get_post_meta($post->ID, 'price', true);
	$reg_price = get_post_meta($post->ID, 'regular_price', true);
	if (isset($price)) {
	$sale_price = $price;
	}
	if (isset($reg_price)) {
	$original_price = $reg_price;
	}
	if (isset($original_price) && isset($sale_price) && $original_price > $sale_price) {
		$discount = $original_price - $sale_price;
		$pct = $discount / $original_price * 100;
		printf(__('Save %s&#37;','ocart'), (int)$pct);
	}
}

/************************************************************
return discount value of product
************************************************************/
function ocart_get_product_discount() {
	global $post;
	$price = get_post_meta($post->ID, 'price', true);
	$reg_price = get_post_meta($post->ID, 'regular_price', true);
	if (isset($price)) {
	$sale_price = $price;
	}
	if (isset($reg_price)) {
	$original_price = $reg_price;
	}
	if (isset($original_price) && isset($sale_price) && $original_price > $sale_price) {
		$discount = $original_price - $sale_price;
		$pct = $discount / $original_price * 100;
		return sprintf(__('Save %s&#37;','ocart'), (int)$pct);
	}
}

/************************************************************
sort array by another array
************************************************************/
function sortArrayByArray($array,$orderArray) {
    $ordered = array();
	if (is_array($orderArray)) {
		foreach($orderArray as $key) {
			if(array_key_exists($key,$array)) {
				$ordered[] = $array[$key];
				unset($array[$key]);
			}
		}
		return $ordered + $array;
	} else {
		return $array;
	}
}

/************************************************************
return true if a product is not sold out!
************************************************************/
function ocart_product_in_stock() {
	global $post;
	$status = get_post_meta($post->ID, 'status', true);
	$leftstock = (int)get_post_meta($post->ID, 'stock', true);
	if ($status == 'sold') {
		return false;
	} elseif ($leftstock && $leftstock == 0) {
		return false;
	} else {
		return true;
	}
}

/************************************************************
check if a product has related products
************************************************************/
function ocart_has_similar_products() {
	global $post;
	$baseProd = $post->ID;
	if (get_post_meta($post->ID, 'similar_products', true) && get_post_meta($post->ID, 'similar_products', true) != '') { // set via products page
		return true;
	} else {
	$similar = ocart_related_products_by_taxonomy($post->ID,ocart_get_option('related_tax'));
		$object = ocart_related_products_by_taxonomy($post->ID,ocart_get_option('related_tax'));
		if (count($object->posts) > 1) {
			return true;
		}
	}
}

/************************************************************
get related posts based on admin selection
************************************************************/
function ocart_related_products($post_id,$args=array()) {
  $query = new WP_Query();
    $post_ids = get_post_meta($post_id, 'similar_products', true);
    $post = get_post($post_id);
    $args = wp_parse_args($args,array(
      'post_type' => $post->post_type, // The assumes the post types match
      'post__in' => $post_ids
    ));
    $query = new WP_Query($args);
  return $query;
}

/************************************************************
get related posts based on taxonomy
************************************************************/
function ocart_related_products_by_taxonomy($post_id,$taxonomy,$args=array()) {
  $query = new WP_Query();
  $terms = wp_get_object_terms($post_id,$taxonomy);
  if (count($terms)) {
    // Assumes only one term for per post in this taxonomy
    $post_ids = get_objects_in_term($terms[0]->term_id,$taxonomy);
    $post = get_post($post_id);
    $args = wp_parse_args($args,array(
      'post_type' => $post->post_type, // The assumes the post types match
      'post__in' => $post_ids,
      'taxonomy' => $taxonomy,
      'term' => $terms[0]->slug,
    ));
    $query = new WP_Query($args);
  }
  return $query;
}

/************************************************************
detect color brightness
************************************************************/
function HTMLToRGB($htmlCode)
  {
	if ($htmlCode[0] == '#') {
    if($htmlCode[0] == '#')
      $htmlCode = substr($htmlCode, 1);

    if (strlen($htmlCode) == 3)
    {
      $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
    }

    $r = hexdec($htmlCode[0] . $htmlCode[1]);
    $g = hexdec($htmlCode[2] . $htmlCode[3]);
    $b = hexdec($htmlCode[4] . $htmlCode[5]);

    return $b + ($g << 0x8) + ($r << 0x10);
  }
 }

function RGBToHSL($RGB) {
    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if($maxC == $minC)
    {
      $s = 0;
      $h = 0;
    }
    else
    {
      if($l < .5)
      {
        $s = ($maxC - $minC) / ($maxC + $minC);
      }
      else
      {
        $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
      }
      if($r == $maxC)
        $h = ($g - $b) / ($maxC - $minC);
      if($g == $maxC)
        $h = 2.0 + ($b - $r) / ($maxC - $minC);
      if($b == $maxC)
        $h = 4.0 + ($r - $g) / ($maxC - $minC);

      $h = $h / 6.0;
    }

    $h = (int)round(255.0 * $h);
    $s = (int)round(255.0 * $s);
    $l = (int)round(255.0 * $l);

    return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
  }

/************************************************************
loop thru shipping options
************************************************************/
function ocart_shipping_options($loop, $id) {
	global $ocart, $post;
	$courier = get_post_meta($post->ID, 'courier', true);

	if ($loop == 'select') {
?>
        <select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
			<?php for ($i = 1; $i <= $ocart['dashboard_shipping_couriers']; $i++) { if ($ocart['courier'.$i.'_label']) { ?>
			<option value="<?php echo $ocart['courier'.$i.'_label']; ?>" <?php selected( $ocart['courier'.$i.'_label'] , $courier); ?>><?php echo $ocart['courier'.$i.'_label']; ?></option>
			<?php } } ?>
        </select>
<?php
	}

}

/************************************************************
payable amount
************************************************************/
function ocart_payable($post_id, $force_currency = false) {

	global $ocart;

	$payable = get_post_meta($post_id, 'payment_gross_total', true);

	// use specific currency to pay
	if ( $force_currency == true ) {
		$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s='.$ocart['currencycode'].$force_currency.'=X';
		$handle = fopen($url, 'r');
		if ($handle) {
			$result = fgetcsv($handle);
			fclose($handle);
		}
		$payable = $result[0] * $payable;
		$payable = number_format((float)$payable, 2, '.', '');

	} else {

		// multiply exchange rate * payable amount
		if (isset($_SESSION['exchange_rate']) && $_SESSION['exchange_rate'] > 0 && $payable > 0) {
			$payable = $_SESSION['exchange_rate'] * $payable;
			$payable = number_format((float)$payable, 2, '.', '');
		} else {
			$payable = number_format((float)$payable, 2, '.', '');
		}

	}

	return $payable;

}

/************************************************************
print update check notice
************************************************************/
function ocart_print_update_notice() {
	if (get_option('occommerce_updates') > 0) {
	?>

	<span class="update_notice"><?php printf(__('A newer version is available. Please <a href="%s">update</a> your theme now.','ocart'), admin_url().'/update-core.php'); ?></span>
	<div class="clear"></div>

	<?php
	}
}

/************************************************************
*
* 	default email templates (first-run)
*
************************************************************/

function ocart_default_template_html_order_received() { // 1
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order has been Received</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Thank you for your order at <a href="{store_url}" style="color:#ea6ea0">{store_name}</a>. We are now reviewing and processing your order. Below is a copy of your invoice for this order. You can track the status of your order online through <a href="{customer_orders_link}" style="color:#ea6ea0">My Orders</a> page. You will be notified by email when this order is processed or if there is an update to the order status.</p>

<p>Please keep a record of your order ID.</p>

<h2 style="padding-top: 10px">Order #{order_id}</h2>

<table width="100%" style="border:style 5; border-collapse:collapse;font-size:13px;">
<tr>
<td style="border:solid 1px #ddd;padding: 10px;"><b>Product</b></td>
<td style="border:solid 1px #ddd;padding: 10px;"><b>Quantity</b></td>
<td style="border:solid 1px #ddd;padding: 10px;"><b>Subtotal</b></td>
</tr>

{cart_items}

<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Cart Subtotal:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;">{cart_subtotal}</td>
</tr>

{coupon_codes}

<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Tax:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;">{tax_fee}</td>
</tr>
<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Shipping:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;">{shipping_fee}</td>
</tr>
<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Order Total:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;"><b>{cart_total}</b></td>
</tr>
</table>

<h2 style="padding-top: 10px">Customer Details</h2>

<p>
<b>Email:</b> <a href="mailto:{customer_email}" style="color:#ea6ea0">{customer_email}</a><br />
<b>Telephone:</b> <a href="tel:{customer_phone}" style="color:#ea6ea0">{customer_phone}</a>
</p>

<div style="float: left;width: 40%; padding: 0 10% 0 0;">
<h3>Bill To:</h3>
<span style="color:#888;display: block;">{customer_name}</span>
<span style="color:#888;display: block;">{customer_address}</span>
<span style="color:#888;display: block;">{customer_city}</span>
<span style="color:#888;display: block;">{customer_state}, {customer_postcode}</span>
<span style="color:#888;display: block;">{customer_country}</span>
</div>

<div style="float: left;width: 40%;padding: 0 0 0 10%;">
<h3>Ship To:</h3>
<span style="color:#888;display: block;">{shipping_name}</span>
<span style="color:#888;display: block;">{shipping_address}</span>
<span style="color:#888;display: block;">{shipping_city}</span>
<span style="color:#888;display: block;">{shipping_state}, {shipping_postcode}</span>
<span style="color:#888;display: block;">{shipping_country}</span>
</div>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_awaiting_payment() { // 2
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order is Awaiting Payment</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Your order #<b>{order_id}</b> is <b>Awaiting Payment</b>. Your order will not be processed until you complete the payment of this order.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_pending() { // 3
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order is Pending</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Your order #<b>{order_id}</b> is <b>Pending</b>. Your payment for this order has been received but we are manually reviewing this order. Your order will be processed once it is approved by an admin.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_processing() { // 4
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order is being Processed</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Congratulations! Your order #<b>{order_id}</b> has been approved. We\'re now processing your order! You will receive a notification when this order has been shipped.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_shipped() { // 5
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order has been Shipped</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Congratulations! Your order #<b>{order_id}</b> has been shipped!</p>

<p>We\'ll send you a seperate e-mail with your shipment tracking number as soon as it is available. You can also track your shipment directly from our store by viewing your Order History.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_cancelled() { // 6
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order has been Cancelled</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Your order #<b>{order_id}</b> has been cancelled.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_declined() { // 7
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order has been Declined</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Your order #<b>{order_id}</b> has been declined.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_tracking() { // 8
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">Your Order is on it\'s Way</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

<p>Congratulations! Your order #<b>{order_id}</b> is on its way!</p>

<p>Your order was shipped by {shipping_courier} and your shipment tracking number is {tracking_information}.</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_order_comments() { // 9
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">About Your Order</h1>

<div style="margin: 20px;line-height: 20px;">

<p>Dear {customer_name},</p>

{order_comments}

<p>To view your current order history and check your order status:<br />
<a href="{customer_orders_link}" style="color:#ea6ea0">{customer_orders_link}</a>
</p>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

function ocart_default_template_html_admin_order_received() { // 10
return '<div style="text-align: left;width: 600px;margin: 50px auto;font-family: Arial;color:#333;font-size:13px;">

<h1 style="background: #ea6ea0;padding: 30px 20px;color:#fff;font-size: 20px;font-weight: normal;">New Order has been Received</h1>

<div style="margin: 20px;line-height: 20px;">

<p>The system received a new order. You can login to your <a href="{admin_orders_dashboard}" style="color:#ea6ea0">dashboard</a> to view complete order details, change order status, or email customer about this order.</p>

<h2 style="padding-top: 10px">Order #{order_id}</h2>

<table width="100%" style="border:style 5; border-collapse:collapse;font-size:13px;">
<tr>
<td style="border:solid 1px #ddd;padding: 10px;"><b>Product</b></td>
<td style="border:solid 1px #ddd;padding: 10px;"><b>Quantity</b></td>
<td style="border:solid 1px #ddd;padding: 10px;"><b>Subtotal</b></td>
</tr>

{cart_items}

<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Cart Subtotal:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;">{cart_subtotal}</td>
</tr>

{coupon_codes}

<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Tax:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;">{tax_fee}</td>
</tr>
<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Shipping:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;">{shipping_fee}</td>
</tr>
<tr>
<td style="border:solid 1px #ddd;padding: 10px;" colspan="2"><b>Order Total:</b></td>
<td style="border:solid 1px #ddd;padding: 10px;"><b>{cart_total}</b></td>
</tr>
</table>

<h2 style="padding-top: 10px">Customer Details</h2>

<p>
<b>Email:</b> <a href="mailto:{customer_email}" style="color:#ea6ea0">{customer_email}</a><br />
<b>Telephone:</b> <a href="tel:{customer_phone}" style="color:#ea6ea0">{customer_phone}</a>
</p>

<div style="float: left;width: 40%; padding: 0 10% 0 0;">
<h3>Bill To:</h3>
<span style="color:#888;display: block;">{customer_name}</span>
<span style="color:#888;display: block;">{customer_address}</span>
<span style="color:#888;display: block;">{customer_city}</span>
<span style="color:#888;display: block;">{customer_state}, {customer_postcode}</span>
<span style="color:#888;display: block;">{customer_country}</span>
</div>

<div style="float: left;width: 40%;padding: 0 0 0 10%;">
<h3>Ship To:</h3>
<span style="color:#888;display: block;">{shipping_name}</span>
<span style="color:#888;display: block;">{shipping_address}</span>
<span style="color:#888;display: block;">{shipping_city}</span>
<span style="color:#888;display: block;">{shipping_state}, {shipping_postcode}</span>
<span style="color:#888;display: block;">{shipping_country}</span>
</div>

<p style="clear:both;text-align: center;color: #aaa;padding: 40px 0 0 0;">{store_name} - Powered by OneCart</p>

</div>

</div>';
}

/************************************************************
show default product image placeholder
************************************************************/
function ocart_default_image($type) {
	if ($type == 'sort') {
		echo '<img src="'.get_template_directory_uri().'/thumb.php?src='.get_stylesheet_directory_uri().'/img/no-image.png&amp;w=50&amp;h=50&amp;zc=0&amp;q=100" alt="" />';
	}
}

/************************************************************
show the showlist button
************************************************************/
function ocart_wishlist_button() {
	if (ocart_get_option('wishlist')) {
	global $post, $current_user;
	$id = $current_user->ID;
	$wishlist = get_user_meta($id, 'wishlist', true);
	if (is_array($wishlist) && in_array($post->ID, $wishlist)) {
		?>
		<a href="#AddtoWishlist" data-ID="<?php the_ID(); ?>" class="add_to_wishlist"><?php _e('Already in Wishlist','ocart'); ?></a>
		<?php } else { ?>
		<a href="#AddtoWishlist" data-ID="<?php the_ID(); ?>" class="add_to_wishlist"><?php _e('Add to Wishlist','ocart'); ?></a>
	<?php }
	}
}

/************************************************************
count of user wishlist items
************************************************************/
function ocart_wishlist_count() {
	if (is_user_logged_in()){
		global $current_user;
		$id = $current_user->ID;
		$wishlist = get_user_meta($id, 'wishlist', true);
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
		$count = count($wishlist);
		if ($wishlist && $count > 0) {
			if ($count == 1) {
				return __('1 item','ocart');
			} elseif ($count != 1) {
				return sprintf(__('%s items','ocart'), $count);
			}
		}
	}
	return __('0 items','ocart');
}

/************************************************************
display sortable products
************************************************************/
function sneek_product_order_page() {
?>
	<div class="wrap">
		<h2><?php _e('Sort Products','ocart'); ?></h2>
		<p><?php _e('Simply drag the product up or down and they will be saved in that order.','ocart'); ?></p>
	<?php $products = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) ); ?>
	<?php if( $products->have_posts() ) : ?>

		<table class="wp-list-table widefat fixed posts" id="sortable-table">
			<thead>
				<tr>
					<th class="column-order"><?php _e('Order','ocart'); ?></th>
					<th class="column-thumbnail"><?php _e('Thumbnail','ocart'); ?></th>
					<th class="column-title"><?php _e('Title','ocart'); ?></th>
				</tr>
			</thead>
			<tbody data-post-type="product">
			<?php while( $products->have_posts() ) : $products->the_post(); ?>
				<tr id="post-<?php the_ID(); ?>">
					<td class="column-order"><img src="<?php echo get_template_directory_uri() . '/lib/img/move-icon.png'; ?>" alt="" /></td>
					<td class="column-thumbnail"><?php if (has_post_thumbnail()) { the_post_thumbnail( 'size-50' ); } else { ocart_default_image('sort'); } ?></td>
					<td class="column-title"><a href="post.php?post=<?php the_ID(); ?>&action=edit"><strong><?php the_title(); ?></strong></a></td>
				</tr>
			<?php endwhile; ?>
			</tbody>
			<tfoot>
				<tr>
					<th class="column-order"><?php _e('Order','ocart'); ?></th>
					<th class="column-thumbnail"><?php _e('Thumbnail','ocart'); ?></th>
					<th class="column-title"><?php _e('Title','ocart'); ?></th>
				</tr>
			</tfoot>

		</table>

	<?php else: ?>

		<p><?php echo __('No products found, why not <a href="post-new.php?post_type=product">create one?</a>','ocart'); ?></p>

	<?php endif; ?>
	<?php wp_reset_postdata(); // Don't forget to reset again! ?>

	<style>
		/* Dodgy CSS ^_^ */
		#sortable-table td { background: white; }
		#sortable-table .column-order { padding: 3px 10px; width: 50px;}
		#sortable-table .column-order img { cursor: move; }
		#sortable-table td.column-order { vertical-align: middle; height: 50px; text-align: center; }
		#sortable-table .column-thumbnail { width: 80px; }
		#sortable-table td.column-title { font-size: 13px; }
	</style>

	</div><!-- .wrap -->

<?php

}
