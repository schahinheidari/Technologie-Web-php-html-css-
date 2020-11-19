<?php
set_include_path("./src");
require_once("Routeur.php");
require_once("modele/objet/Objet.php");
require_once("modele/utilisateur/GestionAuthentification.php");
require_once("modele/utilisateur/ConstructeurUtilisateur.php");
require_once("modele/utilisateur/UtilisateurStorageDB.php");
// Classe repr?sentant la vue du site pour les visiteurs
class Vue {
  protected $routeur;
  protected $titre;
  protected $menu;
  protected $contenu;
  protected $feedback;
  // Constructeur
  public function __construct(Routeur $routeur, $feedback) {
    $this->routeur = $routeur;
    $this->titre = "";
    $this->contenu = "";
    $this->feedback = $feedback;
  }
  // Assesseur de routeur
  public function getRouteur () {
    return $this->routeur;
  }
  // M?thode permettant de r?cup?rer le contenu du menu
  public function getMenu () {
    $this->menu = "<li> <a href='" . $this->routeur->getURLPageAccueil() ."'> Accueil </a> </li>";
    $this->menu .= "<li> <a href='" . $this->routeur->getURLPageConnexion() ."'> Connexion </a> </li>";
    $this->menu .= "<li> <a href='" . $this->routeur->getURLPageInscription() ."'> Inscription </a> </li>";
  }
  // M?thode permettant de cr?er la page finale
  public function render () {
    $this->getMenu();
    include("vue/squelettes/squelette.php");
  }
  // M?thode permettant de cr?er l'accueil du site
  public function creationAccueil (GestionAuthentification $authentification) {
    $this->titre = "Accueil";
    $this->contenu = "Bienvenue !";
    //Pour tester les requ?tes
    $nav_pages = "<li> <a href='" . $this->routeur->getURLTest() . "'> Page de test </a> </li>";
  }
  // M?thode permettant de cr?er la page de connexion
  public function creationPageConnexion (GestionAuthentification $authentification) {
    $this->titre = "Connexion";
    $this->contenu = "<form action='" . $this->routeur->getURLConnexion() . "' method='POST'>";
    $this->contenu .= "<label> Login : <input type='text' name='" . GestionAuthentification::LOGIN_REF . "' value='" . htmlspecialchars($authentification->getLogin(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'> </label>";
    $this->contenu .= $authentification->getErreur()[GestionAuthentification::LOGIN_REF];
    $this->contenu .= "<label> Password : <input type='password' name='" . GestionAuthentification::PASSWORD_REF ."' value='" . htmlspecialchars($authentification->getPassword(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'> </label>";
    $this->contenu .= $authentification->getErreur()[GestionAuthentification::PASSWORD_REF];
    $this->contenu .= "<button type='submit'>Connexion</button>";
    $this->contenu .= "</form>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageAccueil() ."'> Annuler </a>";
  }
  // M?thode permettant d'afficher la page correspondant ? une connexion r?ussie
  public function affichageConnexionReussie () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Connexion r?ussie");
  }
  // M?thode permettant d'afficher la page correspondant ? une connexion ayant ?chou?e
  public function affichageConnexionEchouee () {
    $url = $this->routeur->getURLPageConnexion();
    $this->routeur->POSTredirect($url, "Connexion impossible, veuillez corriger les erreurs");
  }
  // M?thode permettant de cr?er la page d'inscription
  public function creationPageInscription (ConstructeurUtilisateur $constructeur) {
    $this->titre = "Inscription";
    $message_erreur = $constructeur->getErreur();
    $this->contenu .= "<form action='" . $this->routeur->getURLInscription() . "' method='POST'>";
    $this->contenu .= "<label> Login : <input type='text' name='" . ConstructeurUtilisateur::LOGIN_REF . "' value='" . htmlspecialchars($constructeur->getLogin(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[ConstructeurUtilisateur::LOGIN_REF];
    $this->contenu .= "<label> Password : <input type='password' name='" . ConstructeurUtilisateur::PASSWORD_REF . "' value='" . htmlspecialchars($constructeur->getPassword(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[ConstructeurUtilisateur::PASSWORD_REF];
    $this->contenu .= "<label> Pr?nom : <input type='text' name='" . ConstructeurUtilisateur::PRENOM_REF . "' value='" . htmlspecialchars($constructeur->getPrenom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[ConstructeurUtilisateur::PRENOM_REF];
    $this->contenu .= "<label> Nom : <input type='text' name='" . ConstructeurUtilisateur::NOM_REF . "' value='" . htmlspecialchars($constructeur->getNom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[ConstructeurUtilisateur::NOM_REF];
    $this->contenu .= "<button type='submit'>Inscription</button>";
    $this->contenu .= "</form>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageAccueil() ."'> Annuler </a>";
  }
  // M?thode permettant d'afficher la page correspondant ? une inscription r?ussie
  public function affichageInscriptionReussie () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Inscription r?ussie");
  }
  // M?thode permettant d'afficher la page correspondant ? une inscription ayant ?chou?e
  public function affichageInscriptionEchouee () {
    $url = $this->routeur->getURLPageInscription();
    $this->routeur->POSTredirect($url, "Inscription impossible, veuillez corriger les erreurs");
  }
  // M?thode permettant d'afficher la page correspondant ? la modification d'un utilisateur non connect?
  public function affichageModificationEchouee () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Vous devez ?tre connect? pour modifier vos informations");
  }
  // M?thode permettant d'afficher la page correspondant ? la d?connexion d'un utilisateur non connect?
  public function affichageDeconnexionEchouee () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "D?connexion impossible");
  }
  public function creationPageTest($db, $dbu) {
    $this->titre = "Page de test des requetes";
    $user = new Utilisateur("Client2", "ui", "ui", "ui", false);
    $date = new DateTime('2012-12-02');
    $result = $date->format('Y-m-d');
    $obj = new Objet("Objet2", $result, "Un objet modifi? maintenant Objet 2", "Categorie2", $user->getLogin(), 1);
    //$db->createObjet($obj);
    //$db->deleteObjet($obj);
    $db->modifObjet($obj);
    //$dbu->modifUser($user, $user->getLogin());
    //$this->contenu .= var_dump($db->readObjectFromDateUser($result, $user));
    //$this->contenu .= var_dump($db->readObjectFromUserCategorieDate($user, 'Categorie2', $result));
    $this->contenu .= var_dump($db->readObjectFromUserCategorieDate($user->getLogin(), 'Categorie2', $result));
    $this->contenu .= var_dump($db->readAll());
    //$this->contenu .= var_dump($db->readLastAdded($user));
    //$this->contenu .= var_dump($dbu->readAll());
    $message_erreur = $db->getErreur();
  }
}
?>
