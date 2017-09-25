jQuery(document).ready(function(){
	
	// dashboard cookies/tab
	jQuery('.dashboard_body').css({display: 'none'});
	if (jQuery.cookies.get('active_tab')) {
		jQuery('#'+jQuery.cookies.get('active_tab')).show();
		jQuery('.dashboard_tabs a[rel=' + jQuery.cookies.get('active_tab')  + ']').addClass('current');
	} else {
		jQuery('.dashboard_tabs a:first').addClass('current');
		var rel = jQuery('.dashboard_tabs a.current').attr('rel');
		jQuery('#'+rel).show();
	}
	jQuery('.dashboard_tabs a').click(function(){
		var rel = jQuery(this).attr('rel');
		jQuery.cookies.set( 'active_tab', rel );
		jQuery('.dashboard_tabs a').removeClass('current');
		jQuery('.dashboard_body').hide();
		jQuery(this).addClass('current');
		jQuery('#'+rel).show();
	});
	
	// auto submit
	jQuery('#skin').change(function() {
		jQuery('#save').click();
	});
	
	// colorpicker
	jQuery('.color1').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color1 div').css('backgroundColor', '#' + hex);
			jQuery('.color1').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color2').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color2 div').css('backgroundColor', '#' + hex);
			jQuery('.color2').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color3').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color3 div').css('backgroundColor', '#' + hex);
			jQuery('.color3').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color4').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color4 div').css('backgroundColor', '#' + hex);
			jQuery('.color4').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color5').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color5 div').css('backgroundColor', '#' + hex);
			jQuery('.color5').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color6').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color6 div').css('backgroundColor', '#' + hex);
			jQuery('.color6').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color7').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color7 div').css('backgroundColor', '#' + hex);
			jQuery('.color7').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color8').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color8 div').css('backgroundColor', '#' + hex);
			jQuery('.color8').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color9').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color9 div').css('backgroundColor', '#' + hex);
			jQuery('.color9').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color10').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color10 div').css('backgroundColor', '#' + hex);
			jQuery('.color10').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color11').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color11 div').css('backgroundColor', '#' + hex);
			jQuery('.color11').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color12').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color12 div').css('backgroundColor', '#' + hex);
			jQuery('.color12').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color13').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color13 div').css('backgroundColor', '#' + hex);
			jQuery('.color13').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color14').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color14 div').css('backgroundColor', '#' + hex);
			jQuery('.color14').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color15').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color15 div').css('backgroundColor', '#' + hex);
			jQuery('.color15').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color16').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color16 div').css('backgroundColor', '#' + hex);
			jQuery('.color16').parent().children('input[type=hidden]').val('#' + hex);
		}
	});

	// colorpicker
	jQuery('.color17').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color17 div').css('backgroundColor', '#' + hex);
			jQuery('.color17').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color18').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color18 div').css('backgroundColor', '#' + hex);
			jQuery('.color18').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color19').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color19 div').css('backgroundColor', '#' + hex);
			jQuery('.color19').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color20').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color20 div').css('backgroundColor', '#' + hex);
			jQuery('.color20').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color21').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color21 div').css('backgroundColor', '#' + hex);
			jQuery('.color21').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color22').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color22 div').css('backgroundColor', '#' + hex);
			jQuery('.color22').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color23').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color23 div').css('backgroundColor', '#' + hex);
			jQuery('.color23').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color24').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color24 div').css('backgroundColor', '#' + hex);
			jQuery('.color24').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color25').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color25 div').css('backgroundColor', '#' + hex);
			jQuery('.color25').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color26').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color26 div').css('backgroundColor', '#' + hex);
			jQuery('.color26').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color27').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color27 div').css('backgroundColor', '#' + hex);
			jQuery('.color27').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color28').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color28 div').css('backgroundColor', '#' + hex);
			jQuery('.color28').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color29').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color29 div').css('backgroundColor', '#' + hex);
			jQuery('.color29').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color30').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color30 div').css('backgroundColor', '#' + hex);
			jQuery('.color30').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color31').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color31 div').css('backgroundColor', '#' + hex);
			jQuery('.color31').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color32').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color32 div').css('backgroundColor', '#' + hex);
			jQuery('.color32').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color33').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color33 div').css('backgroundColor', '#' + hex);
			jQuery('.color33').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color34').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color34 div').css('backgroundColor', '#' + hex);
			jQuery('.color34').parent().children('input[type=hidden]').val('#' + hex);
		}
	});
	
	// colorpicker
	jQuery('.color35').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('.color35 div').css('backgroundColor', '#' + hex);
			jQuery('.color35').parent().children('input[type=hidden]').val('#' + hex);
		}
	});

});