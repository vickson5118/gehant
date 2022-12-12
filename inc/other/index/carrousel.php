<?php
use manager\FormationManager;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");
$formationManager = new FormationManager();
$listeFourLastFormation = $formationManager -> getFourLastFormation();
?>

<div class="carrousel-container">
	<div class="carrousel-cover">
		<div class="carrousel-infos">
			<h1>+ de 1000 formations destinées aux particuliers, aux entreprises
				et aux institutions</h1>
			<p>Notre mission est de vous accompagner dans le but d’atteindre vos
				objectifs de performance. Les formations offertes conduisent à une
				véritable transformation des hommes et des organisations.</p>
		</div>
	</div>
</div>

<div class="carrousel-details-container">

	<h2>Choisissez et suivez vos formations de partout</h2>
	<p  class="container">En quelques clics, faites le choix de vos formations à tout moment.
		Choisissez le lieu où vous désirez les suivre. Décidez de suivre la
		formation seul ou en groupe, en ligne ou en présentiel. Grâce à
		l’expérience de nos formateurs et aux outils mis à disposition au
		cours des séances, vous produirez d’excellents rendements.</p>

	<div class="container">
		<div class="row">
			<?php foreach ($listeFourLastFormation as $formation){ ?>
			<div class="details-card">
			<img src="<?= $formation->getIllustration() ?>"
				alt="<?= $formation->getTitre() ?>" />
			<h3>
				<a
					href="/formations/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"><?= $formation->getTitre() ?></a>
			</h3>
		</div>
			<?php } ?>
			
		</div>
	</div>

</div>
