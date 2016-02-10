<?php
require_once("LivreIterator.php");

class LivreMetierAggregate implements IteratorAggregate {

  public function getIterator() {
    return new LivreIterator();
  }


}

$lma = new LivreMetierAggregate();
$literator = $lma->getIterator();
echo "<ul>";
foreach ($literator as $livre)
  echo "<li>".$literator->key()." ".$literator->current()." ".$livre->toJSON()."</li>";
echo "</ul>";

?>