/********************************************** Page Index **************************************************/

/**
 * But :    Ce fichier javascript est le controlleur principal de l'application web. Il permet de lancer les
 *          différentes pages en fonction de l'url et de géré la page index et ces actions.
 * Auteur : Alexis Stella
 * Date :   13.03.2021 / V1.0
 */

// Constante BASE_DOCUMENT,
const BASE_DOCUMENT = "/Mangabis/client/";

// Constante qui représente l'adresse du serveur.
const URL_API = "http://localhost:80/Mangabis/server/";

// Constante vers le main du serveur web.
const SERVER_MAIN = URL_API + "main.php";

// Constante qui représente l'adresse du serveur qui contient les images.
const IMAGE_SERVER = URL_API;

// Constante qui représente l'adresse de l'image par défault.
const DEFAULT_IMAGE = IMAGE_SERVER + "uploads/default/default.png";

// Constante PAGE, elle coupe l'url pour récupérer le nom de la page à charger
const PAGE = window.location.href.split("/")[5].split('#')[0].split(".")[0];

// Variable qui contient le bean de l'utilisateur connecté.
var userConnected;

// Variable qui contient la liste des annonces de la base de données.
var annoncesList;


/**
 * Permet d'effectuer les éléments se trouvant dans cette fonction seulement si la page est totalement chargé.
 */
$(document).ready(function () {
    console.log("Chargement des scripts !")
    $.when(
        $.getScript(BASE_DOCUMENT + "js/ctrl/annonceCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/gestionAnnoncesCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/loginCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/registerCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/navbarCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/profilCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/listeUtilisateursCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/ctrl/listeAnnoncesCtrl.js"),
        $.getScript(BASE_DOCUMENT + "js/services/httpServ.js"),
        $.Deferred(function (deferred) {
            $(deferred.resolve);
        })
    ).done(function () {
        console.log("Scripts chargés !")
        start();
    });
});

/**
 * Cette fonction permet de charger la session utilisateur. Si un utilisateur est connecté, un bean avec ces informations
 * sera créé, sinon, un bean avec seulement le champs isConnected sera mis à false.
 *
 * @param callback permet faire un retour quand la fonction à fini son travail.
 */
function loadUtilisateur(callback) {
    getSession(
        function (data) {
            window.userConnected = new Utilisateur()
            if (!data.error) {
                window.userConnected.setPkUtilisateur(data.utilisateur.pk_utilisateur);
                window.userConnected.setNom(data.utilisateur.nom);
                window.userConnected.setPrenom(data.utilisateur.prenom);
                window.userConnected.setEmail(data.utilisateur.email);
                window.userConnected.setTelephone(data.utilisateur.telephone);
                window.userConnected.setAdmin(data.utilisateur.estAdmin)
                window.userConnected.setConnected(true);
            } else {
                window.userConnected.setConnected(false);
            }
            if (typeof callback !== "undefined") {
                callback();
            }
        }
    );
}

/**
 * Cette fonction permet de charger la liste des annonces stockées dans la base de données. Pour chaque annonce, un bean
 * sera créé et ajouté dans la liste des annonces.
 *
 * @param callback permet faire un retour quand la fonction à fini son travail.
 */
function loadAnnonces(callback) {
    getAnnonces(
        function (data) {
            if (!data.error) {
                window.annoncesList = [];
                let annonces = data.annonces;
                if (annonces != null && annonces.length > 0) {
                    annonces.forEach((annonce) => {
                        let tempAnnonce = new Annonce();
                        tempAnnonce.setDateCreation(annonce.date_creation);
                        tempAnnonce.setDescription(annonce.description);
                        tempAnnonce.setObjetEtat(annonce.etat);
                        tempAnnonce.setImages(annonce.images);
                        tempAnnonce.setPkAnnonce(annonce.pk_annonce);
                        tempAnnonce.setPrix(annonce.prix);
                        tempAnnonce.setTitre(annonce.titre)
                        tempAnnonce.setObjetUtilisateur(annonce.utilisateur);
                        window.annoncesList.push(tempAnnonce)
                    });
                }
            } else {
                erreur(data);
            }
            if (typeof callback !== "undefined") {
                callback();
            }
        }
    )
}

/**
 * Cette méthode permet en fonction de l'url, choisir la vue à afficher.
 */
function start() {
    loadUtilisateur(function () {
        loadAnnonces(function () {
            //charge la navbar
            chargerComponent("navbar", "navbar", function () {
                loadConnecteNavbarSection();
            });
            //charge le footer
            chargerComponent("footer", "footer", function () {
                $("#annee").text(new Date().getFullYear());
            });

            // Ce bout de code permet, en fonction de l'url, de lancer la bonne vue
            switch (PAGE) {
                case "" :
                    return loadPageIndex();
                case "index":
                    return loadPageIndex();
                case "login":
                    if (!window.userConnected.isConnected) {
                        loadPage("view", "login");
                    } else {
                        loadPageIndex();
                    }
                    break;
                case "register":
                    if (!window.userConnected.isConnected) {
                        loadPage("view", "register");
                    } else {
                        loadPageIndex();
                    }
                    break;
                case "annonces":
                    return loadAnnoncesPage();
                case "annonce":
                    return loadAnnoncePage();
                case "profil":
                    return loadProfilPage();
                case "liste-utilisateurs":
                    return loadListeUtilisateursPage();
                case "liste-annonces":
                    return loadListAnnoncesPage();
                default:
                    return loadPage("view", "404");
            }
        });
    });

}

/**
 * Cette fonction permet de charger une vue en fonction de l'id vers laquelle la lancer et le nom du fichier html à charger.
 *
 * @param id représente l'id vers laquelle lancer la vue.
 * @param page représente le nom du fichier html.
 */
function loadPage(id, page) {
    chargerVue(id, page);
}

/**
 * Cette fonction permet de charger les annonces contenues dans une liste sous forme de cartes.
 *
 * @param annonces représente la liste des annonce.
 */
function loadAnnonceCards(annonces) {
    $("#listeAnnonces").empty();
    if (annonces != null && annonces.length > 0) {
        annonces.forEach(function (annonce) {
            let html =
                '<div class="col-lg-4">' +
                '<div class="card item-listeAnnonces">' +
                '<img class="card-img-top img-fluid thumb-post" src="' + (typeof annonce.images[0] !== "undefined" ? IMAGE_SERVER + annonce.images[0].image : DEFAULT_IMAGE) + '"  alt="image annonce"  onerror="this.src=\'' + DEFAULT_IMAGE + '\';"  >' +
                '<div class="card-body">' +
                '<h5 class="card-title">' + annonce.titre + '</h5>' +
                '<p class="card-text text-muted"><i class="fas fa-tags"></i> ' + annonce.prix + ' CHF</p>' +
                '<a class="btn btn-primary" href="' + BASE_DOCUMENT + 'annonce/' + annonce.pk_annonce + '">Voir l\'annonce</a>' +
                '</div>' +
                '</div>' +
                '</div>';
            appendComponent("#listeAnnonces", html);
        });

        //Écouteur qui permet lors d'un changememt, de trier les annonces.
        $("#filtreEtatCombo").change(() => {
            let selectedValue = document.getElementById("filtreEtatCombo").value;
            loadAnnonceCards(
                selectedValue > 0 ?
                    window.annoncesList.filter(annonce => {
                            if (annonce.etat.pk_etat == selectedValue) {
                                return annonce;
                            }
                        }
                    )
                    : window.annoncesList
            )
        });

        //Écouteur qui permet lors d'un changememt, de trier les annonces.
        $('#filtrePrixCombo').change(() => {
            let selectedValuePrix = document.getElementById("filtrePrixCombo").value;
            let newOrder = window.annoncesList;
            switch (selectedValuePrix) {
                case "0":
                    loadAnnonceCards(window.annoncesList);
                    break;
                case "1":
                    loadAnnonceCards(
                        newOrder.sort(function (a, b) {
                            return a.prix.localeCompare(b.prix);
                        }));
                    break;
                case "2":
                    loadAnnonceCards(
                        newOrder.sort(function (a, b) {
                            return a.prix.localeCompare(b.prix);
                        }).reverse());
                    break;
                case "3":
                    loadAnnonceCards(
                        newOrder.sort(function (a, b) {
                            return a.titre.localeCompare(b.titre);
                        }));
                    break;
                case "4":
                    loadAnnonceCards(
                        newOrder.sort(function (a, b) {
                            return a.titre.localeCompare(b.titre);
                        }).reverse());
                    break;
            }
        });
    } else {
        appendComponent("#listeAnnonces", "<h5 class='text-warning'><strong>Aucune annonce de manga trouvé</strong></h5>")
    }
}


/**
 *  Cette fonction permet lors du chargement de la vue index de lancer la méthode "loadAnnonceCards".
 */
function loadPageIndex() {
    chargerVue("view", "index", () => loadAnnonceCards(window.annoncesList));
}


/********************************************** Gestion Erreur/Succès *************************************************/

/**
 * Affiche une erreur avec le message reçu en paramètre
 *
 * @param {*} erreur représente le message d'erreur
 */
function erreur(erreur) {
    //affiche le message d'erreur
    let html =
        '<div class="ui negative message">' +
        '<i class="close icon text-muted" onclick="$(this).parent().remove()"></i>' +
        '<div class="header">' +
        'Erreur !' +
        '</div>\n' +
        '<p>' +
        (typeof erreur.message == "undefined" ? (typeof erreur.responseJSON == "undefined" ? erreur : erreur.responseJSON.message) : erreur.message) +
        '</p>' +
        '</div>';
    appendComponent('#alertErreurSucces', html);
}

function erreur403() {
    erreur("Vous n'avez pas accès à cette page !");
}

/**
 * Affiche un succès avec le message reçu en paramètre
 *
 * @param {*} succes représente le message de succès.
 */
function succes(succes) {
    //affiche le message de succès

    let html =
        '<div class="ui positive  message">' +
        '<i class="close icon text-muted" onclick="$(this).parent().remove()"></i>' +
        '<div class="header">' +
        'Succès !' +
        '</div>\n' +
        '<p>' +
        succes +
        '</p>' +
        '</div>';
    appendComponent('#alertErreurSucces', html);
}


