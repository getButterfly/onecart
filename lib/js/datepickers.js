jQuery(document).ready(function(){
	// default state
	if (jQuery('#mark_as_new').is(':checked')) {
		jQuery("#new_start, #new_expiry").removeAttr('disabled');
	} else {
		jQuery("#new_start, #new_expiry").attr('disabled', 'disabled');
	}
	
	// when mark as new is clicked
	jQuery('#mark_as_new').click(function(){
		if (jQuery(this).is(':checked')) {
			jQuery("#new_start, #new_expiry").removeAttr('disabled');
		} else {
			jQuery("#new_start, #new_expiry").attr('disabled', 'disabled');
		}
	});
	
	// pick date in product new mark
	jQuery("#new_start, #new_expiry" ).datepicker({
		dateFormat: "yy-mm-dd",
		minDate: 0
	});
	
	// default stock
	if (jQuery('#status').val() == 'sold') {
		jQuery("#stock").attr('disabled', 'disabled');
	} else {
		jQuery("#stock").removeAttr('disabled');
	}
	
	// when stock status is switched
	jQuery('#status').change(function(){
		if (jQuery(this).val() == 'sold') {
			jQuery("#stock").attr('disabled', 'disabled');
		} else {
			jQuery("#stock").removeAttr('disabled');
		}
	});

});