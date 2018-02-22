<?php
/**
 * Only run if theme is being activated for the first time.
 * Create required pages, default options and settings
*/

$flag = get_option('occommerce_first_time_theme_activation_hook');
if ($flag == false && is_admin()) {
	/*************************************************
	create myorders page
	*************************************************/
	$myorders = array(
		'post_title'    => __('My Orders','ocart'),
		'post_type'     => 'page',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_name'     => 'myorders'
	);
	$myorders_id = wp_insert_post( $myorders );
	update_post_meta($myorders_id, '_wp_page_template', 'myorders.php');

	/*************************************************
	create checkout page
	*************************************************/
	$checkout = array(
		'post_title'    => __('Checkout','ocart'),
		'post_type'     => 'page',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_name'     => 'checkout'
	);
	$checkout_id = wp_insert_post( $checkout );
	update_post_meta($checkout_id, '_wp_page_template', 'checkout.php');

	/*************************************************
	create blog page
	*************************************************/
	$blog = array(
		'post_title'    => __('Blog','ocart'),
		'post_type'     => 'page',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_name'     => 'blog'
	);
	$blog_id = wp_insert_post( $blog );
	update_post_meta($blog_id, '_wp_page_template', 'blog.php');

	/*************************************************
	create default social bookmarks
	*************************************************/
	$bookmarks = array('blogger','digg','dribble','facebook','feed','feedburner','flickr','google','lastfm','linkedin','newsvine','sharethis','skype','tumblr','twitter','vimeo','wordpress','youtube');
	update_option('occommerce_social_bookmarks', $bookmarks);

	/*************************************************
	create default payment logos
	*************************************************/
	$payments = array('paypal','co','visa','electron','mastercard','maestro','cirrus','amex','discover','solo','switch','delta','directdebit','moneybookers','westernunion','google','ebay','sage');
	update_option('occommerce_payment_logos', $payments);

	/*************************************************
	install default currency codes
	*************************************************/
	$currencies = array('AUD','CAD','EUR','GBP','JPY','USD','NZD','CHF','HKD','SGD','SEK','DKK','PLN','NOK','HUF','CZK','ILS','MXN','BRL','MYR','PHP','TWD','THB','TRY');
	update_option('occommerce_currencies', $currencies);

	/*************************************************
	save default options
	*************************************************/
	$defaults = array(
		'emptyterms' => 1, // hide empty terms
		'showcount' => 1, // show products count
		'paymethods' => array('paypal','visa','mastercard','amex','discover'), // default payment logos
		'product_attr' => array('color', 'size'), // default product attributes
		'grid_attr' => array('product_category', 'brand', 'color', 'size'), // default grid view attributes
		'scroll_attr' => '', // default scrollable attributes empty
		'browser_attr' => array('product_category', 'brand', 'color', 'size'), // default browser attributes
		'related_tax' => 'product_category', // default relationship for similar products
		'default_nav_tax' => 'brand', // default navigation filter
		'show_gridbtn' => 1, // show grid button by default
		'show_sliderbtn' => 1, // show slider button by default
		'show_backtotop' => 1, // show back to top button by default
		'currencycode' => 'USD', // default currency code
		'disable_cart' => 0, // default to false do not disable purchase/cart module
		'disable_prices' => 0, // default to false do not disable prices in catalog mode
		'dashboard_shipping_couriers' => 1, // 1 shipping option at startup
		'courier1_label' => 'FedEx Standard Overnight®', // shipping label
		'courier1_fee' => '20.00', // shipping fee
		'courier1_est' => '2-5', // shipping est. delivery
		'blogger' => '#',
		'digg' => '#',
		'dribble' => '#',
		'facebook' => '#',
		'feed' => '#',
		'feedburner' => '#',
		'flickr' => '#',
		'google' => '#',
		'lastfm' => '#',
		'linkedin' => '#',
		'newsvine' => '#',
		'sharethis' => '#',
		'skype' => '#',
		'tumblr' => '#',
		'twitter' => '#',
		'vimeo' => '#',
		'wordpress' => '#',
		'youtube' => '#',
		'html_order_received' => ocart_default_template_html_order_received(), // default email templates (preset in functions.php)
		'html_order_awaiting_payment' => ocart_default_template_html_order_awaiting_payment(),
		'html_order_pending' => ocart_default_template_html_order_pending(),
		'html_order_processing' => ocart_default_template_html_order_processing(),
		'html_order_shipped' => ocart_default_template_html_order_shipped(),
		'html_order_cancelled' => ocart_default_template_html_order_cancelled(),
		'html_order_declined' => ocart_default_template_html_order_declined(),
		'html_order_tracking' => ocart_default_template_html_order_tracking(),
		'html_order_comments' => ocart_default_template_html_order_comments(),
		'html_admin_order_received' => ocart_default_template_html_admin_order_received(),
		'html_header_code' => '', // default header code
		'skin' => 'default', // default skin
		'html_custom_css' => '', // custom css rules
		'html_footer' => sprintf(__('Copyright &copy;2017 <a href="%s">%s</a>. All Rights Reserved. Powered by <a href="https://wordpress.org/">WordPress</a> &amp; <a href="https://getbutterfly.com/downloads/onecart/">OneCart</a>.','ocart'), home_url(), get_bloginfo('name')), // footer copyright/text
		'html_contact_googlemaps' => '<iframe width="614" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=San+Francisco,+CA&aq=&sll=37.0625,-95.677068&sspn=56.200193,114.169922&ie=UTF8&hq=&hnear=San+Francisco,+California&t=m&ll=37.775057,-122.419281&spn=0.094979,0.2108&z=12&iwloc=A&output=embed"></iframe>', // default googlemap for contact page
		'html_contact_addr' => '30 South Park Avenue<br />San Francisco, CA 94108<br />USA', // default contact addr.
		'contact_phone' => '(123) 456-7890', // default contact phone
		'contact_fax' => '+08 (123) 456-7890', // default contact fax
		'contact_email' => 'contact@companyname.com', // default contact email
		'contact_web' => 'companyname.com', // default contact website
		'html_contact_text' => '<h3>Dummy Text</h3>
		<p><em>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</em></p>', // default dummy text in contact page
		'lightbox_shadow' => 'fff', // default lightbox shadow
		'theme_usebgcolor' => 0, // default to no
		'theme_header_height' => 62, // default header height
		'scroll_distance' => 400, // default scroll distance
		'catalog_version' => 2, // default to slider catalog
		'weight_unit' => 'lbs', // default weight unit
		'cost_per_item' => '', // default to none
		'ocml' => 1, // enable multi language module
		'ocmc' => 1, // enable multi currency module
		'grid_prod_num' => 9, // show 9 results on grid by default
		'max_grid_prods' => 18, // show maximum X products before button
		'show_nav' => 1, // show main dropdown navigation by default
		'show_nav_all' => 1, // show main navigation (all)
		'currency_pos' => 'left', // left pos. for currency by default
		'checkout_extras' => 1, // enable extra delivery date by default
		'enable_slideshow' => 1, // enable slideshow by default
		'enable_calc' => 1, // enable calc form on cart by default
		'theme_slide_opacity' => 100, // slide text opacity
		'theme_menu_opacity' => 100, // menu 2 submenu opacity
		'theme_slide_usebg' => 0, // default to not use background for slideshow text
		'theme_slide_usebg_image' => 1, // default to use background image for slideshow text
		'enable_tax' => 1, // enable tax by default
		'tax_rate' => 0, // default tax rate 0%
		'show_bloglink' => 1, // show blog link by default
		'show_login' => 1, // show login/logout by default
		'page_terms' => 0, // no terms requirement by default
		'tax_included' => 0, // default to prices are NOT inclusive of tax
		'show_product_breadcrumb' => 1, // default to show product breadcrumb
		'menu_style' => 0, // default to original menu style
		'catalog_image_height' => 176, // default to 176px in catalog image height
		'mail_name' => get_bloginfo('name'), // default headers store name
		'mail_address' => get_option('admin_email'), // default headers admin e-mail address
		'sort_products' => 1, // sort products by menu_order by default
		'weight_table' => '', // default blank weight pricing rules
		'attr_select' => 0, // default display options in unordered list
		'multi_currency' => 'USD, EUR, GBP, AUD, JPY', // default multi currency supported
		'force_lightbox' => 0, // open products in slide mode by default
		'cur_no_space' => 0, // default no SPACES
		'single_product_in_popup' => 0, // default do not open product in popup
	);
	update_option('ocart', $defaults);

	/*************************************************
	save so this won't get executed again
	*************************************************/
	update_option('occommerce_first_time_theme_activation_hook', 'true');

}

/************************************************************
add payment gateways
************************************************************/
if (!get_option('occommerce_OC_gateways')) {
$OCgateways = array(
		'cod' => array ( 'name' => __('Cash on Delivery','ocart'), 'paymentname' => __('Cash on Delivery (COD)','ocart'), 'options' => array( 'enabled', 'requirelogin', 'charge' ) ),
		'bank' => array ( 'name' => __('Wire Transfer','ocart'), 'paymentname' => __('Wire Transfer','ocart'), 'options' => array( 'enabled', 'instructions', 'charge' ) ),
		'cheque' => array ( 'name' => __('Cheque','ocart'), 'paymentname' => __('Cheque payment','ocart'), 'options' => array( 'enabled', 'instructions', 'charge' ) ),
		'paylater' => array( 'name' => __('Pay Later','ocart'), 'paymentname' => __('Pay Later','ocart'), 'options' => array( 'enabled', 'charge' ) )
	);
	update_option('occommerce_OC_gateways', $OCgateways);
}

/************************************************************
add taxonomy files if they do not exist
************************************************************/
if (get_option('occommerce_custom_attributes')) {
	$attrs = get_option('occommerce_custom_attributes');
	foreach($attrs as $attr) {
		$slug = $attr['slug'];
		$single = $attr['single'];
		$plural = $attr['plural'];
		if (!file_exists(get_template_directory().'/lib/custom/'.$slug.'.php')) { // check the taxonomy file exists
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
		}
	}
}
// delete_option('occommerce_custom_attributes'); // uncomment to remove custom attributes

/************************************************************
setup default zone
************************************************************/
if (!get_option('occommerce_zones')) {
	$zones = array(
		0 => array(
			'name' => __('Everywhere Else','ocart'),
			'filters' => array(
				'regions' => array()
			),
			'pricing' => array(
				'fixed_tax' => 0,
				'pct_tax' => 0,
				'fixed_shipping' => 0,
				'pct_shipping' => 0,
				'weight' => array(),
				'handling' => array()
			)
		)
	);
	update_option('occommerce_zones', $zones);
}

/************************************************************
install countries list
************************************************************/
if (!get_option('occommerce_all_countries')) {
	$countries = array (
    'AF' => 'Afghanistan',
    'AX' => 'Åland Islands',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua and Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia and Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'IO' => 'British Indian Ocean Territory',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos (Keeling) Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CD' => 'Congo, the Democratic Republic of the',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'CI' => "Côte d'Ivoire",
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island and McDonald Islands',
    'VA' => 'Holy See (Vatican City State)',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IM' => 'Isle of Man',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => "Lao People's Democratic Republic",
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'MX' => 'Mexico',
    'FM' => 'Micronesia',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
	'KP' => 'North Korea',
    'MP' => 'Northern Mariana Islands',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestine',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RE' => 'Réunion',
    'RO' => 'Romania',
    'RU' => 'Russian Federation',
    'RW' => 'Rwanda',
    'BL' => 'Saint Barthélemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts and Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin (French part)',
    'PM' => 'Saint Pierre and Miquelon',
    'VC' => 'Saint Vincent and the Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome and Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'KR' => 'South Korea',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard and Jan Mayen',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad and Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks and Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'GB' => 'United Kingdom',
    'US' => 'United States',
    'UM' => 'United States Minor Outlying Islands',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VE' => 'Venezuela',
    'VN' => 'Vietnam',
    'VG' => 'Virgin Islands, British',
    'VI' => 'Virgin Islands, U.S.',
    'WF' => 'Wallis and Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe'
	);
	update_option('occommerce_all_countries', $countries);
	update_option('occommerce_allowed_countries', $countries);
	update_option('occommerce_allowed_shipping_destinations', $countries);
}

/************************************************************
add skins
************************************************************/

/* default skin */
update_option('occommerce_skin_default', array(
	'body_bg' => '#ffffff',
	'active_color' => '#ea6ea0',
	'header_bg' => '#ffffff',
	'slide_text_color' => '#ffffff',
	'slide_text_bg' => '#000000',
	'text_color_1' => '#929292',
	'text_color_2' => '#686868',
	'text_color_3' => '#929292',
	'text_color_4' => '#afaeae',
	'text_color_5' => '#666666',
	'nav_color' => '#929292',
	'nav_hover_color' => '#111111',
	'bottom_bg' => 'none',
	'catalog_border' => '#cccccc',
	'header_border' => '#cccccc',
	'footer_border' => '#cccccc',
	'common_border_1' => '#dddddd',
	'widget_border' => '#cccccc',
	'comments_color' => '#666666',
	'comments_meta_color' => '#9e9e9e',
	'comments_author' => '#333333',
	'button_hover_1' => '#e74a89',
	'button_hover_2' => '#f67bad',
	'button_style1_color' => '#99ca3b',
	'button_style1_hover' => '#abd759',
	'button_style2_color' => '#49c9f8',
	'button_style2_hover' => '#76d5f8',
	'heading3' => '#494949',
	'menu_color' => '#666666',
	'menu_active_color' => '#ffffff',
	'menu_hover_color' => '#ffffff',
	'menu_hover_bg' => '#666666',
	'menu_sub_bg' => '#ffffff',
	'menu_sub_color' => '#666666',
	'menu_sub_border' => '#dddddd',
));

/* canvas skin */
update_option('occommerce_skin_canvas', array(
	'body_bg' => '#ffffff',
	'active_color' => '#a46516',
	'header_bg' => '#ffffff',
	'slide_text_color' => '#ffffff',
	'slide_text_bg' => '#000000',
	'text_color_1' => '#998e80',
	'text_color_2' => '#885c25',
	'text_color_3' => '#333333',
	'text_color_4' => '#c7b399',
	'text_color_5' => '#bda696',
	'nav_color' => '#333333',
	'nav_hover_color' => '#a46516',
	'bottom_bg' => '#f6f3e3',
	'catalog_border' => '#e8d8c4',
	'header_border' => '#cccccc',
	'footer_border' => '#e8d8c4',
	'common_border_1' => '#d4bdad',
	'widget_border' => '#cccccc',
	'comments_color' => '#333333',
	'comments_meta_color' => '#b29580',
	'comments_author' => '#947966',
	'button_hover_1' => '#da7900',
	'button_hover_2' => '#da7900',
	'button_style1_color' => '#414141',
	'button_style1_hover' => '#555555',
	'button_style2_color' => '#f48800',
	'button_style2_hover' => '#fda83f',
	'heading3' => '#b4a087',
	'menu_color' => '#666666',
	'menu_active_color' => '#ffffff',
	'menu_hover_color' => '#ffffff',
	'menu_hover_bg' => '#666666',
	'menu_sub_bg' => '#ffffff',
	'menu_sub_color' => '#666666',
	'menu_sub_border' => '#dddddd',
));
