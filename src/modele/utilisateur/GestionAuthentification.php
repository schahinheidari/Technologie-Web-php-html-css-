<?php
// Classe permettant de g?rer la connexion et la d?connexion des utilisateurs
class GestionAuthentification {
  private $utilisateurs;
  private $donnees;
  private $erreur;
  private $vue;
  // Constantes repr?sentant les r?f?rences des diff?rents champs du formulaire
  const LOGIN_REF = "login";
  const PASSWORD_REF = "password";
  // Constantes repr?sentant la r?f?rence de l'utilisateur
  const UTILISATEUR_REF = "utilisateur";
  // Constantes repr?sentant les r?f?rences de donn?es de l'utilisateur
  const PRENOM_REF = "prenom";
  const NOM_REF = "nom";
  const ADMIN_REF = "admin";
  // Constructeur
  public function __construct (UtilisateurStorageDB $utilisateurs, $donnees, Vue $vue) {
    $this->utilisateurs = $utilisateurs;
    $this->donnees = $donnees;
    $this->erreur = array(self::LOGIN_REF => "",
                          self::PASSWORD_REF => "",
                          );
    $this->vue = $vue;
  }
  // Accesseur d'erreur
  public function getErreur () {
    return $this->erreur;
  }
  // Accesseur de donnees
  public function getDonnees () {
    return $this->donnees;
  }
  // M?thode retournant le login contenu dans les donn?es (renvoie une chaine vide si aucun login)
  public function getLogin () {
    if ($this->donnees !== null and key_exists(self::LOGIN_REF, $this->donnees)) {
      return $this->donnees[self::LOGIN_REF];
    } else {
      return "";
    }
  }
  // M?thode retournant le password contenu dans les donn?es (renvoie une chaine vide si aucun password)
  public function getPassword () {
    if ($this->donnees !== null and key_exists(self::PASSWORD_REF, $this->donnees)) {
      return $this->donnees[self::PASSWORD_REF];
    } else {
      return "";
    }
  }
  // M?thode retournant le pr?nom de l'utilisateur connect?
  public function getPrenom () {
    if (key_exists(self::UTILISATEUR_REF, $_SESSION)) {
      return $_SESSION[self::UTILISATEUR_REF][self::PRENOM_REF];
    }
    return "";
  }
  // M?thode retournant le nom de l'utilisateur connect?
  public function getNom () {
    if (key_exists(self::UTILISATEUR_REF, $_SESSION)) {
      return $_SESSION[self::UTILISATEUR_REF][self::NOM_REF];
    }
    return "";
  }
  // M?thode indiquant si un utilisateur est connect?
  public static function estConnecte() {
    return key_exists(self::UTILISATEUR_REF, $_SESSION);
  }
  // M?thode indiquant si un utilisateur est connect? et si cet utilisateur est un administrateur
  public static function estAdmin() {
    if (self::estConnecte()) {
      return $_SESSION[self::UTILISATEUR_REF][self::ADMIN_REF] === true;
    }
    return false;
  }
  // M?thode indiquant si le login donn? correspond aux pr?requis
  public function loginValide () {
    $login = $this->donnees[self::LOGIN_REF];
    // Le champ login ne doit pas ?tre vide
    if ($login == null) {
      $this->erreur[self::LOGIN_REF] = "Veuillez remplir le champ login";
      return false;
    // Le champ login doit contenir un login pr?sent dans la base de donn?es
    } else if (! $this->utilisateurs->exist($login)) {
      $this->erreur[self::LOGIN_REF] .= "ce login est inconnu";
      return false;
      }
    return true;
  }
  // M?thode indiquant si le password donn? correspond aux pr?requis
  public function passwordValide () {
    $password = $this->donnees[self::PASSWORD_REF];
    // Le champ password ne doit pas ?tre vide
    if ($password == null) {
      $this->erreur[self::PASSWORD_REF] = "Veuillez remplir le champ password";
      return false;
    }
    // Si le password entr? correspond au password contenu dans la base de donn?es on retourne true
    if ($this->utilisateurs->identificationUser($this->donnees[self::LOGIN_REF], $this->donnees[self::PASSWORD_REF])) {
      return true;
    // Sinon on cr?e une erreur et on renvoie false
    } else {
      $this->erreur[self::PASSWORD_REF] .= "Mot de passe erron?";
      return false;
    }
  }
  // M?thode indiquant si le login et le password correspondent aux donn?es d'un utilisateur
  public function estValide () {
    // Si les clefs n'existent pas on retourne false
    if (! key_exists(self::PASSWORD_REF, $_POST) or ! key_exists(self::LOGIN_REF, $_POST)) {
      return false;
    }
    $login_valide = $this->loginValide();
    $password_valide = $this->passwordValide();
    return $login_valide and $password_valide;
  }
  // M?thode permettant de connecter l'utilisateur correspondant aux donn?es et affiche la page correspondante
  // S'il y a une erreur et que la connexion n'est pas possible on retourne false, sinon true
  public function connexion() {
    if (! $this->estValide($this->donnees)) {
      $_SESSION['connexionEnCours'] = $this->donnees;
      $this->vue->affichageConnexionEchouee();
      return false;
    } else {
      $_SESSION[self::UTILISATEUR_REF] = $this->utilisateurs->readWithLogin($this->donnees[self::LOGIN_REF]);
      $this->vue->affichageConnexionReussie();
      return true;
    }
  }
  // M?thode permettant de d?connecter un utilisateur
  public function deconnexion () {
    // On v?rifie qu'un utilisateur est bien connect? puis on le d?connecte et on met ? jour la page ? afficher
    if ($this->estConnecte()) {
      session_destroy();
      session_start();
      $this->vue->affichageDeconnexionReussie();
      return true;
    } else {
      $this->vue->affichageDeconnexionEchouee();
      return false;
    }
  }
}
?>
