<?php

namespace components\compte;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/utils/Functions.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Formation.php");

use utils\Functions;
use manager\FormationManager;

session_start();
if(($_SESSION["utilisateur"]) != null){
    header("Location: http://" . $_SERVER["SERVER_NAME"]);
    exit();
}
$formationManager = new FormationManager();
$listeFormation = $formationManager -> getTwelveLastFormation();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Vous êtes un particulier ou une entreprise, inscrivez-vous et
	bénéficier des avantages du site | Gehant</title>
		<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/css.php");
?>
		<link rel="stylesheet" href="/inc/css/compte/inscription.css"
	type="text/css" />
<link rel="stylesheet" href="/inc/css/formation/presentation.css"
	type="text/css" />
</head>

<body>

		<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/header.php");
?>

		<div class="inscription-banniere">
		<div class="inscription-banniere-cover"></div>
	</div>

	<div class="container-fluid">

		<div class="container">

			<div class="row">

				<div class="col-md-6 particulier-inscription-box">
					<div class="gbadge">Particulier</div>
					<h3>Offre particulier</h3>
					<div>Inscrivez-vous pour profiter des formations afin de booster vos compétences et vos performances.</div>

					<div class="options-container">
						<div class="options">
							<i class="bi bi-check2"></i> Participez à des formations
						</div>
						<div class="options">
							<i class="bi bi-check2"></i> Evaluez votre niveau grâce aux quiz
						</div>
						<div class="options">
							<i class="bi bi-check2"></i> Obtenez des certificats
						</div>

					</div>

					<a href="/compte/connexion">Inscrivez-vous</a>
				</div>

				<div class="col-md-6 entreprise-inscription-box">
					<div class="gbadge">Entreprise</div>
					<h3>Offre entreprise</h3>
					<div>Créez un compte Entreprise pour améliorer les compétences de votre staff grâce aux 
					formations et aux services offerts..</div>

					<div class="options-container">
						<div class="options">
							<i class="bi bi-check2"></i> Ajoutez des participants aux
							formations
						</div>
						<div class="options">
							<i class="bi bi-check2"></i> Suivez la progression de vos
							collaborateurs
						</div>
						<div class="options">
							<i class="bi bi-check2"></i> Recevez les fiches de suivi de votre staff
						</div>
					</div>

					<a href="/compte/inscription/entreprise">Inscrivez-vous</a>
				</div>

			</div>

		</div>



		<div class="container">
			<div class="other-formation-container">
				<h3>Ces formations pourraient vous interesser</h3>

				<div class="row">
					<?php foreach ($listeFormation as $interrestingFormation){ ?>
    					<div class="col-md-3 formation-item">
						<div class="card">
							<img src="<?= $interrestingFormation->getIllustration() ?>"
								class="card-img-top"
								alt='<?= $interrestingFormation->getTitre() ?>'>
							<div class="card-body">
								<h1 class="card-title">
									<a
										href="/formations/<?= $interrestingFormation->getDomaine()->getTitreUrl() ?>/<?= $interrestingFormation->getTitreUrl() ?>">
										<span class="cours-title"><?= $interrestingFormation->getTitre() ?></span>
									</a>
								</h1>
								<div class="date"><?= Functions::formatFormationDate($interrestingFormation->getDateDebut(), $interrestingFormation->getDateFin()) ?></div>

							</div>
						</div>
					</div>
    				<?php }?>
						
					
					</div>


			</div>
		</div>
			
		
		<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/footer.php"); ?>
		
		</div>

		

		<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/js.php"); ?>
		<script src="/inc/js/compte/inscription.js" type="text/javascript"></script>
</body>

</html>