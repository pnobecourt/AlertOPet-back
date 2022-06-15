<?php

namespace aop\Classes;

use aop\Taxonomy\SpeciesTaxonomy;

class PetDb {

/**
     * generateTables
     *
     * Creates the custom mysql tables for aop
     * 
     * @return void
     */
    static public function generateTables()
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
        );
        ";
        

        // on utlise l'objet wpdb pour faire la requête
        $wpdb->query($sql);
    }

    static public function dropTables()
    {
        // on récupère l'objet $wpdb => une globale déclarée par WP
        global $wpdb;

        // définir le nom de la table custom, on n'oublie pas d'utiliser le préfixe défini dans wp_config pour dynamiser le nom de la table
        $tableName = $wpdb->prefix . 'pets';

        $wpdb->query('DROP TABLE IF EXISTS ' . $tableName);
    }

    static public function getSpecies($speciesId)
    {
        // on récupère l'objet wpdb qui permet d'interagir avec la BDD dans WP.
        global $wpdb;

        // on recompose le nom de la table en utilisant le préfixe
        $tableName = $wpdb->prefix . 'pets';

        // réaliser la requête en bdd pour récupérer la liste des animaux
        // on utilise la syntaxe sprintf() qui permet de définir des zones variables dans une chaîne => %d sera un integer
        $sql = "SELECT * FROM `{$tableName}` WHERE species_id=%d";

        // on utilise wpdb:->prepare() pour préparer notre requête SQL et lui fournir les arguments à utiliser
        $preparedQuery = $wpdb->prepare($sql, [$speciesId]);

        // exécution de la requête
        // on récupère le résultat sous la forme d'un array associatif
        $relationList = $wpdb->get_results($preparedQuery, ARRAY_A);
        
        
        // on retourne un array qui contient pour chaque animale
        // - l'objet WP_Term 
        // - le niveau de maîtrise (int)
        $speciesList = [];
        foreach ($relationList as $relation) {
            $species = [
                'term' => get_term($relation['species_id'], SpeciesTaxonomy::TAXONOMY_KEY),
            ];

            $speciesList[$species['term']->slug] = $species;
        }
        
        return $speciesList;
    }

    /**
     * update the list of technologies associated to a specified developer, and the grade for this technology
     *
     * @param Array // technologies represented by term_id => grade
     * @return void
     */
    static public function updateTechnologiesByDeveloperId($technologies, $developerPostId)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'developer_technology';
        // Chaque technologie est un ensemble clé/valeur tel que term_id => grade
        // Dans notre cas, on décide qu'une valeur de 0 pour grade veut dire que la technologie n'est pas associée. 
        // on va d'abord supprimer toutes les technologies pour un développeur
        // wpdb::delete() est fait pour
        // le deuxième argument permet de donner un ensemble de WHERE
        // Dans mon cas, WHERE developer_id=$developerPostId
        $wpdb->delete($tableName, ['developer_id' => $developerPostId]);
        // puis on ajoute des relations pour  chaque technologie qui a une note > 0
        foreach ($technologies as $technologyId => $grade) {
            if ($grade > 0) {
                self::addTechnologyToDeveloper($technologyId, $grade, $developerPostId);
            }
        }
    }

    /**
     * Registers a relation between a developer attached post and a technology, and the associated grade
     *
     * @param [type] $technologyId
     * @param [type] $grade
     * @param [type] $developerPostId
     * @return void
     */
    static public function addTechnologyToDeveloper($technologyId, $grade, $developerPostId)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'developer_technology';
        $wpdb->insert($tableName, ['technology_id' => $technologyId, 'grade' => $grade, 'developer_id' => $developerPostId]);
    }

    /**
     * I don't know what this function does
     *
     * @param [type] $developerPostId
     * @return void
     */
    static public function addPostOnCreatePet()
    {

    }
}