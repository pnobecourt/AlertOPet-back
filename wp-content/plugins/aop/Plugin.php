<?php

namespace aop;

use aop\Api\AlertApi;
use aop\Api\UserApi;
use aop\Api\PetApi;
use aop\Classes\Database;
use aop\PostType\PetPostType;
use aop\PostType\AlertPostType;
use aop\Taxonomy\SpeciesTaxonomy;
use aop\Taxonomy\AlertTypeTaxonomy;
use aop\Taxonomy\AlertStatusTaxonomy;

class Plugin {

    /**
     * Starts the plugin
     *
     * @return void
     */
    static public function run()
    {
        // actions to perform before init 
        self::preInit();

        // we attach on the init hook, so that the plugin is ready to use
        add_action('init', [self::class, 'onInit']);

        // we attach on the rest_api_init hook, so that the plugin is ready to use
        add_action('rest_api_init', [self::class, 'onRestInit']);

        // idem pour l'activation du plugin
        register_activation_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginActivation'] // la méthode à déclencher à l'activation du plugin
        );
        // idem pour la désactivation du plugin
        register_deactivation_hook(
            AOP_PLUGIN_FILE,
            [self::class, 'onPluginDeactivation'] // la méthode à déclencher à la désactivation du plugin
        );
        // idem pour la désinstallation du plugin
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
        // we add the new route to the jwt-auth whitelist, otherwise an unregistered user couldn't access it and create account
        // we return an array that contains all unprotected routes
        add_filter('jwt_auth_whitelist', function ($endpoints) {
            $your_endpoints = [
                '/wp/v2/posts',
                '/wp/v2/alert',
                '/wp/v2/media',
                '/wp/v2/species',
                '/wp/v2/alert_type',
                '/wp/v2/alert_status',
                '/wp-json/aop/v1/user',
            ];
        
            return array_unique(array_merge($endpoints, $your_endpoints));
        });

        // we run the API customization for cpt alert before init
        AlertApi::run();
    }

    /**
     * onInit()
     * Regroups all the actions to perform on WordPress init hook 
     *
     * @return void
     */
    static public function onInit()
    {
        // registration of custom post types
        PetPostType::register();
        AlertPostType::register();
        // registration of custom taxonomies
        SpeciesTaxonomy::register();
        AlertTypeTaxonomy::register();
        AlertStatusTaxonomy::register();

        // register metas for custom post types
        AlertPostType::registerMetas();
    }

    static public function onRestInit()
    {
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
        add_filter('rest_pre_serve_request', [self::class, 'setupCors']);

        // we call UserRegisterApi::initRoute() to add a custom route to the REST API
        UserApi::initRoute();

        // we call PetRegisterApi::initRoute() to add a custom route to the REST API
        PetApi::initRoute();

        // we call PostMetaApi::register() to register the posts metas
        // PostMetaApi::register();

    }

    static public function onPluginActivation()
    {
        // associer les caps custom de nos CPT et CT à l'admin
        PetPostType::addCaps();
        AlertPostType::addCaps();
        SpeciesTaxonomy::addCaps();
        AlertTypeTaxonomy::addCaps();
        AlertStatusTaxonomy::addCaps();

        // create the custom mysql tables
        Database::generatePetTable();
    }
    
    /**
     * onPluginDeactivation()
     * Actions to perform on plugin deactivation
     *
     * @return void
     */
    static public function onPluginDeactivation()
    {
        // Dissocier les caps custom de nos CPT et CT de l'admin
        PetPostType::removeCaps();
        AlertPostType::removeCaps();
        SpeciesTaxonomy::removeCaps();
        AlertTypeTaxonomy::removeCaps();
        AlertStatusTaxonomy::removeCaps();
    }

    public function onPluginUninstall()
    {
        // actions to perform on plugin uninstallation
        Database::dropPetTable();
    }

    static public function setupCors()
    {
        header( 'Access-Control-Allow-Origin: *' );
        // header( 'Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE,OPTIONS' );
        header( 'Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE' );
        // header( 'Access-Control-Allow-Credentials: true' );
    }

}