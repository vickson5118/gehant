<?php 

namespace components\entreprise;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Facture.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FactureManager.php");

session_start (); 

use manager\FactureManager;
use utils\Functions;


Functions::redirectWhenNotConnexionEntreprise($_SESSION["utilisateur"]);

$factureManager = new FactureManager();
$listeFacture = $factureManager->getListeFacture($_SESSION["utilisateur"]->getEntreprise()->getId(),true);

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Facture entreprise | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/espace-client/facture.css" type="text/css" />
		<link rel="stylesheet" href="/inc/css/entreprise/facture.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid" style="margin-top: 100px;">
		
			<div class="container">
		<?php if(empty($listeFacture)){ ?>	
    		<div class="col-md-8 justify-content-center">
    			<div class="historique-icon"><i class="bi bi-receipt"></i></div>
    			<p class="text-historique-vide">Vous n'avez aucune facture enrégistrée</p>
    			<a href="/" type="button" class="btn-begin-purchase">Commencer les achats</a>
    		</div>
		<?php }else{ ?>
		
		<div class="panel panel-success">
    		<div class="panel-header">
    			<h3>Les Factures</h3>
    		</div>
			<div class="panel-body" style="padding-top: 20px;padding-bottom: 20px;">
				<table class="table">
        		  <thead>
        		    <tr>
        		      <th scope="col">N°</th>
        		      <th scope="col" class="col-designation">N° Facture</th>
        		      <th scope="col" class="col-date">Date</th>
        		      <th scope="col"></th>
        		    </tr>
        		  </thead>
        		  <tbody>
        				<?php foreach ($listeFacture as $key => $facture){ ?>   		
            	    		<tr>
            			      <th scope="row" class="fcount"><?= $key+1 ?></th>
            			      <td class="fdesignation"><?= $facture->getDesignation() ?></td>
            			      <td class="fdate"><?= $facture->getDateCreation() ?></td>
            			       <td><a href="<?= $facture->getPdf() ?>" class="download-recu" target="_blank"><i class="bi bi-cloud-download-fill"></i></a></td>
            			    </tr>
        			    <?php } ?>
        			  </tbody>
        			</table>
			</div>
		</div>
		
			<?php } ?>
			</div>
			
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
			
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
	</body>
	
</html>