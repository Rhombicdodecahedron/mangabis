<?php
require_once("./wrk/WrkDatabase.php");
require_once("./beans/Annonce.php");
require_once("./wrk/Wrk.php");

/**
 * Classe WrkAnnonce
 * Cette classe permet de gérer les actions CRUD pour les annonces.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class WrkAnnonce
{
    private $database;
    private $refWrk;

    /**
     * Constructeur du worker "WrkAnnonce". Ce constructeur initialise la variable database et reçoit l'instance
     * du worker principale.
     * @param Wrk $wrk représente l'instance du worker principal.
     */
    public function __construct(Wrk $wrk)
    {
        $this->refWrk = $wrk;
        $this->database = WrkDatabase::getInstance();
    }

    /**
     * Cette méthode permet de lister toutes les annonces se trouvant dans la base de données en fonction de la pk de
     * l'utilisateur ou null si nous voulons recevoir toutes les annonces.
     *
     * @param $pk . représente la pk de l'utilisateur ou null si nous voulons recevoir toutes les annonces.
     * @return array la liste de toutes les annonces.
     */
    public function getAnnonces($pk)
    {
        $listeAnnonces = array();

        $sql = "SELECT * FROM t_annonce";
        $params = null;

        if (isset($pk) && $pk > 0) {
            $sql = "SELECT * FROM t_annonce WHERE fk_utilisateur=?";
            $params = array($pk);
        }

        $req = $this->database->selectQuery($sql, $params);

        foreach ($req as $row) {
            $pk = $row['pk_annonce'];
            $titre = $row['titre'];
            $description = $row['description'];
            $prix = $row['prix'];
            $fk_utilisateur = $row['fk_utilisateur'];
            $fk_etat = $row['fk_etat'];
            $date_creation = $row['date_creation'];

            $annonce = new Annonce($titre, $description, $prix, $date_creation);
            $annonce->setPkAnnonce($pk);
            $annonce->setImages($this->refWrk->getImagesAnnonce($pk));

            $etat = $this->refWrk->getEtat($fk_etat);
            $annonce->setEtat($etat->jsonSerialize());

            if ($this->refWrk->isConnected()) {
                $utilisateur = $this->refWrk->getUtilisateur($fk_utilisateur);
                $annonce->setUtilisateur($utilisateur->jsonSerializeSimplified());
            } else {
                $annonce->setUtilisateur(null);
            }

            array_push($listeAnnonces, $annonce->jsonSerialize());
        }
        return $listeAnnonces;
    }

    /**
     * Cette méthode permet retourner une annonce se trouvant dans la base de données en fonction d'un pk.
     *
     * @param $pk . représente la pk de l'annonce à rechercher.
     * @return Annonce l'annonce recherchée ou null en cas d'erreur ou d'annonce non-trouvée.
     */
    public function getAnnonce($pk)
    {
        $result = null;

        if (isset($pk) && $pk > 0) {
            if ($this->annonceExistPK($pk)) {
                $row = $this->database->selectSingleQuery("SELECT * FROM t_annonce WHERE pk_annonce=?", array($pk));
                $titre = $row['titre'];
                $description = $row['description'];
                $prix = $row['prix'];
                $fk_utilisateur = $row['fk_utilisateur'];
                $fk_etat = $row['fk_etat'];
                $date_creation = $row['date_creation'];

                $annonce = new Annonce($titre, $description, $prix, $date_creation);
                $annonce->setPkAnnonce($pk);
                $annonce->setImages($this->refWrk->getImagesAnnonce($pk));
                $etats = $this->refWrk->getEtat($fk_etat);
                if (isset($etats)) {

                    $annonce->setEtat($this->refWrk->getEtat($fk_etat) != null ? $this->refWrk->getEtat($fk_etat)->jsonSerialize() : null);
                    if ($this->refWrk->isConnected()) {
                        $utilisateur = $this->refWrk->getUtilisateur($fk_utilisateur);
                        $annonce->setUtilisateur($utilisateur->jsonSerializeSimplified());
                    } else {
                        $annonce->setUtilisateur(null);
                    }
                    $result = $annonce;
                }
            }
        }
        return $result;
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
        $result = false;
        if (isset($data)) {
            $array = json_decode($data);
            if (isset($array) && !empty($array)) {
                if ($this->database->startTransaction()) {
                    try {
                        $titre = htmlspecialchars($array->titre);
                        $description = htmlspecialchars($array->description);
                        $prix = htmlspecialchars($array->prix);
                        $fkUtilisateur = htmlspecialchars($array->fkUtilisateur);
                        $fkEtat = htmlspecialchars($array->fkEtat);
                        if (isset($titre, $description, $prix, $fkEtat, $fkUtilisateur)) {
                            $sql = "INSERT INTO t_annonce (titre, description, prix, fk_utilisateur ,fk_etat) VALUES (?,?,?,?,?)";
                            $params = array($titre, $description, $prix, $fkUtilisateur, $fkEtat);
                            if ($this->database->addQueryToTransaction($sql, $params)) {
                                $pk_annonce = $this->database->getLastId('t_annonce');
                                if (isset($images)) {
                                    $imagesAdd = false;
                                    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                                        $imagesAdd = $this->refWrk->addImage($_FILES['images']['name'][$i], $_FILES['images']['tmp_name'][$i], $_FILES['images']['type'][$i], $pk_annonce);
                                    }
                                    if ($imagesAdd) {
                                        $result = $this->database->commitTransaction();
                                    }
                                } else {
                                    $result = $this->database->commitTransaction();
                                }
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

    /**
     * Cette méthode permet de supprimer une annonce de la base de données en fonction d'une pk.
     *
     * @param int $pk_annonce représente la pk de l'annonce à supprimer.
     * @return bool true si la suppression s'est bien effectuée ou false.
     */
    public function deleteAnnonce($pk_annonce)
    {
        $result = false;
        if (isset($pk_annonce) && $pk_annonce > 0) {
            if ($this->database->startTransaction()) {
                try {
                    $sql = "DELETE t_annonce FROM t_annonce
                                LEFT OUTER JOIN t_image
                                    ON t_image.fk_annonce=t_annonce.pk_annonce
                            WHERE pk_annonce=?";
                    $params = array($pk_annonce);
                    if ($this->database->addQueryToTransaction($sql, $params)) {
                        if ($this->refWrk->deleteAllImagesAnnonce($pk_annonce)) {
                            $result = $this->database->commitTransaction();
                        }
                    }
                } catch (Exception $e) {
                    $this->database->rollbackTransaction();
                }
            }
        }
        return $result;
    }

    /**
     * Cette méthode permet de tester si une annonce avec la pk passée en paramètre existe déjà.
     *
     * @param $pk . représente la pk de l'annonce.
     * @return bool true si l'annonce existe déjà ou false.
     */
    public function annonceExistPK($pk)
    {
        return $pk > 0 && $this->database->getNumberSelectedQuery("SELECT * FROM t_annonce WHERE pk_annonce=?", array($pk)) > 0;
    }


    /**
     * Cette méthode permet de tester si l'annonce reçu en paramètre à comme vendeur l'utilisateur connecté.
     *
     * @param $pk_annonce . représente la pk de l'annonce à tester.
     * @return bool true si l'utilisateur connecté est le créateur de l'annonce ou false.
     */
    public function estUtilisateurAnnonce($pk_annonce)
    {
        $result = false;
        if (isset($pk_annonce) && $pk_annonce > 0) {
            if ($this->refWrk->isConnected()) {
                $utilisateur = $this->refWrk->currentUser();
                $annonce = $this->getAnnonce($pk_annonce);
                if ($annonce->getUtilisateur() != null) {
                    if ($annonce->getUtilisateur()['pk_utilisateur'] == $utilisateur->getPkUtilisateur()) {
                        $result = true;
                    }
                }
            }
        }
        return $result;
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
        $result = false;
        if (isset($data)) {
            $array = json_decode($data);
            if (isset($array) && !empty($array)) {
                if ($this->database->startTransaction()) {
                    try {
                        $imagesToDelete = isset($array->imagesToDelete) ? $array->imagesToDelete : [];
                        $pk_annonce = $array->pk_annonce;

                        $titre = htmlspecialchars($array->titre);
                        $description = htmlspecialchars($array->description);
                        $prix = htmlspecialchars($array->prix);
                        $fkEtat = htmlspecialchars($array->fkEtat);

                        if (isset($pk_annonce, $titre, $description, $prix, $fkEtat)) {
                            $sql = "UPDATE t_annonce SET titre=?, description=?, prix=?, fk_etat=? WHERE pk_annonce=?";
                            $params = array($titre, $description, $prix, $fkEtat, $pk_annonce);
                            if ($this->database->addQueryToTransaction($sql, $params)) {

                                $imageDeletedOk = true;
                                if (!empty($imagesToDelete)) {
                                    foreach ($imagesToDelete as $item) {
                                        if (!$this->refWrk->deleteImage($item->image)) {
                                            $imageDeletedOk = false;
                                        }
                                    }
                                }
                                if ($imageDeletedOk) {
                                    if (isset($images)) {
                                        $imagesAdd = false;
                                        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                                            $imagesAdd = $this->refWrk->addImage($_FILES['images']['name'][$i], $_FILES['images']['tmp_name'][$i], $_FILES['images']['type'][$i], $pk_annonce);
                                        }
                                        if ($imagesAdd) {
                                            $result = $this->database->commitTransaction();
                                        }
                                    } else {
                                        $result = $this->database->commitTransaction();
                                    }
                                }
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