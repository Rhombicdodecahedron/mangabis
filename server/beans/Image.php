<?php

/**
 * Classe Bean Image
 * Bean de la table t_image de la base de données.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class Image
{

    private $pk_image;
    private $image;
    private $fk_annonce;

    /**
     * Constructeur du bean "Image".
     *
     * @param $image . représente l'image
     * @param $fk_annonce . représente la pk de l'annonce qui est associée à l'image.
     */
    public function __construct($image, $fk_annonce)
    {
        $this->image = $image;
        $this->$fk_annonce = $fk_annonce;
    }

    /**
     * Cette méthode permet de convertir un bean sous forme de JSON.
     *
     * @return array le JSON du bean Image.
     */
    public function jsonSerialize()
    {
        return
            [
                'pk_image' => $this->getPkImage(),
                'image' => $this->getImage(),
            ];
    }

    /*SETTER & GETTER*/

    public function getPkImage()
    {
        return $this->pk_image;
    }

    public function setPkImage($pk_image)
    {
        $this->pk_image = $pk_image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getFkAnnonce()
    {
        return $this->fk_annonce;
    }

    public function setFkAnnonce($fk_annonce)
    {
        $this->fk_annonce = $fk_annonce;
    }
}