<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Facture.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FactureManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/pdf/html2pdf/src/Html2Pdf.php");

use manager\FactureManager;
use src\Facture;
use manager\AchatManager;
use utils\Functions;
use utils\Constants;
use Spipu\Html2Pdf\Html2Pdf;

session_start();

if (($_SESSION["utilisateur"]) == null) {
    echo json_encode(array("type" => "session"));
    exit();
}

//Recupération des champs
$factureId = Functions::getValueChamp($_POST["id"]);
$designation = Functions::getValueChamp($_POST["designation"]);
$souscripteurEmail = Functions::getValueChamp($_POST["email"]);
$souscripteurName = Functions::getValueChamp($_POST["name"]);
$prixTotal = substr(Functions::getValueChamp($_POST["prix"]), 1);

if ($factureId == null || intval($factureId) == 0) {
    echo json_encode(array("msg" => "Une erreur est survenue, veuillez réessayer ultérieurement."));
    exit();
}

// Generer la facture
$dateTime = new DateTime();
$dateCreation = $dateTime->format("Y-m-d");

$factureManager = new FactureManager();
$achatManager = new AchatManager();
$facture = new Facture();

//Recuperation de la liste des formations en base de données
$listeFormationPanier = $achatManager->getListeFormationNotConfirmPaid($_SESSION["utilisateur"]->getId(), false);

//Construction de l'objet et du message de l'email en fonction du nombre de formations dans le panier
$userMailObjet = "Confirmation du paiement de la facture N° " . $designation;
$userMailMessage = null;

if (sizeof($listeFormationPanier) > 1) {
    $userMailMessage .= "<h3 style='text-align:center'>Bonjour " . $souscripteurName . "</h3>
                            <p style='text-align:justify;'>Le paiement de la facture N° " . $designation . " rattachée aux formations suivantes : <p>";
} else if (sizeof($listeFormationPanier) == 1) {
    $userMailMessage .= "<h3 style='text-align:center'>Bonjour " . $souscripteurName . "</h3>
                            <p style='text-align:justify;'>Le paiement de la facture N° " . $designation . " rattachée à la formation suivante : <p>";
}

$dateFacture = new DateTime();
$dateFacture = $dateFacture->format("d/m/Y");

//Genereration du format PDF de la facture proforma
$html = " <page>
             <page_header> 
                     <img src=\"" . $_SERVER["DOCUMENT_ROOT"] . "/inc/images/logo.png\" alt='Logo GEHANT' width='150' />
                     <img src=\"" . $_SERVER["DOCUMENT_ROOT"] . "/inc/images/paye.jpg\" alt='Logo Paye' style='width: 15%; height: auto; position:absolute;right: 50px' />
            </page_header> 
            <div style='margin-top: 80px'>
                <p style=\"font-size: 40px;font-weight: bold;text-align: center;\">FACTURE</p>
                <div>
                   <p style='margin-left: 30px;'> <b>Client</b> : " . $souscripteurName . "</p>
                    <p style='margin-left: 30px;'><b>Reférence</b> : " . $designation . "</p>
                     <p style='margin-left: 30px;'><b>Date</b> : " . $dateFacture . "</p>
                        <div style='margin-left: 30px;margin-top: 20px;'>
                         <table style=\"margin-top: 20px;width:100%;\">
                            <thead style='padding: 15px 5px;'>
                              <tr style=\"text-align: center;background-color: #f07b16;color: white;\">
                                <th style='padding: 10px 0;font-size:12px;width: 10;'>N°</th>
                                <th style='padding: 10px 0;font-size:12px;width: 50%;'>Module</th>
                                <th style='padding: 10px 0;font-size:12px;width: 10%;'>Prix Un.</th>
                                 <th style='padding: 10px 0;font-size:12px;width: 10%;'>Nbre Part.</th>
                                 <th style='padding: 10px 0;font-size:12px;width: 10%;'>Prix Total</th>
                              </tr>
                            </thead>
                            <tbody>";

foreach ($listeFormationPanier as $key => $oneAchat) {

    //Ajout des formations à la facture
    $html .= "<tr style=\"text-align: center; background-color: #f0f0f0;\">
                                 <th style=\"padding: 10px 20px;font-size:10px;width: 10%;\">" . ($key + 1) . "</th>
                                 <td style='padding:10px 20px;font-size:10px;width: 50%;'>" . $oneAchat->getFormation()->getTitre() . "</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 10%;'>$" . $oneAchat->getFormation()->getPrix() . "</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 10%;'>01</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 10%;'>$" . $oneAchat->getFormation()->getPrix() . "</td>
                           </tr>";

    $userMailMessage .= "« <b>" . $oneAchat->getFormation()->getTitre() . "</b> » qui se tiendra du " . Functions::formatFormationDate($oneAchat->getFormation()->getDateDebut(), $oneAchat->getFormation()->getDateFin()) . " <br>";
}

$userMailMessage .= "</p> a été pris en compte</p> 
                    <p style='margin-bottom:30px;'>L'equipe GEHANT vous remercie.</p>
                    <p style='text-align:center;margin-bottom:30px;'>
                                <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/espace-client \" style=\"text-decoration:none;padding:15px;color:#fff;background:#f07b16\">
                                   Voir la facture
                                </a>
                            </p>
                     <p>A bientôt,<br />L'équipe GEHANT</p>";

//Ajout des references sur la facture proforma
$html .= " <tr style=' background-color: #f0f0f0;'>
    <td colspan='2' style='font-size: 25px;text-align: right;padding: 10px;'><b>Total </b></td>
    <td colspan='3' style='font-size: 25px;text-align: center;padding: 10px 0;'>$" . $prixTotal . "</td>
  </tr>
  </tbody>
      </table>
                    </div>
                </div>
                    <div style='margin-left: 30px;margin-top: 10px;'>Arrêtée la présente facture à la somme de : <br><b>" . Functions::convertirNombreEnLettres($prixTotal) . " dollars</b></div>
                     <div style='position:absolute;right: 60px;bottom: 50px;'>
                        <p style='font-size: 20px'><b>LA DIRECTION</b></p>
                        <img src='" . $_SERVER["DOCUMENT_ROOT"] . "/inc/images/cachet.png' alt='Cachet' style='width: 50%;margin-left: 15px;'>
                    </div>
                </div>
                    <page_footer> 
                       <div style='color: #f07b16;border: 1px solid #c0bfbf; border-top: 2px solid #f07b16;padding: 0 5px 10px;margin: 0 auto;'>
                            <p style='text-align: center;margin: 0;padding: 0;'>Siège : Abidjan, Marcory Zone 4, Boulevard Valéry Giscard d’Estaing, face ORCA DECO. Immeuble kalimba, 3ème étage, 1ere porte.</p>
                            <p style='text-align: center;margin: 0;padding: 0;'>26 BP 82 Abidjan 2. Tel +225 2124348 Cel : +225 0708089077 / 0171102766 -RCCM : CI-ABJ-201-A-1782-C.C N° : 13878 U</p>
                            <p style='text-align: center;margin: 0;padding: 0;'>Compte Bancaire GTBANK N° : CI1301204 00000004384 21 – Site internet : www.gehant.net</p>
                        </div>
                    </page_footer> 
                </page>";

$pdf = new Html2Pdf("P", "A4", "fr");
$pdf->writeHTML($html);

//envoie du mail à l'utilisateur
try {
    Functions::sendMail($souscripteurEmail, $userMailObjet, $userMailMessage, null);
    $pdf->output($_SERVER["DOCUMENT_ROOT"] . Constants::PDF_FACTURE_FOLDER . $designation . ".pdf", "F");
} catch(Exception $e) {
    echo json_encode(array("msg" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."));
}

$facture->setId(intval($factureId));
$facture->setPdf(Constants::PDF_FACTURE_FOLDER . $designation . ".pdf");
$facture->setDateCreation(Functions::convertDateFrToEn($dateFacture));

if($achatManager->updateConfirmParticulierPaid(intval($factureId)) && $factureManager->updateFacture($facture)){
    echo json_encode(array("type" => "success"));
}else{
    echo json_encode(array("msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
    exit();
}