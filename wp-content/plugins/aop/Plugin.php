<?php

namespace aop;

use aop\PostType\PetPostType;
use aop\Taxonomy\SpeciesTaxonomy;
use aop\Classes\PetDb;
use aop\Api\UserRegister;
use aop\Api\Pet;

class Plugin {
    /**
     * Entry method
     *
     * @return void
     */
    static public function run()
    {
        self::preInit();
        
        add_action('init', [self::class, 'onInit']);

        add_action( 'rest_api_init', [self::class, 'onRestInit']);

        add_action( 'save_post_pet', PetDb::addPet(), 10, 3 );

        register_activation_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginActivation']
        );

        register_deactivation_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginDeactivation']
        );

        register_uninstall_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginUninstall']
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
        // actions to perform before the WP init hook
        // on gère la whitelist pour le plugin jwt-auth
        // on veut return un array qui contient toutes les routes à ne PAS protéger
        add_filter('jwt_auth_whitelist', function ($endpoints) {
            $your_endpoints = [
                '/wp-json/jwt-auth/v1/token',
                '/wp-json/aop/v1/user',
            ];

        return array_unique(array_merge($endpoints, $your_endpoints));
        });

        Pet::run();
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
        PetPostType::addCustomFields();
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
        remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
        add_filter( 'rest_pre_serve_request', [self::class, 'setupCors']);

        // ajouter la route custom pour enregistrer un utilisateur
        UserRegister::initRoute();
    }

    /**
     * setupCors()
     * filters the Cross Origin Policy
     *
     * @return void
     */
    static public function setupCors()
    {
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

        // create custom tables
        PetDb::generateTables();
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

    /**
     * onPluginUninstall()
     * Actions to perform on plugin uninstallation
     *
     * @return void
     */
    public function onPluginUninstall()
    {
        // actions to perform on plugin uninstallation
        PetDb::dropTables();
    }
}