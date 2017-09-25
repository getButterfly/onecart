<?php global $invoice, $ocart;

$countries = get_option('occommerce_all_countries');

$order_summary = get_post_meta($invoice->ID, 'order_summary', true);
$status = get_post_meta($invoice->ID, 'order_status', true);
$payment = get_post_meta($invoice->ID, 'payment_status', true); // store payment status in 'payment_status'
$payment_type = get_post_meta($invoice->ID, 'payment_type', true);
$shipping_fee = get_post_meta($invoice->ID, 'shipping_fee', true);
$tax_fee = get_post_meta($invoice->ID, 'order_tax', true);
$bill = get_post_meta($invoice->ID, 'order_billing', true);
$ship = get_post_meta($invoice->ID, 'order_shipping', true);
$total = get_post_meta($invoice->ID, 'payment_gross_total', true);
$coupons = get_post_meta($invoice->ID, 'order_coupons', true);
$coupon_values = get_post_meta($invoice->ID, 'order_coupon_values', true);

?>

<div class="checkout_process">
	
	<div class="myorders">

		<h2><?php printf(__('Order no. %s','ocart'), $invoice->ID); ?></h2>
		
		<a href="<?php echo remove_query_arg( array ( 'invoiceID' ) ); ?>" class="back"><?php _e('Back to my orders','ocart'); ?></a>
		
		<table class="table-invoice">
			<tr class="lbl">
				<th class="prd"><?php _e('Product','ocart'); ?></th>
				<th class="prc"><?php _e('Price','ocart'); ?></th>
				<th class="qty"><?php _e('Quantity','ocart'); ?></th>
				<th class="sbt"><?php _e('Subtotal','ocart'); ?></th>
			</tr>
			<?php 
			$cart_subtotal = '';
			foreach($order_summary as $item) { $item_subtotal = get_post_meta($item['id'], 'price', true) * $item['quantity']; $cart_subtotal += $item_subtotal; ?>
			<tr class="itm">
				<td><?php echo $item['name']; ?><?php ocart_incart_product_options($item['terms']); ?></td>
				<td><?php ocart_product('plain_price', $item['id']); ?></td>
				<td><?php echo $item['quantity']; ?></td>
				<td><?php echo ocart_format_currency( ocart_show_price($item_subtotal) ); ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="3"><abbr><?php _e('Cart Subtotal','ocart'); ?></abbr></td>
				<td><?php echo ocart_format_currency( ocart_show_price($cart_subtotal) ); ?></td>
			</tr>
			<?php
			if (is_array($coupons)) {
				foreach($coupons as $coupon) {
			?>
			<tr>
				<td colspan="3"><abbr><?php printf(__('Discount Coupon: %s','ocart'), $coupon['code']); ?></abbr></td>
				<td>-<?php echo ocart_format_currency( ocart_show_price( $coupon_values[$coupon['id']] ) ); ?></td>
			</tr>
			<?php
				}
			}
			?>
			<?php if (ocart_get_option('enable_tax')) { ?>
			<tr>
				<td colspan="3"><abbr><?php _e('Tax Fee','ocart'); ?></abbr></td>
				<td><?php echo ocart_format_currency( ocart_show_price( $tax_fee )); ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="3"><abbr><?php _e('Shipping Fee','ocart'); ?></abbr></td>
				<td><?php echo ocart_format_currency( ocart_show_price($shipping_fee) ); ?></td>
			</tr>
			<tr class="grd">
				<td colspan="3"><abbr><?php _e('Grand Total','ocart'); ?></abbr></td>
				<td><ins><?php echo ocart_format_currency( ocart_show_price($total) ); ?></ins></td>
			</tr>
		</table>
		
		<h3><?php _e('Order Status','ocart'); ?></h3>
		<?php
		if ($status == 'received') echo '<span class="status status-'.$status.'">'.__('New Order','ocart').'</span>';
		if ($status == 'awaiting') echo '<span class="status status-'.$status.'">'.__('Awaiting Payment','ocart').'</span>';
		if ($status == 'pending') echo '<span class="status status-'.$status.'">'.__('Pending','ocart').'</span>';
		if ($status == 'processing') echo '<span class="status status-'.$status.'">'.__('Processing','ocart').'</span>';
		if ($status == 'shipped') echo '<span class="status status-'.$status.'">'.__('Shipped','ocart').'</span>';
		if ($status == 'delivered') echo '<span class="status status-'.$status.'">'.__('Delivered','ocart').'</span>';
		if ($status == 'declined') echo '<span class="status status-'.$status.'">'.__('Declined','ocart').'</span>';
		if ($status == 'cancelled') echo '<span class="status status-'.$status.'">'.__('Cancelled','ocart').'</span>';
		?>
		
		<h3><?php _e('Order Payment','ocart'); ?></h3>
		
		<?php
		switch($payment) {
			case 'Completed':
				echo '<p style="color:green;font-weight:bold;">'.__('Payment Completed!','ocart').'</p>';
				break;
			case 'Unpaid':
				echo '<span class="says">'.__('Your order will not be processed until you complete payment. To pay this invoice, select on of the following payment options.','ocart').'</span><p class="radiobox" id="invoice_payment">';
				ocart_payment_options();
				echo '</p>';
				break;
		}
		?>
		
		<div class="billto">
		<h3><?php _e('Bill To','ocart'); ?></h3>
		<p>
			<span class="data"><?php echo $bill[0].' '.$bill[1]; ?></span>
			<span class="data"><?php echo $bill[2]; ?></span>
			<span class="data"><?php echo $bill[4]; ?></span>
			<span class="data"><?php echo $bill[5].', '.$bill[6]; ?></span>
			<span class="data"><?php echo $countries["$bill[7]"]; ?></span>
			<span class="data"><?php echo $bill[8]; ?></span>
		</p>
		</div>
		
		<div class="shipto">
		<h3><?php _e('Ship To','ocart'); ?></h3>
		<p>
			<span class="data"><?php echo $ship[0].' '.$ship[1]; ?></span>
			<span class="data"><?php echo $ship[2]; ?></span>
			<span class="data"><?php echo $ship[4]; ?></span>
			<span class="data"><?php echo $ship[5].', '.$ship[6]; ?></span>
			<span class="data"><?php echo $countries["$ship[7]"]; ?></span>
			<span class="data"><?php echo $ship[8]; ?></span>
		</p>
		</div><div class="clear"></div>
	
	</div>

</div>