<?php

namespace aop\PostType;

// cette classe récupère toutes les méthodes et propriétés (public et protected) de la classe parente
class PetPostType extends PostType {

    // ici, dans la classe fille, on définit les données qui sont spécifiques à ce CPT
    const POST_TYPE_KEY = 'pets';
    const POST_TYPE_LABEL = 'Pets';
    const POST_TYPE_SLUG = 'animaux';

    const CAPABILITIES = [
        // [cap par défaut, existante dans WP] => [cap custom qui correpond à la même action mais pour ce CPT distinct]
        'edit_posts' => 'edit_pets', // on décide du nom de la capability à associer au comportement par défaut "edit_posts"
        'publish_posts' => 'publish_pets',
        'edit_post' => 'edit_pet',
        'read_post' => 'read_pet',
        'delete_post' => 'delete_pet',
        'edit_others_posts' => 'edit_others_pets',
        'delete_others_posts' =>  'delete_others_pets', // la notion "others" s'appuie sur l'auteur du post, il faut donc que ce CPT déclare le support de la feature "author"
        'delete_published_posts' => 'delete_published_pets',
    ];

    // la liste des custom caps pour ce CPT que je veux associer à l'administrateur
    const DEFAULT_ROLES_CAPS = [
        'administrator' => [
            'edit_pets' => true, 
            'publish_pets' => true,
            'edit_pet' => true,
            'read_pet' => true,
            'delete_pet' => true,
            'edit_others_pets' => true,
            'delete_others_pets' => true,
        ],
        'contributor' => [
            'edit_pets' => true, 
            'publish_pets' => false,
            'edit_pet' => true,
            'read_pet' => true,
            'delete_pet' => true,
            'edit_others_pets' => false,
            'delete_others_pets' => false,
        ]
    ];

    static public function addCustomFields()
    {
        /* add_post_type_support(
            'pets',
            'custom-fields',
            array(
                'breed' => '',
                'name' => '',
                'birth_date' => '',
                'color' => '',
                'size' => '',
                'weight' => '',
                'identification' => '',
                'description' => '',
            )
        ); */
        add_post_type_support(
            'pets',
            'custom-fields',
            [
                'breed' => 'breed',
                'name' => 'name',
                'birth_date' => 'birth_date',
                'color' => 'color',
                'size' => 'size',
                'weight' => 'weight',
                'identification' => 'identification',
                'description' => 'description'
            ]
        );
    }
}   