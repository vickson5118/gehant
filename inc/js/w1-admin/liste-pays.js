$(document).ready(function(){
	
	/**
	Ajouter un pays */
	inputValidation("#nom","nom",3,30,true)
	$("#btn-confirm-add-pays").on("click",function(){
		
		$("#btn-confirm-add-pays").attr("disabled","disabled")
		gifLoader("#btn-confirm-add-pays")
		
		
		$.post("/validation/w1-admin/other/add-pays-validation.php",{nom: $("#nom").val()},function(data){
			if(data.type == "success"){
				location.reload();
				
			}else if(data.type == "session"){
				
				$(location).attr("href","/w1-admin")
					
			}else{
				
				$("#btn-confirm-add-pays").removeAttr("disabled")
				
				if(data.nom != null){
					$("#nom").addClass("is-invalid")
					$("#nom").parent().find(".error").text(data.nom)
				}else{
					$("#nom").removeClass("is-invalid").addClass("is-valid")
					$("#nom").parent().find(".error").text("")
				}
			}
		},"json")
	})
	/**
	Editer un pays */
	inputValidation("#edit-nom","nom",3,30,true)
	$(".btn-edit-pays").on("click",function(){
		
		const paysId = $(this).val();
		
		$("#edit-nom").val($(this).parent().parent().find(".nom").text())
		
		$("#btn-confirm-edit-pays").on("click",function(){
			
			$("#btn-confirm-edit-pays").attr("disabled","disabled")
			gifLoader("#btn-confirm-edit-pays")
			
			$.post("/validation/w1-admin/other/edit-pays-validation.php",{id : paysId, nom:$("#edit-nom").val()},function(data){
			if(data.type == "success"){
				location.reload();
			}else if(data.type == "session"){
				
				$(location).attr("href","/w1-admin")
					
			}else{
				
				$("#btn-confirm-edit-pays").removeAttr("disabled");
				
				if(data.nom != null){
					$("#edit-nom").addClass("is-invalid")
					$("#edit-nom").parent().find(".error").text(data.nom)
				}else{
					$("#edit-nom").removeClass("is-invalid").addClass("is-valid")
					$("#edit-nom").parent().find(".error").text("")
				}
				
				if(data.id != null){
					createErrorNotif(data.id,3,"top-right")
				}
				
			}
		},"json")
		})
	})
	
	/**
	Supprimer un pays */
	$(".btn-delete-pays").on("click",function(){
		
		const paysId = $(this).val();
		const pays_nom = $(this).parent().parent().find(".row-pays").text().trim();
			
			//vider la partie affichant le message de suppression
			$("#modal-texte").html("");
			
			$("#modal-texte").html("<h5 style='font-size: 17px'>Voulez-vous vraiment supprimer le pays <b>"
				+ pays_nom + " </b>?</h5><p style='color:red;font-size: 15px'>Cette action est irreversible.</p>");
		
		$("#btn-confirm-delete-pays").on("click",function(){
			
			$("#btn-confirm-delete-pays").attr("disabled","disabled")
			gifLoader("#btn-confirm-delete-pays")
			
			$.post("/validation/w1-admin/other/delete-pays-validation.php",{id : paysId},function(data){
			if(data.type == "success"){
				location.reload();
			}else if(data.type == "session"){
				
				$(location).attr("href","/w1-admin")
					
			}else{
				
				$("#btn-confirm-delete-pays").removeAttr("disabled");
				
				if(data.id != null){
					createErrorNotif(data.id,3,"top-right")
				}
				
			}
		},"json")
		})
	})
	
})