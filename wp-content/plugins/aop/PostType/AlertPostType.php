<?php

namespace aop\PostType;

class AlertPostType extends PostType {

    // in the subclass, we modify only the necessary information
    const POST_TYPE_KEY = 'alert';
    const POST_TYPE_LABEL = 'Alert';
    const POST_TYPE_SLUG = 'alerte';
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
        'read_post' => 'read_alert',
        'edit_post' => 'edit_alert',
        'delete_post' => 'delete_alert',
        'create_posts' => 'create_private_alerts',
        'edit_posts' => 'edit_alerts',
        'publish_posts' => 'publish_alerts',
        'read_others_posts' => 'read_others_alerts',  // "others" notion relies on post's author, it is necessary CPT declares "author" feature support
        'edit_others_posts' => 'edit_others_alerts',
        'delete_others_posts' => 'delete_others_alerts',
        'read_private_posts' => 'read_private_alerts',
        'edit_private_posts' => 'edit_private_alerts',
        'delete_private_posts' => 'delete_private_alerts',
        'edit_published_posts' => 'edit_published_alerts',
        'delete_published_posts' => 'delete_published_alerts',
        ];
    const DEFAULT_ROLES_CAPS = [
        'administrator' => [
            // the custom's caps list I want to add to administartors
            'read_alert' => true,
            'edit_alert' => true,
            'delete_alert' => true,
            'create_private_alerts' => true,
            'edit_alerts' => true,
            'publish_alerts' => true,
            'read_others_alerts' => true,
            'edit_others_alerts' => true,
            'delete_others_alerts' => true,
            'read_private_alerts' => true,
            'edit_private_alerts' => true,
            'delete_private_alerts' => true,
            'edit_published_alerts' => true,
            'delete_published_alerts' => true,
        ],
        'author' => [
            // the custom's caps list I want to add to authors
            'read_alert' => true,
            'edit_alert' => true,
            'delete_alert' => true,
            'create_private_alerts' => true,
            'edit_alerts' => false,
            'publish_alerts' => false,
            'read_others_alerts' => false,
            'edit_others_alerts' => false,
            'delete_others_alerts' => false,
            'read_private_alerts' => false,
            'edit_private_alerts' => false,
            'delete_private_alerts' => false,
            'edit_published_alerts' => false,
            'delete_published_alerts' => false,
        ],
    ];

    static public function registerMetas()
    {
        register_meta('post', 'datetime', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'localization', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petId', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petBreed', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petName', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petAge', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petColor', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petSize', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petWeight', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'petDescription', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'contactPhone', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
        register_meta('post', 'contactMail', [
            'object_subtype' => 'alert',
            'show_in_rest' => true
        ]);
    }

}