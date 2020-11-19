<?php
set_include_path("./src");
require_once("controleur/Controleur.php");
require_once("modele/utilisateur/UtilisateurStorage.php");
require_once("modele/utilisateur/Utilisateur.php");
require_once("modele/utilisateur/UtilisateurStorageDB.php");
require_once("modele/utilisateur/GestionAuthentification.php");
require_once("modele/utilisateur/ConstructeurUtilisateur.php");
require_once("vue/Vue.php");
require_once("vue/VueAdmin.php");
require_once("vue/VuePrive.php");
class Routeur {
    // Fonction retournant l'URL de la page d'accueil
    public function getURLPageAccueil () {
        return ".?action=accueil";
    }
    // Fonction retournant l'URL de la page de connexion
    public function getURLPageConnexion () {
        return ".?page=connexion";
    }
    // Fonction retournant l'URL de la page d'inscription
    public function getURLPageInscription () {
        return ".?page=inscription";
    }
    // Fonction retournant l'URL de la page de modification des donn?es d'un utilisateur
    public function getURLPageModifUtilisateur () {
        return ".?page=modifUtilisateur";
    }
    // Fonction retournant l'URL de la page de la cr?ation d'objet.
    public function getURLPageCreationObjet () {
        return ".?page=creerObjet";
    }
    // Fonction retournant l'URL de la page Galerie de l'utilisateur.
    public function getURLPageGalerieUtilisateur () {
        return ".?page=galerieUtilisateur";
    }
    // Fonction retournant l'URL de la page de suppression de l'objet
    public function getURLPageSuppressionObjet ($id) {
        return ".?objet=$id&amp;page=supprimerObjet";
    }
    // Fonction retournant l'url de la page de modification de l'objet
    public function getURLPageModififerObjet ($id) {
        return ".?objet=$id&amp;page=modifierObjet";
    }
    // Fonction retournant l'URL de l'action "connexion"
    public function getURLConnexion () {
        return ".?action=connexion";
    }
    // Fonction retournant l'URL de l'action "inscription"
    public function getURLInscription () {
        global $db;
        $this->db=$db;
        $this->db->exec("insert into user_tbl (username) VALUES ('$data[username]')");
    }

    // Fonction retournant l'URL de l'action "deconnexion"
    public function getURLDeconnexion () {
        return ".?action=deconnexion";
    }
    // Fonction retournant l'URL de l'action "modifUtilisateur"
    public function getURLModifUtilisateur () {
        return ".?action=modifUtilisateur";
    }
    // Fonction retourne l'URL de l'action "creerObjet"
    public function getURLCreationObjet() {
        return ".?action=creationObjet";
    }
    // Fonction retournant l'URL de l'action "afficheObjetPrive"
    public function getURLAfficheObjetPrive () {
        return ".?action=afficheObjetPrive";
    }
    // Fonction retournant l'URL pour supprimer un Objet
    public function getURLSuppressionObjet ($id) {
        return ".?objet=$id&amp;action=supprimerobjet";
    }
    // Fonction retournant l'URL de la page d'un objet.
    public function getURLPageObjet ($id){
        return ".?objet=$id";
    }
    // Fonction retournant l'url pour la modification de l'objet
    public function getURLModifierObjet ($id) {
        return ".?objet=$id&amp;action=modifierObjet";
    }
    // Fonction de redirection
    public function POSTredirect($url, $feedback) {
        $_SESSION['feedback'] = $feedback;
        header("Location: " .htmlspecialchars_decode($url), true, 303);
        die;
    }
    public function getURLTest(){
        return ".?page=test";
    }
    public static function main ($dbUser, $dbObject) {
        session_start();
        if (! key_exists('feedback', $_SESSION)) {
            $_SESSION['feedback'] = "";
        }
        $objetId = key_exists('objet', $_GET) ? $_GET['objet'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;
        $page = key_exists('page', $_GET) ? $_GET['page'] : null;
        if ($action === null && $page === null){
            $action = ($objetId === null) ? "accueil" : "afficheObjetPrive";
        }
        // On cr?? la vue correspondante au statut de l'actuel utilisateur (administrateur, connect? ou visiteur)
        if (GestionAuthentification::estAdmin()) {
            $vue = new VueAdmin(new Routeur(), $_SESSION['feedback']);
        } else if (GestionAuthentification::estConnecte()) {
            //$constructeur_objet = new ConstructeurObjet($_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF], $_POST);
            $vue = new VuePrive(new Routeur(), $_SESSION['feedback']);
        } else {
            $vue = new Vue(new Routeur(), $_SESSION['feedback']);
        }
        unset($_SESSION['feedback']);
        $controleur = new Controleur($vue, $dbUser, $dbObject);
        $authentification = new GestionAuthentification($dbUser, $_POST, $vue);
        $constructeur_utilisateur = new ConstructeurUtilisateur($dbUser, $_POST);
        switch ($action) {
            // Si l'action ? effectuer est une connexion
            case 'connexion' :
                $controleur->tentativeConnexion($_POST);
                break;
            // Si l'action ? effectuer est une d?connexion
            case 'deconnexion' :
                $controleur->deconnexionUtilisateur();
                break;
            // Si l'action ? effectuer est une inscription
            case 'inscription' :
                $controleur->creationUtilisateur($_POST);
                break;
            // Si l'action ? effectuer est un ajout d'objet
            case 'creationObjet' :
                $controleur->creationObjet($_POST);
                break;
            // Si l'action est d'afficher un Objet
            case 'afficheObjetPrive' :
                $controleur->afficheObjetPrive($objetId);
                break;
            // Si l'action est de retourner ? l'accueil.
            case 'accueil' :
                $vue->creationAccueil($authentification);
                break;
            // Si l'action est de supprimer un Objet
            case 'supprimerobjet' :
                $controleur->confirmationSuppression($objetId);
                break;
            // Si l'action est de modifier un Objet
            case 'modifierObjet' :
                $controleur->confirmationModification($objetId, $_POST);
                break;
            // Si l'action ? effectuer est une modification d'un utilisateur
            case 'modifUtilisateur' :
                break;
        }
        switch ($page) {
            // Si la page ? visiter est la page de connexion
            case 'connexion' :
                $controleur->connexion();
                break;
            // Si la page ? visiter est la page d'inscription
            case 'inscription' :
                $vue->creationPageInscription($constructeur_utilisateur);
                break;
            // Si la page ? visiter est la page de modification d'un utilisateur
            case 'modifUtilisateur' :
                $controleur->demandeModifUtilisateur($_SESSION[GestionAuthentification::UTILISATEUR_REF]);
                break;
            // Si la page ? visiter est la page de cr?ation d'un objet.
            case 'creerObjet' :
                $controleur->nouveauObjet();
                break;
            // Si la page ? visiter est la page de la gelerie de l'utilisateur
            case 'galerieUtilisateur' :
                $controleur->afficheGalerieUtilisateur($_SESSION[GestionAuthentification::UTILISATEUR_REF][GestionAuthentification::LOGIN_REF]);
                break;
            // Si la page ? visiter est la page de suppression d'un objet
            case 'supprimerObjet' :
                $controleur->supprimerObjet($objetId);
                break;
            // Si la page ? visiter est la page de modification d'un objet
            case 'modifierObjet' :
                $controleur->modifierObjet($objetId);
                break;
            //test
            case 'test' :
                $vue->creationPageTest($dbObject, $dbUser);
                break;
        }
        $vue->render();
    }
}
?>


