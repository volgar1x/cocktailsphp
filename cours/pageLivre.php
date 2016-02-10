<?php
//require_once("LivreIterator.php");
function __autoload($classname) {
    $filename = "./". $classname .".php";
    require($filename);
}
session_start();

if (isset($_GET['reset'])) {
  session_unset();
  session_destroy();
}

if (! isset($_SESSION['collection'])) {
  $_SESSION['collection'] = new LivreIterator();
  $_SESSION['debut'] = 1;
  $_SESSION['taillePage'] = 10;
}

if(isset($_GET['suivant'])) {
  $_SESSION['debut'] += $_GET['suivant']*$_SESSION['taillePage'];
}

echo "<p>**** ".count($_SESSION['collection'])." ****</p>";
echo "<p>**** ".$_SESSION['debut']." -- ".$_SESSION['taillePage']." ****</p>";

$pageCourante = new LimitIterator($_SESSION['collection'],$_SESSION['debut']-1,$_SESSION['taillePage']);

echo "<ul>";
foreach ($pageCourante as $val) {
  echo "<li>";
  echo $pageCourante->key()." ".$val->toJSON();
  echo "</li> ";
}
echo "</ul>";

$decalageFirst =  (int)(-1 *$_SESSION['debut']/$_SESSION['taillePage']);
$decalageLast =  (int)((count($_SESSION['collection'])-$_SESSION['debut'])/$_SESSION['taillePage']);
$decalagePrev = ($_SESSION['debut']==1)?0:-1;
$decalageNext = ($_SESSION['debut']+$_SESSION['taillePage']>count($_SESSION['collection']))?0:1;

$urlFirst = $_SERVER['PHP_SELF']."?suivant=".$decalageFirst;
$urlPrev = $_SERVER['PHP_SELF']."?suivant=".$decalagePrev;
$urlNext = $_SERVER['PHP_SELF']."?suivant=".$decalageNext;
$urlLast = $_SERVER['PHP_SELF']."?suivant=".$decalageLast;

echo "<p><a href='$urlFirst'>First</a> <a href='$urlPrev'>prev</a> <a href='$urlNext'>next</a> <a href='$urlLast'>Last</a></p>";

$urlReset = $_SERVER['PHP_SELF']."?reset";
echo "<p><a href='$urlReset'>Reset</a></p>";




?>