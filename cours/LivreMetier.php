<?php

/*******************************************************************/
/*                                                                 */
/*                       Classe LivreMetier                        */
/*                                                                 */
/*                 @Author Dominique Fournier                      */
/*                       @date 2013-2015                           */
/*                                                                 */
/*                                                                 */
/*******************************************************************/

class LivreMetier {

  /***********************************/
  /* gestion statique des accès SGBD */
  /***********************************/

  // instance de PDO 
  private static $_pdo; 

  // PreparedStatement associé à un select qui retourne les informations 
  // d'un enregistrement de la table livre en fonction d'un numéro
 private static $_pdos_select;  

  // PreparedStatement associé à un UPDATE, met à jour les informations d'un livre
  private static $_pdos_update;

  // PreparedStatement associé à un INSERT, insère un nouveau livre dans la table
  private static $_pdos_insert;

  // PreparedStatement associé à un DELETE, supprime un livre de la table
  private static $_pdos_delete;

  // PreparedStatement associé à un SELECT, calcule le nombre de livres de la table
  private static $_pdos_count;

  /**
   * méthode réalisant l'initialisation et la configuration de la connexion à la BD
   * instantiation de self::$_pdo
   */
  public static function initPDO() {
    self::$_pdo = new PDO('pgsql:host=localhost dbname=coursBado user=dominique password=dominique');
    //    self::$_pdo = new PDO('mysql:host=localhost;dbname=infoweb','infoweb','infoweb');
    // pour récupérer aussi les exceptions provenant de PDOStatement
    self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  
  /**
   * préparation de la requête SELECT * FROM livre WHERE liv_num= :numero
   * instantiation de self::$_pdos_select
   */
  public static function initPDOS_select() {
    self::$_pdos_select = self::$_pdo->prepare('SELECT * FROM livre WHERE liv_num= :numero');
  }

  /**
   * préparation de la requête UPDATE livre SET liv_titre=:titre, liv_depotlegal=:depotLegal WHERE liv_num=:numero
   * instantiation de self::$_pdos_update
   */
  public static function initPDOS_update() {
    self::$_pdos_update =  self::$_pdo->prepare('UPDATE livre SET liv_titre=:titre, liv_depotlegal=:depotLegal WHERE liv_num=:numero');
  }

  /**
   * préparation de la requête INSERT INTO livre VALUES(:numero,:titre,:depotLegal)
   * instantiation de self::$_pdos_insert
   */
  public static function initPDOS_insert() {
    self::$_pdos_insert = self::$_pdo->prepare('INSERT INTO livre VALUES(:numero,:titre,:depotLegal)');
  }

  /**
   * préparation de la requête DELETE FROM livre WHERE liv_num=:numero
   * instantiation de self::$_pdos_delete
   */
  public static function initPDOS_delete() {
    self::$_pdos_delete = self::$_pdo->prepare('DELETE FROM livre WHERE liv_num=:numero');
  }

  /**
   * préparation de la requête SELECT COUNT(*) FROM livre
   * instantiation de self::$_pdos_count
   */
  public static function initPDOS_count() {
    if (!isset(self::$_pdo)) 
      self::initPDO();
    self::$_pdos_count = self::$_pdo->prepare('SELECT COUNT(*) FROM livre');
  }
  
  /***********************************/
  /*    gestion de l'objet métier    */
  /***********************************/

 // chaque attribut d'instance public correspond à une colonne de la table de la BD
  public $liv_num;
  public $liv_titre;
  public $liv_depotlegal;

  /* attribut interne pour différencier les nouveaux objets des objets issus du SGBD */
  protected $nouveau = TRUE;

  public function getLiv_num() {
    return $this->liv_num;
  }

  public function setLiv_num($liv_num) {
    $this->liv_num=$liv_num;
  }

  public function getLiv_titre() {
    return $this->liv_titre;
  }

  public function setLiv_titre($liv_titre) {
    $this->liv_titre=$liv_titre;
  }

  public function getLiv_depotlegal() {
    return $this->liv_depotlegal;
  }

  public function setLiv_depotlegal($liv_depotlegal) {
    $this->liv_depotlegal=$liv_depotlegal;
  }

  public function getNouveau() {
    return $this->nouveau;
  }

  public function setNouveau($nouveau) {
    $this->nouveau=$nouveau;
  }

  /**
   * initialisation d'un objet métier à partir d'un enregistrement de livre
   * mise en oeuvre de LivreMetier::_pdos_select avec "binding" de paramètre
   * @param $liv_num un identifiant de livre 
   * @return nouvelle instance de LivreMetier
    */
  public static function initLivreMetier($liv_num) {
      try {
	if (!isset(self::$_pdo)) 
	  self::initPDO();
	if (!isset(self::$_pdos_select)) 
	  self::initPDOS_select();
	self::$_pdos_select->bindValue(':numero',$liv_num);
	self::$_pdos_select->execute();
	// résultat du fetch dans une instance de LivreMetier
	$lm = self::$_pdos_select->fetchObject('LivreMetier');
	if (isset($lm) && ! empty($lm)) 
	  $lm->setNouveau(FALSE);
	return $lm;
      }
      catch (PDOException $e) {
	print($e);
      }
  }

  /**
   * sauvegarde d'un objet métier 
   * soit on insère un nouvel objet via LivreMetier::_pdo_insert
   * soit on le met à jour via LivreMetier::_pdo_update
   */
  public function save() {
    if (!isset(self::$_pdo)) 
      self::initPDO();
    if ($this->nouveau) {
      if (!isset(self::$_pdos_insert)) {
	self::initPDOS_insert();
      }
      self::$_pdos_insert->bindParam(':numero', $this->liv_num);
      self::$_pdos_insert->bindParam(':titre', $this->liv_titre);
      self::$_pdos_insert->bindParam(':depotLegal', $this->liv_depotlegal);
      self::$_pdos_insert->execute();
      $this->setNouveau(FALSE);
    }
    else {
      if (!isset(self::$_pdos_update)) 
	self::initPDOS_update();
      self::$_pdos_update->bindParam(':numero', $this->liv_num);
      self::$_pdos_update->bindParam(':titre', $this->liv_titre);
      self::$_pdos_update->bindParam(':depotLegal', $this->liv_depotlegal);
      self::$_pdos_update->execute();
    }
  }

  /**
   * suppression d'un objet métier : élimine l'enregistrement correspondant dans la BD
   * mise en oeuvre de LivreMetier::_pdos_delete avec "binding" de paramètre
   */
  public function delete() {
    if (!isset(self::$_pdo)) 
      self::initPDO();
    if (!$this->nouveau) {
      if (!isset(self::$_pdos_delete)) {
	self::initPDOS_delete();
      }
      self::$_pdos_delete->bindParam(':numero', $this->liv_num);
      self::$_pdos_delete->execute();
    }
    $this->setNouveau(TRUE);
  }   
  
  /**
   * nombre d'objets metier disponible dans la table 
   */
  public static function getNbLivres() {
    if (!isset(self::$_pdos_count)) {
      self::initPDOS_count();
    }
    self::$_pdos_count->execute();
    $resu = self::$_pdos_count->fetch();
    return $resu[0];
  }   
  
  /**
   * encodage json
   */
  public function toJSON() {
    return json_encode($this);
  }

  /**
   * affichage élémentaire pour visualiser dans un navigateur
   */
  public function __toString() {
    $ch = "<table border='1'><tr>";
    $ch.= "<td>".$this->liv_num."</td>";
    $ch.= "<td>".$this->liv_titre."</td>";
    $ch.= "<td>".$this->liv_depotlegal."</td>";
    $ch.= "<td>".$this->nouveau."</td>";
    $ch.= "</tr></table>";
    return $ch;
  }

}

?>