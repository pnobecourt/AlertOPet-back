<?php

namespace aop;

/**
 * Plugin Name: aop
 * Plugin URI: http://alertopet.com
 * Description: Adds Alert O'Pet specific features, content types and views
 * Version: 1.0.0
 * Author: Nobécourt Paul, Baffray David, Cherier Marc, Abdallaoui Radhwane
 */
 
require __DIR__ . '/vendor/autoload.php';

// the absolute path to this file in a constant
define('AOP_PLUGIN_FILE', __FILE__);

// the absolute url to this file in a constant
define('AOP_PLUGIN_URL', plugin_dir_url(__FILE__));

// on démarre le plugin
Plugin::run();