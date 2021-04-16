<?php

require_once("./wrk/WrkDatabase.php");
require_once("./beans/Etat.php");

/**
 * Classe WrkEtat
 * Cette classe permet de gérer les actions CRUD pour les états.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class WrkEtat
{
    private $database;

    /**
     * Constructeur du worker "WrkEtat". Ce constructeur initialise la variable database.
     */
    public function __construct()
    {
        $this->database = WrkDatabase::getInstance();
    }

    /**
     * Cette méthode permet de récupérer tous les états stockés dans la base de données.
     *
     * @return array représente la liste des états.
     */
    public function getEtats()
    {
        $listeEtats = array();

        $sql = "SELECT * FROM t_etat";
        $params = null;

        $req = $this->database->selectQuery($sql, $params);

        foreach ($req as $row) {
            $pk = $row['pk_etat'];
            $etat = $row['etat'];

            $etat = new Etat($pk, $etat);

            array_push($listeEtats, $etat->jsonSerialize());
        }
        return $listeEtats;
    }


    /**
     * Cette méthode permet de récupérer les informations d'un état en fonction de sa pk.
     *
     * @param $pk . représente la pk de l'état.
     * @return Etat|null l'objet "Etat" ou null si aucun état n'a été trouvé.
     */
    public function getEtat($pk)
    {
        $result = null;

        if (isset($pk) && $pk > 0) {
            $sql = "SELECT * FROM t_etat WHERE pk_etat=?";
            $params = array($pk);

            $row = $this->database->selectSingleQuery($sql, $params);
            $etat = $row['etat'];

            $etat = new Etat($pk, $etat);
            $result = $etat;
        }
        return $result;
    }
}