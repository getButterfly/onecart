<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

?>

<div class="lightbox">

	<a href="javascript:closeLightbox()" class="close tip" title="<?php _e('Close window','ocart'); ?>"></a>
	
	<!-- form to login -->
	<div class="div-login">
		<h1><?php _e('Sign In To OneCart','ocart'); ?></h1>
		<form action="/" class="form_norm" id="form_login">
			<fieldset>
				<p><input type="text" class="form_text form_user" title="<?php _e('Enter your username','ocart'); ?>" name="log" id="log" /></p>
				<p><input type="password" class="form_text form_pass" title="<?php _e('Enter your password','ocart'); ?>" name="pwd" id="pwd" /></p>
				<p class="form-left"><a href="#" class="forgot-pw"><?php _e('Forgot password?','ocart'); ?></a></p>
				<p class="form-right"><input type="submit" value="<?php _e('Login','ocart'); ?>" class="form_submit" /></p>
			</fieldset>
		</form>
		<div class="member"><span><?php _e('Not a member yet?','ocart'); ?></span><a href="#" class="registerlink"><?php _e('Register','ocart'); ?></a></div><div class="clear"></div>
		<?php do_action('ocart_login_form_extend_fields'); ?>
	</div>
	
	<!-- form to register -->
	<div class="div-register">
		<h1><?php _e('Register an Account','ocart'); ?></h1>
		<form action="/" class="form_norm" id="form_register">
			<fieldset>
				<p><input type="text" class="form_text form_user" title="<?php _e('Choose a username','ocart'); ?>" name="r_user" id="r_user" /></p>
				<p><input type="text" class="form_text form_email" title="<?php _e('Enter your email address','ocart'); ?>" name="r_email" id="r_email" /></p>
				<p class="form-right">
					<input type="submit" value="<?php _e('Register','ocart'); ?>" class="form_submit" />
				</p>
			</fieldset>
		</form>
		<div class="member"><span><?php _e('Already a member?','ocart'); ?></span><a href="#" class="loginlink"><?php _e('Login','ocart'); ?></a></div><div class="clear"></div>
		<?php do_action('ocart_login_form_extend_fields'); ?>
	</div>
	
	<!-- form to reset password -->
	<div class="div-resetpw">
		<h1><?php _e('Lost your Password','ocart'); ?></h1>
		<form action="/" class="form_norm" id="form_resetpw">
			<fieldset>
				<p><input type="text" class="form_text form_user" title="<?php _e('Enter your username or email address','ocart'); ?>" name="login" id="login" /></p>
				<p class="form-right"><input type="submit" value="<?php _e('Submit','ocart'); ?>" class="form_submit" /></p>
			</fieldset>
		</form>
		<div class="member"><span><?php _e('Not a member yet?','ocart'); ?></span><a href="#" class="registerlink"><?php _e('Register','ocart'); ?></a></div><div class="clear"></div>
	</div>

</div>