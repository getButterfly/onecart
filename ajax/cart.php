<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

if(!isset($_SESSION))
{
	session_start();
}

?>

<div class="lightbox lightbox-large">

	<a href="javascript:closeLightbox()" class="close tip" title="<?php _e('Close window','ocart'); ?>"></a>

	<h1><?php _e('Shopping Cart','ocart'); ?></h1>
	
	<table class="thecart">

		<tr>
			<th class="td-item"><?php _e('Item','ocart'); ?></th>
			<th class="td-desc"><?php _e('Description','ocart'); ?></th>
			<th class="td-prix"><?php _e('Price','ocart'); ?></th>
			<th class="td-qnt"><?php _e('Quantity','ocart'); ?></th>
			<th class="td-sub"><?php _e('Subtotal','ocart'); ?></th>
		</tr>
		
		<?php
		if (isset($_SESSION['cart']) && $_SESSION['ocart_cart_count'] > 0) {
		$cart_array = array_reverse($_SESSION['cart']);
		foreach($cart_array as $k => $v) {
			if ( $v['quantity'] > 0 ) {
		?>
		
		<tr id="product-<?php echo $v['id']; ?>">
			<td class="td-item"><a href="<?php echo get_permalink($v['id']); ?>" id="item-thumb-<?php echo $v['id']; ?>"><?php ocart_product('small_thumb', $v['id']); ?></a></td>
			<td class="td-desc">
				<span class="t-productname"><a href="<?php echo get_permalink($v['id']); ?>" id="item-<?php echo $v['id']; ?>"><?php echo $v['name']; ?></a></span>
				<?php ocart_incart_product_options($v['terms']); ?>
			</td>
			<td class="td-prix"><?php echo ocart_format_currency( ocart_show_price($v['price']) ); ?></td>
			<td class="td-qnt">
				<form class="qcontrols">
					<a href="#" class="minus update_q"></a>
					<input type="text" value="<?php echo $v['quantity']; ?>" class="item_quantity" id="session-<?php echo $v['session_id']; ?>" />
					<a href="#" class="plus update_q"></a>
				</form>
			</td>
			<td class="td-sub"><?php echo ocart_format_currency( ocart_show_price($v['price'] * $v['quantity']) ); ?></td>
		</tr>
	
		<?php
				}
			}
		}
		?>
		
		<?php
		/** Action hook to add special tags, lines, purchase notes **/
		do_action('ocart_cart_popup_special');
		?>
	
	</table>
	
	<div class="cart-left">
	
		<?php ocart_calculation_form_pre_cart(); ?>
		
		<form class="coupon" method="post" action="/">
			<input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php _e('Enter coupon code','ocart'); ?>" />
			<input type="submit" value="<?php _e('Apply Coupon','ocart'); ?>" />
			<img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" />
			<div class="clear"></div>
		</form>
	
	</div><!-- end coupon and recalculation -->
	
	<div class="totals">
		<?php if (ocart_get_option('enable_tax')) { ?>
		<div class="calc-tax"><?php _e('Tax','ocart'); ?><span><?php echo ocart_get_tax($_SESSION['zonedata']['fixed_tax'], $_SESSION['zonedata']['pct_tax'], true); ?></span></div>
		<?php } ?>
		<div class="calc-shipping"><?php _e('Shipping & handling','ocart'); ?><span><?php echo ocart_get_shipping($_SESSION['zonedata']['fixed_shipping'], $_SESSION['zonedata']['pct_shipping'], $_SESSION['zonedata']['weight'], $_SESSION['zonedata']['handling'], true); ?></span></div>
		<div id="added_coupons"><?php ocart_print_saved_coupons() ?></div>
		<?php do_action('ocart_cart_popup_price_columns'); ?>
		<div class="calc-total"><?php _e('Total Price','ocart'); ?><span class="shake-total"><?php echo ocart_get_total(true); ?></span></div>
	</div><div class="clear"></div>
	
	<div class="cart-buttons">
		<a href="javascript:closeLightbox()" class="btnstyle2 btn-continue"><?php _e('Continue Shopping','ocart'); ?></a>
		<a href="<?php echo get_permalink( get_page_by_path( 'checkout' ) ); ?>" class="btnstyle1 btn-checkout"><?php _e('Proceed to Checkout','ocart'); ?></a>
	</div>
	
</div>