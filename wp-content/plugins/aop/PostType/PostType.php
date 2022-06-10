<?php

// Cette classe sert de classe générique pour tous les PostType. Dans chaque classe enfant on viendra modifier seulement les informations nécessaires

namespace aop\PostType;

class PostType {

    /**
     * Register the post type
     *
     * @return void
     */
    static public function register()
    {

        register_post_type (

            static::POST_TYPE_KEY, // handle
            [
                'label' => static::POST_TYPE_LABEL,
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => static::POST_TYPE_SLUG],
                'capabilities' => static::CAPABILITIES,
                'supports' => [
                    'title',
                    'editor',
                    'thumbnail',
                    'author',
                    'comments',
                ],
                'show_in_rest' => true,
            ]
        );

    }

    /**
     * Automatically attach current CPT custom caps to default roles
     *
     * @return void
     */
    static public function addCaps()
    {

        foreach (static::DEFAULT_ROLES_CAPS as $roleSlug => $capsArray) {

            $currentRole = get_role($roleSlug);
    
            foreach ($capsArray as $cap => $grant) {

                $currentRole->add_cap($cap, $grant);
            }
        }
    }

    /**
     * Automatically detach current CPT custom caps from default roles
     *
     * @return void
     */
    static public function removeCaps()
    {

        foreach (static::DEFAULT_ROLES_CAPS as $roleSlug => $capsArray) {

            $currentRole = get_role($roleSlug);
    

            foreach ($capsArray as $cap => $grant) {

                $currentRole->remove_cap($cap);
            }
        }
    }
}