<?php

require_once "BoissonMetier.php";

/**
 * Helper class setting up every models connection to database
 *
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
class Metiers {
    public static function init($url) {
        $pdo = new PDO($url);
        BoissonMetier::init($pdo);
    }
}
