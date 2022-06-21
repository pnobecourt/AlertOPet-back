<?php

namespace aop\Classes;

class Database {

/**
     * generateTables
     *
     * Creates the custom mysql tables for aop
     * 
     * @return void
     */
    static public function generatePetTable()
    {

        global $wpdb;

        $tableName = $wpdb->prefix . 'pets';

        $charsetCollate = $wpdb->get_charset_collate();

        // écrire la requête à exécuter
        // on crée une table (si elle n'existe pas déjà => IF NOT EXSITS) :
        $sql = "
        CREATE TABLE IF NOT EXISTS `{$tableName}` (
            `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `breed` varchar(100) NULL,
            `name` varchar(100) NULL,
            `picture` varchar(255) NULL,
            `birth_date` date NULL,
            `color` varchar(100) NULL,
            `size` float NULL,
            `weight` float NULL,
            `identification` varchar(100) NULL,
            `description` longtext NULL,
            `owner_id` bigint(20) unsigned DEFAULT 0,
            `post_id` bigint(20) unsigned DEFAULT 0,
            FOREIGN KEY (`owner_id`) REFERENCES `{$wpdb->prefix}users`(`ID`),
            FOREIGN KEY (`post_id`) REFERENCES `{$wpdb->prefix}posts`(`ID`)
        ) {$charsetCollate};
        ";
        

        // on utlise l'objet wpdb pour faire la requête
        $wpdb->query($sql);
    }

    static public function dropPetTable()
    {
        // on récupère l'objet $wpdb => une globale déclarée par WP
        global $wpdb;

        // définir le nom de la table custom, on n'oublie pas d'utiliser le préfixe défini dans wp_config pour dynamiser le nom de la table
        $tableName = $wpdb->prefix . 'pets';

        $wpdb->query('DROP TABLE IF EXISTS ' . $tableName);
    }

}