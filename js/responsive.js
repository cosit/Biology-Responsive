jQuery(document).ready(function ($) {

	$(function() {
	    $(window).resize(function() {
	     
	            var width = $(window).width();	    	            
	           	if( width < 668 ) {
	                // code for mobile portrait
	                $("#sidebar").insertAfter(".innerContent");		
	                $("#contactContainer").insertAfter("#sliders"); 

					// **Re-arranging content in single person page ** //
					$(".tabNavigation").insertBefore(".tabs"); 
					$(".person-image").siblings().insertAfter(".personBasics");
					$("#person_photo_links").insertAfter("#person_name");

	                // ********* New menu stuff **********
	                $("#main_header").find('.sub-menu').removeClass('sub-menu').addClass('resp-sub-menu');    
	                $("#main_header").find('.children').removeClass('children').addClass('resp-children');                  
	                // *******************************         

	                $(".tabNavigation").insertBefore(".tabs");   
	                                                          	            
	            }else if( (width > 580) && (width < 960) ) {

	            	// ********* New menu stuff **********
	                $("#main_header").find('.resp-sub-menu').removeClass('resp-sub-menu').addClass('sub-menu'); 	                
	                $("#main_header").find('.resp-children').removeClass('resp-children').addClass('children');  
	                // **************************************

	                // ** Re-arranging Single Person layout ** //
	                $(".tabNavigation").insertAfter(".person-image"); 
					$(".person_website").insertAfter(".tabNavigation");
					$(".person_cv").insertAfter(".tabNavigation");
					$("#person_photo_links").insertAfter("#person_name");

	            	$(".innerContent").insertAfter("#sidebar");
	            	$("#contactContainer").insertBefore("#sliders");
	            	//$(".tabNavigation").insertBefore(".tabs");  
	            	
	            	$(".children").css("display", "");
	            	$(".nav-arrow").removeClass("nav-rotate");	            	
	            	$(".home_search").hide().appendTo("#contactContainer");	
	            	$("#main_menu #startNav, #main_menu #menu-main-menu").show();
	            	
	            	/* Reset the mobile menu attributes.  Collapse everthing and reset icons to down */
	            	$("#main_menu #startNav, #main_menu #startNav .children, #main_menu #menu-main-menu, #main_menu #menu-main-menu .sub-menu, .footerWidgetDiv, #inner_searchform").removeAttr("style");   //	            	
	            	$("#main_menu #startNav .icon-angle-up, #main_menu #menu-main-menu .icon-angle-up").removeClass("icon-angle-up").addClass("icon-angle-down");
	            	$(".icon-double-angle-up").removeClass("icon-double-angle-up").addClass("icon-double-angle-down");   

	            	$("#main_footer .widget").each(function(){
						$(this).css("height","");
					});
     	
	            }else if( width > 960){
	            	$('#contact_list').show();         	
	    			$("#main_menu #startNav, #main_menu #menu-main-menu").show();

	    			// ********* New menu stuff **********
	                $("#main_header").find('.resp-sub-menu').removeClass('resp-sub-menu').addClass('sub-menu'); 	                
	                $("#main_header").find('.resp-children').removeClass('resp-children').addClass('children');  
	                // **************************************

	                /*** Tallest Footer Widget ***/
					var maxHeight = 0;
					$('#main_footer .widget').each(function(){
					   maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight;
					});
					$('#main_footer .widget').each(function(){
						$(this).css("height", maxHeight);
					});
	            } 
	    });	    	    
	});

	/* Function that moves the sidebar and hides the search form if the page is smaller than 580 */
	$(function() {
		var width = $(window).width();
		if( width < 668){					
			$("#sidebar").insertAfter(".innerContent");		
			$('.home_search').hide().insertBefore($("#main_menu #startNav")).removeClass('.home_search');		
			$("#contactContainer").insertAfter("#sliders");

			// **Re-arranging content in single person page ** //
			$(".tabNavigation").insertBefore(".tabs"); 
			$(".person-image").siblings().insertAfter(".personBasics");
			$("#person_photo_links").insertAfter("#person_name");

			// ********** New Menu stuff ********
			$("#main_header").find('.sub-menu').removeClass('sub-menu').addClass('resp-sub-menu');
			$("#main_header").find('.children').removeClass('children').addClass('resp-children'); 
			// *********************************    
		}
		else{
			$('.home_search').show().appendTo("#contactContainer");	
		}
		$(".mobile_search").hide().insertBefore($("#main_menu #menu-main-menu"));				
		if( width < 960){
			$("#main_footer .widget").each(function(){
				$(this).css("height","");
			});
		}
	});
	/******************/

	/*****************/
	/* Functions to show/hide the widget information when it's in the phone view < 540px */	
	$('#first-footer-widget-area h3.title').click(function(){	
		if( $(window).width() < 580 ){	
			$(this).find('i').toggleClass('icon-double-angle-down icon-double-angle-up');
			$(this).next('.footerWidgetDiv').toggle('fast');		
			return false;
		}
	});
	$('#second-footer-widget-area h3.title').click(function(){	
		if( $(window).width() < 580 ){		
			$(this).find('i').toggleClass('icon-double-angle-down icon-double-angle-up');
			$(this).next('.footerWidgetDiv').toggle('fast');		
			return false;
		}
	});
	$('#third-footer-widget-area h3.title').click(function(){	
		if( $(window).width() < 580 ){	
			$(this).find('i').toggleClass('icon-double-angle-down icon-double-angle-up');
			$(this).next('.footerWidgetDiv').toggle('fast');		
			return false;
		}
	});
	$('#contactContainer h2 span.contact_title').click(function(){
		if($(window).width() < 1000){
			$('#contactContainer h2 .contact-drop').toggleClass('icon-double-angle-down icon-double-angle-up');
			$('#contact_list').toggle('fast');
			return false;
		}
	});
	$('#contact h2 .icon-search').click(function(){
		if($(window).width() < 1000 && $(window).width() > 580 ){
			//$('#contact h2 .contact-drop').toggleClass('icon-double-angle-down icon-double-angle-up');
			$('#inner_searchform').toggle();
			return false;
		}
	});	
	/******************/


	/* If the 'Front Page Slider Right' Widget does NOT contain the Contact Widget 
	   then activate the slider on hover nav.  It checks to see if the width is 	
	   greater than 860 which is a responsive breakpoint that will automatically
	   start showing the Flex Direction Nav on hover
	 */
	if( $("#contact").length == 0){	
		$('#sliders .flexslider').hover( 
			function(){
				if($(window).width() > 860){
					$('.flex-next, .flex-prev').css({
						'opacity' : '.8',
						'filter' : 'alpha(opacity=80)'
					});
				}				
			}, function(){
				if($(window).width() > 860){
					$('.flex-next, .flex-prev').css({
						'opacity' : '0',
						'filter' : 'alpha(opacity=0)'
					});				
				}
			}
		);
	}
	
		
	// Append the mobile icon nav
	
	// Add a <span> to every .nav-item that has a <ul> inside
	$('#main_menu .parent').has('ul').prepend('<span class="nav-click"><i class="icon-angle-down"></i></span>');
	$('#main_menu .children .parent>a, #main_menu .sub-menu .parent>a').append('<i class="icon-double-angle-right"></i>');	
	
	// Click to reveal the nav
	$('.icon-reorder').click(function(){
		$('nav >ul').toggle('fast');
		$('#inner_searchform').toggle('fast');
	});

	// Dynamic binding to on 'click'
	$('nav >ul').on('click', '.nav-click i', function(){
	
		// Toggle the nested nav
		$(this).parents().siblings('.resp-children').toggle('fast');
		$(this).parents().siblings('.resp-sub-menu').toggle('fast');
		
		// Toggle the arrow using CSS3 transforms
		$(this).toggleClass('icon-angle-down icon-angle-up');			
	});
});	