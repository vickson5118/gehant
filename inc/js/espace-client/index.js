$(document).ready(function(){
	
	$(".espace-client-formation").css({color:"#f07b16",borderBottom:"3px solid #f07b16"})
	$(".espace-client-content").html("");
	$(".espace-client-content").load("/components/espace-client/formation.php");
	
	$(".espace-client-profil").on("click",function(event){
		event.preventDefault();
		
		$(".espace-client-link").css({color:"black",borderBottom:"none"})
		$(this).css({color:"#f07b16",borderBottom:"3px solid #f07b16"})
		$(".espace-client-content").html("");
		
		$(".espace-client-content").load("/components/espace-client/profil.php");
		
	})
	
	$(".espace-client-formation").on("click",function(event){
		event.preventDefault();
		
		$(".espace-client-link").css({color:"black",borderBottom:"none"})
		$(this).css({color:"#f07b16",borderBottom:"3px solid #f07b16"})
		$(".espace-client-content").html("");
		
		$(".espace-client-content").load("/components/espace-client/formation.php");
		
	})
	
	$(".espace-client-facture").on("click",function(event){
		event.preventDefault();
		
		$(".espace-client-link").css({color:"black",borderBottom:"none"})
		$(this).css({color:"#f07b16",borderBottom:"3px solid #f07b16"})
		$(".espace-client-content").html("");
		
		$(".espace-client-content").load("/components/espace-client/facture.php");
		
	})

	
	
	
})