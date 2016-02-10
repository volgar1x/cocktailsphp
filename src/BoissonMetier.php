<?php

/**
 * Handles data persistence of table "public.boisson"
 *
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
class BoissonMetier {
    private static $pdo;

    private static $qselectall;
    private static $qinsert;
    private static $qupdate;
    private static $qdelete;

    public static function init($pdo) {
        self::$pdo = $pdo;

        self::$qselectall = $pdo->prepare('select boi_id, boi_nom, boi_degreAlcool from boisson');
        self::$qinsert = $pdo->prepare('insert into boisson (boi_nom, boi_degreAlcool) values (:nom, :degreAlcool) returning boi_id');
        self::$qupdate = $pdo->prepare('update boisson set boi_nom=:nom, boi_degreAlcool=:degreAlcool where boi_id=:id');
        self::$qinsert = $pdo->prepare('delete from boisson where boi_id=:id');
    }

    public static function getAll() {
        self::$qselectall->execute();
        $lines = self::$qselectall->fetchAll();

        $result = [];
        foreach ($lines as $line) {
            $result[] = new BoissonMetier(
                $line[0],
                $line[1],
                $line[2],
                true
            );
        }
        return $result;
    }

    public static function getArrayIterator() {
        return new ArrayIterator(self::getAll());
    }

    public static function getLimitIterator($offset, $count) {
        // really, really, really, bad...
        return new LimitIterator(self::getArrayIterator(), $offset, $count);
    }

    public static function build($nom, $degreAlcool) {
        return new BoissonMetier(0, $nom, $degreAlcool, false);
    }

    private $id;
    private $nom;
    private $degreAlcool;

    private $persisted;

    public function getId() { return $this->id; }

    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }

    public function getDegreAlcool() { return $this->degreAlcool; }
    public function setDegreAlcool($degreAlcool) { $this->degreAlcool = $degreAlcool; }

    private function __construct($id, $nom, $degreAlcool, $persisted) {
        $this->id = $id;
        $this->nom = $nom;
        $this->degreAlcool = $degreAlcool;
        $this->persisted = $persisted;
    }

    public function save() {
        if ($this->persisted) {
            self::$qupdate->bindValue(':id', $this->id);
            self::$qupdate->bindValue(':nom', $this->nom);
            self::$qupdate->bindValue(':degreAlcool', $this->degreAlcool);
            self::$qupdate->execute();
            return true;
        } else {
            self::$qinsert->bindValue(':nom', $this->nom);
            self::$qinsert->bindValue(':degreAlcool', $this->degreAlcool);
            $this->id = self::$qinsert->fetchColumn();
            $this->persisted = true;
            return true;
        }
    }

    public function delete() {
        self::$qdelete->bindValue(':id', $this->id);
        self::$qdelete->execute();
        $this->id = 0;
        $this->persisted = false;
        return true;
    }
}
