<?php
require_once("LivreMetier.php");

class LivreIterator implements Iterator, Countable {

  protected $idLivre = 1;
  
  public function count() {
    return LivreMetier::getNbLivres();
  }

  public function current() {
    return LivreMetier::initLivreMetier($this->idLivre);
  }

  public function key() {
    return $this->idLivre;
  }

  public function next() {
    $this->idLivre = $this->idLivre+1;
  }
   
  public function rewind (  ) {
    $this->idLivre = 1;
  }
  
  public function valid () {
    return $this->idLivre>0 && $this->idLivre<=$this->count();
  }

}

/* tests */
/* $literator = new LivreIterator(); */
/* echo "<ul>"; */
/* foreach ($literator as $livre) */
/*   echo "<li>".$literator->key()." ".$literator->current()." ".$livre->toJSON()."</li>"; */
/* echo "</ul>"; */
/* echo "<br/<br/><br/>"; */
/* $literator->rewind(); */
/* $literator->next(); */
/* $literator->next(); */
/* $literator->next(); */
/* $literator->next(); */
/* echo "<ol>"; */
/* while ($literator->valid()) { */
/*   echo "<li>".$literator->key()." ".$literator->current()."</li>"; */
/*   $literator->next(); */
/* } */
/* echo "</ol>"; */

/* $pageCourante = new LimitIterator($literator,0,7); */

/* echo "<ul>"; */
/* foreach ($pageCourante as $val) { */
/*   echo "<li>"; */
/*   echo $pageCourante->key()." ".$val->toJSON(); */
/*   echo "</li> "; */
/* } */
/* echo "</ul>"; */


?>