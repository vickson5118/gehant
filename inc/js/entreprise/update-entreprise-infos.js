$(document).ready(function(){
	
	inputValidation("#entreprise","nom de l'entreprise",2,30,false);
	$("#btn-update-entreprise-infos").on("click",function(){
		
		$("#btn-update-entreprise-infos").attr("disabled","disabled")
		gifLoader("#btn-update-entreprise-infos")
		$(".error").text("")
		
		var data = new Object();
		data.id = $(this).val();
		data.entreprise = $("#entreprise").val();
		data.nbEmployes = $("#nb-employes").val();
		data.objectif = $("#objectif").val();
		data.secteur = $("#secteur").val();
		
		$.post("/validation/entreprise/update-entreprise-infos-validation.php",data,function(data){
			
			if(data.type == "success"){
				
				location.reload();
				
			}else if(data.type == "session"){
					
				$(location).attr("href","/compte/connexion")	
				
			}else{
				
				$("#btn-update-entreprise-infos").removeAttr("disabled");
				
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
				
				if (data.msg != null) {
					createErrorNotif(data.msg, 3, "top-right")
				}
				
			}
			
			
		},"json")
		
		
	})
	
})