<?php

namespace aop\PostType;

class PetPostType extends PostType {

    // in the subclass, we modify only the necessary information
    const POST_TYPE_KEY = 'pet';
    const POST_TYPE_LABEL = 'Pet';
    const POST_TYPE_SLUG = 'animal';
    const POST_TYPE_ARCHIVE = true;
    const SHOW_IN_MENU = true;
    const SUPPORTS = [
        'title',
        'editor',
        'thumbnail', // on autorise l'utilisation d'images mises en avant (featured images)
        'author', // ce custom post type utilisera les auteurs
        'comments',
        'custom-fields',
        'page-attributes',
    ];
    const CAPABILITIES = [
        // [default cap, existing in WP] => [custom cap that corrspond to the same action but for the current CPT]
        'read_post' => 'read_pet',
        'edit_post' => 'edit_pet',
        'delete_post' => 'delete_pet',
        'create_posts' => 'create_private_pets',
        'edit_posts' => 'edit_pets',
        'publish_posts' => 'publish_pets',
        'read_others_posts' => 'read_others_pets',  // "others" notion relies on post's author, it is necessary CPT declares "author" feature support
        'edit_others_posts' => 'edit_others_pets',
        'delete_others_posts' => 'delete_others_pets',
        'read_private_posts' => 'read_private_pets',
        'edit_private_posts' => 'edit_private_pets',
        'delete_private_posts' => 'delete_private_pets',
        'edit_published_posts' => 'edit_published_pets',
        'delete_published_posts' => 'delete_published_pets',
        ];
    const DEFAULT_ROLES_CAPS = [
        'administrator' => [
            // the custom's caps list I want to add to administartors
            'read_pet' => true,
            'edit_pet' => true,
            'delete_pet' => true,
            'create_private_pets' => true,
            'edit_pets' => true,
            'publish_pets' => true,
            'read_others_pets' => true,
            'edit_others_pets' => true,
            'delete_others_pets' => true,
            'read_private_pets' => true,
            'edit_private_pets' => true,
            'delete_private_pets' => true,
            'edit_published_pets' => true,
            'delete_published_pets' => true,
        ],
        'author' => [
            // the custom's caps list I want to add to authors
            'read_alert' => true,
            'edit_alert' => true,
            'delete_alert' => true,
            'edit_alerts' => true,
            'publish_alerts' => true,
            'read_others_alerts' => false,
            'edit_others_alerts' => false,
            'delete_others_alerts' => false,
            'create_private_alerts' => true,
            'read_private_alerts' => true,
            'edit_private_alerts' => true,
            'delete_private_alerts' => true,
            'edit_published_alerts' => true,
            'delete_published_alerts' => true,
        ],
    ];
}