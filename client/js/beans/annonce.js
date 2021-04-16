/*
 * Bean "Annonce".
 *
 * @author Alexis Stella
 * @project Mangabis
 * @version 1.0 / 12.03.2021
 */

/**
 * Constructeur du bean "Annonce".
 *
 * @constructor
 */
var Annonce = function () {
};

//GETTER
Annonce.prototype.getTitre = function () {
    return this.titre;
};

Annonce.prototype.getPkAnnonce = function () {
    return this.pk_annonce;
}

Annonce.prototype.getDescription = function () {
    return this.description;
};

Annonce.prototype.getPrix = function () {
    return this.prix;
};

Annonce.prototype.getDescription = function () {
    return this.description;
};

Annonce.prototype.getImages = function () {
    return this.images;
};

Annonce.prototype.getImagesToDelete = function () {
    return this.imagesToDelete;
};

Annonce.prototype.getFKEtat = function () {
    return this.fkEtat;
};

Annonce.prototype.getObjetEtat = function () {
    return this.etat;
};

Annonce.prototype.getFKUtilisateur = function () {
    return this.fkUtilisateur;
};

Annonce.prototype.getObjetUtilisateur = function () {
    return this.utilisateur;
};
Annonce.prototype.getDateCreation = function () {
    return this.date_creation;
};

//SETTER

Annonce.prototype.setTitre = function (titre) {
    this.titre = titre;
};

Annonce.prototype.setPkAnnonce = function (pk_annonce) {
    this.pk_annonce = pk_annonce;
};
Annonce.prototype.setDescription = function (description) {
    this.description = description;
};

Annonce.prototype.setImages = function (images) {
    this.images = images;
};
Annonce.prototype.setImagesToDelete = function (imagesToDelete) {
    this.imagesToDelete = imagesToDelete;
};

Annonce.prototype.setPrix = function (prix) {
    this.prix = prix;
};

Annonce.prototype.setDateCreation = function (date_creation) {
    this.date_creation = date_creation;
};

Annonce.prototype.setFkEtat = function (fkEtat) {
    this.fkEtat = fkEtat;
};
Annonce.prototype.setObjetEtat = function (etat) {
    this.etat = etat;
};

Annonce.prototype.setFkUtilisateur = function (fkUtilisateur) {
    this.fkUtilisateur = fkUtilisateur;
};

Annonce.prototype.setObjetUtilisateur = function (utilisateur) {
    this.utilisateur = utilisateur;
};





