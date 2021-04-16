<?php

/**
 * Classe Bean Utilisateur
 * Bean de la table t_utilisateur de la base de données.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class Utilisateur
{
    private $pk_utilisateur;
    private $nom;
    private $prenom;
    private $email;
    private $telephone;
    private $estAdmin;
    private $motdepasse;
    private $date_creation;

    /**
     * Constructeur du bean "Utilisateur".
     * @param $nom . représente le nom de l'utilisteur.
     * @param $prenom . représente le prénom de l'utilisteur.
     * @param $email . représente l'email de l'utilisteur.
     * @param $telephone . représente le téléphone de l'utilisteur.
     */
    public function __construct($nom, $prenom, $email, $telephone)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
    }


    /**
     * Cette méthode permet de convertir un bean sous forme de JSON.
     *
     * @return array le JSON du bean Utilisateur.
     */
    public function jsonSerialize()
    {
        return
            [
                'pk_utilisateur' => $this->getPkUtilisateur(),
                'nom' => $this->getNom(),
                'prenom' => $this->getPrenom(),
                'email' => $this->getEmail(),
                'telephone' => $this->getTelephone(),
                'estAdmin' => $this->isAdmin() == 1,
                'date_creation' => $this->getDateCreation()
            ];
    }

    /**
     * Cette méthode permet de convertir un bean sous forme de JSON sans le rôle de l'utilisateur et sa date de création.
     *
     * @return array le JSON du bean Utilisateur.
     */
    public function jsonSerializeSimplified()
    {
        return
            [
                'pk_utilisateur' => $this->getPkUtilisateur(),
                'nom' => $this->getNom(),
                'prenom' => $this->getPrenom(),
                'email' => $this->getEmail(),
                'telephone' => $this->getTelephone()
            ];
    }


    /*SETTER & GETTER*/

    public function getPkUtilisateur()
    {
        return $this->pk_utilisateur;
    }

    public function setPkUtilisateur($pk_utilisateur)
    {
        $this->pk_utilisateur = $pk_utilisateur;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    public function isAdmin()
    {
        return $this->estAdmin;
    }

    public function setIsAdmin($estAdmin)
    {
        $this->estAdmin = $estAdmin;
    }

    public function getMotdepasse()
    {
        return $this->motdepasse;
    }

    public function setMotdepasse($motdepasse)
    {
        $this->motdepasse = $motdepasse;
    }

    public function getDateCreation()
    {
        return $this->date_creation;
    }

    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }
}