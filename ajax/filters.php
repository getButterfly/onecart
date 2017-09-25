<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $ocart;
$published_posts = wp_count_posts('product');

?>

<h2><?php _e('Browse Products','ocart'); ?><?php if ($ocart['showcount'] == 1) { ?><span class="count">(<?php printf(__('%s Products','ocart'), $published_posts->publish); ?>)</span><?php } ?></h2>
<?php ocart_filters(); ?>

<script>
	$('.tax-parent-li > ul').each(function(){
		$(this).find('a:last').css({'border-bottom': 'none'});
	});
</script>