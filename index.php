<?php

require_once "src/Metiers.php";
require_once "src/Vues.php";


/**
 * put here every services that needs some configuration or other set up
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
function setup() {
    Metiers::init("pgsql:host=localhost dbname=cocktailsphp user=antoinechauvin password=");
}



/**
 * your "controller"
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
function main() {
    $boissons = BoissonMetier::getAll();

    $currentPage = isset($_GET['page']) ? intval($_GET['page'], 10) : 1;

    layout(function() use ($boissons, $currentPage) {
        boissons($boissons, $currentPage);
    });
}


//////////////////////////////
setup();
main();
/////////////////////////////
