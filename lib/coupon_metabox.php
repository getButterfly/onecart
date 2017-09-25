<?php

// add meta box
add_action( 'add_meta_boxes', 'cp_meta_box_add' );
function cp_meta_box_add() {
	global $post, $post_ID;
    add_meta_box( 'coupon-meta-box', __('Coupon Details','ocart'), 'cp_meta_box_cb', 'coupon', 'normal', 'high' );
}

// render meta box
function cp_meta_box_cb($post) {
	global $ocart;
	
	/* text fields */
	$discount_type = get_post_meta($post->ID, 'discount_type', true);
	$discount_amount = (double)get_post_meta($post->ID, 'discount_amount', true);
	$min_subtotal = (double)get_post_meta($post->ID, 'min_subtotal', true);
	$user_emails = get_post_meta($post->ID, 'user_emails', true);
	$usage_limit = get_post_meta($post->ID, 'usage_limit', true);
	$expiry = get_post_meta($post->ID, 'expiry', true);
	
	/* checkboxes */
	$individual_use = get_post_meta($post->ID, 'individual_use', true);
	$individual_use_checked = isset( $individual_use ) ? esc_attr( $individual_use ) : '';
	
	$free_shipping = get_post_meta($post->ID, 'free_shipping', true);
	$free_shipping_checked = isset( $free_shipping ) ? esc_attr( $free_shipping ) : '';
	
	$must_login = get_post_meta($post->ID, 'must_login', true);
	$must_login_checked = isset( $must_login ) ? esc_attr( $must_login ) : '';
	
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	
    ?>
	
    <p>  
        <label for="discount_type"><?php _e('Discount type','ocart'); ?></label> 
        <select name="discount_type" id="discount_type">
			<option value="1" <?php selected('1', $discount_type); ?>><?php _e('Fixed Price Discount','ocart'); ?></option>
			<option value="2" <?php selected('2', $discount_type); ?>><?php _e('Percent Discount','ocart'); ?></option>
        </select>
    </p>
	
    <p>
        <label for="discount_amount"><?php _e('Coupon amount','ocart'); ?></label> 
        <input type="text" name="discount_amount" id="discount_amount" value="<?php if (!empty($discount_amount)) echo number_format($discount_amount, 2); ?>" placeholder="0.00" />
		<span class="description"><?php _e('Enter coupon amount. e.g. 5.99','ocart'); ?></span>
    </p>
	
	<p class="grid_checkbox">
		<label for="individual_use"><?php _e('Individual use','ocart'); ?></label>
		<input type="checkbox" id="individual_use" name="individual_use" <?php checked( $individual_use_checked, 'on' ); ?> /><span class="description"><?php _e('Check this box if the coupon cannot be used in conjunction with other coupons','ocart'); ?>
	</p>
	
	<p class="grid_checkbox">
		<label for="free_shipping"><?php _e('Enable free shipping','ocart'); ?></label>
		<input type="checkbox" id="free_shipping" name="free_shipping" <?php checked( $free_shipping_checked, 'on' ); ?> /><span class="description"><?php _e('Check this box if the coupon enables free shipping','ocart'); ?>
	</p>
	
	<p class="grid_checkbox">
		<label for="must_login"><?php _e('Require user login','ocart'); ?></label>
		<input type="checkbox" id="must_login" name="must_login" <?php checked( $must_login_checked, 'on' ); ?> /><span class="description"><?php _e('Check this box if user must log in to use this coupon','ocart'); ?>
	</p>
	
    <p>
        <label for="min_subtotal"><?php _e('Minimum amount','ocart'); ?></label> 
        <input type="text" name="min_subtotal" id="min_subtotal" value="<?php if (!empty($min_subtotal)) echo number_format($min_subtotal, 2); ?>" placeholder="<?php _e('No minimum','ocart'); ?>" />
		<span class="description"><?php _e('Set the minimum cart subtotal required to use this coupon.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="user_emails"><?php _e('Customer Emails','ocart'); ?></label> 
        <input type="text" name="user_emails" id="user_emails" value="<?php echo $user_emails; ?>" placeholder="<?php _e('All customers','ocart'); ?>" />
		<span class="description"><?php _e('Comma separate email addresses to restrict this coupon to sepcific user emails.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="usage_limit"><?php _e('Usage limit','ocart'); ?></label> 
        <input type="text" name="usage_limit" id="usage_limit" value="<?php if (!empty($usage_limit)) echo $usage_limit; ?>" placeholder="<?php _e('Unlimited usage','ocart'); ?>" />
		<span class="description"><?php _e('How many times this coupon can be used before it is void.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="expiry"><?php _e('Expiration date','ocart'); ?></label> 
        <input type="text" name="expiry" id="expiry" value="<?php echo $expiry; ?>" placeholder="<?php _e('Never expires','ocart'); ?>" />
		<span class="description"><?php _e('The date this coupon will expire. e.g. <code>YYYY-MM-DD</code>','ocart'); ?></span>
    </p>

<?php
}

// save meta box
add_action( 'save_post', 'cp_meta_box_save' );  
function cp_meta_box_save( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post', $post_id ) ) return;
	
	// update custom meta
	if (isset($_POST['discount_type'])) {
	
	update_post_meta( $post_id, 'discount_type', esc_attr( $_POST['discount_type'] ) );
	update_post_meta( $post_id, 'discount_amount', esc_attr( $_POST['discount_amount'] ) );
	update_post_meta( $post_id, 'min_subtotal', esc_attr( $_POST['min_subtotal'] ) );
	update_post_meta( $post_id, 'user_emails', esc_attr( $_POST['user_emails'] ) );
	if ( $_POST['usage_limit'] == '') { // default to no limit
		$_POST['usage_limit'] = 9999;
	}
	update_post_meta( $post_id, 'usage_limit', esc_attr( $_POST['usage_limit'] ) );
	update_post_meta( $post_id, 'expiry', esc_attr( $_POST['expiry'] ) );
	
	// save checkboxes

    $individual_use_checked = isset( $_POST['individual_use'] ) && $_POST['individual_use'] ? 'on' : 'off';
    update_post_meta( $post_id, 'individual_use', $individual_use_checked );
	
    $free_shipping_checked = isset( $_POST['free_shipping'] ) && $_POST['free_shipping'] ? 'on' : 'off';
    update_post_meta( $post_id, 'free_shipping', $free_shipping_checked );
	
    $must_login_checked = isset( $_POST['must_login'] ) && $_POST['must_login'] ? 'on' : 'off';
    update_post_meta( $post_id, 'must_login', $must_login_checked );
	
	}

}

?>