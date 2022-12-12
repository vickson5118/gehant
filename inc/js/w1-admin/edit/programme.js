$(document).ready(function(){
	
	
	/**
	*Ajouter un nouveau module
	 */
	inputValidation("#titre","titre",10,100,true);
	$(".btn-create-module").on("click",function(){
		
		$(".btn-create-module").attr("disabled","disabled")
		gifLoader(".btn-create-module");
		$(".error").text("");
		
		
		$.post("/validation/w1-admin/formation/add-module-validation.php",{titre: $("#titre").val()},function(data){
			
			if(data.type == "success"){
				
				location.reload();
				
			}else if(data.type == "session"){
				
				$(location).attr("href","/w1-admin")
					
			}else{
				$(".btn-create-module").removeAttr("disabled")
				
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
	
	/** 
	Editer un module
	*/
	//Controle JS
	inputValidation("#edit-module-titre","titre",10,100,true);
	$(".btn-edit-module").click(function() {
		let module_id = $(this).val();
	
		//recuperer le titre
		const module_title = $(this).parent().parent().find(".module-title").text().trim();
		
		$("#edit-module-titre").val(module_title)
		
		$(".btn-confirm-edit-module").click(function() {
			
			$(".btn-confirm-edit-module").attr("disabled", "disabled");
			gifLoader(".btn-confirm-edit-module");

			$.post("/validation/w1-admin/formation/edit-module-validation.php", { id: module_id, titre: $("#edit-module-titre").val()}, function(data) {
				if (data.type == "success") {
					
					location.reload();
					
				}else if(data.type == "session"){
				
					$(location).attr("href","/w1-admin")
						
				}else {
					
					$(".btn-confirm-edit-module").removeAttr("disabled");
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
					
					if(data.titre != null){
					$("#edit-module-titre").addClass("is-invalid")
					$("#edit-module-titre").parent().find(".error").text(data.titre)
				}else{
					$("#edit-module-titre").removeClass("is-invalid").addClass("is-valid")
					$("#edit-module-titre").parent().find(".error").text("")
				}
				
					
				}
			}, "json")

		})

	});
	
	
	/** 
	Ajouter un point clé un module
	*/
	//Controle JS
	inputValidation("#point","point clé",10,50,true);
	$(".add-module-point-cle").click(function() {
		let module_id = $(this).val();
		
		$(".btn-create-module-point-cle").click(function() {
			
			$(".btn-create-module-point-cle").attr("disabled", "disabled");
			gifLoader(".btn-create-module-point-cle");

			$.post("/validation/w1-admin/formation/add-module-point-cle-validation.php", { id: module_id, point: $("#point").val()}, function(data) {
				if (data.type == "success") {
					
					location.reload();
					
				}else if(data.type == "session"){
				
					$(location).attr("href","/w1-admin")
						
				}else {
					
					$(".btn-create-module-point-cle").removeAttr("disabled");
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
					
					if(data.point != null){
						$("#point").addClass("is-invalid")
						$("#point").parent().find(".error").text(data.point)
					}else{
						$("#point").removeClass("is-invalid").addClass("is-valid")
						$("#point").parent().find(".error").text("")
					}
				
					
				}
			}, "json")

		})

	});
	
		/** 
		Supprimer un module
		*/
		$(".btn-delete-module").click(function() {
			let module_id = $(this).val();
			
			//vider la partie affichant le message de suppression
			$("#modal-body-delete-module").html("");
			
			//recuperer le nom du module courant
			const module_nom = $(this).parent().parent().find(".module-title").text().trim();
			$("#modal-body-delete-module").html("<h5 style='font-size: 17px'>Voulez-vous vraiment supprimer le module <b>"
				+ module_nom + "</b></h5><p style='color:red;font-size: 15px'>Cette action est irreversible et supprimera tous les points clés de ce module.</p>");
				
				$(".btn-confirm-delete-module").click(function() {
				
				$(".btn-confirm-delete-module").attr("disabled", "disabled");
				gifLoader(".btn-confirm-delete-module");

				$.post("/validation/w1-admin/formation/delete-module-validation.php", { id: module_id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/w1-admin")
							
					}else {
						
						$(".btn-confirm-delete-module").removeAttr("disabled");
						
						createErrorNotif(data.id,3,"top-right")
					}
				}, "json")

			})
				
		});
		
		/** 
	Editer un point clé un module
	*/
	//Controle JS
	inputValidation("#edit-point","point clé",10,50,true);
	$(".btn-edit-module-point-cle").click(function() {
		let point_id = $(this).val();
		
		//recuperer le titre
		const point_titre = $(this).parent().find(".point-title").text().trim();
		
		$("#edit-point").val(point_titre)
		
		$(".btn-confirm-edit-module-point-cle").click(function() {
			
			$(".btn-confirm-edit-module-point-cle").attr("disabled", "disabled");
			gifLoader(".btn-confirm-edit-module-point-cle");

			$.post("/validation/w1-admin/formation/edit-module-point-cle-validation.php", { id: point_id, point: $("#edit-point").val()}, function(data) {
				if (data.type == "success") {
					
					location.reload();
					
				}else if(data.type == "session"){
				
					$(location).attr("href","/w1-admin")
						
				}else {
					
					$(".btn-confirm-edit-module-point-cle").removeAttr("disabled");
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
					
					if(data.point != null){
						$("#edit-point").addClass("is-invalid")
						$("#edit-point").parent().find(".error").text(data.point)
					}else{
						$("#edit-point").removeClass("is-invalid").addClass("is-valid")
						$("#edit-point").parent().find(".error").text("")
					}
				
					
				}
			}, "json")

		})

	});
	
		/** 
		Supprimer un point clé
		*/
		$(".btn-delete-module-point-cle").click(function() {
			let point_id = $(this).val();
			
			const point_titre = $(this).parent().find(".point-title").text().trim();
			
			//vider la partie affichant le message de suppression
			$("#modal-texte").html("");
			
			$("#modal-texte").html("<h5 style='font-size: 17px'>Voulez-vous vraiment supprimer le point clé <b>"
				+ point_titre + " </b>?</h5><p style='color:red;font-size: 15px'>Cette action est irreversible.</p>");
				
				$(".btn-confirm-delete-module-point-cle").click(function() {
				
				$(".btn-confirm-delete-module-point-cle").attr("disabled", "disabled");
				gifLoader(".btn-confirm-delete-module-point-cle");

				$.post("/validation/w1-admin/formation/delete-module-point-cle-validation.php", { id: point_id }, function(data) {
					if (data.type == "success") {
						location.reload();
					} else if(data.type == "session"){
				
						$(location).attr("href","/w1-admin")
							
					}else {
						
						$(".btn-confirm-delete-module-point-cle").removeAttr("disabled");
						
						createErrorNotif(data.id,3,"top-right")
					}
				}, "json")

			})
				
		});
	
})