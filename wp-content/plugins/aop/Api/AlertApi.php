<?php

namespace aop\Api;

use aop\PostType\AlertPostType;

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
        add_filter("rest_prepare_{$postType}", [self::class, "onPrepare"]);
    }

    /**
     * onPrepare()
     * filters the WordPress Rest Api response for the Alert resource
     *
     * @param [type] $response
     * @return void
     */
    static public function onPrepare($response)
    {
        // $response contient la réponse que WP a prévu de renvoyer
        // dans $response, on trouve la propriété "data" qui est un array associatif dans lequel on peut placer des données

        // strip_tags() pour supprimer le HTML qui pourrait s'y trouver
        $response->data['content']['rendered'] = strip_tags($response->data['content']['rendered']);
        
        // on est dans un filter, on doit donc retourner une valeur
        return $response;
    }

}