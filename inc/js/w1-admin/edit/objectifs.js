$(document).ready(function() {


	let contentLength = $("#objectifs-form input").length

	$("#add-objectifs-input").click(function(event) {
		event.preventDefault();
		contentLength++
		$("#objectifs-form").append("<input type=\"text\" id=\"objectifs-" + contentLength + "\" class=\"form-control\" placeholder=\"Exemple : Reussir à créer une application mobile multiplateforme\">")
	})

	let content = "";
	$("#objectifs-save").click(function() {
		
		//mettre le contenu des prerequis au format de liste
		for (let i = 1; i <= contentLength; i++) {
			if ($("#objectifs-" + i).val() !== "") {
				content += $("#objectifs-" + i).val() + ";"
			}

		}
		
		gifLoader("#objectifs-save")
		$("#objectifs-save").attr("disabled","disabled")
		
		$.post("/validation/w1-admin/formation/update-formation-info-validation.php",{objectifs: content }, function(data) {
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