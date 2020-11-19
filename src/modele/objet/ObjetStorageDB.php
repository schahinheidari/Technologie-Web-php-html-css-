<?php
require_once("./src/modele/utilisateur/Utilisateur.php");
require_once("./src/modele/objet/Objet.php");
/**
 */
class ObjetStorageDB {
    private $pdo;
    private $erreur;
    function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->erreur = null;
    }
    public function getErreur(){
        return $this->erreur;
    }
    //M?thode pour savoir si l'objet est pr?sent dans la base ou non.
    //Si nous sommes dans une zone sensible et qu'on ne doit pas retourner de donn?es il est
    //pr?f?rable d'utiliser cette m?thode qui return que true ou false.
    public function exist($id){
        $rq = "SELECT * FROM Objets WHERE id = :id";
        $stmt = $this->pdo->prepare($rq);
        $data = array(":id" => $id);
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        if($stmt->rowCount() > 0){
            return true;
        } else {
            $this->erreur = "Objet inexistant";
            return false;
        }
    }
    //M?thode pour recuperer tout les objets d'un client
    public function readFromClient( $user){
        $rq = "SELECT * FROM Objets WHERE client = :client";
        $stmt = $this->pdo->prepare($rq);
        $data = array(":client" => $user);
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        return $result = $stmt->fetchAll();
    }
    //M?thode pour r?cuprer un/des objet(s) en fct de la date et un utilisateur
    public function readObjectFromDateUser($date_Ajout ,  $user){
        $rq = 'SELECT obj.* FROM Objets as obj, Clients as clt WHERE date_Ajout = :date_Ajout AND obj.client = :client AND obj.client = clt.login';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":date_Ajout" => $date_Ajout,
            ":client" => $user
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer un/des objet(s) en fct de la categorie
    public function readObjectFromCategorie($categorie){
        $rq = 'SELECT * FROM Objets WHERE categorie = :categorie';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":categorie" => $categorie);
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer un/des objet(s) en fct du nom
    public function readObjectFromNom($nom){
        $rq = 'SELECT * FROM Objets WHERE nom = :nom';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":nom" => $nom);
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer un/des objet(s) en fct du nom et d'un utilisateur
    public function readObjectFromNomUser($nom,  $user){
        $rq = 'SELECT * FROM Objets WHERE nom = :nom AND client = :userLogin';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":nom" => $nom,
            ":userLogin" => $user
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer un/des objet(s) en fct du nom, d'un utilisateur et d'une categorie
    public function readObjectFromNomUserCategorie($nom,  $user, $categorie){
        $rq = 'SELECT * FROM Objets WHERE nom = :nom AND client = :userLogin AND categorie = :categorie';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":nom" => $nom,
            ":userLogin" => $user,
            ":categorie" => $categorie
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer un/des objet(s) en fct du nom, d'un utilisateur, d'une categorie et d'une date
    public function readObjectFromNomUserCategorieDate($nom,  $user, $categorie, $date){
        $rq = 'SELECT * FROM Objets WHERE nom = :nom AND client = :userLogin AND categorie = :categorie AND date_Ajout = :date';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":nom" => $nom,
            ":userLogin" => $user,
            ":categorie" => $categorie,
            ":date" => $date
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer un/des objet(s) en fct de la categorie de l'utilisateur et de la date
    public function readObjectFromUserCategorieDate( $user, $categorie, $date){
        $rq = 'SELECT obj.* FROM Objets as obj, Clients as clt WHERE categorie = :categorie AND obj.client = :client AND obj.date_Ajout = :date AND obj.client = clt.login';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":categorie" => $categorie,
            ":client" => $user,
            ":date" => $date
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour recuperer un/des objet(s) en fct de la categorie et du client
    public function readObjectFromUserCategorie( $user, $categorie){
        $rq = 'SELECT obj.* FROM Objets as obj, Clients as clt WHERE categorie = :categorie AND obj.client = :client AND obj.client = clt.login';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":categorie" => $categorie,
            ":client" => $user
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetchAll();
        return $result;
    }
    //M?thode pour r?cuperer tous les objets.
    public function readAll(){
        $rq = "SELECT * FROM Objets";
        $stmt = $this->pdo->prepare($rq);
        $data = array();
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        return $result = $stmt->fetchAll();
    }
    public function createObjet(Objet $obj){
        $rq = "INSERT INTO Objets (nom, date_Ajout, description, categorie, client)
    VALUES (:nom,
      :date,
      :description,
      :categorie,
      :client)";
        $stmt = $this->pdo->prepare($rq);
        $data = array(":nom" => $obj->getNom(),
            ":date" => $obj->getDateAjout(),
            ":description" => $obj->getDescription(),
            ":categorie" => $obj->getCategorie(),
            ":client" => $obj->getUtilisateur(),
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        return $this->readLastAdded($obj->getUtilisateur());
    }
    //M?thode pour retourner le dernier objet ajout? par un utilisateur.
    public function readLastAdded($user){
        $rq = 'SELECT * FROM Objets as obj WHERE id=(SELECT max(id) FROM Objets as obj2 WHERE obj2.client = :userLogin)';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":userLogin" => $user);
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        return $result = $stmt->fetch();
    }
    //M?thode pour trouver un objet en fct de son ID
    public function readWithIDprivate($id, $user){
        $rq = 'SELECT * FROM Objets WHERE id = :id AND client = :user';
        $stmt = $this->pdo->prepare($rq);
        $data = array(
            ":id" => $id,
            ":user" => $user
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetch();
        if($result){
            $objet = new Objet ($result['nom'], $result['date_Ajout'], $result['description'], $result['categorie'], $result['client'], $result['id']);
            return $objet;
        }
        return null;
    }
    //M?thode pour trouver un objet en fct de son ID
    public function readWithID($id){
        $rq = 'SELECT * FROM Objets WHERE id = :id';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":id" => $id);
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        }
        $result = $stmt->fetch();
        if($result){
            $objet = new Objet ($result['nom'], $result['date_Ajout'], $result['description'], $result['categorie'], $result['client'], $result['id']);
            return $objet;
        }
        return null;
    }
    //M?thode pour modifier un objet
    //Avant de modifier l'utilisateur aura choisi l'objet a modifier.
    public function modifObjet($id ,Objet $obj, $user){
        $rq = 'UPDATE Objets
      SET nom = :nom,
        description = :description,
        categorie = :categorie
      WHERE id = :id AND client = :client';
        $stmt = $this->pdo->prepare($rq);
        $data = array(":nom" => $obj->getNom(),
            ":description" => $obj->getDescription(),
            ":categorie" => $obj->getCategorie(),
            ":client" => $user,
            ":id" => $obj->getId()
        );
        if(!$stmt->execute($data)){
            throw new Exception($pdo->errorInfo());
        } else {
            return true;
        }
    }
    //M?thode pour supprimer un objet.
    //L'utilisateur aura choisi un objet ? supprimer ? l'avance
    public function deleteObjet($id, $user){
        $rq = 'DELETE FROM Objets WHERE id = :id AND client = :user';
        $stmt = $this->pdo->prepare($rq);
        $data = array(
            ":id" => $id,
            ":user" => $user
        );
        $stmt->execute($data);
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
}
?>