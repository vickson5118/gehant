$(document).ready(function(){
	
	/**
	*Creer un administrateur
	 */
	//validation JS
	inputValidation("#staticBackdropCreateAdmin #nom","nom",2,30,true);
	inputValidation("#staticBackdropCreateAdmin #prenoms","prenoms",2,150,true);
	emailInputValidation("#staticBackdropCreateAdmin #email");
	
	$("#staticBackdropCreateAdmin #create-admin").click(function(){
		
		$("#staticBackdropCreateAdmin #create-admin").attr("disabled","disabled")
		gifLoader("#staticBackdropCreateAdmin #create-admin");
		$(".error").text("");
		
		var data = new Object();
		data.nom = $("#staticBackdropCreateAdmin #nom").val();
		data.prenoms = $("#staticBackdropCreateAdmin #prenoms").val();
		data.email = $("#staticBackdropCreateAdmin #email").val();
		
		$.post("/validation/w1-admin/users/create-admin-validation.php",data,function(data){
			if(data.type == "success"){
				
				location.reload();
				
			}else if(data.type == "session"){
				$(location).attr("href","/w1-admin")
			}else{
				
				$("#staticBackdropCreateAdmin #create-admin").removeAttr("disabled")
				
				if(data.nom != null){
						$("#staticBackdropCreateAdmin #nom").addClass("is-invalid")
						$("#staticBackdropCreateAdmin #nom").parent().find(".error").text(data.nom)
					}else{
						$("#staticBackdropCreateAdmin #nom").removeClass("is-invalid").addClass("is-valid")
						$("#staticBackdropCreateAdmin #nom").parent().find(".error").text("")	
					}
					
					if(data.prenoms != null){
						$("#staticBackdropCreateAdmin #prenoms").addClass("is-invalid")
						$("#staticBackdropCreateAdmin #prenoms").parent().find(".error").text(data.prenoms)
					}else{
						$("#staticBackdropCreateAdmin #prenoms").removeClass("is-invalid").addClass("is-valid")
						$("#staticBackdropCreateAdmin #prenoms").parent().find(".error").text("")	
					}
					
					if(data.email != null){
						$("#staticBackdropCreateAdmin #email").addClass("is-invalid")
						$("#staticBackdropCreateAdmin #email").parent().find(".error").text(data.email)
					}else{
						$("#staticBackdropCreateAdmin #email").removeClass("is-invalid").addClass("is-valid")
						$("#staticBackdropCreateAdmin #email").parent().find(".error").text("")	
					}
					
					if(data.emailSend != null){
						createErrorNotif(data.emailSend,3,"top-right")
					}
			}
			
		},"json")
		
	})
	
	//Bloquer l'user
	inputValidation("#motif-blocage","motif",10,250,true);
	$(".btn-bloquer-user").click(function(){
		var id = $(this).val();
		var name = $(this).parent().parent().find(".row-name").text();
		var email = $(this).parent().parent().find(".row-email").text();
		
		$("#bloquer-info").html("Le blocage de l'utilisateur suivant: <br /><br /> Nom & prenoms: <b>"+name+"</b> <br />"
			+"Email: <b>"+email+"</b><br /><br /> empêchera celui-ci de se connecter à nouveau.")
	
		$("#btn-confirm-bloquer-user").click(function(){
			
			$("#btn-confirm-bloquer-user").attr("disabled","disabled")
			gifLoader("#btn-confirm-bloquer-user")
			
			var motif = $("#motif-blocage").val();
			
			$.post("/validation/w1-admin/users/blocage-user-validation.php",{id,motif},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-confirm-bloquer-user").removeAttr("disabled")
					
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
	
	//Débloquer l'admin
	$(".btn-debloquer-user").click(function(){
		var id = $(this).val();
		var name = $(this).parent().parent().find(".row-name").text();
		var email = $(this).parent().parent().find(".row-email").text();
		
		$("#debloquer-info").html("Le déblocage de l'utilisateur suivant: <br /><br /> Nom & prenoms: <b>"+name+"</b> <br />"
			+"Email: <b>"+email+"</b><br /><br /> permettra à celui-ci de se connecter à nouveau.")
		
		$("#btn-confirm-debloquer-user").click(function(){
			
			$("#btn-confirm-debloquer-user").attr("disabled","disabled")
			gifLoader("#btn-confirm-debloquer-user")
			
			$.post("/validation/w1-admin/users/deblocage-user-validation.php",{id},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					
					$(location).attr("href","/w1-admin")
					
				}else{
					$("#btn-confirm-debloquer-user").removeAttr("disabled")
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
					
				}
			},"json")
			
		})
	})
	
	//Definir comme admin
	$(".btn-definir-admin").click(function(){
		var id = $(this).val();
		var name = $(this).parent().parent().find(".row-name").text();
		var email = $(this).parent().parent().find(".row-email").text();
		
		$("#modal-info").html("Voulez-vous vraiment définir l'utilisateur: <br /><br /> Nom & prenoms: <b>"+name+"</b> <br />"
			+"Email: <b>"+email+"</b><br /><br /> comme nouveau administrateur.")
		
		$("#btn-confirm-definir-admin").click(function(){
			
			$("#btn-confirm-definir-admin").attr("disabled","disabled")
			gifLoader("#btn-confirm-definir-admin")
			
			$.post("/validation/w1-admin/users/definir-admin-validation.php",{id},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-confirm-definir-admin").removeAttr("disabled")
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	//Definir un admin comme user
	$(".btn-annuler-admin").click(function(){
		var id = $(this).val();
		var name = $(this).parent().parent().find(".row-name").text();
		var email = $(this).parent().parent().find(".row-email").text();
		
		$("#modal-info").html("Voulez-vous vraiment annuler le compte administrateur de l'utilisateur: <br /><br /> Nom & prenoms: <b>"
		+name+"</b> <br />"+"Email: <b>"+email+"</b><br />.")
		
		$("#btn-confirm-annuler-admin").click(function(){
			
			$("#btn-confirm-annuler-admin").attr("disabled","disabled")
			gifLoader("#btn-annuler-definir-admin")
			
			$.post("/validation/w1-admin/users/annuler-admin-validation.php",{id},function(data){
				if(data.type == "success"){
					location.reload();				
				}else if(data.type == "session"){
					$(location).attr("href","/w1-admin")
				}else{
					$("#btn-annuler-definir-admin").removeAttr("disabled")
					
					if(data.id != null){
						createErrorNotif(data.id,3,"top-right")
					}
				}
			},"json")
			
		})
	})
	
	
})