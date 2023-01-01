<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FactureManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Facture.php");

session_start ();

use manager\FactureManager;

$factureManager = new FactureManager();
$listeFacture = $factureManager->getListeFacture($_SESSION["utilisateur"]->getId(),false);

?>

<div class="container">

	<div class="row">
		<?php if(empty($listeFacture)){ ?>
    		<div class="col-md-8 justify-content-center">
    			<div class="historique-icon"><i class="bi bi-receipt"></i></div>
    			<p class="text-historique-vide">Vous n'avez encore effectué aucun achat de formation.</p>
    			<a href="/" type="button" class="btn-begin-purchase">Commencer les achats</a>
    		</div>
		<?php }else{ ?>
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">N°</th>
		      <th scope="col" class="col-designation">Référence</th>
		      <th scope="col" class="col-date">Proforma</th>
		      <th scope="col">Facture</th>
		    </tr>
		  </thead>
		  <tbody>
				<?php foreach ($listeFacture as $key => $facture){ ?>
    	    		<tr>
    			      <th scope="row" class="fcount"><?= $key+1 ?></th>
    			      <td class="fdesignation"><?= $facture->getDesignation() ?></td>
                        <td><a href="<?= $facture->getProforma() ?>" class="download-recu" target="_blank"><i class="bi bi-cloud-download-fill"></i></a></td>
                    <?php if(!empty($facture->getPdf())){ ?>
                        <td><a href="<?= $facture->getPdf() ?>" class="download-recu" target="_blank"><i class="bi bi-cloud-download-fill"></i></a></td>
                    <?php }else{ ?>
                        <td class="text-danger">En attente de paiement </td>
                    <?php } ?>
    			    </tr>
			    <?php } ?>
			  </tbody>
			</table>
			<?php } ?>
	</div>

</div>