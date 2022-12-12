<?php


require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");

session_start();

use manager\FormationManager;
use utils\Functions;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$formationManager = new FormationManager();
$listeFormation = $formationManager->getAllFormationByDomaine(Functions::getValueChamp($_GET["domaine"]));

$formationActif = false;
$formationLocked = false;
$formationWriting = false;
$formationExpired = false;

foreach ($listeFormation as $formation) {

    if (!$formation->isBloquer() && $formation->isRedactionFinished()) {
        $formationActif = true;
    }

    if (!$formation->isRedactionFinished()) {
        $formationWriting = true;
    }

    if ($formation->isBloquer()) {
        $formationLocked = true;
    }

    if (Functions::convertDateFrToEn($formation->getDateDebut()) < date("Y-m-d")) {
        $formationExpired = true;
    }

}

//mettre la url du domaine en session
$_SESSION["domaineUrl"] = Functions::getValueChamp($_GET["domaine"]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Liste des formations du domaine.. | Gehant</title>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/css.php"); ?>
</head>
<body>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

<div class="page-container">
    <div class="container-fluid">

        <!-- Button Creer une formation modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#staticBackdropCreateFormationInDomaine">Créer une
            formation
        </button>

        <!-- Listes des formations en cours d'eciture -->
        <div class="panel panel-default">
            <div class="panel-header">
                <h3>Les formations en cours d'ecriture</h3>
            </div>
            <div class="panel-body table-responsive">
                <?php if (!$formationWriting) { ?>
                    <h3 class="text-center">Aucune formation de ce domaine en cours de
                        redaction.</h3>
                <?php } else { ?>

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountWriting = 0;
                        foreach ($listeFormation as $courseWriting) {
                            if (!$courseWriting->isRedactionFinished()) {
                                $indexCountWriting++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountWriting ?></th>
                                    <td><?= $courseWriting->getTitre() ?></td>
                                    <td class="row-email"><?= $courseWriting->getAuteur()->getEmail() ?></td>
                                    <td>
                                        <a href="/w1-admin/creation/formation/<?= $courseWriting->getDomaine()->getTitreUrl() ?>/<?= $courseWriting->getTitreUrl() ?>/prerequis"
                                           class="btn btn-primary" title="Modifier la formation"> <i
                                                    class="bi bi-pencil-fill"></i>
                                        </a>

                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button"
                                                class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur"><i class="bi bi-envelope"></i>
                                        </button>

                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>


        <!-- Listes des formations fonctionnelles -->
        <div class="panel panel-success">
            <div class="panel-header">
                <h3>Les formations en ligne</h3>
            </div>
            <div class="panel-body table-responsive">
                <?php if (!$formationActif) { ?>
                    <h3 class="text-center">Aucune formation de ce domaine active.</h3>
                <?php } else { ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Nbre d'inscrits</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountOnLine = 0;
                        foreach ($listeFormation as $courseActif) {
                            if (!$courseActif->isBloquer() && $courseActif->isRedactionFinished() && Functions::convertDateFrToEn($courseActif->getDateDebut()) >= date("Y-m-d")) {
                                $indexCountOnLine++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountOnLine ?></th>
                                    <td class="formation-title"><?= $courseActif->getTitre() ?></td>
                                    <td class="row-email"><?= $courseActif->getAuteur()->getEmail() ?></td>
                                    <td><?= $courseActif->getNombreAchat() ?></td>
                                    <td><?= $courseActif->getDateDebut() ?></td>
                                    <td><?= $courseActif->getDateFin() ?></td>
                                    <td>
                                        <a href="/w1-admin/liste-acheteur/<?= $courseActif->getId() ?>"
                                           class="btn btn-primary" title="Voir les achéteurs de la formation"> <i
                                                    class="bi bi-person-fill"></i></a>
                                        <a href="/w1-admin/apercu/formation/<?= $courseActif->getDomaine()->getTitreUrl() ?>/<?= $courseActif->getTitreUrl() ?>"
                                           class="btn btn-success" title="Aperçu de la formation"> <i
                                                    class="bi bi-eye-fill"></i></a>
                                        <a href="/w1-admin/creation/formation/<?= $courseActif->getDomaine()->getTitreUrl() ?>/<?= $courseActif->getTitreUrl() ?>/prerequis"
                                           class="btn btn-primary" title="Modifier la formation"> <i
                                                    class="bi bi-pencil-fill"></i></a>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button"
                                                class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur"><i class="bi bi-envelope"></i>
                                        </button>
                                        <button data-bs-toggle="modal" data-bs-target="#staticBackdropBloquerFormation"
                                                value="<?= $courseActif->getId() ?>" type="button"
                                                class="btn btn-danger btn-bloquer-formation"
                                                title="Bloquer la formation"><i class="bi bi-lock-fill"></i></button>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                <?php } ?>

            </div>
        </div>

        <!-- Listes des formations expirées -->
        <div class="panel panel-warning">
            <div class="panel-header">
                <h3>Les formations expirées</h3>
            </div>
            <div class="panel-body table-responsive">
                <?php if (!$formationExpired) { ?>
                    <h3 class="text-center">Aucune formation de ce domaine.</h3>
                <?php } else { ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Nbre d'inscrits</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountExpired = 0;
                        foreach ($listeFormation as $courseExpired) {
                            if (Functions::convertDateFrToEn($courseExpired->getDateDebut()) < date("Y-m-d")) {
                                $indexCountExpired++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountExpired ?></th>
                                    <td class="formation-title"><?= $courseExpired->getTitre() ?></td>
                                    <td class="row-email"><?= $courseExpired->getAuteur()->getEmail() ?></td>
                                    <td><?= $courseExpired->getNombreAchat() ?></td>
                                    <td><?= $courseExpired->getDateDebut() ?></td>
                                    <td><?= $courseExpired->getDateFin() ?></td>
                                    <td>
                                        <a href="/w1-admin/apercu/formation/<?= $courseExpired->getDomaine()->getTitreUrl() ?>/<?= $courseExpired->getTitreUrl() ?>"
                                           class="btn btn-success" title="Aperçu de la formation"> <i
                                                    class="bi bi-eye-fill"></i></a>
                                        <a href="/w1-admin/liste-acheteur/<?= $courseExpired->getId() ?>"
                                           class="btn btn-primary" title="Voir les achéteurs de la formation"> <i
                                                    class="bi bi-person-fill"></i></a>
                                        <a href="/w1-admin/creation/formation/<?= $courseExpired->getDomaine()->getTitreUrl() ?>/<?= $courseExpired->getTitreUrl() ?>/prerequis"
                                           class="btn btn-primary" title="Modifier la formation"> <i
                                                    class="bi bi-pencil-fill"></i></a>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button"
                                                class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur"><i class="bi bi-envelope"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                <?php } ?>

            </div>
        </div>


        <!-- Liste domaines bloqués -->
        <div class="panel panel-danger">
            <div class="panel-header">
                <h3>Les formations bloquées</h3>
            </div>
            <div class="panel-body table-responsive">
                <?php if (!$formationLocked) { ?>
                    <h3 class="text-center">Aucune formation de ce domaine bloquée.</h3>
                <?php } else { ?>

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Motif</th>
                            <th scope="col">Blocage</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountLocked = 0;
                        foreach ($listeFormation as $courseLocked) {
                            if ($courseLocked->getBloquer()) {
                                $indexCountLocked++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountLocked ?></th>
                                    <td class="formation-title"><?= $courseLocked->getTitre() ?></td>
                                    <td class="row-email"><?= $courseLocked->getAuteur()->getEmail() ?></td>
                                    <td><?= $courseLocked->getMotifBlocage() ?></td>
                                    <td><?= $courseLocked->getDateBlocage() ?></td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button"
                                                class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur"><i class="bi bi-envelope"></i>
                                        </button>

                                        <button data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropDebloquerFormation"
                                                value="<?= $courseLocked->getId() ?>"
                                                type="button" class="btn btn-success btn-debloquer-formation"
                                                title="Débloquer la formation">
                                            <i class="bi bi-unlock-fill"></i>
                                        </button>

                                    </td>
                                </tr>

                            <?php }
                        } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>


    </div>

    <!-- Modals -->
    <?php
    require_once("modal/modal-create-formation-in-domaine.php");
    require_once("../admins/modal/modal-send-mail.php");
    require_once("../courses/modal/modal-bloquer-formation.php");
    require_once("../courses/modal/modal-debloquer-formation.php");
    ?>


</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/js.php"); ?>
<script src="/inc/js/w1-admin/liste-formation-domaine.js" type="text/javascript"></script>
<script src="/inc/js/w1-admin/liste-formation.js" type="text/javascript"></script>
</body>
</html>