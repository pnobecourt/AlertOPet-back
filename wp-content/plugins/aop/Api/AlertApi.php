<?php

namespace aop\Api;

use aop\PostType\AlertPostType;
use WP_REST_Request;

class AlertApi
{

    /**
     * run()
     * starts the Alert related Api customization
     *
     * @return void
     */
    static public function run()
    {
        $postType = AlertPostType::POST_TYPE_KEY;
        // au moment où WP prépare la réponse pour notre CPT Recipe, on ajoute des données qui nous intéressent
        add_filter("rest_prepare_{$postType}", [self::class, "onPrepare"], 10, 3);
    }

    /**
     * onPrepare()
     * filters the WordPress Rest Api response for the Alert resource
     *
     * @param [type] $response
     * @return void
     */
    static public function onPrepare($response, $post_type, $request)
    {

        // $response contient la réponse que WP a prévu de renvoyer
        // dans $response, on trouve la propriété "data" qui est un array associatif dans lequel on peut placer des données

        // strip_tags() pour supprimer le HTML qui pourrait s'y trouver
        $response->data['content']['rendered'] = trim(strip_tags($response->data['content']['rendered']));

        global $wpdb;
        $tableName = $wpdb->prefix . 'posts';
        // get alert picture
        $sqlAlertPicture = "SELECT `guid` FROM `{$tableName}` WHERE `post_parent` = {$response->data['id']} AND `post_type` = \"attachment\";";
        $alertPictures = $wpdb->get_results( 
            $wpdb->prepare( 
                $sqlAlertPicture,
            )
        );
        $alertPicture = $alertPictures[0]->guid;
        $response->data['alertPicture'] = $alertPicture;
        // get pet picture
        $sqlPetPicture = "SELECT `guid` FROM `{$tableName}` WHERE `post_parent` = {$response->data['meta']['petId']} AND `post_type` = \"attachment\";";
        $petPictures = $wpdb->get_results( 
            $wpdb->prepare( 
                $sqlPetPicture,
            )
        );
        $petPicture = $petPictures[0]->guid;
        $response->data['petPicture'] = $petPicture;

        /* print_r($sqlPetPicture);
        die(); */
    
        // on est dans un filter, on doit donc retourner une valeur
        return $response;
    }

}