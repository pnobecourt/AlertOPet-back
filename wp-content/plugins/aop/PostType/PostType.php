<?php

// This class is a generic class for all PostTypes. In each subclass we will modify only the necessary information

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
                'has_archive' => static::POST_TYPE_ARCHIVE,
                'rewrite' => ['slug' => static::POST_TYPE_SLUG],
                'capabilities' => static::CAPABILITIES,
                'supports' => static::SUPPORTS,
                'show_in_rest' => true,
                'delete_with_user' => true,
                'show_ui' => true,
                'show_in_menu' => static::SHOW_IN_MENU,
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