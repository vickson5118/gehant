<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/TypeCompte.php");

session_start ();

use utils\Functions;
use utils\Constants;

if (($_SESSION ["utilisateur"]) == null) {
    header("Location: http://".$_SERVER["SERVER_NAME"]."/compte/connexion");
    exit();
}

$utilisateur = $_SESSION["utilisateur"];
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Mon espace client | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/inc/css/espace-client/index.css" type="text/css" />
		<link rel="stylesheet" href="/inc/css/espace-client/profil.css" type="text/css" />
		<link rel="stylesheet" href="/inc/css/espace-client/formation.css" type="text/css" />
		<link rel="stylesheet" href="/inc/css/espace-client/facture.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid">
		
			<div class="espace-client-header container">
				<p>Espace client</p>
				
				<div class="espace-client-info-content">
					<div class="type-compte-badge">Compte <?= $utilisateur->getTypeCompte()->getNom() ?></div>
					<p class="welcome"><?php if($utilisateur->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){echo $utilisateur->getEntreprise()->getNom();}else{ echo $utilisateur->getPrenoms()." ".$utilisateur->getNom();} ?></p>
    				<div class="temps-inscription"><?= Functions::dateInscriptionInterval($utilisateur->getDateInscription()) ?></div>
				</div>
				
				<div class="profil-picture">
					<?php if($utilisateur->getProfilPicture() == null){ ?>
					
					<form method="post" enctype="multipart/form-data" id="profil-picture-form">
						<input type="file" id="profilFile" name="profilFile"/>
						<label for="profilFile" class="profllLabel"><?= Functions::getUtilisateurInitial($utilisateur->getNom(), $utilisateur->getPrenoms()) ?></label>
						<input type="submit" id="submit-profil-picture" />
					</form>
					
					<?php }else{?>
					
						<form method="post" enctype="multipart/form-data" id="profil-picture-form">
							<input type="file" id="profilFile" name="profilFile"/>
							<label for="profilFile"><img src="<?= $utilisateur->getProfilPicture() ?>" alt="Photo de profil" /></label>
							<input type="submit" id="submit-profil-picture" />
						</form>
					
					<?php }?>
				</div>
			</div>
			
			<div class="espace-client-menu">
				<ul class="container">
					<li><a href="#" class="espace-client-link espace-client-profil">Profil</a></li>
					<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() != Constants::COMPTE_ENTREPRISE){ ?>
					<li><a href="#" class="espace-client-link espace-client-formation">Formations</a></li>
					<li><a href="#" class="espace-client-link espace-client-facture">Factures</a></li>
					<?php }?>
				</ul>
			</div>
		
			<div class="espace-client-content"></div>
			
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
		<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js" type="text/javascript"></script>
		<script src="/inc/js/espace-client/index.js" type="text/javascript"></script>
	</body>
	
</html>