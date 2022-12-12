$(document).ready(function() {
	
	/*Ajouter un nouvel participant*/
	
	inputValidation("#nom","nom",2,30,true);
	inputValidation("#prenoms","prenoms",2,150,true);
	telParticipantInputValidation("#telephone");
	emailInputValidation("#email");
	inputValidation("#fonction","fonction",5,30,true);
	$(".btn-add-participant").on("click", function() {
		const formationId = $(this).val();

		$("#btn-confirm-add-participant").on("click", function() {

			$("#btn-confirm-add-participant").attr("disabled", "disabled")
			gifLoader("#btn-confirm-add-participant")
			$(".erreur").text("");

			var data = new Object();
			data.nom = $("#nom").val();
			data.prenoms = $("#prenoms").val();
			data.email = $("#email").val();
			data.telephone = $("#telephone").val();
			data.fonction = $("#fonction").val();
			data.formationId = formationId;

			$.post("/validation/entreprise/add-participant-validation.php", data, function(data) {

				if (data.type == "success") {

					location.reload();

				} else if(data.type == "session"){
					
					$(location).attr("href","/compte/connexion")	
					
				}else {

					$("#btn-confirm-add-participant").removeAttr("disabled")

					if (data.nom != null) {
						$("#nom").addClass("is-invalid")
						$("#nom").parent().find(".error").text(data.nom)
					} else {
						$("#nom").removeClass("is-invalid").addClass("is-valid")
						$("#nom").parent().find(".error").text("")
					}

					if (data.prenoms != null) {
						$("#prenoms").addClass("is-invalid")
						$("#prenoms").parent().find(".error").text(data.prenoms)
					} else {
						$("#prenoms").removeClass("is-invalid").addClass("is-valid")
						$("#prenoms").parent().find(".error").text("")
					}

					if (data.email != null) {
						$("#email").addClass("is-invalid")
						$("#email").parent().find(".error").text(data.email)
					} else {
						$("#email").removeClass("is-invalid").addClass("is-valid")
						$("#email").parent().find(".error").text("")
					}

					if (data.telephone != null) {
						$("#telephone").addClass("is-invalid")
						$("#telephone").parent().find(".error").text(data.telephone)
					} else {
						$("#telephone").removeClass("is-invalid").addClass("is-valid")
						$("#telephone").parent().find(".error").text("")
					}
					
					if (data.fonction != null) {
						$("#fonction").addClass("is-invalid")
						$("#fonction").parent().find(".error").text(data.fonction)
					} else {
						$("#fonction").removeClass("is-invalid").addClass("is-valid")
						$("#fonction").parent().find(".error").text("")
					}

					if (data.msg != null) {
						createErrorNotif(data.msg, 3, "top-right")
					}
				}

			}, "json")


		})


	})
	
	/*Ajout un participant existant*/
	$(".btn-add-exist-participant").on("click", function() {
		const formationId = $(this).val();

		$("#btn-confirm-add-exist-participant").on("click", function() {

			$("#btn-confirm-add-exist-participant").attr("disabled", "disabled")
			gifLoader("#btn-confirm-add-exist-participant")
			$(".erreur").text("");
			
			var data = new Object();
			data.formationId = formationId;
			data.userId = $("#user-infos").val();
			
			$.post("/validation/entreprise/add-exist-participant-validation.php",data,function(data){
				
				if (data.type == "success") {

					location.reload();

				} else if(data.type == "session"){
					
					$(location).attr("href","/compte/connexion")	
					
				}else {

					$("#btn-confirm-add-exist-participant").removeAttr("disabled");
					
					if (data.msg != null) {
						createErrorNotif(data.msg, 3, "top-right")
					}
					
				}
				
			},"json")
			
		})
			
	})
	
	/** 
		Supprimer une inscription d'entreprise
		*/
		$(".btn-modal-confirm-entreprise-delete-formation-inscription").click(function() {
			var id = $(this).val();
			var formationTitre = $(".formation-title").text();
			var name = $(this).parent().parent().find(".row-name").text();
			var email = $(this).parent().parent().find(".row-email").text();
			var fonction = $(this).parent().parent().find(".row-fonction").text();
			
			//vider la partie affichant le message de suppression
			$(".modal-info").html("");
			
			$(".modal-info").html("Voulez-vous supprimer l'achat de la formation suivante : <br /><br />"
			+"Titre: <b> "+formationTitre+"</b><br />"
			+"Beneficiaire: <b>"+name+" - "+email+"</b><br />"
			+"Fonction: <b>"+fonction+"</b><br />"
			+"<p style='color:red;font-size: 14px;margin-top: 20px;'>Cette action est irreversible.</p>");
				
				$("#btn-confirm-entreprise-delete-formation-inscription").click(function() {
				
				$("#btn-confirm-entreprise-delete-formation-inscription").attr("disabled", "disabled");
				gifLoader("#btn-confirm-entreprise-delete-formation-inscription");

				$.post("/validation/entreprise/delete-entreprise-formation-inscription-validation.php", { id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/compte/connexion")
							
					}else {
						
						$("#btn-confirm-entreprise-delete-formation-inscription").removeAttr("disabled");
						createErrorNotif(data.msg,3,"top-right");
						setTimeout(function(){$("#btn-close-desinscrire-modal").trigger("click"); }, 3000);
					}
				}, "json")

			})
				
		});
		

})