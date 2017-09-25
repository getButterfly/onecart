<?php
/*
	Template Name: Full Width Template
*/
?>

<?php get_header(); ?>

<?php get_template_part('template','header'); ?>

<div id="blog">
	
	<div class="wrap">
	
		<div class="blog_title">
			<h1><?php echo single_post_title(); ?></h1>
			<a href="<?php echo home_url(); ?>/" class="blog_store"><?php _e('Back to Store','ocart'); ?></a>
		</div>
		
		<div class="blog_wrap">
			<div class="blog_content blog_content_fullwidth">
			
				<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
						<div class="page-pad">
						<div class="post-content">
							<?php the_content(); ?>
						</div>
						</div>
						
					</div>
					
				<?php endwhile; ?>
				<?php endif; ?>

			</div>
			<div class="clear"></div>
		</div>
	
	</div>
	
</div>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>