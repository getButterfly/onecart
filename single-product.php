<?php get_header(); ?>

<script type="text/javascript">
// custom scripting starts here
$(function() {

	<?php if (ocart_get_option('single_product_in_popup')) { ?>
	
	product_id = $('.product').attr('id').replace(/[^0-9]/g, '');
	lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', product_id);
	
	<?php } else { ?>

	$('#details').css({opacity: 1, left: 0});
	$('.iosSlider').css({'display': 'none'});
	deviceWidth = $(window).width();
	if (deviceWidth <= 977) {
		$('#banner, #details').css({'height':'800px'});
	}
	$('.navi li:last').remove();
	$('input[type="text"]').not('#min_price, #max_price').clearOnFocus();
	$('.btn-quantity').tipsy({
		trigger: 'focus',
		gravity: 'w',
		offset: 18
	});
	$('.tip').tipsy({
		delayIn: 200,
		gravity: 'n',
		offset: 8
	});
	$('.optionprice').tipsy({
		trigger: 'hover',
		gravity: 'w',
		offset: 4
	});
	// reinstate carousel
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(reinitCarousel, 100);
	$('.main-image .zoom:first').fadeIn(800, function(){
				if (deviceWidth > 766) {
				$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
	});
	$('.thumbs a, .thumbs2 a').click(function(){
				var rel = $(this).attr('rel');
				var currentID = $('.main-image .zoom:visible').attr('id');
				if (rel !== currentID && rel != 'video') {
					$('.main-image .zoom').fadeOut(800);
					$(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
						if (deviceWidth > 766) {
						$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
						}
					});
				}
	});
	
	<?php } ?>
	
});
</script>

<?php get_template_part('template','header'); ?>

<?php get_template_part('template','nav'); ?>

<div id="banner">
	<div class="wrap">
	
		<!-- filter products/sort by -->
		
		<a href="#" id="filter" class="help" title="<?php _e('Click to browse products','ocart'); ?>"></a>
		
		<div id="filter-by">

		</div>
		
		<!-- done -->
	
		<!-- product details -->
		<?php get_template_part('template','product-noajax'); ?>
		
		<!-- slider container -->
		<div class="iosSlider">

			<!-- slider -->
			<div class="slider">
			
				<!-- loop -->
				
				<?php
				global $WP_Query;
				$slides = new WP_Query( array( 'post_type' => 'slide', 'posts_per_page' => 5 ));
				$c = array();
				if ($slides->have_posts() ) :
					while ( $slides->have_posts() ) : $slides->the_post();
						$content = get_post_meta($post->ID, 'slide_content', true);
						$video = get_post_meta($post->ID, 'slide_video', true);
						$video_pos = get_post_meta($post->ID, 'video_pos', true);
						$url = get_post_meta($post->ID, 'slide_button_url', true);
						$text = get_post_meta($post->ID, 'slide_button_text', true);
						$slide_url = get_post_meta($post->ID, 'slide_url', true);
						$video_txt1 = get_post_meta($post->ID, 'video_txt1', true);
						$video_txt2 = get_post_meta($post->ID, 'video_txt2', true);
				?>

				<?php if (has_post_thumbnail()) { $c[] = 'entry'; ?>
				<div class="slide">
					
					<?php
					if ($slide_url) { echo '<a href="'.$slide_url.'">'; }
					//the_post_thumbnail();
					ocart_thumb_only(977, 406);
					if ($slide_url) { echo '</a>'; }
					?>
				
					<!-- show this if there is no video -->
					
					<?php if (!$video) { ?>
					
						<?php if ($post->post_title) { ?>
						<div class="text1"><span><?php the_title(); ?></span></div>
						<?php } ?>
						
						<?php if ($content || $url) { ?>
						<div class="text2">
							<?php if ($content) { ?>
							<span><?php echo $content; ?></span>
							<?php } ?>
							<?php if ($url) { ?>
							<p><a href="<?php echo $url; ?>" class="button1"><?php if ($text) { echo $text; } else { echo __('Learn More','ocart'); } ?></a></p>
							<?php } ?>
						</div>
						<?php } ?>
					
					<?php } else { ?>
					
						<?php if ($video_pos == 1) { ?>
						
						<div class="text5"><?php echo $video; ?></div>
					
						<?php if ($video_txt1 || $video_txt2) { ?>
						<div class="text6">
							<p class="large"><?php echo $video_txt1; ?></p>
							<p class="smaller"><?php echo $video_txt2; ?></p>
							<?php if ($url) { ?>
							<p class="hasbutton"><a href="<?php echo $url; ?>" class="button1"><?php if ($text) { echo $text; } else { echo __('Learn More','ocart'); } ?></a></p>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php } else { ?>
						
						<div class="text3"><?php echo $video; ?></div>
					
						<?php if ($video_txt1 || $video_txt2) { ?>
						<div class="text4">
							<p class="large"><?php echo $video_txt1; ?></p>
							<p class="smaller"><?php echo $video_txt2; ?></p>
							<?php if ($url) { ?>
							<p class="hasbutton"><a href="<?php echo $url; ?>" class="button1"><?php if ($text) { echo $text; } else { echo __('Learn More','ocart'); } ?></a></p>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php } ?>
					
					<?php } ?>
					
				</div>
				<?php } ?>
	
				<?php
					endwhile;
				endif;
				?>
			
			</div>
			
			<!-- slider controls -->
			
			<?php if (count($c) > 1) { ?>
			
			<div class="iosSlider_buttons">
				<?php foreach($c as $entry) { ?><div class="button"></div><?php } ?>
			</div>
			
			<div class="nextButton"></div>
			<div class="prevButton"></div>
			
			<?php } ?>

		</div>
	
	</div>
</div>

<?php get_template_part('template','similar'); ?>

<?php // show collections after slider ?>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('home-afterslider') ) : else : endif; ?>

<div id="catalog-noajax">
<?php get_template_part('template','catalog-noajax'); ?>
</div>

<?php // show collections after catalog ?>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('home-aftercatalog') ) : else : endif; ?>

<?php get_template_part('template','bottom'); ?>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>