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
	
	$("#objectif").change(function(){
		
		if($(this).val() == 0){
			const parent = $(this).parent();
			$("select#objectif").css("display","none")
			parent.find(".error").css("display","none")
			parent.append("<input type=\"text\" name=\"objectif-new\" id=\"objectif-new\" required=\"required\" /><div class=\"error\"></div>")
		}
		inputValidation("#objectif-new","objectif",10,50,true);
	})
	
	$("#secteur").change(function(){
		
		if($(this).val() == 0){
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
		$(".error").text("")
		
		const data = {};
		data.nom = $("#nom").val();
		data.prenoms = $("#prenoms").val()
		data.email = $("#email").val();
		data.telephone = $("#telephone").val();
		data.entreprise = $("#entreprise").val();
		data.employes = $("#employes").val();
		data.objectif = $("#objectif").val() == 0 ? $("#objectif-new").val() : $("#objectif").val();
		data.secteur = $("#secteur").val() == 0 ? $("#secteur-new").val() : $("#secteur").val();
		
		$.post("/validation/compte/inscription-entreprise-validation.php",data,function(data){
			
			if(data.type === "success"){
				
				createSuccessNotif("Un mail vous a été envoyé à votre adresse E-mail veuillez la consulter pour activez votre compte.",3,"top-right")
				setTimeout(function(){$(location).attr("href","/"); }, 3000);
				
			}else{
				
				$("#btn-inscription-entreprise").removeAttr("disabled");

				Object.entries(data).forEach(([key, value]) => {
					$("#"+key).addClass("is-invalid")
					$("#"+key).parent().find(".error").text(value)
				})
				
				if(data.msg != null){
					createErrorNotif(data.msg, 3, "top-right")
					setTimeout(function(){location.reload(); }, 3000);
				}
				
			}
			
			
		},"json")
		
		
	})
	
	
})