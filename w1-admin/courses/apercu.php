<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/ModuleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/PointCleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");

session_start();

use manager\FormationManager;
use utils\Constants;
use utils\Functions;
use manager\ModuleManager;
use manager\PointCleManager;
use manager\AchatManager;

if (($_SESSION["utilisateur"]) == null) {
    header("Location: http://" . $_SERVER["SERVER_NAME"] . "/w1-admin/index.php");
    exit();
} else if ($_SESSION ["utilisateur"]->getTypeCompte()->getId() != Constants::COMPTE_ADMIN) {
    header("Location: http://" . $_SERVER ["SERVER_NAME"]);
    exit ();
}

$formationManager = new FormationManager();
$formation = $formationManager->getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]), Functions::getValueChamp($_GET["formation"]), true);

if ($formation != null) {
    $moduleManager = new ModuleManager();
    $pointCleManager = new PointCleManager();
    $achatManager = new AchatManager();
    $listeModule = $moduleManager->getAllModuleByFormation($formation->getId());
    $listePointCle = $pointCleManager->getAllPointCleByFormation($formation->getId());
    $listeFormationParticipant = $achatManager->getAllFormationParticipant($formation->getId());
} else {
    http_response_code(404);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Formation - <?= $formation->getTitre() ?> | Gehant</title>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/css.php"); ?>
    <link rel="stylesheet" href="/inc/css/formation/presentation.css" type="text/css"/>
    <link rel="stylesheet" href="/inc/css/w1-admin/apercu.css" type="text/css"/>
</head>

<body>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

<div class="page-container">

    <div class="container-fluid">
        <div class="formation-info-banniere" style="background-image: url('<?= $formation->getIllustration() ?>')">
            <div class="formation-banniere-bg">
                <div class="container">
                    <h1>Formation - <?= $formation->getTitre() ?></h1>
                    <p class="formation-but"><?= $formation->getBut() ?>Lorem ipsum dolor sit amet, consectetur
                        adipisicing elit. Accusamus nulla beatae necessitatibus ipsum totam eligendi.</p>
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
            <div class="col-md-9">

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
            <aside class="col-md-3">

                <div class="aside-infos">
                    <p>Brochure</p>
                    <div class="date"><?= Functions::formatFormationDate($formation->getDateDebut(), $formation->getDateFin()) ?></div>
                    <div class="lieu"><?= $formation->getLieu() ?></div>
                    <div class="prix">$<?= $formation->getPrix() ?> </div>

                </div>

            </aside>
        </div>
    </div>

</div>


<?php if ($formation->getRedactionFinished()) { ?>
    <div class="container">
        <div class="panel panel-success">
            <div class="panel-header">
                <h3>Les participants</h3>
            </div>
            <div class="panel-body"
                 style="padding-top: 20px; padding-bottom: 20px;">
                <?php if (empty($listeFormationParticipant)) { ?>
                    <h3 class="text-center">Aucun participant à cette formation</h3>
                <?php } else { ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Payé</th>
                            <th scope="col">Nom &amp; prénoms</th>
                            <th scope="col">Email</th>
                            <th scope="col">Telephone</th>
                            <th scope="col">Fonction</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($listeFormationParticipant as $key => $achat) {
                            ?>
                            <tr style="text-align: center;">
                                <th scope="row"><?= ($key + 1) ?></th>
                                <?php if ($achat->getPaid()) { ?>
                                    <td style="margin-top: 10px;"
                                        class="badge rounded-pill bg-success">Oui
                                    </td>
                                <?php } else { ?>
                                    <td style="margin-top: 10px;"
                                        class="badge rounded-pill bg-danger">Non
                                    </td>
                                <?php } ?>
                                <td class="row-name"><?= $achat->getUtilisateur()->getPrenoms() . " " . $achat->getUtilisateur()->getNom() ?></td>
                                <td class="row-email"><?= $achat->getUtilisateur()->getEmail() ?></td>
                                <td><?= $achat->getUtilisateur()->getTelephone() ?></td>

                                <td><?= $achat->getUtilisateur()->getFonction() ?></td>

                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>

    </div>
<?php } ?>


<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/js.php"); ?>
</body>

</html>