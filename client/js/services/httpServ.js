/**
 * But :    Ce fichier javascript est le service "httpServ" de l'application web. Il permet d'effectuer, à l'aide de
 *          différentes fonctions, des requêtes vers le serveur REST et de les retourner via des callbacks.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */


/**
 * Charge le fichier html dans la div avec l'id reçu en paramètre et charge le fichier html en fonction du nom reçu en paramètre.
 * Retourne un callback si tout s'est bien effectué.
 *
 * @param {*} id représente l'id de la div où doit être contenu la page html.
 * @param {*} vue représente le nom de la page html.
 * @param {*} callback représente l'appelle de retour si tout s'est bien effectué
 */
function chargerVue(id, vue, callback) {
    // charger la vue demandée
    $("#" + id).load(BASE_DOCUMENT + "js/views/" + vue + ".html", function () {
        // si une fonction de callback est spécifiée, on l'appelle ici
        if (typeof callback !== "undefined") {
            callback();
        }
    });
}

/**
 * Charge le fichier html dans la div avec l'id reçu en paramètre et charge le fichier html en fonction du nom reçu en paramètre.
 * Retourne un callback si tout s'est bien effectué.
 *
 * @param id représente l'id de la div où doit être contenu la page html
 * @param component représente le nom de la page html
 * @param callback représente l'appelle de retour si tout s'est bien effectué
 */
function chargerComponent(id, component, callback) {
    // charger le component demandé
    $("#" + id).load((BASE_DOCUMENT + "js/views/components/" + component + ".html"), function () {
        // si une fonction de callback est spécifiée, on l'appelle ici
        if (typeof callback !== "undefined") {
            callback();
        }
    });
}

/**
 * Rajoute le fichier html dans la div avec l'id reçu en paramètre et charge le fichier html en fonction du nom reçu en paramètre.
 * Retourne un callback si tout s'est bien effectué.
 *
 * @param id
 * @param html
 * @param callback représente l'appelle de retour si tout s'est bien effectué
 */
function appendComponent(id, html, callback) {
    // charger le component demandée
    $(id).append(html);
}


/********************************************** Annonce **************************************************/

/**
 * Envoie une requête GET_ANNONCES au serveur.
 *
 * @param callback représente l'appelle de retour si tout s'est bien effectué
 */
function getAnnonces(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=GET_ANNONCES",
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête GET_ANNONCES_UTILISATEUR au serveur.
 *
 * @param pk_utilisateur représente la pk de l'utilisateur.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function getAnnoncesUtilisateur(pk_utilisateur, callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=GET_ANNONCES_UTILISATEUR&pk_utilisateur=" + pk_utilisateur,
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête GET_ANNONCE au serveur.
 *
 * @param pk_annonce représente la pk de l'annonce.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function getAnnonce(pk_annonce, callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=GET_ANNONCE&pk_annonce=" + pk_annonce,
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête POST_ANNONCE au serveur.
 *
 * @param formData représente les informations à envoyer au serveur.
 * @param callback représente l'appelle de retour si tout s'est bien effectué
 */
function addAnnonce(formData, callback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SERVER_MAIN,
        data: formData,
        success: callback,
        error: callback,
        contentType: false,
        processData: false
    });
}

/**
 * Envoie une requête UPDATE_ANNONCE au serveur.
 *
 * @param formData représente les informations à envoyer au serveur.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function updateAnnonce(formData, callback) {
    $.ajax({
        type: /*"PUT"*/"POST",
        dataType: "json",
        url: SERVER_MAIN,
        data: formData,
        success: callback,
        error: callback,
        contentType: false,
        processData: false
    });
}

/**
 * Envoie une requête DELETE_ANNONCE au serveur.
 *
 * @param pk_annonce représente la pk de l'annonce à supprimer.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function deleteAnnonce(pk_annonce, callback) {
    $.ajax({
        type: "DELETE",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=DELETE_ANNONCE&pk_annonce=" + pk_annonce,
        success: callback,
        error: callback
    });
}


/********************************************** Utilisateur **************************************************/

/**
 * Envoie une requête POST_UTILISATEUR_INSCRIPTION au serveur.
 *
 * @param utilisateur représente l'objet de l'utilisateur au format JSON.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function postUtilisateurInscription(utilisateur, callback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=POST_UTILISATEUR_INSCRIPTION&utilisateur=" + utilisateur,
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête POST_UTILISATEUR_CONNEXION au serveur.
 *
 * @param utilisateur représente l'objet de l'utilisateur au format JSON.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function postUtilisateurConnexion(utilisateur, callback) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=POST_UTILISATEUR_CONNEXION&utilisateur=" + utilisateur,
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête GET_SESSION au serveur.
 *
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function getSession(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=GET_SESSION",
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête DELETE_SESSION au serveur.
 *
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function deleteSession(callback) {
    $.ajax({
        type: "DELETE",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=DELETE_SESSION",
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête UPDATE_UTILISATEUR au serveur.
 *
 * @param utilisateur représente l'objet de l'utilisateur au format JSON.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function updateUtilisateur(utilisateur, callback) {
    $.ajax({
        type: "PUT",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=UPDATE_UTILISATEUR&utilisateur=" + utilisateur,
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête GET_UTILISATEURS au serveur.
 *
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function getUtilisateurs(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=GET_UTILISATEURS",
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête DELETE_UTILISATEUR au serveur.
 *
 * @param pk_utilisateur représente la pk de l'utilisateur à supprimer.
 * @param callback représente l'appelle de retour si tout s'est bien effectué.
 */
function deleteUtilisateur(pk_utilisateur, callback) {
    $.ajax({
        type: "DELETE",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=DELETE_UTILISATEUR&pk_utilisateur=" + pk_utilisateur,
        success: callback,
        error: callback
    });
}

/**
 * Envoie une requête UPDATE_UTILISATEUR_ROLE au serveur.
 *
 * @param utilisateur représente l'objet de l'utilisateur au format JSON.
 * @param callback représente l'appelle de retour si tout s'est bien effectué
 */
function updateRoleUtilisateur(utilisateur, callback) {
    $.ajax({
        type: "PUT",
        dataType: "json",
        url: SERVER_MAIN,
        data: "action=UPDATE_UTILISATEUR_ROLE&utilisateur=" + utilisateur,
        success: callback,
        error: callback
    });
}














