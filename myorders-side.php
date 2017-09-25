<?php

global $ocart;

	$current_user = wp_get_current_user();
		
?>

<div class="checkout_summary">

	<div class="myorders">

		<h2><?php _e('My Account','ocart'); ?></h2>

		<form method="post" action="/" id="updateinfo">
		
			<div class="myorders_field">
				<h4><?php _e('Change Email Address','ocart'); ?></h4>
				<span class="help"><?php _e('You\'ll receive order notifications, notes, and any other messages on that address. Make sure it is always valid.','ocart'); ?></span>
				<input type="text" value="<?php echo $current_user->user_email; ?>" name="change_mail" id="change_mail" />
			</div>
			
			<div class="myorders_field">
				<h4><?php _e('Account Password','ocart'); ?></h4>
				<span class="help"><?php _e('For the security of your account, please enter your current password to confirm your changes.','ocart'); ?></span>
				<input type="password" value="" name="password_current" id="password_current" />
			</div>
		
			<div class="myorders_field">
				<h4><?php _e('New Account Password','ocart'); ?></h4>
				<span class="help"><?php _e('If you want to change your current password, please enter a new password below.','ocart'); ?></span>
				<input type="password" value="" name="password_new" id="password_new" />
			</div>
		
			<div class="myorders_field">
				<h4><?php _e('Confirm New Account Password','ocart'); ?></h4>
				<span class="help"><?php _e('For confirmation purposes, enter your new account password again here.','ocart'); ?></span>
				<input type="password" value="" name="password_confirm" id="password_confirm" />
			</div>
			
			<input type="submit" value="<?php _e('Save Account','ocart'); ?>" />
		
		</form>
		
	</div>
	
</div>