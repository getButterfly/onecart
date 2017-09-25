<?php
/*
	Template Name: My Orders
*/

		$current_user = wp_get_current_user();
		$id = $current_user->ID;
		
		// invoice#
		if (isset($_GET['invoiceID']) && is_user_logged_in()) {
			$invoice = get_post($_GET['invoiceID']);
			$custID = get_post_meta($invoice->ID, 'custID', true);
			if ($custID != $id)
				unset($invoice); // do not allow invoice to be read
		}
		
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
	
		<div class="checkout_left">
		
			<?php if (isset($invoice)) { get_template_part('myorders', 'invoice'); } else { get_template_part('myorders', 'grid'); } ?>
		
		</div>
		
		<div class="checkout_right">
		
			<?php get_template_part('myorders', 'side'); ?>
		
		</div><div class="clear"></div>
	
	</div>
	
</div>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>