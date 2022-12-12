$(document).ready(function(){
	
	$("#btn-delete-formation-inscription").click(function(){
		const achatId = $(this).val();
		
		$("#btn-confirm-delete-formation-inscription").click(function(){
			
			$("#btn-confirm-delete-formation-inscription").attr("disabled","disabled")
			gifLoader("#btn-confirm-delete-formation-inscription")
			
			$.post("/validation/espace-client/confirm-delete-formation-inscription-validation.php",{id: achatId},function(data){
				
				if(data.type == "success"){
					location.reload();
				}else if(data.type == "session"){
				
					$(location).attr("href","/compte/connexion")
					
				}else{
					$("#btn-confirm-delete-formation-inscription").removeAttr("disabled");
					createErrorNotif(data.msg,3,"top-right");
					setTimeout(function(){$("#btn-close-desinscrire-modal").trigger("click"); }, 3000);
				}
				
				
			},"json")
			
		})
		
		
	})
	
	
})