<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

?>

<div class="lightbox">

	<a href="javascript:closeLightbox()" class="close tip" title="<?php _e('Close window','ocart'); ?>"></a>
	
	<!-- form to reset password -->
	<div class="div-standalone-resetpw">
		<h1><?php _e('Lost your Password','ocart'); ?></h1>
		<form action="/" class="form_norm form_norm2" id="form_resetpw">
			<fieldset>
				<p><input type="text" class="form_text form_user" title="<?php _e('Enter your username or email address','ocart'); ?>" name="login" id="login" /></p>
				<p class="form-right"><input type="submit" value="<?php _e('Submit','ocart'); ?>" class="form_submit" /></p>
			</fieldset>
		</form>
	</div>

</div>