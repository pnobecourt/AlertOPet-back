<?php

namespace aop\Api;

use aop\Classes\PetDatabase;

class PetApi
{
    public static function initRoute()
    {
        // on utilise register_rest_route pour définir une nouvelle route
        // on peut préciser pour quelle méthode HTTP (verbe) et quel sera le callback => la métode à déclencher pour cette route
        // l'argument 1 contient le namespace dans lequel créer la route
        register_rest_route('aop/v1', '/pet', [
            'methods' => 'POST',
            'callback' => [self::class, 'handleCreatePet'],
        ]);
        register_rest_route('aop/v1', '/pet/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'handleGetPetById'],
        ]);
        register_rest_route('aop/v1', '/user/(?P<id>[\d]+)/pets', [
            'methods' => 'GET',
            'callback' => [self::class, 'handleGetAllPetsByOwnerId'],
        ]);
        register_rest_route('aop/v1', '/pet/user/(?P<id>[\d]+)', [
            'methods' => 'GET',
            'callback' => [self::class, 'handleGetAllPetsByOwnerId'],
        ]);
        register_rest_route('aop/v1', '/pet/(?P<id>[\d]+)', [
            'methods' => 'POST',
            'callback' => [self::class, 'handleEditPetById'],
        ]);
        register_rest_route('aop/v1', '/pet/(?P<id>[\d]+)', [
            'methods' => 'DELETE',
            'callback' => [self::class, 'handleDeletePetById'],
        ]);
    }

    public static function handleCreatePet()
    {
        // cette méthode doit return quelque chose pour servir de réponse
        // selon la configuration de PHP, et lorsque le body de la requête est au format json, $_POST reste vide
        // alternative : file_get_contents("php://input") qui permet de récupérer tout ce qui a été envoyé dans le body par la requête
        // on récupère une string => attention, il faut la convertir en la décodant avec json_decode()
        // on récupère les données sour forme de tableau associatif si le deuxième argument vaut true
        $petData = json_decode(file_get_contents("php://input"), true);

        $petPostIdOrError = wp_insert_post([
            'post_type' => 'pet',
            'post_title' => strip_tags($petData['title']),
            'post_content' => strip_tags($petData['content']),
            'post_status' => 'publish',
        ]);

        // si on a reçu un int
        if (is_int($petPostIdOrError) && $petPostIdOrError > 0) {
            PetDatabase::CreatePetMetaData($petData, $petPostIdOrError);

            $response = ["userId" => $petPostIdOrError];
        } else {
            // sinon c'est une erreur
            $response = $petPostIdOrError;
        }
        
        // on renvoie la réponse au format JSON
        return $response;
    }

    public static function handleGetPetById($request)
    {
        $petPostData = get_post($request['id'], ARRAY_A);
        
        $petMetaData = PetDatabase::GetPetMetaDataByPetId($request['id']);

        if (!empty(get_the_terms($request['id'], 'species'))){
            $petSpecies = get_the_terms($request['id'], 'species')[0]->name;
        } else {
            $petSpecies = null;
        }

        global $wpdb;
        $tableName = $wpdb->prefix . 'posts';
        $sql = "SELECT `guid` FROM `{$tableName}` WHERE `post_parent` = {$request['id']} AND `post_type` = \"attachment\"";
        $petPictures = $wpdb->get_results(
            $wpdb->prepare(
                $sql,
            )
        );
        $petPicture = $petPictures[0]->guid;

        /* print_r($petPostData);
        die(); */

        $preparedData = [
            'id' => $petPostData['ID'],
            'title' => strip_tags($petPostData['post_title']),
            'content' => strip_tags($petPostData['post_content']),
            'species' => $petSpecies,
            'breed' => strip_tags($petMetaData[0]['breed']),
            'identification' => strip_tags($petMetaData[0]['identification']),
            'birth_date' => $petMetaData[0]['birth_date'],
            'color' => strip_tags($petMetaData[0]['color']),
            'size' => strip_tags($petMetaData[0]['size']),
            'weight' => strip_tags($petMetaData[0]['weight']),
            'picture' => $petPicture,
            'owner_id' => intval($petPostData['post_author']),
        ];

        // on renvoie la réponse au format JSON
        return $preparedData;
    }

    public static function handleGetAllPetsByOwnerId($request)
    {
        $query_args = [
            'post_type' => 'pet',
            'author' => $request['id']
        ];

        $petList = get_posts($query_args);

        foreach ($petList as $petNumber => $pet){

            $petId = $pet->ID;

            $petPostData = get_post($petId, ARRAY_A);
        
            $petMetaData = PetDatabase::GetPetMetaDataByPetId($petId);

            if (!empty(get_the_terms($petId, 'species'))){
                $petSpecies = get_the_terms($petId, 'species')[0]->name;
            } else {
                $petSpecies = null;
            }

            global $wpdb;
            $tableName = $wpdb->prefix . 'posts';
            $sql = "SELECT `guid` FROM `{$tableName}` WHERE `post_parent` = {$petId} AND `post_type` = \"attachment\"";
            $petPictures = $wpdb->get_results(
                $wpdb->prepare(
                    $sql,
                )
            );
            $petPicture = $petPictures[$petNumber]->guid;

            $preparedData[] = [
                'id' => $petPostData['ID'],
                'title' => strip_tags($petPostData['post_title']),
                'content' => strip_tags($petPostData['post_content']),
                'species' => $petSpecies,
                'breed' => strip_tags($petMetaData[$petNumber]['breed']),
                'identification' => strip_tags($petMetaData[$petNumber]['identification']),
                'birth_date' => $petMetaData[$petNumber]['birth_date'],
                'color' => strip_tags($petMetaData[$petNumber]['color']),
                'size' => strip_tags($petMetaData[$petNumber]['size']),
                'weight' => strip_tags($petMetaData[$petNumber]['weight']),
                'picture' => $petPicture,
                'owner_id' => intval($petPostData['post_author']),
            ];
        }

        // on renvoie la réponse au format JSON
        return $preparedData;
    }

    public static function handleEditPetById($request)
    {
        // cette méthode doit return quelque chose pour servir de réponse
        // selon la configuration de PHP, et lorsque le body de la requête est au format json, $_POST reste vide
        // alternative : file_get_contents("php://input") qui permet de récupérer tout ce qui a été envoyé dans le body par la requête
        // on récupère une string => attention, il faut la convertir en la décodant avec json_decode()
        // on récupère les données sour forme de tableau associatif si le deuxième argument vaut true
        $petData = json_decode(file_get_contents("php://input"), true);

        // dans $postData on a un array associatif qui correspond à l'objet passé dans la requête ajax de userService.registerUser() côté VueJS
        // en cas de succès de wp_create_user() on récupère l'id de l'utilisateur créés
        // en cas d'échec un objet WP_Error
        $petPostIdOrError = wp_insert_post([
            'id' => $request['id'],
            'post_type' => 'pet',
            'post_title' => strip_tags($petData['title']),
            'post_content' => strip_tags($petData['content']),
            'post_status' => 'publish',
        ]);

        // si on a reçu un int
        if (is_int($petPostIdOrError)) {

            PetDatabase::UpdatePetMetaDataByPetId($petData, $petPostIdOrError);

            $response = ["userId" => $petPostIdOrError];
        } else {
            // sinon c'est une erreur
            $response = $petPostIdOrError;
        }

        // on renvoie la réponse au format JSON
        return $response;
    }
    
    public static function handleDeletePetById($request)
    {
        $petIdToDelete = (int) $request['id'];

        $deletePetMeta = PetDatabase::DeletePetMetaDataByPetId($request['id']);

        if (is_int($deletePetMeta)) {

            $response = wp_delete_post($petIdToDelete);

        } else {

            $response = "Erreur";

        }

        // on renvoie la réponse au format JSON
        return $response;
    }

}