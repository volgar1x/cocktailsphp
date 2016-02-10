<?php
require_once ("LivreIterator.php");

class LivreIterator2 extends LivreIterator implements ArrayAccess {
  
  public function offsetExists($offset) {
    $testOffset = LivreMetier::initLivreMetier($offset);
    if (isset($testOffset)) 
      return true ;
    else 
      return false;
  }

  public function offsetGet($offset) {
    return LivreMetier::initLivreMetier($offset);
  }

  public function offsetSet($offset, $value) {
    // pas possible avec LivreIterator : update de livre ?
  }

  public function offsetUnset($offset) {
    // pas possible avec LivreIterator : suppression de livre ?
  }

}


/* test */

$li2 = new LivreIterator2();

echo $li2[5];
echo $li2[117]; 
echo $li2[5];

?>