<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/PaysManager.php");

session_start();

use manager\PaysManager;
use utils\Constants;



if (($_SESSION ["utilisateur"]) == null) {
    header("Location: http://" . $_SERVER["SERVER_NAME"] . "/w1-admin");
    exit();
}else if($_SESSION ["utilisateur"] ->getTypeCompte()->getId() != Constants::COMPTE_ADMIN){
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
    exit ();
}

$paysManager = new PaysManager();
$listePays = $paysManager -> getAllPays();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des pays | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
	</head>

<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">

			<!-- Button modal creer une formation -->
			<button type="button" class="btn btn-primary" data-bs-toggle="modal"
				data-bs-target="#staticBackdropAddPays">Ajouter un pays</button>


			<!-- Listes des pays fonctionnels -->
			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les pays en ligne</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listePays)){ ?>
						<h3 class="text-center">Aucun pays en ligne.</h3>
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
    							<?php foreach ($listePays as $key => $pays){ ?>
        							<tr>
        								<th scope="row"><?= $key+1 ?></th>
        								<td class="nom row-pays"><?= $pays->getNom() ?></td>
        								<td>
        									<button data-bs-toggle="modal"
        										data-bs-target="#staticBackdropEditPays" value="<?= $pays->getId() ?>"
        										type="button" class="btn btn-primary btn-edit-pays"
        										title="Editer un pays">
        										<i class="bi bi-pencil-fill"></i>
        									</button>
        									
        									<button value="<?= $pays->getId() ?>" data-bs-toggle="modal" 
        											data-bs-target="#deletePaysModal" 
        											type="button" class="btn btn-danger btn-delete-pays" 
        											title="Supprimer le pays"><i class="bi bi-x-octagon-fill"></i></button>
        
        								</td>
        
        							</tr>
    							<?php } ?>
    						</tbody>
    					</table>
					<?php } ?>
				</div>
			</div>

		</div>
			
			<?php
            require_once ("modal/modal-add-pays.php");
            require_once ("modal/modal-edit-pays.php");
            require_once ("modal/modal-delete-pays.php");
            ?>
			
		</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-pays.js" type="text/javascript"></script>
</body>

</html>