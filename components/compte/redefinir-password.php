<?php 

namespace components\compte;

session_start (); 


if (($_SESSION ["utilisateur"]) != null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
    exit ();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mot de passe oublié ? Redéfinir le mot de passe - Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/compte/redefinir-password.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid">
		
			<div class="container">
			
				<div class="row">
					<div class="col-md-6 forget-container">
						<h1>Vous avez oublié votre mot de passe ?<br />
						Pas de panique, redéfinissez-le et profitez à nouveau de notre contenu.</h1>
						
						<p>Assurez vous de rentrer une adresse E-mail valide, car vous recevrez la démarche à suivre à cette adresse</p>
					
						<div class="form-floating mb-3 inscription-info">
							<input type="email" class="form-control" id="reset-pass-email" placeholder="Adresse email" name="reset-pass-email">
							<label for="reset-pass-email">Adresse E-mail</label>
							<div class="error"></div>
						</div>
						<button id="btn-reset-pass-valide" class="mb-4" type="button">Réinitialiser le mot de passe</button>
					</div>
					<div class="col-md-6 img-forget-password-container"><i class="bi bi-unlock-fill"></i></div>
				</div>
				
			</div>
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
		<script src="/inc/js/compte/redefinir-password.js" type="text/javascript"></script>
	</body>
	
</html>