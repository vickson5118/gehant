<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PartenaireManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Partenaire.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");

use manager\PartenaireManager;
use utils\Functions;

session_start();

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$partenaireManager = new PartenaireManager();
$listePartenaire = $partenaireManager -> getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des partenaires | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/w1-admin/liste-partenaire.css" type="text/css" />
	</head>

<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">
		
			<!-- Button Creer admin modal -->
			<button type="button" class="btn btn-primary" data-bs-toggle="modal"
				data-bs-target="#staticBackdropAddPartenaire" title="Ajouter un partenaire">Ajouter un partenaire</button>

			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les partenaires</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listePartenaire)){ ?>
						<h3 class="text-center">Aucun partenaire enregistré.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom</th>
								<th scope="col">Logo</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
                                foreach($listePartenaire as $key => $partenaire){
                                        ?>
								<tr>
    								<th scope="row"><?= ($key+1) ?></th>
    								<td class="row-nom"><?= $partenaire->getNom() ?></td>
    								<td><img src="<?= $partenaire->getLogo() ?>" alt="<?= $partenaire->getNom() ?>" /></td>
    								<td>
    									<button data-bs-toggle="modal" data-bs-target="#staticBackdropEditPartenaire" value="<?= $partenaire->getId() ?>" type="button" class="btn btn-primary btn-modal-edit-partenaire" title="Editer le partenaire">
    										<i class="bi bi-pencil-fill"></i>
    									</button>
    									<button data-bs-toggle="modal" data-bs-target="#staticBackdropRemovePartenaire" value="<?= $partenaire->getId() ?>" type="button" class="btn btn-danger btn-modal-remove-partenaire" title="Supprimer le partenaire">
    										<i class="bi bi-x-octagon-fill"></i>
    									</button>
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
        require_once("modal/modal-add-partenaire.php");
        require_once("modal/modal-edit-partenaire.php");
        require_once("modal/modal-remove-partenaire.php");
        ?>

	</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-partenaire.js" type="text/javascript"></script>
	</body>

</html>