<?php
set_include_path("./src");
require_once("Routeur.php");
require_once("controleur/GestionAuthentification.php");
require_once("modele/objet/ConstructeurObjet.php");
require_once("modele/objet/Objet.php");
require_once("vue/Vue.php");
// Classe repr?sentant la vue du site pour les utilisateurs connect?s
class VuePrive extends Vue {
  // Constructeur
  public function __construct (Routeur $routeur, $feedback) {
    parent::__construct($routeur, $feedback);
  }
  // M?thode permettant de r?cup?rer le contenu du menu
  public function getMenu () {
    $this->menu .= "<li> <a href='" . $this->routeur->getURLPageAccueil() ."'> Accueil </a> </li>";
    $this->menu .= "<li> <a href='" . $this->routeur->getURLPageSuppressionCompte() ."'> Supprimer mon compte </a> </li>";
    $this->menu .= "<li> <a href='" . $this->routeur->getURLPageModifUtilisateur() ."'> Modifier mes informations </a> </li>";
    $this->menu .= "<li> <a href='" . $this->routeur->getURLDeconnexion() ."'> D?connexion </a> </li>";
    $this->menu .= "<li> <a href=" . $this->routeur->getURLPageCreationObjet() . "> Ajouter un objet </a> </li>";
    $this->menu .= "<li> <a href='" . $this->routeur->getURLPageGaleriePerso() ."'> Votre Galerie </a> </li>";
  }
  // M?thode permettant de cr?er l'accueil du site
  public function creationAccueil (GestionAuthentification $authentification) {
    $this->titre = "Accueil";
    $this->menu .= "Bienvenue " . htmlspecialchars($authentification->getPrenom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
    $this->menu .= " " . htmlspecialchars($authentification->getNom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . " !";
    $this->contenu_principal .= "Bienvenue sur votre compte, ajoutez, modifiez et supprimez ? votre guise les objets en votre possession, en acc?dant ? votre galerie.";
    // Pour tester les requ?tes
    $page_test = "<a href=" . $this->routeur->getURLTest() . "> Page de test </a>";
  }
  // M?thode permettant d'afficher la page correspondant ? une connexion ayant ?chou?e
  public function affichageConnexionEchouee () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Connexion impossible, vous ?tes d?j? connect?");
  }
  // M?thode permettant d'afficher la page correspondant ? une connexion ayant ?chou?e
  public function affichageInscriptionEchouee () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Inscription impossible, vous poss?dez d?j? un compte");
  }
  // M?thode permettant de cr?er la page de modification d'un utilisateur
  public function creationPageModifUtilisateur (ConstructeurUtilisateur $constructeur) {
    $this->titre = "Modification";
    $this->contenu .= "<form action='" . $this->routeur->getURLModifUtilisateur() . "' method='POST'>";
    $this->contenu .= "<label> Password : <input type='password' name='" . Utilisateur::PASSWORD_REF . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[Utilisateur::PASSWORD_REF];
    $this->contenu .= "<label> Pr?nom : <input type='text' name='" . Utilisateur::PRENOM_REF . "' value='" . htmlspecialchars($constructeur->getPrenom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[Utilisateur::PRENOM_REF];
    $this->contenu .= "<label> Nom : <input type='text' name='" . Utilisateur::NOM_REF . "' value='" . htmlspecialchars($constructeur->getNom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= $constructeur->getErreur()[Utilisateur::NOM_REF];
    $this->contenu .= "<button type='submit'>Sauvegarder les modifications</button>";
    $this->contenu .= "</form>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageAccueil() ."'> Annuler </a>";
  }
  // M?thode permettant d'afficher la page correspondant ? une modification r?ussie
  public function affichageModificationReussie () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Vos informations ont ?t? modifi?es");
  }
  // M?thode permettant d'afficher la page correspondant ? une modification ayant ?chou?e
  public function affichageModificationEchouee () {
    $url = $this->routeur->getURLPageModifUtilisateur();
    $this->routeur->POSTredirect($url, "Modification impossible, veuillez corriger les erreurs");
  }
  // M?thode permettant d'afficher la page correspondant ? la d?connexion d'un utilisateur non connect?
  public function affichageDeconnexionReussie () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Vous avez ?t? d?connect?");
  }
  // M?thode permettant de cr?er la page de suppression d'un utilisateur
  public function creationPageSuppUtilisateur () {
    $this->titre = "Supprimer le compte";
    $this->contenu .= "<p> ?tes vous s?r de vouloir supprimer votre compte ?</p>";
    $this->contenu .= "<form action='" . $this->routeur->getURLSuppressionCompte() . "' method='POST'>";
    $this->contenu .= "<input type='submit' value='Supprimer'>";
  }
  // M?thode permettant d'afficher la page correspondant ? une suppression r?ussie
  public function affichageSuppUtilisateurReussie () {
    $url = $this->routeur->getURLPageAccueil();
    $this->routeur->POSTredirect($url, "Le compte a bien ?t? supprim?");
  }
  //M?thode permettant de cr?er la page pour cr?er un Objet
  public function creationPageCreationObjet($date, ConstructeurObjet $constructeur){
    $this->titre = "Ajouter un objet ? votre collection";
    $this->contenu .= $constructeur->getErreur();
    $this->contenu .= "<form action='" . $this->routeur->getURLCreationObjet() . "' method='POST'>";
    $this->contenu .= "<label> Nom de l'objet : <input type='text' name='" . ConstructeurObjet::NOM_REF . "' value='" . htmlspecialchars($constructeur->getNom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= "<label for='description'> Description : </label>";
    $this->contenu .= "<textarea name='" . ConstructeurObjet::DESCRIPTION_REF . "' id='description' value='" . htmlspecialchars($constructeur->getDescription(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'> </textarea>";
    $this->contenu .= "<label for='categorie'> Categorie de l'objet : </label>";
    $this->contenu .= "<select id='categorie' name='" . ConstructeurObjet::CATEGORIE_REF . "' >";
      $this->contenu .= "<option value='Categorie1'> Categorie 1 </option>";
      $this->contenu .= "<option value='Categorie2'> Categorie 2 </option>";
      $this->contenu .= "<option value='Categorie3'> Categorie 3 </option> </select>";
    $this->contenu .= "<button type='submit'> Ajouter un objet </button>";
    $this->contenu .= "</form>";
    $this->contenu .= "Ajout le : " . $date;
    $this->contenu .= "<a href='" . $this->routeur->getURLPageAccueil() . "'> Annuler </a>";
  }
  // M?thodes de redirection, si ok
  public function creationPageObjetCree ($id) {
    $this->routeur->POSTredirect($this->routeur->getURLPageObjet($id), "L'Objet ? bien ?t? ajout?.");
  }
  // Si erreur.
  public function creationPageObjetNonCree ($erreur) {
    $this->routeur->POSTredirect($this->routeur->getURLPageCreationObjet(), "L'objet n'a pas ?t? ajout?. " . $erreur);
  }
  // Si erreur.
  public function creationPageObjetNonValideGalerie ($erreur) {
    $this->routeur->POSTredirect($this->routeur->getURLPageGaleriePerso(), "L'objet n'existe pas/plus. " . $erreur);
  }
  // Si erreur.
  public function creationPageObjetNonValideAccueil ($erreur) {
    $this->routeur->POSTredirect($this->routeur->getURLPageAccueil(), "L'objet n'existe pas/plus. " . $erreur);
  }
  // M?thode pour l'affichage d'un objet.
  public function creationPageObjetPerso (Objet $objet) {
    $this->titre = $objet->getNom();
    $this->contenu .= "Votre objet a ?t? ajout? le : " . $objet->getDateAjout() . "</br>";
    $this->contenu .= "Description de votre objet : " . $objet->getDescription() . "</br>";
    $this->contenu .= "Cat?gorie de l'objet : " .$objet->getCategorie() . "</br>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageAccueil() ."'> Retour Accueil </a> </br>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageCreationObjet() ."'> Retour Cr?ation </a> </br>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageGalerie() ."'> Votre Galerie </a> </br>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageSuppressionObjet($objet->getId()) ."'> Supprimer l'objet </a> </br>";
    $this->contenu .= "<a href='" . $this->routeur->getURLPageModififerObjet($objet->getId()) ."'> Modifier l'objet </a>";
  }
  public function creationPageSuppression ($id, $objet) {
    $this->titre = "Supprimer un objet : ";
    $this->contenu .= "L'objet ? supprimer : " . $objet->getNom() . "</br>";
    $this->contenu .= "Description : " . $objet->getDescription() . "</br>";
    $this->contenu .= "Ajout? le : " . $objet->getDateAjout() . "<br>";
    $this->contenu .= "<form action='" . $this->routeur->getURLSuppressionObjet($id) . "' method='POST'>";
    $this->contenu .= "<button type='submit'> Supprimer </button>";
  }
  public function creationPageSuppressionOk () {
    $this->routeur->POSTredirect($this->routeur->getURLPageGaleriePerso(unserialize($_SESSION[GestionAuthentification::UTILISATEUR_REF])->getLogin()), "L'objet a bien ?t? supprim?.");
  }
  public function creationPageModifObjet($id, ConstructeurObjet $constructeur) {
    $this->titre = "Modifier l'objet";
    $this->contenu .= "<form action='" . $this->routeur->getURLModifierObjet($id) . "' method='POST'>";
    $this->contenu .= "<label> Nom de l'objet : <input type='text' name='" . ConstructeurObjet::NOM_REF . "' value='" . htmlspecialchars($constructeur->getNom(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'/> </label>";
    $this->contenu .= "<label for='description'> Description : </label>";
    $this->contenu .= "<textarea name='" . ConstructeurObjet::DESCRIPTION_REF . "' id='description' value='" . htmlspecialchars($constructeur->getDescription(), ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8') . "'>" . $constructeur->getDescription() . " </textarea>";
    $this->contenu .= "<label for='categorie'> Categorie de l'objet : </label>";
    $this->contenu .= "<select id='categorie' name='" . ConstructeurObjet::CATEGORIE_REF . "' >";
    $this->contenu .= "<option value=" . $constructeur->getCategorie() . " selected hidden>" . $constructeur->getCategorie() . "</option>";
      $this->contenu .= "<option value='Categorie1'> Categorie 1 </option>";
      $this->contenu .= "<option value='Categorie2'> Categorie 2 </option>";
      $this->contenu .= "<option value='Categorie3'> Categorie 3 </option> </select>";
    $this->contenu .= "<button type='submit'> Modifier l'objet </button>";
    $this->contenu .= "</form>";
  }
  public function creationPageObjetModifie($id) {
    $this->routeur->POSTredirect($this->routeur->getURLPageObjet($id), "L'Objet a bien ?t? modifi?");
  }
  public function creationPageObjetNonModifie($id) {
    $this->routeur->POSTredirect($this->routeur->getURLPageModififerObjet($id), "L'Objet n'a pas ?t? modifi?");
  }
  // Methode pour l'affichage si l'objet n'existe pas
  public function creationPageObjetInexistant (){
    $this->titre = "L'objet n'existe pas";
    $this->contenu = "L'objet actuellement selectionn? n'a pas ?tait trouv?.";
  }
  public function creationPageGalerieVide(){
    $this->routeur->POSTredirect($this->routeur->getURLPageGalerie(), "Aucun objet trouv?.");
  }
  public function affichageFormulaireRecherchePerso() {
    $this->contenu .= "Recherche : </br>";
    $this->contenu .= "<form action='" . $this->routeur->getURLRecherchePerso() . "' method='GET'>";
    $this->contenu .= "<label> Nom de l'objet : <input type='text' name='" . ConstructeurObjet::NOM_REF . "'/> </label>";
    $this->contenu .= "<label for='categorie'> Categorie de l'objet : </label>";
    $this->contenu .= "<select id='categorie' name='" . ConstructeurObjet::CATEGORIE_REF . "' >";
    $this->contenu .= "<option value='none' selected disabled hidden>
          Selectionner une categorie </option>";
      $this->contenu .= "<option value='Categorie1'> Categorie 1 </option>";
      $this->contenu .= "<option value='Categorie2'> Categorie 2 </option>";
      $this->contenu .= "<option value='Categorie3'> Categorie 3 </option> </select>";
    $this->contenu .= "<labet for='date'> Date de recherche : </label>";
      $this->contenu .= "<input type='date' id='date' name='date' value'"  . date("Y-m-d") . "'>";
    $this->contenu .= "<button type='submit'> Recherche </button>";
    $this->contenu .= "</form>";
  }
  // M?thode pour l'affichage de la galerie.
  public function creationPageGaleriePerso ($objets) {
    $this->sous_titre = "Vos Objets : ";
    $this->affichageFormulaireRecherchePerso();
    foreach ($objets as $objet) {
      $this->contenu .= "<li> <a href=". $this->routeur->getURLPageObjet($objet['id']) .">" . $objet['nom'] . "</a> </li>";
    }
  }
}
?>