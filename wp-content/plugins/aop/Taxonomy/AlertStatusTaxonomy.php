<?php

namespace aop\Taxonomy;

use aop\PostType\AlertPostType;

class AlertStatusTaxonomy extends Taxonomy {

    const TAXONOMY_KEY = 'alert_status';
    const TAXONOMY_NAME = 'Alert status';
    const POST_TYPE_KEY = AlertPostType::POST_TYPE_KEY;

     const CAPABILITIES =  [
        'manage_terms' => 'manage_alert_status',
        'edit_terms' => 'edit_alert_status',
        'delete_terms' => 'delete_alert_status',
        'assign_terms' => 'assign_alert_status'
    ];

    const DEFAULT_ROLES_CAPS =  [
        'administrator' => [
            'manage_alert_status' => true,
            'edit_alert_status' => true,
            'delete_alert_status' => true,
            'assign_alert_status' => true,
        ],
        'author' => [
            'manage_alert_status' => true,
            'edit_alert_status' => true,
            'delete_alert_status' => true,
            'assign_alert_status' => true,
        ]
    ];
}