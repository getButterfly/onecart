<?php global $ocart; ?>

<div class="checkout_summary" id="checkout_summary">

	<h2><?php _e('Purchase Summary','ocart'); ?></h2>
	
	<ul>
	
	<?php $cart_array = array_reverse($_SESSION['cart']);
	foreach($cart_array as $k => $v) {
		if ($v['quantity'] > 0) {
	?>
	
		<li id="item-<?php echo $v['id']; ?>">
			<div class="remove" id="session-<?php echo $v['session_id']; ?>" rel="quantity-<?php echo $v['quantity']; ?>"></div>
			<div class="subtotal"><?php echo ocart_format_currency( ocart_show_price($v['price'] * $v['quantity']) ); ?></div>
			<div class="thumb"><a href="<?php echo get_permalink($v['id']); ?>"><?php ocart_product('small_thumb', $v['id']); ?></a></div>
			<div class="info">
				<h3><a href="<?php echo get_permalink($v['id']); ?>"><?php echo $v['name']; ?></a></h3>
				<?php ocart_incart_product_terms($v['terms']); ?>
				<span><?php printf(__('Qty:<span>%s</span>','ocart'), $v['quantity']); ?></span>
			</div>
			<div class="clear"></div>
		</li>
	
	<?php }
	} ?>
	
	</ul>
	
	<?php if (ocart_get_option('enable_tax')) { ?>
	<div class="checkout_est checkout_est_tax"><?php _e('Estimated Tax','ocart'); ?><span><?php echo ocart_get_tax($_SESSION['zonedata']['fixed_tax'], $_SESSION['zonedata']['pct_tax'], true); ?></span></div>
	<?php } ?>
	<div class="checkout_est"><?php _e('Shipping & Handling','ocart'); ?><span><?php echo '<ins id="shipping_fee">'.ocart_get_shipping($_SESSION['zonedata']['fixed_shipping'], $_SESSION['zonedata']['pct_shipping'], $_SESSION['zonedata']['weight'], $_SESSION['zonedata']['handling'], true).'</ins>'; ?></span></div>
	<?php ocart_show_coupons_in_cart(); ?>
	<div class="checkout_total"><?php _e('Total','ocart'); ?><span><?php echo '<ins id="order_total">'.ocart_get_total(true).'</ins>'; ?></span></div>
	
</div>