/********************************************** Page Liste Annonce *****************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "listeUtilisateur" de l'application
 *          web. Il permet de charger la page accessible seulement par les administrateurs permettant de gérer les
 *          utilisateurs.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */


/**
 * Cette fonction permet de charger la page "liste-utilisateur". Si l'utilisateur n'est pas connecté et n'est pas
 * admin, une erreur sera alors affichée. Ensuite la liste des utilisateurs stockés dans la base de données sera affichée.
 */
function loadListeUtilisateursPage() {
    if (window.userConnected.isConnected && window.userConnected.isAdmin) {
        chargerVue("view", "liste-utilisateurs", function () {
            getUtilisateurs(function (data) {
                $(`#listeUtilisateurs`).empty();
                if (data.utilisateurs.length > 0) {
                    data.utilisateurs.forEach(function (utilisateur) {
                        let html =
                            '<li class="list-group-item">' +
                            '<div class="row">' +
                            '<div class="col-12">' +
                            '<p class="text-uppercase text-truncate"><strong>' + utilisateur.nom + " " + utilisateur.prenom + '</strong></p>' +
                            '<button class="ui inverted red button supprimerAnnonce" onClick="actionSupprimerUtilisateur(' +
                            utilisateur.pk_utilisateur +
                            ')">' +
                            '<i class="fas fa-trash-alt"></i>' +
                            '</button>' +
                            '<p/>' +
                            '<p class="text-muted"> Email : ' + utilisateur.email + '</p>' +
                            '<p class="text-muted"> Téléphone : ' + utilisateur.telephone + '</p>' +
                            '</div>' +
                            '</div>' +
                            '<p>' +
                            '<div class="mb-2 text-muted"><strong>* Changer le role de l\'utilisateur</strong></div>' +
                            '<div id="roleToggle-' + utilisateur.pk_utilisateur + '" class="ui toggle checkbox">' +
                            '<input type="checkbox"' + (utilisateur.estAdmin ? 'checked="checked"' : "") + '>' +
                            '<label></label>' +
                            '</div>' +
                            '</p>' +
                            '</li>';
                        appendComponent("#listeUtilisateurs", html);
                        $('#roleToggle-' + utilisateur.pk_utilisateur).checkbox({
                            onChecked: () => actionUpdateRoleUtilisateur(true, utilisateur.pk_utilisateur),
                            onUnchecked: () => actionUpdateRoleUtilisateur(false, utilisateur.pk_utilisateur)
                        });
                    })
                } else {
                    $('#listeUtilisateurs').append(
                        '<li class="list-group-item">Aucun utilisateur n\'est dans la base de données !</li>'
                    );
                }
            });
        });
    } else {
        erreur403();
    }
}

/**
 * Cette fonction représente l'action de modifier le rôle d'un utilisteur en fonction de sa pk qui est donnée en paramètre.
 *
 * @param estAdmin représente la valeur du role. True si admin ou false.
 * @param pk_utilisateur représente la pk de l'utilisateur où le rôle doit être changé.
 */
function actionUpdateRoleUtilisateur(estAdmin, pk_utilisateur) {
    let utilisateur = new Utilisateur();
    utilisateur.setAdmin(estAdmin);
    utilisateur.setPkUtilisateur(pk_utilisateur);
    updateRoleUtilisateur(JSON.stringify(utilisateur), function (data) {
        if (!data.error) {
            succes(data.message);
        } else {
            erreur(data);
        }
    });
}

/**
 * Cette fonction représente l'action de supprimer un utilisteur.
 *
 * @param pk_utilisateur représente la pk de l'utilisateur à supprimer.
 */
function actionSupprimerUtilisateur(pk_utilisateur) {
    deleteUtilisateur(pk_utilisateur,
        function (data) {
            if (!data.error) {
                succes(data.message);
                start();
            } else {
                erreur(data);
            }
        }
    );
}
