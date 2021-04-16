/********************************************** Page Login **********************************************************/

/**
 * But :    Ce fichier javascript est le controlleur "login" de l'application
 *          web. Il permet de charger la page accessible seulement par les visiteurs et permettant de se connecter.
 * Auteur : Alexis Stella
 * Date :   12.03.2021 / V1.0
 */

// Variable représentent le status de validation du ReCaptcha pour le login.
let isCaptchaValidateLogin = false;

/**
 * Cette fonction s'execute seulement quand le captcha à été validé et permet de mettre la variable
 * "isCaptchaValidateLogin" à true.
 */
function captchaLogin() {
    isCaptchaValidateLogin = true;
}


/**
 *  Cette fonction représente l'action de se connecter. Cette fonction va récupérer les informations dans les champs
 *  de connexion et les envoyer au serveur. En cas d'erreur lors de la connexion, une erreur sera affichées sinon une
 *  redirection vers la page d'accueil sera faite.
 */
function actionConnexionUtilisateur() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if (isCaptchaValidateLogin) {
        let utilisateur = new Utilisateur();
        utilisateur.setEmail(email);
        utilisateur.setMotDePasse(password);
        postUtilisateurConnexion(JSON.stringify(utilisateur), function (data) {
            if (!data.error) {
                window.location.replace('index');
            } else {
                erreur(data);
            }
        });
    } else {
        erreur("Veuillez confirmer le recaptcha !");
    }
}
