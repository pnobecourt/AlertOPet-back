<?php

namespace aop\Api;

class UserApi {

    static public function initRoute()
    {
        // on utilise register_rest_route pour définir une nouvelle route
        // on peut préciser pour quelle méthode HTTP (verbe) et quel sera le callback => la métode à déclencher pour cette route
        // l'argument 1 contient le namespace dans lequel créer la route
        register_rest_route( 'aop/v1', '/user', [
            'methods' => 'POST',
            'callback' => [self::class, 'handleCreateUser'],
        ]);
        register_rest_route( 'aop/v1', '/user/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'handleGetUser'],
        ]);
        register_rest_route( 'aop/v1', '/user/(?P<id>[\d]+)', [
            'methods' => 'POST',
            'callback' => [self::class, 'handleEditUser'],
        ]);
        register_rest_route( 'aop/v1', '/user/(?P<id>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [self::class, 'handleDeleteUser'],
        ]);
    }

    static public function handleCreateUser()
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
        $userIdOrErrorObject = wp_insert_user([
            'username' => $postData['username'],
            'user_login' => $postData['user_login'],
            'user_pass' => $postData['user_pass'],
            'user_nicename' => $postData['user_nicename'],
            'user_email' => $postData['user_email'],
            'display_name' => $postData['display_name'],
            'nickname' => $postData['nickname'],
            'first_name' => $postData['first_name'],
            'last_name' => $postData['last_name'],
            'user_url' => $postData['user_url'],
            'description' => $postData['description'],
            'show_admin_bar_front' => false,
            'role' => $postData['role'],
            'locale' => $postData['locale'],
            'meta_input' => [
                'country' => $postData['country'],
                'zip' => $postData['zip'],
                'city' => $postData['city'],
                'address' => $postData['address'],
                'phone' => $postData['phone'],
            ]
        ]);

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

    static public function handleGetUser($request)
    {
        $userBase = get_userdata($request['id']);
        $userMeta = get_user_meta($request['id']);

        $preparedData = [
            'id' => $userBase->ID,
            'email' => $userBase->data->user_email,
            'role' => $userBase->roles[0],
            'first_name' => $userMeta->first_name,
            'last_name' => $userMeta->last_name,
            'description' => $userMeta->description,
        ];

        // on renvoie la réponse au format JSON
        return $preparedData;
    }

    static public function handleEditUser($request)
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
        if ($request['id'] == 1) {
            $adminBar = true;
        } else {
            $adminBar = false;
        }
        $userIdOrErrorObject = wp_insert_user([
            'ID' => intval($request['id']),
            'username' => $postData['username'],
            'user_login' => $postData['user_login'],
            'user_pass' => $postData['user_pass'],
            'user_nicename' => $postData['user_nicename'],
            'user_email' => $postData['user_email'],
            'display_name' => $postData['display_name'],
            'nickname' => $postData['nickname'],
            'first_name' => $postData['first_name'],
            'last_name' => $postData['last_name'],
            'user_url' => $postData['user_url'],
            'description' => $postData['description'],
            'show_admin_bar_front' => $adminBar,
            'role' => $postData['role'],
            'locale' => $postData['locale'],
            'meta_input' => [
                'country' => $postData['country'],
                'zip' => $postData['zip'],
                'city' => $postData['city'],
                'address' => $postData['address'],
                'phone' => $postData['phone'],
            ]
        ]);

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
    
    static public function handleDeleteUser($request)
    {
        $userIdToDelete = (int) $request['id'];
        $userLoginToDelete = get_userdata($userIdToDelete)->data->user_login;

        include_once ABSPATH . 'wp-admin/includes/user.php';

        if (is_int($userIdToDelete) && $userIdToDelete > 1) {
            $response = wp_delete_user($userIdToDelete);
        } else if (is_int($userIdToDelete) && $userIdToDelete == 1) {
            $response = "Impossible de supprimer le super-utilisateur";
        } else {
            $response = "Erreur lors de la suppression de l'utilisateur " . $userLoginToDelete;
        }
        
        // on renvoie la réponse au format JSON
        return $response;
    }
}