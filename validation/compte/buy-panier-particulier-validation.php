<?php

namespace validation\compte;

require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/FactureManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Facture.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/pdf/html2pdf/src/Html2Pdf.php");

use Exception;
use manager\AchatManager;
use manager\FactureManager;
use Spipu\Html2Pdf\Html2Pdf;
use src\Facture;
use utils\Functions;
use utils\Constants;
use DateTime;

session_start();
if (($_SESSION["utilisateur"]) == null) {
    echo json_encode(array("type" => "session"));
    exit();
}

$utilisateur = $_SESSION["utilisateur"];
$achatManager = new AchatManager();
$factureManager = new FactureManager();

//Recuperation de la liste des formations en base de données
$listeFormationPanier = $achatManager->getUserListeFormationNotPaid($utilisateur->getId());

//Construction de l'objet et du message de l'email en fonction du nombre de formations dans le panier
$userMailObjet = null;
$userMailMessage = null;

if (sizeof($listeFormationPanier) > 1) {
    $userMailObjet .= "Votre inscription aux formations";
    $userMailMessage .= "<h3 style='text-align:center'>Bonjour " . $utilisateur->getPrenoms() . "</h3>
                            <p style='text-align:justify;'>Votre inscription aux formations suivantes : <p>";
}

$designation = strtoupper(uniqid("GHT-", true));
$prixTotal = 0;
$dateGenerationProforma = new DateTime();
$dateGenerationProforma = $dateGenerationProforma->format("d/m/Y");
//Genereration du format PDF de la facture proforma
$html = " <page>
             <page_header> 
                     <img src=\"" . $_SERVER["DOCUMENT_ROOT"] . "/inc/images/logo.png\" alt='Logo GEHANT' width='150' />
            </page_header> 
            <div style='margin-top: 80px'>
                <p style=\"font-size: 40px;font-weight: bold;text-align: center;\">FACTURE PROFORMA</p>
                <div>
                   <p style='margin-left: 30px;'> <b>Client</b> : " . $utilisateur->getPrenoms() . " " . $utilisateur->getNom() . "</p>
                    <p style='margin-left: 30px;'><b>Reférence</b> : " . $designation . "</p>
                     <p style='margin-left: 30px;'><b>Date</b> : " . $dateGenerationProforma . "</p>
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
    $prixTotal += $oneAchat->getFormation()->getPrix();
    $html .= "<tr style=\"text-align: center; background-color: #f0f0f0;\">
                                 <th style=\"padding: 10px 20px;font-size:10px;width: 10%;\">" . ($key + 1) . "</th>
                                 <td style='padding:10px 20px;font-size:10px;width: 50%;'>" . $oneAchat->getFormation()->getTitre() . "</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 10%;'>$" . $oneAchat->getFormation()->getPrix() . "</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 10%;'>01</td>
                                 <td style='padding:10px 20px;font-size:10px;width: 10%;'>$" . $oneAchat->getFormation()->getPrix() . "</td>
                           </tr>";

    if (sizeof($listeFormationPanier) == 1) {
        //Creation du mail pour une seule formation dans le panier
        $userMailObjet .= "Votre inscription à la formation " . strtolower($oneAchat->getFormation()->getTitre());
        $userMailMessage .= "<h3 style='text-align:center'>Bonjour " . $utilisateur->getPrenoms() . "</h3>
                            <p style='text-align:justify;'>Votre inscription à la formation « <b>" . $oneAchat->getFormation()->getTitre() . "</b> » qui se tiendra du " . Functions::formatFormationDate(Functions::convertDateEnToFr($oneAchat->getFormation()->getDateDebut()), Functions::convertDateEnToFr($oneAchat->getFormation()->getDateFin())) . " a été prise en compte. <br /></p>
                    <p style='margin-bottom:30px;'>L'equipe GEHANT vous contactera sous peu pour la tenue de la formation.</p>
                    <p style='text-align:center;margin-bottom:30px;'>
                                <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/formations/" . $oneAchat->getFormation()->getDomaine()->getTitreUrl() . "/" . $oneAchat->getFormation()->getTitreUrl() . "\" style=\"text-decoration:none;padding:15px;color:#fff;background:#f07b16\">
                                   Voir la formation
                                </a>
                            </p>
                     <p>A bientôt,<br />L'équipe GEHANT</p>";
    } else {

        //Creation du mail pour plusieurs formations dans le panier
        $userMailObjet .= " " . strtolower($oneAchat->getFormation()->getTitre()) . ",";
        $userMailMessage .= "« <b>" . $oneAchat->getFormation()->getTitre() . "</b> » qui se tiendra du " . Functions::formatFormationDate(Functions::convertDateEnToFr($oneAchat->getFormation()->getDateDebut()), Functions::convertDateEnToFr($oneAchat->getFormation()->getDateFin())) . " <br>";
    }

}

//Fin de la création du mail pour plusieurs formations dans le panier
if (sizeof($listeFormationPanier) > 1) {
    $userMailObjet = substr($userMailObjet, 0, -1);
    $userMailMessage .= "</p> ont été prises en compte</p> 
                    <p style='margin-bottom:30px;'>L'equipe GEHANT vous contactera sous peu pour la tenue de la formation.</p>
                    <p style='text-align:center;margin-bottom:30px;'>
                                <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/espace-client \" style=\"text-decoration:none;padding:15px;color:#fff;background:#f07b16\">
                                   Voir les formations
                                </a>
                            </p>
                     <p>A bientôt,<br />L'équipe GEHANT</p>";
}

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
                    <div style='margin-left: 30px;margin-top: 10px;'>
                        <p style='color: #f07b16'>REFERENCES BANCAIRES</p>
                        <p style='margin: 0;padding: 2px 0;'><b>BENEFICAIRE : </b>GEHANT</p>
                        <p style='margin: 0;padding: 2px 0;'><b>NUMERO DE COMPTE : </b>01204 000000043854 21</p>
                        <p style='margin: 0;padding: 2px 0;'><b>BANQUE : </b>GT BANK</p>
                        <p style='margin: 0;padding: 2px 0;'><b>AGENCE : </b>COCODY ANGRE</p>
                        <p style='margin: 0;padding: 2px 0;'><b>BBAN : </b>CI163 01204 000000043854 21</p>
                        <p style='margin: 0;padding: 2px 0;'><b>IBAN : </b>CI93 CI16 3012 0400 0000 0438 5421</p>
                        <p style='margin: 0;padding: 2px 0;'><b>CODE SWIFT : </b>GTBICIAB</p>
                        <p style='margin: 0;padding: 2px 0;'><b>CODE BIC : </b>GTBICIAB</p>
                    </div>
                </div>
                    <page_footer> 
                     <div style='margin-left: 70%;margin-bottom: 50px;'>
                        <p style='font-size: 20px'><b>LA DIRECTION</b></p>
                        <img src='" . $_SERVER["DOCUMENT_ROOT"] . "/inc/images/cachet.png' alt='Cachet' style='width: 50%;margin-left: 15px;'>
                    </div>
                       <div style='color: #f07b16;border: 1px solid #c0bfbf; border-top: 2px solid #f07b16;padding: 0 5px 10px;margin: 0 auto;'>
                            <p style='text-align: center;margin: 0;padding: 0;'>Siège : Abidjan, Marcory Zone 4, Boulevard Valéry Giscard d’Estaing, face ORCA DECO. Immeuble kalimba, 3ème étage, 1ere porte.</p>
                            <p style='text-align: center;margin: 0;padding: 0;'>26 BP 82 Abidjan 2. Tel +225 2124348 Cel : +225 0708089077 / 0171102766 -RCCM : CI-ABJ-201-A-1782-C.C N° : 13878 U</p>
                            <p style='text-align: center;margin: 0;padding: 0;'>Compte Bancaire GTBANK N° : CI1301204 00000004384 21 – Site internet : www.gehant.net</p>
                        </div>
                    </page_footer> 
                </page>";

$pdf = new Html2Pdf("P", "A4", "fr");
$pdf->
$pdf->writeHTML($html);

//envoie du mail à l'utilisateur
try {
    Functions::sendMail($utilisateur->getEmail(), $userMailObjet, $userMailMessage, null);
    $pdf->output($_SERVER["DOCUMENT_ROOT"] . Constants::PDF_FACTURE_PROFORMA_FOLDER . $designation . ".pdf", "F");
} catch(Exception $e) {
    echo json_encode(array("msg" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."));
}

//Creation de la facture
$facture = new Facture();
$facture->setDesignation($designation);
$facture->setPrix($prixTotal);
$facture->setProforma(Constants::PDF_FACTURE_PROFORMA_FOLDER . $designation . ".pdf");
$facture->setDateProforma(Functions::convertDateFrToEn($dateGenerationProforma));
$factureId = $factureManager->addProfama($facture);

//Mise à jour de l'id de l'achat
$achatManager->updatePaid($utilisateur->getId(), Functions::convertDateFrToEn($dateGenerationProforma), false, $factureId);

//Envoie d'un mail à Gehant pour spécifier de la disponibilité d'une commande de formation
$objet = "Demande de formation";
$message = "<h3 style='text-align:center'>Bonjour,</h3>
                        <p style='text-align:justify;'>Une demande de formation vient d’être créée. Pour la consulter, rendez-vous à l'adresse suivante</p>
                        <p style='text-align:center;margin-bottom:30px;'>
                            <a href='http://" . $_SERVER["HTTP_HOST"] . "/w1-admin' style='text-decoration:none; padding:15px; color:#fff; background:#f07b16;'>Consulter la demande</a>
                       </p>
                       <p>Si vous ne parvenez pas à vous connecter en cliquant sur le bouton, copiez puis collez le lien suivant dans votre navigateur.</p>
                       <p>http://" . $_SERVER["HTTP_HOST"] . "/w1-admin</p>
                       <p>A bientôt,<br />L'équipe GEHANT</p>";
$altMessage = "Bonjour,\n\nVotre mot de passe a été redéfinir avec succès.\n
                        Une demande de formation vient d’être créée. Pour la consulter, rendez-vous à l'adresse suivante.\n http://" . $_SERVER["HTTP_HOST"] . "/w1-admin .\nA bientôt,L'équipe GEHANT";

try {
    Functions::sendMail(Constants::SERVICE_CLIENT_EMAIL, $objet, $message, $altMessage);
    echo json_encode(array("type" => "success"));

} catch(Exception $e) {
    echo json_encode(array("mail" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."));
}

