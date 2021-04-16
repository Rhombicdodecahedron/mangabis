<?php

/**
 * Classe Bean Etat
 * Bean de la table t_etat de la base de données.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class Etat
{
    private $pk_etat;
    private $etat;

    /**
     * Constructeur du bean "Etat".
     * @param $pk_etat . représente la pk de l'etat.
     * @param $etat . représente l'état.
     */
    public function __construct($pk_etat, $etat)
    {
        $this->pk_etat = $pk_etat;
        $this->etat = $etat;
    }

    /**
     * Cette méthode permet de convertir un bean sous forme de JSON.
     *
     * @return array le JSON du bean Etat.
     */
    public function jsonSerialize()
    {
        return
            [
                'pk_etat' => $this->getPkEtat(),
                'etat' => $this->getEtat()
            ];
    }

    /*SETTER & GETTER*/

    public function getPkEtat()
    {
        return $this->pk_etat;
    }

    public function setPkEtat($pk_etat)
    {
        $this->pk_etat = $pk_etat;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }
}