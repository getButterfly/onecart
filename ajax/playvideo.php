<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

// setup post to retrieve video
// of this product
$id = $_GET['p'];
$post = get_post($id);
setup_postdata($post);

?>

<div class="lightbox lightbox-large-video">
	
	<?php
	$product_video = get_post_meta($post->ID, 'customtab_video', true);
	echo wpautop($product_video);
	?>
	
</div>