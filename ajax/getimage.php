<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

// require passed id
if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	die();
}

// meta info for post
$crop = get_post_meta($id, 'imagecrop', true);
$size = get_post_meta($id, 'imagesize', true);

if ($size == 'pic-small') $w = 100; $h = 100;
if ($size == 'pic-medium') $w = 125; $h = 125;
if ($size == 'pic-default') $w = 194; $h = ocart_get_option('catalog_image_height');

// print attachment image
$url = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');
		
echo '<img src="'.get_template_directory_uri().'/thumb.php?src='.$url[0].'&amp;w='.$w.'&amp;h='.$h.'&amp;a='.$crop.'&amp;q=100" class="productfront '.$size.'" alt="" />';

?>