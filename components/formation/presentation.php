<?php

namespace components\formation;

require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/ModuleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/PointCleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");

session_start();

use manager\FormationManager;
use utils\Functions;
use manager\ModuleManager;
use manager\PointCleManager;
use utils\Constants;
use manager\AchatManager;

$achatManager = new AchatManager();
$formationManager = new FormationManager();

$formation = $formationManager->getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]), Functions::getValueChamp($_GET["formation"]), false);

if ($formation != null) {

    $listeFormation = $formationManager->getTwelveLastFormationWithoutThis($formation->getId());

    $moduleManager = new ModuleManager();
    $pointCleManager = new PointCleManager();
    $listeModule = $moduleManager->getAllModuleByFormation($formation->getId());
    $listePointCle = $pointCleManager->getAllPointCleByFormation($formation->getId());
} else {
    http_response_code(404);
    exit();
}

if ($formation->isBloquer()) {
    http_response_code(404);
    exit();
}

$userIsRegisterInFormationOrBuy = null;
if($_SESSION["utilisateur"] != null){
    $userIsRegisterInFormationOrBuy = $achatManager->userIsRegisterInFormationAndPaidOrConfirmPaid($_SESSION["utilisateur"]->getId(), $formation->getId());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Formation - <?= $formation->getTitre() ?> | Gehant</title>
    <?php require_once($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/css.php"); ?>
    <link rel="stylesheet" type="text/css"
          href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="/inc/css/formation/presentation.css" type="text/css"/>
</head>

<body>

<?php require_once($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/header.php"); ?>

<div class="container-fluid">
    <div class="formation-info-banniere" style="background-image: url('<?= $formation->getIllustration() ?>')">
        <div class="formation-banniere-bg">
            <div class="container">
                <h1>Formation - <?= $formation->getTitre() ?></h1>
                <p class="formation-but"><?= $formation->getBut() ?></p>
                <div class="formateur">Par
                    : <?= $formation->getAuteur()->getPrenoms() . " " . $formation->getAuteur()->getNom() ?></div>
            </div>
        </div>
    </div>

    <div class="formation-info-menu">
        <div class="container">
            <a href="#introduction">Introduction</a>
            <a href="#cibles">Cibles</a>
            <a href="#prerequis">Prérequis</a>
            <a href="#objectifs">Objectifs</a>
            <a href="#programme">Programme</a>
        </div>
    </div>

</div>

<div class="container">

    <div class="row">
        <div class="formation-infos-content col-md-9">

            <aside class="mobile-aside">

                <div class="aside-infos">
                    <p>Dates</p>
                    <div class="date"><?= Functions::formatFormationDate($formation->getDateDebut(), $formation->getDateFin()) ?></div>
                    <div class="lieu"><?= $formation->getLieu() ?></div>
                    <div class="prix">$<?= $formation->getPrix() ?></div>
                </div>

                <div class="aside-other-btn-container">

                    <?php if (!empty($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE) { ?>
                        <a href="/espace-client/entreprise/formations/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"
                           class="link-add-participant">
                            Ajouter un participant <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php } else if (!empty($_SESSION["utilisateur"]) && $userIsRegisterInFormationOrBuy == null) { ?>
                        <button id="mobile-btn-register" class="btn-register" type="button"
                                value="<?= $formation->getId() ?>">
                            S'enregistrer <i class="bi bi-chevron-right"></i>
                        </button>
                    <?php } else if (empty($_SESSION["utilisateur"])) { ?>
                        <a href="/compte/connexion" class="btn-register" type="button">S'enregistrer <i
                                    class="bi bi-chevron-right"></i></a>
                    <?php } ?>
                    <!--
                    <button class="btn-download" type="button"
                title="Télécharger la brochure">
                Télécharger le PDF <i class="bi bi-cloud-download-fill"></i>
            </button>-->
                </div>

            </aside>

            <div class="description-container">
                <div id="introduction"></div>
                <h3>Introduction</h3>
                <p><?= $formation->getDescription() ?></p>
            </div>


            <div class="formation-infos">

                <div class="who-container">
                    <div id="cibles"></div>
                    <span class="chapter-point"></span>
                    <h3>A qui s'adresse cette formation</h3>
                    <ul>
                        <?php
                        $listeCible = explode(";", $formation->getCibles());
                        foreach ($listeCible as $cible) {
                            ?>
                            <li><?= $cible ?></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="prerequis-container">
                    <div id="prerequis"></div>
                    <span class="chapter-point"></span>
                    <h3>Les prérequis de la formation</h3>
                    <ul>
                        <?php
                        $listePrerequis = explode(";", $formation->getPrerequis());
                        foreach ($listePrerequis as $prerequis) {
                            ?>
                            <li><?= $prerequis ?></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="objectif-container">
                    <div id="objectifs"></div>
                    <span class="chapter-point"></span>
                    <h3>Les objectifs de la formation</h3>
                    <ul>
                        <?php
                        $listeObjectif = explode(";", $formation->getObjectifs());
                        foreach ($listeObjectif as $objectif) {
                            ?>
                            <li><?= $objectif ?></li>
                        <?php } ?>
                    </ul>
                </div>

            </div>

            <div class="programme-container">
                <div id="programme"></div>
                <span class="chapter-point"></span>
                <h3>Programme</h3>
                <br/>

                <?php foreach ($listeModule as $key => $module) { ?>
                    <div class="one-day">
                        <span class="day-point">Jour <?= $key + 1 ?></span>
                        <p class="day-title"><?= $module->getTitre() ?></p>
                        <ul>
                            <?php
                            foreach ($listePointCle as $pointCle) {
                                if ($module->getId() == $pointCle->getModule()->getId()) {
                                    ?>
                                    <li><?= $pointCle->getTitre() ?></li>
                                <?php }
                            } ?>
                        </ul>
                    </div>
                <?php } ?>

            </div>

        </div>

        <aside class="aside-container col-md-3">

            <div class="aside-infos">
                <p>Dates</p>
                <div class="date"><?= Functions::formatFormationDate($formation->getDateDebut(), $formation->getDateFin()) ?></div>
                <div class="lieu"><?= $formation->getLieu() ?></div>
                <div class="prix">$<?= $formation->getPrix() ?></div>
                <?php if($_SESSION["utilisateur"] != null && ($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_STANDARD || $_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ADMIN)
                    && $userIsRegisterInFormationOrBuy != null && $userIsRegisterInFormationOrBuy->isPaid() && !$userIsRegisterInFormationOrBuy->isConfirmPaid()){ ?>
                    <div class="paiement-statut text-danger">En attente de paiement</div>
                <?php }else if($_SESSION["utilisateur"] != null && ($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_STANDARD || $_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ADMIN)
                    && $userIsRegisterInFormationOrBuy != null && $userIsRegisterInFormationOrBuy->isPaid() && $userIsRegisterInFormationOrBuy->isConfirmPaid()) { ?>
                    <div class="paiement-statut text-success">Payé</div>
                <?php } ?>
            </div>

            <div class="aside-other-btn-container">

                <?php if (!empty($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE) { ?>
                    <a href="/espace-client/entreprise/formations/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"
                       class="link-add-participant">
                        Ajouter un participant <i class="bi bi-chevron-right"></i>
                    </a>
                <?php } else if (!empty($_SESSION["utilisateur"]) && $userIsRegisterInFormationOrBuy == null) { ?>
                    <button class="btn-register" id="btn-register" type="button" value="<?= $formation->getId() ?>">
                        S'enregistrer <i class="bi bi-chevron-right"></i>
                    </button>
                <?php } else if (empty($_SESSION["utilisateur"])) { ?>
                    <a href="/compte/connexion" class="btn-register" type="button">S'enregistrer <i
                                class="bi bi-chevron-right"></i></a>
                <?php } ?>
                <!--
                <button class="btn-download" type="button"
            title="Télécharger la brochure">
            Télécharger le PDF <i class="bi bi-cloud-download-fill"></i>
        </button>-->
            </div>

        </aside>

    </div>

</div>

<?php if (!empty($listeFormation)) { ?>
    <div class="container">

        <div class="other-formation-container">
            <span class="chapter-point"></span>
            <h3>Ces formations pourraient vous interesser</h3>

            <div class="row">

                <?php foreach ($listeFormation as $interrestingFormation) { ?>
                    <div class="col-md-3 formation-item">
                        <div class="card">
                            <img src="<?= $interrestingFormation->getIllustration() ?>"
                                 class="card-img-top"
                                 alt='<?= $interrestingFormation->getTitre() ?>'>
                            <div class="card-body">

                                <h1 class="card-title">
                                    <a
                                            href="/formations/<?= $interrestingFormation->getDomaine()->getTitreUrl() ?>/<?= $interrestingFormation->getTitreUrl() ?>">
                                        <span class="cours-title"><?= $interrestingFormation->getTitre() ?></span>
                                    </a>
                                </h1>
                                <div class="date"><?= Functions::formatFormationDate($interrestingFormation->getDateDebut(), $interrestingFormation->getDateFin()) ?></div>

                            </div>
                        </div>
                    </div>
                <?php } ?>


            </div>


        </div>
    </div>

<?php } ?>


<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/footer.php");
?>

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/js.php");
?>
<script type="text/javascript"
        src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="/inc/js/formation/presentation.js" type="text/javascript"></script>
</body>

</html>