<?php
/*
	Template Name: Contact Us
*/

$current_user = wp_get_current_user();
?>

<?php get_header(); ?>

<?php get_template_part('template','header'); ?>

<?php ocart_display_super_nav() ?>

<div id="blog">
	
	<div class="wrap">
	
		<div class="blog_title">
			<h1><?php echo single_post_title(); ?></h1>
			<a href="<?php echo home_url(); ?>/" class="blog_store"><?php _e('Back to Store','ocart'); ?></a>
		</div>
		
		<div class="blog_wrap">
			<div class="blog_content">
			
				<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					
					<div class="post page">
						
						<div class="post-pad page-pad">
						<div class="post-content">

							<?php if (isset($ocart['html_contact_googlemaps'])) { ?>
								<p><?php echo $ocart['html_contact_googlemaps']; ?></p>
							<?php } ?>

							<div class="contact_left">
							<form method="post" action="/" id="contactform">
								<label for="contact_name"><?php _e('Name:','ocart'); ?><span>*</span></label>
								<input type="text" name="contact_name" id="contact_name" value="<?php echo $current_user->first_name.' '.$current_user->last_name; ?>" />
								<label for="contact_email"><?php _e('Email:','ocart'); ?><span>*</span></label>
								<input type="text" name="contact_email" id="contact_email" value="<?php echo $current_user->user_email; ?>" />
								<label for="contact_subject"><?php _e('Subject:','ocart'); ?></label>
								<input type="text" name="contact_subject" id="contact_subject" value="" />
								<label for="contact_message"><?php _e('Message:','ocart'); ?><span>*</span></label>
								<textarea name="contact_message" id="contact_message"></textarea>
								<input type="submit" value="<?php _e('Send Message','ocart'); ?>" />
							</form>
							</div>

							<div class="contact_right">

							<h3><?php _e('Contact Info','ocart'); ?></h3>

							<?php if (isset($ocart['html_contact_addr']) && $ocart['html_contact_addr'] != '') { ?>
							<p><em><?php echo $ocart['html_contact_addr']; ?></em></p>
							<?php } ?>

							<p><em><?php if (isset($ocart['contact_phone']) && $ocart['contact_phone'] != '') { printf(__('Phone: %s','ocart'), $ocart['contact_phone']); echo '<br />'; } ?>
							<?php if (isset($ocart['contact_fax']) && $ocart['contact_fax'] != '') { printf(__('Fax: %s','ocart'), $ocart['contact_fax']); echo '<br />'; } ?>
							<?php if (isset($ocart['contact_email']) && $ocart['contact_email'] != '') { printf(__('Email: <a href="mailto:%1$s">%1$s</a>','ocart'), $ocart['contact_email']); echo '<br />'; } ?>
							<?php if (isset($ocart['contact_web']) && $ocart['contact_web'] != '') { printf(__('Web: %s','ocart'), $ocart['contact_web']); } ?></em></p>
							
							<?php if (isset($ocart['html_contact_text']) && $ocart['html_contact_text'] != '') { echo $ocart['html_contact_text']; } ?>

							</div><div class="clear"></div>
		
						</div>
						</div>
						
					</div>
					
				<?php endwhile; ?>
				<?php endif; ?>

			</div>
			<?php get_sidebar(); ?>
		</div>
	
	</div>
	
</div>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>