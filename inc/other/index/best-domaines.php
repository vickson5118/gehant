<?php 

require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/DomaineManager.php");
use manager\DomaineManager;

$domaineManager = new DomaineManager();
$listeNineLastDomaine = $domaineManager -> getIndexLastDomaine();


?>
<div class=" container best-domaines-container">

	<h1>Nos domaines de formations</h1>
	<p>Nos domaines de formation sont variés. Du commercial à l’administration en passant par la communication, 
    	l’informatique, les multimédias…. Tous les domaines sont réunis ici pour vous permettre (à vous ou à votre organisation) 
    	de vous former sur les sujets pour lesquels vous exprimez besoin</p>
	
	<div class="container">
		
		<div class="row">
			<?php foreach ($listeNineLastDomaine as $domaine){ ?>
    			<div class="col-md-3 one-best-domaine">
    				<a href="/formations/<?= $domaine->getTitreUrl() ?>">
    					<img src="<?= $domaine->getIllustration() ?>" alt="<?= $domaine->getTitre() ?>" />
    					<h2><?= $domaine->getTitre() ?></h2>
    				</a>
    			</div>
    		<?php } ?>

		</div>
		
	
	</div>


</div>