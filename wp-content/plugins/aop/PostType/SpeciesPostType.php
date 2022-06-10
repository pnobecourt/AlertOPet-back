<?php

namespace aop\PostType;

// cette classe récupère toutes les méthodes et propriétés (public et protected) de la classe parente
class speciesPostType extends PostType {

    // ici, dans la classe fille, on définit les données qui sont spécifiques à ce CPT
    const POST_TYPE_KEY = 'species';
    const POST_TYPE_LABEL = 'Species';
    const POST_TYPE_SLUG = 'especes';

    const CAPABILITIES = [
        // [cap par défaut, existante dans WP] => [cap custom qui correpond à la même action mais pour ce CPT distinct]
        'edit_posts' => 'edit_species', // on décide du nom de la capability à associer au comportement par défaut "edit_posts"
        'publish_posts' => 'publish_species',
        'edit_post' => 'edit_species',
        'read_post' => 'read_species',
        'delete_post' => 'delete_species',
        'edit_others_posts' => 'edit_others_species',
        'delete_others_posts' =>  'delete_others_species', // la notion "others" s'appuie sur l'auteur du post, il faut donc que ce CPT déclare le support de la feature "author"
        'delete_published_posts' => 'delete_published_species',
    ];

    // la liste des custom caps pour ce CPT que je veux associer à l'administrateur
    const DEFAULT_ROLES_CAPS = [
        'administrator' => [
            'edit_species' => true, 
            'publish_species' => true,
            'edit_species' => true,
            'read_species' => true,
            'delete_species' => true,
            'edit_others_species' => true,
            'delete_others_species' => true,
        ],
        'contributor' => [
            'edit_species' => true, 
            'publish_species' => false,
            'edit_species' => true,
            'read_species' => true,
            'delete_species' => true,
            'edit_others_species' => false,
            'delete_others_species' => false,
        ]
    ];
}