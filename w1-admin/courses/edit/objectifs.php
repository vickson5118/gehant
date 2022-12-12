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
    <title>Les objectifs de la formation | Gehant administration</title>
    <meta charset="utf-8"/>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/css.php"); ?>
    <link rel="stylesheet" href="/inc/css/w1-admin/edit-formation.css" type="text/css"/>
    <link rel="stylesheet" href="/inc/css/w1-admin/edit/objectifs.css" type="text/css"/>
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
                    <h1>Objectifs</h1>
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
                            <b>Quels sont les prérequis de ce cours</b>
                            <p>Dressez la liste des compétences, de l'expérience, des outils ou de l'équipement que les
                                participants doivent posséder
                                pour suivre votre cours. S'il n'y a pas de prérequis,profitez-en pour simplifier la
                                tâche des débutants.</p>
                            <form id="objectifs-form">
                                <?php if ($formation->getObjectifs() == null) { ?>
                                    <input type="text" id="objectifs-1" class="form-control"
                                           placeholder="Exemple : Reussir à créer une application mobile multiplateforme">
                                <?php } else {
                                    $listeObjectif = explode(";", $formation->getObjectifs());
                                    foreach ($listeObjectif as $key => $objectif) {
                                        ?>
                                        <input type="text" value="<?= $objectif ?>" id="objectifs-<?= ($key + 1) ?>"
                                               class="form-control"
                                               placeholder="Exemple : Reussir à créer une application mobile multiplateforme">
                                    <?php } ?>
                                <?php } ?>
                            </form>
                            <a href="" id="add-objectifs-input"><i class="bi bi-plus"></i>Ajouter un objectif</a>

                            <div>
                                <button type="button" id="objectifs-save">Valider</button>
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
<script src="/inc/js/w1-admin/edit/objectifs.js" type="text/javascript"></script>
</body>
</html>