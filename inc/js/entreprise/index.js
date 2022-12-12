$(document).ready(function(){
	
	inputValidation("#objet","objet",15,150,true);
	inputValidation("#message","message",20,1500,true);
		
	$("#btn-confirm-send-mail").on("click",function(){
		
		gifLoader("#btn-confirm-send-mail");
		$("#btn-confirm-send-mail").attr("disabled","disabled")
		
		var data = new Object();
		data.objet = $("#objet").val();
		data.message = $("#message").val();
		
		$.post("/validation/entreprise/send-mail-entreprise-validation.php",data,function(data){
			if(data.type == "success"){
				location.reload()
			}else if(data.type == "session"){
				$(location).attr("href","/compte/connexion");
			}else{
				$("#btn-confirm-send-mail").removeAttr("disabled")
				
				if(data.objet != null){
					$("#objet").addClass("is-invalid")
					$("#objet").parent().find(".error").text(data.objet)
				}else{
					$("#objet").removeClass("is-invalid").addClass("is-valid")
					$("#objet").parent().find(".error").text("")	
				}
				
				if(data.message != null){
					$("#message").addClass("is-invalid")
					$("#message").parent().find(".error").text(data.message)
				}else{
					$("#message").removeClass("is-invalid").addClass("is-valid")
					$("#message").parent().find(".error").text("")	
				}
				
				if(data.email != null){
					createErrorNotif(data.email,3,"top-right")
				}
			}
		},"json")
		
	})
	
	
})