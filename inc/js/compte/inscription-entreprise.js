$(document).ready(function(){
	
	$('.part-container').slick({
		dots: false,
		infinite: true,
		speed: 500,
		slidesToShow: 1,
		slidesToScroll: 1,
		prevArrow: '',
		nextArrow: '',
		autoplay: true,
  		autoplaySpeed: 3000
	});
	
	$("#objectif").on("change",function(){
		
		if($(this).val() === 0){
			var parent = $(this).parent();
			$("select#objectif").css("display","none")
			parent.find(".error").css("display","none")
			parent.append("<input type=\"text\" name=\"objectif-new\" id=\"objectif-new\" required=\"required\" /><div class=\"error\"></div>")
		}
		inputValidation("#objectif-new","objectif",10,50,true);
	})
	
	$("#secteur").on("change",function(){
		
		if($(this).val() === 0){
			var parent = $(this).parent();
			$("select#secteur").css("display","none")
			parent.find(".error").css("display","none")
			parent.append("<input type=\"text\" name=\"secteur-new\" id=\"secteur-new\" required=\"required\" /><div class=\"error\"></div>")
		}
		inputValidation("#secteur-new","secteur",3,30,true);
	})
	
	
	/**Creation d'une entreprise' */
	//formatTelephone("#telephone");
	
	inputValidation("#nom","nom",2,15,true);
	inputValidation("#prenoms","prenoms",2,30,true);
	telInputValidation("#telephone")
	emailInputValidation("#email");
	inputValidation("#entreprise","nom de l'entreprise",2,30,false);
	$("#btn-inscription-entreprise").on("click",function(){
		
		$("#btn-inscription-entreprise").attr("disabled","disabled")
		gifLoader("#btn-inscription-entreprise")
		
		const data = {};
		data.nom = $("#nom").val();
		data.prenoms = $("#prenoms").val()
		data.email = $("#email").val();
		data.telephone = $("#telephone").val();
		data.entreprise = $("#entreprise").val();
		data.nbEmployes = $("#nb-employes").val();
		data.objectif = $("#objectif").val() === 0 ? $("#objectif-new").val() : $("#objectif").val();
		data.secteur = $("#secteur").val() === 0 ? $("#secteur-new").val() : $("#secteur").val();
		
		$.post("/validation/compte/inscription-entreprise-validation.php",data,function(data){
			
			if(data.type === "success"){
				
				createSuccessNotif("Un mail vous a été envoyé à votre adresse E-mail veuillez la consulter pour activez votre compte.",3,"top-right")
				setTimeout(function(){$(location).attr("href","/"); }, 3000);
				
			}else{
				
				$("#btn-inscription-entreprise").removeAttr("disabled");
				
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
					$("#email").addClass("is-invalid");
					$("#email").parent().find(".error").text(data.email);
				}else{
					$("#email").removeClass("is-invalid").addClass("is-valid");
					$("#email").parent().find(".error").text("");
				}
				
				if(data.telephone != null){
					$("#telephone").addClass("is-invalid");
					$("#prenoms").parent().find(".error").text(data.telephone);
				}else{
					$("#telephone").removeClass("is-invalid").addClass("is-valid");
					$("#telephone").parent().find(".error").text("");
				}
				
				
				if(data.entreprise != null){
					$("#entreprise").addClass("is-invalid");
					$("#entreprise").parent().find(".error").text(data.entreprise);
				}else{
					$("#entreprise").removeClass("is-invalid").addClass("is-valid");
					$("#entreprise").parent().find(".error").text("");
				}
				
				if(data.nbEmployes != null){
					$("#nb-employes").addClass("is-invalid");
					$("#nb-employes").parent().find(".error").text(data.nbEmployes);
				}else{
					$("#nb-employes").removeClass("is-invalid").addClass("is-valid");
					$("#nb-employes").parent().find(".error").text("");
				}
				
				
				if(data.objectif != null){
					$("#objectif").addClass("is-invalid");
					$("#objectif").parent().find(".error").text(data.objectif);
				}else{
					$("#objectif").removeClass("is-invalid").addClass("is-valid");
					$("#objectif").parent().find(".error").text("");
				}
				
				if(data.secteur != null){
					$("#secteur").addClass("is-invalid");
					$("#secteur").parent().find(".error").text(data.secteur);
				}else{
					$("#secteur").removeClass("is-invalid").addClass("is-valid");
					$("#secteur").parent().find(".error").text("");
				}
				
				if(data.msg != null){
					createErrorNotif(data.msg, 3, "top-right")
					setTimeout(function(){location.reload(); }, 3000);
				}
				
			}
			
			
		},"json")
		
		
	})
	
	
})