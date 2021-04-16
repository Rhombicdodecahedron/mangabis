/********************************************** Page Gestion Annonce **************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "Gestion Annonce" de l'application
 *          web. Il permet de charger la page permettant de modifier/ajouter et supprimer ces annonces.
 * Auteur : Alexis Stella
 * Date :   13.03.2021 / V1.0
 */

/**
 * Cette fonction permet de charger la vue de la gestion des annonces de l'utilisateur.
 */
function loadAnnoncesPage() {
    if (window.userConnected.isConnected) {
        chargerVue("view", "annonces", function () {
            loadTableAnnonces();
            $.get(BASE_DOCUMENT + "js/views/components/addAnnonceModal.html", function (data) {
                $("#modals").append(data);
            });
            $.get(BASE_DOCUMENT + "js/views/components/updateAnnonceModal.html", function (data) {
                $("#modals").append(data);
            });
        });
    } else {
        erreur403();
    }
}

/**
 * Cette fonction permet de charger les annonces de l'utilisateurs dans le tableau avec comme id: listeAnnonces.
 */
function loadTableAnnonces() {
    getAnnoncesUtilisateur(window.userConnected.pk_utilisateur, function (data) {
        if (!data.error) {
            $(`#listeAnnonces`).empty();
            if (data.annonces.length > 0) {
                data.annonces.forEach(function (annonce) {
                    let html =
                        '<li class="list-group-item">' +
                        '<div class="row">' +
                        '<div class="col-2">' +
                        '<img class="img-fluid" src="' + (typeof annonce.images[0] !== "undefined" ? IMAGE_SERVER + annonce.images[0].image : DEFAULT_IMAGE) + '" alt="image annonce"  onerror="this.src=\'' + DEFAULT_IMAGE + '\';" >' +
                        '</div>' +
                        '<div class="col-10">' +
                        '<a href="' + BASE_DOCUMENT + 'annonce/' + annonce.pk_annonce + '" class="text-uppercase text-truncate">' + annonce.titre + '</a>' +
                        '<button class="ui inverted red button supprimerAnnonce" onClick="actionSupprimerAnnonce(' +
                        annonce.pk_annonce +
                        ')">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '<button class="ui inverted violet button modifierAnnonce" onClick="openModalModifyAnnonce(' + annonce.pk_annonce + ')"><i class="fas fa-edit"></i></button>' +
                        '<p/>' +
                        '<p>' + annonce.description + '</p>' +
                        '</div>' +
                        '</div>' +
                        '<p>' +
                        '</li>';
                    appendComponent("#listeAnnonces", html);
                })
            } else {
                $('#listeAnnonces').append(
                    '<li class="list-group-item">Vous n\'avez aucune annonce !</li>'
                );
            }
        } else {
            erreur(data);
        }
    });
}

/**
 * Cette fonction permet d'afficher les images selectionnées dans le gestionnaire de fichiers.
 * @param input représente les différentes images sélectionnées ou aucune.
 * @param id représente l'id de la div dans laquelle les images devront être affichées.
 */
function showImage(input, id) {
    $('#' + id).empty();
    for (let i = 0; i < input.files.length; i++) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $('#' + id).append(
                '<div class="col-4">' +
                '<img  src="' + e.target.result + '" class="img-fluid" alt="your image" />' +
                '</div>'
            )
        }
        reader.readAsDataURL(input.files[i]);
    }
}


//MODAL AJOUTER
$(document).on("click", "#browseImages", function () {
    var file = $(this)
        .parent()
        .parent()
        .parent()
        .find(".file");
    file.trigger("click");
});


$(document).on("change", "#images", function () {
    showImage(this, "imageListAddAnnonce");
    $(this)
        .parent()
        .find(".form-control")
        .val($(this).val().replace(/C:\\fakepath\\/i, ""));
});

//MODAL MODIFIER
$(document).on("click", "#browseImagesModifier", function () {
    var file = $(this)
        .parent()
        .parent()
        .parent()
        .find(".file");
    file.trigger("click");
});


$(document).on("change", "#modifierImages", function () {
    showImage(this, "imageListAddedModifyAnnonce");
    $(this)
        .parent()
        .find(".form-control")
        .val($(this).val().replace(/C:\\fakepath\\/i, ""));
});

/**
 * Cette fonction représente l'action d'ajouter une nouvelle annonce.
 */
function actionAddAnnonce() {
    let titre = document.getElementById("titre").value;
    let description = document.getElementById('description').value;
    let prix = document.getElementById('prix').value;
    let etat = document.getElementById('etat').value;
    let images = document.getElementById("images");

    let annonce = new Annonce();

    annonce.setTitre(titre);
    annonce.setDescription(description);
    annonce.setFkEtat(etat);
    annonce.setPrix(prix);
    annonce.setFkUtilisateur(window.userConnected.pk_utilisateur);

    let formData = new FormData();
    Array.from(images.files).forEach((file) => {
        formData.append('images[]', file);
    });

    formData.append('annonce', JSON.stringify(annonce));
    formData.append('action', "POST_ANNONCE");

    $('#modalAddAnnonce').modal('hide');
    addAnnonce(formData,
        function (data) {
            if (!data.error) {
                succes(data.message);
                loadAnnonces(function () {
                    loadConnecteNavbarSection();
                    loadTableAnnonces();
                });
            } else {
                erreur(data);
            }
        }
    );
}


/**
 * Cette fonction représente l'action de modifier une annonce.
 *
 * @param pk_annonce représente la pk de l'annonce à modifier.
 */
function actionModifierAnnonce(pk_annonce) {
    let titre = document.getElementById("modifierTitre").value;
    let description = document.getElementById('modifierDescription').value;
    let prix = document.getElementById('modifierPrix').value;
    let etat = document.getElementById('modifierEtat').value;


    let annonce = new Annonce();
    annonce.setPkAnnonce(pk_annonce);
    annonce.setTitre(titre);
    annonce.setDescription(description);
    annonce.setFkEtat(etat);
    annonce.setPrix(prix);
    annonce.setFkUtilisateur(window.userConnected.pk_utilisateur);
    annonce.setImages(
        window.annoncesList.filter(annonce => {
            if (annonce.pk_annonce == pk_annonce) {
                return annonce;
            }
        })[0].images
    );
    annonce.setImagesToDelete(
        window.annoncesList.filter(annonce => {
            if (annonce.pk_annonce == pk_annonce) {
                return annonce;
            }
        })[0].imagesToDelete
    )
    let formDataModify = new FormData();
    let images = document.getElementById("modifierImages");

    Array.from(images.files).forEach((file) => {
        formDataModify.append('images[]', file);
    });

    formDataModify.append('annonce', JSON.stringify(annonce));
    formDataModify.append('action', 'UPDATE_ANNONCE');

    $('#modalUpdateAnnonce').modal('hide');

    updateAnnonce(formDataModify,
        function (data) {
            if (!data.error) {
                succes(data.message);
                loadAnnonces(function () {
                    loadConnecteNavbarSection();
                    loadTableAnnonces();
                });
            } else {
                erreur(data);
            }
        }
    );
}


/**
 * Cette fonction représente l'action de supprimer une annonce.
 *
 * @param pk_annonce représente la pk de l'annonce à supprimer.
 */
function actionSupprimerAnnonce(pk_annonce) {
    deleteAnnonce(pk_annonce, function (data) {
        if (!data.error) {
            succes(data.message);
            loadAnnonces(function () {
                loadConnecteNavbarSection();
                loadTableAnnonces();
                loadTableAnnoncesZoneAdmin();
            });
        } else {
            erreur(data);
        }
    })
}

/**
 * Cette fonction permet d'ouvrir le modal de l'ajout d'annonce.
 */
function openModalAddAnnonce() {
    $('#modalAddAnnonce').modal('show');
}

/**
 * Cette fonction permet d'ouvrir le modal de modification d'une annonce en fonction de sa pk. Cette fonction va alors
 * remplir les champs avec les informations de l'annonce.
 *
 * @param pk_annonce représente la pk de l'annonce à modifier.
 */
function openModalModifyAnnonce(pk_annonce) {
    let annonce = window.annoncesList.filter(annonce => {
        if (annonce.pk_annonce == pk_annonce) {
            if (typeof annonce.getImagesToDelete() != "undefined") {
                annonce.setImagesToDelete(undefined);
            }
            return annonce;
        }
    })[0];

    document.getElementById("modifierTitre").value = annonce.titre;
    document.getElementById("modifierDescription").value = annonce.description;
    document.getElementById("modifierPrix").value = annonce.prix;
    document.getElementById("modifierEtat").value = annonce.etat.pk_etat;

    $('#imageListModifyAnnonce').empty();
    if (annonce.images.length > 0) {
        annonce.images.forEach(image => {
            $('#imageListModifyAnnonce').append(
                '<div class="col-4" id="imageListModifyAnnonce-' + image.pk_image + '">' +
                '<div class="ui fluid image">' +
                '<a class="ui red bottom right corner label" onclick="javascript:deleteCurrentImage(' + (image.pk_image) + ',' + (annonce.pk_annonce) + ')">' +
                '<i class="trash icon" style="cursor: pointer;"></i>' +
                '</a>' +
                '<img src="' + IMAGE_SERVER + image.image + '"/>\n' +
                '</div>' +
                '</div>'
            )
        })
    } else {
        $('#imageListModifyAnnonce').append('<p class="text-warning"><strong>   *Aucune image n\'a été trouvé !</strong></p>');
    }


    document.getElementById("modifyModal").setAttribute("action", "javascript:actionModifierAnnonce(" + annonce.pk_annonce + ")");

    $('#modalUpdateAnnonce').modal('show');
}

/**
 * Cette fonction permet d'ajouter une image désirant être supprimé dans la liste d'images à supprimer du bean de
 * l'annonce dont la pk est passé en paramètre.
 *
 * @param pk_image représente la pk de l'image souhaitant être supprimé.
 * @param pk_annonce représente la pk de l'annonce où l'image doit être supprimé.
 */
function deleteCurrentImage(pk_image, pk_annonce) {

    $('#imageListModifyAnnonce-' + pk_image).remove()
    window.annoncesList.filter(annonce => {
        if (annonce.pk_annonce == pk_annonce) {
            console.log(annonce.getImagesToDelete())
            if (typeof annonce.getImagesToDelete() == "undefined") {
                annonce.setImagesToDelete(annonce.images.filter(image => {
                    if (image.pk_image == pk_image) {
                        return image;
                    }
                }))
            } else {
                annonce.getImagesToDelete().push(annonce.images.filter(image => {
                    if (image.pk_image == pk_image) {
                        return image;
                    }
                }))
            }
        }
    })
}
