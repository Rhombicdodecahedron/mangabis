/********************************************** Profil Utilisateur *************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "profil" de l'application
 *          web. Il permet de charger la page de modification de l'utilisateur et ainsi, lui donner accès à modifier son
 *          profil. Toutefois, si l'utilisateur n'est pas connecté, un message d'erreur sera affiché.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */


/**
 * Cette fonction permet de charger la page de modification de profil de l'utilisateur connecté. Si un visiteur tente
 * de venir sur cette page, un message d'erreur sera affiché.
 */
function loadProfilPage() {

    if (window.userConnected.isConnected) {
        chargerVue("view", "profil", function () {
            document.getElementById("nom").value = window.userConnected.nom;
            document.getElementById("prenom").value = window.userConnected.prenom;
            document.getElementById("telephone").value = window.userConnected.telephone;
            document.getElementById("email").value = window.userConnected.email;
            const profilModification = document.getElementById("profilModification");
            profilModification.addEventListener("submit", (e) => {
                e.preventDefault();
                let utilisateur = new Utilisateur();
                if (password.value === password2.value || (password.value.length === 0 && password2.value.length === 0)) {
                    utilisateur.setPkUtilisateur(window.userConnected.pk_utilisateur);
                    utilisateur.setPrenom(prenom.value);
                    utilisateur.setNom(nom.value);
                    utilisateur.setTelephone(telephone.value);
                    utilisateur.setEmail(email.value);
                    utilisateur.setMotDePasse(password.value === password2.value && password.value.length > 0 && password2.value.length > 0 ? password.value : null);
                    updateUtilisateur(JSON.stringify(utilisateur), function (data) {
                        if (!data.error) {
                            start();
                            succes(data.message);
                        } else {
                            erreur(data);
                        }
                    })
                } else {
                    erreur("Veuillez, soit ne rien mettre dans les champs mot de passe pour ne pas le modifier, ou mettre le même mot de passe dans les deux champs pour le changer !")
                }
            });
        });
    } else {
        erreur403();
    }
}




