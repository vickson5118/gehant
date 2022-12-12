<?php

declare(strict_types = 1);

namespace utils;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/utils/phpmailer/Exception.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/utils/phpmailer/PHPMailer.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/utils/phpmailer/SMTP.php");


use PHPMailer\PHPMailer\PHPMailer;
use DateTime;
use Exception;
use PDO;
use PDOStatement;
use IntlDateFormatter;
use manager\UtilisateurManager;
use manager\PaysManager;
use manager\EntrepriseManager;
use manager\DomaineManager;
use manager\FormationManager;
use src\Utilisateur;

class Functions{

    private function __construct ()
    {}

    /**
     * Verifie une chaine de caractères
     *
     * @param string|null $texte
     * @param string $nomChamp
     * @param int $minLength
     * @param int $maxLength
     * @param bool $required
     * @throws Exception
     */
    public static function validTexte (?string $texte, string $nomChamp,int $minLength, int $maxLength, bool $required): void{
        if (($texte == NULL || empty(trim($texte))) && $required) {
            throw new Exception("Le champ " . $nomChamp . " ne peut être vide.");
        } elseif (trim($texte) != null && iconv_strlen(trim($texte)) < $minLength ) {
            throw new Exception("Le champ " . $nomChamp . " ne peut être inférieur à " .$minLength . " caractères.");
        } elseif (trim($texte) != null && iconv_strlen(trim($texte)) > $maxLength) {
            throw new Exception("Le champ " . $nomChamp . " ne peut excéder " .$maxLength . " caractères.");
        }
    }

    /**
     * Genere une requete
     *
     * @param PDO $bdd
     * @param string $sql
     * @param array $args
     * @return PDOStatement
     */
    public static function bindPrepare (PDO $bdd, string $sql, ...$args): PDOStatement{
        $prepare = $bdd->prepare($sql);
        for ($i = 0; $i < sizeof($args); $i ++) {
            if(is_bool($args[$i])){
                $prepare->bindParam($i + 1, $args[$i], PDO::PARAM_BOOL);
            }else{
                $prepare->bindParam($i + 1, $args[$i]);
            }

        }
        return $prepare;
    }

    /**
     * Generer une chaine de caractères aleatoire
     *
     * @param int $length
     *            la longeur de la chaine
     * @return string
     */
    public static function generatePassword (int $length): string{
        $allChars = "!:crfWSvt?.(-~#{[167./-+$%;,!_&|\@]}=^aqw23zkolsAQWxedgbTGVCFR45EDXZyhnuU89JNBHYjipmMPLOKI";

        $password = "";
        for ($i = 0; $i < $length; $i ++) {
            $allChars = str_shuffle($allChars);
            $password = $allChars[$i] . $password;
        }

        return $password;
    }

    /**
     * Verification de la validation email
     *
     * @param string|null $email
     * @param UtilisateurManager $utilisateurManager
     * @throws Exception
     */
    public static function validEmail (?string $email,UtilisateurManager $utilisateurManager): void{
        if ($email == null || iconv_strlen($email) == 0) {
            throw new Exception("Le champ adresse mail ne peut être vide.");
        } elseif (iconv_strlen($email) < 10) {
            throw new Exception("Le champ adresse mail ne peut être inférieur à 10 caractères.");
        } elseif (iconv_strlen($email) > 255) {
            throw new Exception("Le champ adresse mail ne peut excéder 250 caractères.");
        } elseif (! preg_match("#^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\\.[a-z]{2,4}$#",$email)) {
            throw new Exception("Le format de l'adresse mail est incorrect.");
        } elseif ($utilisateurManager->emailExist($email)) {
            throw new Exception("L'adresse mail existe déja.Veuillez choisir une autre adresse.");
        }
    }

    /**
     * Verife l'adresse mail sans se soucier si elle existe en base de donnees
     * ou pas
     *
     * @param string|null $email
     * @throws Exception
     */
    public static function validContactEmail (?string $email): void{
        if ($email == null || iconv_strlen(ltrim($email)) == 0) {
            throw new Exception("Le champ adresse mail ne peut être vide.");
        } elseif (iconv_strlen($email) < 10) {
            throw new Exception( "Le champ adresse mail ne peut être inférieur à 10 caractères.");
        } elseif (iconv_strlen($email) > 255) {
                throw new Exception( "Le champ adresse mail ne peut excéder 250 caractères.");
         } elseif (! preg_match("#^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\\.[a-z]{2,4}$#",$email)) {
            throw new Exception( "Le format de l'adresse mail est incorrect.");
        }
    }


    /**
     * Verification la validation numero
     *
     * @param string|null $telephone
     * @throws Exception
     */
    public static function validTelephone(?string $telephone): void{
        if($telephone == null){
            throw new Exception("Le champ téléphone ne peut être vide.");
        }else if(iconv_strlen($telephone) > 0 && !preg_match("#^\+([0-9][. -]?)+$#",$telephone)){
            throw new Exception("Le format du téléphone est incorrect. Le format autorisé est +225 07 79 79 05 03");
        }
    }

    /**
     * Validation de la date de naissance au format francais
     * @param string|null $naissance
     * @throws Exception
     */
    public static function validDate(?string $naissance): void{
        if($naissance != null && iconv_strlen($naissance) > 0 && !preg_match("#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#",$naissance)){
            throw new Exception("Le format de la date est incorrect.");
        }
    }

    /**
     * Verifier que le pays existe en BDD
     * @param string|null $paysId
     * @param PaysManager $paysManager
     * @throws Exception
     */
    public static function validPays(?string $paysId, PaysManager $paysManager): void{
        if($paysId == null || iconv_strlen($paysId) == 0){
            throw new Exception("Le pays choisi est incorrect.");
        }elseif (!$paysManager->paysExist(intval($paysId))){
            throw new Exception("Le pays choisi est incorrect.");
        }
   }

    /**
     * Verifie si le titre de la formation respecte le nombre de caractères et s'il existe déja
     * @param string|null $titre
     * @param FormationManager $formationManager
     * @throws Exception
     */
   public static function validFormationTitre (?string $titre, FormationManager $formationManager): void{
       if ($titre == null || iconv_strlen(ltrim($titre)) == 0) {
           throw new Exception("Le titre de la formation ne peut être vide.");
       } elseif (iconv_strlen($titre) < 15) {
           throw new Exception( "Le titre de la formation ne peut être inférieur à 15 caractères.");
       } elseif (iconv_strlen($titre) > 40) {
           throw new Exception( "Le titre de la formation ne peut excéder 40 caractères.");
       } elseif ($formationManager->titreExist($titre)) {
           throw new Exception( "Le titre de la formation existe déjà. Veuillez choisir un autre titre.");
       }
   }

    /**
     * Verifie que le nouveau titre de la formation n'existe pas en base de donnees
     * @param string|null $titre
     * @param int|null $formationId
     * @param FormationManager $formationManager
     * @throws Exception
     */
   public static function validFormationTitreExist (?string $titre, ?int $formationId, FormationManager $formationManager): void{
       if ($titre == null || iconv_strlen(ltrim($titre)) == 0) {
           throw new Exception("Le titre de la formation ne peut être vide.");
       } elseif (iconv_strlen($titre) < 15) {
           throw new Exception( "Le titre de la formation ne peut être inférieur à 15 caractères.");
       } elseif (iconv_strlen($titre) > 40) {
           throw new Exception( "Le titre de la formation ne peut excéder 40 caractères.");
       } elseif ($formationManager->titreChangeExist($titre, $formationId)) {
           throw new Exception( "Le titre de la formation existe déjà. Veuillez choisir un autre titre.");
       }
   }

    /**
     * Valider l'id du domaine de formation pour s'assurer qu'il existe
     * @param int|null $domaineId
     * @param DomaineManager $domaineManager
     * @return string|NULL
     * @throws Exception
     */
   public static function validDomaineFormation(?int $domaineId, DomaineManager $domaineManager): ?string {
       $titreUrl = $domaineManager->domaineExist($domaineId);
       if ($titreUrl == null || iconv_strlen($titreUrl) == 0){
           throw new Exception("Le domaine de formation choisi est incorrect.");
       }
       return $titreUrl;
   }

    /**
     * Validation du mot de passe
     * @param string|null $password
     * @throws Exception
     */
    public static function validPassword(?string $password): void{
        if ($password == null || iconv_strlen($password) == 0) {
            throw new Exception("Le champ mot de passe ne peut être vide.");
        } elseif (iconv_strlen($password) < 8) {
            throw new Exception("Le champ mot de passe ne doit pas être inférieur à 8 caractères.");
        } else if (!preg_match("#[a-z]+#",$password)) {
            throw new Exception("Le mot de passe doit conténir au moins un caractère miniscule.");
        } else if (!preg_match("#[A-Z]+#",$password)) {
            throw new Exception("Le mot de passe doit conténir au moins un caractère majuscule.");
        } else if (!preg_match("#[0-9]+#",$password)) {
            throw new Exception("Le mot de passe doit conténir au moins un chiffre.");
        } else if (!preg_match("#[@~&*+°|\#^ù%µ$<>!:;=-]+#",$password)) {
            throw new Exception("Le mot de passe doit conténir au moins un caractère spécial suivant : @~&*+°|#^ù%µ$<>!:;=-");
        }
    }

    /**
     * Recuperer le champ en supprimant les caractères html ainsi que les espaces de debut et fin
     * @param string|null $value
     * @return NULL|string
     */
    public static function getValueChamp(?string $value): ?string{
        if($value == null){
            return null;
        }
        return strip_tags(trim($value));
    }

    /**
     * Valide le nom de l'entrerpise et verifie qu'il n'existe pas deja en BDD
     * @param string|null $entreprise
     * @param EntrepriseManager $entrepriseManager
     * @throws Exception
     */
    public static function validEntreprise(?string $entreprise, EntrepriseManager $entrepriseManager): void{
        if($entreprise == null || iconv_strlen($entreprise) == 0){
            throw new Exception("Le champ nom de l'entreprise ne peut être vide.");
        }elseif(iconv_strlen($entreprise) < 2){
            throw new Exception("Le champ nom de l'entreprise ne peut être inférieur à 2 caractères.");
        }elseif(iconv_strlen($entreprise) > 30){
            throw new Exception("Le champ nom de l'entreprise ne peut excéder 30 caractères.");
        }elseif($entrepriseManager->isExist($entreprise)){
            throw new Exception("L'entreprise existe deja. Veuillez vous connecter avec vos identifinats.");
        }elseif($entrepriseManager->isExist($entreprise)){
            throw new Exception("L'entreprise existe deja. Veuillez vous connecter avec vos identifinats.");
        }
    }

    /**
     * Valide le nouveau nom de l'entrerpise et verifie qu'il n'existe pas deja en BDD
     * @param string|null $entreprise
     * @param int $id
     * @param EntrepriseManager $entrepriseManager
     * @throws Exception
     */
    public static function validEntrepriseNewName(?string $entreprise, int $id, EntrepriseManager $entrepriseManager): void{
        if($entreprise == null || iconv_strlen($entreprise) == 0){
            throw new Exception("Le champ nom de l'entreprise ne peut être vide.");
        }elseif(iconv_strlen($entreprise) < 2){
            throw new Exception("Le champ nom de l'entreprise ne peut être inférieur à 2 caractères.");
        }elseif(iconv_strlen($entreprise) > 30){
            throw new Exception("Le champ nom de l'entreprise ne peut excéder 30 caractères.");
        }elseif($entrepriseManager->newNameisExist($entreprise, $id)){
            throw new Exception("L'entreprise existe deja. Veuillez vous connecter avec vos identifinats.");
        }
    }

    /**
     * Supprimer les metacaractères de la chaine et nous donne une chaine sous forme d'url
     * @param string|null $texte
     * @return string|NULL
     */
    public static function formatUrl(?string $texte): ?string{
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        
        $texteSansAccent = str_replace($search, $replace, $texte);
        $url = str_replace(" ", "-", $texteSansAccent);
        return preg_replace("#-{2,}#", "-", $url);
    }

    /**
     * Verifie que le format du prix est correct
     * @param string|null $prix
     * @throws Exception
     */
    public static function validPrix(?string $prix): void{
       if($prix == null){
            throw new Exception("Le prix de la formation ne peut être vide.");
        }elseif($prix == 0){
            throw new Exception("Le prix de la formation doit être supérieur à 0.");
        }elseif (!preg_match("#[0-9]+#",$prix)){
            throw new Exception("Le prix est incorrect.");
        }
    }
    
    /**
     * Verifier que les images respecte differents critères
     * @param  $image : l'image envoyée dans $_POST
     * @param  $imageSize : La taille de l'image en octet
     * @param  $imageSizeInMo : La taille de l'image en Mo
     * @param  $imageUploadWidth : La largeur de l'image
     * @param  $imageUploadHeight : La hauteur de l'image
     * @throws Exception
     * @return string|NULL
     */
    public static function validImage($image, $imageSize, $imageSizeInMo, $imageUploadWidth, $imageUploadHeight): ?string{
        
        if(empty($image["name"]) && !isset($image)){
            throw new Exception("Le champ de l'image ne peut être vide.");
        }else{
            
            if($image["error"] != 0){
                throw new Exception("Une erreur est survenue, veuillez reessayer ultérieurement.");
            }
            
            $imageInfo = getimagesize($image["tmp_name"]);
            $imageWidth = $imageInfo[0];
            $imageHeight = $imageInfo[1];
            $imageTypeMime = $imageInfo["mime"];
            
            if($image["size"] > $imageSize){
                throw new Exception("La taille de l'image ne doit pas excéder ".$imageSizeInMo." Mo");
            }
            
            $extension = strtolower(pathinfo($image["name"],PATHINFO_EXTENSION));
            $allowExtension = array("jpg","png","jpeg");
            $allowTypeMime = array("image/jpg","image/jpeg","image/png");
            
            if(!in_array($extension, $allowExtension) || !in_array($imageTypeMime, $allowTypeMime)){
                throw new Exception("Le format de l'image n'est pas correct. Les formats autorisés sont: png, jpeg, jpg");
            }
            
            if($imageWidth != $imageUploadWidth || $imageHeight != $imageUploadHeight){
                throw new Exception("La dimension de l'image autorisée est: ".$imageUploadWidth."*".$imageUploadHeight);
            }
            
            return $extension;
        }
    }

    /**
     * Convertir de format de la date Anglaise en format Francais
     * @param string|null $date
     * @return string|NULL
     */
    public static function convertDateEnToFr(?string $date): ?string {
        return ($date == null) ? null : 
        (DateTime::createFromFormat("Y-m-d", $date))->format("d/m/Y");
    }

    /**
     * Convertir le format de la date Francais en format Anglaise
     * @param string|null $date
     * @return string|NULL
     */
    public static function convertDateFrToEn(?string $date): ?string {
        return ($date == null) ? null :
        (DateTime::createFromFormat("d/m/Y", $date))->format("Y-m-d");
    }

    /**
     * Mettre la date au format fr avec le mois abrégé
     * @param string|null $date
     * @return string|NULL
     * @throws Exception
     */
   public static function convertDateFrToFrMonthAbrege(?string $date): ?string {
        $dateFormatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE,
            'Africa/Abidjan',
            IntlDateFormatter::GREGORIAN,
            );
        return $date == null ? null : $dateFormatter->format(new DateTime(DateTime::createFromFormat("d/m/Y", $date)->format("Y-m-d")));
    }

    /**
     * Permettre d'afficher la date de formation correctement en fonction de si cela se deroule dans le même mois ou pas
     * @param string|null $dateDebut
     * @param string|null $dateFin
     * @return string|null
     * @throws Exception
     */
    public static function formatFormationDate(?string $dateDebut, ?string $dateFin): ?string{

        if($dateDebut == null || $dateFin == null){
            return null;
        }

        $dateDebut = DateTime::createFromFormat("d/m/Y", $dateDebut)->format("d/n/Y");
        $dateFin = DateTime::createFromFormat("d/m/Y", $dateFin)->format("d/n/Y");

        $monthTab = [ "Jan", "Fév", "Mar", "Avr", "Mai", "Juin","Juil", "Août", "Sept", "Oct", "Nov", "Déc" ];
        $dateDebutTab = explode("/", $dateDebut);
        $dateFinTab = explode("/", $dateFin);

        if($dateDebutTab[1] == $dateFinTab[1] && $dateDebutTab[2] == $dateFinTab[2]){
            return $dateDebutTab[0]." - ".$dateFinTab[0]." ".$monthTab[(intval($dateDebutTab[1])-1)]." ".$dateDebutTab[2];
        }else if($dateDebutTab[1] == $dateFinTab[1] && $dateDebutTab[2] != $dateFinTab[2]){
            return self::convertDateFrToFrMonthAbrege($dateDebut)." - ".self::convertDateFrToFrMonthAbrege($dateFin);
        }else if($dateDebutTab[1] != $dateFinTab[1] && $dateDebutTab[2] == $dateFinTab[2]){
            return $dateDebutTab[0]." ".$monthTab[(intval($dateDebutTab[1])-1)]." - ".$dateFinTab[0]." ".$monthTab[(intval($dateFinTab[1])-1)]." ".$dateDebutTab[2];
        }else{
            return self::convertDateFrToFrMonthAbrege($dateDebut)." - ".self::convertDateFrToFrMonthAbrege($dateFin);
        }

    }

    /** Calculez le temps ecoulé depuis la date d'inscription
     * @param string $dateInscription
     * @return string|null
     * @throws Exception
     */
    public static function dateInscriptionInterval(string $dateInscription): ?string{
        $dateInscriptionConvert = new DateTime($dateInscription);
        $now = new DateTime("now");
        $interval = $dateInscriptionConvert->diff($now);
       
        if(intval($interval->format("%a")) < 1){
            return $interval->format("Inscrit depuis quelques heures");
        }else if(intval($interval->format("%a")) == 1){
            return $interval->format("Inscrit depuis %a jour");
        }else if(intval($interval->format("%a")) > 1 && intval($interval->format("%Y")) < 1){
            return $interval->format("Inscrit depuis %a jours");
        }else if(intval($interval->format("%Y")) == 1){
            return $interval->format("Inscrit depuis %Y an");
        }else{
            return $interval->format("Inscrit depuis %Y ans");
        }
        
    }

    /**
     * Envoyer un mail
     * @param string $email
     * @param string $objet
     * @param string $message
     * @param string|null $altMessage
     * @return bool
     * @throws Exception
     */
    public static function sendMail(string $email, string $objet,string $message,?string $altMessage): bool{
        
        $mailer = new PHPMailer(true);
        
        try{
            
            // Debutg
            // $mailer -> SMTPDebug = SMTP::DEBUG_SERVER;
            
            // config SMTP
            $mailer -> isSMTP();
            $mailer -> Host = Constants::SMTP_HOST;
            $mailer -> Port = Constants::SMTP_PORT;
            $mailer -> SMTPAuth = true;
            $mailer -> Username = Constants::SMTP_USERNAME;
            $mailer -> Password = Constants::SMTP_PASSWORD;
            $mailer -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            
            // Charset
            $mailer -> CharSet = "utf-8";
            
            // Destinataire et expediteur
            $mailer -> setFrom(Constants::SMTP_USERNAME,"L'équipe Gehant");
            $mailer -> addAddress($email);
            
            // Message
            $mailer -> isHTML();
            $mailer -> Subject = $objet;
            $mailer -> Body = $message;
            
            $mailer -> AltBody = $altMessage;
                         
            return $mailer->send();
             
        } catch(\PHPMailer\PHPMailer\Exception $e){
             throw new Exception("Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement. " .$e->getMessage());
        }
    }
    
    /**
     * Obtenir les initiaux de l'utilisateur
     * @param string $nom
     * @param string $prenoms
     * @return string
     */
    public static function getUtilisateurInitial(string $nom, string $prenoms): string{
        return strtoupper(substr($prenoms, 0, 1).substr($nom, 0, 1));
    }

    /** Rediriger lorsqu'il n'y a pas de session entreprise pour les pages d'entreprise
     * @param Utilisateur|null $utilisateur
     * @return void*
     */
    public static function redirectWhenNotConnexionEntreprise(?Utilisateur $utilisateur) : void{
        if($utilisateur == null){
            header("Location: http://" . $_SERVER["SERVER_NAME"] . "/compte/connexion");
            exit();
        } else if(!empty($utilisateur) &&  $utilisateur -> getTypeCompte() -> getId() != Constants::COMPTE_ENTREPRISE){
            header("Location: http://" . $_SERVER["SERVER_NAME"]);
            exit();
        }
    }

    /**
     * @param Utilisateur|null $utilisateur
     * @return void
     */
    public static function redirectWhenNotConnexionAdmin(?Utilisateur $utilisateur) : void{
        if ($utilisateur == null) {
            header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin" );
            exit ();
        }else if($utilisateur ->getTypeCompte()->getId() != Constants::COMPTE_ADMIN){
            http_response_code(404);
            exit ();
        }
    }
    
}

