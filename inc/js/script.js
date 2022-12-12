$(document).ready(function(){
	
	
	//var positionElementInPage = $('header').offset().top;
	
	$(window).scroll(function(){
		if($(window).scrollTop() >= 70){
			$('header').css({
				position: "fixed",
				zIndex:"3",
				top: "0",
			})
		}
	})
	
	
	$(window).scroll(function(){
		if($(window).scrollTop() >= 300 && screen.width > 989){
			$('.page-ancre').css({
				display: "block",
			})
		}else{
			$('.page-ancre').css({
				display: "none",
			})
		}
	})
	
	
	$(".page-ancre").on("click",function(event){
		
		event.preventDefault();
		
		$(window).scrollTop(0);
		
	})
	
	/*menu mobile*/
	if(screen.width <= 989){
		
		$(".res-menu-icon").on("click",function(){
			$(".mobile-menu").css("left","0%");
		})
		
		$(".mobile-menu .close-menu").on("click",function(){
			$(".mobile-menu").css("left","100%");
			$(".nav-smenu").parent().find("i").removeClass("bi-plus").removeClass("bi-dash").addClass("bi-plus")
		})
		
		$(".nav-smenu").on("click",function(){
			$(this).toggleClass("show-menu");
			var parent = $(this).parent().find("i");
			if(parent.hasClass("bi-plus")){
				parent.removeClass("bi-plus").addClass("bi-dash")
			}else{
				parent.removeClass("bi-dash").addClass("bi-plus")
			}
			
		})
		
	}
	
	/***********FOOTER */
	if(screen.width <= 767){
		/*$("footer .col-md-4 ul").css("display", "none");
		$("footer .col-md-4 .rs-content").css("display", "none");*/
		
		var parent = $("footer .col-md-4");
		
		parent.on("click",function(){
			$(this).toggleClass("show-fmenu");
			if(parent.find("i").hasClass("bi-caret-down-fill")){
				parent.find("i").removeClass("bi-caret-down-fill").addClass("bi-caret-up-fill")
			}else{
				parent.find("i").removeClass("bi-caret-up-fill").addClass("bi-caret-down-fill")
			}
		})
		
		
		
	}
	
	/*Le bar de recherche sur toutes les pages*/
	$("#search-input").keyup(function(){
		if($(this).val().length >= 5){
			const search = $(this).val();
			
			$.post("/validation/formation/search-formation-validation.php",{search},function(data){
				$(".search-page .result-container").html("");
				
					var html = "";
					data.forEach(function(formation){
						html += "<p><a href='/formations/"+formation.domaineUrl+"/"+formation.titreUrl+"'>"+formation.titre+"</a></p>"
					})
					
					$(".search-page .result-container").append(html);
				
				
			},"json")
		}else{
			$(".search-page .result-container").html("");
		}
		
	})
	
	
	$(".search-page").hide();
	$(".search-page span").click(function(){
		$(".search-page").hide();
	})
	
	$(".search-container").click(function(event){
		event.preventDefault();
		$(".search-page").show()
	})
	
	
})