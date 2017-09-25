<?php
/*
	Template Name: Checkout
*/

// empty checkout not allowed!
if (empty($_SESSION['cart']) || $_SESSION['ocart_cart_count'] == 0) {
	wp_redirect ( home_url() );
	exit;
}

// redirect to checkout step
if (!isset($_GET['checkout_step'])) {
	if (is_user_logged_in()) {
		$url = add_query_arg( array ( 'checkout_step' => 2 ) );
	} else {
		$url = add_query_arg( array ( 'checkout_step' => 1 ) );
	}
	wp_redirect( $url );
	exit;
} else {
	if ($_GET['checkout_step'] == 1 && is_user_logged_in()) {
		wp_redirect( add_query_arg( array ( 'checkout_step' => 2 )  ) );
	}
}

?>

<?php get_header(); ?>

<?php get_template_part('template','header'); ?>

<?php ocart_display_super_nav() ?>

<div id="blog">

	<div class="wrap">
	
		<div class="checkout_left">
		
			<?php get_template_part('checkout', 'form'); ?>
		
		</div>
		
		<div class="checkout_right">
		
			<?php get_template_part('checkout', 'summary'); ?>
		
		</div><div class="clear"></div>
	
	</div>
	
</div>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>