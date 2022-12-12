<?php
use manager\FormationManager;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

$formationManager = new FormationManager();
$listeFourMostSold = $formationManager->getFourMostSold();
?>

<div class="best-courses-container">

	<div class="container">

		<h2>Nos meilleures formations</h2>
		<p>La plupart des particuliers et des organisations ont suivi ces formations. Nous pensons que vous pouvez les suivre également</p>

		<div class="row">
			
			<?php foreach ($listeFourMostSold as $formation){ ?>
			<div class="one-best-course">
				<div class="image-container">
					<img src="<?= $formation->getIllustration() ?>" alt="<?= $formation->getTitre() ?>" />
				</div>
				<a href="/formations/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"><?= $formation->getTitre() ?></a>
			</div>
			<?php } ?>
			
		</div>

	</div>

</div>