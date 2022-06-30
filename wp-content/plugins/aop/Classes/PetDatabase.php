<?php

namespace aop\Classes;

class PetDatabase
{
    public static function CreatePetMetaData($petData, $petPostId)
    {
        global $wpdb; //removed $name and $description there is no need to assign them to a global variable
        $table_name = $wpdb->prefix . "pets"; //try not using Uppercase letters or blank spaces when naming db tables

        $wpdb->insert($table_name, array(
            'post_id' => $petPostId,
            'owner_id' => $petData['author'],
            'breed' => $petData['breed'],
            'identification' => $petData['identification'],
            'birth_date' => $petData['birth_date'],
            'color' => $petData['color'],
            'size' => $petData['size'],
            'weight' => $petData['weight']
        ),
        array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ) //replaced %d with %s - I guess that your description field will hold strings not decimals
        );
    }

    public static function GetPetMetaDataByPetId($request)
    {
        
        global $wpdb;
        $tableName = $wpdb->prefix . 'pets';
        $sql = "SELECT * FROM {$tableName} WHERE post_id = {$request}";
        
        $postMeta = $wpdb->get_results($sql, ARRAY_A);

        return $postMeta;
    }

    public static function UpdatePetMetaDataByPetId($petData, $petPostId)
    {

        /* print_r("youpi!");
        die(); */

        global $wpdb; //removed $name and $description there is no need to assign them to a global variable
        $table_name = $wpdb->prefix . "pets"; //try not using Uppercase letters or blank spaces when naming db tables

        $wpdb->update($table_name, array(
            'owner_id' => $petData['author'],
            'breed' => $petData['breed'],
            'identification' => $petData['identification'],
            'birth_date' => $petData['birth_date'],
            'color' => $petData['color'],
            'size' => $petData['size'],
            'weight' => $petData['weight']
        ),
        array(
            'post_id' => $petPostId
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ),
        array(
            '%d'
        )
        );
    }

    public static function DeletePetMetaDataByPetId($request)
    {
        
        global $wpdb;
        $tableName = $wpdb->prefix . 'pets';
        $DeletedRowsOrError = $wpdb->delete(
            $tableName,
            ['post_id' => $request],
            ['%d']
        );

        return $DeletedRowsOrError;
    }

}