$(document).ready(function(){
	
	//choisir de l'image d'illustration
	$("#domaineIllustration").change(function(){
		$("#domaineIllustration").parent().find(".error").text("");
		
		var reader = new FileReader();
		
		reader.addEventListener("load",function(){
			$(".domaineIllustrationLabel img").attr("src",this.result)
			$(".domaineIllustrationLabel img").css({"width": "300px","height":"auto"})
			$(".domaineIllustrationLabel").css({"border": "none"})
			$(".domaineIllustrationLabel i").css("display","none")
		})
		
		reader.readAsDataURL(this.files[0]);
		
	});
	
	
	/**
	Creation d'un domaine
	 */
	inputValidation("#titre","titre du domaine",5,30,true)
	inputValidation("#description","description",100,500,true)
	$("#add-domaine-form").submit(function(event){
		event.preventDefault();
		
		gifLoader("#btn-create-domaine");
		$("#btn-create-domaine").attr("disabled","disabled")
		$(".error").text("");

		$.ajax({
			url: "/validation/w1-admin/domaine/create-domaine-validation.php",
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
					$("#btn-create-domaine").removeAttr("disabled");
					
					if(data.titre != null){
						$("#titre").addClass("is-invalid")
						$("#titre").parent().find(".error").text(data.titre)
					}else{
						$("#titre").removeClass("is-invalid").addClass("is-valid")
						$("#titre").parent().find(".error").text("")
					}
					
					if(data.description != null){
						$("#description").addClass("is-invalid")
						$("#description").parent().find(".error").text(data.description)
					}else{
						$("#description").removeClass("is-invalid").addClass("is-valid")
						$("#description").parent().find(".error").text("")
					}
					
					if(data.domaineIllustration != null){
						$("#domaineIllustration").parent().find(".error").text(data.domaineIllustration)
					}else{
						$("#domaineIllustration").parent().find(".error").text("")
					}
				}
			}
			
		})
	});
	
	//Bloquer le domaine
	inputValidation("#motif-blocage","motif",10,250,true);
	$(".btn-bloquer-domaine").click(function(){
		var id = $(this).val();
		var domaineTitre = $(this).parent().parent().find(".domaine-title").text();
		
		$("#bloquer-info").html("Le blocage du domaine <b>"+domaineTitre+"</b> entrainera le blocage de toutes les formations de ce domaine")
		
		$("#btn-confirm-bloquer-domaine").click(function(){
			
			$("#btn-confirm-bloquer-domaine").attr("disabled","disabled")
			gifLoader("#btn-confirm-bloquer-domaine")
			$(".error").text("");
			
			var motif = $("#motif-blocage").val();
			
			$.post("/validation/w1-admin/domaine/blocage-domaine-validation.php",{id,motif},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-confirm-bloquer-domaine").removeAttr("disabled")
					
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
	
	//Débloquer le domaine
	$(".btn-debloquer-domaine").click(function(){
		var id = $(this).val();
		var domaineTitre = $(this).parent().parent().find(".domaine-title").text();
		
		$("#debloquer-info").html("Le déblocage du domaine <b>"+domaineTitre+"</b> entrainera le déblocage de toutes les formations de ce domaine")
		
		$("#btn-confirm-debloquer-domaine").click(function(){
			
			$("#btn-confirm-debloquer-domaine").attr("disabled","disabled");
			gifLoader("#btn-confirm-debloquer-domaine");
			$(".error").text("");
			
			$.post("/validation/w1-admin/domaine/deblocage-domaine-validation.php",{id},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-confirm-debloquer-domaine").removeAttr("disabled")
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	//choisir l'image d'illustration lors de l'edit
	$("#editDomaineIllustration").change(function(){
		
		var reader = new FileReader();
		
		reader.addEventListener("load",function(){
			$(".editDomaineIllustrationLabel img").attr("src",this.result)
			$(".editDomaineIllustrationLabel img").css({"width": "300px","height":"auto"})
			$(".editDomaineIllustrationLabel").css({"border": "none"})
			$(".editDomaineIllustrationLabel i").css("display","none")
		})
		
		reader.readAsDataURL(this.files[0]);
		
	});
	
	//Editer un domaine
	inputValidation("#edit-titre","titre du domaine",5,30,true)
	inputValidation("#edit-description","description",100,500,true)
	
	$(".btn-edit-domaine").click(function(){
		var id = $(this).val();
		var domaineTitre = $(this).parent().parent().parent().find(".domaine-title").text();
		var description = $(this).parent().parent().parent().find(".description").text();
		var illustration = $(this).parent().parent().parent().find(".illustration").text();
		
		$("#edit-titre").val(domaineTitre);
		$("#edit-description").val(description)
		$(".editDomaineIllustrationLabel img").attr("src",illustration)


		$("#edit-domaine-form").submit(function(event){
			event.preventDefault();
			
			gifLoader("#btn-confirm-edit-domaine");
			$("#btn-confirm-edit-domaine").attr("disabled","disabled")
			$(".error").text();
			
			var formData = new FormData(this);
			formData.append("id",id);
			formData.append("oldIllustrationPath",illustration);
	
			$.ajax({
				url: "/validation/w1-admin/domaine/edit-domaine-validation.php",
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
						
						$("#btn-confirm-edit-domaine").removeAttr("disabled");
						
						if(data.titre != null){
							$("#edit-titre").addClass("is-invalid")
							$("#edit-titre").parent().find(".error").text(data.titre)
						}else{
							$("#edit-titre").removeClass("is-invalid").addClass("is-valid")
							$("#edit-titre").parent().find(".error").text("")
						}
						
						if(data.description != null){
							$("#edit-description").addClass("is-invalid")
							$("#edit-description").parent().find(".error").text(data.description)
						}else{
							$("#edit-description").removeClass("is-invalid").addClass("is-valid")
							$("#edit-description").parent().find(".error").text("")
						}
						
						if(data.domaineIllustration != null){
							$("#editDomaineIllustration").parent().find(".error").text(data.domaineIllustration)
						}else{
							$("#editDomaineIllustration").parent().find(".error").text("")
						}
						
						if(data.msg != null){
							createErrorNotif(data.msg,3,"top-right")
						}
						
					}
				}
				
			})
		});
		
		
	})
	
})