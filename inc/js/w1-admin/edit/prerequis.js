$(document).ready(function() {


	let contentLength = $("#prerequis-form input").length

	$("#add-prerequis-input").click(function(event) {
		event.preventDefault();
		contentLength++
		$("#prerequis-form").append("<input type=\"text\" id=\"prerequis-" + contentLength + "\" class=\"form-control\" placeholder=\"Exemple : Avoir des competences en management\">")
	})

	let content = "";
	$("#prerequis-save").click(function() {
		
		//mettre le contenu des prerequis au format de liste
		for (let i = 1; i <= contentLength; i++) {
			if ($("#prerequis-" + i).val() !== "") {
				content += $("#prerequis-" + i).val() + ";"
			}

		}

		
		gifLoader("#prerequis-save")
		$("#prerequis-save").attr("disabled","disabled")
		
		$.post("/validation/w1-admin/formation/update-formation-info-validation.php",{prerequis: content }, function(data) {
			if (data.type == "success") {
				location.reload();
			}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
			}else{
				location.reload();
			}
		}, "json")

	})


})