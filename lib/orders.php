<?php

/************************************************************
register orders private post type
************************************************************/
function register_orders() {
  // get new orders
  $new_orders = get_posts( array( 'post_type' => 'orders', 'post_status' => 'draft', 'meta_key' => 'order_status', 'meta_value' => array('received','awaiting','pending'), 'numberposts' => -1 ) );
  $count = count($new_orders);
  if ($count == 1) {
	$title = esc_attr( sprintf( '%s new order', $count ) );
  } else {
    $title = esc_attr( sprintf( '%s new orders', $count ) );
  }
  $menu_label = sprintf( __( 'Orders %s' ), "<span class='update-plugins count-$count' title='$title'><span class='update-count'>" . number_format_i18n($count) . "</span></span>" );
  $labels = array(
    'name' => _x('Orders', 'post type general name'),
    'singular_name' => _x('Order', 'post type singular name'),
    'add_new' => _x('Add New', 'orders'),
    'add_new_item' => __('Add New Order'),
    'edit_item' => __('Modify Order'),
    'new_item' => __('New Order'),
    'all_items' => $menu_label,
    'view_item' => __('View Order'),
    'search_items' => __('Search Orders'),
    'not_found' =>  __('No orders found.'),
    'not_found_in_trash' => __('No orders found in Trash.'), 
    'parent_item_colon' => '',
    'menu_name' => __('Orders')
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
	'exclude_from_search' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => 'occommerce', 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'supports' => array('')
  ); 
  register_post_type('orders',$args);
}
add_action( 'init', 'register_orders' );

/************************************************************
custom statuses to orders
************************************************************/
function ocart_order_statuses(){
  register_post_status( 'received', array(
      'label' => __('New','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'New <span class="count">(%s)</span>', 'New <span class="count">(%s)</span>' ),
  ) );
  register_post_status( 'awaiting', array(
      'label' => __('Awaiting Payment','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Awaiting Payment <span class="count">(%s)</span>', 'Awaiting Payment <span class="count">(%s)</span>' ),
  ) );
  register_post_status('pending', array(
      'label' => __('Pending','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>' ),
  ) );
  register_post_status( 'processing', array(
      'label' => __('Processing','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>' ),
  ) );
  register_post_status( 'shipped', array(
      'label' => __('Shipped','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>' ),
  ) );
  register_post_status( 'delivered', array(
      'label' => __('Delivered','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Delivered <span class="count">(%s)</span>', 'Delivered <span class="count">(%s)</span>' ),
  ) );
  register_post_status( 'declined', array(
      'label' => __('Declined','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Declined <span class="count">(%s)</span>', 'Declined <span class="count">(%s)</span>' ),
  ) );
  register_post_status( 'cancelled', array(
      'label' => __('Cancelled','ocart'),
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>' ),
  ) );
}
add_action( 'init', 'ocart_order_statuses' );

/************************************************************
first-time mod, update to new status tagging system
this will run only once on 'draft' orders
************************************************************/
$new_orders = get_posts( array( 'post_type' => 'orders', 'post_status' => 'draft', 'numberposts' => -1 ) );
foreach($new_orders as $getorder) {
	$current_status = get_post_meta($getorder->ID, 'order_status', true);
	wp_update_post( array( 'ID' => $getorder->ID, 'post_status' => $current_status ) );
}

/************************************************************
change status messages when updating an order
************************************************************/
add_filter('post_updated_messages', 'orders_updated_messages');
function orders_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['orders'][10] = sprintf(__('Order #%s updated.','ocart'), $post_ID);
	return $messages;
}

/************************************************************
remove publish box from orders view
************************************************************/
function remove_orders_box()
{
	remove_meta_box('submitdiv', 'orders', 'side' );
	remove_meta_box('slugdiv', 'orders', 'core');
}
add_action( 'admin_menu', 'remove_orders_box' );

/************************************************************
manage/edit the columns
************************************************************/
function manage_cols($columns) {
	$columns['order_no'] = __('Order No.','ocart');
	$columns['order_status'] = __('Order Status','ocart');
	$columns['payment'] = __('Payment Status','ocart');
	$columns['payment_type'] = __('Payment','ocart');
	$columns['order_summary'] = __('Order Summary','ocart');
	$columns['billing'] = __('Billing Address','ocart');
	$columns['shipping'] = __('Shipping Address','ocart');
	$columns['customer_note'] = __('Extra Notes','ocart');
	$date_label = $columns['date'];
	unset($columns['date']);
	$columns['date'] = $date_label;
	unset($columns['title']);
	return $columns;
}
add_action('manage_edit-orders_columns', 'manage_cols');

/************************************************************
render the columns
************************************************************/
function render_cols($column){
	global $post;
	
	// order number
	if ($column == 'order_no') {
		echo '<a href="post.php?post='.$post->ID.'&action=edit">'.sprintf(__('Order #%s','ocart'), $post->ID).'</a>';
	}
	
	// payment type
	if ($column == 'payment_type') {
		$payment_type = get_post_meta($post->ID, 'payment_type', true);
		if ($payment_type) {
			$gateways = get_option('occommerce_OC_gateways');
			$type= str_replace('pay_by_','', $payment_type);
			if (isset($gateways[$type]['name'])) echo $gateways[$type]['name'];
		}
	}
	
	// payment status
	if ($column == 'payment') {
		$payment_status = get_post_meta($post->ID, 'payment_status', true);
		if ($payment_status) {
			echo '<span class="orderstatusp orderstatusp-'.strtolower($payment_status).'">'.$payment_status.'</span>';
		}
	}
	
	// order status
	if ($column == 'order_status') {
		$orderstatus = get_post_meta($post->ID, 'order_status', true);
		if ($orderstatus == 'received') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('New Order','ocart').'</span>';
		if ($orderstatus == 'awaiting') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Awaiting Payment','ocart').'</span>';
		if ($orderstatus == 'pending') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Pending','ocart').'</span>';
		if ($orderstatus == 'processing') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Processing','ocart').'</span>';
		if ($orderstatus == 'shipped') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Shipped','ocart').'</span>';
		if ($orderstatus == 'delivered') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Delivered','ocart').'</span>';
		if ($orderstatus == 'declined') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Declined','ocart').'</span>';
		if ($orderstatus == 'cancelled') echo '<span class="orderstatus orderstatus-'.$orderstatus.'">'.__('Cancelled','ocart').'</span>';
	}
	
	// order billing addr.
	if ($column == 'billing') {
		$countries = get_option('occommerce_all_countries');
		$billing = get_post_meta($post->ID, 'order_billing', true);
		echo '<div class="order-custdata">';
			printf(__('<span>Bill To:</span> %s %s','ocart'), $billing[0], $billing[1]);
			echo '<br />';
			printf(__('<span>Address 1:</span> %s','ocart'), $billing[2]);
			echo '<br />';
			printf(__('<span>Address 2:</span> %s','ocart'), $billing[3]);
			echo '<br />';
			printf(__('<span>City/Town:</span> %s','ocart'), $billing[4]);
			echo '<br />';
			printf(__('<span>State/Province:</span> %s','ocart'), $billing[5]);
			echo '<br />';
			printf(__('<span>Postal Code:</span> %s','ocart'), $billing[6]);
			echo '<br />';
			printf(__('<span>Country:</span> %s','ocart'), $countries["$billing[7]"]);
			echo '<br />';
			printf(__('<span>Phone Number:</span> %s','ocart'), $billing[8]);
			echo '<br />';
			printf(__('<span>E-mail Address:</span> <a href="mailto:%1$s">%1$s</a>','ocart'), $billing[9]);
			echo '<br />';
			printf(__('<span>IP Lookup:</span> <a href="#">%s</a>','ocart'), $billing[10]);
		echo '</div>';
	}
	
	// order shipping addr.
	if ($column == 'shipping') {
		$countries = get_option('occommerce_all_countries');
		$shipping = get_post_meta($post->ID, 'order_shipping', true);
		echo '<div class="order-custdata">';
			printf(__('<span>Ship To:</span> %s %s','ocart'), $shipping[0], $shipping[1]);
			echo '<br />';
			printf(__('<span>Address 1:</span> %s','ocart'), $shipping[2]);
			echo '<br />';
			printf(__('<span>Address 2:</span> %s','ocart'), $shipping[3]);
			echo '<br />';
			printf(__('<span>City/Town:</span> %s','ocart'), $shipping[4]);
			echo '<br />';
			printf(__('<span>State/Province:</span> %s','ocart'), $shipping[5]);
			echo '<br />';
			printf(__('<span>Postal Code:</span> %s','ocart'), $shipping[6]);
			echo '<br />';
			printf(__('<span>Country:</span> %s','ocart'), $countries["$shipping[7]"]);
			echo '<br />';
			printf(__('<span>Phone Number:</span> %s','ocart'), $shipping[8]);
		echo '</div>';
	}
	
	// show customer's note
	if ($column == 'customer_note') {
		$note = get_post_meta($post->ID, 'customer_note', true);
		$date = get_post_meta($post->ID, 'custom_delivery_date', true);
		if ($note) {
			echo '<strong>'.__('Special Order Note:','ocart').'</strong>';
			echo '<p>'.$note.'</p>';
		}
		if ($date) {
			echo '<strong>'.__('Requested Delivery Date:','ocart').'</strong>';
			echo '<p>'.$date.'</p>';
		}
	}
	
	// display order summary
	if ($column == 'order_summary') {
		$order_summary = get_post_meta($post->ID, 'order_summary', true);
		$shipping_fee = get_post_meta($post->ID, 'shipping_fee', true);
		$tax_fee = get_post_meta($post->ID, 'order_tax', true);
		$total = get_post_meta($post->ID, 'payment_gross_total', true);
		$coupons = get_post_meta($post->ID, 'order_coupons', true);
		$coupon_values = get_post_meta($post->ID, 'order_coupon_values', true);
		
		foreach($order_summary as $order_c) {
		
			echo '<div class="order-custdata order-sum">';
				printf(__('<span>Product ID:</span> %s','ocart'), $order_c['id']);
				echo '<br />';
				printf(__('<span>Product SKU:</span> %s','ocart'), get_post_meta($order_c['id'], 'sku', true));
				echo '<br />';
				printf(__('<span>Product Name: </span> %s','ocart'), $order_c['name']);
				echo '<br />';
				printf(__('<span>Quantity: </span> %s','ocart'), $order_c['quantity']);
				echo '<br />';
				printf(__('<span>Price: </span> %s','ocart'), ocart_format_currency( ocart_show_price( $order_c['price'] ) ));
				echo '<br />';
				echo '<div class="order-sum-terms">';
				ocart_incart_product_terms($order_c['terms']);
				echo '</div>';
			echo '</div>';
			
		}
		
			echo '<div class="order-custdata order-sum">';
				printf(__('<span>Shipping and handling:</span> %s','ocart'), ocart_format_currency( ocart_show_price( $shipping_fee ) ));
				echo '<br />';
				printf(__('<span>Tax:</span> %s','ocart'), ocart_format_currency( ocart_show_price( $tax_fee ) ));
				echo '<br />';
				if (is_array($coupons)) {
					foreach($coupons as $coupon) {
						printf(__('<span>Coupon (%s): </span> -%s','ocart'), $coupon['code'], ocart_format_currency( ocart_show_price( $coupon_values[$coupon['id']] ) ));
						echo '<br />';
					}
				}
				printf(__('<span>Total:</span> %s','ocart'), ocart_format_currency( ocart_show_price( $total ) ));
				echo '<br />';
			echo '</div>';
			
	}
	
}
add_action('manage_orders_posts_custom_column','render_cols');

/************************************************************
orders dashboard css
************************************************************/
add_action('admin_head', 'orders_dash_css');

function orders_dash_css() {
    echo '<style type="text/css">';
    echo '.column-order_no { width: 90px !important; overflow:hidden }';
	echo '.column-payment, .column-payment_type, .column-order_status { width: 120px !important; overflow: hidden }';
    echo '</style>';
}

/************************************************************
hide 'post' elements like 'add new'
************************************************************/
function hide_add_new_custom_type() {
global $submenu;
unset($submenu['edit.php?post_type=orders'][10]);
}
function hide_buttons() {
      global $pagenow;
      if(is_admin()){
        if($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'orders' || $pagenow == 'post.php' && get_post_type($_GET['post']) == 'orders'){
            echo "<style type=\"text/css\">.add-new-h2{display: none;}</style>";
        }  
      }

}
function permissions_admin_redirect() {
$result = stripos($_SERVER['REQUEST_URI'], 'post-new.php?post_type=orders');
if ($result!==false) {
wp_redirect(get_option('siteurl') . '/wp-admin/index.php?permissions_error=true');
}
}
function permissions_admin_notice() {
// use the class "error" for red notices, and "update" for yellow notices
echo "<div id='permissions-warning' class='error fade'><p><strong>".__('It is not possible to add orders manually.','ocart')."</strong></p></div>";
}
function permissions_show_notice(){
if(isset($_GET['permissions_error'])){
add_action('admin_notices', 'permissions_admin_notice');  
}
}
add_action('admin_menu', 'hide_add_new_custom_type');
add_action('admin_head','hide_buttons');
add_action('admin_menu','permissions_admin_redirect');
add_action('admin_init','permissions_show_notice');

?>