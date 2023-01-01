$(document).ready(function() {
	
	//click sur valider panier par une entreprise
	$("#valid-facture").on("click",function(){
		
		gifLoader("#valid-facture");
		$("#valid-facture").attr("disabled","disabled");
		
		$.post("/validation/entreprise/buy-panier-validation.php",function(data){
			
			if(data.type === "success"){
				
				createSuccessNotif("Vous avez été ajouté avec succès les participants. L'équipe Gehant vous contactera pour le régement de la facture et le début de la formation.",5,"top-right")
				setTimeout(function(){$(location).attr("href","/espace-client/entreprise"); }, 5000);
				
			}else if (data.type === "session") {
				$(location).attr("href", "/compte/connexion")
			}else{
				createErrorNotif(data.msg, 5, "top-right")
				setTimeout(function(){location.reload(); }, 5000);
			}
			
		},"json")
		
	})
	
	//click sur valider panier par un utilisateur
	$("#particulier-valid-facture").on("click",function(){
		
		gifLoader("#particulier-valid-facture");
		$("#particulier-valid-facture").attr("disabled","disabled");
		
		$.post("/validation/compte/buy-panier-particulier-validation.php",function(data){
			
			if(data.type === "success"){
				
				createSuccessNotif("Votre inscription a été validée avec succès. L'équipe Gehant vous contactera pour le régement de la facture disponible dans votre espace client et " +
					"le début de la formation.",5,"top-right")
				setTimeout(function(){$(location).attr("href","/espace-client"); }, 5000);
				
			}else if (data.type === "session") {
				$(location).attr("href", "/compte/connexion")
			}else{
				createErrorNotif(data.msg, 5, "top-right")
				setTimeout(function(){location.reload(); }, 5000);
			}
			
		},"json")
		
	})
		
		

	$(".btn-delete-formation-panier").click(function() {

		$.post("/validation/compte/delete-panier-validation.php", { panierId: $(this).val() }, function(data) {

			if (data.type === "success") {
				location.reload();
			} else if (data.type === "session") {
				$(location).attr("href", "/compte/connexion")
			} else {
				createErrorNotif(data.msg, 3, "top-right")
				setTimeout(function(){location.reload(); }, 3000);
			}

		}, "json")


	})


})