<?php
set_include_path("./src");
require_once("controleur/Controleur.php");
require_once("controleur/GestionAuthentification.php");
require_once("vue/Vue.php");
require_once("vue/VueAdmin.php");
require_once("vue/VuePrive.php");
class Routeur {
  public static function creerURL ($chaine) {
    if (key_exists('PATH_INFO', $_SERVER)) {
      $chemin = explode('/', substr($_SERVER['PATH_INFO'], 1));
      if (count($chemin) == 1) {
        return "./" . $chaine;
      } else if (count($chemin) == 2) {
        return "../" . $chaine;
      }
    }
    return "./index.php/" . $chaine;
  }
  // Fonction retournant l'URL de la page d'accueil
  public function getURLPageAccueil () {
    if (key_exists('PATH_INFO', $_SERVER)) {
      $chemin = explode('/', substr($_SERVER['PATH_INFO'], 1));
      if (count($chemin) == 1) {
        return "../index.php";
      } else if (count($chemin) == 2) {
        return "../";
      }
    }
    return ".";
  }
  // Fonction retournant l'URL de la page de connexion
  public function getURLPageConnexion () {
    return Routeur::creerURL("connexion");
  }
  // Fonction retournant l'URL de l'action "connexion"
  public function getURLConnexion () {
    return Routeur::creerURL("tentativeConnexion");
  }
  // Fonction retournant l'URL de l'action "deconnexion"
  public function getURLDeconnexion () {
    return Routeur::creerURL("deconnexion");
  }
  // Fonction retournant l'URL de la page d'inscription
  public function getURLPageInscription () {
    return Routeur::creerURL("inscription");
  }
  // Fonction retournant l'URL de l'action "inscription"
  public function getURLInscription () {
    return Routeur::creerURL("tentativeInscription");
  }
  // Fonction retournant l'URL de la page de modification des donn?es d'un utilisateur
  public function getURLPageModifUtilisateur () {
    return Routeur::creerURL("modifUtilisateur");
  }
  // Fonction retournant l'URL de l'action "modifUtilisateur"
  public function getURLModifUtilisateur () {
    return Routeur::creerURL("tentativeModifUtilisateur");
  }
  // Fonction retournant l'URL de la page de modification de l'utilisateur login par un administrateur
  public function getURLPageModifAdmin ($login) {
    return Routeur::creerURL("modifAdmin/" . $login);
  }
  // Fonction retournant l'URL de l'action "modifAdmin"
  public function getURLModifAdmin ($login) {
    return Routeur::creerURL("tentativeModifAdmin/" . $login);
  }
  // Fonction retournant l'URL de la page suppression d'un utilisateur
  public function getURLPageSuppressionCompte () {
    return Routeur::creerURL("suppressionCompte");
  }
  // Fonction retournant l'URL de l'action "suppressionCompte"
  public function getURLSuppressionCompte () {
    return Routeur::creerURL("tentativeSuppressionCompte");
  }
  // Fonction retournant l'URL de la page de la liste des utilisateurs
  public function getURLListeUtilisateurs () {
    return Routeur::creerURL("listeUtilisateurs");
  }
  // Fonction retournant l'URL de la page de la cr?ation d'objet.
  public function getURLPageCreationObjet () {
    return Routeur::creerURL("creationObjet");
  }
  // Fonction retourne l'URL de l'action "creerObjet"
  public function getURLCreationObjet() {
    return Routeur::creerURL("tentativeCreationObjet");
  }
  // Fonction retournant l'url de la page de modification de l'objet
  public function getURLPageModififerObjet ($id) {
    return Routeur::creerURL("modifObjet/" . $id);
  }
  // Fonction retournant l'url pour la modification de l'objet
  public function getURLModifierObjet ($id) {
    return Routeur::creerURL("tentativeModifObjet/" . $id);
  }
  // Fonction retournant l'URL de la page de suppression de l'objet
  public function getURLPageSuppressionObjet ($id) {
    return Routeur::creerURL("suppressionObjet/" . $id);
  }
  // Fonction retournant l'URL pour supprimer un Objet
  public function getURLSuppressionObjet ($id) {
    return Routeur::creerURL("tentativeSuppressionObjet/" . $id);
  }
  // Fonction retournant l'URL de la page Galerie de l'utilisateur.
  public function getURLPageGalerie () {
    return Routeur::creerURL("galerie");
  }
  // Fonction retournant l'URL de la page Galerie de l'utilisateur.
  public function getURLPageGaleriePerso () {
    return Routeur::creerURL("galeriePerso");
  }
  // Fonction retournant l'URL de l'action "afficheObjet"
  public function getURLAfficheObjet () {
    return Routeur::creerURL("afficheObjet");
  }
  // Fonction retournant l'URL de l'action recherche
  public function getURLRecherche() {
    return Routeur::creerURL("recherche");
  }
  // Fonction retournant l'URL de l'action recherche perso
  public function getURLRecherchePerso() {
    return Routeur::creerURL("recherchePerso");
  }
  // Fonction retournant l'URL de la page d'un objet.
  public function getURLPageObjet ($id){
    return Routeur::creerURL("objet/" . $id);
  }
  // A supprimer dans la version finale
  public function getURLTest(){
    return Routeur::creerURL("test");
  }
  // Fonction de redirection
  public function POSTredirect($url, $feedback) {
    $_SESSION['feedback'] = $feedback;
    header("Location: " .htmlspecialchars_decode($url), true, 303);
    session_start();
    die;
  }
  public static function main ($dbUser, $dbObject) {
    session_start();
    if (! key_exists('feedback', $_SESSION)) {
      $_SESSION['feedback'] = "";
    }
/*
    $objetId = key_exists('objet', $_GET) ? $_GET['objet'] : null;
    $utilisateurId = key_exists('id', $_GET) ? $_GET['id'] : null;
    $action = key_exists('action', $_GET) ? $_GET['action'] : null;
    $page = key_exists('page', $_GET) ? $_GET['page'] : null;

    if($categorie === null && ($nom !== null && $date !== null)){
      $action = "recherche";
    } else if ($categorie !== null && ($nom !== null && $date !== null)){
      $action = "recherche";
    }

    if ($action === null && $page === null){

      $action = ($objetId === null) ? "./index.php/accueil" : "./index.php/afficheObjet";
    }

*/
    // On cr?? la vue correspondante au statut de l'actuel utilisateur (administrateur, connect? ou visiteur)
    if (GestionAuthentification::estAdmin()) {
      $vue = new VueAdmin(new Routeur(), $_SESSION['feedback']);
    } else if (GestionAuthentification::estConnecte()) {
      //$constructeur_objet = new ConstructeurObjet(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin(), $_POST);
      $vue = new VuePrive(new Routeur(), $_SESSION['feedback']);
    } else {
      $vue = new Vue(new Routeur(), $_SESSION['feedback']);
    }
    unset($_SESSION['feedback']);
    $controleur = new Controleur($vue, $dbUser, $dbObject);
    $authentification = new GestionAuthentification($dbUser, $_POST, $vue);
    if (key_exists('PATH_INFO', $_SERVER)) {
      $chemin = explode('/', substr($_SERVER['PATH_INFO'], 1));
      switch ($chemin[0]) {
      // Si la page ? visiter est la page de connexion
      case 'connexion' :
        $controleur->connexion();
        break;
      // Si l'action ? effectuer est une connexion
      case 'tentativeConnexion' :
        $controleur->tentativeConnexion($_POST);
        break;
      // Si l'action ? effectuer est une d?connexion
      case 'deconnexion' :
        $controleur->deconnexionUtilisateur();
        break;
      // Si la page ? visiter est la page d'inscription
      case 'inscription' :
        $controleur->inscription();
        break;
      // Si l'action ? effectuer est une inscription
      case 'tentativeInscription' :
        $controleur->creationUtilisateur($_POST);
        break;
      // Si la page ? visiter est la page de modification d'un utilisateur
      case 'modifUtilisateur' :
        $controleur->demandeModifUtilisateur();
        break;
      // Si l'action ? effectuer est une modification d'un utilisateur
      case 'tentativeModifUtilisateur' :
        $controleur->modifUtilisateur($_POST);
        break;
      // Si la page ? visiter est la page de suppression d'un utilisateur
      case 'suppressionCompte' :
        $controleur->demandeSuppUtilisateur();
        break;
      // Si l'action ? effectuer est une suppression d'un utilisateur
      case 'tentativeSuppressionCompte' :
        $controleur->suppressionUtilisateur();
        break;
      // Si la page ? v?rifier est la page de modification d'un utilisateur par un administrateur
      case 'modifAdmin' :
        if (key_exists(1, $chemin)) {
          $controleur->demandeModifAdmin($chemin[1]);
        } else {
          $controleur->listeUtilisateurs();
        }
        break;
      // Si l'action ? effectuer est une modification d'une utilisateur par un administrateur
      case 'tentativeModifAdmin' :
        if (key_exists(1, $chemin)) {
          $controleur->modifAdmin($chemin[1], $_POST);
        } else {
          $controleur->listeUtilisateurs();
        }
        break;
      // Si la page ? visiter est la liste des utilisateurs
      case 'listeUtilisateurs' :
        $controleur->listeUtilisateurs();
        break;
      // Si la page ? visiter est la page de cr?ation d'un objet.
      case 'creationObjet' :
        $controleur->nouveauObjet();
        break;
      // Si l'action ? effectuer est un ajout d'objet
      case 'tentativeCreationObjet' :
        $controleur->creationObjet($_POST);
        break;
      // Si la page ? visiter est la page de modification d'un objet
      case 'modifObjet' :
        if (key_exists(1, $chemin)) {
          $controleur->modifierObjet($chemin[1]);
        } else {
          $controleur->accueil();
        }
        break;
      // Si l'action est de modifier un objet
      case 'tentativeModifObjet' :
        if (key_exists(1, $chemin)) {
          $controleur->confirmationModification($chemin[1], $_POST);
        } else {
          $controleur->accueil();
        }
        break;
      // Si la page ? visiter est la page de suppression d'un objet
      case 'suppressionObjet' :
        if (key_exists(1, $chemin)) {
          $controleur->supprimerObjet($chemin[1]);
        } else {
          $controleur->accueil();
        }
        break;
      // Si l'action est de supprimer un objet
      case 'tentativeSuppressionObjet' :
        if (key_exists(1, $chemin)) {
          $controleur->confirmationSuppression($chemin[1]);
        } else {
          $controleur->accueil();
        }
        break;
      // Si l'action est d'afficher un objet d'un utilisateur
      case 'objet' :
        if (key_exists(1, $chemin)) {
          $controleur->afficheObjet($chemin[1]);
        } else {
          $controleur->accueil();
        }
        break;
      // Si la page ? visiter est la galerie des objets
      case 'galerie' :
        $controleur->afficheGalerie();
        break;
      // Si la page ? visiter est la galerie des objets de l'utilisateur
      case 'galeriePerso' :
        $controleur->afficheGalerie(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin());
        break;
      // Si l'action est d'effectuer une recherche g?n?rale
      case 'recherche' :
        $categorie = key_exists('categorie', $_GET) ? $_GET['categorie'] : null;
        $nom = key_exists('nom', $_GET) ? $_GET['nom'] : null;
        $date = key_exists('date', $_GET) ? $_GET['date'] : null;
        $controleur->recherche($nom, $date, $categorie);
        break;
      // Si l'action est d'effectuer une recherche personnelle
      case 'recherchePerso' :
        $categorie = key_exists('categorie', $_GET) ? $_GET['categorie'] : null;
        $nom = key_exists('nom', $_GET) ? $_GET['nom'] : null;
        $date = key_exists('date', $_GET) ? $_GET['date'] : null;
        $controleur->recherche($nom, $date, $categorie, unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin());
        break;
      // A supprimer dans la version finale
      case 'test' :
        $vue->creationPageTest($dbObject, $dbUser);
        break;
      }
    } else {
      $controleur->accueil();
    }
    $vue->render();
  }
}
?>