/************************************************ Navbar *************************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "navbar" de l'application
 *          web. Il permet de charger la barre de navigation avec les différentes informations qui la compose tel que
 *          l'utilisateur connecté ou la barre de recherche.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */

/**
 * Cette fonction premet de charger la barre de navigation tout en initialisant la barre de recherche et l'utilisateur
 * connecté ou non.
 */
function loadConnecteNavbarSection() {
    $('.ui.search').search({
        source: window.annoncesList,
        fields: {
            title: 'titre',
            image: '',
            price: 'prix'
        },
        searchFields: [
            'titre',
            'description'
        ], searchOnFocus: true,
        error: {
            noResults: 'Aucune annonce pour votre recherche !',
        },
        onSelect(result, response) {
            window.location.replace(BASE_DOCUMENT + 'annonce/' + result.pk_annonce);
        },
    });
    if (window.userConnected.isConnected) {
        if (window.userConnected.isAdmin) {
            chargerComponent("connexionSection", "connected_section_admin", function () {
                $('#navbarDropdown').text(window.userConnected.prenom + " " + window.userConnected.nom);
                $('#annonces').attr("href", BASE_DOCUMENT + "annonces");
                $('#liste-utilisateurs').attr("href", BASE_DOCUMENT + "liste-utilisateurs");
                $('#liste-annonces').attr("href", BASE_DOCUMENT + "liste-annonces");
                $('#profil').attr("href", BASE_DOCUMENT + "profil");
            });
        } else {
            chargerComponent("connexionSection", "connected_section", function () {
                $('#navbarDropdown').text(window.userConnected.prenom + " " + window.userConnected.nom);
                $('#annonces').attr("href", BASE_DOCUMENT + "annonces");
                $('#profil').attr("href", BASE_DOCUMENT + "profil");
            });
        }
    } else {
        chargerComponent("connexionSection", "non_connected_section", function () {
        })
    }

}

/**
 * Cette fonction représente l'action de se déconnecter de l'application web.
 */
function actionDeconnexionUtilisateur() {
    deleteSession(
        function (data) {
            window.location.replace(BASE_DOCUMENT);
            if (!data.error) {
                succes(data.message);
            } else {
                erreur(data);
            }
        }
    );

}