<?php
set_include_path("./src");
require_once("modele/utilisateur/UtilisateurStorageDB.php");
require_once("modele/utilisateur/GestionAuthentification.php");
require_once("modele/objet/ObjetStorageDB.php");
require_once("vue/Vue.php");
require_once("vue/VuePrive.php");
require_once("vue/VueAdmin.php");
// Classe repr?sentant le contr?leur du site
class Controleur {
    private $vue;
    private $utilisateurs;
    private $objets;
    private $constructeur_objet;
    private $modificateur_objet;
    // Constructeur
    public function __construct (Vue $vue, UtilisateurStorageDB $utilisateurs, ObjetStorageDB $objets) {
        $this->vue = $vue;
        $this->utilisateurs = $utilisateurs;
        $this->objets = $objets;
        $this->constructeur_objet = key_exists('constructeurObjet', $_SESSION) ? $_SESSION['constructeurObjet'] : null;
        $this->modificateur_objet = key_exists('modificateurObjet', $_SESSION) ? $_SESSION['modificateurObjet'] : array();
    }
    public function __destruct () {
        $_SESSION['constructeurObjet'] = $this->constructeur_objet;
        $_SESSION['modificateurObjet'] = $this->modificateur_objet;
    }
    // Mutateur de vue
    public function setVue (Vue $vue) {
        $this->vue = $vue;
    }
    // M?thode permettant d'afficher la page de connexion d'un utilisateur
    public function connexion () {
        if (key_exists('connexionEnCours', $_SESSION)) {
            $authentification = new GestionAuthentification($this->utilisateurs, $_SESSION['connexionEnCours'], $this->vue);
        } else {
            $authentification = new GestionAuthentification($this->utilisateurs, null, $this->vue);
        }
        $this->vue->creationPageConnexion($authentification);
    }
    // M?thode permettant de g?rer la connexion d'un utilisateur
    public function tentativeConnexion (array $donnees) {
        $authentification = new GestionAuthentification($this->utilisateurs, $donnees, $this->vue);
        $authentification->connexion();
    }
    // M?thode permettant de g?rer la d?connexion d'un utilisateur
    public function deconnexionUtilisateur () {
        $authentification = new GestionAuthentification($this->utilisateurs, null, $this->vue);
        $authentification->deconnexion();
    }
    //
    public function afficheUtilisateur () {
    }
    //
    public function creationUtilisateur (array $donnees) {
        $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $donnees);
        if ($constructeur->estValide()) {
            $utilisateur = $constructeur->creationUtilisateur(false);
            $array_utilisateur = array(GestionAuthentification::LOGIN_REF => $utilisateur->getLogin(),GestionAuthentification::PASSWORD_REF => $utilisateur->getPassword());
            $authentification = new GestionAuthentification($this->utilisateurs, $array_utilisateur, $this->vue);
            $this->setVue(new VuePrive(new Routeur()));
            $this->vue->creationAccueil($authentification);
        } else {
            $this->vue->creationPageInscription($constructeur);
        }
    }
    //
    public function demandeModifUtilisateur (array $donnees) {
        $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $donnees);
        $this->vue->creationPageModifUtilisateur($constructeur);
    }
//-------------------- Cr?ation Objet --------------------//
    // Affichage du formulaire
    public function nouveauObjet(){
        if($this->constructeur_objet === null){
            $this->constructeur_objet = new ConstructeurObjet($_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF]);
        }
        $this->vue->creationPageCreationObjet(date("Y-m-d"), $this->constructeur_objet);
    }
    // M?thode pour la cr?ation d'un objet
    public function creationObjet(array $donnees){
        $this->constructeur_objet = new ConstructeurObjet($_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF], $donnees);
        if ($this->constructeur_objet->estValide()){
            $objet = $this->constructeur_objet->creationObjet();
            $objet = $this->objets->createObjet($objet);
            $this->constructeur_objet = null;
            $this->vue->creationPageObjetCree($objet['id']);
        } else {
            $this->vue->creationPageObjetNonCree($this->constructeur_objet->getErreur());
        }
    }
//-------------------- Fin cr?ation Objet --------------------//
//-------------------- Suppression Objet --------------------//
    // M?thode pour afficher le formulaire de suppression
    public function supprimerObjet($id) {
        $objet = $this->objets->readWithIDprivate($id, $_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF]);
        if($objet === null){
            $this->vue->creationPageObjetInexistant();
        } else {
            $this->vue->creationPageSuppression($id, $objet);
        }
    }
    // M?thode pour supprimer l'objet
    public function confirmationSuppression ($id) {
        if(!$this->objets->deleteObjet($id, $_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF])){
            $this->vue->creationPageObjetInexistant();
        } else {
            $this->vue->creationPageSuppressionOk();
        }
    }
//-------------------- Fin suppression Objet --------------------//
//-------------------- Modification Objet --------------------//
    // M?thode pour afficher le formulaire de modification d'un objet
    public function modifierObjet ($id) {
        if(key_exists($id, $this->modificateur_objet)) {
            $this->vue->creationPageModifObjet($id, $this->modificateur_objet[$id]);
        } else {
            $utilisateur = $_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF];
            $objet = $this->objets->readWithIDprivate($id, $utilisateur);
            if($objet === null) {
                $this->vue->creationPageObjetInexistant();
            } else {
                $constructeur = ConstructeurObjet::construireViaObjet($objet,  $utilisateur);
                $this->vue->creationPageModifObjet($id, $constructeur);
            }
        }
    }
    // M?thode pour confirmer la modification
    public function confirmationModification ($id, array $donnees) {
        $utilisateur = $_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF];
        $objet = $this->objets->readWithIDprivate($id, $utilisateur);
        if($objet === null) {
            $this->vue->creationPageObjetInexistant();
        } else {
            $constructeur = new ConstructeurObjet($utilisateur, $donnees);
            if($constructeur->estValide()) {
                $constructeur->modifierObjet($objet);
                $retour = $this->objets->modifObjet($id, $objet, $utilisateur);
                if(! $retour){
                    $this->vue->creationPageObjetInexistant();
                }
                unset($this->modificateur_objet[$id]);
                $this->vue->creationPageObjetModifie($id);
            } else {
                $this->modificateur_objet[$id] = $constructeur;
                $this->vue->creationPageObjetNonModifie($id);
            }
        }
    }
//-------------------- Fin modification Objet --------------------//
//-------------------- Affichage Objet --------------------//
    // M?thode pour afficher un objet
    public function afficheObjetPrive($id) {
        $objet = $this->objets->readWithIDprivate($id, $_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF]);
        if($objet === null){
            $this->vue->creationPageObjetNonValideAccueil($this->objets->getErreur());
        } else {
            $this->vue->creationPageObjet($objet);
        }
    }
    public function afficheGalerieUtilisateur($login) {
        if($this->utilisateurs->exist($login)) {
            $objets = $this->objets->readFromClient($login);
            $this->vue->creationPageGalerieUtilisateur($objets);
        }
    }
//-------------------- Fin affichage Objet --------------------//
}
?>