<?php

/**
 * Classe Bean Annonce
 * Bean de la table t_annonce de la base de données.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */

class Annonce
{
    private $pk_annonce;
    private $titre;
    private $description;
    private $prix;
    private $utilisateur;
    private $etat;
    private $date_creation;
    private $images;

    /**
     * Constructeur du bean "Annonce".
     *
     * @param $titre . représente le titre de l'annonce.
     * @param $description . représente la description de l'annonce.
     * @param $prix . représente le prix de l'annonce.
     * @param $date_creation . représente la date de création de l'annonce.
     */
    public function __construct($titre, $description, $prix, $date_creation)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
        $this->date_creation = $date_creation;
    }


    /**
     * Cette méthode permet de convertir un bean sous forme de JSON.
     *
     * @return array le JSON du bean Annonce.
     */
    public function jsonSerialize()
    {
        return
            [
                'pk_annonce' => $this->getPkAnnonce(),
                'titre' => $this->getTitre(),
                'description' => $this->getDescription(),
                'prix' => $this->getPrix(),
                'etat' => $this->getEtat(),
                'utilisateur' => $this->getUtilisateur(),
                'date_creation' => $this->getDateCreation(),
                'images' => $this->getImages()
            ];
    }

    /*SETTER & GETTER*/

    public function getPkAnnonce()
    {
        return $this->pk_annonce;
    }

    public function setPkAnnonce($pk_annonce)
    {
        $this->pk_annonce = $pk_annonce;
    }


    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    public function getDateCreation()
    {
        return $this->date_creation;
    }

    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }


}