<?php

namespace validation\formation;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

use utils\Functions;
use manager\FormationManager;

$search = Functions::getValueChamp($_POST["search"]);

$formationManager = new FormationManager();
$listeFormation = $formationManager->searchFormation($search);


if(!empty($listeFormation)){
   echo json_encode($listeFormation);
}
