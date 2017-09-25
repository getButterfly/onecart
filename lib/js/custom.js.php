<script type="text/javascript">
jQuery(document).ready(function(){
	
	// update cost
	jQuery('#add_cost').click(function(e){
		e.preventDefault();
		add_cost = jQuery(this);
		add_cost.next('span').remove();
		jQuery(this).after('<img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" />');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/lib/ajax/add_cost.php',
			type: 'get',
			dataType: 'json',
			data: {count: jQuery('#add_cost_count').val(), cost: jQuery('#add_cost_price').val(), post_id: '<?php echo $post->ID; ?>'},
			success: function(data){
				add_cost.next('img').remove();
				if (data.error) {
					add_cost.after(data.error);
				}
				if (data.success) {
					add_cost.after(data.success);
					jQuery('#CustomRates').append('<span class="subcost current-rate"><?php _e('Cost per','ocart'); ?><input type="text" disabled="disabled" value="' + jQuery('#add_cost_count').val() + '" /><?php _e('item(s)','ocart'); ?><input type="text" disabled="disabled" value="' + jQuery('#add_cost_price').val() + '" /><?php echo $ocart['currency']; ?><a href="" class="button-secondary delete_cost" rel="cost_per_' + jQuery('#add_cost_count').val() + '"><?php _e('Delete Cost','ocart'); ?></a></span><div class="clear"></div>');
					jQuery('#add_cost').parent().children('input[type=text]').val('');
				}
			}
		});
	});
	
	// delete cost
	jQuery('.delete_cost').live('click',function(e){
		e.preventDefault();
		delete_cost = jQuery(this);
		delete_cost.next('span').remove();
		jQuery(this).after('<img src="<?php echo admin_url(); ?>/images/wpspin_light.gif" />');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/lib/ajax/delete_cost.php',
			type: 'get',
			dataType: 'json',
			data: {field: delete_cost.attr('rel'), post_id: '<?php echo $post->ID; ?>'},
			success: function(data){
				delete_cost.next('img').remove();
				if (data.success) {
					delete_cost.after(data.success);
					delete_cost.parent().fadeOut();
				}
			}
		});
	});

});
</script>