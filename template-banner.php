<div id="banner">
	<div class="wrap">
	
		<!-- filter products/sort by -->
		<?php if (ocart_catalog_version() == 1) { ?>
		<a href="#browse" id="filter" class="help" title="<?php _e('Click to browse products','ocart'); ?>"></a>
		
		<div id="filter-by">

		</div>
		<?php } ?>
		<!-- done -->
	
		<!-- product details -->
		<?php get_template_part('template','product'); ?>
		
		<!-- slider container -->
		<div class="iosSlider">

			<!-- slider -->
			<div class="slider">
			
				<!-- loop -->
				
				<?php
				global $WP_Query;
				$slides = new WP_Query( array( 'post_type' => 'slide', 'posts_per_page' => -1 ));
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