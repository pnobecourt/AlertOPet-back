<?php

namespace aop;

use aop\PostType\PetPostType;
use aop\Taxonomy\SpeciesTaxonomy;

class Plugin {
    /**
     * Entry method
     *
     * @return void
     */
    static public function run()
    {
        //! Use this with api - self::preInit();
        
        add_action('init', [self::class, 'onInit']);

        //! use this with api - add_action( 'rest_api_init', [self::class, 'onRestInit']);

        register_activation_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginActivation']
        );

        register_deactivation_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginDeactivation']
        );
    }

    /**
     * preInit()
     * handle actions to perform before the WP init hook
     *
     * @return void
     */
    static public function preInit()
    {
        //! this function will be used for the api
    }

    /**
     * onInit()
     * Regroups all the actions to perform on WordPress init hook 
     *
     * @return void
     */
    static public function onInit()
    {
        // start CPT declaration
        PetPostType::register();
        // start Taxonomy declaration
        SpeciesTaxonomy::register();
    }

    /**
     * Regroups all the actions to perform on WordPress rest_api_init hook
     *
     * @return void
     */
    static public function onRestInit()
    {
        //! this function will be used for the api
    }

    /**
     * setupCors()
     * filters the Cross Origin Policy
     *
     * @return void
     */
    static public function setupCors()
    {
        //! this function will be used for the api
        header( 'Access-Control-Allow-Origin: *' );
    }

    /**
     * onPluginActivation()
     * Actions to perform on plugin activation
     *
     * @return void
     */
    static public function onPluginActivation()
    {
        // associate the custom cap of our CPT and CT with the admin
        PetPostType::addCaps();
        SpeciesTaxonomy::addCaps();
    }
    
    /**
     * onPluginDeactivation()
     * Actions to perform on plugin deactivation
     *
     * @return void
     */
    static public function onPluginDeactivation()
    {
        // Dissociate the custom caps of our CPT and CT from the admin
        PetPostType::removeCaps();
        SpeciesTaxonomy::removeCaps();
    }
}