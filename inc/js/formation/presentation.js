$(document).ready(function(){
	
	$(window).scroll(function(){
		if($(window).scrollTop() >= 250){
			$('.formation-info-menu').css({
				position: "fixed",
				zIndex:"2",
				top: "60px",
			})
		}else{
			$('.formation-info-menu').css({
				position: "relative",
				zIndex:"2",
				top: "0"
			})
		}
	})
	
	/*$('.other-formation-box').slick({
		dots: false,
		infinite: true,
		speed: 500,
		slidesToShow: 4,
		slidesToScroll: 4,
		prevArrow: false,
		nextArrow: false,
		autoplay: true,
  		autoplaySpeed: 3000
	});*/
	
	
	//lors du click sur le btn s'enregistrer
	$("#btn-register").on("click",function(){
		$("#btn-register").attr("disabled","disabled")
		gifLoader("#btn-register")
		
		$.post("/validation/formation/register-particulier-facture-validation.php",{id: $(this).val()},function(data){
			
			if(data.type == "success"){
				
				$(location).attr("href","/panier");
				
			}else if(data.type == "session"){
				
				$(location).attr("href","/compte/connexion");
				
			}else{
				
				$("#btn-register").removeAttr("disabled");
				
				if(data.msg != null){
					createErrorNotif(data.msg,5,"top-right");
				}
			}
			
		},"json")
		
	})
	
	//lors du click sur le btn s'enregistrer mobile
	$("#mobile-btn-register").on("click",function(){
		$("mobile-btn-register").attr("disabled","disabled")
		gifLoader("#mobile-btn-register")
		
		$.post("/validation/formation/register-particulier-facture-validation.php",{id: $(this).val()},function(data){
			
			if(data.type == "success"){
				
				$(location).attr("href","/panier");
				
			}else if(data.type == "session"){
				
				$(location).attr("href","/compte/connexion");
				
			}else{
				
				$("#mobile-btn-register").removeAttr("disabled");
				
				if(data.msg != null){
					createErrorNotif(data.msg,5,"top-right");
				}
			}
			
		},"json")
		
	})
	
})