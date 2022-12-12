<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");

use manager\FormationManager;
use manager\DomaineManager;
use utils\Functions;
    
session_start();

if (($_SESSION ["utilisateur"]) == null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin" );
    exit ();
}

$formationManager = new FormationManager();
$formation = $formationManager -> getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]),
    Functions::getValueChamp($_GET["formation"]),true);

if($formation == null){
    http_response_code(404);
    exit();
}

$domaineManager = new DomaineManager();
$listeDomaine = $domaineManager->getAllDomaineWithoutLocked();
$_SESSION["formation"] = $formation;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>La présentation générale de la formation | Gehant administration</title>
<meta charset="utf-8" />
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
<link rel="stylesheet" href="/inc/css/w1-admin/edit-formation.css" type="text/css"/>
<link rel="stylesheet" href="/inc/css/w1-admin/edit/presentation.css" type="text/css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
</head>
<body>

	<!-- Header -->
	<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

	<div class="container-fluid">
		<div class="container">
			<div class="row">

				<?php require_once($_SERVER["DOCUMENT_ROOT"]."/w1-admin/courses/menu-edit.php"); ?>

				<div class="col-md-9 formation-infos-container">
					<div class="section-header">
						<h1>Page d'accueil de la formation</h1>
					</div>
					<div class="section-content">

						<form method="post" enctype="multipart/form-data" id="basics-form">

							<div class="mb-3">
								<label for="titre-formation" class="form-label">Titre de la formation</label> 
								<input type="text" name="titre" value="<?= $formation->getTitre() ?>" class="form-control" id="titre" placeholder="Ex: Comprendre le droit">
								<div class="error"></div>
							</div>

							<div class="mb-3">
								<label for="but" class="form-label">But de la formation</label>
								<input type="text" name="but" value="<?= $formation->getBut() ?>" class="form-control" id="but" placeholder="Ex: Intégrer les fondamentaux du droit">
								<div class="error"></div>
							</div>
							
							<div class="mb-3">
								<label for="lieu" class="form-label">Lieu de la formation</label>
								<input type="text" name="lieu" value="<?= $formation->getLieu() ?>" class="form-control" id="lieu" placeholder="Ex: 2 plateaux">
								<div class="error"></div>
							</div>
							
							<div class="row">
							
    							<div class="col-md-6 mb-3">
    								<label for="debut" class="form-label">Date de début</label> 
    								<input type="text" name="debut" readonly="readonly" id="debut" class="form-control" value="<?= $formation->getDateDebut() ?>" placeholder="Ex: 10/06/2021" style="background-color: transparent;"/>
    								<div class="error"></div>
    							</div>

								<div class="col-md-6">
									<label for="fin" class="form-label">Date de fin</label> 
									<input type="text" name="fin" readonly="readonly" id="fin" class="form-control" value="<?= $formation->getDateFin() ?>" placeholder="Ex: 15/06/2021" style="background-color: transparent;"/>
									<div class="error"></div>
								</div>

							</div>


							<div class="row">
							
    							<div class="col-md-6 mb-3">
    								<label for="domaine" class="form-label">Domaine de formation</label> 
    								<select class="form-select" id="domaine" name="domaine">
    									<?php foreach ($listeDomaine as $domaine){ ?>
    										<option value="<?= $domaine->getId() ?>" <?php if($_SESSION["formation"]->getDomaine()->getId() == $domaine->getId()){ ?>selected="selected" <?php } ?>  ><?= ucfirst(strtolower($domaine->getTitre())) ?></option>
    									<?php } ?>
    								</select>
    								<div class="error"></div>
    							</div>

								<div class="col-md-6">
									<label for="prix" class="form-label">Prix de la formation en Dollars</label> 
									<input name="prix" type="text" value="<?= $formation->getPrix() ?>" class="form-control" id="prix" placeholder="Exemple: 15000">
									<div class="error"></div>
								</div>

							</div>

							<div class="mb-3">
								<label for="description" class="form-label">Description de la formation</label>
								<textarea name="description" class="form-control" id="description" rows="6"><?= $formation->getDescription() ?></textarea>
									<div class="error"></div>
							</div>

							<div class="row">
								
							<div class="illustration-container col-md-7" style="margin-top: 30px;">
								<label style="margin-bottom: 10px;">Image d'illustration de la formation</label>
								<div id="img-container">
									<?php if($formation->getIllustration() == null){ ?>
										<img src="/inc/images/w1-admin/telechargement.jpg" alt="image par defaut" />
									<?php }else{ ?>
										<img src="<?= $formation->getIllustration()?>" alt="<?= $formation->getTitre() ?>" />
									<?php } ?>
								</div>
							</div>
							
							<div class="illustration-directives col-md-5" style="margin-top: 110px;">
								<p>Téléchargez votre image de cours ici. Pour être acceptée, elle doit répondre à nos normes de qualité 
								relatives aux images de cours. Directives importantes : 1920 x 900 pixels ; format .jpg, .jpeg, ou .png ; 
								aucun texte sur l'image.</p>
								
								<input class="form-control" type="file" id="formationIllustration" name="formationIllustration" accept="image/*">
								
							</div>
							
							</div>


							<button id="btn-valide-presentation-info" type="submit">Valider les informations</button>


						</form>

					</div>

				</div>

			</div>
		</div>
	</div>

	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	<script src="/inc/js/w1-admin/edit-formation.js" type="text/javascript"></script>
	<script src="/inc/js/w1-admin/edit/presentation.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js" type="text/javascript"></script>
</body>
</html>