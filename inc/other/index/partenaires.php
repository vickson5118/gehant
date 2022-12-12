<?php 

    require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PartenaireManager.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/src/Partenaire.php");
    
    use manager\PartenaireManager;

    $partenaireManager = new PartenaireManager();
    $listePartenaire = $partenaireManager->getAll();
    
?>

<div class="partenaires-container">

	<h2 class="container">Nous font confiance</h2>
	
	<div class="container pub-container">
	
		<?php foreach ($listePartenaire as $partenaire){ ?>
			<div class="one-pub"><img src="<?= $partenaire->getLogo() ?>" alt="<?= $partenaire->getNom() ?>" /></div>
		<?php } ?>
		
	</div>
	
	<div class="pub-prev"></div>
	<div class="pub-next"></div>
	
</div>