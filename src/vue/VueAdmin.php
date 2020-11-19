<?php
set_include_path("./src");
require_once("Routeur.php");
require_once("modele/utilisateur/GestionAuthentification.php");
require_once("vue/VuePrive.php");
// Classe repr?sentant la vue du site pour les administrateurs
class VueAdmin extends VuePrive {
  // Contructeur
  public function __construct (Routeur $routeur, $feedback) {
    parent::__construct($routeur, $feedback);
  }
  // M?thode permettant de cr?er la page finale
  public function render () {
    self::getMenu();
    include("vue/squelettes/squelette.php");
  }
  // Cr?ation de la page d'accueil
  public function creationAccueil (GestionAuthentification $authentification) {
    $this->titre = "Accueil";
    $message = "Bienvenue " . $authentification->getPrenom() . " " . $authentification->getNom() . ", vous ?tes administrateur !";
  }
  // M?thode permettant de cr?er la page d'inscription
  public function creationPageInscription (ConstructeurUtilisateur $constructeur) {
    $this->titre = "Inscription";
    $message_erreur = $constructeur->getErreur();
    $this->contenu .= "<form action='" . $this->routeur->getURLInscription() . "' method='POST'>";
    $this->contenu .= "<label> Login : <input type='text' name='" . ConstructeurUtilisateur::LOGIN_REF . "' value='" . htmlspecialchars($constructeur->getLogin(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= "<label> Password : <input type='password' name='" . ConstructeurUtilisateur::PASSWORD_REF . "' value='" . htmlspecialchars($constructeur->getPassword(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= "<label> Pr?nom : <input type='text' name='" . ConstructeurUtilisateur::PRENOM_REF . "' value='" . htmlspecialchars($constructeur->getPrenom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= "<label> Nom : <input type='text' name='" . ConstructeurUtilisateur::NOM_REF . "' value='" . htmlspecialchars($constructeur->getNom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= "<Label> Administrateur : <input type='radio' name='" . ConstructeurUtilisateur::ADMIN_REF . "' value='" . $constructeur->getAdmin() . "'/> </label>";
    $this->contenu .= "<button type='submit'>Inscription</button>";
    $this->contenu .= "</form>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageConnexion() ."'> Annuler </a>";
    include("vue/squelettes/squelette_formulaire.php");
  }
}
?>
