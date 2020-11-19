<?php
require_once("./src/modele/utilisateur/Utilisateur.php");
/**
 *
 */
class Objet
{
    private $id;
    private $nom;
    private $date_Ajout;
    private $description;
    private $categorie;
    private $utilisateur;
    //l'id est optionnel car la bdd incremente toute seul, cependant on a besoin de l'id pour la suppression
    //donc quand on devra recuperer un objet on construira un Objet avec l'id de la base.
    public function __construct($nom, $date_Ajout, $description, $categorie, $utilisateur, $id = null)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->date_Ajout = $date_Ajout;
        $this->description = $description;
        $this->categorie = $categorie;
        $this->utilisateur = $utilisateur;
    }
    //Accesseur pour l'id
    public function getId(){
        return $this->id;
    }
    //Accesseur pour le nom de l'objet
    public function getNom(){
        return $this->nom;
    }
    //Accesseur pour la date d'ajout
    public function getDateAjout(){
        return $this->date_Ajout;
    }
    //Accesseur pour la description de l'objet
    public function getDescription(){
        return $this->description;
    }
    //Accesseur pour la categorie
    public function getCategorie(){
        return $this->categorie;
    }
    //Accesseur pour l'utilisateur poss?dant cet objet
    //Un Objet poss?de un client
    public function getUtilisateur(){
        return $this->utilisateur;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }
}
?>
