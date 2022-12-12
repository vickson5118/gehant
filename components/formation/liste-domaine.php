<?php 
namespace components\formation;

use manager\DomaineManager;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");

session_start (); 


$domaineManager = new DomaineManager();
$listeDomaine = $domaineManager->getAllDomaineWithoutLocked();

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Les domaines de formations | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/formation/liste-domaine.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid all-domaine-container">
		
    		<div class="all-domaine-container-head">
    			<div class="all-domaine-container-bg">
    				<div class="container">
    					<h1>Nos domaines de formations</h1>
        				<p>
        					Nos domaines de formation sont variés. Du commercial à l’administration en passant par la communication, 
                        	l’informatique, les multimédias…. Tous les domaines sont réunis ici pour vous permettre (à vous ou à votre organisation) 
                        	de vous former sur les sujets pour lesquels vous exprimez besoin
        				</p>
    				</div>
    			</div>
    		</div>
		
			<div class="container one-domaine-container">
				
				<div class="row">
					<?php foreach ($listeDomaine as $domaine){ ?>
    					<div class="col-md-4 one-domaine">
    						<img src="<?= $domaine->getIllustration() ?>" alt="<?= $domaine->getTitre() ?>" />
    						<h2><?= $domaine->getTitre() ?></h2>
    						<a href="/formations/<?= $domaine->getTitreUrl() ?>">Voir les formations</a>
    					</div>
					<?php } ?>
					
				</div>
				
			</div>
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
	</body>
	
</html>