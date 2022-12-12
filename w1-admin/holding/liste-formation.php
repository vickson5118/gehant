<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/EntrepriseManager.php");

session_start (); 

use manager\AchatManager;
use utils\Functions;
use manager\EntrepriseManager;


Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$entrepriseId = intval(Functions::getValueChamp($_GET ["entreprise"]));

if($entrepriseId == 0){
    http_response_code(404);
    exit();
}

$achatManager = new AchatManager();
$entrepriseManager = new EntrepriseManager();

$listeFormationAchete = $achatManager->getListeFormationConfirmPaid($entrepriseId,true);
$entrepriseName = $entrepriseManager->getName($entrepriseId);


?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>  Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
	</head>
	
	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
			<div class="container-fluid">
				
				<!-- Listes des formations fonctionnelles -->
			<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les formations achetées par <b><?= $entrepriseName ?></b></h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listeFormationAchete)){ ?>
						<h3 class="text-center">Aucune formation achetée.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Titre</th>
								<th scope="col">Auteur</th>
								<th scope="col">Domaine</th>
								<th scope="col" style="display: none;">Domaine url</th>
								<th scope="col">Date</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listeFormationAchete as $key => $achat){ ?>
								<tr>
    								<th scope="row"><?= ($key+1)?></th>
    								<td class="formation-title"><?= $achat->getFormation()->getTitre() ?></td>
    								<td class="row-email"><?= $achat->getFormation()->getAuteur()->getPrenoms()." ".$achat->getFormation()->getAuteur()->getNom() ?></td>
    								<td class="row-domaine"><?= $achat->getFormation()->getDomaine()->getTitre() ?></td>
    								<td style="display: none;" class="row-domaine-url"><?= $achat->getFormation()->getDomaine()->getTitre() ?></td>
    								<td><?= Functions::formatFormationDate($achat->getFormation()->getDateDebut(), $achat->getFormation()->getDateFin()) ?></td>
    								<td>
        								<a href="/w1-admin/entreprise/formations/participants/<?= $achat->getFormation()->getId() ?>/<?= $entrepriseId ?>" class="btn btn-success" title="Les participants"><i class="bi bi-eye-fill"></i></a>
    								</td>
    							</tr>
							
							<?php } ?>

						</tbody>
					</table>
					<?php } ?>
				</div>
			</div>
				
			</div>
			
		</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	</body>
	
</html>