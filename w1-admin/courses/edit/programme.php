<?php 

    require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ModuleManager.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PointCleManager.php");

    use manager\FormationManager;
use manager\ModuleManager;
use manager\PointCleManager;
use utils\Functions;
    
    session_start();
    
    if (($_SESSION ["utilisateur"]) == null) {
        header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin" );
        exit ();
    }

    
    $formationManager = new FormationManager();
    $formation = $formationManager -> getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]),
        Functions::getValueChamp($_GET["formation"]),true);
    
    if($formation == null){
        http_response_code(404);
        exit();
    }
    
    $_SESSION["formation"] = $formation;
    $moduleManager = new ModuleManager();
    $pointCleManager = new PointCleManager();
    
    $listeModule = $moduleManager->getAllModuleByFormation($formation->getId());
    $listePointCle = $pointCleManager->getAllPointCleByFormation($formation->getId());

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Le programme de la formation | Gehant administration</title>
<meta charset="utf-8" />
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
<link rel="stylesheet" href="/inc/css/w1-admin/edit-formation.css" type="text/css"/>
<link rel="stylesheet" href="/inc/css/w1-admin/edit/programme.css" type="text/css"/>
</head>
<body>

	<!-- Header -->
	<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

	<div class="container-fluid">
		<div class="container">
			<div class="row">

				<?php require_once($_SERVER["DOCUMENT_ROOT"]."/w1-admin/courses/menu-edit.php"); ?>

				<div class="col-md-9 formation-infos-container">
					<div class="section-header">
						<h1>Le programme</h1>
					</div>
					<div class="section-content">
						<p>
							<i class="bi bi-info-circle-fill"></i> C'est ici que vous ajoutez du contenu tel que les principaux thematiques du jour et les exercices de la formation.
						</p>
						
						<p>
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropCreateModule">Créer un module</button>
							<!-- 
							<button type="button" class="btn btn-success" data-bs-toggle="modal"data-bs-target="#staticBackdropCreateFormationQuiz">Créer un quiz</button>
						      -->
						</p>

						<div class="liste-formation-module-container">
								<?php foreach ($listeModule as $key => $module){ ?>
								<div class="one-module">
									<h3>Jour <?= ($key+1)?> - <span class="module-title"><?= $module->getTitre() ?></span></h3>
									
									<div class="module-btn">
										<button type="button" class="btn-edit-module" title="Editer le titre du module" value="<?= $module->getId(); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropEditModule">
											<i class="bi bi-pencil-fill"></i>
										</button>
										
										<button type="button" class="add-module-point-cle" title="Ajouter un point clé du module" value="<?= $module->getId(); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropCreateModulePointCle">
											<i class="bi bi-plus-square"></i>
										</button>
										
										<button type="button" class="btn-delete-module" title="Supprimer le module" value="<?= $module->getId(); ?>" data-bs-toggle="modal" data-bs-target="#deleteModuleModal">
											<i class="bi bi-x-square"></i>
										</button>
										
									</div>
									<ul class="one-module-point-cle">
										<?php foreach ($listePointCle as $pointCle){ 
										    if($pointCle->getModule()->getId() == $module->getId()){
										?>
										<li><span class="point-title"><?= $pointCle->getTitre(); ?></span>
    										<button type="button" class="btn-edit-module-point-cle" title="Editer le point clé du module" value="<?= $pointCle->getId(); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropEditModulePointCle">
    											<i class="bi bi-pencil-fill"></i>
    										</button>
    										
    										<button type="button" class="btn-delete-module-point-cle" title="Supprimer le module" value="<?= $pointCle->getId(); ?>" data-bs-toggle="modal" data-bs-target="#deleteModulePointCleModal">
    											<i class="bi bi-x-square"></i>
    										</button>
										
										</li>
										<?php } } ?>
									</ul>
									
								</div>
							<?php } ?>
						</div>

					</div>
				</div>

			</div>
		</div>
		
		<?php
		    require_once("../modal/modal-create-module.php");
		    require_once("../modal/modal-create-module-point-cle.php");
		    require_once("../modal/modal-edit-module.php");
		    require_once("../modal/modal-delete-module.php");
		    require_once("../modal/modal-edit-module-point-cle.php");
		    require_once("../modal/modal-delete-module-point-cle.php");
           ?>
           
	</div>


	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	<script src="/inc/js/w1-admin/edit-formation.js" type="text/javascript"></script>
	<script src="/inc/js/w1-admin/edit/programme.js" type="text/javascript"></script>
</body>
</html>