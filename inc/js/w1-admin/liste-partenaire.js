$(document).ready(function(){
	
	//choisir de l'image d'illustration
	$("#partenaireIllustration").change(function(){
		$("#partenaireIllustration").parent().find(".error").text("");
		
		var reader = new FileReader();
		
		reader.addEventListener("load",function(){
			$(".partenaireIllustrationLabel img").attr("src",this.result)
			$(".partenaireIllustrationLabel img").css({"width":"auto","height":"150px"})
			$(".partenaireIllustrationLabel").css({"border": "none"})
			$(".partenaireIllustrationLabel i").css("display","none")
		})
		
		reader.readAsDataURL(this.files[0]);
		
	});
	
	//choisir l'image d'illustration lors de l'edit
	$("#editPartenaireIllustration").change(function(){
		
		var reader = new FileReader();
		
		reader.addEventListener("load",function(){
			$(".editPartenaireIllustrationLabel img").attr("src",this.result)
			$(".editPartenaireIllustrationLabel img").css({"width":"auto","height":"150px"})
			$(".editPartenaireIllustrationLabel").css({"border": "none"})
			$(".editPartenaireIllustrationLabel i").css("display","none")
		})
		
		reader.readAsDataURL(this.files[0]);
		
	});
	
	
	/**
	Ajouter un partenaire
	 */
	inputValidation("#nom","nom de l'entreprise'",2,30,true)
	$("#add-partenaire-form").submit(function(event){
		event.preventDefault();
		
		gifLoader("#btn-confirm-add-partenaire");
		$("#btn-confirm-add-partenaire").attr("disabled","disabled")
		$(".error").text("");

		$.ajax({
			url: "/validation/w1-admin/part/add-partenaire-validation.php",
			method: "POST",
			data: new FormData(this),
			contentType: false,
			processData: false,
			dataType: "json",
			success:function(data){
				if(data.type == "success"){
					location.reload();
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-confirm-add-partenaire").removeAttr("disabled");
					
					if(data.nom != null){
						$("#nom").addClass("is-invalid")
						$("#nom").parent().find(".error").text(data.nom)
					}else{
						$("#nom").removeClass("is-invalid").addClass("is-valid")
						$("#nom").parent().find(".error").text("")
					}
					
					if(data.partenaireIllustration != null){
						$("#partenaireIllustration").parent().find(".error").text(data.partenaireIllustration)
					}else{
						$("#partenaireIllustration").parent().find(".error").text("")
					}
				}
			}
			
		})
	});
	
	//Editer un partenaire
	inputValidation("#edit-nom","nom de l'entreprise'",2,30,true)
	$(".btn-modal-edit-partenaire").click(function(){
		var id = $(this).val();
		var nom = $(this).parent().parent().find(".row-nom").text();
		var illustration = $(this).parent().parent().find("img").attr("src");
		
		$("#editNom").val(nom);
		$(".editPartenaireIllustrationLabel img").attr("src",illustration)


		$("#edit-partenaire-form").submit(function(event){
			event.preventDefault();
			
			gifLoader("#btn-confirm-edit-partenaire");
			$("#btn-confirm-edit-partenaire").attr("disabled","disabled")
			$(".error").text();
			
			var formData = new FormData(this);
			formData.append("id",id);
			formData.append("oldIllustrationPath",illustration);
	
			$.ajax({
				url: "/validation/w1-admin/part/edit-partenaire-validation.php",
				method: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "json",
				success:function(data){
					if(data.type == "success"){
						
						location.reload();
						
					}else if(data.type == "session"){
						
						$(location).attr("href","/w1-admin")
						
					} else{
						
						$("#btn-confirm-edit-partenaire").removeAttr("disabled");
						
						if(data.nom != null){
							$("#editNom").addClass("is-invalid")
							$("#editNom").parent().find(".error").text(data.nom)
						}else{
							$("#editNom").removeClass("is-invalid").addClass("is-valid")
							$("#editNom").parent().find(".error").text("")
						}
						
						if(data.partenaireIllustration != null){
							$("#editPartenaireIllustration").parent().find(".error").text(data.partenaireIllustration)
						}else{
							$("#editPartenaireIllustration").parent().find(".error").text("")
						}
						
						if(data.msg != null){
							createErrorNotif(data.msg,3,"top-right")
						}
						
					}
				}
				
			})
		});
		
	})
	
	//Supprimer le partenaire
	$(".btn-modal-remove-partenaire").click(function(){
		var id = $(this).val();
		var nom = $(this).parent().parent().find(".row-nom").text();
		var illustration = $(this).parent().parent().find("img").attr("src");
		
		$("#debloquer-info").html("Voulez-vous vraiment supprimer le partenaire <b>"+nom+"</b>. <br /><br />Cette action est irréversible.")
		
		$("#btn-confirm-remove-partenaire").click(function(){
			
			$("#btn-confirm-remove-partenaire").attr("disabled","disabled");
			gifLoader("#btn-confirm-remove-partenaire");
			$(".error").text("");
			
			$.post("/validation/w1-admin/part/remove-partenaire-validation.php",{id,illustration},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-confirm-remove-partenaire").removeAttr("disabled")
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	
})