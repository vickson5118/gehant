<?php 
    
    use manager\DomaineManager;
use utils\Constants;

    require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
    
    $domaineManager = new DomaineManager();
    $listeDomaine = $domaineManager->getAllDomaineWithoutLocked();

?>
<nav class="navbar navbar-expand-md">
  <div class="container">
  
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
    
      <ul class="navbar-nav">
      
        <li class="nav-item">
          <a class="nav-link" href="/">ACCUEIL</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link nav-domaines" href="/domaines">DOMAINE DE FORMATIONS</a>
          <ul class="smenu">
          		<?php foreach ($listeDomaine as $menuDomaine){ ?>
          			<li><a href="/formations/<?= $menuDomaine->getTitreUrl()?>"><?= strtoupper($menuDomaine->getTitre()) ?></a></li>
          		<?php }?>
			</ul>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/contactez-nous">CONTACTEZ-NOUS</a>
        </li>
      
      </ul>
      
    </div>
  </div>
</nav>


<div class="mobile-menu">
	<div class="close-menu"><i class="bi bi-x"></i></div>
	<a href="/" class="logo-container">
    		<img src="/inc/images/logo.png" alt="Logo Gehant" />
    	</a>

	 <ul class="navbar-nav">
      
        <li class="nav-item">
          <a class="nav-link" href="/">ACCUEIL</a>
        </li>
        
        <li class="nav-item">
          <span class="nav-link nav-smenu">DOMAINE DE FORMATIONS <i class="bi bi-plus"></i></span>
          <ul class="smenu">
          		<?php foreach ($listeDomaine as $menuDomaine){ ?>
          			<li><a href="/formations/<?= $menuDomaine->getTitreUrl()?>"><?= $menuDomaine->getTitre() ?></a></li>
          		<?php }?>
			</ul>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/contactez-nous">CONTACTEZ-NOUS</a>
        </li>
        
        	<?php if(!empty($_SESSION["utilisateur"])){  ?>
             <li class="nav-item">
             	<span class="nav-link nav-smenu"><?= strtoupper($_SESSION["utilisateur"]->getNom()) ?> <i class="bi bi-plus"></i></span>
             	<ul class="smenu">
              		<li><a href="/espace-client">ESPACE CLIENT</a></li>
    					<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>
    						<li><a href="/espace-client/entreprise">ENTREPRISE</a></li>
    					<?php }?>
    					<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ADMIN){ ?>
    						<li><a href="/w1-admin">ADMINISTRATION</a></li>
    					<?php }?>
    					<li><a
						href="<?php echo "/compte/deconnexion/" . $_SESSION["utilisateur"] -> getId(); ?>">DECONNEXION</a></li>
    			</ul>
            </li>
        <?php }?>
      </ul>
      
      
</div>


