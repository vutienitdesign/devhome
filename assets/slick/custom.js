jQuery(document).on('ready', function() {

	jQuery('.slider-partner').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 10,
		adaptiveHeight: true,
		arrows: true,
		centerMode: true,
		vertical: false,        
		autoplay: true,
		autoplaySpeed: 3000,		
		prevArrow: '<span class="slick-prev"><img src="https://i.imgur.com/bmId1mz.png" /></span>',
		nextArrow: '<span class="slick-next"><img src="https://i.imgur.com/bhe8mGn.png" /></span>',		
		responsive: [
			{
			  breakpoint: 1281,
			  settings: {
			    slidesToShow: 8,
			    slidesToScroll: 1
			  }
			},		
			{
			  breakpoint: 1025,
			  settings: {
			    slidesToShow: 6,
			    slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 768,
			  settings: {
			    slidesToShow: 2,
			    slidesToScroll: 1
			  }
			}
		]		
	});

	jQuery("#bvct #btlq #slider").slick({
		dots: false,
		infinite: true,
		arrows: true,
		centerMode: false,
		slidesToShow: 3, 
		vertical: false,        
		autoplay: false,
		autoplaySpeed: 3000,
		prevArrow: '<span class="slick-prev"><img src="https://i.imgur.com/bmId1mz.png" /></span>',
		nextArrow: '<span class="slick-next"><img src="https://i.imgur.com/bhe8mGn.png" /></span>',			
		responsive: [
			{
			  breakpoint: 1025,
			  settings: {
			    slidesToShow: 2,
			    slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			  }
			}
		]							
	});	
	jQuery("#dmsp #layout_2 .slider1").slick({
		dots: false,
		infinite: true,
		arrows: true,
		centerMode: false,
		slidesToShow: 6, 
		vertical: false,        
		autoplay: false,
		autoplaySpeed: 3000,
		prevArrow: '<span class="slick-prev"><img src="https://i.imgur.com/bmId1mz.png" /></span>',
		nextArrow: '<span class="slick-next"><img src="https://i.imgur.com/bhe8mGn.png" /></span>',			
		responsive: [
			{
			  breakpoint: 1025,
			  settings: {
			    slidesToShow: 4,
			    slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
			    slidesToShow: 2,
			    slidesToScroll: 1
			  }
			}
		]							
	});
	jQuery('.slider-for').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		asNavFor: '.slider-nav',
		responsive: [
			{
			  breakpoint: 768,
			  settings: {
			  	arrows: true,
				prevArrow: '<span class="slick-prev"><img src="https://i.imgur.com/bmId1mz.png" /></span>',
				nextArrow: '<span class="slick-next"><img src="https://i.imgur.com/bhe8mGn.png" /></span>',			
			 	}
			}
		]		
	});
	jQuery('.slider-nav').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor: '.slider-for',
		prevArrow: '<span class="slick-prev"><img src="https://www.upsieutoc.com/images/2019/05/22/Mui-ten-phai-copy.png" /></span>',
		nextArrow: '<span class="slick-next"><img src="https://www.upsieutoc.com/images/2019/05/22/Mui-ten-phai.png" /></span>',			
		//dots: true,
		centerMode: false,
		//infinite: true,
		//arrows: true,	
		vertical: true, 
		verticalSwiping: true, 			
		focusOnSelect: true,
		responsive: [
			{
			  breakpoint: 768,
			  settings: {
				vertical: false, 
				verticalSwiping: false, 
			  }
			}
		]		
	});
	jQuery("#layout_3_spct #layout_3_1_2 #slider").slick({
		dots: false,
		infinite: true,
		arrows: true,
		centerMode: false,
		slidesToShow: 3, 
		vertical: false,        
		autoplay: false,
		autoplaySpeed: 3000,
		prevArrow: '<span class="slick-prev"><img src="https://i.imgur.com/bmId1mz.png" /></span>',
		nextArrow: '<span class="slick-next"><img src="https://i.imgur.com/bhe8mGn.png" /></span>',			
		responsive: [
			{
			  breakpoint: 1025,
			  settings: {
			    slidesToShow: 3,
			    slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			  }
			}
		]							
	}); 
	jQuery("#layout_2_spct #slider").slick({
		dots: false,
		infinite: true,
		arrows: true,
		centerMode: false,
		slidesToShow: 4,  
		vertical: false,       
		autoplay: false,
		autoplaySpeed: 3000,
		prevArrow: '<span class="slick-prev"><img src="https://i.imgur.com/bmId1mz.png" /></span>',
		nextArrow: '<span class="slick-next"><img src="https://i.imgur.com/bhe8mGn.png" /></span>',			
		responsive: [
			{
			  breakpoint: 1025,
			  settings: {
			    slidesToShow: 2,
			    slidesToScroll: 1
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			  }
			}
		]							
	});	
		 	  
});
    