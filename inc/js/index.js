$(document).ready(function(){
	
	$('.pub-container').slick({
		dots: false,
		infinite: true,
		speed: 500,
		slidesToShow: 4,
		slidesToScroll: 2,
		prevArrow: '.pub-prev',
		nextArrow: '.pub-next',
		autoplay: true,
  		autoplaySpeed: 3000,
  		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2,
					dots: false
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					dots: false
				}
			},
			{
				breakpoint: 420,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			}
			
		]
	});
	
})