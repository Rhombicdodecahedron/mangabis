<?php

require_once("./wrk/WrkAnnonce.php");
require_once("./wrk/WrkUtilisateur.php");
require_once("./wrk/WrkSession.php");
require_once("./wrk/WrkImage.php");
require_once("./wrk/WrkEtat.php");


/**
 * Classe Wrk
 * Cette classe permet de contacter les sous-worker.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class Wrk
{

    private $wrkAnnonce;
    private $wrkUtilisateur;
    private $wrkSession;
    private $wrkImage;
    private $wrkEtat;

    /**
     * Constructeur de la classe worker "Wrk". Il initialise les workers : wrkAnnonce, wrkUtilisateur,wrkSession, wrkImage
     * et wrkEtat.
     */
    public function __construct()
    {
        $this->wrkAnnonce = new WrkAnnonce($this);
        $this->wrkUtilisateur = new WrkUtilisateur($this);
        $this->wrkSession = new WrkSession();
        $this->wrkImage = new WrkImage();
        $this->wrkEtat = new WrkEtat();
    }

    //---------------------------ANNONCE------------------------------------------

    /**
     * Cette méthode permet de lister toutes les annonces se trouvant dans la base de données en fonction de la pk de
     * l'utilisateur ou null si nous voulons recevoir toutes les annonces.
     *
     * @param $pk représente la pk de l'utilisateur ou null si nous voulons recevoir toutes les annonces.
     * @return array la liste de toutes les annonces.
     */
    public function getAnnonces($pk)
    {
        return $this->wrkAnnonce->getAnnonces($pk);
    }

    /**
     * Cette méthode permet de récupérer une annonce en fonction de la pk reçu en paramètre. Elle va alors l'afficher sous la
     * forme d'un JSON.
     *
     * @param $data . représente la pk de l'annonce.
     * @return Annonce .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 200.
     */
    public function getAnnonce($data)
    {
        return $this->wrkAnnonce->getAnnonce($data);
    }

    /**
     * Cette méthode permet d'ajouter une nouvelle annonce dans la base de données.
     *
     * @param $data . représente les informations de l'annonce à ajouter.
     * @param $images . représente la/les images à ajouter pour l'annonce. Une annonce peut avoir aucune image.
     * @return bool true si l'ajout s'est correctement été effectué.
     */
    public function addAnnonce($data, $images)
    {
        return $this->wrkAnnonce->addAnnonce($data, $images);
    }

    /**
     * Cette méthode permet de supprimer une annonce de la base de données en fonction d'une pk.
     *
     * @param int $pk_annonce représente la pk de l'annonce à supprimer.
     * @return bool true si la suppression s'est bien effectuée ou false.
     */
    public function deleteAnnonce(int $pk_annonce)
    {
        return $this->wrkAnnonce->deleteAnnonce($pk_annonce);
    }

    /**
     * Cette méthode permet de tester si l'annonce reçu en paramètre à comme vendeur l'utilisateur connecté.
     *
     * @param $pk_annonce . représente la pk de l'annonce à tester.
     * @return bool true si l'utilisateur connecté est le créateur de l'annonce ou false.
     */
    public function estUtilisateurAnnonce($pk_annonce)
    {
        return $this->wrkAnnonce->estUtilisateurAnnonce($pk_annonce);
    }

    /**
     * Cette méthode permet de modifier une annonce se trouvant dans la base de données.
     *
     * @param $data . représente les informations de l'annonce à modifier sous forme de json.
     * @param $images . représente les nouvelles images à ajouter à l'annonce.
     * @return bool true si la modification s'est effectuée ou false.
     */
    public function updateAnnonce($data, $images)
    {
        return $this->wrkAnnonce->updateAnnonce($data, $images);
    }


    //---------------------------UTILISATEUR--------------------------------------

    /**
     * Cette méthode permet d'inscrire un utilisateur à la base de données. Si l'inscription a correctement été effectuée,
     * le visiteur pourra se connecter pour accéder aux fonctionnalitées qui lui sont disponibles.
     *
     * @param $data . représente les informations de l'utilisateur.
     * @return bool true si l'inscription a fonctionné ou false.
     */
    public function addUtilisateur($data)
    {
        return $this->wrkUtilisateur->addUtilisateur($data);

    }

    /**
     * Cette méthode permet de tester si le mot de passe et le login passés en paramètre correspondent à un utilisateur
     * dans la base de données. Si ils correspondent, une session sera créée avec les informations de l'utilisateur.
     *
     * @param $data . représente les informations de l'utilisateur à connecter.
     * @return bool true si le login et le mot de passe correspondent.
     */
    public function isUtilisateur($data)
    {
        return $this->wrkUtilisateur->isUtilisateur($data);
    }

    /**
     * Cette méthode permet de modifier un utilisateur via les informations passées en paramètre.
     *
     * @param $utilisateur . représente les informations de l'utilisateur à modifier.
     * @return bool true si la modification a correctement été effectué ou false.
     */
    public function updateUtilisateur($utilisateur)
    {
        return $this->wrkUtilisateur->updateUtilisateur($utilisateur);
    }

    /**
     * Cette méthode permet de tester si un utilisateur avec l'adresse email passée en paramètre existe.
     *
     * @param $email . représente l'email à tester.
     * @return bool true si l'utilisateur existe ou false.
     */
    public function utilisateurExistEmail($email)
    {
        return $this->wrkUtilisateur->utilisateurExistEmail($email);
    }

    /**
     * Cette méthode permet de vérifier si un utilisateur existe dans la base de données en fonction d'une pk.
     *
     * @param $pk . représente la pk de l'utilisateur à rechercher.
     * @return bool true si l'utilisateur existe ou false.
     */
    public function checkUserExistByPK($pk)
    {
        return $this->wrkUtilisateur->checkUserExistByPK($pk);
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
        return $this->wrkUtilisateur->getUtilisateur($pk);
    }

    /**
     * Cette méthode permet de lister tous les utilisateurs stockés dans la base de données.
     *
     * @return array la liste de tous les utilisateurs stockés dans la base de données.
     */
    public function getUtilisateurs()
    {
        return $this->wrkUtilisateur->getUtilisateurs();
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
        return $this->wrkUtilisateur->deleteUtilisateur($pk_utilisateur);
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
        return $this->wrkUtilisateur->updateRoleUtilisateur($utilisateur);
    }

    //---------------------------SESSION-----------------------------------------

    /**
     * Cette méthode permet de détruire la session de l'utilisateur actuellement connecté.
     *
     * @return bool true si la session à correctement été supprimé ou false.
     */
    public function destroySession()
    {
        return $this->wrkSession->destroySession();
    }

    /**
     * Cette méthode permet de dire si une session est ouverte et alors dire si l'utilisateur est connecté.
     *
     * @return bool true si l'utilisateur est connecté ou false.
     */
    public function isConnected()
    {
        return $this->wrkSession->isConnected();
    }

    /**
     * Cette méthode permet de recevoir l'objet utilisateur de la personne connecté ou null si aucune ne l'est.
     *
     * @return Utilisateur|null l'objet de l'utilisateur connecté ou null si aucun utilisateur n'est connecté.
     */
    public function currentUser()
    {
        return $this->wrkSession->currentUser();
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
        $this->wrkSession->openSession($pk_utilisateur, $nom, $prenom, $email, $telephone, $estAdmin, $date_creation);

    }

    /**
     * Cette méthode permet de tester si l'utilisateur de la session est un admin ou non.
     *
     * @return bool true si l'utilisateur est un admin ou false.
     */
    public function isAdmin()
    {
        return $this->wrkSession->isAdmin();
    }


    //---------------------------IMAGE-------------------------------------

    /**
     * Cette méthode permet d'ajouter une image en lien avec une annonce à la base de données. L'image sera ensuite
     * ajoutée dans un dossier de même nom que la pk de l'annonce.
     *
     * @param $image . représente le nom de l'image.
     * @param $tmp_name . représente le chemin temporaire de l'image.
     * @param $type . représente le type de l'image.
     * @param $pk_annonce . représente la pk de l'annonce en lien avec l'image.
     * @return bool true si l'ajout a correctement été effectué ou false.
     */
    public function addImage($image, $tmp_name, $type, $pk_annonce)
    {
        return $this->wrkImage->addImage($image, $tmp_name, $type, $pk_annonce);
    }

    /**
     * Cette méthode permet de lister toutes les images que possède une annonce.
     *
     * @param $pk_annonce . représente la pk de l'annonce que nous voulons récupérer les images.
     * @return array représente la liste des images de l'annonce.
     */
    public function getImagesAnnonce($pk_annonce)
    {
        return $this->wrkImage->getImagesAnnonce($pk_annonce);
    }

    /**
     * Cette méthode permet de supprimer toutes les images d'une annonce. Pour ce faire, son dossier qui contient
     * toutes les images sere supprimé.
     *
     * @param $pk_annonce . représente la pk de l'annonce où nous voulons supprimer les images.
     * @return bool true si la suppression du dossier de l'annonce s'est correctement effectuée ou false.
     */
    public function deleteAllImagesAnnonce($pk_annonce)
    {
        return $this->wrkImage->deleteAllImagesAnnonce($pk_annonce);
    }

    /**
     * Cette méthode permet de supprimer de la base de données une image tout en la supprimer du dossier de l'annonce
     * en lien avec l'image.
     *
     * @param $image_path . représente le chemin de l'image.
     * @return bool true si la suppression s'est correctement effectuée ou false.
     */
    public function deleteImage($image_path)
    {
        return $this->wrkImage->deleteImage($image_path);
    }

    //---------------------------Etat---------------------------------------

    /**
     * Cette méthode permet de récupérer tous les états stockés dans la base de données.
     *
     * @return array représente la liste des états.
     */
    public function getEtats()
    {
        return $this->wrkEtat->getEtats();
    }

    /**
     * Cette méthode permet de récupérer les informations d'un état en fonction de sa pk.
     *
     * @param $pk . représente la pk de l'état.
     * @return Etat|null l'objet "Etat" ou null si aucun état n'a été trouvé.
     */
    public function getEtat($pk)
    {
        return $this->wrkEtat->getEtat($pk);
    }
}