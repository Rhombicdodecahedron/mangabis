<?php
require_once("wrk/Wrk.php");

/**
 * Classe Ctrl
 * Cette classe permet de contacter le worker principale et d'afficher les résultats avec les codes d'erreurs.
 *
 * @version 1.0
 * @author Stella Alexis
 * @project Mangabis | Module 151
 */
class Ctrl
{
    private $wrk;

    /**
     * Constructeur de la classe "Ctrl". Il initialise la classe Wrk.
     */
    public function __construct()
    {
        $this->wrk = new Wrk();
    }

    //---------------------------ANNONCE------------------------------------------

    /**
     * Cette méthode permet de récupérer toutes les annonces que l'utilisateur possède. Elle va alors afficher sous la
     * forme d'un JSON: les annonces de l'utilisateur.
     *
     * @param $pk_utilisateur . représente la pk de l'utilisateur.
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté, le code 403 quand l'utilisateur n'a pas accès et 200 quand tout est bon.
     */
    public function getAnnoncesUtilisateur($pk_utilisateur)
    {
        if ($this->wrk->isConnected()) {
            if ($this->wrk->checkUserExistByPK($pk_utilisateur)) {
                if ($this->wrk->currentUser()->getPkUtilisateur() == $pk_utilisateur) {
                    echo json_encode(array("error" => false, "annonces" => $this->wrk->getAnnonces($pk_utilisateur)));
                } else {
                    http_response_code(403);
                    echo json_encode(array("error" => true, "message" => "Vous n'avez pas d'accès à ces ressources !"));
                }
            } else {
                echo json_encode(array("error" => true, "message" => "L'utilisateur spécifié n'existe pas !"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous devez être connecter pour effectuer cette action !"));
        }
    }

    /**
     * Cette méthode permet de récupérer une annonce en fonction de la pk reçu en paramètre. Elle va alors l'afficher sous la
     * forme d'un JSON.
     *
     * @param $pk_annonce . représente la pk de l'annonce.
     * @return void .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 200.
     */
    public function getAnnonce($pk_annonce)
    {
        $annonce = $this->wrk->getAnnonce($pk_annonce);
        if ($annonce != null) {
            echo json_encode(array("error" => false, "annonce" => $annonce->jsonSerialize()));
        } else {
            echo json_encode(array("error" => true, "message" => "Aucune annonce n'a été trouvé !"));
        }
    }

    /**
     * Cette méthode permet de récupérer toutes les annonces stockées dans la base de données. Elle va alors les afficher
     * sous la forme d'un JSON.
     *
     * @return void . un message de succès et un boolean "error" à true et va aussi retourner le code 200 car elle ne peut jamais
     * être fausse.
     */
    public function getAnnonces()
    {
        echo json_encode(array("error" => false, "message" => "Toutes les annonces ont correctement été récupérées", "annonces" => $this->wrk->getAnnonces(null)));
    }

    /**
     * Cette méthode permet d'ajouter une nouvelle annonce à la base de données.
     *
     * @param $data . représente le json contenant les informations de l'annonce à ajouter.
     * @param $images . représente les images à ajouter.
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté,500 quand un problème est survenu et 200 quand tout est bon.
     */
    public function nouvelleAnnonce($data, $images)
    {
        if ($this->wrk->isConnected()) {
            if ($this->wrk->addAnnonce($data, $images)) {
                echo json_encode(array("error" => false, "message" => "L'annonce a correctement été ajouté !"));
            } else {
                http_response_code(500);
                echo json_encode(array("error" => true, "message" => "Erreur lors de l'ajout de l'annonce !"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation d'ajouter une nouvelle annonce !"));
        }
    }

    /**
     * Cette méthode permet de modifier une annonce.
     *
     * @param $data . représente le json contenant les informations de l'annonce à modifier.
     * @param $images . représente les images à ajouter.
     * @return void .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté, le code 403 quand l'utilisateur n'a pas accès, 500 quand un problème est survenu et 200 quand tout
     * est bon.
     */
    public function modificationAnnonce($data, $images)
    {
        if ($this->wrk->isConnected()) {
            $pk_annonce = json_decode($data)->pk_annonce;
            if ($this->wrk->estUtilisateurAnnonce($pk_annonce)) {
                if ($this->wrk->updateAnnonce($data, $images)) {
                    echo json_encode(array("error" => false, "message" => "L'annonce a correctement été modifié !"));
                } else {
                    http_response_code(500);
                    echo json_encode(array("error" => true, "message" => "Erreur lors de la modification de l'annonce !"));
                }
            } else {
                http_response_code(403);
                echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation de modifier cette utilisateur!"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous devez être connecté pour effectuer cette action !"));
        }
    }

    /**
     * Cette méthode permet de supprimer une annonce en fonction de la pk reçu en paramètre.
     *
     * @param $pk_annonce . représente la pk de l'annonce à supprimer.
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté, le code 403 quand l'utilisateur n'a pas accès, 500 quand un problème est survenu et 200 quand tout
     * est bon.
     */
    public function removeAnnonce($pk_annonce)
    {
        if ($this->wrk->isConnected()) {
            if ($this->wrk->estUtilisateurAnnonce($pk_annonce) || $this->wrk->isAdmin()) {
                if ($this->wrk->deleteAnnonce($pk_annonce)) {
                    echo json_encode(array("error" => false, "message" => "L'annonce a correctement été supprimé !"));
                } else {
                    http_response_code(500);
                    echo json_encode(array("error" => true, "message" => "Erreur lors de la suppression de l'annonce !"));
                }
            } else {
                http_response_code(403);
                echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation de supprimer cette annonce car elle ne vous appartient pas !"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation de supprimer cette annonce, vous devez être connecté !"));
        }
    }

    //---------------------------UTILISATEUR--------------------------------------


    /**
     * Cette méthode permet d'ajouter un utilisateur à la base de données.
     *
     * @param $data . représente le json contenant les informations de l'utilisateur à ajouter..
     * @return void .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 500 quand un problème est
     * survenu et 200 quand tout est bon.
     */
    public function inscriptionUtilisateur($data)
    {
        if (!$this->wrk->utilisateurExistEmail(json_decode($data)->email)) {
            if ($this->wrk->addUtilisateur($data)) {
                echo json_encode(array("error" => false, "message" => "L'inscription a correctement été effectué !"));
            } else {
                http_response_code(500);
                echo json_encode(array("error" => true, "message" => "Erreur lors de l'inscription !"));
            }
        } else {
            echo json_encode(array("error" => true, "message" => "Un utilisateur avec la même adresse email existe déjà !"));
        }
    }

    /**
     * Cette méthode permet de tester si les crédits reçu en paramètre sont correcte et corresponde à une entrée dans la
     * base de données.
     *
     * @param $data . représente le json contenant les informations de l'utilisateur à tester.
     * @return void .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi 200 quand tout est bon.
     */
    public function connexionUtilisateur($data)
    {
        if ($this->wrk->isUtilisateur($data)) {
            echo json_encode(array("error" => false, "message" => "La connexion a correctement été effectué !"));
        } else {
            echo json_encode(array("error" => true, "message" => "Erreur lors de la connexion ! Vérifiez que vos identifiants soient corrects !"));
        }
    }


    /**
     * Cette méthode permet d'envoyer les informations de la session actuelle de l'utilisateur.
     *
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 200 quand tout est bon.
     */
    public function envoieInfoUtilisateurConnecte()
    {
        $utilisateur = $this->wrk->currentUser();
        if ($utilisateur != null) {
            echo json_encode(array("error" => false, "utilisateur" => $utilisateur->jsonSerialize(), "message" => "Les informations ont correctement été envoyé !"));
        } else {
            echo json_encode(array("error" => true, "message" => "Erreur lors de l'envoie des informations. Vérifiez que vous êtes bien connecté !"));
        }
    }

    /**
     * Cette méthode permet de supprimer la session actuelle de l'utilisateur.
     *
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 500 quand un problème est
     * survenu et 200 quand tout est bon.
     */
    public function deconnexionUtilisateur()
    {
        if ($this->wrk->destroySession()) {
            echo json_encode(array("error" => false, "message" => "Vous vous êtes correctement déconnecté !"));
        } else {
            http_response_code(500);
            echo json_encode(array("error" => true, "message" => "Erreur lors de la déconnexion de l'utilisateur !"));
        }
    }


    /**
     * Cette méthode permet de modifier un utilisateur de la base de données.
     *
     * @param $data . représente le json contenant les informations de l'utilisateur à modifier.
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté, le code 403 quand l'utilisateur n'a pas accès, 500 quand un problème est survenu et 200 quand tout
     * est bon.
     */
    public function modificationUtilisateur($data)
    {
        $pk = json_decode($data)->pk_utilisateur;
        if ($this->wrk->isConnected()) {
            if ($this->wrk->currentUser()->getPkUtilisateur() == $pk) {
                if ($this->wrk->updateUtilisateur($data) && $this->wrk->checkUserExistByPK($pk)) {
                    echo json_encode(array("error" => false, "message" => "L'utilisateur a correctement été modifié !"));
                } else {
                    http_response_code(500);
                    echo json_encode(array("error" => true, "message" => "Erreur lors de la modification de l'utilisateur !"));
                }
            } else {
                http_response_code(403);
                echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation de modifier cet utilisateur!"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous devez être connecté pour effectuer cette action !"));
        }
    }

    /**
     * Cette méthode permet recevoir tous les utilisateurs stockés dans la base de données.
     *
     * @return void . un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 200 quand tout est bon.
     */
    public function getUtilisateurs()
    {
        echo json_encode(array("error" => false, "utilisateurs" => $this->wrk->getUtilisateurs()));
    }


    /**
     * Cette méthode permet de modifier un utilisateur de la base de données.
     *
     * @param $pk_utilisateur . représente la pk de l'utilisateur à supprimer.
     * @return void .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté, le code 403 quand l'utilisateur n'a pas accès, 500 quand un problème est survenu et 200 quand tout
     * est bon.
     */
    public function deleteUtilisateur($pk_utilisateur)
    {
        if ($this->wrk->isConnected()) {
            if ($this->wrk->isAdmin()) {
                if ($this->wrk->deleteUtilisateur($pk_utilisateur)) {
                    echo json_encode(array("error" => false, "message" => "L'utilisateur a correctement été supprimé !"));
                } else {
                    http_response_code(500);
                    echo json_encode(array("error" => true, "message" => "Erreur lors de la suppression de l'utilisateur !"));
                }

            } else {
                http_response_code(403);
                echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation de supprimer cet utilisateur!"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous devez être connecté pour effectuer cette action !"));
        }
    }

    /**
     * Cette méthode permet de modifier le rôle d'un utilisateur de la base de données.
     *
     * @param $utilisateur . représente le json contenant les informations de l'utilisateur à modifier.
     * @return void .un message d'erreur/succès et un boolean "error" qui, quand il est à true, veut dire que le retour est
     * une erreur ou false si ce n'est pas une erreur. La méthode va aussi retourner le code 401 quand l'utilisateur n'est
     * pas connecté, le code 403 quand l'utilisateur n'a pas accès, 500 quand un problème est survenu et 200 quand tout
     * est bon.
     */
    public function modificationRoleUtilisateur($utilisateur)
    {
        if ($this->wrk->isConnected()) {
            if ($this->wrk->isAdmin()) {
                if ($this->wrk->currentUser()->getPkUtilisateur() != json_decode($utilisateur)->pk_utilisateur) {
                    if ($this->wrk->updateRoleUtilisateur($utilisateur)) {
                        echo json_encode(array("error" => false, "message" => "L'utilisateur a correctement été modifié !"));
                    } else {
                        http_response_code(500);
                        echo json_encode(array("error" => true, "message" => "Erreur lors de la modification du role de l'utilisateur !"));
                    }
                } else {
                    //http_response_code(500);
                    echo json_encode(array("error" => true, "message" => "Vous ne pouvez pas modifier votre role !"));
                }
            } else {
                http_response_code(403);
                echo json_encode(array("error" => true, "message" => "Vous n'avez pas l'autorisation de modifier le role de cet utilisateur!"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("error" => true, "message" => "Vous devez être connecté pour effectuer cette action !"));
        }
    }
}