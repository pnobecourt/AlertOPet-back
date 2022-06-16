<?php

namespace aop\Api;

use aop\PostType\PetPostType;
/* use WP_REST_Request;
use static; */

class Pet {

    /**
     * run()
     * starts the Recipe related Api customization
     *
     * @return void
     */
    static public function run()
    {
        $postType = PetPostType::POST_TYPE_KEY;
        // au moment où WP prépare la réponse pour notre CPT Recipe, on ajoute des données qui nous intéressent
        add_filter("rest_prepare_{$postType}", [self::class, "onPrepare"], 10,3);
    }

    /**
     * onPrepare()
     * filters the WordPress Rest Api response for the Recipe resource
     *
     * @param [type] $response
     * @return void
     */
    static public function onPrepare($response, $post, $request)
    {
        // on récupère le post_type
        $postType = $post->post_type;

        // the call back function, use the $request parameter
        $parameters = $request->get_params();
        
        // $response contient la réponse que WP a prévu de renvoyer
        // dans $response, on trouve la propriété "data" qui est un array associatif dans lequel on peut placer des données
        // strip_tags() pour supprimer le HTML qui pourrait s'y trouver
        $response->data['breed'] = strip_tags($parameters['breed']);
        $response->data['name'] = strip_tags($parameters['name']);
        $response->data['birth_date'] = strip_tags($parameters['birth_date']);
        $response->data['color'] = strip_tags($parameters['color']);
        $response->data['size'] = strip_tags($parameters['size']);
        $response->data['weight'] = strip_tags($parameters['weight']);
        $response->data['identification'] = strip_tags($parameters['identification']);
        $response->data['description'] = strip_tags($parameters['description']);
        $response->data['idOwner'] = strip_tags($parameters['idOwner']);
        $response->data['idSpecies'] = strip_tags($parameters['idSpecies']);

        /* $post->data['breed'] = strip_tags($parameters['breed']);
        $post->data['name'] = strip_tags($parameters['name']);
        $post->data['birth_date'] = strip_tags($parameters['birth_date']);
        $post->data['color'] = strip_tags($parameters['color']);
        $post->data['size'] = strip_tags($parameters['size']);
        $post->data['weight'] = strip_tags($parameters['weight']);
        $post->data['identification'] = strip_tags($parameters['identification']);
        $post->data['description'] = strip_tags($parameters['description']);
        $post->data['idOwner'] = strip_tags($parameters['idOwner']);
        $post->data['idSpecies'] = strip_tags($parameters['idSpecies']); */
        
        // on est dans un filter, on doit donc retourner une valeur
        return $response;
    }

}
