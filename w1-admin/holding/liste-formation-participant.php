<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/EntrepriseManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

session_start (); 

use manager\AchatManager;
use manager\EntrepriseManager;
use utils\Constants;
use utils\Functions;
use manager\FormationManager;



if (($_SESSION ["utilisateur"]) == null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin/index.php" );
    exit ();
}else if($_SESSION ["utilisateur"] ->getTypeCompte()->getId() != Constants::COMPTE_ADMIN){
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
    exit ();
}


$entrepriseId = intval(Functions::getValueChamp($_GET ["entreprise"]));
$formationId = intval(Functions::getValueChamp($_GET ["formation"]));

if($entrepriseId == 0 || $formationId == 0){
    http_response_code(404);
    exit();
}


$achatManager = new AchatManager();
$entrepriseManager = new EntrepriseManager();
$formationManager = new FormationManager();

$listeFormationParticipant = $achatManager->getListeFormationParticipant($formationId,$entrepriseId);
$entrepriseName = $entrepriseManager->getName($entrepriseId);
$formationTitre = $formationManager->getFormationTitre($formationId);


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
				
				<div class="panel panel-success">
				<div class="panel-header">
					<h5 style="color: white;">Les participants de l'entreprise <b><?= $entrepriseName ?></b> à la formation <b><?= $formationTitre ?></b></h5>
				</div>
				<div class="panel-body" style="padding-top: 20px;padding-bottom: 20px;">
					<?php if(empty($listeFormationParticipant)){ ?>
					<h3 class="text-center">Aucun participant à cette formation</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Payé</th>
								<th scope="col">Nom &amp; prénoms</th>
								<th scope="col">Email</th>
								<th scope="col">Telephone</th>
								<th scope="col">Fonction</th>	
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listeFormationParticipant as $key => $achat){
							    ?>
							    <tr style="text-align: center;">
    								<th scope="row"><?= ($key+1) ?></th>
    								<?php if($achat->getConfirmPaid()){ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
    								<?php }else{ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
    								<?php } ?>
    								<td class="row-name"><?= $achat->getUtilisateur()->getPrenoms()." ".$achat->getUtilisateur()->getNom() ?></td>
    								<td class="row-email"><?= $achat->getUtilisateur()->getEmail() ?></td>
    								<td><?= $achat->getUtilisateur()->getTelephone() ?></td>
    								<td><?= $achat->getUtilisateur()->getFonction() ?></td>
    								
    							</tr>
							<?php }?>
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