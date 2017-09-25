jQuery(function($) {

	// drag and drop products
	$('#sortable-table tbody').sortable({
		axis: 'y',
		handle: '.column-order img',
		placeholder: 'ui-state-highlight',
		forcePlaceholderSize: true,
		update: function(event, ui) {
			var theOrder = $(this).sortable('toArray');

			var data = {
				action: 'sneek_update_post_order',
				postType: $(this).attr('data-post-type'),
				order: theOrder
			};

			$.post(ajaxurl, data);
		}
	}).disableSelection();
	
	// sortable checkboxes
	$('.sortable-attributes').children('label').append('<span class="handle"></span>');
	$('.sortable-attributes').sortable({
		axis: 'y',
		handle: '.handle',
		placeholder: 'ui-state-highlight',
		forcePlaceholderSize: true,
		update: function(event, ui) {
		}
	}).disableSelection();
	
	// country_select
	$('.country_select').change(function() {
		val = $(this).val();
		actual_val = $(this).parent().next('.subfield').find('textarea').val();
		if (actual_val === '') {
		$(this).parent().next('.subfield').find('textarea').val(val);
		} else {
		$(this).parent().next('.subfield').find('textarea').val(actual_val + ', ' + val);
		}
	});
	
	$('.toggle-db-mb-closed').each(function(){
		var parentbox = $(this).parent().parent();
		parentbox.find('.dashboard-metabox-body').fadeOut();
		$(this).parent().css({'border-radius' : '3px'});
	});
	
	// toggle dashboard boxes
	$('.toggle-db-mb').click(function(){
		var parentbox = $(this).parent().parent();
		if (parentbox.find('.dashboard-metabox-body').is(':visible')) {
			parentbox.find('.dashboard-metabox-body').hide();
			$(this).parent().css({'border-radius' : '3px'});
			$(this).addClass('toggle-db-mb-closed');
		} else {
			parentbox.find('.dashboard-metabox-body').show();
			$(this).parent().css({'border-radius' : '3px 3px 0 0'});
			$(this).removeClass('toggle-db-mb-closed');
		}
	});

});