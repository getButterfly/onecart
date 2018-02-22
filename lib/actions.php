<?php
/************************************************************
update sale record
************************************************************/
add_action('ocart_after_order_placed', 'ocart_default_order_payment_status');
function ocart_default_order_payment_status($post_id){
	update_post_meta($post_id, 'order_status', 'received');
	update_post_meta($post_id, 'payment_status', 'Unpaid');

	/* new post status */
	wp_update_post( array( 'ID' => $post_id, 'post_status' => 'received' ) );
}

/************************************************************
update sale record
************************************************************/
add_action('ocart_after_order_placed', 'ocart_update_sale_record');
function ocart_update_sale_record($post_id){

	$order_gross = get_post_meta($post_id, 'payment_gross_total', true);
	$sub_tax = get_post_meta($post_id, 'order_tax', true);
	$sub_shipping = get_post_meta($post_id, 'shipping_fee', true);

	$sub_total = $order_gross - ($sub_tax + $sub_shipping);

	/* total sales */
	$sales = get_option('occommerce_get_sales');
	update_option('occommerce_get_sales', $sub_total + $sales);

	/* day sales */
	$date = date('Y-m-d');
	$sale_data = get_option('occommerce_sales_by_day');
	if (!isset($sale_data[$date])) { $sale_data[$date] = 0; }
	$sale_data[$date] += $sub_total;
	update_option('occommerce_sales_by_day', $sale_data);

	/* month sales */
	$date = date('Y-m');
	$sale_data = get_option('occommerce_sales_by_month');
	if (!isset($sale_data[$date])) { $sale_data[$date] = 0; }
	$sale_data[$date] += $sub_total;
	update_option('occommerce_sales_by_month', $sale_data);

	/* country sales */
	$code = $_POST['cform_country'];
	$sale_data = get_option('occommerce_sales_by_country');
	if (!isset($sale_data[$code]['orders'])) { $sale_data[$code]['orders'] = 0; }
	$sale_data[$code]['orders'] += 1;
	if (!isset($sale_data[$code]['volume'])) { $sale_data[$code]['volume'] = 0; }
	$sale_data[$code]['volume'] += $sub_total;
	update_option('occommerce_sales_by_country', $sale_data);

	/* total shipping */
	$shipping = get_option('occommerce_get_shipping');
	update_option('occommerce_get_shipping', $sub_shipping + $shipping);

	/* day shipping */
	$date = date('Y-m-d');
	$sale_data = get_option('occommerce_shipping_by_day');
	if (!isset($sale_data[$date])) { $sale_data[$date] = 0; }
	$sale_data[$date] += $sub_shipping;
	update_option('occommerce_shipping_by_day', $sale_data);

	/* month shipping */
	$date = date('Y-m');
	$sale_data = get_option('occommerce_shipping_by_month');
	if (!isset($sale_data[$date])) { $sale_data[$date] = 0; }
	$sale_data[$date] += $sub_shipping;
	update_option('occommerce_shipping_by_month', $sale_data);

	/* country shipping */
	$code = $_POST['cform_country'];
	$sale_data = get_option('occommerce_shipping_by_country');
	if (!isset($sale_data[$code]['orders'])) { $sale_data[$code]['orders'] = 0; }
	$sale_data[$code]['orders'] += 1;
	if (!isset($sale_data[$code]['volume'])) { $sale_data[$code]['volume'] = 0; }
	$sale_data[$code]['volume'] += $sub_shipping;
	update_option('occommerce_shipping_by_country', $sale_data);

	/* total tax */
	$tax = get_option('occommerce_get_tax');
	update_option('occommerce_get_tax', $sub_tax + $tax);

	/* day tax */
	$date = date('Y-m-d');
	$sale_data = get_option('occommerce_tax_by_day');
	if (!isset($sale_data[$date])) { $sale_data[$date] = 0; }
	$sale_data[$date] += $sub_tax;
	update_option('occommerce_tax_by_day', $sale_data);

	/* month tax */
	$date = date('Y-m');
	$sale_data = get_option('occommerce_tax_by_month');
	if (!isset($sale_data[$date])) { $sale_data[$date] = 0; }
	$sale_data[$date] += $sub_tax;
	update_option('occommerce_tax_by_month', $sale_data);

	/* country tax */
	$code = $_POST['cform_country'];
	$sale_data = get_option('occommerce_tax_by_country');
	if (!isset($sale_data[$code]['orders'])) { $sale_data[$code]['orders'] = 0; }
	$sale_data[$code]['orders'] += 1;
	if (!isset($sale_data[$code]['volume'])) { $sale_data[$code]['volume'] = 0; }
	$sale_data[$code]['volume'] += $sub_tax;
	update_option('occommerce_tax_by_country', $sale_data);

}

/************************************************************
load admin/lib css and js
************************************************************/
define('MY_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('MY_THEME_FOLDER',str_replace('\\','/',dirname(__FILE__)));
define('MY_THEME_PATH','/' . substr(MY_THEME_FOLDER,stripos(MY_THEME_FOLDER,'wp-content')));

add_action('admin_init','ocart_admin_css_and_js');

function ocart_admin_css_and_js() {
	wp_enqueue_style('my_orders_css', MY_THEME_PATH . '/css/orders.css');
	wp_enqueue_style('my_slide_css', MY_THEME_PATH . '/css/slide.css');
	wp_enqueue_style('my_product_css', MY_THEME_PATH . '/css/product.css');
	wp_enqueue_style('my_seo_css', MY_THEME_PATH . '/css/seo.css');
	wp_enqueue_style('my_coupon_css', MY_THEME_PATH . '/css/coupon.css');
	wp_enqueue_style('dashboard_css', MY_THEME_PATH . '/css/dashboard.css');
	wp_enqueue_style('oc_colorpicker_admin',  MY_THEME_PATH . '/css/colorpicker.css');
	wp_enqueue_style('oc_layout_admin',  MY_THEME_PATH . '/css/layout.css');

	wp_enqueue_script('product_datepicker', MY_THEME_PATH . '/js/datepickers.js', array('jquery', 'jquery-ui-datepicker'));
	wp_enqueue_script('oc_cookies_js_admin', MY_THEME_PATH . '/js/jquery.cookies.js', array('jquery'));
	wp_enqueue_script('oc_colorpicker_js_admin', MY_THEME_PATH . '/js/colorpicker.js', array('jquery'));
	wp_enqueue_script('oc_eye_js_admin', MY_THEME_PATH . '/js/eye.js', array('jquery'));
    wp_enqueue_script('oc_utils_js_admin', MY_THEME_PATH . '/js/utils.js', array('jquery'));
	wp_enqueue_script('oc_panel_js_admin', MY_THEME_PATH . '/js/panel.js', array('jquery'));
	wp_enqueue_script('oc_editcountries', MY_THEME_PATH . '/js/countries.js', array('jquery'));
	wp_enqueue_script('googlechart', 'https://www.google.com/jsapi', array('jquery'));
    wp_enqueue_script('oc_layout_js_admin', MY_THEME_PATH . '/js/layout.js', array('jquery'));
	/* Register jQuery UI style */
	if ( 'classic' == get_user_option( 'admin_color') )
		wp_register_style('onecart-jquery-ui-style',MY_THEME_PATH.'/css/admin-classic.css',array());
	else
		wp_register_style('onecart-jquery-ui-style',MY_THEME_PATH .'/css/admin-fresh.css',array());
	wp_enqueue_style('onecart-jquery-ui-style');
}

/************************************************************
load scripts in head
************************************************************/
function ocart_enqueue_scripts() {
	wp_enqueue_script('jqueryall', get_stylesheet_directory_uri() . '/js/jquery.js', array('jquery'));
	wp_enqueue_script('ui', get_stylesheet_directory_uri() . '/js/jquery.ui.js', array('jquery'));
	wp_enqueue_script('waypoints', get_stylesheet_directory_uri() . '/js/jquery.waypoints.js', array('jquery'));
	wp_enqueue_script('iosslider', get_stylesheet_directory_uri() . '/js/jquery.iosslider.js', array('jquery'));
	wp_enqueue_script('easing', get_stylesheet_directory_uri() . '/js/jquery.easing.js', array('jquery'));
	wp_enqueue_script('tipsy', get_stylesheet_directory_uri() . '/js/jquery.tipsy.js', array('jquery'));
	wp_enqueue_script('bgpos', get_stylesheet_directory_uri() . '/js/jquery.bgpos.js', array('jquery'));
	wp_enqueue_script('scrollto', get_stylesheet_directory_uri() . '/js/jquery.scrollTo.js', array('jquery'));
	wp_enqueue_script('zoom', get_stylesheet_directory_uri() . '/js/jquery.jqzoom.js', array('jquery'));
	wp_enqueue_script('lightbox', get_stylesheet_directory_uri() . '/js/jquery.lightbox.js', array('jquery'));
	wp_enqueue_script('carousel', get_stylesheet_directory_uri() . '/js/jquery.carouFredSel.js', array('jquery'));
	wp_enqueue_script('scrollbar', get_stylesheet_directory_uri() . '/js/jquery.mCustomScrollbar.js', array('jquery'));
	wp_enqueue_script('mousewheel', get_stylesheet_directory_uri() . '/js/jquery.mousewheel.js', array('jquery'));
	wp_enqueue_script('jcookies', get_stylesheet_directory_uri() . '/js/jquery.cookies.js', array('jquery'));
	wp_enqueue_script('functions', get_stylesheet_directory_uri() . '/js/jquery.functions.js', array('jquery'));
}

add_action('wp_enqueue_scripts', 'ocart_enqueue_scripts');

/************************************************************
what we will load in header using wp_head
************************************************************/
function ocart_place_in_header() {
	global $ocart;

?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/default.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/flick/jquery-ui.css" />
	<link href='https://fonts.googleapis.com/css?family=Open+Sans|Montserrat|Bree+Serif|Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>

	<?php /* background images */ ?>

	<style type="text/css">

	body {background: <?php ocart_background() ?>;}

	.cart .cart-link span {background: url(<?php ocart_current_skin_uri() ?>/icon-count.png) no-repeat;}

	.addtocart {background: url(<?php ocart_current_skin_uri() ?>/addtocart.png) no-repeat 0 0;}

	.cartarrow {background: url(<?php ocart_current_skin_uri() ?>/arrow-cart.png) no-repeat center;}

	.tax-parent {background: #fff url(<?php ocart_current_skin_uri() ?>/arrow-dd.png) no-repeat right top;}

	#filter {background: url(<?php ocart_current_skin_uri() ?>/filter.png) no-repeat 0 0;}

	.tax ul ul li a {background: url(<?php ocart_current_skin_uri() ?>/filter-item.png) no-repeat 10px 0;}

	.items .remove {background: url(<?php ocart_current_skin_uri() ?>/remove.png) no-repeat top;}

	#options li.sort .sort-link {background: url(<?php ocart_current_skin_uri() ?>/arrow-list.png) no-repeat right top;}

	#browser .next, #browser .prev {background-image: url(<?php ocart_current_skin_uri() ?>/icon-buttons.png);}

	.nextItem, .prevItem, .snextItem, .sprevItem {background: #fff url(<?php ocart_current_skin_uri() ?>/nav-catalog.png) no-repeat;}

	.upImage, .dnImage, .nextImage, .prevImage {background: #fff url(<?php ocart_current_skin_uri() ?>/nav-images.png) no-repeat;}

	a.back {background: url(<?php ocart_current_skin_uri() ?>/backto.png) no-repeat left center;}

	.blog_store {background: url(<?php ocart_current_skin_uri() ?>/back.png) no-repeat 8px top;}

	.tabcontent ul li {background: url(<?php ocart_current_skin_uri() ?>/bullet.png) no-repeat 20px 20px;}

	.blog_content .post ul li {background: url(<?php ocart_current_skin_uri() ?>/bullet.png) no-repeat left center;}

	.blog_content .page ul li {background: url(<?php ocart_current_skin_uri() ?>/bullet.png) no-repeat left center;}

	.c-tag {background: url(<?php ocart_current_skin_uri() ?>/tags.png) no-repeat;}

	.product-tag {background: url(<?php ocart_current_skin_uri() ?>/prodlabels.png) no-repeat;}

	.nextButton {background: url(<?php ocart_current_skin_uri() ?>/nav-slider.png) no-repeat -40px 0;}
	.prevButton {background: url(<?php ocart_current_skin_uri() ?>/nav-slider.png) no-repeat 0 0;}

	.nextItem {right: -10px;background-position: -20px 0;}
	.nextItem:hover {background-position: -20px -22px}
	.prevItem {left: -10px;background-position: 0 0;}
	.prevItem:hover {background-position: 0 -22px}
	.snextItem {right: 25px;background-position: -20px 0;}
	.snextItem:hover {background-position: -20px -22px}
	.sprevItem {left: 25px;background-position: 0 0;}
	.sprevItem:hover {background-position: 0 -22px}
	.product-tag-new, .product-tag-instock {background-position: 0 0}
	.product-tag-sale {background-position: 0 -26px}
	.product-tag-sold {background-position: 0 -52px}
	.dnImage, .upImage {right: -18px}
	.nextImage, .prevImage {right: 165px}
	.dnImage, .nextImage {top: 200px}
	.upImage, .prevImage {top: 172px}
	.dnImage {background-position: 0 0}
	.upImage {background-position: -35px 0}
	.nextImage {background-position: -70px 0}
	.prevImage {background-position: -105px 0}
	.dnImage:hover {background-position: 0 -27px}
	.upImage:hover {background-position: -35px -27px}
	.nextImage:hover {background-position: -70px -27px}
	.prevImage:hover {background-position: -105px -27px}
	.tag-sale {background-position: 0 0}
	.tag-new {background-position: 0 -28px}
	.tag-sold {background-position: 0 -56px}

	</style>

	<?php /* conditional css */
	if (ocart_current_skin() != 'default' ) { ?>

	<style type="text/css">

	.product-tag-new, .product-tag-instock, .btn-add, .btnstyle1, .checkout_login input[type=submit],
	.cform input[type=submit], .sidebar .widget a.btnstyle3
	{text-shadow: none;}

	</style>

	<?php } ?>

	<?php /* css */ ?>

	<style type="text/css">

	#header {background-color: <?php ocart_skin_data('header_bg') ?>}

	.label-content .price-now, .btnstyle1, .blog_store, .blog_content .post-tags a:hover,
	#comments a#cancel-comment-reply-link:hover, #submit, .tagcloud a, .cform input[type=submit], .myorders input[type=submit],
	#contactform input[type=submit]:hover, #contactform input[type=submit]:focus, #contactform input[type=submit]:active,
	.form_submit, .plus:hover, .minus:hover, .cartbtn a:hover, .iosSlider_buttons .selected, .iosSlider_buttons .selected:hover,
	.iosSlider .button1, #closeProductdetail:hover, .coupon input[type=submit]:hover, .loc_fields input[type=submit]:hover, #reviewform input[type=submit]:hover,
	p#toggle_review a:hover, .catalog_item_status_sale, #supermenu li.current-menu-item > a, #supermenu li.current-menu-item > a:hover,
	#supermenu li.current-menu-ancestor > a, .lightbox .closebutton:hover, .get-more-results a
	{background-color: <?php ocart_skin_data('active_color') ?>}

	#supermenu li.current-menu-item > a, #supermenu li.current-menu-item > a:hover {
	border-right: 1px solid <?php ocart_skin_data('active_color') ?>;
	}

	.list a.current, .iosSlider .button1:hover, .tax ul ul li a:hover span, .tax ul ul li a.colorbox:hover span ,
	.section .meta a, #footer a:hover, .product-price .price-now,
	.product-tax a.current, .product-tax a:hover, .t-productname a, .calc-total span, .blog_nav .current-cat a,
	.blog_content .post a, .blog_content .post-tags span, .blog_content .page a, .blog_content .page h1, #comments a,
	#comments a#cancel-comment-reply-link, .widget a, .widget_custom a,
	.tabcontent li span.meta a, .checkout_total span, .checkout_process h2, .cform span a, .cform p.radiobox label ins,
	.cform span.req, .result a, .myorders a, .myorders_field span.errorfield, table.table-invoice ins, #contactform label span,
	.paymentform input[type=submit], #Skrill_payment_form input[type=submit], #Authorize_payment_form input[type=submit],
	#Zaakpay_payment_form input[type=submit], #Pagos_payment_form input[type=submit], #Payex_payment_form input[type=submit], .items .info span, .infotab_div a, .cform p.radiobox label a,
	.myorders p.radiobox label a, .product-rating-note a, span.is_req, .catalog_item_title span.price, .filter ul li a.selected,
	.cform p.chkbox_terms label a, .column-price
	{color: <?php ocart_skin_data('active_color') ?>}

	.cartpopup
	{
		border-top: 2px solid <?php ocart_skin_data('active_color') ?>;
	}

	ul.options
	{
		border-top: 3px solid <?php ocart_skin_data('active_color') ?>;
	}

	.cform input[type=text]:focus, .cform textarea:focus, .cform select:focus, .myorders_field input[type=text]:focus, .myorders_field input[type=password]:focus,
	#contactform input[type=text]:focus, #contactform textarea:focus
	{
		-webkit-box-shadow: <?php ocart_skin_data('active_color') ?> 0 0 1px;
		-moz-box-shadow: <?php ocart_skin_data('active_color') ?> 0 0 1px;
		box-shadow: <?php ocart_skin_data('active_color') ?> 0 0 1px;
		border-color: <?php ocart_skin_data('active_color') ?>;
	}

	.iosSlider .text1 span, .iosSlider .text2 span {
		background: <?php ocart_skin_data('slide_text_bg') ?>;
		color: <?php ocart_skin_data('slide_text_color') ?>;
	}

	.section p, #commentform p
	{
		color: <?php ocart_skin_data('text_color_1') ?>;
	}

	.tax-parent, #min_price, #max_price, .tax ul ul li a, .product-about p, .product-tax-nocart li, .product-tax a,
	.blog_content .post-meta, .blog_content .post p, #blog .blog_content .post-tags a, .blog_content .page-meta,
	.blog_content .page p, .liststyle1 .entry span, .checkout_guest p
	{
		color: <?php ocart_skin_data('text_color_3') ?>;
	}

	.thecart th, #toplinks a, #options li.sort .sort-link, #options li.sort .sort-link-nodd, ul.options a, .section h3, .member span
	{
		color: <?php ocart_skin_data('text_color_2') ?>;
	}

	.section .meta, .section datetime
	{
		color: <?php ocart_skin_data('text_color_4') ?>;
	}

	#bottom {
		background-color: <?php ocart_skin_data('bottom_bg') ?>;
	}

	.prods {
		border-bottom: 1px solid <?php ocart_skin_data('catalog_border') ?>;
	}

	#header {
		border-bottom: 1px solid <?php ocart_skin_data('header_border') ?>;
	}

	#footer {
		border-top: 1px solid <?php ocart_skin_data('footer_border') ?>;
	}

	.widget, .widget_custom, .checkout_left, .checkout_summary, .blog_content .post, .blog_content .page, .commentlist .vcard img,
	.commentlist p em, #commentform input[type=text], #commentform textarea
	{
		border-bottom: 1px solid <?php ocart_skin_data('widget_border') ?>;
	}

	.list a, .blog_nav a {
		color: <?php ocart_skin_data('nav_color') ?>;
	}

	.blog_title, .commentlist li.depth-1 {
		border-bottom: 1px solid <?php ocart_skin_data('common_border_1') ?>;
	}

	.commentlist li.depth-2, .commentlist li.depth-3 {
		border-top: 1px solid <?php ocart_skin_data('common_border_1') ?>;
	}

	.commentlist p {
		color: <?php ocart_skin_data('comments_color') ?>;
	}

	.commentlist .commentmetadata {
		color: <?php ocart_skin_data('comments_meta_color') ?>;
	}

	.commentlist .commenter, #comments .commentlist .commenter a {
		color: <?php ocart_skin_data('comments_author') ?>;
	}

	.checkout_login input[type=submit] {
		background: <?php ocart_skin_data('button_style1_color') ?>;
	}

	.checkout_login input[type=submit]:hover {
		background: <?php ocart_skin_data('button_style1_hover') ?>;
	}

	.cform input[type=submit]:hover, .cform input[type=submit]:focus, .cform input[type=submit]:active, .myorders input[type=submit]:hover {
		background: <?php ocart_skin_data('button_hover_2') ?>;
	}

	.checkout_process h3, .cform legend, .widget h3, .widget_custom h3 {
		color: <?php ocart_skin_data('heading3') ?>;
	}

	.sidebar .widget a.btnstyle3 {
		background: <?php ocart_skin_data('button_style2_color') ?>;
	}

	.blog_content .post-meta span {
		color: <?php ocart_skin_data('text_color_5') ?>;
	}

	#supermenu li a {
		color: <?php ocart_skin_data('menu_color') ?>;
	}

	#supermenu li.current-menu-item > a, #supermenu li.current-menu-item > a:hover, #supermenu li.current-menu-ancestor > a  {
		color: <?php ocart_skin_data('menu_active_color') ?>;
	}

	#supermenu li a:hover {
		background-color: <?php ocart_skin_data('menu_hover_bg') ?>;
		color: <?php ocart_skin_data('menu_hover_color') ?>;
	}

	#supermenu ul {
		background: <?php ocart_skin_data('menu_sub_bg') ?>;
	}

	#supermenu li li {
		border-bottom: 1px dotted <?php ocart_skin_data('menu_sub_border') ?>;
	}

	#supermenu ul a {
		color: <?php ocart_skin_data('menu_sub_color') ?>;
	}

	#supermenu ul {
		opacity: <?php if (ocart_get_option('theme_menu_opacity') != 100) { ?>0.<?php } ?><?php echo ocart_get_option('theme_menu_opacity'); ?>;
		filter: alpha(opacity:<?php echo ocart_get_option('theme_menu_opacity'); ?>);
	}

	</style>

	<?php /* custom/dynamic css */ ?>

	<style type="text/css">

	<?php if (!ocart_get_option('theme_slide_usebg')) { ?>
	.iosSlider .text1 span, .iosSlider .text2 span {
		background: none;
		padding: 0;
	}
	<?php } ?>

	.iosSlider .text1 span, .iosSlider .text2 span {
		opacity: <?php if (ocart_get_option('theme_slide_opacity') != 100) { ?>0.<?php } ?><?php echo ocart_get_option('theme_slide_opacity'); ?>;
		filter: alpha(opacity:<?php echo ocart_get_option('theme_slide_opacity'); ?>);
	}

	<?php if (ocart_get_option('theme_slide_usebg_image')) { ?>
	.iosSlider .text1 span, .iosSlider .text2 span {
		background: url(<?php echo get_template_directory_uri(); ?>/img/bg-transparent.png) repeat;
		padding: 10px;
		opacity: 1;
		filter: alpha(opacity:100);
	}
	<?php } ?>

	<?php if (!ocart_get_option('enable_slideshow')) { ?>
	.nextButton, .prevButton, .iosSlider_buttons {display: none}
	<?php } ?>

	#lightbox-shadow {
		background: #<?php echo ocart_get_option('lightbox_shadow'); ?>;
	}

	#header, #header-holder {
		height: <?php echo ocart_get_option('theme_header_height'); ?>px;
	}

	<?php if (ocart_get_option('disable_cart')) { ?>
	#toplinks {
		padding: 0;
	}
	<?php } ?>

	<?php if (!ocart_get_option('show_nav_all')) { ?>
	.list {
		left: 0;
	}
	<?php } ?>

	#catalog, .prods, .prods li {
		height: <?php echo ocart_get_option('catalog_image_height'); ?>px;
	}

	<?php
	if (isset($ocart['scroll_attr']) && is_array($ocart['scroll_attr'])) {
		foreach ($ocart['scroll_attr'] as $key) {
	?>
	.filter ul.root-<?php echo $key; ?> {
		overflow: auto;
		height: 200px;
	}
	<?php
		}
	}
	?>

	.main-image {
		left: <?php echo ocart_get_option('main_image_left_px'); ?>px;
	}

	<?php
	global $ocart;
	if (isset($ocart['html_custom_css']) && $ocart['html_custom_css']) {
		print $ocart['html_custom_css'];
	}
	?>

	</style>

	<?php /* responsive css */ ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" />

	<?php

	// javascripts, jquery
	require_once get_template_directory().'/js/jquery.custom.js.php';

	// other js code, tracking code, before </head>
	if (isset($ocart['html_header_code']) && !empty($ocart['html_header_code'])) {
		echo $ocart['html_header_code'];
	}

}
add_action('wp_head', 'ocart_place_in_header');

/************************************************************
include comment reply
************************************************************/
function ocart_queue_js(){
if ( (!is_admin()) && is_singular() && comments_open() && get_option('thread_comments') )
	wp_enqueue_script( 'comment-reply' );
}
add_action('wp_print_scripts', 'ocart_queue_js');

/************************************************************
Do not show admin bar
************************************************************/
add_action( 'show_admin_bar', '__return_false' );

/************************************************************
Register custom menus
************************************************************/
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
	register_nav_menus(
	array(
		'nav_menu' => __('Navigation Menu','ocart'),
		'header_menu' => __('Header Menu','ocart'),
		'footer_menu' => __('Footer Menu','ocart')
		)
	);
}

/************************************************************
register page to sort products
************************************************************/
add_action( 'admin_menu', 'sneek_register_product_menu' );
function sneek_register_product_menu() {
	add_submenu_page(
		'edit.php?post_type=product',
		__('Sort Products','ocart'),
		__('Sort Products','ocart'),
		'edit_pages', 'product-order',
		'sneek_product_order_page'
	);
}

/************************************************************
add drag and drop script
************************************************************/
add_action( 'admin_enqueue_scripts', 'sneek_admin_enqueue_scripts' );

function sneek_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'sneek-admin-scripts', get_template_directory_uri() . '/lib/js/sneek-admin-scripts.js' );
}

/************************************************************
ajax callback to update products order
************************************************************/
add_action( 'wp_ajax_sneek_update_post_order', 'sneek_update_post_order' );

function sneek_update_post_order() {
	global $wpdb;

	$post_type     = $_POST['postType'];
	$order        = $_POST['order'];

	/**
	*    Expect: $sorted = array(
	*                menu_order => post-XX
	*            );
	*/
	foreach( $order as $menu_order => $post_id )
	{
		$post_id         = intval( str_ireplace( 'post-', '', $post_id ) );
		$menu_order     = intval($menu_order);
		wp_update_post( array( 'ID' => $post_id, 'menu_order' => $menu_order ) );
	}

	die( '1' );
}

/************************************************************
custom icons in dashboard
************************************************************/
add_action( 'admin_head', 'ocart_product_icons' );
function ocart_product_icons() {
    ?>
    <style type="text/css" media="screen">
	#icon-edit.icon32-posts-product {background: url(<?php echo get_template_directory_uri() ?>/lib/img/product-32.png) no-repeat;}
	#icon-edit.icon32-posts-slide {background: url(<?php echo get_template_directory_uri() ?>/lib/img/slide-32.png) no-repeat;}
	.icon32-posts-orders {background: url(<?php echo get_template_directory_uri() ?>/lib/img/orders-32.png) no-repeat;}
	.icon32-posts-coupon {background: url(<?php echo get_template_directory_uri() ?>/lib/img/coupon-32.png) no-repeat;}
    </style>
<?php }

/************************************************************
remove fields from collection page
************************************************************/
add_action( 'admin_footer-edit-tags.php', 'ocart_collection_fields' );

function ocart_collection_fields(){
    global $current_screen;
    if ($current_screen->id == 'edit-collection') {
?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $('div.form-field').find('#parent, #tag-description, #tag-slug').parent().remove();
		$('tr.form-field').find('td').find('#parent, #description, #slug').parent().parent().remove();
    });
    </script>
    <?php
	}
}

?>