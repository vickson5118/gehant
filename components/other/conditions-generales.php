<?php
namespace components\other;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Conditions générales | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/other/conditions-generales.css"
	type="text/css" />
</head>

<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid">

		<div class="container conditions-container">
			<div class="row">
				<div class="col-md-9">

					<h1>Conditions Générales de vente GEHANT</h1>

					<div>
						<h2>Article 1 : Objet des présentes conditions</h2>
						<p>
							Les présentes conditions générales de ventes détaillent les
							droits et les obligations de l’entreprise GEHANT et de ses
							clients dans le cadre de la vente des formations offertes par le
							cabinet. <br /> Toute prestation accomplie par le cabinet GEHANT
							implique l’adhésion sans réserve de l’acheteur aux conditions
							générales de vente ci-dessous.
						</p>
					</div>

					<div>
						<h2>Article 2 : Présentation des formations</h2>
						<p>
							Les formations proposées par le cabinet GEHANT se trouvent dans
							l’onglet « DOMAINE DE FORMATIONS ». Les photographies n’entrent
							pas dans le champ contractuel. <br /> Le contenu du site est
							réservé. Au titre des droits d’auteur et de propriété
							intellectuelle, la reproduction partielle ou totale est
							strictement interdite.
						</p>
					</div>

					<div>
						<h2>Article 3 : Détenteurs de compte</h2>
						<p>Toute personne physique désirant suivre une formation peut
							ouvrir un compte et formuler une demande de formation. Une
							entreprise peut également créer un compte afin de formuler des
							demandes de formation pour son staff. Dans ce cas, l’entreprise
							assure le paiement des frais de formation pour l’ensemble des
							personnes de son portefeuille.</p>
					</div>

					<div>
						<h2>Article 4 : Tarifs des formations</h2>
						<p>
							Les tarifs des formations figurant sur le site sont affichés en
							TTC. Ils prennent en compte la formation dispensée par un
							formateur ainsi que les supports de formation. <br /> GEHANT se
							réserve le droit de modifier les prix à tout moment, mais les
							formations sont facturées au prix en vigueur lors de la commande
							de formation.
						</p>
					</div>

					<div>
						<h2>Article 5 : Le début de la formation</h2>
						<p>La formation est dispensée lorsque les participants ont procédé
							au règlement des frais de la formation. Une confirmation de
							l’intention de participer à une formation ne tient pas lieu
							règlement.</p>
					</div>

					<div>
						<h2>Article 6 : Mode de paiement</h2>
						<p>Les frais de formation sont réglés par tous les moyens :
							Chèques, virement ou espèces… La latitude est offerte au
							participant de faire le choix qui lui convient.</p>
					</div>

					<div>
						<h2>Article 7 : Hébergement</h2>
						<p>Les frais d’hébergement ne sont pas pris en compte dans les
							tarifs affichés. Le Cabinet peut, si vous souhaitez, vous aider à
							héberger les participants venus en formation. Les frais
							d’hébergement peuvent être considérés comme des frais
							additionnels. Dans ce cas, GEHANT assurera la prise en
							charge complète des participants.</p>
					</div>

					<div>
						<h2>Article 8 : Certificat de formation</h2>
						<p>Un certificat de formation est remis aux participants ayant
							régulièrement suivi la formation et validé les tests soumis à la
							fin de la formation.</p>
					</div>

				</div>
				
				<div class="col-md-3 actions-container">
					<a href="/domaines" type="button">Voir les cours <i class="bi bi-chevron-right"></i></a>
					<a href="/contactez-nous">Contactez-nous <i class="bi bi-chevron-right"></i></a>
					<?php if($_SESSION["utilisateur"] == null){ ?>
					<a href="/compte/connexion">Se connecter <i class="bi bi-chevron-right"></i></a>
					<?php }?>
				</div>
				
			</div>



		</div>
			
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
			
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
	</body>

</html>