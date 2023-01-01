$(document).ready(function(){
	
	//Confimer le paiement de la formation - Particulier
	$(".btn-modal-confirm-particulier-paiement").click(function(){
		const id = $(this).val();
		const designation = $(this).parent().parent().find(".row-designation").text();
		const name = $(this).parent().parent().find(".row-name").text();
		const email = $(this).parent().parent().find(".row-email").text();
		const prix = $(this).parent().parent().find(".row-prix").text();

		
		$("#confirm-paiement-info").html("Voulez-vous confirmer l'achat de la facture suivante : <br /><br />"
			+"Référence: <b> "+designation+"</b><br />"
			+"Prix: <b>"+prix+"</b><br />"
			+"Souscripteur: <b>"+name+" - "+email+"</b><br />");
		
		$(".btn-confirm-particulier-paiement").click(function(){
			
			$(".btn-confirm-particulier-paiement").attr("disabled","disabled")
			gifLoader(".btn-confirm-particulier-paiement");
			
			const data = {};
			
			data.id = id;
			data.designation = designation;
			data.name = name;
			data.email = email;
			data.prix = prix;
			
			$.post("/validation/w1-admin/courses-buy/confirm-particulier-paiement-validation.php",data,function(data){
				if(data.type == "success"){
					
					location.reload();				
				}else if(data.type == "session"){
					
					$(location).attr("href","/w1-admin")
					
				}else{
					$(".btn-confirm-particulier-paiement").removeAttr("disabled")
					
					if(data.msg != null){
						createErrorNotif(data.msg,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	//Confimer le paiement de la formation - Entreprise
	$(".btn-modal-confirm-entreprise-paiement").click(function(){
		const id = $(this).val();
		const formationTitre = $(this).parent().parent().find(".formation-title").text();
		const name = $(this).parent().parent().find(".row-name").text();
		const email = $(this).parent().parent().find(".row-email").text();
		const entrepNom = $(this).parent().parent().find(".row-entrep-nom").text();
		const entrepMail = $(this).parent().parent().find(".row-entrep-mail").text();
		const prix = $(this).parent().parent().find(".row-prix").text();
		const date = $(this).parent().parent().find(".row-date").text();
		const lieu = $(this).parent().parent().find(".row-lieu").text();
		const domaineUrl = $(this).parent().parent().find(".row-domaine-url").text();
		const formationUrl = $(this).parent().parent().find(".row-formation-url").text();
		
		$("#confirm-paiement-info-entreprise").html("Voulez-vous confirmer l'achat de la formation suivante : <br /><br />"
			+"Titre: <b> "+formationTitre+"</b><br />"
			+"Beneficiaire: <b>"+name+" - "+email+"</b><br />"
			+"Souscripteur: <b>"+entrepNom+" - "+entrepMail+"</b><br />"
			+"Prix: <b>"+prix+"</b><br />"
			+"Date: <b>"+date+"</b>")
		
		$(".btn-confirm-entreprise-paiement").click(function(){
			
			$(".btn-confirm-entreprise-paiement").attr("disabled","disabled")
			gifLoader(".btn-confirm-entreprise-paiement");

			const data = {};

			data.id = id;
			data.name = name;
			data.titre = formationTitre;
			data.email = email;
			data.prix = prix;
			data.date = date;
			data.lieu = lieu;
			data.entreprise = entrepNom;
			data.domaineUrl = domaineUrl;
			data.formationUrl = formationUrl;
			
			$.post("/validation/w1-admin/courses-buy/confirm-entreprise-paiement-validation.php",data,function(data){
				if(data.type == "success"){
					
					location.reload();				
				}else if(data.type == "session"){
					
					$(location).attr("href","/w1-admin")
					
				}else{
					$(".btn-confirm-entreprise-paiement").removeAttr("disabled")
					
					if(data.msg != null){
						createErrorNotif(data.msg,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	
	/** 
		Supprimer un paiement-particulier
		*/
		$(".btn-modal-delete-confirm-particulier-paiement").click(function() {
			const id = $(this).val();
			const designation = $(this).parent().parent().find(".row-designation").text();
			const name = $(this).parent().parent().find(".row-name").text();
			const email = $(this).parent().parent().find(".row-email").text();
			const prix = $(this).parent().parent().find(".row-prix").text();
			
			//vider la partie affichant le message de suppression
			$("#modal-texte-delete").html("");
			
			$("#modal-texte-delete").html("Voulez-vous supprimer la facture suivante : <br /><br />"
				+"Référence: <b> "+designation+"</b><br />"
				+"Prix: <b> "+prix+"</b><br />"
				+"Beneficiaire: <b>"+name+" - "+email+"</b><br />"
			+"<p style='color:red;font-size: 14px;margin-top: 20px;'>Cette action est irreversible entrainera la suprpession des formations rattachées à cette facture.</p>");
				
				$(".btn-confirm-delete-confirm-paiement").click(function() {
				
				$(".btn-confirm-delete-confirm-paiement").attr("disabled", "disabled");
				gifLoader(".btn-confirm-delete-confirm-paiement");

				$.post("/validation/w1-admin/courses-buy/remove-paiement-validation.php", { id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/w1-admin")
							
					}else {
						
						$(".btn-confirm-delete-confirm-paiement").removeAttr("disabled");
						
						createErrorNotif(data.id,3,"top-right")
					}
				}, "json")

			})
				
		});
		
		/** 
		Supprimer un paiement entreprise
		*/
		$(".btn-modal-delete-confirm-entreprise-paiement").click(function() {
			const id = $(this).val();
			const formationTitre = $(this).parent().parent().find(".formation-title").text();
			const name = $(this).parent().parent().find(".row-name").text();
			const email = $(this).parent().parent().find(".row-email").text();
			const entrepNom = $(this).parent().parent().find(".row-entrep-nom").text();
			const entrepMail = $(this).parent().parent().find(".row-entrep-mail").text();
			const prix = $(this).parent().parent().find(".row-prix").text();
			const date = $(this).parent().parent().find(".row-date").text();
			
			//vider la partie affichant le message de suppression
			$("#modal-texte").html("");
			
			$("#modal-texte").html("Voulez-vous supprimer l'achat de la formation suivante : <br /><br />"
			+"Titre: <b> "+formationTitre+"</b><br />"
			+"Beneficiaire: <b>"+name+" - "+email+"</b><br />"
			+"Souscripteur: <b>"+entrepNom+" - "+entrepMail+"</b><br />"
			+"Prix: <b>"+prix+"</b><br />"
			+"Date: <b>"+date+"</b>"
			+"<p style='color:red;font-size: 14px;margin-top: 20px;'>Cette action est irreversible.</p>");
				
				$(".btn-confirm-delete-confirm-paiement").click(function() {
				
				$(".btn-confirm-delete-confirm-paiement").attr("disabled", "disabled");
				gifLoader(".btn-confirm-delete-confirm-paiement");

				$.post("/validation/w1-admin/courses-buy/remove-paiement-validation.php", { id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/w1-admin")
							
					}else {
						
						$(".btn-confirm-delete-confirm-paiement").removeAttr("disabled");
						
						createErrorNotif(data.id,3,"top-right")
					}
				}, "json")

			})
				
		});
		
		/** 
		Bloquer un paiement particulier
		*/
		$(".btn-modal-confirm-particulier-locked-paiement").click(function() {
			const id = $(this).val();
			const designation = $(this).parent().parent().find(".row-designation").text();
			const name = $(this).parent().parent().find(".row-name").text();
			const email = $(this).parent().parent().find(".row-email").text();
			const prix = $(this).parent().parent().find(".row-prix").text();
			
			//vider la partie affichant le message de suppression
			$("#modal-texte").html("");
			
			$("#modal-texte").html("Voulez-vous obliger le réglement de la facture : <br /><br />"
			+"Référence: <b> "+designation+"</b><br />"
			+"Prix: <b> "+prix+"</b><br />"
			+"Beneficiaire: <b>"+name+" - "+email+"</b><br />");
				
				$(".btn-confirm-particulier-locked-paiement").click(function() {
				
				$(".btn-confirm-particulier-locked-paiement").attr("disabled", "disabled");
				gifLoader(".btn-confirm-particulier-locked-paiement");

				$.post("/validation/w1-admin/courses-buy/locked-paiement-validation.php", { id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/w1-admin")
							
					}else {
						
						$(".btn-confirm-particulier-locked-paiement").removeAttr("disabled");
						createErrorNotif(data.msg,3,"top-right")
					}
				}, "json")

			})
				
		});
		
		/** 
		Debloquer un paiement particulier
		*/
		$(".btn-modal-confirm-particulier-unlocked-paiement").click(function() {
			const id = $(this).val();
			const designation = $(this).parent().parent().find(".row-designation").text();
			const name = $(this).parent().parent().find(".row-name").text();
			const email = $(this).parent().parent().find(".row-email").text();
			const prix = $(this).parent().parent().find(".row-prix").text();
			
			//vider la partie affichant le message de suppression
			$(".modal-texte").html("");
			
			$(".modal-texte").html("Voulez-vous annuler l'obligation de régler la facture suivante : <br /><br />"
				+"Référence: <b> "+designation+"</b><br />"
				+"Prix: <b> "+prix+"</b><br />"
				+"Beneficiaire: <b>"+name+" - "+email+"</b><br />"
			+"<p style='color:red;font-size: 14px;margin-top: 20px;'>Cette action donnera la possibilité à l'utilisateur de se désincrire à la formation.</p>");
				
				$(".btn-confirm-particulier-unlocked-paiement").click(function() {
				
				$(".btn-confirm-particulier-unlocked-paiement").attr("disabled", "disabled");
				gifLoader(".btn-confirm-particulier-unlocked-paiement");

				$.post("/validation/w1-admin/courses-buy/unlocked-paiement-validation.php", { id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/w1-admin")
							
					}else {
						
						$(".btn-confirm-particulier-unlocked-paiement").removeAttr("disabled");
						createErrorNotif(data.msg,3,"top-right")
					}
				}, "json")

			})
				
		});
	
	
	
})