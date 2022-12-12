$(document).ready(function(){
	
	$("#search-ins").keyup(function(){
		if($(this).val().length >= 5){
			const search = $(this).val();
			
			$.post("/validation/formation/search-formation-validation.php",{search},function(data){
				$(".search-banniere-container .result-container").html("");
				
					var html = "";
					data.forEach(function(formation){
						html += "<p><a href='/formations/"+formation.domaineUrl+"/"+formation.titreUrl+"'>"+formation.titre+"</a></p>"
					})
					
					$(".search-banniere-container .result-container").append(html);
				
				
			},"json")
		}else{
			$(".search-banniere-container .result-container").html("");
		}
		
	})
	
	
	
})