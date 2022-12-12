$(document).ready(function(){
	
		//Envoyer un mail à l'admin
	inputValidation("#objet","objet",15,150,true);
	inputValidation("#message","message",20,1500,true);
	$(".btn-send-mail").click(function(){
		
		var email = $(this).parent().parent().find(".row-email").text();
		
		$("#btn-confirm-send-mail").click(function(){
			
			gifLoader("#btn-confirm-send-mail");
			$("#btn-confirm-send-mail").attr("disabled","disabled");
			$(".error").text("");
			
			var data = new Object();
			data.objet = $("#objet").val();
			data.message = $("#message").val();
			data.email = email;
			
			$.post("/validation/w1-admin/other/send-mail-validation.php",data,function(data){
				if(data.type == "success"){
					
					location.reload();
					
				}else if(data.type == "session"){
					
					$(location).attr("href","/w1-admin")
					
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
	
	
})