<?php

namespace utils;

class Constants {
	private function __construct() {}

	/**
	 * BDD Config
	 */
	public const MYSQL_HOST = "localhost";
	public const DBNAME = "gehant_5118";
	public const USERNAME = "gehant_admin";
	public const PASSWORD = "koWGluYIzT7QTKh_";
	
	public const MYSQL_HOST_ONLINE = "185.98.131.176";
	public const DBNAME_ONLINE = "gehan1793907";
	public const USERNAME_ONLINE = "gehan1793907";
	public const PASSWORD_ONLINE = "8lwd3lrpba";

	/**
	 * TABLES
	 */
	public const TABLE_UTILISATEUR = "utilisateurs";
	public const TABLE_CONTACTEZ_NOUS = "contactez_nous";
	public const TABLE_TYPE_COMPTE = "type_comptes";
	public const TABLE_PAYS = "pays";
	public const TABLE_NOMBRE_EMPLOYE = "nombre_employes";
	public const TABLE_OBJECTIF = "objectifs";
	public const TABLE_SECTEUR = "secteurs";
	public const TABLE_ENTREPRISE = "entreprises";
	public const TABLE_DOMAINE = "domaines";
	public const TABLE_FORMATION = "formations";
	public const TABLE_MODULE = "modules";
	public const TABLE_POINT_CLE = "points_cles";
	public const TABLE_FACTURE = "factures";
	public const TABLE_ACHAT = "achats";
	public const TABLE_PARTENAIRE = "partenaires";

	/**
	 * SMTP IDENTIFICATION
	 */
	public const SMTP_USERNAME = "info@gehant-acedemie.net";
	public const SMTP_PASSWORD = "hV1!7AEfD!bzvXz";
	public const SMTP_PORT = 587;
	public const SMTP_HOST = "mail53.lwspanel.com";
	
	/**
	 * SERVICE CLIENT
	 */
    public const SERVICE_CLIENT_EMAIL = "serviceclient@gehant-acedemie.net";
	
	/**
	 * PASSWORD
	 */
	public const PASSWORD_LENGTH = 16;
	public const PASSWORD_ADMIN_LENGTH = 24;

	/* Autres constantes */
	public const PASSWORD_GENERATE_START_SALT = "TGkik74GSF@856";
	public const PASSWORD_GENERATE_END_SALT = "ohsg85/7_n&5shjHYF";
	public const COMPTE_ADMIN = 1;
	public const COMPTE_STANDARD = 2;
	public const COMPTE_ENTREPRISE = 3;

	public const TAILLE_ILLUSTRATION = 10000000;
	public const ILLUSTRATION_WIDTH = 1920;
	public const ILLUSTRATION_HEIGHT = 900;
	
	public const DOMAINE_ILLUSTRATION_FOLDER = "/uploads/domaines/";
	public const PROFIL_FOLDER = "/uploads/profil/";
	public const FORMATION_ILLUSTRATION_FOLDER = "/uploads/formations/";
	public const PDF_FACTURE_FOLDER = "/uploads/pdf/factures/";
    public const PDF_FACTURE_PROFORMA_FOLDER = "/uploads/pdf/proforma/";
	public const PARTENAIRE_FOLDER = "/uploads/partenaires/";
}

