<?php
require_once("./wrk/WrkDatabase.php");
require_once("./beans/Image.php");

/**
 * Classe WrkImage
 * Cette classe permet de gérer les actions CRUD pour les images.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class WrkImage
{
    const UPLOAD_PATH = "uploads/";
    private $database;

    /**
     * Constructeur du worker "WrkImage". Ce constructeur initialise la variable database.
     */
    public function __construct()
    {
        $this->database = WrkDatabase::getInstance();
    }

    /**
     * Cette méthode permet de lister toutes les images que possède une annonce.
     *
     * @param $pk_annonce . représente la pk de l'annonce que nous voulons récupérer les images.
     * @return array représente la liste des images de l'annonce.
     */
    public function getImagesAnnonce($pk_annonce)
    {
        $result = array();
        if (isset($pk_annonce) && $pk_annonce > 0) {
            $req = $this->database->selectQuery("SELECT * FROM t_image WHERE fk_annonce=?", array($pk_annonce));
            foreach ($req as $row) {
                $pk_image = $row['pk_image'];
                $image = $row['image'];
                $fk_annonce = $row['fk_annonce'];

                $image = new Image($image, $fk_annonce);
                $image->setPkImage($pk_image);

                array_push($result, $image->jsonSerialize());
            }

        }
        return $result;
    }

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
        $result = false;
        if (isset($image, $tmp_name, $type, $pk_annonce) && $pk_annonce > 0) {
            $ready = true;
            if (!$this->database->isInTransaction()) {
                $ready = $this->database->startTransaction();
            }
            if ($ready) {
                try {
                    $newImageName = $this->renameImage($image);
                    $advertisementFolder = self::UPLOAD_PATH . $pk_annonce . "/";
                    $query = "INSERT INTO t_image (image, fk_annonce) VALUES (?,?)";
                    $params = array(htmlspecialchars($advertisementFolder . $newImageName), htmlspecialchars($pk_annonce));
                    if ($this->database->addQueryToTransaction($query, $params)) {
                        if (!file_exists($advertisementFolder)) {
                            @mkdir($advertisementFolder, 0777);
                        }
                        $result = move_uploaded_file($tmp_name, $advertisementFolder . $newImageName);
                    }
                } catch (Exception $e) {
                    $this->database->rollbackTransaction();
                }
            }
        }
        return $result;
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
        $result = false;
        if (isset($image_path) && !empty($image_path)) {
            $pk_image = $this->database->selectSingleQuery("SELECT * FROM t_image WHERE image=?", array($image_path))['pk_image'];
            if ($pk_image > 0) {
                $ready = true;
                if (!$this->database->isInTransaction()) {
                    $ready = $this->database->startTransaction();
                }
                if ($ready) {
                    try {
                        $sql = "DELETE FROM t_image WHERE pk_image=?";
                        $params = array($pk_image);
                        if ($this->database->addQueryToTransaction($sql, $params)) {
                            $this->rmrf($image_path);
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
     * Cette méthode permet de supprimer toutes les images d'une annonce. Pour ce faire, son dossier qui contient
     * toutes les images sere supprimé.
     *
     * @param $pk_annonce . représente la pk de l'annonce où nous voulons supprimer les images.
     * @return bool true si la suppression du dossier de l'annonce s'est correctement effectuée ou false.
     */
    public function deleteAllImagesAnnonce($pk_annonce)
    {
        $result = false;
        if (isset($pk_annonce) && $pk_annonce > 0) {
            try {
                $this->rmrf(self::UPLOAD_PATH . $pk_annonce);
                $result = true;
            } catch (Exception $e) {
            }
        }
        return $result;
    }

    /**
     * Cette méthode permet de supprimer un dossier ou un fichier en fonction de son chemin.
     *
     * @param $dir . représente le dossier ou le fichier à supprimer.
     */
    function rmrf($dir)
    {
        foreach (glob($dir) as $file) {
            if (is_dir($file)) {
                $this->rmrf("$file/*");
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }

    /**
     * Cette méthode permet de renommer une image en lui ajoutant le nom quand elle a été ajouté.
     *
     * @param $baseImage . représente le nom de l'image à renommer.
     * @return string le nouveau nom de l'image.
     */
    public function renameImage($baseImage)
    {
        $randomNum = time();
        $imageName = str_replace(' ', '-', strtolower($baseImage));
        $imageExt = str_replace('.', '', substr($imageName, strrpos($imageName, '.')));
        $imageName = preg_replace("/\.[^.\s]{3,4}$/", "", $imageName);

        return $imageName . '-' . $randomNum . '.' . $imageExt;
    }
}