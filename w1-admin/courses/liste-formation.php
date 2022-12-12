<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");

session_start();

use manager\FormationManager;
use utils\Constants;
use utils\Functions;

if (($_SESSION["utilisateur"]) == null) {
    header("Location: http://" . $_SERVER["SERVER_NAME"] . "/w1-admin");
    exit();
} else if ($_SESSION["utilisateur"]->getTypeCompte()->getId() != Constants::COMPTE_ADMIN) {
    header("Location: http://" . $_SERVER["SERVER_NAME"]);
    exit();
}

$formationManager = new FormationManager();

$listeFormation = $formationManager->getAll();

$formationActif = false;
$formationLocked = false;
$formationWriting = false;
$formationExpired = false;

foreach ($listeFormation as $formation) {

    if (!$formation->isBloquer() && $formation->isRedactionFinished() && Functions::convertDateFrToEn($formation->getDateDebut()) > date("Y-m-d")) {
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

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>liste des formations | Gehant</title>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/css.php"); ?>
    <link rel="stylesheet" href="/inc/css/w1-admin/liste-formation.css"
          type="text/css"/>
</head>

<body>


<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

<div class="page-container">
    <div class="container-fluid">

        <!-- Button modal creer une formation -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#staticBackdropCreateFormation">Créer une formation
        </button>


        <!-- Listes des formations en cours d'eciture -->
        <div class="panel panel-primary">
            <div class="panel-header">
                <h3>Les formations en cours d'ecriture</h3>
            </div>
            <div class="panel-body">
                <?php if (!$formationWriting) { ?>
                    <h3 class="text-center">Aucune formation en cours de redaction.</h3>
                <?php } else { ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Domaine</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountWriting = 0;
                        foreach ($listeFormation as $formation) {
                            if (!$formation->isRedactionFinished()) {
                                $indexCountWriting++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountWriting ?></th>
                                    <td><?= $formation->getTitre() ?></td>
                                    <td><?= ucfirst(strtolower($formation->getDomaine()->getTitre())) ?></td>
                                    <td class="row-email"><?= $formation->getAuteur()->getEmail() ?></td>
                                    <td><a
                                                href="/w1-admin/apercu/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"
                                                class="btn btn-success" title="Aperçu de la formation"> <i
                                                    class="bi bi-eye-fill"></i></a> <a
                                                href="/w1-admin/creation/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>/prerequis"
                                                class="btn btn-primary" title="Modifier la formation"> <i
                                                    class="bi bi-pencil-fill"></i></a>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail"
                                                type="button" class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur">
                                            <i class="bi bi-envelope"></i>
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

        <!-- Listes des formations fonctionnelles -->
        <div class="panel panel-success">
            <div class="panel-header">
                <h3>Les formations en ligne</h3>
            </div>
            <div class="panel-body">
                <?php if (!$formationActif) { ?>
                    <h3 class="text-center">Aucune formation active.</h3>
                <?php } else { ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Domaine</th>
                            <th scope="col" style="display: none;">Domaine url</th>
                            <th scope="col">Nbre d'inscrits</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountOnline = 0;
                        foreach ($listeFormation as $formation) {
                            if (!$formation->isBloquer() && $formation->isRedactionFinished() && Functions::convertDateFrToEn($formation->getDateDebut()) >= date("Y-m-d")) {
                                $indexCountOnline++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountOnline ?></th>
                                    <td class="formation-title"><?= $formation->getTitre() ?></td>
                                    <td class="row-email"><?= $formation->getAuteur()->getEmail() ?></td>
                                    <td class="row-domaine"><?= ucfirst(strtolower($formation->getDomaine()->getTitre())) ?></td>
                                    <td style="display: none;"
                                        class="row-domaine-url"><?= $formation->getDomaine()->getTitreUrl() ?></td>
                                    <td><?= $formation->getNombreAchat() ?></td>
                                    <td><?= $formation->getDateDebut() ?></td>
                                    <td><?= $formation->getDateFin() ?></td>
                                    <td><a
                                                href="/w1-admin/apercu/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"
                                                class="btn btn-success" title="Aperçu de la formation"> <i
                                                    class="bi bi-eye-fill"></i></a> <a
                                                href="/w1-admin/liste-acheteur/<?= $formation->getId() ?>"
                                                class="btn btn-success" title="Liste des acheteurs"> <i
                                                    class="bi bi-person-fill"></i></a> <a
                                                href="/w1-admin/creation/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>/prerequis"
                                                class="btn btn-primary" title="Modifier la formation"> <i
                                                    class="bi bi-pencil-fill"></i></a>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail"
                                                type="button" class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                        <button data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropBloquerFormation"
                                                value="<?= $formation->getId() ?>" type="button"
                                                class="btn btn-danger btn-bloquer-formation"
                                                title="Bloquer la formation">
                                            <i class="bi bi-lock-fill"></i>
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
                            <th scope="col">Domaine</th>
                            <th scope="col">Nbre d'inscrits</th>
                            <th scope="col">Debut</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountExpired = 0;
                        foreach ($listeFormation as $formation) {
                            if (Functions::convertDateFrToEn($formation->getDateDebut()) < date("Y-m-d")) {
                                $indexCountExpired++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountExpired ?></th>
                                    <td class="formation-title"><?= $formation->getTitre() ?></td>
                                    <td class="row-email"><?= $formation->getAuteur()->getEmail() ?></td>
                                    <td class="row-domaine"><?= ucfirst(strtolower($formation->getDomaine()->getTitre())) ?></td>
                                    <td><?= $formation->getNombreAchat() ?></td>
                                    <td><?= $formation->getDateDebut() ?></td>
                                    <td><?= $formation->getDateFin() ?></td>
                                    <td><a
                                                href="/w1-admin/apercu/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"
                                                class="btn btn-success" title="Aperçu de la formation"> <i
                                                    class="bi bi-eye-fill"></i></a> <a
                                                href="/w1-admin/liste-acheteur/<?= $formation->getId() ?>"
                                                class="btn btn-primary"
                                                title="Voir les achéteurs de la formation"> <i
                                                    class="bi bi-person-fill"></i></a> <a
                                                href="/w1-admin/creation/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>/prerequis"
                                                class="btn btn-primary" title="Modifier la formation"> <i
                                                    class="bi bi-pencil-fill"></i></a>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail"
                                                type="button" class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur">
                                            <i class="bi bi-envelope"></i>
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
            <div class="panel-body">
                <?php if (!$formationLocked) { ?>
                    <h3 class="text-center">Aucune formation bloquée.</h3>
                <?php } else { ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Domaine</th>
                            <th scope="col" style="display: none;">Domaine url</th>
                            <th scope="col">Nbre d'achat</th>
                            <th scope="col">Motif</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $indexCountLocked = 0;
                        foreach ($listeFormation as $formation) {
                            if ($formation->isBloquer()) {
                                $indexCountLocked++;
                                ?>
                                <tr>
                                    <th scope="row"><?= $indexCountLocked ?></th>
                                    <td class="formation-title"><?= $formation->getTitre() ?></td>
                                    <td class="row-email"><?= $formation->getAuteur()->getEmail() ?></td>
                                    <td class="row-domaine"><?= ucfirst(strtolower($formation->getDomaine()->getTitre())) ?></td>
                                    <td class="row-domaine-url"
                                        style="display: none;"><?= $formation->getDomaine()->getTitreUrl() ?></td>
                                    <td><?= $formation->getNombreAchat() ?></td>
                                    <td><?= $formation->getMotifBlocage() ?></td>
                                    <td><?= $formation->getDateBlocage() ?></td>
                                    <td><a
                                                href="/w1-admin/apercu/formation/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>"
                                                class="btn btn-success" title="Aperçu de la formation"> <i
                                                    class="bi bi-eye-fill"></i></a>
                                        <button data-bs-toggle="modal" data-bs-target="#modalSendMail"
                                                type="button" class="btn btn-primary btn-send-mail"
                                                title="Envoyer un mail à l'auteur">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                        <button data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropDebloquerFormation"
                                                value="<?= $formation->getId() ?>" type="button"
                                                class="btn btn-success btn-debloquer-formation"
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
    require_once("modal/modal-create-formation.php");
    require_once("modal/modal-bloquer-formation.php");
    require_once("modal/modal-debloquer-formation.php");
    require_once("../admins/modal/modal-send-mail.php");
    ?>

</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/js.php"); ?>
<script src="/inc/js/w1-admin/liste-formation.js"
        type="text/javascript"></script>
</body>

</html>