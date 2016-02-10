<?php
require_once ("LivreIterator.php");

class LivreIterator3 extends LivreIterator implements SeekableIterator {
  
  public function seek($position) {
    if ($position<0 || $position>$this->count()) {
      throw new OutOfBoundsException("position invalide ($position)");
    }

    $this->idLivre = $position;  
  }
  
}
/* tests */
try {
  $literator3 = new LivreIterator3();
  $literator3->seek(100);
  echo $literator3->current();
  $literator3->seek(200);
} catch (OutOfBoundsException $e) {
    echo $e->getMessage();
}

?>