/********************************************** Page Annonce *********************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "Annonce" de l'application
 *          web. Il permet de charger la page contenant les informations d'une annonce.
 * Auteur : Alexis Stella
 * Date :   13.03.2021 / V1.0
 */


/**
 * Cette fonctionne permet de charger la page d'une annonce. L'annonce chargée sur cette page est en fonction du nombre
 * dans l'url qui représente la pk de l'annonce. Si l'annonce n'existe pas, une page d'erreur 404 sera affichée.
 */
function loadAnnoncePage() {
    let pageUser = window.location.href.split("/")[6].split('#')[0].split(".")[0];

    //Permet de charger la vue de base de la page annonce
    chargerVue("view", "annonce", function () {
        getAnnonce(pageUser, function (data) {
            if (!data.error) {
                let annonce = data.annonce
                $('#titreAnnonce').text(annonce.titre);
                $('#info-etat').text(annonce.etat.etat);
                $('#info-prix').text(annonce.prix + " CHF");
                $('#info-description').text(annonce.description);
                if (window.userConnected.isConnected) {
                    chargerComponent('infos-vendeur', "connected_info_user_annonce", function () {
                        $('#nom-vendeur').text(annonce.utilisateur.nom);
                        $('#prenom-vendeur').text(annonce.utilisateur.prenom);
                        $('#email-vendeur').text(annonce.utilisateur.email);
                        $('#telephone-vendeur').text(annonce.utilisateur.telephone);
                    });
                } else {
                    chargerComponent('infos-vendeur', "non_connected_info_user_annonce");
                }

                if (annonce.images.length > 0) {
                    annonce.images.forEach(image => {
                        $('#owl-annonce-images').append(
                            '<div className="item"><img class="img-fluid" src="' + IMAGE_SERVER + image.image + '" alt="' + image.image + ' onerror="this.src=\'' + DEFAULT_IMAGE + '\';"></div>'
                        );
                    })
                } else {
                    $('#owl-annonce-images').append(
                        '<div className="item"><img src="' + DEFAULT_IMAGE + '" alt="default image"></div>'
                    );
                }

                $("#owl-annonce-images").owlCarousel({
                    navigation: false, // Show next and prev buttons
                    slideSpeed: 300,
                    paginationSpeed: 400,
                    items: 1,
                    itemsDesktop: false,
                    itemsDesktopSmall: false,
                    itemsTablet: false,
                    itemsMobile: false,
                    navigationText: ["<", ">"]
                });
            } else {
                $('#pageAnnonce').empty();
                erreur(data);
            }
        });
    });
}
