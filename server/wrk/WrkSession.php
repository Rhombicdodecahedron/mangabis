<?php
require_once("./beans/Utilisateur.php");
require_once("./wrk/Wrk.php");
require_once("./wrk/WrkDatabase.php");


/**
 * Classe WrkSession
 * Cette classe permet de gérer la session.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class WrkSession
{

    private $database;

    /**
     * Constructeur du worker "WrkSession". Ce constructeur initialise la variable database et débute la session.
     */
    public function __construct()
    {
        session_start();
        $this->database = WrkDatabase::getInstance();
    }

    /**
     * Cette méthode permet de recevoir l'objet utilisateur de la personne connecté ou null si aucune ne l'est.
     *
     * @return Utilisateur|null l'objet de l'utilisateur connecté ou null si aucun utilisateur n'est connecté.
     */
    public function currentUser()
    {
        $result = null;
        if (!empty($_SESSION)) {
            $id = $_SESSION['pk_utilisateur'];
            $email = $_SESSION['email'];
            $nom = $_SESSION['nom'];
            $prenom = $_SESSION['prenom'];
            $telephone = $_SESSION['telephone'];
            $estAdmin = $_SESSION['estAdmin'];
            $date_creation = $_SESSION['date_creation'];

            $utilisateur = new Utilisateur($nom, $prenom, $email, $telephone);

            $utilisateur->setIsAdmin($estAdmin);
            $utilisateur->setPkUtilisateur($id);
            $utilisateur->setDateCreation($date_creation);
            $result = $utilisateur;
        }
        return $result;
    }

    /**
     * Cette méthode permet de détruire la session de l'utilisateur actuellement connecté.
     *
     * @return bool true si la session à correctement été supprimé ou false.
     */
    public function destroySession()
    {
        $this->database->__destruct();
        return session_destroy();
    }

    /**
     * Cette méthode permet de dire si une session est ouverte et alors dire si l'utilisateur est connecté.
     *
     * @return bool true si l'utilisateur est connecté ou false.
     */
    public function isConnected()
    {
        return !empty($_SESSION);
    }

    /**
     * Cette méthode permet de tester si l'utilisateur de la session est un admin ou non.
     *
     * @return bool true si l'utilisateur est un admin ou false.
     */
    public function isAdmin()
    {
        $result = false;
        if ($this->isConnected()) {
            $utilisateur = $this->currentUser();
            $result = $utilisateur->isAdmin();
        }
        return $result;
    }

    /**
     * Cette méthode permet de créer une nouvelle session utilisateur avec les informations passées en paramètre.
     *
     * @param  $pk_utilisateur . représente la pk de l'utilisateur.
     * @param  $nom . représente le nom de l'utilisateur.
     * @param  $prenom . représente le prénom de l'utilisateur.
     * @param  $email . représente l'email de l'utilisateur.
     * @param  $telephone . représente le numéro de téléphone de l'utilisateur.
     * @param  $estAdmin . représente le rôle de l'utilisateur.
     * @param  $date_creation . représente la date de création de l'utilisateur.
     */
    public function openSession($pk_utilisateur, $nom, $prenom, $email, $telephone, $estAdmin, $date_creation)
    {
        $_SESSION['pk_utilisateur'] = $pk_utilisateur;
        $_SESSION['email'] = $email;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['telephone'] = $telephone;
        $_SESSION['estAdmin'] = $estAdmin;
        $_SESSION['date_creation'] = $date_creation;
    }
}