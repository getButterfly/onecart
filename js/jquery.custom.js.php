<?php global $ocart; ?>

<script type="text/javascript">
function getProductAjaxRequest(){
	jQuery.ajax({
		url: '<?php echo get_template_directory_uri(); ?>/ajax/product.php',
		type: 'get',
		data: {product_id : product_id},
		beforeSend: function(){
			jQuery('#details').css({opacity: 1, left: 0});
			jQuery('#details').html('<div class="product product-load"></div>');
			deviceWidth = jQuery(window).width();
			if (deviceWidth <= 977) {
				jQuery('#banner, #details').css({'height':'800px'});
			}
			jQuery.scrollTo('#header', 800);
		},
		error: function(x, textStatus, m) {
			if (textStatus=="timeout") {
				getProductAjaxRequest();
			}
		},
		success: function(data){
			jQuery('.iosSlider').fadeOut('fast');
			jQuery('.prods li[id=item-' + product_id + ']').addClass('current');
			jQuery('#details').html(data);
			jQuery('.navi li:last').remove();
			jQuery('input[type="text"]').not('#min_price, #max_price').clearOnFocus();
			if (deviceWidth > 800) {
				jQuery('.btn-quantity').tipsy({
					trigger: 'focus',
					gravity: 'w',
					offset: 18
				});
			}
			if (deviceWidth > 800) {
				jQuery('.tip').tipsy({
					delayIn: 200,
					gravity: 'n',
					offset: 8
				});
			}
			jQuery('.optionprice').tipsy({
				trigger: 'hover',
				gravity: 'w',
				offset: 4
			});
			// reinstate carousel
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(reinitCarousel, 100);
			jQuery('.main-image .zoom:first').fadeIn(800, function(){
				if (deviceWidth > 766) {
				jQuery(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
			});zz
			jQuery('.thumbs a, .thumbs2 a').click(function(){
				var rel = jQuery(this).attr('rel');
				var currentID = jQuery('.main-image .zoom:visible').attr('id');
				if (rel !== currentID && rel != 'video') {
					jQuery('.main-image .zoom').fadeOut(800);
					jQuery(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
						if (deviceWidth > 766) {
						jQuery(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
						}
					});
				}
			});
		}
	});
	return false;
}

// function to re-init carousel
function reinitCarousel() {

	deviceWidth = jQuery(window).width();
	if (deviceWidth <= 977) {
		jQuery('.product-img').css({'width': jQuery('#details').width() });
	} else {
		catalogver = '<?php echo ocart_catalog_version() ?>';
		force_lightbox = '<?php echo ocart_get_option('force_lightbox'); ?>';
		if (jQuery('#lightbox').length == 0) { // if we are not using lightbox ver
		jQuery('.product-img').css({'width': '490px'});
		}
		jQuery('.thumbs').css({'position': 'absolute', 'top': 0, 'right': '36px'});
	}
	
    jQuery('.thumbs').trigger('destroy');
	if (deviceWidth > 480) {
	jQuery('.thumbs').show().carouFredSel({
				width: 'auto',
				height: 406,
				scroll: 1,
				items: 3,
				auto: false,
				direction: "down",
				prev: ".upImage",
				next: ".dnImage"
	});
	}
	
	// thumbs v2
	jQuery('.thumbs2').show().carouFredSel({
				width: '100%',
				height: 'auto',
				align: 'left',
				scroll: 1,
				items: 5,
				auto: false,
				direction: "right",
				prev: ".prevImage2",
				next: ".nextImage2"
	});
	
	jQuery('.prods').carouFredSel({
			width: 977,
			height: <?php echo ocart_get_option('catalog_image_height') + 100; ?>,
			scroll: 1,
			align: "left",
			auto: false,
			direction: "right",
			prev: {
				button: '.prevItem',
				onBefore: function(){
						jQuery('.prods li').removeClass('viewport');
						jQuery('.prevproduct').stop().animate({left: 0});
						jQuery('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var next_item_id = jQuery('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
							jQuery('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
								jQuery('.prevproduct').hide().stop().animate({left: '-200px'});
							});
						});
				},
				onAfter: function(){
						jQuery('.prods li').removeClass('viewport');
						jQuery('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var $img = jQuery('.prods li.viewport:first').last(),
								$prev = $img.prev();
							if (0==$prev.length) {
								$prev = $img.siblings().last();
							}
							var prev_item_id = $prev.attr('id').replace(/[^0-9]/g, '');
							jQuery('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
								jQuery('.prevproduct').show();
							});
						});
				}
			},
			next: {
				button: '.nextItem',
				onBefore: function(){
						jQuery('.prods li').removeClass('viewport');
						jQuery('.nextproduct').stop().animate({right: 0});
						jQuery('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var prev_item_id = jQuery('.prods li.viewport:first').attr('id').replace(/[^0-9]/g, '');
							jQuery('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
								jQuery('.nextproduct').hide().stop().animate({right: '-200px'});
							});
						});
				},
				onAfter: function(){
						jQuery('.prods li').removeClass('viewport');
						jQuery('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var next_item_id = jQuery('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
							jQuery('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
								jQuery('.nextproduct').show();
							});
						});
				}
			}
	});

};

var resizeTimer;

// window resizing
jQuery(window).resize(function(){
	
	// detect banner/details height
	deviceWidth = jQuery(window).width();
	if (deviceWidth <= 977) {
		if (jQuery('.iosSlider').is(':hidden')) { // only when product is active
			jQuery('#details, #banner').css({'height':'800px'});
		}
	} else {
		if (jQuery('#details .product').is(':visible')) {
			jQuery('#details, #banner').css({'height':'406px'});
		}
	}
	
	// reinstate carousel
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(reinitCarousel, 100);
	
});

// custom scripting starts here
jQuery(function() {

	// get device width
	deviceWidth = jQuery(window).width();
	
	// reinstate carousel
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(reinitCarousel, 100);

	// sticky footer code
	function positionFooter(){
		if(jQuery(document).height() == jQuery(window).height()){
			jQuery("#footer").css({position: "absolute",top:(jQuery(window).scrollTop()+jQuery(window).height()-jQuery("#footer").height())-1+"px"});
		}
	}
	positionFooter(); 
	jQuery(window).scroll(positionFooter).resize(positionFooter);
	
	// sticky sidebar
	if (!!jQuery('#checkout_summary').offset()) { // make sure ".sticky" element exists
 
    var stickyTop = jQuery('#checkout_summary').offset().top; // returns number
 
    jQuery(window).scroll(function(){ // scroll event
 
      var windowTop = jQuery(window).scrollTop(); // returns number
 
      if (stickyTop < windowTop){
        jQuery('#checkout_summary').css({ position: 'fixed', top: 0 });
      }
      else {
        jQuery('#checkout_summary').css('position','static');
      }
 
    });
	
	}

	// close filter automatically
	jQuery(document).click(function () {
		if (jQuery('#filter-by').is(':visible')) {
			jQuery('#filter-by').fadeOut();
			jQuery('#filter').css({'background-position' : '0 0'});
		}
		jQuery('.cartpopup').fadeOut('fast', function(){
		});
		if (jQuery('.ajax-search-results').is(':visible')) {
			jQuery('.ajax-search-results').hide();
			jQuery('#productSearch').stop().animate({'width':'100px'}, 600);
		} else {
			jQuery('#productSearch').stop().animate({'width':'100px'}, 600);
		}
	});
	jQuery('#filter-by, #filter, .cart, .ajax-search').live('click',function (e) {
        e.stopPropagation();
	});

	// clear field on focus
	jQuery('input[type="text"]').not('#min_price, #max_price').clearOnFocus();

	// animate top links
    jQuery('#toplinks a').mouseenter(function() {
		jQuery(this).stop(true, true).animate({backgroundColor:'<?php ocart_skin_data('active_color') ?>'},300);
	}).mouseleave(function() {
		jQuery(this).stop(true, true).animate({backgroundColor:'#fff'}, 1);
    });
	
	// animate list choices
    jQuery('.list a').live('mouseenter',function() {
		if (jQuery(this).hasClass('current') == false) {
		jQuery(this).stop().animate({color:'<?php ocart_skin_data('nav_hover_color') ?>'},600);
		}
	}).live('mouseleave',function() {
		if (jQuery(this).hasClass('current') == false) {
		jQuery(this).stop().animate({color:'<?php ocart_skin_data('nav_color') ?>'},100);
		}
    });
	
	// animate blog categories
    jQuery('.blog_nav a').live('mouseenter',function() {
		if (jQuery(this).parent().hasClass('current-cat') == false) {
		jQuery(this).stop().animate({color:'<?php ocart_skin_data('nav_hover_color') ?>'},600);
		}
	}).live('mouseleave',function() {
		if (jQuery(this).parent().hasClass('current-cat') == false) {
		jQuery(this).stop().animate({color:'<?php ocart_skin_data('nav_color') ?>'},100);
		}
    });
	jQuery('.current-cat a').append('<span />');

	// toggle current choice
    jQuery('.list a').live('click',function(e) {
	
		e.preventDefault();
		
		// animation
		jQuery('.list a').css({'color':'<?php ocart_skin_data('nav_color') ?>'});
		jQuery('.list a').removeClass('current');
		jQuery('.list a').children('span').remove();
		jQuery(this).addClass('current');
		jQuery(this).append('<span></span>');
		jQuery(this).stop().animate({color:'<?php ocart_skin_data('active_color') ?>'}, {duration:1, complete: function(){
			jQuery(this).effect("bounce", { direction: 'left', distance:10, times:3 }, 400);
		}
		});
		
		catalogver = '<?php echo ocart_catalog_version() ?>';
		
		// v1
		if (catalogver == 1) {
		var taxonomy = jQuery(this).attr('id');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				jQuery('.catalogWrapper').html(data);
				jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
		}
		
		// v2
		if (catalogver == 2) {
		// reset filters
		jQuery('.filter ul a').removeClass('selected');
		jQuery('.filter ul a#' + jQuery(this).attr('id')).addClass('selected');
		var taxonomies = jQuery(this).attr('id');
			pricemin = jQuery('.text_min ins').html();
			pricemax = jQuery('.text_max ins').html();
		jQuery('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		jQuery('.catalog').css({opacity: 0.5});
		jQuery.ajax({
			type: 'post',
			url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
			data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
			success: function(res) {
				jQuery(".catalog_list").html(res);
				jQuery('.catalog').css({opacity: 1});
			}
		});
		}
		
    });
	
	// animate next/prev
	jQuery('.next').live('mouseenter',function(){ 
		jQuery(this).stop().animate({backgroundPosition:"(-21px -20px)"}, 300)
	}).live('mouseleave',function(){
		jQuery(this).stop().animate({backgroundPosition:"(-21px 0)"},300) });
	jQuery('.prev').live('mouseenter',function(){
		jQuery(this).stop().animate({backgroundPosition:"(0 -20px)"}, 300)
	}).live('mouseleave',function(){
		jQuery(this).stop().animate({backgroundPosition:"(0 0)"}, 300)
	});
	
	// animate option links
	jQuery('.options a').live('mouseenter',function(){
		jQuery(this).stop().animate({backgroundColor:'#fbfbfb','padding-left':'30px'}, 200);
	}).live('mouseleave',function(){
		jQuery(this).stop().animate({backgroundColor:'#fff','padding-left':'20px'}, 100);
	});
	
	// toggle sorting options
	jQuery('.options').css({'display': 'none'});
	jQuery('.options a:last').css({'border-bottom': 'none'});
	jQuery('.sort').live('mouseenter',function(){
		jQuery('.options').stop(true, true).slideDown('slow');
		jQuery(this).children('.sort-link').stop().animate({backgroundPosition: "right bottom"},500);
		jQuery(this).children('.sort-link').addClass('current');
	}).live('mouseleave',function(){
		jQuery('.options').fadeOut('fast');
		jQuery(this).children('.sort-link').stop().animate({backgroundPosition: "right top"},300);
		jQuery(this).children('.sort-link').removeClass('current');
	});
	
	// toggle languages
	jQuery('.switchbar-inner ul ul').css({'display': 'none'});
	jQuery('.switchbar-inner li').live('mouseenter',function(){
		jQuery(this).find('ul').stop(true, true).slideDown();
	});
	jQuery('.switchbar-inner').live('mouseleave',function(){
		jQuery('.switchbar-inner ul ul').slideUp();
	});
	
	// switch parent categories
	jQuery('.options a').live('click',function(){
		var taxonomy = jQuery(this).attr('id');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/categories.php',
			type: 'get',
			data: {taxonomy : taxonomy},
			beforeSend: function(){
				jQuery('.list').empty();
			},
			success: function(data){
				jQuery('#browser').html(data);
			}
		});
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				jQuery('.catalogWrapper').html(data);
				jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	});
	
	// switch "select"
	jQuery('#nav select').live('change',function(){
		var select = jQuery(this);
		var taxonomy = select.val();
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				jQuery('.catalogWrapper').html(data);
				jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	});

	/* main slideshow */
	<?php if (ocart_get_option('enable_slideshow')) { ?>
	jQuery('.iosSlider').iosSlider({
		autoSlide: true,
		desktopClickDrag: true,
		snapToChildren: true,
		infiniteSlider: true,
		navSlideSelector: jQuery('.iosSlider_buttons .button'),
		navPrevSelector: jQuery('.prevButton'),
		navNextSelector: jQuery('.nextButton'),
		onSlideChange: slideContentChange,
		onSlideComplete: slideContentComplete,
		onSliderLoaded: slideContentLoaded
	});
	<?php } ?>
	
	function slideContentChange(args) {
		/* indicator */
		jQuery(args.sliderObject).parent().find('.iosSlider_buttons .button').removeClass('selected');
		jQuery(args.sliderObject).parent().find('.iosSlider_buttons .button:eq(' + args.currentSlideNumber + ')').addClass('selected');
	}

	function slideContentComplete(args) {

		/* animation */
		jQuery(args.sliderObject).find('.text1, .text2, .text4, .text6').attr('style', '');
		jQuery(args.currentSlideObject).children('.text1').animate({
			left: '500px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		jQuery(args.currentSlideObject).children('.text2').delay(600).animate({
			top: '140px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		jQuery(args.currentSlideObject).children('.text4').delay(300).animate({
			left: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');
		jQuery(args.currentSlideObject).children('.text6').delay(300).animate({
			right: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');
	}

	function slideContentLoaded(args) {
	
		/* animation */
		jQuery(args.sliderObject).find('.text1, .text2, .text4, .text6').attr('style', '');
		jQuery(args.currentSlideObject).children('.text1').animate({
			left: '500px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		jQuery(args.currentSlideObject).children('.text2').delay(600).animate({
			top: '140px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		jQuery(args.currentSlideObject).children('.text4').delay(300).animate({
			left: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');
		jQuery(args.currentSlideObject).children('.text6').delay(300).animate({
			right: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');

		/* indicator */
		jQuery(args.sliderObject).parent().find('.iosSlider_buttons .button').removeClass('selected');
		jQuery(args.sliderObject).parent().find('.iosSlider_buttons .button:eq(' + args.currentSlideNumber + ')').addClass('selected');

	}
	
	// animate button [1]
    jQuery('.button1').mouseover(function() {
		jQuery(this).stop().animate({opacity: 1,backgroundColor:'#fff'},300);
	}).mouseout(function() {
		jQuery(this).stop().animate({opacity: 0.90,backgroundColor:'<?php ocart_skin_data('active_color') ?>'},300);
    });
	
	// animate product in catalog
    jQuery('.prods li').live('mouseenter',function() {
		jQuery(this).children('.label').stop(true,true).animate({top: '80%',opacity: 1}, 800, 'easeOutQuint');
		if (jQuery(this).children('.producthover').length) {
			jQuery(this).children('.producthover').stop(true,true).fadeIn();
		}
	}).live('mouseleave',function() {
		jQuery(this).children('.label').stop().animate({top: '50%',opacity: 0}, 800, 'easeOutQuint');
		if (jQuery(this).children('.producthover').length) {
			jQuery(this).children('.producthover').stop(true,true).hide();
		}
    });
	
	// animate small cart
	jQuery('.items li').live('mouseenter',function(){
		jQuery(this).children('.remove').show();
		jQuery(this).stop().animate({backgroundColor:'#f9f9f9'}, 500);
	}).live('mouseleave',function(){
		jQuery(this).children('.remove').hide();
		jQuery(this).stop().animate({backgroundColor:'#fff'}, 1);
	});
	
	// tooltips
	if (deviceWidth > 800) {
		jQuery('.items li div.remove').tipsy({
			fade: true,
			fallback: '<?php _e('Remove from cart','ocart'); ?>',
			gravity: 'w',
			offset: 4
		});
		jQuery('.tagcloud a').tipsy({
			fade: true,
			gravity: 'n',
			offset: 4
		});
		jQuery('.checkout_form_text').tipsy({
			gravity: 'w',
			trigger: 'focus',
			offset: 8
		});
	}
	
	// remove icon effect
	jQuery('.remove').live('mouseenter',function(){ jQuery(this).css({'background-position':'bottom'}); }).live('mouseout',function(){ jQuery(this).css({'background-position':'top'}); });
	
	// remove item from smallcart
	jQuery('.items li div.remove').live('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		var what_to_remove = jQuery(this).parent();
		var session_id = jQuery(this).attr('id').replace(/[^0-9]/g, '');
		var item_id = jQuery(this).parent().attr('id').replace(/[^0-9]/g, '');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/remove_item.php',
			type: 'get',
			data: {session_id: session_id},
			beforeSend: function(){
				what_to_remove.after('<li></li>');
				what_to_remove.fadeOut();
			},
			success: function(data){
				jQuery('.cartpopup-inner').html(data);
				jQuery('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
				jQuery('.tipsy').hide();
				
				jQuery.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/update_rt_quantity.php?item_id=' + item_id,
					dataType: 'json',
					success: function(data){
						jQuery('.product').attr('name', 'qty-' + data.new_product_quantity);
						jQuery.each(data, function(key, val) {
							jQuery('.product-tax').find('a[data-termID=' + val.term + ']').attr('name', 'qty-' + val.qty);
						});
					}
				});

			}
		});
	});
	
	// toggle the small cart view
	jQuery('.cartpopup').css({'display':'none'});
	jQuery('.cart-link').bind('mouseenter',function(){
		jQuery('.cartpopup').stop(true, true).show().animate({top: 100}, 200).animate({top: 44}, 500);
	});
	
	// open product details
	jQuery('.items li, #similar ul li a, .ajax-search-results a, .t-productname a, .td-item a').live('click',function(e){
		e.preventDefault();
		closeLightbox(id='');
		jQuery('.tipsy').hide();
		jQuery('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
			jQuery('#similar').hide();
			jQuery('#similar .similarWrap').empty();
		}});
		product_id = jQuery(this).attr('id').replace(/[^0-9]/g, '');
		jQuery('.iosSlider').fadeOut('fast');
		getProductAjaxRequest();
	});
	
	force_lightbox = '<?php echo ocart_get_option('force_lightbox'); ?>';
	if (force_lightbox == 0) {
	// open product inline
	jQuery('.prods li').live('click',function(e){
		e.preventDefault();
		closeLightbox(id='');
		jQuery('.tipsy').hide();
		jQuery('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
			jQuery('#similar').hide();
			jQuery('#similar .similarWrap').empty();
		}});
		product_id = jQuery(this).attr('id').replace(/[^0-9]/g, '');
		jQuery('.iosSlider').fadeOut('fast');
		getProductAjaxRequest();
	});
	} else {
	// opening product in lightbox
	jQuery('.prods li').live('click',function(e){
		e.preventDefault();
		product_id = jQuery(this).attr('id').replace(/[^0-9]/g, '');
		lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', product_id);
	});
	}
	
	// change link by opening product
	jQuery('.prods li').live('click',function(e){
		e.preventDefault();
		link = jQuery(this).attr('rel');
		window.history.pushState(null,null, link);
	});
	
	// change location
	jQuery('.items li').live('click',function(e){
		if (jQuery('#blog').length > 0) {
			href = jQuery(this).attr('rel');
			document.location.href=href;
		}
	});
	
	// close product detail
	jQuery('#closeProductdetail').live('click',function(e){
	
		jQuery('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
			jQuery('#similar').hide();
			jQuery('#similar .similarWrap').empty();
		}});

		jQuery('#details').stop().animate({left: '-200%', opacity: 0}, {duration: 1200, complete:function(){
			jQuery('.iosSlider').fadeIn('slow');
			jQuery('#banner').css({'height': jQuery('.iosSlider').height() });
		}});
		
	});
	
	// back to home
	jQuery('.navi-home, #logo a').live('click',function(e){
	
	jQuery('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
		jQuery('#similar').hide();
		jQuery('#similar .similarWrap').empty();
	}});
	
	if (jQuery('#blog').length == 0) {
	
		e.preventDefault();
		if (jQuery('.iosSlider').is(':visible')) {
			jQuery('.iosSlider').effect("bounce", { times:3, distance: 10 }, 200);
		} else {
			jQuery('#details').stop().animate({left: '-200%', opacity: 0}, {duration: 1200, complete:function(){
				jQuery('.iosSlider').fadeIn('slow');
				jQuery('#banner, #details').css({'height': jQuery('.iosSlider').height() });
			}});
		}
		
		// default product list
		jQuery('#filter').css({'background-position' : '0 0'});
		jQuery('#filter-by').hide();
		jQuery('#browser').load('<?php echo get_template_directory_uri(); ?>/ajax/categories.php');
		jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
		jQuery('.catalogWrapper').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog.php');
		
	}
		
	});
	
	// product term selection
	jQuery('.product-color a').live('click',function(e){
		e.preventDefault();
		jQuery('.product-color li').removeClass('current');
		jQuery(this).parent().addClass('current');
	});
	jQuery('.product-tax a').live('click',function(e){
		e.preventDefault();
		jQuery(this).parent().parent().parent().find('a').removeClass('current');
		jQuery(this).addClass('current');
	});
	
	// validate quantity change
	jQuery('.btn-quantity').live('change keydown keyup', function(e) {
		var re = /^[1-9]\d*$/;
		var str = jQuery(this).val();
		if (!re.test(str)){ jQuery(this).val(''); }
	});
	
	// add to cart button
	jQuery('.addtocart').live('submit',function(e){
	
		// remove alerts
		e.preventDefault();
		jQuery('.no-options-selected').remove();
		
		// quantity chosen
		var re = /^[1-9]\d*$/;
		var str = jQuery('.btn-quantity').val();
		if (re.test(str)){
			var quantity = parseInt(str);
		} else {
			var quantity = 1;
		}
		
		// check stock quantity levels
		var prod_stock = jQuery('.product').attr('name').replace(/[^0-9]/g, '');
		if (quantity > prod_stock) {
		
			jQuery('.product-var').effect("shake", { times:3, distance: 5 }, 100);
			jQuery('.addtocart').append('<span class="no-options-selected">' + prod_stock + ' <?php _e('item(s) are left only.','ocart'); ?></span>');
			jQuery('.no-options-selected').stop().animate({opacity: 1});
			setTimeout(function(){
				jQuery('.no-options-selected').fadeOut('slow');
			}, 2000);
			
		} else {
		
		// make sure that customer checked product options
		var attr_select = '<?php echo ocart_get_option('attr_select'); ?>';
		
		if (attr_select == 0) {
		
		if(jQuery('.product-tax li.current > a, .product-tax li > a.current').size() != jQuery('.product-tax').not('.do-not-count').size()) {
			
			jQuery('.product-var').effect("shake", { times:3, distance: 5 }, 100);
			jQuery('.addtocart').append('<span class="no-options-selected"><?php _e('Please select product options first.','ocart'); ?></span>');
			jQuery('.no-options-selected').stop().animate({opacity: 1});
			setTimeout(function(){
				jQuery('.no-options-selected').fadeOut('slow');
			}, 2000);
		
		} else {
		
		// check now for custom stock levels if found
		canAddglobally = true;
		canAddtoCart = true;
		jQuery('.product-tax li.current > a, .product-tax li > a.current').each(function(i, val){
			var opt_qty = jQuery(this).attr('name').replace(/[^0-9]/g, '');
			if (quantity > opt_qty) {
				jQuery(this).parent().effect("shake", { times:3, distance: 5 }, 100);
				jQuery('.addtocart').append('<span class="no-options-selected">' + opt_qty + ' <?php _e('item(s) are left only.','ocart'); ?></span>');
				jQuery('.no-options-selected').stop().animate({opacity: 1});
				setTimeout(function(){
					jQuery('.no-options-selected').fadeOut('slow');
				}, 2000);
				canAddtoCart = false;
				canAddglobally = false;
			} else {
				canAddtoCart = true;
			}
		});
		
		if (canAddtoCart === true && canAddglobally !== false) {
		
		jQuery.scrollTo('#header', 800);
		
		// variables
		var item_id = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
		var item_name = jQuery('.product-name').html();
		var terms = '';
		jQuery('.product-tax li.current > a, .product-tax li > a.current').each(function(){
			terms = terms + jQuery(this).attr("id") + ":";
		});

		// now user can add item to cart!
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/add_item.php',
			type: 'get',
			data: {item_id: item_id, quantity: quantity, item_name: item_name, terms: terms},
			beforeSend: function(){
				jQuery('.btn-add').attr('disabled', 'disabled');
				jQuery('.cartpopup').hide();
			},
			success: function(data){
				jQuery('.cartpopup-inner').html(data);
				jQuery('.cartpopup').fadeIn(500, function(){
					jQuery('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
					jQuery('.items li:first').effect("shake", { times:2, distance: 5 }, 200);
				});
				jQuery('.btn-add').removeAttr('disabled');
				jQuery('.btn-quantity').val('Qty');
				
				jQuery.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/update_rt_quantity.php?item_id=' + item_id,
					dataType: 'json',
					success: function(data){
						jQuery('.product').attr('name', 'qty-' + data.new_product_quantity);
						jQuery.each(data, function(key, val) {
							jQuery('.product-tax').find('a[data-termID=' + val.term + ']').attr('name', 'qty-' + val.qty);
						});
					}
				});
				
			}
		});
		
		// animation
		jQuery('.main-image img:first').clone().attr('class','clonedproduct').appendTo('.main-image').stop(true, true).animate({
			top: 0 - jQuery(this).offset().top + 320,
			left: jQuery(this).offset().left + 30,
			opacity: 0,
			width: 0,
			height: 0
		},800);
		
		} // canAddtoCart
		
		} // did not choose any product options
		
		} else {

		err = false;
		jQuery('.product-tax select').each(function(){
			if (jQuery(this).val() == 0) {
				jQuery('.product-var').effect("shake", { times:3, distance: 5 }, 100);
				jQuery('.addtocart').append('<span class="no-options-selected"><?php _e('Please select product options first.','ocart'); ?></span>');
				jQuery('.no-options-selected').stop().animate({opacity: 1});
				setTimeout(function(){
					jQuery('.no-options-selected').fadeOut('slow');
				}, 2000);
				err = true;
				return false;
			}
		});
		
		// check now for custom stock levels if found
		canAddglobally = true;
		canAddtoCart = true;
		
		if (canAddtoCart === true && canAddglobally !== false && err == false) {
		
		jQuery.scrollTo('#header', 800);
		
		// variables
		var item_id = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
		var item_name = jQuery('.product-name').html();
		var terms = '';
		jQuery('.product-tax select').each(function(){
			terms = terms + jQuery(this).val() + ":"
		});

		// now user can add item to cart!
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/add_item.php',
			type: 'get',
			data: {item_id: item_id, quantity: quantity, item_name: item_name, terms: terms},
			beforeSend: function(){
				jQuery('.btn-add').attr('disabled', 'disabled');
				jQuery('.cartpopup').hide();
			},
			success: function(data){
				jQuery('.cartpopup-inner').html(data);
				jQuery('.cartpopup').fadeIn(500, function(){
					jQuery('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
					jQuery('.items li:first').effect("shake", { times:2, distance: 5 }, 200);
				});
				jQuery('.btn-add').removeAttr('disabled');
				jQuery('.btn-quantity').val('Qty');
			}
		});
		
		// animation
		jQuery('.main-image img:first').clone().attr('class','clonedproduct').appendTo('.main-image').stop(true, true).animate({
			top: 0 - jQuery(this).offset().top + 320,
			left: jQuery(this).offset().left + 30,
			opacity: 0,
			width: 0,
			height: 0
		},800);
		
		}
		
		} // attr select 1
		
		} // global stock check
		
	});
	
	// add to basket (v2)
	jQuery('.catalog_quickadd span').live('click',function(e){
		e.preventDefault();
		var item_id = jQuery(this).parent().parent().attr('id').replace(/[^0-9]/g, '');
		lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', item_id);
	});
	
	// submitting login form (in checkout step)
	jQuery('#checkout_form_login').live('submit',function(e){
		e.preventDefault();
		jQuery(this).css({opacity: 0.5});
		jQuery('.checkout_form_submit').attr('disabled','disabled');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_checkout_login.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			success: function(data){
				jQuery('#checkout_form_login .message, #checkout_form_login .status').remove();
				jQuery('#checkout_form_login').css({opacity: 1});
				if (data.error) {
					jQuery('#' + data.validID).focus().parent().append('<span class="status status-success"></span>');
					jQuery('#' + data.errorID).focus().parent().append('<span class="status status-error"></span>');
					jQuery('#checkout_form_login .checkout_form_submit').effect("bounce", { times:2, distance:10 }, 300);
					jQuery('#checkout_form_login').append('<p class="message message-error">' + data.error + '</p>');
					jQuery('#checkout_form_login .message').fadeIn('slow', function(){
						jQuery('.checkout_form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					location.reload();
				}
			}
		});
		return false;
	});
	
	// submitting login form
	jQuery('#form_login').live('submit',function(e){
		e.preventDefault();
		jQuery(this).css({opacity: 0.5});
		jQuery('.form_submit').attr('disabled','disabled');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_login.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			success: function(data){
				jQuery('#form_login .message, #form_login .status').remove();
				jQuery('#form_login').css({opacity: 1});
				if (data.error) {
					jQuery('#' + data.validID).focus().parent().append('<span class="status status-success"></span>');
					jQuery('#' + data.errorID).focus().parent().append('<span class="status status-error"></span>');
					if (deviceWidth > 977) { jQuery('#form_login .form_submit').effect("bounce", { times:2, distance:10 }, 300); }
					jQuery('#form_login').append('<p class="message message-error">' + data.error + '</p>');
					jQuery('#form_login .message').fadeIn('slow', function(){
						jQuery('.form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					location.reload();
				}
			}
		});
		return false;
	});
	
	// submitting register form
	jQuery('#form_register').live('submit',function(e){
		e.preventDefault();
		jQuery(this).css({opacity: 0.5});
		jQuery('.form_submit').attr('disabled','disabled');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_register.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			success: function(data){
				jQuery('#form_register .message, #form_register .status').remove();
				jQuery('#form_register').css({opacity: 1});
				if (data.error) {
					jQuery('#' + data.validID).focus().parent().append('<span class="status status-success"></span>');
					if (deviceWidth > 977) { jQuery('#form_register .form_submit').effect("bounce", { times:2, distance:10 }, 300); }
					jQuery('#form_register').append('<p class="message message-error">' + data.error + '</p>');
					jQuery('#form_register .message').fadeIn('slow', function(){
						jQuery('.form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					jQuery('#form_register').append('<p class="message message-success"><?php _e('Your password will be emailed to you.','ocart'); ?></p>');
					jQuery('#form_register .message').fadeIn('slow', function(){
						jQuery('.form_submit').removeAttr('disabled');
						location.reload();
					});
				}
			}
		});
		return false;
	});
	
	// submitting reset password form
	jQuery('#form_resetpw').live('submit',function(e){
		e.preventDefault();
		jQuery(this).css({opacity: 0.5});
		jQuery('.form_submit').attr('disabled','disabled');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_recover_password.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			success: function(data){
				jQuery('#form_resetpw .message, #form_resetpw .status').remove();
				jQuery('#form_resetpw').css({opacity: 1});
				if (data.error) {
					jQuery('#' + data.errorID).focus().parent().append('<span class="status status-error"></span>');
					jQuery('#form_resetpw .form_submit').effect("bounce", { times:2, distance:10 }, 300);
					jQuery('#form_resetpw').append('<p class="message message-error">' + data.error + '</p>');
					jQuery('#form_resetpw .message').fadeIn('slow', function(){
						jQuery('.form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					jQuery('#form_resetpw').append('<p class="message message-success"><?php _e('Check your e-mail for the confirmation link.','ocart'); ?></p>');
					jQuery('#form_resetpw .message').fadeIn('slow', function(){
						jQuery('.form_submit').removeAttr('disabled');
					});
				}
			}
		});
		return false;
	});

	// animate button style 2
    jQuery('.btnstyle2').live('mouseenter',function() {
		jQuery(this).stop().animate({backgroundColor:'#333'},300);
	}).live('mouseleave',function() {
		jQuery(this).stop().animate({backgroundColor:'#ddd'},100);
    });
	
	// animate button style 1
    jQuery('.btnstyle1').live('mouseenter',function() {
		jQuery(this).stop().animate({backgroundColor:'<?php ocart_skin_data('button_hover_1') ?>'},500);
	}).live('mouseleave',function() {
		jQuery(this).stop().animate({backgroundColor:'<?php ocart_skin_data('active_color') ?>'},300);
    });
	
	// animate button style 3
    jQuery('.btnstyle3').live('mouseenter',function() {
		jQuery(this).stop().animate({backgroundColor:'<?php ocart_skin_data('button_style2_hover') ?>'},500);
	}).live('mouseleave',function() {
		jQuery(this).stop().animate({backgroundColor:'<?php ocart_skin_data('button_style2_color') ?>'},300);
    });
	
	// switching login/register
	jQuery('.registerlink').live('click',function(){
		jQuery(this).parent().parent().slideToggle(400, function(){
			jQuery('.tipsy').hide();
		});
		jQuery('.div-register').slideToggle(400, function(){
			jQuery('.tipsy').hide();
			jQuery('#form_register input:first').focus();
		});
	});
	jQuery('.loginlink').live('click',function(){
		jQuery(this).parent().parent().slideToggle(400, function(){
			jQuery('.tipsy').hide();
		});
		jQuery('.div-login').slideToggle(400, function(){
			jQuery('.tipsy').hide();
			jQuery('#form_login input:first').focus();
		});
	});
	jQuery('.forgot-pw').live('click',function(){
		jQuery(this).parent().parent().parent().parent().slideToggle(400, function(){
			jQuery('.tipsy').hide();
		});
		jQuery('.div-resetpw').slideToggle(400, function(){
			jQuery('.tipsy').hide();
			jQuery('#form_resetpw input:first').focus();
		});
	});
	
	// carousel for store categories
	<?php
	if (ocart_get_option('show_nav_all')) {
		$width = 770;
	} else {
		$width = 900;
	}
	?>
	jQuery('ul.list').carouFredSel({
		width: <?php echo $width; ?>,
		height: 30,
		scroll: 1,
		align: "left",
		auto: false,
		direction: "right",
		prev: "#browser .prev",
		next: "#browser .next"
	});
	
	// effect on product thumbnails
	jQuery('.thumbs a').live('mouseenter',function(){
		if (jQuery(this).hasClass('video')) {
		jQuery(this).children('img').stop().animate({opacity: 0.2});
		} else {
		jQuery(this).stop().animate({opacity: 0.5}, 900);
		}
	}).live('mouseleave',function(){
		if (jQuery(this).hasClass('video')) {
		jQuery(this).children('img').stop().animate({opacity: 1});
		} else {
		jQuery(this).stop().animate({opacity: 1}, 900);
		}
	});
	
	// switch main product images [next]
	jQuery('.nextImage').live('click',function(){
		var $img = jQuery('.main-image .zoom:visible').last(),
			$next = $img.next();
		if (0==$next.length) {
		   $next = $img.siblings().first();
		}
		$img.fadeOut(800);
		$next.fadeIn(800, function(){
				if (deviceWidth > 800) {
				jQuery(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
		});
		return false;
	});
	
	// switch main product images [previous]
	jQuery('.prevImage').live('click',function(){
		var $img = jQuery('.main-image .zoom:visible').last(),
			$prev = $img.prev();
		if (0==$prev.length) {
		   $prev = $img.siblings().last();
		}
		$img.fadeOut(800);
		$prev.fadeIn(800, function(){
				if (deviceWidth > 800) {
				jQuery(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
		});
		return false;
	});

	// load products in catalog
	if (jQuery('#catalog-noajax').length == 0) {
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			success: function(data){
				jQuery('.catalogWrapper').html(data);
				jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	} else {
		jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
	}
	
	// breadcrumb jump
	jQuery('.navi-tax').live('click',function(e){
		e.preventDefault();
		jQuery(this).effect("bounce", { direction: 'right', distance:5, times:3 }, 200);
		var taxonomy = jQuery(this).attr('id');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				jQuery('.catalogWrapper').html(data);
				jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	});
	
	// close small cart popup
	jQuery('#gotostore').live('click',function(e){
		if (jQuery('#blog').length == 0) {
		e.preventDefault();
		jQuery('.cartpopup').fadeOut();
		}
	});
	
	// hover on cart product
	jQuery('.thecart tr').live('mouseenter',function(){
		jQuery(this).css({'background': '#fbfbfb'});
	}).live('mouseleave',function(){
		jQuery(this).css({'background': '#fff'});
	});

	// change quantity by buttons
	jQuery('.update_q').live('click',function(e){
		
		e.preventDefault();
		var item = jQuery(this).parent().children('input');
		if (jQuery(this).hasClass('plus') == true) {
		var new_quantity = parseInt(item.val()) + 1;
		} else {
		var new_quantity = parseInt(item.val()) - 1;
		}
		if (new_quantity < 0) {
			var new_quantity = 0;
		}
		var session_id = jQuery(this).parent().children('input').attr('id').replace(/[^0-9]/g, '');

		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/check_stock.php',
			type: 'get',
			dataType: 'json',
			data: {id: jQuery(this).parent().parent().parent().attr('id').replace(/[^0-9]/g, ''), new_quantity: new_quantity},
			success: function(data){
				if (data.error) {

					item.val(data.max);
		
				} else {

					item.val(new_quantity).change();
					jQuery.ajax({
						url: '<?php echo get_template_directory_uri(); ?>/ajax/update_item.php',
						type: 'get',
						data: {session_id: session_id, new_quantity: new_quantity},
						success: function(data){
							jQuery('.cartpopup-inner').html(data);
							jQuery('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
							jQuery('#added_coupons').load('<?php echo get_template_directory_uri(); ?>/ajax/get_coupons.php');
							jQuery('.calc-tax span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_tax.php');
							jQuery('.calc-shipping span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_shipping.php');
							jQuery('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
							
							<?php do_action('ocart_ajax_calls_cart_calc'); ?>
							
						}
					});
		
				}
			}
		});
		
	});
	
	// change quantity in manual mode
	jQuery('.item_quantity').live('blur', function(e) {
	
		var this_quantity = jQuery(this);
		
		var new_quantity = jQuery(this).val();
		var session_id = jQuery(this).attr('id').replace(/[^0-9]/g, '');
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/check_stock.php',
			type: 'get',
			dataType: 'json',
			data: {id: jQuery(this).parent().parent().parent().attr('id').replace(/[^0-9]/g, ''), new_quantity: new_quantity},
			success: function(data){
				if (data.error) {
					this_quantity.val(data.max);
				} else {
					jQuery.ajax({
						url: '<?php echo get_template_directory_uri(); ?>/ajax/update_item.php',
						type: 'get',
						data: {session_id: session_id, new_quantity: new_quantity},
						success: function(data){
							jQuery('.cartpopup-inner').html(data);
							jQuery('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
							jQuery('#added_coupons').load('<?php echo get_template_directory_uri(); ?>/ajax/get_coupons.php');
							jQuery('.calc-tax span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_tax.php');
							jQuery('.calc-shipping span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_shipping.php');
							jQuery('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
							
								<?php do_action('ocart_ajax_calls_cart_calc'); ?>
								
						}
					});
				}
			}
		});
		
	});
	
	// change prices dynamically
	jQuery('.item_quantity').live('change keyup blur', function(e) {
		var re = /^[0-9]\d*$/;
		var str = jQuery(this).val();
		jQuery(this).val(parseFloat(str));
		if (re.test(str)){
			var price = jQuery(this).parent().parent().prev('td').html();
			var realprice = price.replace(/[^0-9\.]/g,'');
			jQuery(this).parent().parent().next('td').load('<?php echo get_template_directory_uri(); ?>/ajax/print_value.php?value=' + realprice + '&quantity=' + str);
		} else {
			jQuery(this).parent().parent().next('td').html('<?php echo ocart_format_currency( number_format(0, 2) ); ?>');
			jQuery(this).val(0);
		}
		// update grand total
		jQuery('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
	});
	
	// liststyle1 animation
	jQuery('.liststyle1 li').mouseenter(function(){
		jQuery(this).children('.thumb').children('a').stop(true, true).animate({opacity: 0.7}, 600);
	}).mouseleave(function(){
		jQuery(this).children('.thumb').children('a').stop(true, true).animate({opacity: 1}, 600);
	});
	
	// animate featured image in archive
	jQuery('.post').mouseenter(function(){
		jQuery(this).find('img').not('.collection_front_image, .collection_hover_image').stop(true, true).animate({opacity: 0.5}, 600);
	}).mouseleave(function(){
		jQuery(this).find('img').not('.collection_front_image, .collection_hover_image').stop(true, true).animate({opacity: 1}, 600);
	});
	
	// blog store button
	jQuery('.blog_store').mouseenter(function(){
		jQuery(this).stop(true, true).animate({backgroundColor: '#fff'}, 300);
	}).mouseleave(function(){
		jQuery(this).stop(true, true).animate({backgroundColor: '<?php ocart_skin_data('active_color') ?>'}, 800);
	});
	
	// list animation in tab content
	jQuery('.tabcontent ul li').mouseenter(function(){
		jQuery(this).css({'background': '#f9f9f9 url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet-hover.png) no-repeat 20px 20px'});
	}).mouseleave(function(){
		jQuery(this).css({'background': '#fff url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet.png) no-repeat 20px 20px'});
	});
	
	// list animation in normal list
	jQuery('.widget:not(.oc_tabs,.oc_latestblogs,.oc_twitter) ul li').mouseenter(function(){
		jQuery(this).css({'background': 'url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet-hover.png) no-repeat left 20px'});
	}).mouseleave(function(){
		jQuery(this).css({'background': 'url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet.png) no-repeat left 20px'});
	});
	
	// jquery tabs
	jQuery('.tabs').fptabs('.tabcontent');
	
	// open the filters
	jQuery('#filter').mouseenter(function(){ if (jQuery('#filter-by').is(':hidden')) { jQuery(this).css({'background-position' : '0 -36px'}); } });
	jQuery('#filter').mouseleave(function(){ if (jQuery('#filter-by').is(':hidden')) { jQuery(this).css({'background-position' : '0 0'}); } });
	jQuery('#filter').live('click',function(){
		if (jQuery('#filter-by').is(':hidden')) {
			jQuery(this).css({'background-position' : '0 -72px'});
			jQuery('#filter-by').fadeIn();
		} else {
			jQuery(this).css({'background-position' : '0 -36px'});
			jQuery('#filter-by').fadeOut();
		}
	});
	
	// filter / sort
	jQuery('.tax-parent').live('click',function(e){
		e.preventDefault();
		var dropd = jQuery(this).next('ul');
		if (dropd.is(':visible')) {
			dropd.fadeOut();
			jQuery(this).css({'background-position':'right top'});
		} else {
			dropd.fadeIn();
			jQuery(this).css({'background-position':'right bottom'});
		}
	});
	jQuery('.tax > ul').live('mouseleave',function(){
		jQuery(this).children('li').children('ul').fadeOut();
		jQuery(this).find('.tax-parent').css({'background-position':'right top'});
	});
	
	// reset filters
	jQuery('#resetfilters').live('click',function(e){
		jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
		jQuery('.catalogWrapper').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog.php');
	});
	
	// update catalog by filters
	jQuery('#filter-by li ul a').live('click',function(e){
		e.preventDefault();

		// toggle current class
		if (jQuery(this).hasClass('current')) {
			jQuery(this).removeClass('current');
		} else {
			jQuery(this).addClass('current');
		}
		if (!jQuery(this).attr('rel')) { // regular link
			jQuery(this).closest('.tax-parent-li').find('ul li:first a').removeClass('current');
		} else {
			jQuery(this).closest('.tax-parent-li').find('a').removeClass('current');
			jQuery(this).closest('.tax-parent-li').find('ul li:first a').addClass('current');
		}
		
		// custom field parameter
		if (jQuery(this).parent().parent().attr('rel') == 'custom_fields' || jQuery(this).parent().parent().attr('rel') == 'sort_by') {
			jQuery(this).closest('.tax-parent-li').find('a').removeClass('current');
			jQuery(this).addClass('current');
		}
		
		// selected items
		var sortfield = '';
		var cfield = '';
		var terms_ids = '';
		var taxonomies = '';
		jQuery('#filter-by li ul a.current').each( function() {
			if (jQuery(this).parent().attr('class')) {
			terms_ids = terms_ids + jQuery(this).parent().attr('class').replace(/[^0-9]/g, '') + ',';
			taxonomies = taxonomies + jQuery(this).closest('.tax-parent-li').find('ul li:first a').attr('rel').replace(/default_/, '') + ',';
			}
			if (jQuery(this).parent().parent().attr('rel') == 'custom_fields') {
			cfield = jQuery(this).attr('id');
			}
			if (jQuery(this).parent().parent().attr('rel') == 'sort_by') {
			sortfield = jQuery(this).attr('id');
			}
		});
		
		// ajax request
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomies: taxonomies, terms_ids: terms_ids, cfield: cfield, sortfield: sortfield, min_price: jQuery('#min_price').val(), max_price: jQuery('#max_price').val(), use_saved_query: true},
			beforeSend: function() {
				jQuery('#filter-by h2').append('<span class="loading"><?php _e('Updating...','ocart'); ?><img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" /></span>');
			},
			success: function(data){
				jQuery('#filter-by h2 span.loading').remove();
				jQuery('.catalogWrapper').html(data);
			}
		});
		
	});
	
	// auto update on price range changes
	jQuery('#min_price, #max_price').live('change',function(){
		jQuery('#filter-by li ul a:first').trigger('click');
	});
	
	// billing/address same check
	jQuery('#cform_ub').click(function(){
		if (jQuery('#cform_ub').is(':checked')) {
			// visual: mark fields as disabled
			jQuery(this).parent().parent().parent().children('p').not('.chkbox').fadeTo(1, 0.5);
			jQuery(this).parent().parent().parent().children('p').not('.chkbox').children('input, select').attr('disabled','disabled');
			// remove any error msgs
			jQuery('.cform fieldset:eq(2) span.errorfield').remove();
			// copy values from billing
			jQuery('.cform fieldset:eq(1) input[type=text]').each(function(){
				var fieldvalue = jQuery(this).val();
				var thisfield = jQuery(this).attr('id');
				jQuery('.cform fieldset:eq(2) input[type=text]#' + thisfield + '2').val(fieldvalue);
			});
			jQuery('.cform fieldset:eq(1) select').each(function(){
				var fieldvalue = jQuery(this).val();
				var thisfield = jQuery(this).attr('id');
				jQuery('.cform fieldset:eq(2) select#' + thisfield + '2').val(fieldvalue);
				if (jQuery('.cform fieldset:eq(2) select#' + thisfield + '2').val() != fieldvalue) {
					jQuery('span.errorfield').remove();
					jQuery('.cform fieldset:eq(2) select#' + thisfield + '2').parent().fadeTo(1, 1);
					jQuery('.cform fieldset:eq(2) select#' + thisfield + '2').removeAttr('disabled');
					jQuery('.cform fieldset:eq(2) select#' + thisfield + '2').after('<span class="errorfield"><?php _e('We do not ship to this destination yet.','ocart'); ?></span>').focus();
				}
			});
		} else {
			// visual: mark fields as not disabled
			jQuery(this).parent().parent().parent().children('p').not('.chkbox').fadeTo(1, 1);
			jQuery(this).parent().parent().parent().children('p').not('.chkbox').children('input, select').removeAttr('disabled');
		}
	});
	
	// detect billing fields change when cform_ub is checked
	jQuery('.cform fieldset:eq(1) input[type=text]').change(function(){
		if (jQuery('#cform_ub').is(':checked')) {
			var fieldvalue = jQuery(this).val();
			var thisfield = jQuery(this).attr('id');
			jQuery('.cform fieldset:eq(2) input[type=text]#' + thisfield + '2').val(fieldvalue);
		}
	});
	
	// detect billing fields change when cform_ub is checked
	jQuery('.cform fieldset:eq(1) select').change(function(){
		if (jQuery('#cform_ub').is(':checked')) {
			var fieldvalue = jQuery(this).val();
			var thisfield = jQuery(this).attr('id');
			jQuery('.cform fieldset:eq(2) select#' + thisfield + '2').val(fieldvalue);
		}
	});
	
	// update user pic on saving email on checkout
	jQuery('#cform_email').change(function(){
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/validate_email.php',
			type: 'post',
			data: {email: jQuery('#cform_email').val()},
			dataType: 'json',
			success: function(data){
				jQuery('.cform span.email').remove();
				jQuery('#'+ data.field).focus().after('<span class="email">' + data.msg + '</span>');
				jQuery('.cform_email label').html(data.gravatar);
			}
		});
	});
	
	// capture data-fee
	function get_additional_charges() {
		add_charge = 0;
		jQuery('input[type=radio]:checked').each(function(){
			add_charge += parseFloat(jQuery(this).attr('data-fee'));
		});
		return add_charge;
	}
	
	// capture data-fee
	function get_additional_charges_shipping() {
		add_charge = 0;
		jQuery('#radio_shipping_options input[type=radio]:checked').each(function(){
			add_charge += parseFloat(jQuery(this).attr('data-fee'));
		});
		return add_charge;
	}
	
	// add shipping label price
	jQuery('#radio_shipping_options input[type=radio]').click(function(){
		jQuery('#shipping_fee').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_shipping.php?add=' + get_additional_charges_shipping());
		jQuery('#order_total').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php?add=' + get_additional_charges(), function(){
			jQuery('.checkout_total span').effect("bounce", { times:2 }, 400);
		});
	});
	
	// add payment option price
	jQuery('#radio_payment_options input[type=radio]').click(function(){
		jQuery('#order_total').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php?add=' + get_additional_charges(), function(){
			jQuery('.checkout_total span').effect("bounce", { times:2 }, 400);
		});
	});
	
	// submitting order form details
	jQuery('#cform').submit(function(e){
		e.preventDefault();
		jQuery('input#cform_gross_total').val(jQuery('#order_total').html());
		
		// check for agreement
		if (jQuery(this).find('.chkbox_terms').length) {
			if (!jQuery('.chkbox_terms').find('input[type=checkbox]').is(':checked')) {
				jQuery('#terms_must_check').remove();
				jQuery('#cform_agreement').parent().parent().after('<span class="errorfield3" id="terms_must_check"><?php _e('Please accept terms and conditions before placing your order.','ocart'); ?></span>');
				return false;
			}
		}
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/submit_order.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			beforeSend: function(){
				jQuery('.submit input').css({opacity: 0.5});
				jQuery('.submit input').attr('disabled','disabled');
				jQuery('.preorder').html('<img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" />');
			},
			success: function(data){
				jQuery('.cform span.email, .cform span.errorfield, .cform span.errorfield2, .cform span.errorfield3').remove();
				var count = 0;
				if (data.fields) {
					jQuery('.submit input').css({opacity: 1});
					jQuery('.submit input').removeAttr('disabled');
					jQuery('.preorder').html('<?php _e('Please confirm your billing and shipping details before placing your order.','ocart'); ?>');
				jQuery.each(data.fields, function(i, msg) {
					count++;
					if (count == 1) { jQuery('#'+ i).focus(); }
					if (i == 'cform_email') {
					jQuery('#'+ i).after('<span class="email">' + msg + '</span>');
					} else {
					jQuery('#'+ i).after('<span class="errorfield">' + msg + '</span>');
					}
				});
				} else if (data.custom_error) {
					jQuery('.submit input').css({opacity: 1});
					jQuery('.submit input').removeAttr('disabled');
					jQuery('.preorder').html('<?php _e('Please confirm your billing and shipping details before placing your order.','ocart'); ?>');
					jQuery('#' + data.custom_error_field).after('<span class="errorfield2">' + data.custom_error + '</span>');
					jQuery.scrollTo('#' + data.custom_error_field, 800);
				} else {
					jQuery('#cform').slideToggle();
					jQuery('.checkout_process').load('<?php echo get_template_directory_uri(); ?>/ajax/paymentredirection.php?orderID=' + data.order_id + '&paymentgateway=' + data.order_pay);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	});
	
	// updating account settings
	jQuery('#updateinfo').submit(function(e){
		e.preventDefault();
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/account_change.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			beforeSend: function(){
			},
			success: function(data){
				jQuery('#updateinfo span').not('.help').remove();
				if (data.fields) {
				var count = 0;
				jQuery.each(data.fields, function(i, msg) {
					count++;
					if (count == 1) { jQuery('#'+ i).focus(); }
					jQuery('#'+ i).after('<span class="errorfield">' + msg + '</span>');
				});
				} else if (data.success) {
					jQuery('#updateinfo input[type=password]').val('');
					jQuery('#updateinfo input[type=submit]').before('<span class="successfield">' + data.success + '</span>');
				} else {
					jQuery('#updateinfo input[type=password]').val('');
					jQuery('#updateinfo input[type=submit]').before('<span class="emptyfield">' + data.empty + '</span>');
				}
				jQuery('#footer').css({position: 'relative',top:'auto'});
			}
		});
	});
	
	// footer css fix
	jQuery('#footer .footer_menu a:last').css({'background': 'none'});
	
	// footer widgets fix
	if (deviceWidth > 977) {
		jQuery('.section:last').css({'width':'197px','margin-right':0});
	} else {
		jQuery('.section:last').css({'margin': 0});
	}
	
	// submitting contact form
	jQuery('#contactform').live('submit',function(e){
		e.preventDefault();
		jQuery('#contactform span.contact_result').remove();
		jQuery('#contactform input[type=submit]').after('<img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" />');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/contactform.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			beforeSend: function(){
			},
			success: function(data){
				jQuery('#contactform img').remove();
				if (data.fields) {
					var count = 0;
					jQuery.each(data.fields, function(i, msg) {
						count++;
						if (count == 1) { jQuery('#'+ i).focus(); }
					});
				} else if (data.thankyou) {
					jQuery('#contactform').append('<span class="contact_result">' + data.thankyou + '</span>');
				}
			}
		});
	});
	
	// apply coupon code
	jQuery('.coupon').live('submit',function(e){
		e.preventDefault();
		jQuery('.coupon-error, .coupon-discount').remove();
		jQuery('.coupon img').show();
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/apply_coupon.php',
			type: 'post',
			data: jQuery(this).serialize(),
			dataType: 'json',
			success: function(data){
				jQuery('.coupon img').hide();
				if (data.err) {
					jQuery('.coupon').append('<div class="coupon-error">' + data.err + '</div><div class="clear"></div>');
				} else {
					jQuery('#coupon_code').val('');
				}
				if (data.discount) {
					jQuery('.coupon').append('<div class="coupon-discount">' + data.discount + '</div><div class="clear"></div>');
				}
				if (data.new_total) {
					jQuery('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
					jQuery('.calc-shipping').children('span').html('<?php _e('FREE!','ocart'); ?>');
				}
				if (data.new_subtotal) {
					jQuery('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
					jQuery('#added_coupons').load('<?php echo get_template_directory_uri(); ?>/ajax/get_coupons.php');
				}
			}
		});
	});
	
	// product tabs (new feature)
	jQuery('.infotabs li a:first').css({'border-radius' : '5px 0 0 0'});
	
	// when tab is clicked
	jQuery('.infotabs a').live('click',function(e){
		
		if (jQuery(this).attr('rel') != 'tab_video') {
		e.preventDefault();
		}
		if (jQuery(this).attr('rel') == 'tab_video' && jQuery(this).hasClass('current')) {
			e.preventDefault();
		}
		
		if (jQuery(this).hasClass('current') && jQuery(this).attr('rel') != 'tab_content') {
		
			jQuery('.infotabs a').removeClass('current');
			jQuery('.infotabs a:first').addClass('current');
			jQuery('.infotab').html('<div class="infotab_div_default"></div>');
			var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
			jQuery.ajax({
				url: '<?php echo get_template_directory_uri(); ?>/ajax/short_excerpt.php?id=' + productID,
				type: 'get',
				success: function(data){
					jQuery('.infotab_div_default').html(data);
					jQuery('.infotab').append('<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>');
				}
			});
			
		} else if (jQuery(this).hasClass('current') && jQuery(this).attr('rel') == 'tab_content') {

			if (jQuery('.infotab_div_default').is(':visible')) {
			
				jQuery('.infotabs a').removeClass('current');
				var rel = jQuery(this).attr('rel');
				var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
				var activetab = jQuery(this);
				jQuery('.infotab').html('<div class="infotab_div" />');
				jQuery('.infotab_div').stop().animate({'height' : '200px'}, function() {
					jQuery('.infotab_div').addClass('loader');
					jQuery('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=' + rel, function(){
						jQuery('.infotab_div').removeClass('loader');
						jQuery('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
						activetab.addClass('current');
						jQuery('.infotab_div').mCustomScrollbar();
					});
				});

			} else {
			
				jQuery('.infotabs a').removeClass('current');
				jQuery('.infotabs a:first').addClass('current');
				jQuery('.infotab').html('<div class="infotab_div_default"></div>');
				var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
				jQuery.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/short_excerpt.php?id=' + productID,
					type: 'get',
					success: function(data){
						jQuery('.infotab_div_default').html(data);
						jQuery('.infotab').append('<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>');
					}
				});
			
			}
		
		} else {
		
		jQuery('.infotabs a').removeClass('current');
		var rel = jQuery(this).attr('rel');
		var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
		var activetab = jQuery(this);
		jQuery('.infotab').html('<div class="infotab_div" />');
		jQuery('.infotab_div').stop().animate({'height' : '200px'}, function() {
			jQuery('.infotab_div').addClass('loader');
			jQuery('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=' + rel, function(){
				jQuery('.infotab_div').removeClass('loader');
				jQuery('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
				activetab.addClass('current');
				jQuery('.infotab_div').mCustomScrollbar();
			});
		});
		
		}
		
	});
	
	// when rate is clicked
	jQuery('#rate_product').live('click',function(e){
		var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
		jQuery('.infotabs a').removeClass('current');
		jQuery('.infotab').html('<div class="infotab_div" />');
		jQuery('.infotab_div').stop().animate({'height' : '200px'}, function() {
			jQuery('.infotab_div').addClass('loader');
			jQuery('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=tab_reviews', function(){
				jQuery('.infotab_div').removeClass('loader');
				jQuery('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
				jQuery('.infotabs a:last').addClass('current');
				jQuery('.infotab_div').mCustomScrollbar();
			});
		});
	});
	
	// when read more is clicked
	jQuery('.togglemore').live('click',function(e){
			var rel = jQuery('.infotabs a:first').attr('rel');
			var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
			var activetab = jQuery(this);
			jQuery('.infotab').html('<div class="infotab_div" />');
			jQuery('.infotab_div').stop().animate({'height' : '200px'}, function() {
				jQuery('.infotab_div').addClass('loader');
				jQuery('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=' + rel, function(){
					jQuery('.infotab_div').removeClass('loader');
					jQuery('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
					jQuery('.infotab_div').mCustomScrollbar();
				});
			});
	});
	
	// when close tab is clicked
	jQuery('.closetab').live('click',function(e){
		jQuery('.infotabs a').removeClass('current');
		jQuery('.infotabs a:first').addClass('current');
		jQuery('.infotab').html('<div class="infotab_div_default"></div>');
		var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/short_excerpt.php?id=' + productID,
			type: 'get',
			success: function(data){
				jQuery('.infotab_div_default').html(data);
				jQuery('.infotab').append('<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>');
			}
		});
	});
	
	// submit a review
	jQuery('#reviewform').live('submit',function(e){
		e.preventDefault();
		jQuery.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/submit_review.php',
				data: jQuery(this).serialize(),
				beforeSend: function(){
					jQuery('#reviewform').css({opacity: 0.5});
					jQuery('#reviewform input[type=submit]').attr('disabled','disabled');
				},
				success: function(data){
					jQuery('#reviewform').stop().animate({'opacity':1});
					jQuery('#reviewform input[type=submit]').removeAttr('disabled');
					jQuery('#reviewform input, #reviewform textarea').not('#review_submit').css({'border-color':'#e5e5e5'});
					if (data.error) {
						jQuery('#'+data.error).css({'border-color':'red'});
						jQuery('#'+data.error).focus();
						if (data.error == 'comment') {
						jQuery('.infotab_div').mCustomScrollbar("scrollTo", '#comment');
						} else {
						jQuery('.infotab_div').mCustomScrollbar("scrollTo", 'first');
						}
					} else if (data.error_rate) {
						jQuery('.rating-title span').remove();
						jQuery('.rating-title').append('<span>' + data.error_rate + '</span>');
						jQuery('.infotab_div').mCustomScrollbar("scrollTo", '.rating-title');
					} else {
						// update reviews count
						jQuery('.ajax_reviews_count').html(data.count_of_reviews);
						// on success: reload reviews
						var productID = jQuery('.product').attr('id').replace(/[^0-9]/g, '');
						jQuery('.infotab_div').empty().addClass('loader');
						jQuery('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=tab_reviews', function(){
								jQuery('.infotab_div').removeClass('loader');
								jQuery('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
								jQuery('.infotab_div').mCustomScrollbar();
						});
					}
				}
		});
	});
	
	// rating UI
    jQuery('.ratings_stars').live('mouseenter', function() {
		jQuery('.rating').removeClass('rated');
		jQuery(this).prevAll().andSelf().addClass('ratings_over');
		jQuery(this).nextAll().removeClass('ratings_over');
	});
	jQuery('.ratings_stars').live('mouseout', function() {
		if (jQuery('.rating').hasClass('rated') == false) {
		jQuery(this).prevAll().andSelf().removeClass('ratings_over');
		}
    });
	
    jQuery('.ratings_stars').live('click', function() {
		jQuery(this).prevAll().andSelf().addClass('ratings_over');
		jQuery(this).nextAll().removeClass('ratings_over');
		jQuery('.rating').addClass('rated');
		// save the vote
		jQuery('#rating').val(jQuery(this).attr('id').replace(/[^0-9]/g, ''));
	});
	
	// toggle review form
	jQuery('#toggle_review a').live('click',function(){
		jQuery('.toggled_review_form').slideToggle('slow', function(){
			jQuery('.infotab_div').mCustomScrollbar("update");
		});
	});
	
	// load initial results
	if (jQuery('.catalog').length > 0) { // Run the ajax request only when .catalog exists!
		canScroll = false;
		jQuery("body").prepend("<div id='loading-results' style='display:none;'></div>");
		jQuery('#loading-results').center().show();
		jQuery('.catalog').css({opacity: 0.2});

		jQuery.ajax({
			type: 'post',
			url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
			data: {offset: 0},
			success: function(res) {
				// add initial products
				jQuery(".catalog_list").append(res);
				// enable scroll again
				canScroll = true;
				// remove loader
				jQuery('#loading-results').remove();
				jQuery('.catalog').css({opacity: 1});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				 alert(thrownError);
			}
		});
	}
	
	// back to top button
	<?php if (ocart_get_option('show_backtotop')) { ?>
	jQuery(window).scroll(function() {
		if(jQuery(this).scrollTop() != 0) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},800);
	});
	<?php } ?>
	
	// scroll bar to limit long lists
	<?php
	if (isset($ocart['scroll_attr']) && is_array($ocart['scroll_attr'])) {
		foreach ($ocart['scroll_attr'] as $key) {
	?>
	jQuery('ul.root-<?php echo $key; ?>').mCustomScrollbar();
	<?php
		}
	}
	?>
	
	// add toggle filters to parents
	jQuery('.filter ul li').each(function(){
		if (jQuery(this).children('ul').length) {
			jQuery(this).children('a').after('<span class="filter_e">[+]</span>');
		}
	});
	
	// expand filter
	jQuery('.filter_e').live('click',function(){
		if (jQuery(this).parent().find('ul.children').is(':hidden')) {
		jQuery(this).parent().find('ul.children').slideToggle();
		jQuery(this).html('[–]');
		} else {
		jQuery(this).parent().find('ul.children').slideToggle();
		jQuery(this).html('[+]');
		}
	});
	
	// load items based on left filters
	jQuery('.filter ul a').live('click',function(e){
		
		e.preventDefault();

		// mark 'selected' or 'unselected'
		if (jQuery(this).hasClass('selected')) {
		jQuery(this).removeClass('selected');
		} else {
		jQuery(this).addClass('selected');
		}
		
		// mark unselected from main menu
		jQuery('#supermenu li#' + jQuery(this).attr('id')).removeClass('current-menu-item');
		
		// mark parent unselected if this is child
		if (jQuery(this).parent().parent().hasClass('children')) {
			jQuery(this).parent().parent().parent().children('a').removeClass('selected');
		}
		
		// get active taxonomies
		var taxonomies = '';
		jQuery('.filter ul a.selected').each( function() {
			taxonomies = taxonomies + jQuery(this).attr('id') + ',';
		});
		
			pricemin = jQuery('.text_min ins').html();
			pricemax = jQuery('.text_max ins').html();
		
		// load results and change title
		canScroll = false;
		jQuery("body").prepend("<div id='loading-results' style='display:none;'></div>");
		jQuery('#loading-results').center().show();
		jQuery('.catalog').css({opacity: 0.2});
		jQuery('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		
		// show more
		jQuery.ajax({
				type: 'post',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
				data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
				success: function(res) {
					// add results
					jQuery(".catalog_list").html(res);
					// enable scroll again
					canScroll = true;
					// remove loader
					jQuery('#loading-results').remove();
					jQuery('.catalog').css({opacity: 1});
				}
		});
		
	});
	
	// clear filters
	jQuery('.header a').live('click',function(e){
		
		e.preventDefault();
		
		// clear selection of box
		jQuery(this).parent().next('ul').find('a').removeClass('selected');
		
		// is it price reset
		if (jQuery(this).parent().attr('id') == 'price_range_slider' ) {
			jQuery( ".text_min ins" ).html( 0 );
			jQuery( ".text_max ins" ).html( '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>' );
			var pslider = jQuery("#slider-range");
			pslider.slider("values", 0, 0);
			pslider.slider("values", 1, '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>');
		} else {
		
		// get active taxonomies
		var taxonomies = '';
		jQuery('.filter ul a.selected').each( function() {
			taxonomies = taxonomies + jQuery(this).attr('id') + ',';
		});
		
			pricemin = jQuery('.text_min ins').html();
			pricemax = jQuery('.text_max ins').html();
		
		// load results and change title
		canScroll = false;
		jQuery("body").prepend("<div id='loading-results' style='display:none;'></div>");
		jQuery('#loading-results').center().show();
		jQuery('.catalog').css({opacity: 0.2});
		jQuery('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		
		// show more
		jQuery.ajax({
				type: 'post',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
				data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
				success: function(res) {
					// add results
					jQuery(".catalog_list").html(res);
					// enable scroll again
					canScroll = true;
					// remove loader
					jQuery('#loading-results').remove();
					jQuery('.catalog').css({opacity: 1});
				}
		});
		
		} // run this only if user is not resetting price
		
	});
	
	// remove active filter
	jQuery('.active_filter').live('click',function(e){
		term_id = jQuery(this).attr('rel');
		jQuery(this).fadeOut('slow');
		jQuery('.filter ul a#' + term_id).removeClass('selected');
		
		// get active taxonomies
		var taxonomies = '';
		jQuery('.filter ul a.selected').each( function() {
			taxonomies = taxonomies + jQuery(this).attr('id') + ',';
		});
		
			pricemin = jQuery('.text_min ins').html();
			pricemax = jQuery('.text_max ins').html();
		
		// load results and change title
		canScroll = false;
		jQuery("body").prepend("<div id='loading-results' style='display:none;'></div>");
		jQuery('#loading-results').center().show();
		jQuery('.catalog').css({opacity: 0.2});
		jQuery('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		
		// show more
		jQuery.ajax({
				type: 'post',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
				data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
				success: function(res) {
					// add results
					jQuery(".catalog_list").html(res);
					// enable scroll again
					canScroll = true;
					// remove loader
					jQuery('#loading-results').remove();
					jQuery('.catalog').css({opacity: 1});
				}
		});

	});
	
	// list grid view
	jQuery('#switchToGrid').live('click',function(e){
		jQuery.cookies.set('layout', 'grid');
		location.reload();
	});
	
	// list slider view
	jQuery('#switchToSlider').live('click',function(e){
		jQuery.cookies.set('layout', 'slider');
		location.reload();
	});
	
	// product color / change image
	jQuery('.product-color ul a').live('click',function(){
		var rel = jQuery(this).attr('rel');
		var currentID = jQuery('.main-image .zoom:visible').attr('id');
		if (rel && rel !== currentID && rel != 'video') {
			jQuery('.main-image .zoom').fadeOut(800);
			jQuery(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
				if (deviceWidth > 766) {
					jQuery(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
			});
		}
	});
	
	// flash price when option is selected
	jQuery('.product-tax ul a').live('click',function(){
		var pricenow = jQuery(this).parent().parent().parent().parent().parent().parent().find('.price-now');
		var changes = '';
		jQuery('.product-tax li.current > a, .product-tax li > a.current').each(function(){
			changes = changes + jQuery(this).attr("data-change") + ":"
		});
		pricenow.load('<?php echo get_template_directory_uri(); ?>/ajax/adjust_product_price.php?product_id=' + jQuery('.product').attr('id').replace(/[^0-9]/g, '') + '&change=' + changes,function(){
			pricenow.effect("bounce", { times:2 }, 400);
		});
	});
	
	// flash price when option is selected (dropdown)
	jQuery('.product-tax select').live('change',function(){
		var pricenow = jQuery(this).parent().parent().parent().parent().parent().parent().find('.price-now');
		var changes = '';
		jQuery('.product-tax select option:selected').each(function(){
			changes = changes + jQuery(this).attr("data-change") + ":"
		});
		pricenow.load('<?php echo get_template_directory_uri(); ?>/ajax/adjust_product_price.php?product_id=' + jQuery('.product').attr('id').replace(/[^0-9]/g, '') + '&change=' + changes,function(){
			pricenow.effect("bounce", { times:2 }, 400);
		});
	});
	
	// change URL dynamically
	jQuery('.list a, .navi a, #similar ul li a, .ajax-search-results a, .filter ul a').live('click',function(e){
		e.preventDefault();
		link = jQuery(this).attr('href');
		window.history.pushState(null,null, link);
	});
	jQuery('#logo a').live('click',function(e){
		if (jQuery('#blog').length == 0) {
		e.preventDefault();
		link = jQuery(this).attr('href');
		window.history.pushState(null,null, link);
		}
	});
	
	// focus on product search
	jQuery('#productSearch').live('focus',function(){
		jQuery(this).stop().animate({'width':'200px'}, 600);
		var searchterm = encodeURIComponent(jQuery('#productSearch').val());
		if (searchterm != '') {
			jQuery('.ajax-search-results').show();
			jQuery('.ajax-search-results').load('<?php echo get_template_directory_uri(); ?>/ajax/search.php?type=product&s=' + searchterm);
		}
	});
	
	// auto complete / ajax results
	var timer;
	jQuery(document).on('keyup', '#productSearch',function(){
		clearTimeout(timer);
		timer = setTimeout(function() {
			var searchterm = encodeURIComponent(jQuery('#productSearch').val());
			jQuery('.ajax-search-results').show().load('<?php echo get_template_directory_uri(); ?>/ajax/search.php?type=product&s=' + searchterm);
		}, 300);
	});
	
	// init similar products
	jQuery('.recommend-btn').live('click',function(){
		if (jQuery('#similar').is(':hidden')) {
			jQuery(this).addClass('clicked');
			jQuery('#similar').show();
			jQuery('#similar .wrap').animate({'height':'200px'}, {duration: 600, complete: function(){
				jQuery('#similar .similarWrap').addClass('loadsimilar');
				jQuery('#similar .similarWrap').load('<?php echo get_template_directory_uri(); ?>/ajax/similar.php?product=' + jQuery('.product').attr('id').replace(/[^0-9]/g, ''), function(){
					jQuery('#similar .similarWrap').removeClass('loadsimilar');
					jQuery('.tooltip').tipsy({
						gravity: 'n',
						trigger: 'hover',
						fade:  true,
						offset: 8
					});
					// init carousel
					jQuery('#similar ul').carouFredSel({
						width: '100%',
						height: 200,
						items: {
							visible: 4
						},
						scroll: 1,
						align: 'center',
						auto: false,
						direction: "right",
						prev: {
							button: '.sprevItem'
						},
						next: {
							button: '.snextItem'
						}
					});
				});
			}});
		} else {
			jQuery('#similar .wrap').animate({'height':0}, {duration: 600, complete: function(){
				jQuery('.recommend-btn').removeClass('clicked');
				jQuery('#similar').hide();
				jQuery('#similar .similarWrap').empty();
			}});
		}
	});

	// pick date in checkout
	jQuery( "#cform_custom_delivery" ).datepicker({
		dateFormat: "dd/mm/yy",
		showOn: "button",
		buttonImage: "<?php echo get_template_directory_uri(); ?>/img/calendar.png",
		buttonImageOnly: true,
		minDate: 0
	});
	
	// supermenu css
	jQuery('#supermenu li a:first').css({'border-radius': '2px 0 0 2px'});
	jQuery('#supermenu li a:first').addClass('homelink');
	jQuery("#supermenu li:has(ul)").find("a:first").append('<span>&#9660;</span>');
	jQuery("#supermenu li li:has(ul)").find("a:first").find('span').html('&#8594;');
	
	// supermenu
	jQuery("#supermenu ul").css({display: "none"}); // Opera Fix
	jQuery("#supermenu li").hover(function(){
	jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).fadeIn(500);
	},function(){
	jQuery(this).find('ul:first').hide();
	});
	
	// supermenu link
	jQuery("#supermenu a").click(function(e){
	
		// track ajaxified links only
		if (jQuery(this).parent().attr('id') && (jQuery('body').find('.catalog_list').length != 0 || jQuery('body').find('.prods').length != 0) ) {
		
		e.preventDefault();
		link = jQuery(this).attr('href');
		window.history.pushState(null,null, link);
		
		// animation
		jQuery('#supermenu li').removeClass('current-menu-item');
		jQuery('#supermenu li').removeClass('current-menu-ancestor');
		jQuery(this).parent().addClass('current-menu-item');
		jQuery(this).parent().parent().parent().addClass('current-menu-item');
		jQuery(this).parent().parent().parent().parent().parent().addClass('current-menu-item');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().addClass('current-menu-item');
		
		catalogver = '<?php echo ocart_catalog_version() ?>';
		
		// v1
		if (catalogver == 1) {
		
			var taxonomy = jQuery(this).parent().attr('id');
			jQuery.scrollTo('.catalogWrapper', 800);
			jQuery.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
					type: 'get',
					data: {taxonomy: taxonomy},
					success: function(data){
						jQuery('.catalogWrapper').html(data);
						jQuery('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
					}
			});

		}
		
		// v2
		if (catalogver == 2) {
		jQuery.scrollTo('#index', 800);
		// reset filters
		jQuery('.filter ul a').removeClass('selected');
		jQuery('.filter ul a#' + jQuery(this).parent().attr('id')).addClass('selected');
		var taxonomies = jQuery(this).parent().attr('id');
			pricemin = jQuery('.text_min ins').html();
			pricemax = jQuery('.text_max ins').html();
		jQuery('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		jQuery('.catalog').css({opacity: 0.5});
		jQuery.ajax({
			type: 'post',
			url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
			data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
			success: function(res) {
				jQuery(".catalog_list").html(res);
				jQuery('.catalog').css({opacity: 1});
			}
		});
		}
		
		}
		
	});
	
	// price slider in grid
	jQuery( "#slider-range" ).slider({
			range: true,
            min: 0,
            max: '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>', // maximum price
            step: 10, // Use this determine the amount of each interval
			values: [ 0, '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>' ], // The default range
			slide: function( event, ui ) {
				jQuery( ".text_min ins" ).html(ui.values[ 0 ]); // Display and selected the min Price
				jQuery( ".text_max ins" ).html(ui.values[ 1 ]); // Display and selected the max Price
			},
			change: function(event, ui) {
				// ajax update
					
					// get active taxonomies
					var taxonomies = '';
					jQuery('.filter ul a.selected').each( function() {
						taxonomies = taxonomies + jQuery(this).attr('id') + ',';
					});
					
					pricemin = jQuery('.text_min ins').html();
					pricemax = jQuery('.text_max ins').html();
					
					// load results and change title
					canScroll = false;
					jQuery("body").prepend("<div id='loading-results' style='display:none;'></div>");
					jQuery('#loading-results').center().show();
					jQuery('.catalog').css({opacity: 0.2});
					jQuery('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
					
					// show more
					jQuery.ajax({
							type: 'post',
							url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
							data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
							success: function(res) {
								// add results
								jQuery(".catalog_list").html(res);
								// enable scroll again
								canScroll = true;
								// remove loader
								jQuery('#loading-results').remove();
								jQuery('.catalog').css({opacity: 1});
							}
					});
			}
	});
	
	// collections js
	jQuery('.collection_front_image').live('mouseenter',function(){
		if (jQuery(this).parent().children('.collection_hover_image').length) {
		jQuery(this).fadeOut('slow');
		jQuery(this).parent().find('.collection_hover_image').fadeIn('slow');
		}
	});
	jQuery('.collection_hover_image').live('mouseleave',function(){
		jQuery(this).fadeOut('slow');
		jQuery(this).parent().find('.collection_front_image').fadeIn('slow');
	});
	
	// animate the tag
	jQuery('.column li').live('mouseenter',function(){
		jQuery(this).find('.catalog_item_status').stop().animate({top: '-30px'});
		jQuery(this).css({'border': '2px solid #ccc'});
	}).live('mouseleave',function(){
		jQuery(this).find('.catalog_item_status').stop().animate({top: '-20px'});
		jQuery(this).css({'border': '2px solid transparent'});
	});
	
	// recalculate shipping/tax form
	jQuery('.loc_fields').live('submit',function(e){
		e.preventDefault();
		jQuery('.loc_notice').remove();
		if (jQuery('#pre_country').val() == 0 && jQuery('#pre_region').val() == 0 && jQuery('#pre_zip').val() == 0) {
			jQuery('.loc').append('<span class="loc_notice"><?php _e('Please select a region first.','ocart'); ?></span>');
		} else {
			jQuery.ajax({
				url: '<?php echo get_template_directory_uri(); ?>/ajax/get_fee_by_location.php',
				type: 'post',
				data: jQuery(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					jQuery('.loc').append('<span class="loc_notice"><?php _e('Calculating fees, please wait...','ocart'); ?></span>');
				},
				success: function(data){
					
					jQuery('.loc_notice').remove();
					jQuery('.loc').append('<span class="loc_notice"><?php _e('Thank you. Your shipping and tax are calculated.','ocart'); ?></span>');
					
					// returned data
					jQuery('.calc-shipping span').html(data.new_shipping);
					jQuery('.calc-tax span').html(data.new_tax);
					jQuery('.calc-total span').html(data.new_total);
					
					// shake new data
					jQuery('.shake-total').effect("shake", { times:2, distance: 5 }, 200);

				}
			});
		}
	});
	
	// get change of region in checkout
	if (jQuery('#cform_country').length) {
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/fees_map.php?country=' + jQuery('#cform_country').val() + '&city=' + jQuery('#cform_city').val() + '&state=' + jQuery('#cform_state').val() + '&zip=' + jQuery('#cform_postcode').val() + '&shipping_charges=' + get_additional_charges_shipping() + '&payment_charges=' + get_additional_charges(),
			dataType: 'json',
			success: function(data){
				jQuery('.checkout_est_tax span').html(data.new_tax);
				jQuery('#shipping_fee').html(data.new_shipping);
				jQuery('#order_total').html(data.new_total);
				jQuery('.checkout_total span').effect("bounce", { times:2 }, 400);
			}
		});
	}
	
	// change fees on change
	jQuery('#cform_country, #cform_state, #cform_city, #cform_postcode').live('change',function(){
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/fees_map.php?country=' + jQuery('#cform_country').val() + '&city=' + jQuery('#cform_city').val() + '&state=' + jQuery('#cform_state').val() + '&zip=' + jQuery('#cform_postcode').val() + '&shipping_charges=' + get_additional_charges_shipping() + '&payment_charges=' + get_additional_charges(),
			dataType: 'json',
			success: function(data){
				jQuery('.checkout_est_tax span').html(data.new_tax);
				jQuery('#shipping_fee').html(data.new_shipping);
				jQuery('#order_total').html(data.new_total);
				jQuery('.checkout_total span').effect("bounce", { times:2 }, 400);
			}
		});
	});
	
	// change fees on country2 change
	jQuery('#cform_country2').live('change',function(){
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/fees_map.php?country=' + jQuery('#cform_country2').val() + '&city=' + jQuery('#cform_city').val() + '&state=' + jQuery('#cform_state').val() + '&zip=' + jQuery('#cform_postcode').val() + '&shipping_charges=' + get_additional_charges_shipping() + '&payment_charges=' + get_additional_charges(),
			dataType: 'json',
			success: function(data){
				jQuery('.checkout_est_tax span').html(data.new_tax);
				jQuery('#shipping_fee').html(data.new_shipping);
				jQuery('#order_total').html(data.new_total);
				jQuery('.checkout_total span').effect("bounce", { times:2 }, 400);
			}
		});
	});
	
	// subscribe to sold out product
	jQuery('#subscribe_to_form').live('submit',function(e) {
		e.preventDefault();
		jQuery('.subs-status').hide();
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/product_subscription.php',
			type: 'post',
			dataType: 'json',
			data: jQuery(this).serialize(),
			success: function(data){
				if (data.fail){
					jQuery('.subs-status').fadeIn('slow').removeClass('pass').html(data.fail);
				} else {
					jQuery('.subs-status').fadeIn('slow').html(data.pass).addClass('pass');
					jQuery('#subscribe_to_product, #subscribe_to_button').remove();
				}
			}
		});
	});
	
	// open lightbox
	jQuery('#wishlist a').click(function(e){
		e.preventDefault();
		<?php if (is_user_logged_in()) { // logged in ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/wishlist.php');
		<?php } else { ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');
		<?php } ?>
	});
	
	// remove item from wishlist
	jQuery('.removeFromWishlist').live('click',function(){
	
		<?php if (!is_user_logged_in()) { // logged in ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');
		<?php } else { ?>
		
		var id = jQuery(this).parent().parent().attr('data-ID');
		var list = jQuery(this).parent().parent();
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/remove_from_wishlist.php?id=' + id,
			success: function(data){
				list.fadeOut();
				jQuery('#ajax_wishlist_count').html(data);
				jQuery('#wishlist').fadeOut().fadeIn();
				if (data == 0) { // emtpy
					jQuery('.wishlist').html("<p><?php _e('Your wishlist is empty. You still have not added any product to your wishlist.','ocart'); ?></p>");
				}
			}
		});
		
		<?php } ?>
		
	});
	
	// add item to wishlist
	jQuery('.add_to_wishlist').live('click',function(){
	
		wishlistbutton = jQuery(this);
	
		<?php if (!is_user_logged_in()) { // logged in ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');
		<?php } else { ?>
	
		var id = jQuery(this).attr('data-ID');
		jQuery.scrollTo('#topbar', 500);
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/add_to_wishlist.php?id=' + id,
			success: function(data){
				// auto update wishlist count
				if (data !== '') {
					jQuery('#ajax_wishlist_count').html(data);
					jQuery('#wishlist').fadeOut().fadeIn();
					wishlistbutton.html("<?php _e('Added to Wishlist','ocart'); ?>");
				} else {
					jQuery('#wishlist').fadeOut().fadeIn();
				}
			}
		});
		
		<?php } ?>
		
	});
	
});
</script>