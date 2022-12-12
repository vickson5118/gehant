<?php 

use manager\ContactManager;
use manager\AchatManager;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ContactManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");

$contactManager = new ContactManager();
$achatManager = new AchatManager();

?>
<!--  <header>-->
	<div class="sidebar">
		<div class="logo-content">
			<div class="logo">
				<h2><a href="/">Gehant</a></h2>
			</div>
			<i class='bx bx-menu' id="btn-menu"></i>
		</div>
		<nav>
			<ul class="nav_list">
				<li>
					<a href="/w1-admin/dashboard">
						<i class='bx bx-grid-alt'></i>
						<span class="link-name">Tableau de bord</span>
					</a>
					<span class=tooltip-content>Tableau de bord</span>
				</li>
				
				<li>
					<a href="/w1-admin/administrateurs">
						<i class='bx bx-street-view'></i>
						<span class="link-name">Administrateurs</span>
					</a>
					<span class="tooltip-content">Administrateurs</span> 
				</li>
				
				<li>
					<a href="/w1-admin/utilisateurs">
						<i class='bx bxs-user-detail' ></i>
						<span class="link-name">Utilisateurs</span>
					</a>
					<span class="tooltip-content">Utilisateurs</span> 
				</li>
				
				<li>
					<a href="/w1-admin/entreprises">
						<i class='bx bxs-buildings'></i>
						<span class="link-name">Entreprises</span>
					</a>
					<span class="tooltip-content">Entreprises</span> 
				</li>
				
				<li>
					<a href="/w1-admin/achats">
						<i class="bi bi-cart4"></i>
						<?php if($achatManager->isNotConfirmPaid()){?>
    						<span class="menu-not-view" 
    						style="display: block;height: 10px;width: 10px;background-color: red;position: absolute;margin-top: -15px;margin-left: 15px; border-radius: 50%;" ></span>
						<?php }?>
						<span class="link-name">Achats</span>
					</a>
					<span class="tooltip-content">Achats</span> 
				</li>
				
				<li>
					<a href="/w1-admin/domaines">
						<i class="bi bi-journals"></i>
						<span class="link-name">Domaines</span>
					</a>
					<span class="tooltip-content">Domaines</span> 
				</li>
				
				<li>
					<a href="/w1-admin/formations">
						<i class="bi bi-book-fill"></i>
						<span class="link-name">Formations</span>
					</a>
					<span class="tooltip-content">Formations</span> 
				</li>
				
				<li>
					<a href="/w1-admin/partenaires">
						<i class='bx bi-people-fill' ></i>
						<span class="link-name">Partenaires</span>
					</a>
					<span class="tooltip-content">Partenaires</span> 
				</li>
				
				<li>
					<a href="/w1-admin/contacts">
						<i class='bx bx-mail-send' ></i>
						<?php if($contactManager->isNotView()){?>
    						<span class="menu-not-view" 
    						style="display: block;height: 10px;width: 10px;background-color: red;position: absolute;margin-top: -15px;margin-left: 15px; border-radius: 50%;" ></span>
						<?php }?>
						<span class="link-name">Contactez-nous</span>
					</a>
					<span class="tooltip-content">Contactez-nous</span> 
				</li>
				
				
				
			</ul>
		</nav>

		<div class="log-out-container">
			<a href="/compte/deconnexion/<?= $_SESSION["utilisateur"]->getId() ?>" title="Deconnexion"><span>Deconnexion</span> <i class='bx bx-log-out' id="log-out"></i></a>
		</div>

	</div>
<!-- </header> -->