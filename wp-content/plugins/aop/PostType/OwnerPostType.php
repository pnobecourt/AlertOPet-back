<?php

namespace aop\PostType;

class OwnerPostType extends PostType {

    // in the subclass, we modify only the necessary information
    const POST_TYPE_KEY = 'owner';
    const POST_TYPE_LABEL = 'Owner';
    const POST_TYPE_SLUG = 'proprietaire';
    const POST_TYPE_ARCHIVE = true;
    const SHOW_IN_MENU = true;
    const SUPPORTS = [
        'title',
        'editor',
        'thumbnail', // on autorise l'utilisation d'images mises en avant (featured images)
        'author', // ce custom post type utilisera les auteurs
        'comments',
    ];
    const CAPABILITIES = [
        // [default cap, existing in WP] => [custom cap that corrspond to the same action but for the current CPT]
        'read_post' => 'read_owner',
        'edit_post' => 'edit_owner',
        'delete_post' => 'delete_owner',
        'create_posts' => 'create_private_owners',
        'edit_posts' => 'edit_owners',
        'publish_posts' => 'publish_owners',
        'read_others_posts' => 'read_others_owners',  // "others" notion relies on post's author, it is necessary CPT declares "author" feature support
        'edit_others_posts' => 'edit_others_owners',
        'delete_others_posts' => 'delete_others_owners',
        'read_private_posts' => 'read_private_owners',
        'edit_private_posts' => 'edit_private_owners',
        'delete_private_posts' => 'delete_private_owners',
        'edit_published_posts' => 'edit_published_owners',
        'delete_published_posts' => 'delete_published_owners',
        ];
    const DEFAULT_ROLES_CAPS = [
        'administrator' => [
            // the custom's caps list I want to add to administartors
            'read_owner' => true,
            'edit_owner' => true,
            'delete_owner' => true,
            'create_private_owners' => true,
            'edit_owners' => true,
            'publish_owners' => true,
            'read_others_owners' => true,
            'edit_others_owners' => true,
            'delete_others_owners' => true,
            'read_private_owners' => true,
            'edit_private_owners' => true,
            'delete_private_owners' => true,
            'edit_published_owners' => true,
            'delete_published_owners' => true,
        ],
        'author' => [
            // the custom's caps list I want to add to authors
            'read_owner' => true,
            'edit_owner' => true,
            'delete_owner' => true,
            'create_private_owners' => true,
            'edit_owners' => false,
            'publish_owners' => false,
            'read_others_owners' => false,
            'edit_others_owners' => false,
            'delete_others_owners' => false,
            'read_private_owners' => false,
            'edit_private_owners' => false,
            'delete_private_owners' => false,
            'edit_published_owners' => false,
            'delete_published_owners' => false,
        ],
    ];
}