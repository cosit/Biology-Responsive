jQuery(document).ready(function ($) {

	var colorDarkBlue = "#22282D";
	var colorOffWhite = "#F2F2F2";
	var colorBlueGray = "#475159";
	var colorLightBlueGray = "#6C747A";

	// Slider
	try{
		$('.flexslider').flexslider({
			animation: "fade",
			slideshow: "true",
			slideshowSpeed: 7000
		});
	} catch(err){ }

	// Indicate people nav link
	$('#main_header nav>ul>li>a:contains("People")').attr("id", "peopleLink").parent().addClass('peopleNav');

    // Display people categories in nav menu
	$('.peopleNav').append( $('#people_cats') );
	

	// Contact Box and Search Form
	$('#contact h2').wrapInner('<span />');
	$('ul#contact_list li:nth-child(odd)').addClass('contactOdd');


	// Events styling for date
	$('.eventDate').each(function(){
		var monthNames = monthNames || ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
		var $this = $(this);
		var rawDate = $this.html();
		// var date = rawDate.split(/[\/-]/);
		var date = rawDate.split(' ');
		var day = date[0].trim();
		var month = date[1].trim();
		var year = date[2].trim();

		// formats and outputs the date in a template that can be styled
		$this.html('<ul><li id="eventMonth">'+month+'</li>'
						+'<li id="eventDay">'+day+'</li>'
						+'<li id="eventYear">'+year+'</li>'
						+'<li id="eventDate">'+rawDate+'</ul>');
	});

	// Ensure that the entire area of a navigational element is clickable
	// $('.events article, nav li').click(function(){
	// 	window.location = $(this).find('a').attr('href');
	// });

	//Back to top button
	var scrollToTop = function(){
		$('html, body').animate({scrollTop: 0}, 'medium'/*, 'easeInOutCubic'*/);
		return false;
	};

	$('#backToTop>a').click( scrollToTop );
	$(window).scroll(function(){
		if( $(window).scrollTop() > 10 ){
			$('#backToTop').fadeIn('slow');
		} else {
			$('#backToTop').fadeOut('slow');
		}
	});

	// Custom menu nav shenanigans v2.0
	$(function() {
		var top_level = $('#custom_menu_nav>div>ul>li.current_page_ancestor');

		// If the parent item is a custom menu item (not a page)
		if( top_level.length == 0){
			top_level = $('#custom_menu_nav>div>ul li.current-menu-ancestor');
		}		
		
		//If the current page is not part of the custom menu		
		if(top_level.length == 0){
			top_level = $('#custom_menu_nav>div>ul li.current-menu-item');
			if(top_level.length == 0){
				$('#custom_menu_nav').hide();
			}
			else{
				$('#custom_menu_nav').prepend( top_level.children('ul').show() ).prepend('<h2>'+top_level.html()+'</h2>');	
				$('#custom_menu_nav>h2 .expand').hide();	//Remove any extra expands
				$('#custom_menu_nav>div').hide();
				$('#custom_menu_nav .current-menu-item').prepend("<div class='arrow-right'></div>");
			}
			
		} else{
			$('#custom_menu_nav').prepend( top_level.children('ul').show() ).prepend('<h2>'+top_level.html()+'</h2>');		
			$('#custom_menu_nav>h2 .expand').hide();	//Remove any extra expands
			$('#custom_menu_nav>div').hide();
			$('#custom_menu_nav .current-menu-item>a').prepend("<div class='arrow-right'></div>");
		}
	});
	


	//Function for Tabs on Person Page
	$(function () {
		var tabContainers = $('div.tabs > div');
		tabContainers.hide().filter(':first').show();			
		$('ul.tabNavigation a').click(function () {
			tabContainers.hide();
			tabContainers.filter(this.hash).show();
			$('ul.tabNavigation a').removeClass('selected');
			$(this).addClass('selected');
			return false;
		}).filter(':first').click();
	});

	// Parent finding for nav li elements 
	$('nav li').has('.children, .sub-menu').addClass('parent');

	// Page nav expand
	// $('.pageNav li.parent').prepend('<span class="expand"><div>+</div></span>');
	// $('.pageNav .expand>div').click( function(){
	// 	$this = $(this);
	// 	$children = $this.parent().parent().children('.children, .sub-menu');
	// 	if($children.is(':visible')){
	// 		$this.text('+');
	// 		$children.hide();
	// 	} else {
	// 		$this.text('-');
	// 		$children.show();
	// 	};
	// });
	// $('.pageNav li.current_page_item.parent>span.expand>div').trigger('click');
	
    // Hide empty list items in people display
    $('.personBasics li').filter(function(){
    	return $.trim($(this).text()) === '';
    }).hide();

    // Display page/people nav correctly
    $('#main_content>div.wrap').append( $('.innerContent .pageNav, .innerContent .peopleNav').remove() );


	// Link Icons 
	$('.innerContent a').parent('li').addClass('link www');
	$('a[href$=\\.pdf], a[href$=\\.PDF]').parent('li').removeClass('www').addClass('pdf');
	$('a[href$=\\.doc], a[href$=\\.DOC], a[href$=\\.docx], a[href$=\\.DOCX]').parent('li').removeClass('www').addClass('doc');
	$('a[href$=\\.ppt], a[href$=\\.PPT], a[href$=\\.pptx], a[href$=\\.PPTX]').parent('li').removeClass('www').addClass('ppt');
	$('a[href$=\\.xls], a[href$=\\.XLS], a[href$=\\.xlsx], a[href$=\\.XLSX]' ).parent('li').removeClass('www').addClass('excel');
	$('.personBasics li, .personTabs li, .tabNavigation li').removeClass('link www pdf doc');

	// Disable same-page clicking
	//$('.current_page_item>a').contents().unwrap().wrap('<span class="unclickable"></span>');

	// Replace title of page if longer one already exists in post
	(function(){
		var oldTitle = $('.innerContent>article>header>h1');
		var newTitle = oldTitle.parent().next('h1');

		if( newTitle.length > 0 ){ oldTitle.replaceWith(newTitle); }
	})();


	// ******************************************
  	// Responsive Code for YouTUbe and Vimeo
  	// Find all YouTube videos
	var $allVideos = $("iframe[src^='http://player.vimeo.com'], iframe[src^='http://www.youtube.com']"),

	    // The element that is fluid width
	    $fluidEl = $(".innerContent");

	// Figure out and save aspect ratio for each video
	$allVideos.each(function() {

	  $(this)
	    .data('aspectRatio', this.height / this.width)

	    // and remove the hard coded width/height
	    .removeAttr('height')
	    .removeAttr('width');

	});

	// When the window is resized
	$(window).resize(function() {

	  var newWidth = $fluidEl.width();

	  // Resize all videos according to their own aspect ratio
	  $allVideos.each(function() {

	    var $el = $(this);
	    $el
	      .width(newWidth)
	      .height(newWidth * $el.data('aspectRatio'));

	  });

	// Kick off one resize to fix all videos on page load
	}).resize();
	// ****************************

	// ****************************************
	// Add >> to menu items that have children
	$('#main_menu .children .parent>a, #main_menu .sub-menu .parent>a').append('<i class="icon-double-angle-right"></i>');	


	// **********************************
	// Main nav links and dropdown menu
	// **********************************
	$('#main_header nav>ul>li').hover(
		function(){
			$(this).children('ul.children').show(); 
			$(this).children('ul.sub-menu').show(); 
		},
		function(){ 
			if( !$(this).hasClass('current_page_item') ){
				$(this).children('ul.children').hide();
				$(this).children('ul.sub-menu').hide();

			} else {
				$(this).children('ul.children').hide();
				$(this).children('ul.sub-menu').hide();
			}
		}
	);
	$('#main_header nav>ul>li>ul>li').hover(
		function(){
			$(this).children('ul.children').show();
			$(this).children('ul.sub-menu').show();
		},
		function(){ 
			if( !$(this).hasClass('current_page_item') ){
				$(this).children('ul.children').hide();
				$(this).children('ul.sub-menu').hide();
			} else {
				$(this).children('ul.children').hide();
				$(this).children('ul.sub-menu').hide();
			}
		}
	);

	$('#main_header nav>ul>li>ul>li>ul>li').hover(
		function(){
			$(this).children('ul.children').show();
			$(this).children('ul.sub-menu').show();

		},
		function(){ 
			if( !$(this).hasClass('current_page_item') ){
				$(this).children('ul.children').hide();
				$(this).children('ul.sub-menu').hide();
			} else {
				$(this).children('ul.children').hide();
				$(this).children('ul.sub-menu').hide();
			}
		}
	);
	// **************************

	/*****************************/
	/*** Tallest Footer Widget ***/
	var maxHeight = 0;
	$('#main_footer .widget').each(function(){
	   maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight;
	});
	$('#main_footer .widget').each(function(){
		$(this).css("height", maxHeight);
	});

	// ********************
	// * Smooth Scrolling *
	// ********************
	$('a[href*="#"]:not([href="#"])').click(function() {
  		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	    	var target = $(this.hash);
	    	target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      	if (target.length) {
	        	$('html, body').animate({
	          		scrollTop: target.offset().top
	        	}, 'medium');
	        	return false;
      		}
    	}
  	});	

});