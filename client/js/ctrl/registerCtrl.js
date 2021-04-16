/********************************************** Page Register **********************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "register" de l'application
 *          web. Il permet de charger la page accessible seulement par les visiteurs et permettant de s'inscrire.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */

// Variable représentent le status de validation du ReCaptcha pour le register.
let isCaptchaValidateRegister = false;

/**
 * Cette fonction s'execute seulement quand le captcha à été validé et permet de mettre la variable
 * "isCaptchaValidateRegister" à true.
 */
function captchaRegister() {
    isCaptchaValidateRegister = true;
}


/**
 *  Cette fonction représente l'action de s'inscrire. Cette fonction va récupérer les informations dans les champs
 *  d'inscription et les envoyer au serveur. En cas d'erreur lors de l'inscription, une erreur sera affichées sinon une
 *  redirection vers la page de connexion sera faite.
 */
function actionInscriptionUtilisateur() {

    let nom = document.getElementById("nom").value;
    let prenom = document.getElementById("prenom").value;
    let email = document.getElementById("email").value;
    let telephone = document.getElementById("telephone").value;
    let password = document.getElementById("password").value;
    let password2 = document.getElementById("password2").value;

    if (password === password2) {
        if (isCaptchaValidateRegister) {
            let utilisateur = new Utilisateur();
            utilisateur.setNom(nom);
            utilisateur.setPrenom(prenom);
            utilisateur.setEmail(email);
            utilisateur.setTelephone(telephone);
            utilisateur.setMotDePasse(password);
            postUtilisateurInscription(JSON.stringify(utilisateur), function (data) {
                if (!data.error) {
                    window.location.replace('login');
                } else {
                    erreur(data);
                }
            });
        } else {
            erreur("Veuillez confirmer le recaptcha !");
        }
    } else {
        erreur("Veuillez mettre deux même mot de passe !");
    }
}
