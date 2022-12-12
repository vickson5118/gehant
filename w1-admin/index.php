<?php

session_start();

if (($_SESSION ["utilisateur"]) != null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin/dashboard" );
    exit ();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Administration | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/w1-admin/index.css" type="text/css" />
	</head>
	
	<body>
	
		<div class="container-fluid" id="connexion-container">
		
			<div class="container">
				
				<div class="connexion-box">
				
				<a href="/"><img src="/inc/images/logo.png" alt="Logo Gehant" /></a>
					
					<p>Connexion</p>
					
					<p class="connexion-error"></p>
					
					<form>
						
						<div class="form-input-container">
    						<label for="email">Adresse E-mail</label>
    						<input type="email" name="email" id="email" required="required" />
    					</div>
    					
    					<div class="form-input-container">
    						<label for="password">Mot de passe</label>
    						<input type="password" name="password" id="password" required="required" />
    					</div>
    					
    					<button type="button" id="btn-admin-connexion">Connexion</button>
						
					</form>
					
					
				</div>
				
			</div>
			
		</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/index.js" type="text/javascript"></script>
	</body>
	
</html>