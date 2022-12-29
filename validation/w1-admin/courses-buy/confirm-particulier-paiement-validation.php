<?php


require_once($_SERVER["DOCUMENT_ROOT"]."/src/Facture.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FactureManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/pdf/html2pdf/src/Html2Pdf.php");

use manager\FactureManager;
use src\Facture;
use manager\AchatManager;
use utils\Functions;
use utils\Constants;
use manager\FormationManager;
use Spipu\Html2Pdf\Html2Pdf;

session_start();

if(($_SESSION["utilisateur"]) == null){
    echo json_encode(array(
        "type" => "session"));
    exit();
}

//Recupération des champs
$achatId = Functions::getValueChamp($_POST["id"]);
$souscripteurEmail = Functions::getValueChamp($_POST["email"]);
$souscripteurName = Functions::getValueChamp($_POST["name"]);
$formationPrix = substr(Functions::getValueChamp($_POST["prix"]), 1);
$formationTitre = Functions::getValueChamp($_POST["titre"]);
$formationDate = Functions::getValueChamp($_POST["date"]);
$formationLieu = Functions::getValueChamp($_POST["lieu"]);
$domaineUrl = Functions::getValueChamp($_POST["domaineUrl"]);
$formationUrl = Functions::getValueChamp($_POST["formationUrl"]);

if($achatId == null || intval($achatId) == 0){
    echo json_encode(array(
        "msg" => "Une erreur est survenue, veuillez réessayer ultérieurement."));
    exit();
}

// Generer la facture
$dateTime = new DateTime();
$dateCreation = $dateTime -> format("Y-m-d");
$designation = strtoupper(uniqid("gehant", true));

$factureManager = new FactureManager();
$facture = new Facture();

$facture -> setDesignation($designation);
$facture -> setDateCreation($dateCreation);
$facture -> setPdf(Constants::PDF_FACTURE_FOLDER . $designation . ".pdf");

$achatManager = new AchatManager();

//Genereration du format PDF de la facture
$html = "<div style=\"padding: 10px;\">
                <img src=\"" . $_SERVER["DOCUMENT_ROOT"] ."/inc/images/logo.png\" alt='Logo GEHANT' width='100' height='50' style='position:absolute; right:50px;margin-top:15px;' />
                <p style=\"text-align: center;font-size: 40px;font-weight: bold;\">Reçu</p>
                <div>
                    <div style='margin-left: 60px;'>
                        <div>
                            <h1>GEHANT, Inc</h1>
                                <div style=\"font-size:13px\">Boulevard Valery Giscard d'Estaing (Face ORCA DECO) <br />
                                    Marcory, Immeuble Kalimba, 3eme etage
                                </div>
                                <p><a href=\"http://gehant.net\" style=\"text-decoration: none;color: #f15a24\">GEHANT</a></p>
                                <p style=\"margin-top: 10px;font-size:13px\">Vendu à: <b>" .$souscripteurName."</b></p>
                          </div>
                          <div>
                            <div style=\"font-size:13px;margin-top: 10px;\"><b>Date: </b>" . Functions::convertDateEnToFr($dateCreation) . "</div>
                            <p style=\"font-size:13px\"><b>N° de facture :</b> " . $designation ."</p>
                          </div>
                        </div>
                        <div style='margin-left: 60px;margin-top: -30px;'>
                         <table style=\"margin-top: 60px;width:100%;\">
                            <thead style='padding: 15px 5px;'>
                              <tr style=\"text-align: center;background-color: #f07b16;color: white;\">
                                <th style='padding: 10px 0;font-size:12px;width: 10%;'>N°</th>
                                <th style='padding: 10px 0;font-size:12px;width: 60%;'>Titre de la formation</th>
                                <th style='padding: 10px 0;font-size:12px;width: 30%;'>Prix</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr style=\"text-align: center; background-color: #f0f0f0;\">
                                 <th style=\"padding: 10px 20px;font-size:10px;width: 10%;\">1</th>
                                 <td style='padding:10px 20px;font-size:10px;width: 60%;'>" .$formationTitre. "</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 30%;'>$" . $formationPrix . "</td>
                                 </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div style='font-size: 20px;margin: 25px 80px 0 0;text-align:right;'>Total: <b>$" 
                                     . $formationPrix. " </b></div>
                </div>
                <div style=\"position:absolute;bottom:0;font-size:10px;\">Facture générée par <b>GEHANT</b> le " 
                 . $dateTime -> format("d/m/Y H:i:s") . "</div>";
                            
                 $facture -> setPrix(intval($formationPrix));
$factureId = $factureManager -> addFacture($facture);
$facture -> setId($factureId);

if($achatManager -> updateConfirmParticulierPaid($factureId, intval($achatId))){
    
    $formationManager = new FormationManager();
    
    //incrementer le nombre d'achats de la formation
    $formationManager -> updateNombreAchat($formationTitre);
    
    try{
        $pdf = new Html2Pdf();
        $pdf -> writeHTML($html);
        $pdf -> output($_SERVER["DOCUMENT_ROOT"] . Constants::PDF_FACTURE_FOLDER . $designation . ".pdf", "F");

        //Création d'un mail
        $objet = "Confirmation de l'achat de la formation ".$formationTitre;
        $message = "<h3 style='text-align:center'>Bonjour " . $souscripteurName. "</h3>
                            <p style='text-align:justify;'>Votre inscription à la formation « <b>".$formationTitre."</b> » qui se tiendra du "
                                .$formationDate." a été prise en compte. <br /></p>
                    <p style='margin-bottom:30px;'>L'equipe GEHANT vous contactera sous peu pour la tenue de la formation.</p>
                    <p style='text-align:center;margin-bottom:30px;'>
                                <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/formations/" . $domaineUrl."/".$formationUrl. "\" style=\"text-decoration:none;padding:15px;color:#fff;background:#f07b16\">
                                   Voir la formation
                                </a>
                            </p>
                     <p>A bientôt,<br />L'équipe GEHANT</p>";
        
       $altMessage = "Bonjour " . $souscripteurName. "\n\nVotre inscription à la formation ".$formationTitre." qui se tiendra du "
                .$formationDate." a été prise en compte.\n\n
                    L'equipe GEHANT vous contactera sous peu pour la tenue de la formation.\n\nA bientôt,\nL'équipe GEHANT";
        
                try {
                    
                    //envoyer le mail
                    if(Functions::sendMail($souscripteurEmail, $objet, $message, $altMessage)){  
                        echo json_encode(array(
                            "type" => "success"
                        ));
                        
                    }
                    
                } catch (Exception $e) {
                    echo json_encode(array(
                        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
                    
                    exit();
                }
       
        
    } catch(Exception $e){
        
        echo json_encode(array(
            "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
        exit();
    }
    
} else{
    
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
    exit();
}