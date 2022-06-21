<?php

namespace aop\Taxonomy;

use aop\PostType\AlertPostType;

class AlertTypeTaxonomy extends Taxonomy {

    const TAXONOMY_KEY = 'alert_type';
    const TAXONOMY_NAME = 'Alert type';
    const POST_TYPE_KEY = AlertPostType::POST_TYPE_KEY;

     const CAPABILITIES =  [
        'manage_terms' => 'manage_alert_type',
        'edit_terms' => 'edit_alert_type',
        'delete_terms' => 'delete_alert_type',
        'assign_terms' => 'assign_alert_type'
    ];

    const DEFAULT_ROLES_CAPS =  [
        'administrator' => [
            'manage_alert_type' => true,
            'edit_alert_type' => true,
            'delete_alert_type' => true,
            'assign_alert_type' => true,
        ]
    ];
}