<?php
set_include_path("./src");
require_once("modele/utilisateur/Utilisateur.php");
require_once("modele/utilisateur/UtilisateurStorageDB.php");
// Classe permettant de g?rer la cr?ation d'un utilisateur et sa modification
class ConstructeurUtilisateur {
  private $donnees;
  private $erreur;
  private $utilisateurs;
  // Constantes repr?sentant les r?f?rences des diff?rents champs du formulaire
  const LOGIN_REF = "login";
  const PASSWORD_REF = "password";
  const PRENOM_REF = "prenom";
  const NOM_REF = "nom";
  const ADMIN_REF = "admin";
  // Constructeur
  public function __construct (UtilisateurStorageDB $utilisateurs, array $donnees) {
    $this->donnees = $donnees;
    $this->erreur = array(self::LOGIN_REF => "",
                          self::PASSWORD_REF => "",
                          self::PRENOM_REF => "",
                          self::NOM_REF => "",
                          );
    $this->utilisateurs = $utilisateurs;
  }
  // Assesseur de donnees
  public function getDonnees () {
    return $this->donnees;
  }
  // Assesseur d'erreurs
  public function getErreur () {
    return $this->erreur;
  }
  // M?thode retournant le login contenu dans les donn?es (renvoie une chaine vide si aucun login)
  public function getLogin () {
    if (key_exists(self::LOGIN_REF, $this->donnees)) {
      return $this->donnees[self::LOGIN_REF];
    } else {
      return "";
    }
  }
  // M?thode retournant le password contenu dans les donn?es (renvoie une chaine vide si aucun password)
  public function getPassword () {
    if (key_exists(self::PASSWORD_REF, $this->donnees)) {
      return $this->donnees[self::PASSWORD_REF];
    } else {
      return "";
    }
  }
  // M?thode retournant le pr?nom contenu dans les donn?es (renvoie une chaine vide si aucun pr?nom)
  public function getPrenom () {
    if (key_exists(self::PRENOM_REF, $this->donnees)) {
      return $this->donnees[self::PRENOM_REF];
    } else {
      return "";
    }
  }
  // M?thode retournant le nom contenu dans les donn?es (renvoie une chaine vide si aucun nom)
  public function getNom () {
    if (key_exists(self::NOM_REF, $this->donnees)) {
      return $this->donnees[self::NOM_REF];
    } else {
      return "";
    }
  }
  //M?thode retournant la valeur admin contenue dans les donn?es (renvoie false si aucun admin)
  public function getAdmin () {
    if (key_exists(self::ADMIN_REF, $this->donnees)) {
      return $this->donnees[self::ADMIN_REF];
    } else {
      return false;
    }
  }
  // M?thode indiquant si le contenu du champ login est valide (donc qu'il ne correspond ? aucun login d?j? dans la base)
  public function loginValide ($login) {
    if ($this->utilisateurs->exist($login)) {
      $this->erreur[self::LOGIN_REF] = "Ce login est d?j? utilis?";
      return false;
    }
    return true;
  }
  // M?thode indiquant si le contenu du champ correspond aux pr?requis
  public function champValide ($nom_champ) {
    // Le champ ne doit pas ?tre vide
    if ($this->donnees[$nom_champ] == null) {
      $this->erreur[$nom_champ] = "Veuillez remplir le champ " . $nom_champ;
      return false;
    // Le champ doit contenir moins de 64 caract?res
    } else if (mb_strlen($this->donnees[$nom_champ], 'UTF-8') > 64) {
      $this->erreur[$nom_champ] = "le " . $nom_champ . " doit faire moins de 64 caract?res";
      return false;
    }
    // Si le champ correspond au champ du login
    if ($nom_champ === self::LOGIN_REF) {
      if (! $this->loginValide($this->donnees[$nom_champ])) {
        return false;
      }
    }
    return true;
  }
  // M?thode permettant d'indiquer si les donn?es sont valides ou non
  public function estValide () {
    // Si les clefs n'existent pas on retourne false
    if (! key_exists(self::LOGIN_REF, $this->donnees) or ! key_exists(self::PASSWORD_REF, $this->donnees) or ! key_exists(self::PRENOM_REF, $this->donnees) or ! key_exists(self::NOM_REF, $this->donnees)) {
      return false;
    }
    $login_valide = $this->champValide(self::LOGIN_REF);
    $password_valide = $this->champValide(self::PASSWORD_REF);
    $prenom_valide = $this->champValide(self::PRENOM_REF);
    $nom_valide = $this->champValide(self::NOM_REF);
    // Si tous les champs sont valides on retourne true
    if ($login_valide and $password_valide and $prenom_valide and $nom_valide) {
      return true;
    }
    return false;
  }
  // M?thode permettant de cr?er un utilisateur
  public function creationUtilisateur ($admin) {
    $utilisateur = new Utilisateur ($this->donnees[self::LOGIN_REF], password_hash($this->donnees[self::PASSWORD_REF], PASSWORD_BCRYPT), $this->donnees[self::PRENOM_REF], $this->donnees[self::NOM_REF], $admin);
    $this->utilisateurs->createUser($utilisateur);
    return $utilisateur;
  }
  // M?thode permettant de modifier un utilisateur
  public function modifUtilisateur (Utilisateur $utilisateur) {
    $utilisateur->setPassword($this->donnees[self::PASSWORD_REF]);
    $utilisateur->setPrenom($this->donnees[self::PRENOM_REF]);
    $utilisateur->setNom($this->donnees[self::NOM_REF]);
    $utilisateur->setAdmin($this->donnees[self::ADMIN_REF]);
  }
}
?>
