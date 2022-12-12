$(document).ready(function(){
	
	$(".connexion-form-container .connexion-box-container .details-box-container button").click(function(){
		
		if(screen.width > 767){
			$(".connexion-form-container .connexion-box-container").hide(500)
			$(".connexion-form-container .inscription-box-container").show(500)
			
			$(".connexion-form-container .div-slide-bg").css({
				right:"480px"	
			})
			
		}
		
		
		
	})
	
	$(".connexion-form-container .inscription-box-container .details-box-container button").click(function(){
		
		if(screen.width > 767){
			$(".connexion-form-container .connexion-box-container").show(500)
			$(".connexion-form-container .inscription-box-container").hide(500)
			$(".connexion-form-container .div-slide-bg").css({
				right:"0px"	
			})
		}
		
		
	})
	
	if(screen.width <= 767){	
		
		$(".link-create-compte").on("click",function(event){
			event.preventDefault();
			$(".connexion-form-container .connexion-box-container").hide()
			$(".connexion-form-container .inscription-box-container").show()
		})
		
		$(".link-connexion").on("click",function(event){
			event.preventDefault();
			$(".connexion-form-container .connexion-box-container").show()
			$(".connexion-form-container .inscription-box-container").hide()
		})
		
	}
	
	/*Creation d'un compte particulier*/
	inputValidation("#nom","nom",2,15,true);
	inputValidation("#prenoms","prenoms",2,30,true);
	emailInputValidation("#ins-email");
	
	$(".create-particulier-compte").on("click",function(){
		
		$(".create-particulier-compte").attr("disabled","disabled")
		gifLoader(".create-particulier-compte")
		
		const data = {};
		data.nom = $("#nom").val();
		data.prenoms = $("#prenoms").val()
		data.email = $("#ins-email").val();
		
		$.post("/validation/compte/inscription-particulier-validation.php",data, function(data){
			
			if(data.type === "success"){
				
				createSuccessNotif("Un mail vous a été envoyé à votre adresse E-mail veuillez la consulter pour activez votre compte.",3,"top-right")
				setTimeout(function(){$(location).attr("href","/"); }, 3000);
				
			}else{
				
				$(".create-particulier-compte").removeAttr("disabled");
				
				if(data.nom != null){
					$("#nom").addClass("is-invalid");
					$("#nom").parent().find(".error").text(data.nom);
				}else{
					$("#nom").removeClass("is-invalid").addClass("is-valid");
					$("#nom").parent().find(".error").text("");
				}
				
				if(data.prenoms != null){
					$("#prenoms").addClass("is-invalid");
					$("#prenoms").parent().find(".error").text(data.prenoms);
				}else{
					$("#prenoms").removeClass("is-invalid").addClass("is-valid");
					$("#prenoms").parent().find(".error").text("");
				}
				
				
				if(data.email != null){
					$("#ins-email").addClass("is-invalid");
					$("#ins-email").parent().find(".error").text(data.email);
				}else{
					$("#ins-email").removeClass("is-invalid").addClass("is-valid");
					$("#ins-email").parent().find(".error").text("");
				}
				
				if(data.mail != null){
					createErrorNotif(data.mail,5,"top-right");
				}
				
			}
			
		},"json")
		
	})
	
	
	/**
	Connecter un utilisateur
	 */
	$("#btn-connexion").on("click",function(){
		
		$("#btn-connexion").attr("disabled","disabled")
		gifLoader("#btn-connexion")
		
		const data = {};
		data.email = $("#email").val();
		data.password = $("#password").val();
		
		$.post("/validation/compte/connexion-validation.php",data, function(data){
			
			if(data.type === "success"){
				
				$(location).attr("href","/");
				
			}else{
				
				$("#btn-connexion").removeAttr("disabled");
				
				if(data.error != null){
					$(".connexion-error").text(data.error);
				}
				
			}
			
		},"json")
		
	})
	
})