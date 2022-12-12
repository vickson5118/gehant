$(document).ready(function() {
	
	$("#debut").datepicker({
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
		yearRange:"c:c+10",
		changeMonth:true,
		changeYear:true
	});
	
	$("#fin").datepicker({
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
		yearRange:"c:c+10",
		changeMonth:true,
		changeYear:true
	});


	let poster = $("#img-container img").attr("src");

	//choisir de l'image d'illustration
	$("#formationIllustration").change(function() {

		var reader = new FileReader();

		reader.addEventListener("load", function() {
			poster = this.result
			$("#img-container img").attr("src", this.result)
			$("#video-container video").attr("poster", poster)
		})

		//showAndHideProgressBar(reader, "formationIllustration", "image")

		reader.readAsDataURL(this.files[0]);
		/*;
		$(".illustration-directives").append("<buttion>")
		$(".illustration-directives button").text("Changer d'image")*/

	});
	
	inputValidation("#titre","titre de la formation",10,40,true);
	inputValidation("#but","but de la formation",5,80,true);
	inputValidation("#lieu","lieu de la formation",2,21,true);
	inputValidation("#description","description de la formation",100,500,true);
	prixValidation("#prix");
	$("#basics-form").submit(function(event) {
		event.preventDefault();
		
		$("#btn-valide-presentation-info").attr("disabled","disabled")
		gifLoader("#btn-valide-presentation-info")

		$.ajax({
			url: "/validation/w1-admin/formation/add-basics-validation.php",
			method: "POST",
			data: new FormData(this),
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(data) {
				if (data.type == "success") {
					
					location.reload();
					
				}else if(data.type == "session"){
				
					$(location).attr("href","/w1-admin")
						
				} else if(data.location != null){
					
					$(location).attr("href",data.location);
					
				}else {
					
					$("#btn-valide-presentation-info").removeAttr("disabled")
					
					if(data.titre != null){
						$("#titre").addClass("is-invalid")
						$("#titre").parent().find(".error").text(data.titre)
					}else{
						$("#titre").removeClass("is-invalid").addClass("is-valid")
						$("#titre").parent().find(".error").text("")	
					}
					
					if(data.but != null){
						$("#but").addClass("is-invalid")
						$("#but").parent().find(".error").text(data.but)
					}else{
						$("#but").removeClass("is-invalid").addClass("is-valid")
						$("#but").parent().find(".error").text("")	
					}
					
					if(data.lieu != null){
						$("#lieu").addClass("is-invalid")
						$("#lieu").parent().find(".error").text(data.lieu)
					}else{
						$("#lieu").removeClass("is-invalid").addClass("is-valid")
						$("#lieu").parent().find(".error").text("")	
					}
					
					if(data.debut != null){
						$("#debut").addClass("is-invalid")
						$("#debut").parent().find(".error").text(data.debut)
					}else{
						$("#debut").removeClass("is-invalid").addClass("is-valid")
						$("#debut").parent().find(".error").text("")	
					}
					
					if(data.fin != null){
						$("#fin").addClass("is-invalid")
						$("#fin").parent().find(".error").text(data.fin)
					}else{
						$("#fin").removeClass("is-invalid").addClass("is-valid")
						$("#fin").parent().find(".error").text("")	
					}
					
					if(data.domaine != null){
						$("#domaine").addClass("is-invalid")
						$("#domaine").parent().find(".error").text(data.domaine)
					}else{
						$("#domaine").removeClass("is-invalid").addClass("is-valid")
						$("#domaine").parent().find(".error").text("")	
					}
					
					if(data.heures != null){
						$("#heures").addClass("is-invalid")
						$("#heures").parent().find(".error").text(data.heures)
					}else{
						$("#heures").removeClass("is-invalid").addClass("is-valid")
						$("#heures").parent().find(".error").text("")	
					}
					
					if(data.prix != null){
						$("#prix").addClass("is-invalid")
						$("#prix").parent().find(".error").text(data.prix)
					}else{
						$("#prix").removeClass("is-invalid").addClass("is-valid")
						$("#prix").parent().find(".error").text("")	
					}
					
					if(data.description != null){
						$("#description").addClass("is-invalid")
						$("#description").parent().find(".error").text(data.description)
					}else{
						$("#description").removeClass("is-invalid").addClass("is-valid")
						$("#description").parent().find(".error").text("")	
					}
					
					if(data.formationIllustration != null){
						createErrorNotif(data.formationIllustration,3,"top-right")
					}
					
					if(data.videoformationIllustration != null){
						createErrorNotif(data.videoformationIllustration,3,"top-right")
					}
					
				}
			}

		});
	})

})