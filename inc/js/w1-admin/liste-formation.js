$(document).ready(function(){
	
	inputValidation("#titre","titre",15,40,true)
	$("#staticBackdropCreateFormation .creer-formation").click(function(){
		
		$("#staticBackdropCreateFormation .creer-formation").attr("disabled","disabled")
		gifLoader("#staticBackdropCreateFormation .creer-formation")
		
		var data = new Object();
		data.domaine = $("#staticBackdropCreateFormation #domaine").val();
		data.titre = $("#staticBackdropCreateFormation input[name=titre]").val();
		
		$.post("/validation/w1-admin/formation/create-formation-validation.php",data,function(data){
			 if(data.type == "session"){
				
					$(location).attr("href","/w1-admin")
					
			}else if(data.location != null){
				
				$(location).attr("href",data.location);
				
			}else{
				
				$("#staticBackdropCreateFormation #creer-formation").removeAttr("disabled")
				
				if(data.titre != null){
					$("#titre").addClass("is-invalid")
					$("#titre").parent().find(".error").text(data.titre)
				}else{
					$("#titre").removeClass("is-invalid").addClass("is-valid")
					$("#titre").parent().find(".error").text("")
				}
				
				if(data.domaine != null){
					$("#domaine").addClass("is-invalid")
					$("#domaine").parent().find(".error").text(data.domaine)
				}else{
					$("#domaine").removeClass("is-invalid").addClass("is-valid")
					$("#domaine").parent().find(".error").text("")
				}
				
				//createErrorNotif(data.msg,3,"top-right")
			}
		},"json");
		
	});
	
	//Bloquer la formation
	inputValidation("#motif-blocage","motif",10,250,true);
	$(".btn-bloquer-formation").click(function(){
		var id = $(this).val();
		var formationTitre = $(this).parent().parent().find(".formation-title").text();
		var email = $(this).parent().parent().find(".row-email").text();
		//var domaine = $(this).parent().parent().find(".row-domaine").text();
		var url = $(this).parent().parent().find(".row-domaine-url").text();
		
		$("#bloquer-info").html("Le blocage formation suivante la rendra inaccessible: <br /><br />"
			+"Titre: <b> "+formationTitre+"</b><br />"
			+"Auteur: <b>"+email+"</b>.")
		
		$(".btn-confirm-bloquer-formation").click(function(){
			
			$(".btn-confirm-bloquer-formation").attr("disabled","disabled")
			gifLoader(".btn-confirm-bloquer-formation")
			$(".error").text("")
			
			var motif = $("#motif-blocage").val();
			
			$.post("/validation/w1-admin/formation/blocage-formation-validation.php",{id,motif,url},function(data){
				if(data.type == "success"){
					
					location.reload();				
					
				}else if(data.type == "session"){
					
					$(location).attr("href","/w1-admin")
					
				}else{
					$(".btn-confirm-bloquer-formation").removeAttr("disabled")
					
					if(data.motif != null){
						$("#motif-blocage").addClass("is-invalid")
						$("#motif-blocage").parent().find(".error").text(data.motif)
					}else{
						$("#motif-blocage").removeClass("is-invalid").addClass("is-valid")
						$("#motif-blocage").parent().find(".error").text("")	
					}
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	
	//Débloquer la formation
	$(".btn-debloquer-formation").click(function(){
		var id = $(this).val();
		var formationTitre = $(this).parent().parent().find(".formation-title").text();
		var email = $(this).parent().parent().find(".row-email").text();
		//var domaine = $(this).parent().parent().find(".row-domaine").text();
		var url = $(this).parent().parent().find(".row-domaine-url").text();
		
		$("#debloquer-info").html("Le déblocage de la formation suivante la rendra de nouveau accessible: <br /><br />"
			+"Titre: <b> "+formationTitre+"</b><br />"
			+"Auteur: <b>"+email+"</b>.")
		
		$(".btn-confirm-debloquer-formation").click(function(){
			
			$(".btn-confirm-debloquer-formation").attr("disabled","disabled")
			gifLoader(".btn-confirm-debloquer-formation")
			$(".error").text("")
			
			$.post("/validation/w1-admin/formation/deblocage-formation-validation.php",{id,url},function(data){
				if(data.type == "success"){
					
					location.reload();				
				}else if(data.type == "session"){
					
					$(location).attr("href","/w1-admin")
					
				}else{
					$(".btn-confirm-debloquer-formation").removeAttr("disabled")
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
})