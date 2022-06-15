<?php

namespace aop\Api;

class UserRegister {

    static public function initRoute()
    {
        // on utilise register_rest_route pour définir une nouvelle route
        // on peut préciser pour quelle méthode HTTP (verbe) et quel sera le callback => la métode à déclencher pour cette route
        // l'argument 1 contient le namespace dans lequel créer la route
        register_rest_route( 'aop/v1', '/user', [
            'methods' => 'POST',
            'callback' => [self::class, 'handleRegistration'],
        ]);
    }

    static public function handleRegistration()
    {
        // cette méthode doit return quelque chose pour servir de réponse
        // selon la configuration de PHP, et lorsque le body de la requête est au format json, $_POST reste vide
        // alternative : file_get_contents("php://input") qui permet de récupérer tout ce qui a été envoyé dans le body par la requête
        // on récupère une string => attention, il faut la convertir en la décodant avec json_decode()
        // on récupère les données sour forme de tableau associatif si le deuxième argument vaut true
        $postData = json_decode(file_get_contents("php://input"), true);

        // dans $postData on a un array associatif qui correspond à l'objet passé dans la requête ajax de userService.registerUser() côté VueJS
        // en cas de succès de wp_create_user() on récupère l'id de l'utilisateur créés
        // en cas d'échec un objet WP_Error
        $userIdOrErrorObject = wp_create_user(
            $postData['username'],
            $postData['password'],
            $postData['email']
        );

        // si on a reçu un int
        if (is_int($userIdOrErrorObject)) {
            $response = ["userId" => $userIdOrErrorObject];
        } else {
            // sinon c'est une erreur
            $response = $userIdOrErrorObject;
        }

        // on renvoie la réponse au format JSON
        return $response;
    }
    
}
