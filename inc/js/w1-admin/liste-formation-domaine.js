$(document).ready(function(){
	
	inputValidation("#titre","titre",15,100,true)
	//Créer une formation
	$("#btn-confirm-create-formation-in-domaine").click(function(){
		
		gifLoader("#btn-confirm-create-formation-in-domaine");
		$("#btn-confirm-create-formation-in-domaine").attr("disabled","disabled")
		
		$.post("/validation/w1-admin/domaine/create-formation-in-domaine-validation.php",{titre: $("#titre").val()},function(data){
			if(data.type == "session"){
				
				$(location).attr("href","/w1-admin")
				
			}else if(data.location != null){
				
				$(location).attr("href",data.location);
				
			}else{
				
				$("#btn-confirm-create-formation-in-domaine").removeAttr("disabled")
				
				if(data.titre != null){
					$("#titre").addClass("is-invalid")
					$("#titre").parent().find(".error").text(data.titre)
				}else{
					$("#titre").removeClass("is-invalid").addClass("is-valid")
					$("#titre").parent().find(".error").text("")
				}
			}
		},"json")
	})
	
})