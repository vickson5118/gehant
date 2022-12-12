<?php

namespace components\compte;

session_start ();

if (($_SESSION ["utilisateur"]) != null) {
	header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
	exit ();
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Se Connecter | Gehant</title>
		<?php

		require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/css.php");
		?>
		<link rel="stylesheet" href="/inc/css/compte/connexion.css" type="text/css" />
	</head>

	<body>

		<div class="container-fluid">

			<div class="container">

				<div class="connexion-form-container">

					<div class="div-slide-bg"></div>

					<div class="connexion-box-container">

						<div class="form-box-container">

    						<div class="logo-box">
    							<a href="/" class="logo-container">
            						<img src="/inc/images/logo.png" alt="Logo Gehant" />
            					</a>
    						</div>

							<div class="connexion-text">Connexion</div>

        					<div class="welcome-text">Connectez-vous pour profiter pleinement des formations et des services offerts</div>

        					<form action="">
        					
        						<div class="connexion-error"></div>

        						<div class="form-floating email-container mb-3">
        							  <img src="/inc/images/icones/connexion/email.png" alt="Email icone" />
                                      <input type="email" class="form-control" id="email" name="email" placeholder="Adresse mail">
                                      <label for="email">Adresse mail</label>
                                </div>

        						<div class="form-floating password-container">
        							<img src="/inc/images/icones/connexion/pass.png" alt="Mot de passe icone" />
                                 	<input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                                 	<label for="password">Mot de passe</label>
                                </div>
								
								<a href="/compte/redefinir-mot-de-passe" class="redef-password">Mot de passe oublié</a>
                                <a href="/compte/inscription" class="link-create-compte">Créer un compte</a>
                                <button type="button" id="btn-connexion">Se connecter</button>


        					</form>
							<!--  
        					<div class="rs-connect">
                            	<div>Se connecter avec:</div>
                            	<button type="button" class="btn-facebook"></button>
                            	<button type="button" class="btn-google"></button>
                            	<button type="button" class="btn-linkedin"></button>
                            </div>
                            -->
						</div>

                        <div class="details-box-container">

                        	<p>Bonjour</p>

    						<p>Entrez vos informations personnelles et créez un compte particulier GEHANT .</p>

    						<button type="button">Créer un compte</button>

    					</div>

					</div>



					<div class="inscription-box-container">

    					<div class="form-box-container">

    						<div class="logo-box">
    							<a href="/" class="logo-container">
            						<img src="/inc/images/logo.png" alt="Logo Gehant" />
            					</a>
    						</div>

    						<div class="connexion-text">Créer un compte</div>

        					<div class="welcome-text">Créer un compte en seulement quelques clics</div>

        					<form action="">

        						<div class="form-floating nom-container mb-3">
        							  <img src="/inc/images/icones/connexion/user.png" alt="Nom icone" />
                                      <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom">
                                      <label for="nom">Nom</label>
                                      <div class="error"></div>
                                </div>

        						<div class="form-floating prenoms-container mb-3">
        							  <img src="/inc/images/icones/connexion/user.png" alt="Prenoms icone" />
                                      <input type="text" class="form-control" id="prenoms" name="prenoms" placeholder="Prenoms">
                                      <label for="prenoms">Prenoms</label>
                                      <div class="error"></div>
                                </div>

                                <div class="form-floating email-inscription-container mb-3">
        							  <img src="/inc/images/icones/connexion/email.png" alt="Email icone" />
                                      <input type="email" class="form-control" id="ins-email" name="ins-email" placeholder="Adresse mail">
                                      <label for="ins-email">Adresse mail</label>
                                      <div class="error"></div>
                                </div>
								
								 <a href="/compte/inscription/entreprise">Compte entreprise</a>
								 <a href="/compte/inscription" class="link-connexion">Se connecter</a>
                                <button type="button" class="create-particulier-compte">Creer un compte</button>


        					</form>
							<!-- 
        					<div class="rs-connect">
                            	<div>Se connecter avec:</div>
                            	<button type="button" class="btn-facebook"></button>
                            	<button type="button" class="btn-google"></button>
                            	<button type="button" class="btn-linkedin"></button>
                            </div>
                             -->
    					</div>

                        <div class="details-box-container">

    						<p>Bienvenue</p>

    						<p>Connectez vous et profitez des differentes formations.</p>

    						<button type="button">Se connecter</button>

    					</div>

					</div>




				</div>

			</div>

		</div>

		<?php

		require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/js.php");
		?>
		<script src="/inc/js/compte/connexion.js" type="text/javascript"></script>
	</body>

</html>