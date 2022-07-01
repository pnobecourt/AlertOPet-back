<?php

namespace aop\Taxonomy;

use aop\PostType\AlertPostType;

class AlertLocalizationTaxonomy extends Taxonomy {

    const TAXONOMY_KEY = 'alert_localization';
    const TAXONOMY_NAME = 'Alert localization';
    const POST_TYPE_KEY = AlertPostType::POST_TYPE_KEY;

     const CAPABILITIES =  [
        'manage_terms' => 'manage_alert_localization',
        'edit_terms' => 'edit_alert_localization',
        'delete_terms' => 'delete_alert_localization',
        'assign_terms' => 'assign_alert_localization'
    ];

    const DEFAULT_ROLES_CAPS =  [
        'administrator' => [
            'manage_alert_localization' => true,
            'edit_alert_localization' => true,
            'delete_alert_localization' => true,
            'assign_alert_localization' => true,
        ],
        'author' => [
            'manage_alert_localization' => true,
            'edit_alert_localization' => true,
            'delete_alert_localization' => true,
            'assign_alert_localization' => true,
        ]
    ];
}