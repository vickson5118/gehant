<?php

namespace components\entreprise;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");

session_start ();

use utils\Constants;
use utils\Functions;
use manager\AchatManager;
use manager\FormationManager;


if (($_SESSION ["utilisateur"]) == null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/compte/connexion" );
    exit ();
}else if(!empty($_SESSION ["utilisateur"]) && $_SESSION ["utilisateur"]->getTypeCompte()->getId() != Constants::COMPTE_ENTREPRISE){
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]);
    exit ();
}

$formationManager = new FormationManager();
$achatManager = new AchatManager();

$formation = $formationManager -> getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]),
    Functions::getValueChamp($_GET["formation"]),false);

/*$formation = $achatManager -> getOneFormationEntrepriseInfo(Functions::getValueChamp($_GET["domaine"]),
    Functions::getValueChamp($_GET["formation"]),$_SESSION["utilisateur"]->getEntreprise()->getId());*/

if($formation != null){
    $_SESSION["formationId"] = $formation->getId();
    $listeFormationParticipant = $achatManager->getListeFormationParticipant($formation->getId(), $_SESSION["utilisateur"]->getEntreprise()->getId());
}else{
    http_response_code(404);
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Formation - <?= $formation->getTitre() ?> | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/entreprise/presentation.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
		
		<div class="container-fluid">
    			<div class="formation-info-banniere" style="background-image: url('<?= $formation->getIllustration() ?>')">
    				<div class="banniere-bg">
    					<div class="container">
        					<h1>Formation - <span class="formation-title"><?= $formation->getTitre() ?></span></h1>
        					<p class="formation-but"><?= $formation->getBut() ?></p>
        					<div class="formateur">Formateur : <?= $formation->getAuteur()->getPrenoms()." ".$formation->getAuteur()->getNom() ?></div>
        					<div class="date"><?= Functions::formatFormationDate($formation->getDateDebut(), $formation->getDateFin()) ?></div>
        					<div class="lieu"><?= $formation->getLieu() ?></div>
        					<div class="prix">$<?= $formation->getPrix() ?></div>
        					<div class="description"><?= $formation->getDescription() ?></div>
        				</div>
    				</div>
    			</div>
			</div>
	
		<div class="container-fluid">
			
			<div class="container">
				
			<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les participants</h3>

					<button type="button" class="btn-add-participant" title="Ajouter un nouvel participant" value="<?= $formation->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropAddParticipant"><i class="bi bi-plus-square-fill"></i></button>
					<button type="button" class="btn-add-exist-participant" title="Ajouter un employé enregistré" value="<?= $formation->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropAddExistParticipant"><i style="font-size: 32px;" class="bi bi-person-plus-fill"></i></button>
				</div>
				<div class="table-responsive-md panel-body" style="padding-top: 20px;padding-bottom: 20px;">
					<?php if(empty($listeFormationParticipant)){ ?>
					<h3 class="text-center">Aucun participant à cette formation</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom &amp; prénoms</th>
								<th scope="col" class="col-email">Email</th>
								<th scope="col" class="col-telephone">Telephone</th>
								<th scope="col" class="col-fonction">Fonction</th>
								<th scope="col">Payé</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listeFormationParticipant as $key => $achat){
							    ?>
							    <tr style="text-align: center;">
    								<th scope="row"><?= ($key+1) ?></th>
    								<td class="row-name"><?= $achat->getUtilisateur()->getPrenoms()." ".$achat->getUtilisateur()->getNom() ?></td>
    								<td class="row-email"><?= $achat->getUtilisateur()->getEmail() ?></td>
    								<td class="row-telephone"><?= $achat->getUtilisateur()->getTelephone() ?></td>
    								<td class="row-fonction"><?= $achat->getUtilisateur()->getFonction() ?></td>
    								<?php if($achat->getConfirmPaid()){ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
    								<?php }else{ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
    								<?php } ?>
    								<td>
    									<?php if(!$achat->getPaidForced()){ ?>
    										<button data-bs-toggle="modal" data-bs-target="#staticBackdropAnnulerEntrepriseInscription" value="<?= $achat->getId() ?>" type="button" class="btn btn-danger btn-modal-confirm-entreprise-delete-formation-inscription" title="Supprimer l'achat">
        										<i class="bi bi-x-lg"></i>
        									</button>
        								<?php }?>
    								</td>
    							</tr>
							<?php }?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>
				
				
			</div>
			<?php
                require_once("modal/modal-add-participant.php");
                require_once("modal/modal-add-exist-participant.php");
                require_once("modal/confirm-delete-formation-inscription.php");
                ?>
                
                <?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
		<script src="/inc/js/entreprise/presentation.js" type="text/javascript"></script>
	</body>
	
</html>