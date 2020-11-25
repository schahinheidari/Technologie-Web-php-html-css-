<?php
require_once("./src/modele/utilisateur/Utilisateur.php");
class UtilisateurStorageDB{
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
  //M?thode pour r?cuperer un utilisateur par son login ? utiliser une fois que l'utilisateur est connect?.
  //Apr?s l'appel de cette m?thode on cr?er un Objet Utilisateur pour manipuler les donn?es.
  public function readWithLogin($login){
    $rq = "SELECT * FROM Clients WHERE login = :login";
    $stmt = $this->pdo->prepare($rq);
    $data = array(":login" => $login);
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    }
    if ($stmt->rowCount() > 0)
      return $result = $stmt->fetch();
    else {
      $this->erreur = "Login incorrect";
      return null;
    }
  }
  //M?thode pour savoir si l'utilisateur est pr?sent dans la base ou non.
  //Si nous sommes dans une zone sensible et qu'on ne doit pas retourner de donn?es il est
  //pr?f?rable d'utiliser cette m?thode qui return que true ou false.
  public function exist($login){
    $rq = "SELECT * FROM Clients WHERE login = :login";
    $stmt = $this->pdo->prepare($rq);
    $data = array(":login" => $login);
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    }
    if($stmt->rowCount() > 0){
      return true;
    } else {
      $this->erreur = "Utilisateur inexistant";
      return false;
    }
  }
  //M?thode pour r?cuperer un mdp utilisateur
  public function identificationUser($login, $mdp){
    $rq = "SELECT mdp FROM Clients WHERE login = :login";
    $stmt = $this->pdo->prepare($rq);
    $data = array(":login" => $login);
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    }
    $result = $stmt->fetch()['mdp'];
    if(password_verify($mdp, $result)){
      return true;
    } else {
      return false;
    }
  }
  //M?thode pour r?cuperer tous les utilisateurs de la bdd. A utiliser le moins possible.
  public function readAll(){
    $rq = "SELECT * FROM Clients";
    $stmt = $this->pdo->prepare($rq);
    $data = array();
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    }
    return $result = $stmt->fetchAll();
  }
  //M?thode pour cr?er un utilisateur.
  //Si l'utilisateur est inexistant la m?thode lance un exception
  public function createUser(Utilisateur $user){
    $rq = "INSERT INTO Clients (login, nom, prenom, mdp, admin)
    VALUES (:login,
        :nom,
        :prenom,
        :mdp,
        :admin)";
    $stmt = $this->pdo->prepare($rq);
    $data = array(":login" => $user->getLogin(),
      ":nom" => $user->getNom(),
      ":prenom" => $user->getPrenom(),
      ":mdp" => password_hash($user->getPassword(), PASSWORD_BCRYPT),
      ":admin" => $user->getAdmin()
    );
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    }
    try {
      $result = $this->readWithLogin($user->getLogin());
      return $result;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
  //M?thode pour supprimer un utilisateur de la base.
  //Utilisable en admin seulement et si l'utilisateur souhaite partir du site.
  //---->Cependant nous devons aussi supprimer tous les objets appartenant ? l'utilisateur.
  //$user c'est l'utilisateur que l'on souhaite supprimer.
  public function deleteUser(Utilisateur $user){
    $rq = "DELETE FROM Clients WHERE login = :login";
    $stmt = $this->pdo->prepare($rq);
    $data = array(":login" => $user->getLogin());
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    }
  }
  //M?thode pour modifier un utilisateur
  //Utilisable que par l'utilisateur connect?.
  public function modifUser(Utilisateur $user, $login){
    $rq = "UPDATE Clients
      SET login = :loginUp,
        nom = :nom,
        prenom = :prenom,
        mdp = :mdp
      WHERE login = :loginNow";
    $stmt = $this->pdo->prepare($rq);
    $data = array(":loginUp" => $user->getLogin(),
      ":nom" => $user->getNom(),
      ":prenom" => $user->getPrenom(),
      ":mdp" => password_hash($user->getPassword(), PASSWORD_BCRYPT),
      ":loginNow" => $login
    );
    if(!$stmt->execute($data)){
      throw new Exception($pdo->errorInfo());
    } else {
      try {
        $result = $this->readWithLogin($login);
        return $result;
      } catch (Exception $e) {
        throw new Exception($e->getMessage());
      }
    }
  }
}
 ?>