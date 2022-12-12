$(document).ready(function(){
	
	
	let contentLength = $("#cibles-form input").length

	$("#add-cibles-input").click(function(event) {
		event.preventDefault();
		contentLength++
		$("#cibles-form").append("<input type=\"text\" id=\"cibles-" + contentLength + "\" class=\"form-control\" placeholder=\"Exemple : Developpeur Web\">")
	})

	let content = "";
	$("#cibles-save").click(function() {
		
		//mettre le contenu des prerequis au format de liste
		for (let i = 1; i <= contentLength; i++) {
			if ($("#cibles-" + i).val() !== "") {
				content += $("#cibles-" + i).val() + ";"
			}

		}

		gifLoader("#cibles-save")
		$("#cibles-save").attr("disabled","disabled")
		
		$.post("/validation/w1-admin/formation/update-formation-info-validation.php",{cibles: content}, function(data) {
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