<div class="checkout_process">
	
	<div class="myorders">

		<h2><?php echo __('Order History','ocart'); ?></h2>
		
		<?php
		$args = array( 'post_type' => 'orders', 'post_status' => 'any', 'numberposts' => -1, 'meta_key' => 'custID', 'meta_value' => $id );
		$posts = get_posts( $args );
		if (count($posts) > 0) {
		?>
		
		<table>
			<tr>
				<th class="hide-320"><?php _e('Placed on','ocart'); ?></th>
				<th><?php _e('Invoice','ocart'); ?></th>
				<th><?php _e('Status','ocart'); ?></th>
				<th><?php _e('Tracking Number','ocart'); ?></th>
			</tr>
		
		<?php foreach ($posts as $post) { setup_postdata($post); ?>
		
			<tr>
				<td class="hide-320"><abbr title="<?php echo get_the_time('d/m/Y h:i:s A', $post->ID ); ?>"><?php echo get_the_time('d/m/Y', $post->ID ); ?></abbr></td>
				<td><a href="<?php echo add_query_arg( array ( 'invoiceID' => $post->ID ) ); ?>"><?php _e('View Invoice','ocart'); ?></a></td>
				<td><?php ocart_order_status() ?></td>
				<td><?php ocart_order_tracking_number() ?></td>
			</tr>

		<?php } ?>

		</table>
		
		<?php } else { ?>
		
		<p><?php _e('You did not place any order yet.','ocart'); ?></p>
		
		<?php } ?>
	
	</div>

</div>