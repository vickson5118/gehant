<?php

namespace components\compte;

require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/NombreEmployeManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/SecteurManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/ObjectifManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/PartenaireManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Partenaire.php");

use manager\NombreEmployeManager;
use manager\SecteurManager;
use manager\ObjectifManager;
use manager\PartenaireManager;

session_start();

if (($_SESSION["utilisateur"]) != null) {
    header("Location: http://" . $_SERVER["SERVER_NAME"]);
    exit();
}

$partenaireManager = new PartenaireManager();
$listePartenaire = $partenaireManager->getAll();

$nombreEmployeManager = new NombreEmployeManager();
$secteurManager = new SecteurManager();
$objectifManager = new ObjectifManager();
$listeNombreEmploye = $nombreEmployeManager->getAll();
$listeSecteur = $secteurManager->getAll();
$listeObjectif = $objectifManager->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inscription entreprise | Gehant</title>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/css.php"); ?>
    <link rel="stylesheet" type="text/css"
          href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="/inc/css/compte/inscription-entreprise.css"
          type="text/css"/>
</head>

<body>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/header.php"); ?>

<div class="container-fluid entreprise-container">

    <div class="container">

        <div class="row">
            <div class="col-md-4 text-boost-container">

                <p>Préparez vos équipes au monde de demain</p>

                <div class="performance">
                    <img src="/inc/images/icones/business/performance.png"
                         alt="Icone de performance"/>
                    <div>Un suivi personnalisé par notre équipe avec un accès
                        disponible 24 h/24 h
                    </div>
                </div>

                <div class="quality">
                    <img src="/inc/images/icones/business/quality.png"
                         alt="Icone de qualité"/>
                    <div>Un contenu pédagogique de qualité et reconnu avec des
                        exercices pour s'évaluer
                    </div>
                </div>

                <div class="mentor">
                    <img src="/inc/images/icones/business/mentor.png"
                         alt="Icone de mentor"/>
                    <div>De nombreux mentors disponibles pour accompagner la
                        progression de vos employés
                    </div>
                </div>

                <div class="part-box-container">

                    <div>Ils nous font confiance</div>

                    <div class="part-container">
                        <?php foreach ($listePartenaire as $partenaire) { ?>
                            <div class="one-pub"><img src="<?= $partenaire->getLogo() ?>"
                                                      alt="<?= $partenaire->getNom() ?>"/></div>
                        <?php } ?>

                    </div>

                </div>

            </div>

            <div class="col-md-7 form-inscription-entreprise-container">
                <p>Faisons connaissance</p>

                <form class="form-box">

                    <div class="row">
                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="nom">Nom*</label> <input type="text" name="nom"
                                                                 id="nom" required="required"/>
                            <div class="error"></div>
                        </div>

                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="prenoms">Prenoms*</label> <input type="text"
                                                                         name="prenoms" id="prenoms"
                                                                         required="required"/>
                            <div class="error"></div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="email">Adresse E-mail*</label> <input type="email"
                                                                              name="email" id="email"
                                                                              required="required"/>
                            <div class="error"></div>
                        </div>

                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="telephone">Téléphone</label> <input type="text"
                                                                            name="telephone" id="telephone"
                                                                            required="required"/>
                            <div class="error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="entreprise">Nom de l'entreprise*</label> <input
                                    type="text" name="entreprise" id="entreprise"
                                    required="required"/>
                            <div class="error"></div>
                        </div>

                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="employes">Nombre d'employés*</label>
                            <select name="employes" id="employes">
                                <?php foreach ($listeNombreEmploye as $nombreEmploye) { ?>
                                    <option value="<?= $nombreEmploye->getId(); ?>"><?= $nombreEmploye->getTranche(); ?></option>
                                <?php } ?>

                            </select>
                            <div class="error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="objectif">Votre objectif*</label>
                            <select name="objectif" id="objectif">
                                <?php foreach ($listeObjectif as $objectif) { ?>
                                    <option value="<?= $objectif->getId(); ?>"><?= $objectif->getNom(); ?></option>
                                <?php } ?>
                                <option value="0">Autre</option>
                            </select>
                            <div class="error"></div>
                        </div>

                        <div class="col-md-6 col-sm-6 form-input-container">
                            <label for="secteur">Secteur d'activité*</label>
                            <select name="secteur" id="secteur">
                                <?php foreach ($listeSecteur as $secteur) { ?>
                                    <option value="<?= $secteur->getId(); ?>"><?= $secteur->getNom(); ?></option>
                                <?php } ?>
                                <option value="0">Autre</option>
                            </select>
                            <div class="error"></div>
                        </div>
                    </div>

                    <p>En remplissant ce formulaire, vous acceptez que les informations
                        ci-dessus soient traitées par le cabinet Gehant pour répondre à
                        votre demande et éventuellement être contacté par un
                        conseiller par mail, sms et/ou téléphone.</p>

                    <button type="button" id="btn-inscription-entreprise">Valider</button>

                </form>

            </div>

        </div>

    </div>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/footer.php"); ?>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/js.php"); ?>
<script type="text/javascript"
        src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="/inc/js/compte/inscription-entreprise.js"
        type="text/javascript"></script>
</body>

</html>