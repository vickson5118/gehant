$(document).ready(function(){
	
	/**
	Creer un nouveau secteur d'activité'
	 */
	inputValidation("#nom-secteur","secteur d'activité",3,30,true)
	$("#btn-confirm-create-secteur").on("click",function(){
			gifLoader("#btn-confirm-create-secteur");
			$("#btn-confirm-create-secteur").attr("disabled","disabled")
			$(".error").text();
			
			$.post("/validation/w1-admin/holding/add-secteur-validation.php",{nom: $("#nom-secteur").val()}, function(data){
				
				if(data.type == "success"){
					location.reload();
				}else{
					
					$("#btn-confirm-create-secteur").removeAttr("disabled")
					
					if(data.secteur != null){
						$("#nom-secteur").addClass("is-invalid")
						$("#nom-secteur").parent().find(".error").text(data.secteur)
					}else{
						$("#nom-secteur").removeClass("is-invalid").addClass("is-valid")
						$("#nom-secteur").parent().find(".error").text("")
					}
				}
				
			},"json")
	
	})


	inputValidation("#nom-objectif","objectif",10,50,true)
	$("#btn-confirm-create-objectif").on("click",function(){
			gifLoader("#btn-confirm-create-objectif");
			$("#btn-confirm-create-objectif").attr("disabled","disabled")
			$(".error").text();
			
			$.post("/validation/w1-admin/holding/add-objectif-validation.php",{nom: $("#nom-objectif").val()}, function(data){
				
				if(data.type == "success"){
					location.reload();
				}else{
					
					$("#btn-confirm-create-objectif").removeAttr("disabled")
					
					if(data.objectif != null){
						$("#nom-objectif").addClass("is-invalid")
						$("#nom-objectif").parent().find(".error").text(data.objectif)
					}else{
						$("#nom-objectif").removeClass("is-invalid").addClass("is-valid")
						$("#nom-objectif").parent().find(".error").text("")
					}
				}
				
			},"json")
	
	})	
	
	
	inputValidation("#secteur","secteur d'activité",3,30,true)
	$(".btn-edit-secteur").click(function(){
		var id = $(this).val();
		var nom = $(this).parent().parent().find(".row-name").text();
		
		$("#secteur").val(nom);
		
		$("#btn-confirm-edit-secteur").on("click",function(){
			gifLoader("#btn-confirm-edit-secteur");
			$("#btn-confirm-edit-secteur").attr("disabled","disabled")
			$(".error").text();
			
			$.post("/validation/w1-admin/holding/edit-secteur-validation.php",{id,nom: $("#secteur").val()}, function(data){
				
				if(data.type == "success"){
					location.reload();
				}else{
					
					$("#btn-confirm-edit-secteur").removeAttr("disabled")
					
					if(data.secteur != null){
						$("#secteur").addClass("is-invalid")
						$("#secteur").parent().find(".error").text(data.secteur)
					}else{
						$("#secteur").removeClass("is-invalid").addClass("is-valid")
						$("#secteur").parent().find(".error").text("")
					}
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
					
				}
				
			},"json")
			
		})
	
	})
	
	inputValidation("#objectif","objectif",10,50,true)
	$(".btn-edit-objectif").click(function(){
		var id = $(this).val();
		var nom = $(this).parent().parent().find(".row-name").text();
		
		$("#objectif").val(nom);
		
		$("#btn-confirm-edit-objectif").on("click",function(){
			gifLoader("#btn-confirm-edit-objectif");
			$("#btn-confirm-edit-objectif").attr("disabled","disabled")
			$(".error").text();
			
			$.post("/validation/w1-admin/holding/edit-objectif-validation.php",{id,nom: $("#objectif").val()}, function(data){
				
				if(data.type == "success"){
					location.reload();
				}else{
					
					$("#btn-confirm-edit-objectif").removeAttr("disabled")
					
					if(data.objectif != null){
						$("#objectif").addClass("is-invalid")
						$("#objectif").parent().find(".error").text(data.objectif)
					}else{
						$("#objectif").removeClass("is-invalid").addClass("is-valid")
						$("#objectif").parent().find(".error").text("")
					}
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
					
				}
				
			},"json")
			
		})
	
	})
	
	
})