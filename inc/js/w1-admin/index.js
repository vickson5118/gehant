$(document).ready(function(){
	
	
	$("#btn-admin-connexion").on("click",function(){
		
		$("#btn-admin-connexion").attr("disabled","disabled")
		gifLoader("#btn-admin-connexion");
		$(".connexion-error").text("");
		
		var data = new Object();
		data.email = $("#email").val();
		data.password = $("#password").val();
		
		$.post("/validation/w1-admin/other/connexion-validation.php",data,function(data){
			
			if(data.type == "success"){
				
				$(location).attr("href","/w1-admin/dashboard");
				
			}else{
				
					$("#btn-admin-connexion").removeAttr("disabled")
				
					$(".connexion-error").text(data.error);
				
				}
			
		},"json")
		
	})
	
})