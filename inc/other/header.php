<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");

session_start();
    
use utils\Constants;
use utils\Functions;
use manager\AchatManager;


$nombreFormation = 0;

if(!empty($_SESSION["utilisateur"])){
    $achatManager = new AchatManager();
    
    if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){
        $nombreFormation = $achatManager->nombreFormationNotPaidPanier($_SESSION["utilisateur"]->getEntreprise()->getId(),true);
    }else{
        $nombreFormation = $achatManager->nombreFormationNotPaidPanier($_SESSION["utilisateur"]->getId(),false);
    }
}

?>
<header>

	<div class="container">
	
    	<a href="/" class="logo-container not-mobile">
    		<img src="/inc/images/logo.png" alt="Logo Gehant" />
    	</a>
    
    	 <?php require_once ("menu.php"); ?>
    	 
		<div class="menu-extra-container">
    		<?php if(empty($_SESSION["utilisateur"])){  ?>
    		
    			<a href="/compte/connexion" class="compte-container"> <img
				src="/inc/images/icones/compte.png" alt="Icone de compte" /> <span>Compte</span>
			</a>
    			
    		<?php } else { ?>
    		
    			<div class="user-connect-container">
    				<?php if($_SESSION["utilisateur"]->getProfilPicture() == null){ ?>
    					<div class="not-profil-picture"><?= Functions::getUtilisateurInitial($_SESSION["utilisateur"]->getNom(), $_SESSION["utilisateur"]->getPrenoms()) ?></div>
    				<?php }else{?>
    					<img src="<?= $_SESSION["utilisateur"]->getProfilPicture() ?>"
					alt="Photo de profil de l'utilisateur" />
    				<?php }?>
    				
    				<ul>
					<li><a href="/espace-client">Espace client</a></li>
    					<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>
    						<li><a href="/espace-client/entreprise">Entreprise</a></li>
    					<?php }?>
    					<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ADMIN){ ?>
    						<li><a href="/w1-admin">Administration</a></li>
    					<?php }?>
    					<li><a
						href="<?php echo "/compte/deconnexion/" . $_SESSION["utilisateur"] -> getId(); ?>">Deconnexion</a></li>
				</ul>
			</div>
    			
    		<?php } ?>
    	
    	<a href="/panier" class="basket-container">
    		<i class="bi bi-cart4"></i>
    			<?php if(!empty($_SESSION["utilisateur"]) && $nombreFormation > 0){ ?>
    				<span class="gbadge"><?= $nombreFormation ?></span><?php }?>
    			</a> 
    			
    			<a href="" class="search-container"> 
    				<img src="/inc/images/icones/search.png" alt="Icone de compte" /> <span>Rechercher</span>
				</a>
		</div>

		<div class="menu-res-extra-container">
            <?php if(empty($_SESSION["utilisateur"])){ ?>
                <a href="/compte/connexion" class="compte-container">
    				<img src="/inc/images/icones/compte.png" alt="Icone de compte"/>
    				<span>Compte</span>
    			</a>
            <?php }?>
            
            <a href="/panier" class="basket-container"><i class="bi bi-cart4"></i><?php if(!empty($_SESSION["utilisateur"]) && $nombreFormation > 0){ ?><span class="gbadge"><?= $nombreFormation ?></span><?php }?></a>
            
        	<div class="res-menu-icon"><i class="bi bi-list"></i></div>
    		
    	</div>
    	
    	
	
	
	</div>

</header>

<div class="rs-container">
			
	<a href=""></a>
	<a href=""></a>
	<a href=""></a>
	<a href=""></a>
	<a href=""></a>
	
</div>

