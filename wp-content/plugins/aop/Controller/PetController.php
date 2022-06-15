<?php

namespace  aop\Controller;

use aop\Classes\PetDb;
use aop\Taxonomy\SpeciesTaxonomy;


class PetController {

    public function createPet($breed=null, $name=null, $birth_date=null, $color=null, $size=null, $weight=null, $identification=null, $description=null, $owner_id=null)
    {
        $this->breed = $breed;
        $this->name = $name;
        $this->birth_date = $birth_date;
        $this->color = $color;
        $this->size = $size;
        $this->weight = $weight;
        $this->identification = $identification;
        $this->description = $description;
        $this->owner_id = $owner_id;
        
        // @link: https://developer.wordpress.org/reference/functions/wp_insert_post/
        $my_pet_post = array(
            'post_title' => $this->name,
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'pet',
            'post_author' => $this->owner_id,
        );
        wp_insert_post($my_pet_post);

        // on récupère l'objet $wpdb => une globale déclarée par WP    
        global $wpdb;

        $tableName = $wpdb->prefix . 'pets';
    }

    public function updatePet($petId) 
    {
        global $wpdb;
        // https://hotexamples.com/fr/examples/-/wpdb/update/php-wpdb-update-method-examples.html
        // Si mise à jour du form animal
        if(){
        // alors on envoi les modifications à la table custom
            return $wpdb->update( $wpdb->prefix . 'pets', array(), array('id' => $petId));
        }
    }
}