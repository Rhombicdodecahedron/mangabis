<?php

require_once("./wrk/WrkDatabase.php");
require_once("./beans/Utilisateur.php");
require_once("./wrk/Wrk.php");

/**
 * Classe WrkUtilisateur
 * Cette classe permet de gérer les actions CRUD pour les utilisateur.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class WrkUtilisateur
{
    private $database;
    private $refWrk;

    /**
     * Constructeur du worker "WrkUtilisateur". Ce constructeur initialise la variable database et reçoit l'instance
     * du worker principale.
     * @param Wrk $wrk représente l'instance du worker principal.
     */
    public function __construct(Wrk $wrk)
    {
        $this->database = WrkDatabase::getInstance();
        $this->refWrk = $wrk;
    }

    /**
     * Cette méthode permet de modifier un utilisateur via les informations passées en paramètre.
     *
     * @param $utilisateur . représente les informations de l'utilisateur à modifier.
     * @return bool true si la modification a correctement été effectué ou false.
     */
    public function updateUtilisateur($utilisateur)
    {
        $result = false;
        if (isset($utilisateur)) {
            $utilisateurData = json_decode($utilisateur);
            if (isset($utilisateurData->pk_utilisateur, $utilisateurData->nom, $utilisateurData->prenom, $utilisateurData->email, $utilisateurData->telephone, $utilisateurData->motdepasse)) {

                $pk_utilisateur = $utilisateurData->pk_utilisateur;

                $nom = htmlspecialchars($utilisateurData->nom);
                $prenom = htmlspecialchars($utilisateurData->prenom);
                $email = htmlspecialchars($utilisateurData->email);
                $telephone = htmlspecialchars($utilisateurData->telephone);
                $password = htmlspecialchars($utilisateurData->motdepasse);

                if ($this->refWrk->isConnected() && $this->refWrk->currentUser()->getPkUtilisateur() == $pk_utilisateur) {
                    if (strlen($password) > 0) {
                        $sql = "UPDATE t_utilisateur SET nom=?, prenom=?, email=?, telephone=?, motdepasse=? WHERE pk_utilisateur=?";
                        $params = array($nom, $prenom, $email, $telephone, password_hash($password, PASSWORD_DEFAULT), $pk_utilisateur);
                    } else {
                        $sql = "UPDATE t_utilisateur SET nom=?, prenom=?, email=?, telephone=? WHERE pk_utilisateur=?";
                        $params = array($nom, $prenom, $email, $telephone, $pk_utilisateur);
                    }
                    if ($this->database->startTransaction()) {
                        try {
                            if ($this->database->addQueryToTransaction($sql, $params)) {
                                $this->refWrk->openSession($pk_utilisateur, $nom, $prenom, $email, $telephone, $this->refWrk->currentUser()->isAdmin(), $this->getUtilisateur($pk_utilisateur)->getDateCreation());
                                $result = $this->database->commitTransaction();
                            }
                        } catch (Exception $e) {
                            $this->database->rollbackTransaction();
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Cette méthode permet de modifier le rôle d'un utilisateur.
     *
     * @param $utilisateur . représente le json contenant la pk de l'utilisateur à modifier et le nouveau role true ou
     * admin ou false.
     * @return bool true la modification a correctement été effectuée ou false.
     */
    public function updateRoleUtilisateur($utilisateur)
    {
        $result = false;
        if (isset($utilisateur)) {
            $utilisateurData = json_decode($utilisateur);
            if (isset($utilisateurData->pk_utilisateur, $utilisateurData->isAdmin)) {
                $pk_utilisateur = htmlspecialchars($utilisateurData->pk_utilisateur);
                $estAdmin = $utilisateurData->isAdmin ? 1 : 0;
                if ($this->refWrk->isConnected() && $this->refWrk->isAdmin()) {
                    if ($pk_utilisateur > 0) {
                        if ($this->database->startTransaction()) {
                            try {
                                $sql = "UPDATE t_utilisateur SET estAdmin=? WHERE pk_utilisateur=?";
                                $params = array($estAdmin, $pk_utilisateur);
                                if ($this->database->addQueryToTransaction($sql, $params)) {
                                    $result = $this->database->commitTransaction();
                                }
                            } catch (Exception $e) {
                                $this->database->rollbackTransaction();
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }


    /**
     * Cette méthode permet d'inscrire un utilisateur à la base de données. Si l'inscription a correctement été effectuée,
     * le visiteur pourra se connecter pour accéder aux fonctionnalitées qui lui sont disponibles.
     *
     * @param $data . représente les informations de l'utilisateur.
     * @return bool true si l'inscription a correctement été effectuée ou false.
     */
    public function addUtilisateur($data)
    {
        $result = false;
        if (isset($data)) {
            $array = json_decode($data);
            if (isset($array->motdepasse, $array->nom, $array->prenom, $array->email, $array->telephone)) {
                if ($this->database->startTransaction()) {
                    try {
                        //MDP
                        $password = password_hash($array->motdepasse, PASSWORD_DEFAULT);

                        //Injection JS/HTML
                        $nom = htmlspecialchars($array->nom);
                        $prenom = htmlspecialchars($array->prenom);
                        $email = htmlspecialchars($array->email);
                        $telephone = htmlspecialchars($array->telephone);
                        //USER
                        $params = array($nom, $prenom, $email, $telephone, 0, $password);
                        $sql = "INSERT INTO t_utilisateur (nom, prenom, email, telephone, estAdmin, motdepasse) VALUES (?,?,?,?,?,?)";

                        if ($this->database->addQueryToTransaction($sql, $params)) {
                            $this->database->commitTransaction();
                            $result = true;
                        }

                    } catch (Exception $e) {
                        $this->database->rollbackTransaction();
                    }
                }
            }
        }


        return $result;
    }

    /**
     * Cette méthode permet de tester si le mot de passe et le login passés en paramètre correspondent à un utilisateur
     * dans la base de données. S'ils correspondent, une session sera créée avec les informations de l'utilisateur.
     *
     * @param $data . représente les informations de l'utilisateur à connecter.
     * @return bool true si le login et le mot de passe correspondent.
     */
    public function isUtilisateur($data)
    {
        $result = false;
        if (isset($data)) {
            $array = json_decode($data);
            //USER
            $sql = "SELECT * FROM t_utilisateur WHERE email=?";
            $params = array($array->email);
            $utilisateur = $this->database->selectSingleQuery($sql, $params);

            //PWD
            if (password_verify($array->motdepasse, $utilisateur['motdepasse'])) {
                $this->refWrk->openSession($utilisateur['pk_utilisateur'], $utilisateur['nom'], $utilisateur['prenom'], $utilisateur['email'], $utilisateur['telephone'], $utilisateur['estAdmin'], $utilisateur['date_creation']);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Cette méthode permet de tester si un utilisateur avec l'adresse email passée en paramètre existe.
     *
     * @param $email . représente l'email à tester.
     * @return bool true si l'utilisateur existe ou false.
     */
    public function utilisateurExistEmail($email)
    {
        return $email != null && $this->database->getNumberSelectedQuery("SELECT * FROM t_utilisateur WHERE email=?", array(htmlspecialchars($email))) > 0;
    }

    /**
     * Cette méthode permet de vérifier si un utilisateur existe dans la base de données en fonction d'une pk.
     *
     * @param $pk . représente la pk de l'utilisateur à rechercher.
     * @return bool true si l'utilisateur existe ou false.
     */
    public function checkUserExistByPK($pk)
    {
        return $pk > 0 && $this->database->getNumberSelectedQuery("SELECT * FROM t_utilisateur WHERE pk_utilisateur=?", array(htmlspecialchars($pk))) > 0;
    }


    /**
     * Cette méthode permet de lister tous les utilisateurs stockés dans la base de données.
     *
     * @return array la liste de tous les utilisateurs stockés dans la base de données.
     */
    public function getUtilisateurs()
    {
        $results = array();
        if ($this->refWrk->isConnected() && $this->refWrk->isAdmin()) {
            $req = $this->database->selectQuery('SELECT * FROM t_utilisateur', null);
            foreach ($req as $row) {
                $utilisateur = new Utilisateur($row['nom'], $row['prenom'], $row['email'], $row['telephone']);
                $utilisateur->setPkUtilisateur($row['pk_utilisateur']);
                $utilisateur->setIsAdmin($row['estAdmin']);
                $utilisateur->setDateCreation($row['date_creation']);
                array_push($results, $utilisateur->jsonSerialize());
            }
        }
        return $results;
    }

    /**
     * Cette méthode permet de retourner l'objet Utilisateur en fonction de la pk passée en paramètre.
     *
     * @param $pk . représente la pk de l'utilisateur.
     * @return Utilisateur|null l'objet Utilisateur ou null si aucun utilisateur se trouvant dans la base de données
     * possède la pk reçu en paramètre.
     */
    public function getUtilisateur($pk)
    {
        $result = null;

        if (isset($pk) && $pk > 0) {
            $sql = "SELECT * FROM t_utilisateur WHERE pk_utilisateur=?";
            $params = array($pk);
            $row = $this->database->selectSingleQuery($sql, $params);

            $nom = $row['nom'];
            $prenom = $row['prenom'];
            $email = $row['email'];
            $telephone = $row['telephone'];
            $date_creation = $row['date_creation'];
            $getUtilisateur = new Utilisateur($nom, $prenom, $email, $telephone);
            $getUtilisateur->setDateCreation($date_creation);
            $getUtilisateur->setPkUtilisateur($pk);

            $result = $getUtilisateur;
        }

        return $result;
    }

    /**
     * Cette méthode permet de supprimer un utilisateur de la base de données en fonction de sa pk. Les annonces et les
     * images de cet utilisateur seront aussi supprimées.
     *
     * @param $pk_utilisateur . représente la pk de l'utilisateur à supprimer.
     * @return bool true si la suppression s'est correctement effectuée ou false.
     */
    public function deleteUtilisateur($pk_utilisateur)
    {
        $result = false;
        if ($this->refWrk->isConnected() && $this->refWrk->isAdmin()) {
            if (isset($pk_utilisateur) && $pk_utilisateur > 0) {
                if ($this->database->startTransaction()) {
                    try {
                        $annonces = $this->database->selectQuery("SELECT * FROM t_annonce WHERE fk_utilisateur=?", array($pk_utilisateur));
                        $sql = "DELETE t_utilisateur FROM t_utilisateur 
                                LEFT OUTER JOIN t_annonce
    	                            ON t_utilisateur.pk_utilisateur=t_annonce.fk_utilisateur 
                                LEFT OUTER JOIN t_image
    	                            ON t_image.fk_annonce=t_annonce.pk_annonce
                                 WHERE pk_utilisateur=?";
                        $params = array($pk_utilisateur);
                        if ($this->database->addQueryToTransaction($sql, $params)) {
                            $imagesDeleted = true;
                            foreach ($annonces as $annonce) {
                                if (!$this->refWrk->deleteAllImagesAnnonce($annonce['pk_annonce'])) {
                                    $imagesDeleted = false;
                                }
                            }
                            if ($imagesDeleted) {
                                $result = $this->database->commitTransaction();
                            }
                        }
                    } catch (Exception $e) {
                        $this->database->rollbackTransaction();
                    }
                }
            }
        }
        return $result;
    }
}