<?php 

namespace components\formation;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

session_start (); 

use manager\DomaineManager;
use utils\Functions;
use manager\FormationManager;

$domaineManager = new DomaineManager();
$currentDomaine = $domaineManager->getOneDomaine(Functions::getValueChamp($_GET["domaine"]));


if($currentDomaine != null){
    $formationManager = new FormationManager();
    $listeOtherDomaine = $domaineManager->getOtherDomaineWithoutLocked($currentDomaine->getId());
    $listeFormation = $formationManager->getFormationByDomaineInfoWhithoutLocked($currentDomaine->getId());
}else{
    http_response_code(404);
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?= ucfirst(strtolower($currentDomaine->getTitre())) ?> | Gehant</title>
		<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/formation/domaine.css" type="text/css" />
	</head>

	<body>

		<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/header.php"); ?>

		<div class="container-fluid">

			<div class="domaine-title-banniere">
				<img src="<?= $currentDomaine->getIllustration(); ?>" alt="<?= $currentDomaine->getTitre(); ?>" />
				<div class="banniere-bg">

					<div class="container">
    					<h1><?= $currentDomaine->getTitre(); ?></h1>
    					<p><?= $currentDomaine->getDescription(); ?></p>
    				</div>

				</div>

			</div>

			<div class="container liste-formation-container">

				<p><b>Résultat</b> : <?php if(sizeof($listeFormation) <= 1){ echo sizeof($listeFormation)." formation";}else{ echo sizeof($listeFormation)." formations";} ?></p>
				
				<?php foreach ($listeFormation as $formation){ ?>
    				<div class="one-formation-container">
    				<a href="/formations/<?= $currentDomaine->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>">
    					<h2><?= $formation->getTitre() ?></h2>
    					<div class="one-formation-content">
    						<p class="formation-date"><?= Functions::formatFormationDate($formation->getDateDebut(), $formation->getDateFin()) ?></p>
        					<p class="formation-prix">$<?= $formation->getPrix() ?></p>
        					<p class="formation-lieu"><?= $formation->getLieu() ?></p>
        					<i class="bi bi-arrow-right-square-fill"></i>
    					</div>
    				</a>
    				</div>
				<?php } ?>

			</div>

			<?php if(!empty($listeOtherDomaine)){ ?>
				<div class="other-domaines-container">

    				<div class="container">
    
    					<p>Autres domaines de formation</p>
    					<div class="other-domaines-content">
    					
    						<?php foreach ($listeOtherDomaine as $domaine){ ?>
            				<div class="one-domaine">
            					<a href="/formations/<?= $domaine->getTitreUrl() ?>"><?= $domaine->getTitre() ?></a>
            				</div>
    					<?php }?>
    					
    					</div>
    
    
    				</div>
    
    			</div>
			<?php }?>
			
				<?php  require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/footer.php");?>
			
		</div>

	
		<?php

        require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/js.php");
		?>
	</body>

</html>