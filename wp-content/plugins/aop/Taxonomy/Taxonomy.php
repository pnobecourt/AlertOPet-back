<?php

namespace aop\Taxonomy;

class Taxonomy {
    /**
     * Registers the taxonomy
     *
     * @return void
     */
    static public function register()
    {
        register_taxonomy(
            static::TAXONOMY_KEY,
            [static::POST_TYPE_KEY],
            [
                'hierarchical' => true, // make it hierarchical (like categories)
                'labels' => ['name' =>  static::TAXONOMY_NAME],
                'show_ui' => true,
                'capabilities' => static::CAPABILITIES,
                'show_in_rest' => true
            ]
        );        
    }

    /**
     * Automatically attach current CT custom caps to default roles
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
     * Automatically detach current CT custom caps from default roles
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