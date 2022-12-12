<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");

use manager\FormationManager;
use utils\Functions;

session_start();

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$formationManager = new FormationManager();
$formation = $formationManager->getOneFormationInfo(Functions::getValueChamp($_GET["domaine"]), Functions::getValueChamp($_GET["formation"]), true);

if ($formation == null) {
    http_response_code(404);
    exit();
}

$_SESSION["formation"] = $formation;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Les cibles de la formation | Gehant administration</title>
    <meta charset="utf-8"/>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/css.php"); ?>
    <link rel="stylesheet" href="/inc/css/w1-admin/edit-formation.css" type="text/css"/>
    <link rel="stylesheet" href="/inc/css/w1-admin/edit/cibles.css" type="text/css"/>
</head>
<body>

<!-- Header -->
<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

<div class="container-fluid">
    <div class="container">
        <div class="row">

            <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/w1-admin/courses/menu-edit.php"); ?>

            <div class="col-md-9 formation-infos-container">
                <div class="section-header">
                    <h1>A qui s'adresse cette formation</h1>
                </div>
                <div class="section-content">
                    <p>Les prérequis suivants seront affichés sur le page d'accueil du cours. Ces prérequis doivent
                        respecter les spécifications suivanes:</p>

                    <ul>
                        <li>Etre explicite</li>
                        <li>La longueur du texte doit être supérieure à 3 caractères</li>
                    </ul>

                    <div>
                        <div>
                            <b>À qui ce cours s'adresse-t-il ?</b>
                            <p>Rédigez une courte description des participants cibles que le contenu de votre cours peut
                                intéresser. Cela vous aidera à attirer les bons participants.</p>
                            <form id="cibles-form">
                                <?php if ($formation->getPrerequis() == null) { ?>
                                    <input type="text" id="cibles-1" class="form-control"
                                           placeholder="Exemple : Developpeur Web">
                                <?php } else {
                                    $listeCible = explode(";", $formation->getCibles());
                                    foreach ($listeCible as $key => $cible) {
                                        ?>
                                        <input type="text" value="<?= $cible ?>" id="cibles-<?= ($key + 1) ?>"
                                               class="form-control" placeholder="Exemple : Developpeur Web">
                                    <?php } ?>
                                <?php } ?>
                            </form>
                            <a href="" id="add-cibles-input"><i class="bi bi-plus"></i>Ajouter une cible</a>

                            <div>
                                <button type="button" id="cibles-save">Valider</button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/js.php"); ?>
<script src="/inc/js/w1-admin/edit-formation.js" type="text/javascript"></script>
<script src="/inc/js/w1-admin/edit/cibles.js" type="text/javascript"></script>
</body>
</html>