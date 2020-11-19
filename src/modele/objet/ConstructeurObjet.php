<?php
set_include_path("./src");
require_once("modele/objet/Objet.php");
require_once("modele/objet/ObjetStorageDB.php");
class ConstructeurObjet {
    private $donnees;
    private $erreur;
    private $objets;
    private $utilisateur;
    const NOM_REF = "nom";
    const DESCRIPTION_REF = "description";
    const CATEGORIE_REF = "categorie";
    public function __construct ($utilisateur, array $donnees=null) {
        if (empty($donnees)) {
            $donnees = array(
                "nom" => "",
                "description" => "",
                "categorie" => "",
            );
        }
        $this->donnees = $donnees;
        $this->utilisateur = $utilisateur;
        $this->erreur = null;
    }
    public static function construireViaObjet(Objet $obj, $utilisateur) {
        return new ConstructeurObjet($utilisateur, array(
            "nom" => $obj->getNom(),
            "description" => $obj->getDescription(),
            "categorie" => $obj->getCategorie(),
        ));
    }
    //Accesseur de donn?es
    public function getDonnees () {
        return $this->donnees;
    }
    //Accesseur de erreur
    public function getErreur () {
        return $this->erreur;
    }
    //M?thode pour retourner le nom de l'objet des donn?es POST
    public function getNom () {
        if (key_exists(self::NOM_REF, $this->donnees)) {
            return $this->donnees[self::NOM_REF];
        } else {
            return "";
        }
    }
    //M?thode pour retourner la description de l'objet des donn?es POST
    public function getDescription () {
        if (key_exists(self::DESCRIPTION_REF, $this->donnees)) {
            return $this->donnees[self::DESCRIPTION_REF];
        } else {
            return "";
        }
    }
    //M?thode pour retourner la cat?gorie de l'objet des donn?es POST
    public function getCategorie () {
        if (key_exists(self::CATEGORIE_REF, $this->donnees)) {
            return $this->donnees[self::CATEGORIE_REF];
        } else {
            return "";
        }
    }
    public function estValide () {
        if (!key_exists(self::NOM_REF, $this->donnees) || !key_exists(self::DESCRIPTION_REF, $this->donnees) || !key_exists(self::CATEGORIE_REF, $this->donnees)){
            return false;
        }
        if ($this->donnees['nom'] == null) {
            $this->erreur = "Erreur : Veuillez remplire le champ nom.";
            return false;
        }
        if (mb_strlen($this->donnees['nom'], 'UTF-8') > 64) {
            $this->erreur = "Erreur : Nom trop long, maximum 64 caract?res.";
            return false;
        }
        if (mb_strlen($this->donnees['description'], 'UTF-8') > 255) {
            $this->erreur = "Erreur : Description trop longue, maximum 255 caract?res.";
            return false;
        }
        return true;
    }
    public function modifierObjet(Objet $obj) {
        $obj->setNom($this->donnees['nom']);
        $obj->setDescription($this->donnees['description']);
        $obj->setCategorie($this->donnees['categorie']);
    }
    public function creationObjet () {
        $objet = new Objet($this->donnees[self::NOM_REF], date('Y-m-d'), $this->donnees[self::DESCRIPTION_REF], $this->donnees[self::CATEGORIE_REF], $this->utilisateur);
        return $objet;
    }
}
?>