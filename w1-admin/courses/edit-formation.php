<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

session_start();

use manager\FormationManager;
use utils\Constants;
use utils\Functions;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$formationManager = new FormationManager();
$formation = $formationManager -> getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]), Functions::getValueChamp($_GET["formation"]),true);

if($formation == null){
    http_response_code(404);
    exit();
}

$_SESSION["formation"] = $formation;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Editer une formation | Gehant administration</title>
<meta charset="utf-8" />
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
<link rel="stylesheet" href="/inc/css/w1-admin/edit-formation.css" type="text/css"/>
</head>
<body>

	<!-- Header -->
	<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

	<div class="container-fluid">
		<div class="container">
			<div class="row">

				<?php require_once($_SERVER["DOCUMENT_ROOT"]."/w1-admin/courses/menu-edit.php"); ?>

				<div class="col-md-9 formation-infos-container" id="edit-formation-container">
					<p class="edit-welcome">Bienvenue sur la page de creation d'un article</p>
					
					<div class="welcome-texte">
						<p>Pour la création de l'article vous devez respecter certaines conditions pour passer la validation</p>
						<ul>
							<li>Eviter les fautes d'ortographe et de grammaire</li>
							<li>Respecter les tailles des images</li>
							<li>S'assurer que les images importées s'affiche correctement</li>
							<li>S'assurer des reponses des différentes questions des exercices</li>
						</ul>
					</div>
					
					<a href="/w1-admin/creation/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>/prerequis" class="btn-begin-redaction">Commencer la redaction</a>
				</div>

			</div>
		</div>
	</div>


	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	<script src="/inc/js/w1-admin/edit-formation.js" type="text/javascript"></script>
</body>
</html>