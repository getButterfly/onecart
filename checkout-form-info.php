<?php global $ocart; ?>

<form class="cform" id="cform" action="/" method="post">
	
	<?php if (is_user_logged_in() == false) { ?>
	
	<fieldset class="cform_email">
	<legend><?php _e('Checking out as a guest','ocart'); ?></legend>
		<p>
			<label></label>
			<input type="text" name="cform_email" id="cform_email" value="<?php _e('Enter your e-mail address...','ocart'); ?>" />
		</p>
	</fieldset>
	
	<?php } else { $current_user = wp_get_current_user(); ?>
	
	<fieldset class="cform_email">
	<legend><?php _e('Checking out as','ocart'); ?></legend>
		<p>
			<label><?php echo get_avatar($current_user->user_email, $size = '58', $d = get_template_directory_uri() . '/img/assets/default-user.png' ); ?></label>
			<input type="hidden" name="cform_email" id="cform_email" value="<?php echo $current_user->user_email; ?>" />
			<abbr class="usericon"><?php if ($current_user->first_name) { echo $current_user->first_name.' '.$current_user->last_name; } else { echo $current_user->user_email; } ?></abbr>
		</p>
	</fieldset>
	
	<?php } ?>
	
	<fieldset>
	<legend><?php _e('Billing Information','ocart'); ?></legend>
		<p>
			<label for="cform_fname"><?php _e('First Name','ocart'); ?></label>
			<input type="text" name="cform_fname" id="cform_fname" value="<?php echo ocart_get_field('first_name'); ?>" /><?php echo ocart_is_required('cform_fname'); ?>
		</p>
		<p>
			<label for="cform_lname"><?php _e('Last Name','ocart'); ?></label>
			<input type="text" name="cform_lname" id="cform_lname" value="<?php echo ocart_get_field('last_name'); ?>" /><?php echo ocart_is_required('cform_lname'); ?>
		</p>
		<p>
			<label for="cform_addr1"><?php _e('Address Line 1','ocart'); ?></label>
			<input type="text" name="cform_addr1" id="cform_addr1" value="<?php echo ocart_get_field('address1'); ?>" /><?php echo ocart_is_required('cform_addr1'); ?>
		</p>
		<p>
			<label for="cform_addr2"><?php _e('Address Line 2','ocart'); ?></label>
			<input type="text" name="cform_addr2" id="cform_addr2" value="<?php echo ocart_get_field('address2'); ?>" /><?php echo ocart_is_required('cform_addr2'); ?>
		</p>
		<p>
			<label for="cform_city"><?php _e('City/Town','ocart'); ?></label>
			<input type="text" name="cform_city" id="cform_city" value="<?php echo ocart_get_field('city'); ?>" /><?php echo ocart_is_required('cform_city'); ?>
		</p>
		<p>
			<label for="cform_state"><?php _e('State/Province','ocart'); ?></label>
			<input type="text" name="cform_state" id="cform_state" value="<?php echo ocart_get_field('state'); ?>" /><?php echo ocart_is_required('cform_state'); ?>
		</p>
		<p>
			<label for="cform_postcode"><?php _e('Postal Code','ocart'); ?></label>
			<input type="text" name="cform_postcode" id="cform_postcode" value="<?php echo ocart_get_field('postcode'); ?>" /><?php echo ocart_is_required('cform_postcode'); ?>
		</p>
		<p>
			<label for="cform_country"><?php _e('Country','ocart'); ?></label>
			<select name="cform_country" id="cform_country">
				<option value="0"><?php _e('Select your country...','ocart'); ?></option>
				<?php
				$countries = get_option('occommerce_allowed_countries');
				foreach($countries as $countrycode => $country) {
				?>
					<option value="<?php echo $countrycode; ?>" <?php selected($countrycode, ocart_get_field('country')); ?>><?php echo $country; ?></option>
				<?php } ?>
			</select><?php echo ocart_is_required('cform_country'); ?>
		</p>
		<p>
			<label for="cform_phone"><?php _e('Phone Number','ocart'); ?></label>
			<input type="text" name="cform_phone" id="cform_phone" value="<?php echo ocart_get_field('phone'); ?>" /><?php echo ocart_is_required('cform_phone'); ?>
		</p>
	</fieldset>
	
	<fieldset>
	<legend><?php _e('Shipping Information','ocart'); ?></legend>
		<p class="chkbox">
			<label for="cform_ub"><input type="checkbox" name="cform_ub" id="cform_ub" value="1" /><?php _e('Same as Billing Address','ocart'); ?></label>
		</p>
		<p>
			<label for="cform_fname2"><?php _e('First Name','ocart'); ?></label>
			<input type="text" name="cform_fname2" id="cform_fname2" value="<?php echo ocart_get_field('first_name2'); ?>" /><?php echo ocart_is_required('cform_fname2'); ?>
		</p>
		<p>
			<label for="cform_lname2"><?php _e('Last Name','ocart'); ?></label>
			<input type="text" name="cform_lname2" id="cform_lname2" value="<?php echo ocart_get_field('last_name2'); ?>" /><?php echo ocart_is_required('cform_lname2'); ?>
		</p>
		<p>
			<label for="cform_addr12"><?php _e('Address Line 1','ocart'); ?></label>
			<input type="text" name="cform_addr12" id="cform_addr12" value="<?php echo ocart_get_field('address12'); ?>" /><?php echo ocart_is_required('cform_addr12'); ?>
		</p>
		<p>
			<label for="cform_addr22"><?php _e('Address Line 2','ocart'); ?></label>
			<input type="text" name="cform_addr22" id="cform_addr22" value="<?php echo ocart_get_field('address22'); ?>" /><?php echo ocart_is_required('cform_addr22'); ?>
		</p>
		<p>
			<label for="cform_city2"><?php _e('City/Town','ocart'); ?></label>
			<input type="text" name="cform_city2" id="cform_city2" value="<?php echo ocart_get_field('city2'); ?>" /><?php echo ocart_is_required('cform_city2'); ?>
		</p>
		<p>
			<label for="cform_state2"><?php _e('State/Province','ocart'); ?></label>
			<input type="text" name="cform_state2" id="cform_state2" value="<?php echo ocart_get_field('state2'); ?>" /><?php echo ocart_is_required('cform_state2'); ?>
		</p>
		<p>
			<label for="cform_postcode2"><?php _e('Postal Code','ocart'); ?></label>
			<input type="text" name="cform_postcode2" id="cform_postcode2" value="<?php echo ocart_get_field('postcode2'); ?>" /><?php echo ocart_is_required('cform_postcode2'); ?>
		</p>
		<p>
			<label for="cform_country2"><?php _e('Country','ocart'); ?></label>
			<select name="cform_country2" id="cform_country2">
				<option value="0"><?php _e('Select your country...','ocart'); ?></option>
				<?php
				$countries = get_option('occommerce_allowed_shipping_destinations');
				foreach($countries as $countrycode => $country) {
				?>
					<option value="<?php echo $countrycode; ?>" <?php selected($countrycode, ocart_get_field('country2')); ?>><?php echo $country; ?></option>
				<?php } ?>
			</select><?php echo ocart_is_required('cform_country2'); ?>
		</p>
		<p>
			<label for="cform_phone2"><?php _e('Phone Number','ocart'); ?></label>
			<input type="text" name="cform_phone2" id="cform_phone2" value="<?php echo ocart_get_field('phone2'); ?>" /><?php echo ocart_is_required('cform_phone2'); ?>
		</p>
	</fieldset>

	<fieldset>
	<legend><?php _e('Additional fields','ocart'); ?></legend>
		<p>
			<label for="cform_note" class="wide"><?php _e('Order Note','ocart'); ?></label>
			<textarea name="cform_note" id="cform_note"></textarea>
			<span class="inform"><?php _e('Optionally, add a note for this order or any special requirements in the above field.','ocart'); ?></span>
		</p>
		<?php if (ocart_get_option('checkout_extras')) { ?>
		<p id="cform-calendar">
			<label for="cform_custom_delivery" class="wide"><?php _e('Choose Delivery Date','ocart'); ?></label>
			<input type="text" name="cform_custom_delivery" id="cform_custom_delivery" readonly="readonly" />
			<span class="inform"><?php _e('If you want to choose your delivery date please click on the date picker above.','ocart'); ?></span>
		</p>
		<?php } ?>
	</fieldset>
	
	<?php if ($ocart['dashboard_shipping_couriers'] != 0) { ?>
	<fieldset>
	<legend><?php _e('Select Shipping Option','ocart'); ?></legend>
		<p class="radiobox" id="radio_shipping_options">
			<?php
			
			for ($i = 1; $i <= 5; $i++) {
				if ($ocart['courier'.$i.'_label']) {
			?>
			<label for="courier<?php echo $i; ?>"><input type="radio" data-fee="<?php echo $ocart['courier'.$i.'_fee']; ?>" name="cform_shipping_option" id="courier<?php echo $i; ?>" value="courier<?php echo $i; ?>" /><?php echo $ocart['courier'.$i.'_label']; ?> 
			<abbr>(<?php if ($ocart['courier'.$i.'_fee'] == 0 || isset($_SESSION['force_free_shipping'])) { echo '<ins>+'.ocart_format_currency( ocart_show_price( 0 ) ).'</ins>'; } else { echo '+'. ocart_format_currency( ocart_show_price( $ocart['courier'.$i.'_fee'] ) ); } ?>)</abbr><?php if ($ocart['courier'.$i.'_est']) { echo '<datetime>'; printf(__('Est. Delivery: %s days','ocart'), $ocart['courier'.$i.'_est']); echo '</datetime>'; } ?></label>
			<?php
				}
			}
			?>
		</p>
	</fieldset>
	<?php } ?>
	
	<fieldset>
	<legend><?php _e('Payment','ocart'); ?></legend>
		<p class="radiobox" id="radio_payment_options">
		
			<?php ocart_payment_options() ?>
			
		</p>
	</fieldset>
	
	<?php if (ocart_get_option('page_terms')) { ?>
	<fieldset>
		<p class="chkbox chkbox_terms">
			<label for="cform_agreement"><input type="checkbox" name="cform_agreement" id="cform_agreement" /><?php printf(__('By completing a purchase with us you agree to our <a href="javascript:lightbox(null, \'%s/ajax/page.php?id=%s\');"><strong>Terms and Conditions</strong></a>.','ocart'), get_template_directory_uri(), ocart_get_option('page_terms')); ?></label>
		</p>
	</fieldset>
	<?php } ?>
	
	<p class="preorder"><?php _e('Please confirm your billing and shipping details before placing your order.','ocart'); ?></p>
	
	<p class="submit">
		<input type="hidden" name="cform_currency" id="cform_currency" value="<?php echo $ocart['currency']; ?>" />
		<input type="hidden" name="cform_gross_total" id="cform_gross_total" value="" />
		<input type="submit" name="cform_submit" id="cform_submit" value="<?php _e('Place Order','ocart'); ?>" />
	</p>
	
</form>