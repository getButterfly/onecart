jQuery().ready(function() {  
	
	jQuery('#add_country').click(function() {
		return !jQuery('#select1 option:selected').remove().appendTo('#select2');  
	});  
	jQuery('#remove_country').click(function() {  
		return !jQuery('#select2 option:selected').remove().appendTo('#select1');  
	});
	jQuery('#add_country2').click(function() {
		return !jQuery('#select3 option:selected').remove().appendTo('#select4');  
	});  
	jQuery('#remove_country2').click(function() {  
		return !jQuery('#select4 option:selected').remove().appendTo('#select3');  
	});
	
    jQuery('#cp-form').submit(function() {  
     jQuery('#select2 option').each(function(i) {
      jQuery(this).attr("selected", "selected");  
     }); 
     jQuery('#select4 option').each(function(i) {
      jQuery(this).attr("selected", "selected");  
     });  
    });
	
});  