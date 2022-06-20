<?php

namespace aop\Classes;

class PetDatabase
{
    public static function CreatePetMetaData($petData, $petPostId)
    {
        global $wpdb; //removed $name and $description there is no need to assign them to a global variable
        $table_name = $wpdb->prefix . "pets"; //try not using Uppercase letters or blank spaces when naming db tables

        $wpdb->insert($table_name, array(
            'post_id' => $petPostId,
            'owner_id' => $petData['author'],
            'breed' => $petData['breed'],
            'identification' => $petData['identification'],
            'birth_date' => $petData['birth_date'],
            'color' => $petData['color'],
            'size' => $petData['size'],
            'weight' => $petData['weight']
        ),
        array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ) //replaced %d with %s - I guess that your description field will hold strings not decimals
        );
    }

    public static function GetPetMetaData($request)
    {
        
        global $wpdb;
        $tableName = $wpdb->prefix . 'pets';
        $sql1 = "SELECT * FROM {$tableName} WHERE post_id = {$request}";
        $sql2 = "SELECT * FROM aop_pets WHERE post_id = 28";
        
        $postMeta = $wpdb->get_results($sql1, ARRAY_A);

        return $postMeta;
    }

    public static function UpdatePetMetaData($petData, $petPostId)
    {

        /* print_r("youpi!");
        die(); */

        global $wpdb; //removed $name and $description there is no need to assign them to a global variable
        $table_name = $wpdb->prefix . "pets"; //try not using Uppercase letters or blank spaces when naming db tables

        $wpdb->update($table_name, array(
            'owner_id' => $petData['author'],
            'breed' => $petData['breed'],
            'identification' => $petData['identification'],
            'birth_date' => $petData['birth_date'],
            'color' => $petData['color'],
            'size' => $petData['size'],
            'weight' => $petData['weight']
        ),
        array(
            'post_id' => $petPostId
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ),
        array(
            '%d'
        )
        );
    }

    public static function handleEditPet($request)
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
            'ID' => $request['id'],
            'username' => $request['username'],
            'user_login' => $request['user_login'],
            'user_pass' => $request['user_pass'],
            'user_nicename' => $request['user_nicename'],
            'user_email' => $request['user_email'],
            'display_name' => $request['display_name'],
            'nickname' => $request['nickname'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'user_url' => $request['user_url'],
            'description' => $request['description'],
            'show_admin_bar_front' => $request['show_admin_bar_front'],
            'role' => $request['role'],
            'locale' => $request['locale'],
            'meta_input' => [
                'country' => $request['country'],
                'zip' => $request['zip'],
                'city' => $request['city'],
                'address' => $request['address'],
                'phone' => $request['phone'],
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
    
    public static function handleDeletePet($request)
    {
        $userIdToDelete = (int) $request['id'];
        $userLoginToDelete = get_userdata($userIdToDelete)->data->user_login;

        include_once ABSPATH . 'wp-admin/includes/user.php';

        if (is_int($userIdToDelete) && $userIdToDelete > 1) {
            $response = wp_delete_user($userIdToDelete);
        } elseif (is_int($userIdToDelete) && $userIdToDelete == 1) {
            $response = "Impossible de supprimer le super-utilisateur";
        } else {
            $response = "Erreur lors de la suppression de l'utilisateur " . $userLoginToDelete;
        }
        
        // on renvoie la réponse au format JSON
        return $response;
    }
}