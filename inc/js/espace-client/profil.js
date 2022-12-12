$(document).ready(function(){
	
	$("#naissance").datepicker({
		monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
			"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
		monthNamesShort: [ "Janv.", "Févr.", "Mars", "Avr.", "Mai", "Juin",
			"Juil.", "Août", "Sept.", "Oct.", "Nov.", "Déc." ],
		dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
		dayNamesShort: [ "Dim.", "Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam." ],
		dayNamesMin: [ "D","L","M","M","J","V","S" ],
		prevText: "Précédent",
		nextText: "Suivant",
		currentText: "Aujourd'hui",
		dateFormat:"dd/mm/yy",
		yearRange:"1900:c",
		changeMonth:true,
		changeYear:true
	});
	
	
	
	/*Changer les informations personnelles*/
	//formatTelephone("#telephone");
	
	inputValidation("#nom","nom",2,15,true);
	inputValidation("#prenoms","prenoms",2,30,true);
	telInputValidation("#telephone")
	dateInputValidation("#naissance")
	inputValidation("#fonction","fonction",5,30,false);
	$("#update-infos-perso").on("click",function(){
		
		$("#update-infos-perso").attr("disabled","disabled")
		gifLoader("#update-infos-perso")
		
		var data = new Object();
		data.nom = $("#nom").val();
		data.prenoms = $("#prenoms").val()
		data.telephone = $("#telephone").val();
		data.naissance = $("#naissance").val()
		data.pays = $("#pays").val();
		data.fonction = $("#fonction").val();
		
		$.post("/validation/espace-client/update-infos-perso-validation.php",data,function(data){
			
			if(data.type == "success"){
				location.reload();
			}else if(data.type == "session"){
				
				$(location).attr("href","/compte/connexion")
				
			}else{
				
				$("#update-infos-perso").removeAttr("disabled");
				
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
				
				if(data.telephone != null){
					$("#telephone").addClass("is-invalid");
					$("#telephone").parent().find(".error").text(data.telephone);
				}else{
					$("#telephone").removeClass("is-invalid").addClass("is-valid");
					$("#telephone").parent().find(".error").text("");
				}
				
				
				if(data.naissance != null){
					$("#naissance").addClass("is-invalid");
					$("#naissance").parent().find(".error").text(data.naissance);
				}else{
					$("#naissance").removeClass("is-invalid").addClass("is-valid");
					$("#naissance").parent().find(".error").text("");
				}
				
				if(data.pays != null){
					$("#pays").addClass("is-invalid");
					$("#pays").parent().find(".error").text(data.pays);
				}else{
					$("#pays").removeClass("is-invalid").addClass("is-valid");
					$("#pays").parent().find(".error").text("");
				}
				
				
				if(data.fonction != null){
					$("#fonction").addClass("is-invalid");
					$("#fonction").parent().find(".error").text(data.fonction);
				}else{
					$("#fonction").removeClass("is-invalid").addClass("is-valid");
					$("#fonction").parent().find(".error").text("");
				}
				
				
			}
			
		},"json")
		
	})
	
	/*Changement de l'adresse mail*/
	emailInputValidation("#email");
	emailInputValidation("#new-email");
	$("#btn-update-email").on("click",function(){
		
		$("#btn-update-email").attr("disabled","disabled");
		gifLoader("#btn-update-email");
		
		var data = new Object();
		data.email = $("#email").val();
		data.newEmail = $("#new-email").val();
		
		$.post("/validation/espace-client/update-email-validation.php",data,function(data){
			
			if(data.type == "success"){
				
				createSuccessNotif("Un mail vous a été envoyé à votre nouvelle adresse E-mail veuillez la consulter pour avoir vos nouveaux identifiants de connexion.",5,"top-right")
				setTimeout(function(){$(location).attr("href","/compte/connexion"); }, 5000);
				
			}else if(data.type == "session"){
				
				$(location).attr("href","/compte/connexion")
				
			}else{
				
				$("#btn-update-email").removeAttr("disabled");
				
				if(data.email != null){
					$("#email").addClass("is-invalid");
					$("#email").parent().find(".error").text(data.email);
				}else{
					$("#email").removeClass("is-invalid").addClass("is-valid");
					$("#email").parent().find(".error").text("");
				}
				
				if(data.newEmail != null){
					$("#new-email").addClass("is-invalid");
					$("#new-email").parent().find(".error").text(data.newEmail);
				}else{
					$("#new-email").removeClass("is-invalid").addClass("is-valid");
					$("#new-email").parent().find(".error").text("");
				}
				
				if(data.mail != null){
					createErrorNotif(data.mail,5,"top-right");
				}
				
			}
			
		},"json")
		
	});
	
	/**Changement de mot de passe */
	passwordInputValidation("#password");
	repeatPasswordInputValidation("#password","#repeat-password");
	$("#btn-update-password").on("click",function(){
		
		$("#btn-update-password").attr("disabled","disabled");
		gifLoader("#btn-update-password");
		
		var data = new Object();
		data.oldPassword = $("#old-password").val();
		data.password = $("#password").val();
		data.repeatPassword = $("#repeat-password").val();
		
		$.post("/validation/espace-client/update-password-validation.php",data,function(data){
			
			if(data.type == "success"){
				
				createSuccessNotif("Votre mot de passe à été redefini avec succès. Votre compte sera deconnecté..",3,"top-right")
				setTimeout(function(){$(location).attr("href","/compte/connexion"); }, 3000);
				
			}else if(data.type == "session"){
				
				$(location).attr("href","/compte/connexion")
				
			}else{
				
				$("#btn-update-password").removeAttr("disabled");
				
				if(data.oldPassword != null){
					$("#old-password").addClass("is-invalid");
					$("#old-password").parent().find(".error").text(data.oldPassword);
				}else{
					$("#old-password").removeClass("is-invalid").addClass("is-valid");
					$("#old-password").parent().find(".error").text("");
				}
				
				if(data.password != null){
					$("#password").addClass("is-invalid");
					$("#password").parent().find(".error").text(data.password);
				}else{
					$("#password").removeClass("is-invalid").addClass("is-valid");
					$("#password").parent().find(".error").text("");
				}
				
				if(data.repeatPassword != null){
					$("#repeat-password").addClass("is-invalid");
					$("#repeat-password").parent().find(".error").text(data.repeatPassword);
				}else{
					$("#repeat-password").removeClass("is-invalid").addClass("is-valid");
					$("#repeat-password").parent().find(".error").text("");
				}
				
				
			}
			
		},"json")
		
		
	});
	
	/**Upload de la photo de profil' */
	$("#profilFile").change(function() {		
		$("#profil-picture-form").trigger("submit");
	});
	
	$("#profil-picture-form").submit(function(event){
		event.preventDefault();

		$.ajax({
			url: "/validation/espace-client/update-profil-picture-validation.php",
			method: "POST",
			data: new FormData(this),
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(data) {
				if (data.type == "success") {
					location.reload();
				} else if(data.type == "session"){
					$(location).attr("href","/compte/login")
				}else{
					location.reload();
				}
			}

		});
		
	})
	
})