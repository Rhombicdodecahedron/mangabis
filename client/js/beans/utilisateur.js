/*
 * Bean "Utilisateur".
 *
 * @author Alexis Stella
 * @project Mangabis
 * @version 1.0 / 12.03.2021
 */

/**
 * Constructeur du bean "Utilisateur".
 *
 * @constructor
 */
let Utilisateur = function () {
};

//SETTER
Utilisateur.prototype.setPkUtilisateur = function (pk_utilisateur) {
    this.pk_utilisateur = pk_utilisateur;
};
Utilisateur.prototype.setNom = function (nom) {
    this.nom = nom;
};
Utilisateur.prototype.setPrenom = function (prenom) {
    this.prenom = prenom;
};

Utilisateur.prototype.setMotDePasse = function (motdepasse) {
    this.motdepasse = motdepasse;
};
Utilisateur.prototype.setEmail = function (email) {
    this.email = email;
};

Utilisateur.prototype.setTelephone = function (telephone) {
    this.telephone = telephone;
};

Utilisateur.prototype.setConnected = function (isConnected) {
    this.isConnected = isConnected;
};
Utilisateur.prototype.setAdmin = function (isAdmin) {
    this.isAdmin = isAdmin;
};

//GETTER
Utilisateur.prototype.getPkUtilisateur = function () {
    return this.pk_utilisateur;
};
Utilisateur.prototype.toString = function () {
    return this.nom;
};

Utilisateur.prototype.getPrenom = function () {
    return this.prenom;
};

Utilisateur.prototype.isAdmin = function () {
    return this.isAdmin;
};

Utilisateur.prototype.isConnected = function () {
    return this.isConnected;
}