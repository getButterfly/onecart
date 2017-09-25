/****************************************
	Barebones Lightbox Template
	by Kyle Schaeffer
	http://www.kyleschaeffer.com
	* requires jQuery
****************************************/

// display the lightbox
function lightbox(insertContent, ajaxContentUrl, inputFocus, postID, postURL){

	if (postURL) {
		link = $(this).attr('href');
		window.history.pushState(null,null, postURL);
	}

	// jQuery wrapper (optional, for compatibility only)
	(function($) {
	
		// close cart popup
		$('.cartpopup').hide();
		
		// hide active tooltips
		$('.tipsy').remove();
		
		// restore slideshow
		$('.product, .main-image').remove();
		$('#details').stop().animate({left: '-200%', opacity: 0}, {duration: 1200, complete:function(){
			$('.iosSlider').fadeIn('slow');
			$('#details').empty();
		}});
	
		// add lightbox/shadow <div/>'s if not previously added
		if($('#lightbox').size() == 0){
			var theLightbox = $('<div id="lightbox"/>');
			var theShadow = $('<div id="lightbox-shadow"/>');
			$(theShadow).click(function(e){
				closeLightbox(id='');
			});
			$('body').append(theShadow);
			$('body').append(theLightbox);
		}
		
		// remove any previously added content
		$('#lightbox').empty();
		
		// insert HTML content
		if(insertContent != null){
			$('#lightbox').append(insertContent);
		}
		
		// insert AJAX content
		if(ajaxContentUrl != null){
			// temporarily add a "Loading..." message in the lightbox
			$('#lightbox').append('<div class="loading"></div>');
			
			// if post ID is passed
			if (postID != null) {
				ajaxContentUrl = ajaxContentUrl + '?p=' + postID;
			}
			
			// request AJAX content
			deviceWidth = $(window).width();
			$.ajax({
				type: 'GET',
				url: ajaxContentUrl,
				success:function(data){
					
					// remove "Loading..." message and append AJAX content
					$('#lightbox').empty();
					$('#lightbox').append(data);
					
					// load FB, twitter
					FB.XFBML.parse();
					twttr.widgets.load();
					
					// tooltips
					if (deviceWidth > 800) {
						$('.tip').tipsy({
							gravity: 'w',
							offset: 8
						});
						$('.form_text').tipsy({
							gravity: 'w',
							trigger: 'focus',
							offset: 8
						});
					}
					
					// focus input on lightbox load
					if (inputFocus != null) {
						$('#'+inputFocus).focus();
					}
					
				},
				error:function(){
					// ajax failed
				}
			});
		}
		
		// move the lightbox to the current window top + 100px
		$('#lightbox').css('top', $('document').scrollTop() + 100 + 'px');
		$.scrollTo('#wrapper');
		
		// display the lightbox
		$('#lightbox').show();
		$('#lightbox-shadow').show();
		
		// shadow height after data is loaded
		$('#lightbox-shadow').css({height: $('body').height() });
	
	})(jQuery); // end jQuery wrapper
	
}

// close the lightbox
function closeLightbox(id){
	
	// jQuery wrapper (optional, for compatibility only)
	(function($) {
		
		// hide lightbox/shadow <div/>'s
		$('#lightbox').fadeOut();
		$('#lightbox-shadow').fadeOut();
		$('.tipsy').remove();
		
		// remove contents of lightbox in case a video or other content is actively playing
		$('#lightbox').empty();
		
		if (id) {
		$.scrollTo('#product-' + id, 800);
		}
	
	})(jQuery); // end jQuery wrapper
	
}