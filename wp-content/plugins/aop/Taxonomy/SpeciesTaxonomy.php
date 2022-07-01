<?php

namespace aop\Taxonomy;

use aop\PostType\AlertPostType;
use aop\PostType\PetPostType;

class SpeciesTaxonomy extends Taxonomy {

    const TAXONOMY_KEY = 'species';
    const TAXONOMY_NAME = 'Species';
    const POST_TYPE_KEY = [PetPostType::POST_TYPE_KEY, AlertPostType::POST_TYPE_KEY];

     const CAPABILITIES =  [
        'manage_terms' => 'manage_species',
        'edit_terms' => 'edit_species',
        'delete_terms' => 'delete_species',
        'assign_terms' => 'assign_species'
    ];

    const DEFAULT_ROLES_CAPS =  [
        'administrator' => [
            'manage_species' => true,
            'edit_species' => true,
            'delete_species' => true,
            'assign_species' => true,
        ],
        'author' => [
            'manage_species' => true,
            'edit_species' => true,
            'delete_species' => true,
            'assign_species' => true,
        ]
    ];
}