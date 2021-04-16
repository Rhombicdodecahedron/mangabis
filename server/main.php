<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once('./ctrl/Ctrl.php');

$ctrl = new Ctrl();

/**
 * Le code ci-dessous permet en fonction de la requête et de l'action, lancer la bonne méthode du contrôlleur.
 */
if (isset($_SERVER['REQUEST_METHOD'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case "GET":
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    /*-----------------------------GET ANNONCE----------------------------------*/
                    case "GET_ANNONCES":
                        $ctrl->getAnnonces();
                        break;
                    case "GET_ANNONCES_UTILISATEUR":
                        if (isset($_GET['pk_utilisateur'])) {
                            $ctrl->getAnnoncesUtilisateur($_GET['pk_utilisateur']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'pk_utilisateur' manquant !"));
                        }
                        break;
                    case "GET_ANNONCE":
                        if (isset($_GET['pk_annonce'])) {
                            $ctrl->getAnnonce($_GET['pk_annonce']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'pk_annonce' manquant !"));
                        }
                        break;
                    /*------------------------GET UTILISATEUR----------------------------------*/
                    case 'GET_UTILISATEURS':
                        $ctrl->getUtilisateurs();
                        break;
                    case 'GET_SESSION':
                        $ctrl->envoieInfoUtilisateurConnecte();
                        break;
                    default:
                        http_response_code(500);
                        echo json_encode(array("error" => true, "message" => "Aucune action du nom de " . $_GET['action'] . " n'existe."));
                        break;
                }
            } else {
                http_response_code(500);
                echo json_encode(array("error" => true, "message" => "Paramètre 'action' manquant !"));
            }
            break;
        case "POST":
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    /*-----------------------------POST ANNONCE----------------------------------*/
                    case 'POST_ANNONCE':
                        if (isset($_POST['annonce'])) {
                            $ctrl->nouvelleAnnonce($_POST['annonce'], isset($_FILES['images']) ? $_FILES['images'] : null);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'annonce' manquant !"));
                        }
                        break;

                    /**
                     * Pour le cas ci-dessous, il devrait normalement être un PUT. Le problème est que nous ne pouvons
                     * pas transférer de FormData via un PUT mais seulement d'un POST.
                     *
                     * Voici les articles sur github et laracasts qui expliquent ce problème.
                     * - https://laracasts.com/discuss/channels/laravel/ajax-formdata-and-put-fails
                     * - https://github.com/laravel/framework/issues/13457
                     */
                    case 'UPDATE_ANNONCE':
                        if (isset(/*$_PUT*/$_POST['annonce'])) {
                            $ctrl->modificationAnnonce(/*$_PUT*/ $_POST['annonce'], isset($_FILES['images']) ? $_FILES['images'] : null);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'annonce' manquant !"));
                        }
                        break;
                    /*------------------------POST UTILISATEUR----------------------------------*/
                    case 'POST_UTILISATEUR_INSCRIPTION':
                        if (isset($_POST['utilisateur'])) {
                            $ctrl->inscriptionUtilisateur($_POST['utilisateur']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'utilisateur' manquant !"));
                        }
                        break;
                    case 'POST_UTILISATEUR_CONNEXION':
                        if (isset($_POST['utilisateur'])) {
                            $ctrl->connexionUtilisateur($_POST['utilisateur']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'annonce' manquant !"));
                        }
                        break;
                    default:
                        http_response_code(500);
                        echo json_encode(array("error" => true, "message" => "Aucune action du nom de " . $_POST['action'] . " n'existe."));
                        break;
                }
            } else {
                http_response_code(500);
                echo json_encode(array("error" => true, "message" => "Paramètre 'action' manquant !"));
            }
            break;
        case "DELETE":
            parse_str(file_get_contents("php://input"), $_DELETE);
            if (isset($_DELETE['action'])) {
                switch ($_DELETE['action']) {
                    /*------------------------DELETE ANNONCE----------------------------------*/
                    case "DELETE_ANNONCE":
                        if (isset($_DELETE['pk_annonce'])) {
                            $ctrl->removeAnnonce($_DELETE['pk_annonce']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'data' manquant !"));
                        }
                        break;
                    /*------------------------DELETE UTILISATEUR----------------------------------*/
                    case "DELETE_UTILISATEUR":
                        if (isset($_DELETE['pk_utilisateur'])) {
                            $ctrl->deleteUtilisateur($_DELETE['pk_utilisateur']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'pk_utilisateur' manquant !"));
                        }
                        break;
                    case 'DELETE_SESSION':
                        $ctrl->deconnexionUtilisateur();
                        break;
                    default:
                        http_response_code(500);
                        echo json_encode(array("error" => true, "message" => "Aucune action du nom de " . $_DELETE['action'] . " n'existe."));
                        break;
                }
            } else {
                http_response_code(500);
                echo json_encode(array("error" => true, "message" => "Paramètre 'action' manquant !"));
            }
            break;
        case "PUT":
            parse_str(file_get_contents("php://input"), $_PUT);
            if (isset($_PUT['action'])) {
                switch ($_PUT['action']) {
                    /*------------------------PUT UTILISATEUR----------------------------------*/
                    case 'UPDATE_UTILISATEUR':
                        if (isset($_PUT['utilisateur'])) {
                            $ctrl->modificationUtilisateur($_PUT['utilisateur']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'utilisateur' manquant !"));
                        }
                        break;
                    case 'UPDATE_UTILISATEUR_ROLE':
                        if (isset($_PUT['utilisateur'])) {
                            $ctrl->modificationRoleUtilisateur($_PUT['utilisateur']);
                        } else {
                            http_response_code(500);
                            echo json_encode(array("error" => true, "message" => "Paramètre 'utilisateur' manquant !"));
                        }
                        break;
                    default:
                        http_response_code(500);
                        echo json_encode(array("error" => true, "message" => "Aucune action du nom de " . $_PUT['action'] . " n'existe."));
                        break;
                }
            } else {
                http_response_code(500);
                echo json_encode(array("error" => true, "message" => "Paramètre 'action' manquant !"));
            }
            break;
    }

}
?>