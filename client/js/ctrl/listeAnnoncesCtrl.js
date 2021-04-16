/********************************************** Page Liste Annonce *****************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "listeAnnonce" de l'application
 *          web. Il permet de charger la page accessible seulement par les administrateurs permettant de gérer les
 *          annonces de tous les utilisateurs.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */


/**
 * Cette fonction permet de charger la page de gestion des annonces de tous les utilisateurs.
 */
function loadListAnnoncesPage() {
    if (window.userConnected.isConnected && window.userConnected.isAdmin) {
        chargerVue("view", "liste-annonces", function () {
            loadTableAnnoncesZoneAdmin();
        });
    } else {
        erreur403();
    }
}

/**
 * Cette fonction permet de charger le tableau contenant toutes les annonces de tous les utilisateurs.
 */
function loadTableAnnoncesZoneAdmin() {
    $(`#listeAnnoncesZoneAdmin`).empty();
    if (window.annoncesList.length > 0) {
        window.annoncesList.forEach(function (annonce) {
            $('#listeAnnoncesZoneAdmin').append(
                '<li class="list-group-item">' +
                '<p><strong>' + annonce.utilisateur.prenom + " " + annonce.utilisateur.nom + '</strong></p>' +
                '</li>' +
                '<li class="list-group-item">' +
                '<div class="row">' +
                '<div class="col-2">' +
                '<img class="img-fluid" src="' + (typeof annonce.images[0] !== "undefined" ? IMAGE_SERVER + annonce.images[0].image : DEFAULT_IMAGE) + '" alt="image annonce"  onerror="this.src=\'' + DEFAULT_IMAGE + '\';"  >' +
                '</div>' +
                '<div class="col-10">' +
                '<a href=' + BASE_DOCUMENT + 'annonce/' + annonce.pk_annonce + ' class="text-uppercase">' + annonce.titre + '</a>' +
                '<button class="ui inverted red button supprimerAnnonce" onClick="actionSupprimerAnnonce(' +
                annonce.pk_annonce +
                ')">' +
                '<i class="fas fa-trash-alt"></i>' +
                '</button>' +
                '<p/>' +
                '<p>' + annonce.description + '</p>' +
                '</div>' +
                '</div>' +
                '<p>' +
                '</li>'
            );
        })
    } else {
        $('#listeAnnoncesZoneAdmin').append(
            '<li class="list-group-item">Aucune annonce n\'est dans la base de données !</li>'
        );
    }
}
