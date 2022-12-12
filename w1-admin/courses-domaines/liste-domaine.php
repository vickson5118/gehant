<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");

session_start (); 

use manager\DomaineManager;
use utils\Constants;

if (($_SESSION ["utilisateur"]) == null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin" );
    exit ();
}else if($_SESSION ["utilisateur"] ->getTypeCompte()->getId() != Constants::COMPTE_ADMIN){
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
    exit ();
}


$domaineManager = new DomaineManager();
$listeDomaine = $domaineManager->getAll();

$domaineActif = false;
$domaineLocked = false;

foreach ($listeDomaine as $domaine){
    
    if(!$domaine->getBloquer()){
        $domaineActif = true;
    }
    
    if($domaine->getBloquer()){
        $domaineLocked = true;
    }
}

//phpinfo();

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>liste des domaines de formation | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/w1-admin/liste-domaine.css" type="text/css" />
	</head>
	
	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">

			<!-- Button Creer domaine modal -->
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropCreateDomaine">Créer un domaine de formation</button>
	
			<!-- Listes des domaines fonctionnels -->
			<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les domaines de formations</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$domaineActif){ ?>
						<h3 class="text-center">Aucun domaine actif</h3>
					<?php }else{?>

						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th scope="col">N°</th>
									<th scope="col">Titre</th>
									<th scope="col">Nbre formations</th>
									<th scope="col">En ligne</th>
									<th scope="col">Redaction</th>		
									<th scope="col">Bloquées</th>					
									<th scope="col" style="display: none;">Desription</th>
									<th scope="col" style="display: none;">Illustration</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$indexCountOnLine = 0;
								foreach ($listeDomaine as $domaineActif){ 
								    if(!$domaineActif->getBloquer()){
								        $indexCountOnLine++;
								    ?>
									<tr>
    									<th scope="row"><?= $indexCountOnLine ?></th>
    									<td><span  class="domaine-title"><?= $domaineActif->getTitre() ?></span><span class="btn-edit-title"><button type="button" class="btn-edit-domaine" value="<?= $domaineActif->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropEditDomaine"><i class="bi bi-pencil-fill"></i></button></span></td>
    									<td><?= $domaineActif->getNombreFormationTotal() ?></td>
    									<td><?= $domaineActif->getNombreFormationActive() ?></td>
    									<td><?= $domaineActif->getNombreFormationRedaction() ?></td>
    									<td><?= $domaineActif->getNombreFormationBloquer() ?></td>
    									<td class="description" style="display: none;"><?= $domaineActif->getDescription() ?></td>
    									<td class="illustration" style="display: none;"><?= $domaineActif->getIllustration() ?></td>
    									<td>
    										<a type="button" class="btn btn-primary" title="Consulter les formations du domaine" href="/w1-admin/domaines/<?= $domaineActif->getTitreUrl() ?>"><i class="bi bi-eye-fill"></i></a>
    										<button type="button" value="<?= $domaineActif->getId() ?>" class="btn btn-danger btn-bloquer-domaine" title="Bloquer le domaine" data-bs-toggle="modal" data-bs-target="#staticBackdropBloquerDomaine"><i class="bi bi-lock-fill"></i></button>
    									</td>
    								</tr>
								<?php }}?>
							</tbody>
						</table>
					<?php } ?>



				</div>
			</div>

			<!-- Liste domaines bloqués -->
			<div class="panel panel-danger">
				<div class="panel-header">
					<h3>Les domaines bloqués</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$domaineLocked){ ?>
							<h3 class="text-center">Aucun domaine bloqué</h3>
					<?php }else{ ?>
						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th scope="col">N°</th>
									<th scope="col">Titre</th>
									<th scope="col">Nbre formations</th>
									<th scope="col">Motif blocage</th>
									<th scope="col">Date de blocage</th>				
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$indexCountLocked = 0;
								foreach ($listeDomaine as $domaineLocked){
								    if($domaineLocked->getBloquer()){
								        $indexCountLocked++;
								    ?>
									<tr>
    									<th scope="row"><?=$indexCountLocked ?></th>
    									<td class="domaine-title"><?= $domaineActif->getTitre()  ?></td>
    									<td><?= $domaineLocked->getNombreFormationTotal() ?></td>
    									<td><?= $domaineLocked->getMotifBlocage() ?></td>
    									<td><?= $domaineLocked->getDateBlocage() ?></td>
    									<td>
    										<button type="button" value="<?= $domaineLocked->getId() ?>" class="btn btn-success btn-debloquer-domaine" title="Debloquer le domaine" data-bs-toggle="modal" data-bs-target="#staticBackdropDebloquerDomaine"><i class="bi bi-unlock-fill"></i></button>
    									</td>
    								</tr>
								<?php }} ?>
							</tbody>
						</table>
					<?php } ?>

				</div>
			</div>

		</div>
		<!-- Modals -->
		<?php 
		  require_once("modal/modal-create-domaine.php");
		  require_once("modal/modal-edit-domaine.php");
		  require_once("modal/modal-bloquer-domaine.php");
		  require_once("modal/modal-debloquer-domaine.php");
		  ?>


	</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-domaine.js" type="text/javascript"></script>
	</body>
	
</html>