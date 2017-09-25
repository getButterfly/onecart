<?php

// add meta box
add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add() {
	global $post, $post_ID;
    add_meta_box( 'orders-meta-box', sprintf(__('Modify Order #%s','ocart'), $post_ID), 'cd_meta_box_cb', 'orders', 'normal', 'high' );
}

// render meta box
function cd_meta_box_cb($post) {
	global $ocart;
	$order_status = get_post_meta($post->ID, 'order_status', true);
	$payment_status = get_post_meta($post->ID, 'payment_status', true);
	$order_tracking = get_post_meta($post->ID, 'order_tracking', true);
	$order_tracking_url = get_post_meta($post->ID, 'order_tracking_url', true);
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>

    <p>  
        <label for="order_status"><?php _e('Change Order Status','ocart'); ?></label> 
        <select name="order_status" id="order_status">
			<option value="received" <?php selected('received', $order_status); ?>><?php _e('New Order','ocart'); ?></option>
			<option value="awaiting" <?php selected('awaiting', $order_status); ?>><?php _e('Awaiting Payment','ocart'); ?></option>
			<option value="pending" <?php selected('pending', $order_status); ?>><?php _e('Pending','ocart'); ?></option>
			<option value="processing" <?php selected('processing', $order_status); ?>><?php _e('Processing','ocart'); ?></option>
			<option value="shipped" <?php selected('shipped', $order_status); ?>><?php _e('Shipped','ocart'); ?></option>
			<option value="delivered" <?php selected('delivered', $order_status); ?>><?php _e('Delivered','ocart'); ?></option>
			<option value="declined" <?php selected('declined', $order_status); ?>><?php _e('Declined','ocart'); ?></option>
			<option value="cancelled" <?php selected('cancelled', $order_status); ?>><?php _e('Cancelled','ocart'); ?></option>
        </select>
		<span class="description"><?php _e('You can change order status here. Customer will be notified by e-mail about this change.','ocart'); ?></span>
    </p>
	
	<p>
		<label></label>
		<input class="button button-secondary" type="submit" value="<?php _e('Email Order Status to Customer','ocart'); ?>" name="email_order_status">
	</p>
	
    <p>  
        <label for="payment_status"><?php _e('Change Payment Status','ocart'); ?></label> 
        <select name="payment_status" id="payment_status">
			<option value="Unpaid" <?php selected('Unpaid', $payment_status); ?>><?php _e('Unpaid','ocart'); ?></option>
			<option value="Completed" <?php selected('Completed', $payment_status); ?>><?php _e('Completed','ocart'); ?></option>
			<option value="Declined" <?php selected('Declined', $payment_status); ?>><?php _e('Declined','ocart'); ?></option>
        </select>
		<span class="description"><?php _e('You may need to manually change payment status here.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="order_notes"><?php _e('Order Comments','ocart'); ?></label> 
        <textarea name="order_notes" id="order_notes"></textarea>
    </p>
	
	<p>
		<label></label>
		<input class="button button-secondary" type="submit" value="<?php _e('Email Comments to Customer','ocart'); ?>" name="email_order_notes">
	</p>
	
    <p>
        <label for="courier"><?php _e('Shipping Courier','ocart'); ?></label>
		<?php ocart_shipping_options($loop='select', $id='courier'); ?>
		<span class="description"><?php _e('Customer has selected this shipping option during checkout. However you can change shipping method and notify your customer by email.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="order_tracking"><?php _e('Tracking Number','ocart'); ?></label> 
        <input type="text" name="order_tracking" id="order_tracking" value="<?php echo $order_tracking; ?>" />
		<span class="description"><?php _e('If you shipped this order, please enter tracking number for shipment here to allow customer to track their order.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="order_tracking_url"><?php _e('Tracking URL','ocart'); ?></label> 
        <input type="text" name="order_tracking_url" id="order_tracking_url" value="<?php echo $order_tracking_url; ?>" />
		<span class="description"><?php _e('If you shipped this order, please enter complete URL for customer to track their order.','ocart'); ?></span>
    </p>
	
	<p>
		<label></label>
		<input class="button button-secondary" type="submit" value="<?php _e('Email Tracking to Customer','ocart'); ?>" name="email_order_tracking">
	</p>
	
	<p>
		<label></label>
		<input id="publish" class="button button-primary" type="submit" value="<?php _e('Update Order','ocart'); ?>" accesskey="p" tabindex="5" name="save">
	</p>

<?php
}

// save meta box
add_action( 'save_post', 'cd_meta_box_save' );  
function cd_meta_box_save( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post', $post_id ) ) return;
		
	// check we're updating an order
	if (isset($_POST['order_status'])) {
	
		//If calling wp_update_post, unhook this function so it doesn't loop infinitely
		remove_action('save_post', 'cd_meta_box_save');
		wp_update_post(array('ID' => $post_id, 'post_status' => $_POST['order_status'] ));
		add_action('save_post', 'cd_meta_box_save');

		// update meta options
		update_post_meta( $post_id, 'order_status', esc_attr( $_POST['order_status'] ) ); // update order status
		update_post_meta( $post_id, 'payment_status', esc_attr( $_POST['payment_status'] ) ); // update order status
		update_post_meta( $post_id, 'courier', esc_attr( $_POST['courier'] ) ); // update courier
		update_post_meta( $post_id, 'order_tracking', esc_attr( $_POST['order_tracking'] ) ); // update tracking number
		update_post_meta( $post_id, 'order_tracking_url', esc_attr( $_POST['order_tracking_url'] ) ); // update tracking url
		
		// strip customer notes
		if (wp_filter_kses($_POST['order_notes']) != '') {
			$notes = stripslashes($_POST['order_notes']);
		} else {
			$notes = null;
		}

		/* handle customer emails : order status */
		if ( isset($_POST['email_order_status']) ) {

			// loop various emails/statuses
			$order_status = $_POST['order_status'];
			$notes = null; // disable notes
			switch ($order_status ) {
				case 'awaiting':
					wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Your Order is Awaiting Payment','ocart'), get_bloginfo('name')), ocart_email_template('order_awaiting_payment', $post_id, $notes), ocart_mail_headers());
					break;
				case 'pending':
					wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Your Order is Pending','ocart'), get_bloginfo('name')), ocart_email_template('order_pending', $post_id, $notes), ocart_mail_headers());
					break;
				case 'processing':
					wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Your Order is being Processed','ocart'), get_bloginfo('name')), ocart_email_template('order_processing', $post_id, $notes), ocart_mail_headers());
					break;
				case 'shipped':
					wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Your Order has been Shipped','ocart'), get_bloginfo('name')), ocart_email_template('order_shipped', $post_id, $notes), ocart_mail_headers());
					break;
				case 'cancelled':
					wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Your Order has been Cancelled','ocart'), get_bloginfo('name')), ocart_email_template('order_cancelled', $post_id, $notes), ocart_mail_headers());
					break;
				case 'declined':
					wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Your Order has been Declined','ocart'), get_bloginfo('name')), ocart_email_template('order_declined', $post_id, $notes), ocart_mail_headers());
					break;
			}
			
		}
		
		/* handle customer emails : order notes */
		if ( isset($_POST['email_order_notes']) ) {
			wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Regarding Order #%s','ocart'), get_bloginfo('name'), $post_id), ocart_email_template('order_comments', $post_id, $notes), ocart_mail_headers());
		}
		
		/* handle customer emails : order tracking */
		if ( isset($_POST['email_order_tracking']) ) {
			$notes = null;
			wp_mail(get_the_author_meta( 'user_email', get_post_meta($post_id, 'custID', true) ), sprintf(__('[%s] Tracking Number: %s','ocart'), get_bloginfo('name'), get_post_meta($post_id, 'order_tracking', true)), ocart_email_template('order_tracking', $post_id, $notes), ocart_mail_headers());
		}
	
	}

}

?>