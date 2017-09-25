<div class="checkout_process">

	<h2><?php echo single_post_title(); ?></h2>
	
	<?php if ($_GET['checkout_step'] == 1 && !is_user_logged_in()) { // step 1 : user not signed in ?>
	
	<div class="checkout_guest">
		<h3><?php _e('Do not have an account?','ocart'); ?></h3>
		<p><?php _e('Checkout as a guest and save time checking out each time you order. You\'ll also get access to your order status and history.','ocart'); ?></p>
		<h3><?php _e('Checkout as a Guest','ocart'); ?></h3>
		<a href="<?php echo add_query_arg( array ( 'checkout_step' => 2 ) ); ?>" class="btnstyle1"><?php _e('Next','ocart'); ?></a>
	</div>
	
	<div class="checkout_login">
		<h3><?php _e('Already a customer? Login Here','ocart'); ?></h3>
		<form action="/" id="checkout_form_login">
			<fieldset>
				<p><input type="text" class="checkout_form_text" title="<?php _e('Enter your username','ocart'); ?>" name="c_log" id="c_log" /></p>
				<p><input type="password" class="checkout_form_text" title="<?php _e('Enter your password','ocart'); ?>" name="c_pwd" id="c_pwd" /></p>
				<?php do_action('ocart_login_form_extend_fields'); ?>
				<p class="alignleft"><a href="javascript:lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/forgotpassword.php', 'login');" class="checkout_resetpw"><?php _e('Forgot password?','ocart'); ?></a></p>
				<p class="alignright"><input type="submit" value="<?php _e('Login','ocart'); ?>" class="checkout_form_submit" /></p>
			</fieldset>
		</form>
	</div>
	
	<div class="clear"></div>
	
	<?php } ?>
	
	<?php if ($_GET['checkout_step'] == 2) { // checkout details ?>
	
		<?php get_template_part('checkout','form-info'); ?>
	
	<?php } ?>

</div>