<?php
set_include_path("./src");
require_once("modele/utilisateur/Utilisateur.php");
require_once("modele/utilisateur/UtilisateurStorageDB.php");
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
  // M?thode permettant d'afficher la page d'accueil d'un utilisateur
  public function accueil () {
    if (key_exists(GestionAuthentification::UTILISATEUR_REF, $_SESSION)) {
      $authentification = new GestionAuthentification($this->utilisateurs, unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getArray(), $this->vue);
    } else {
      $authentification = new GestionAuthentification($this->utilisateurs, array(), $this->vue);
    }
    $this->vue->creationAccueil($authentification);
    $this->afficheGalerie();
  }
  // M?thode permettant d'afficher la page de connexion d'un utilisateur
  public function connexion () {
    if (key_exists('connexionEnCours', $_SESSION)) {
      $authentification = new GestionAuthentification($this->utilisateurs, $_SESSION['connexionEnCours'][GestionAuthentification::DONNEES_REF], $this->vue);
      $authentification->setErreur($_SESSION['connexionEnCours'][GestionAuthentification::ERREUR_REF]);
    } else {
      $authentification = new GestionAuthentification($this->utilisateurs, array(), $this->vue);
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
    $deconnexion = GestionAuthentification::deconnexion();
    if ($deconnexion) {
      $this->vue->affichageDeconnexionReussie();
    } else {
      $this->vue->affichageDeconnexionEchouee();
    }
  }
  // M?thode permettant d'afficher la page d'inscription d'un utilisateur
  public function inscription () {
    if (key_exists('inscriptionEnCours', $_SESSION)) {
      $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $_SESSION['inscriptionEnCours'][GestionAuthentification::DONNEES_REF]);
      $constructeur->setErreur($_SESSION['inscriptionEnCours'][GestionAuthentification::ERREUR_REF]);
    } else {
      $constructeur = new ConstructeurUtilisateur($this->utilisateurs, array());
    }
    $this->vue->creationPageInscription($constructeur);
  }
  // M?thode permettant de g?rer l'inscription d'un utilisateur
  public function creationUtilisateur (array $donnees) {
    $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $donnees);
    if ($constructeur->estValide()) {
      if (key_exists('inscriptionEnCours', $_SESSION)) {
        unset($_SESSION['inscriptionEnCours']);
      }
      $constructeur->creationUtilisateur(false);
      $authentification = new GestionAuthentification($this->utilisateurs, array(Utilisateur::LOGIN_REF=>$constructeur->getLogin(), Utilisateur::PASSWORD_REF=>$constructeur->getLogin()), $this->vue);
      $authentification->connexion();
    } else {
      $_SESSION['inscriptionEnCours'] = array(GestionAuthentification::DONNEES_REF=>$constructeur->getDonnees(), GestionAuthentification::ERREUR_REF=>$constructeur->getErreur());
      $this->vue->affichageInscriptionEchouee($constructeur);
    }
  }
  // M?thode permettant d'afficher la page de modification d'un utilisateur
  public function demandeModifUtilisateur () {
    if (key_exists('modifUtilisateurEnCours', $_SESSION)) {
      $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $_SESSION['modifUtilisateurEnCours'][GestionAuthentification::DONNEES_REF]);
      $constructeur->setErreur($_SESSION['modifUtilisateurEnCours'][GestionAuthentification::ERREUR_REF]);
    } else {
      $constructeur = new ConstructeurUtilisateur($this->utilisateurs, unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getArray());
    }
    if (GestionAuthentification::estConnecte()) {
      $this->vue->creationPageModifUtilisateur($constructeur);
    } else {
      $this->vue->affichageModificationEchouee();
    }
  }
  // M?thode permettant de g?rer la modification d'un utilisateur
  public function modifUtilisateur (array $donnees) {
    $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $donnees);
    if ($constructeur->estValideModif() and GestionAuthentification::estConnecte()) {
      if (key_exists('modifUtilisateurEnCours', $_SESSION)) {
        unset($_SESSION['modifUtilisateurEnCours']);
      }
      $utilisateur = $constructeur->modifUtilisateur(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF]));
      $_SESSION[GestionAuthentification::UTILISATEUR_REF] = serialize($utilisateur);
      $this->vue->affichageModificationReussie();
    } else {
      $_SESSION['modifUtilisateurEnCours'] = array(GestionAuthentification::DONNEES_REF=>$constructeur->getDonnees(), GestionAuthentification::ERREUR_REF=>$constructeur->getErreur());
      $this->vue->affichageModificationEchouee();
    }
  }
  // M?thode permettant d'afficher la page de modification d'un utilisateur par un administrateur
  public function demandeModifAdmin ($login) {
    if (key_exists('modifAdminEnCours', $_SESSION) and key_exists($login, $_SESSION['modifAdminEnCours'])) {
      $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $_SESSION['modifAdminEnCours'][$login][GestionAuthentification::DONNEES_REF]);
      $constructeur->setErreur($_SESSION['modifAdminEnCours'][$login][GestionAuthentification::ERREUR_REF]);
    } else {
      $constructeur = new ConstructeurUtilisateur($this->utilisateurs, array());
      $constructeur->setUtilisateur($login);
    }
    $this->vue->creationPageModifAdmin($constructeur, $login);
  }
  // M?thode permettant de g?rer la modification d'un utilisateur par un administrateur
  public function modifAdmin ($login, array $donnees) {
    if (key_exists(Utilisateur::ADMIN_REF, $donnees)) {
      $donnees = array(Utilisateur::ADMIN_REF=>true);
    } else {
      $donnees = array(Utilisateur::ADMIN_REF=>false);
    }
    $constructeur = new ConstructeurUtilisateur($this->utilisateurs, $donnees);
    if (key_exists('modifAdminEnCours', $_SESSION) and key_exists($login, $_SESSION['modifAdminEnCours'])) {
      unset($_SESSION['modifAdminEnCours'][$login]);
    }
    $utilisateur = $constructeur->getUtilisateur($login);
    $constructeur->modifAdmin($utilisateur);
    $this->vue->affichageModificationAdminReussie();
  }
  // M?thode permettant d'afficher la page de suppression d'un utilisateur
  public function demandeSuppUtilisateur () {
    $this->vue->creationPageSuppUtilisateur();
  }
  // M?thode permettant de g?rer la suppression d'un utilisateur
  public function suppressionUtilisateur () {
    if (GestionAuthentification::estConnecte()) {
      $this->objets->deleteAllObjetsFromUser(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF]));
      $this->utilisateurs->deleteUser(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF]));
      GestionAuthentification::deconnexion();
      $this->vue->affichageSuppUtilisateurReussie();
    } else {
      $this->vue->affichageSuppUtilisateurEchouee();
    }
  }
  // M?thode permettant d'afficher la liste des utilisateurs
  public function listeUtilisateurs () {
    $this->vue->creationListeUtilisateurs($this->utilisateurs->readAll());
  }
//-------------------- Cr?ation Objet --------------------//
  // Affichage du formulaire
  public function nouveauObjet(){
    if($this->constructeur_objet === null){
      $this->constructeur_objet = new ConstructeurObjet(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin());
    }
    $this->vue->creationPageCreationObjet(date("Y-m-d"), $this->constructeur_objet);
  }
  // M?thode pour la cr?ation d'un objet
  public function creationObjet(array $donnees){
    $this->constructeur_objet = new ConstructeurObjet(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin(), $donnees);
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
    $objet = $this->objets->readWithIDprivate($id, unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin());
    if($objet === null){
      $this->vue->creationPageObjetInexistant();
    } else {
      $this->vue->creationPageSuppression($id, $objet);
    }
  }
  // M?thode pour supprimer l'objet
  public function confirmationSuppression ($id) {
    if(!$this->objets->deleteObjet($id, unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin())){
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
      $utilisateur = unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin();
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
    $utilisateur = unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin();
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
//-------------------- recherche d'objets --------------------//
public function recherche($nom, $date, $categorie, $user = null){
  /*$nom = $donnees['nom'];
  $date = $donnees['date'];
  $categorie = null;
  if(key_exists('categorie', $donnees)){
    $categorie = $donnees['categorie'];
  }*/
  if($user === null){
    if($categorie === null) {
      if($nom === '') {
        $allobjets = $this->objets->readObjectFromDate($date);
      } else if ($date === '') {
        $allobjets = $this->objets->readObjectFromNom($nom);
      }
    } else {
      if($categorie !== '' && $nom === '' && $date === ''){
        $allobjets = $this->objets->readObjectFromCategorie($categorie);
      } else if($nom === '') {
        $allobjets = $this->objets->readObjectFromCategorieDate($categorie, $date);
      } else if ($date === '') {
        $allobjets = $this->objets->readObjectFromNomCategorie($nom, $categorie);
      } else {
        $allobjets = $this->objets->readObjectFromNomCategorieDate($nom, $categorie, $date);
      }
    }
    if($allobjets !== array()){
      $this->vue->creationPageGalerie($allobjets);
    } else {
      $this->vue->creationPageGalerieVide();
    }
  } else if($user !== null){
    if($categorie === null) {
      if($nom === '') {
        $allobjets = $this->objets->readObjectFromDate($date, $user);
      } else if ($date = '') {
        $allobjets = $this->objets->readObjectFromNom($nom, $user);
      }
    } else {
      if($categorie !== '' && $nom === '' && $date === ''){
        $allobjets = $this->objets->readObjectFromCategorie($categorie, $user);
      } else if($nom === '') {
        $allobjets = $this->objets->readObjectFromCategorieDate($categorie, $date, $user);
      } else if ($date === '') {
        $allobjets = $this->objets->readObjectFromNomCategorie($nom, $categorie, $user);
      } else {
        $allobjets = $this->objets->readObjectFromNomCategorieDate($nom, $categorie, $date, $user);
      }
    }
    if($allobjets !== array()){
      $this->vue->creationPageGaleriePerso($allobjets);
    } else {
      $this->vue->creationPageGalerieVide();
    }
  } else {
    $this->vue->creationPageObjetInexistant();
  }
}
//-------------------- Fin recherche d'objets --------------------//
//-------------------- Affichage Objet --------------------//
  // M?thode pour afficher un objet
  public function afficheObjet($id) {
    if(!key_exists(GestionAuthentification::UTILISATEUR_REF, $_SESSION)){
      $objet = $this->objets->readWithID($id);
      if($objet === null){
        $this->vue->creationPageObjetNonValideAccueil($this->objets->getErreur());
      } else {
        $this->vue->creationPageObjet($objet);
      }
    } else {
      $objet = $this->objets->readWithIDprivate($id, unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin());
      if($objet === null){
        $objet = $this->objets->readWithID($id);
        if($objet === null){
          $this->vue->creationPageObjetNonValideAccueil($this->objets->getErreur());
        } else {
          $this->vue->creationPageObjet($objet);
        }
      } else {
        $this->vue->creationPageObjetPerso($objet);
      }
    }
  }
  public function afficheGalerie($login=null, array $donnees = array()) {
    if($this->utilisateurs->exist($login)) {
      $objets = $this->objets->readFromClient($login);
      $this->vue->creationPageGaleriePerso($objets);
    } else {
      $objets = $this->objets->readAll();
      $this->vue->creationPageGalerie($objets);
    }
  }
//-------------------- Fin affichage Objet --------------------//
}
?>