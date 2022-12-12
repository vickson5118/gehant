$(document).ready(function(){
	
	//Soumettre une formation avec redaction_finished_validated
	$("#btn-send-evaluate").click(function(){
		
		$("#btn-send-evaluate").attr("disabled","disabled")
		gifLoader("#btn-send-evaluate","Envoyer pour evaluation")
		
		$.post("/validation/w1-admin/formation/add-new-formation-validation.php",function(data){
			if(data.type == "success"){
				$(location).attr("href","/w1-admin/formations")
			}else if(data.type == "session"){
				
				$(location).attr("href","/w1-admin")
				
			}else{
				
				$("#btn-send-evaluate").removeAttr("disabled")
				
				createErrorNotif(data.msg,3,"top-right")
				
			}
			
		},"json")
	})	
})