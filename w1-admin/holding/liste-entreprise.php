<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/EntrepriseManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ObjectifManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/SecteurManager.php");

session_start ();

use manager\EntrepriseManager;
use manager\ObjectifManager;
use manager\SecteurManager;
use utils\Functions;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$entrepriseManager = new EntrepriseManager();
$objectifManager = new ObjectifManager();
$secteurManager = new SecteurManager();

$listeEntreprise = $entrepriseManager->getAll();
$listeObjectif = $objectifManager->getAll();
$listeSecteur = $secteurManager->getAll();


?>
<!DOCTYPE html>
    <html lang="fr">
    	<head>
            <meta charset="utf-8">
            <title>Liste des entreprises | Gehant</title>
        	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
    	</head>
    
    	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">

			<!-- Listes des admins fonctionnels -->
			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les entreprises</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(empty($listeEntreprise)){ ?>
						<h3 class="text-center">Aucune entreprise enregistrée.</h3>
					<?php }else{ ?>
    					<table class="table table-hover table-bordered">
    						<thead>
    							<tr>
    								<th scope="col">N°</th>
    								<th scope="col">Nom</th>
    								<th scope="col">Nombre employés</th>
    								<th scope="col">Objectif</th>
    								<th scope="col">Secteur</th>
    								<th scope="col">Actions</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php foreach ($listeEntreprise as $key => $entreprise){ ?>
        							<tr style="text-align: center;">
        								<th scope="row"><?= $key+1 ?></th>
        								<td class="row-name"><?= $entreprise->getNom() ?></td>
        								<td class="row-email"><?= $entreprise->getNombreEmploye()->getTranche() ?></td>
        								<td><?= $entreprise->getObjectif()->getNom() ?></td>
        								<td><?= $entreprise->getSecteur()->getNom() ?></td>
        
        								<td>
        									<a href="/w1-admin/entreprise/employes/<?= $entreprise->getId() ?>" class="btn btn-primary" title="Les employés"> <i class="bi bi-people-fill"></i></a> 
        									<a href="/w1-admin/entreprise/formations/<?= $entreprise->getId() ?>" class="btn btn-success" title="Les formations achetées"> <i class="bi bi-eye-fill"></i></a> 
        								</td>
        
        							</tr>
    							<?php } ?>
    						</tbody>
    					</table>
					<?php } ?>
				</div>
			</div>
			
			<!-- Button Creer domaine modal -->
			<button style="margin-top: 20px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropCreateObjectif">Ajouter un objectif</button>
	
			
			<!-- Listes des admins fonctionnels -->
			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les objectifs</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(empty($listeObjectif)){ ?>
						<h3 class="text-center">Aucun objectif enregistré.</h3>
					<?php }else{ ?>
    					<table class="table table-hover table-bordered">
    						<thead>
    							<tr>
    								<th scope="col">N°</th>
    								<th scope="col">Nom</th>
    								<th scope="col">Actions</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php foreach ($listeObjectif as $key => $objectif){ ?>
        							<tr style="text-align: center;">
        								<th scope="row"><?= $key+1 ?></th>
        								<td class="row-name"><?= $objectif->getNom() ?></td>
        								<td>
        									<button type="button" class="btn btn-primary btn-edit-objectif" value="<?= $objectif->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropEditObjectif"><i class="bi bi-pencil-fill"></i></button> 
        								</td>
        
        							</tr>
    							<?php } ?>
    						</tbody>
    					</table>
					<?php } ?>
				</div>
			</div>
			
			<!-- Button Creer domaine modal -->
			<button style="margin-top: 20px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropCreateSecteur">Ajouter un secteur d'activité</button>
			
				<!-- Listes des admins fonctionnels -->
			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les secteurs d'activité</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(empty($listeSecteur)){ ?>
						<h3 class="text-center">Aucun secteur d'activité enregistré.</h3>
					<?php }else{ ?>
    					<table class="table table-hover table-bordered">
    						<thead>
    							<tr>
    								<th scope="col">N°</th>
    								<th scope="col">Nom</th>
    								<th scope="col">Actions</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php foreach ($listeSecteur as $key => $secteur){ ?>
        							<tr style="text-align: center;">
        								<th scope="row"><?= $key+1 ?></th>
        								<td class="row-name"><?= $secteur->getNom() ?></td>
        								<td>
        									<button type="button" class="btn btn-primary btn-edit-secteur" value="<?= $secteur->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropEditSecteur"><i class="bi bi-pencil-fill"></i></button> 
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
	
	<?php 
          require_once("modal/modal-add-secteur.php");
          require_once("modal/modal-add-objectif.php");
    	  require_once("modal/modal-edit-secteur.php");
    	  require_once("modal/modal-edit-objectif.php");
	  ?>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-entreprise.js" type="text/javascript"></script>
	</body>

</html>