$(document).ready(function(){
	
	$("#btn-reset-pass-valide").on("click", function(){
		
		gifLoader("#btn-reset-pass-valide");
		$("#btn-reset-pass-valide").attr("disabled","disabled");
		
		
		$.post("/validation/compte/redefinir-password-validation.php",{email: $("#reset-pass-email").val()},function(data){
			
			if(data.type == "success"){
				
				createSuccessNotif("Un mail vous a été envoyé. Consultez-le pour obténir votre nouveau mot de passe.",3,"top-right")
				setTimeout(function(){$(location).attr("href","/compte/connexion"); }, 3000);
				
			}else if (data.type == "session") {
				$(location).attr("href", "/compte/connexion")
			}else{
				createErrorNotif(data.msg, 3, "top-right")
				setTimeout(function(){location.reload(); }, 3000);
			}
			
		},"json")
		
	})
	
	
	
})