<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$post = get_post($_GET['id']);
setup_postdata($post);

?>

<div class="lightbox lightbox-large lightbox-no-padding">

	<a href="javascript:closeLightbox()" class="close tip" title="<?php _e('Close window','ocart'); ?>"></a>

		<div class="blog_content blog_content_fullwidth">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-pad">
					<h1><?php the_title(); ?></h1>
					<div class="post-content">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
		<a href="javascript:closeLightbox()" class="closebutton" title="<?php _e('Close window','ocart'); ?>"><?php _e('Close window','ocart'); ?></a>
	
</div>

<script type="text/javascript">
	$('.blog_content').mCustomScrollbar();
</script>