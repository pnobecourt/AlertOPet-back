<?php

namespace aop\Api;

use aop\PostType\AlertPostType;
use WP_REST_Request;

class AlertApi
{

    /**
     * run()
     * starts the Alert related Api customization
     *
     * @return void
     */
    static public function run()
    {
        $postType = AlertPostType::POST_TYPE_KEY;
        // au moment où WP prépare la réponse pour notre CPT Recipe, on ajoute des données qui nous intéressent
        add_filter("rest_prepare_{$postType}", [self::class, "onPrepare"], 10, 3);
        // we add a hook on the save_post_alert
        add_action("save_post_{$postType}", [self::class, 'onSaveAlertPost'], 10, 3);
    }

    /**
     * onPrepare()
     * filters the WordPress Rest Api response for the Alert resource
     *
     * @param [type] $response
     * @return void
     */
    static public function onPrepare($response, $post_type, $request)
    {

        // $response contient la réponse que WP a prévu de renvoyer
        // dans $response, on trouve la propriété "data" qui est un array associatif dans lequel on peut placer des données

        // strip_tags() pour supprimer le HTML qui pourrait s'y trouver
        $response->data['content']['rendered'] = trim(strip_tags($response->data['content']['rendered']));

        global $wpdb;
        
        // get alert picture
        $tablePosts = $wpdb->prefix . 'posts';
        $sqlAlertPicture = "SELECT `guid` FROM `{$tablePosts}` WHERE `post_parent` = {$response->data['id']} AND `post_type` = \"attachment\" AND `post_name` LIKE 'alert%';";
        $alertPictures = $wpdb->get_results( 
            $wpdb->prepare( 
                $sqlAlertPicture,
            )
        );
        if (!empty($alertPictures)){
            $alertPicture = $alertPictures[0]->guid;
            $response->data['alertPicture'] = $alertPicture;
        } else {
            $response->data['alertPicture'] = null;
        }

        // get pet picture
        $tablePosts = $wpdb->prefix . 'posts';
        $sqlPetPicture = "SELECT `guid` FROM `{$tablePosts}` WHERE `post_parent` = {$response->data['meta']['petId']} AND `post_type` = \"attachment\";";
        $petPictures = $wpdb->get_results( 
            $wpdb->prepare( 
                $sqlPetPicture,
            )
        );
        if (!empty($petPictures)){
            $petPicture = $petPictures[0]->guid;
            $response->data['petPicture'] = $petPicture;
        } else {
            $response->data['petPicture'] = null;
        }

        // get alert qrCode link
        $tablePosts = $wpdb->prefix . 'posts';
        $sqlAlertQrCodeLink = "SELECT `guid` FROM `{$tablePosts}` WHERE `post_parent` = {$response->data['id']} AND `post_type` = \"attachment\" AND `post_name` LIKE 'qrcode%';";
        $alertQrCodeLinks = $wpdb->get_results( 
            $wpdb->prepare( 
                $sqlAlertQrCodeLink,
            )
        );
        if (!empty($alertQrCodeLinks)){
            $alertQrCodeLink = $alertQrCodeLinks[0]->guid;
            $response->data['alertQrCodeLink'] = $alertQrCodeLink;
        } else {
            $response->data['alertQrCodeLink'] = null;
        }

        // get taxonomies name instead of id
        if (!empty(get_the_terms($response->data['id'], 'alert_type'))){
            $response->data['alert_type'] = get_the_terms($response->data['id'], 'alert_type')[0]->name;
        } else {
            $response->data['alert_type'] = null;
        }

        if (!empty(get_the_terms($response->data['id'], 'alert_status'))){
            $response->data['alert_status'] = get_the_terms($response->data['id'], 'alert_status')[0]->name;
        } else {
            $response->data['alert_status'] = null;
        }

        if (!empty(get_the_terms($response->data['id'], 'alert_localization'))){
            $response->data['alert_localization'] = get_the_terms($response->data['id'], 'alert_localization')[0]->slug;
        } else {
            $response->data['alert_localization'] = null;
        }
        
        // get pet species
        if (!empty(get_the_terms($response->data['meta']['petId'], 'species'))){
            $response->data['meta']['petSpecies'] = get_the_terms($response->data['meta']['petId'], 'species')[0]->name;
        } /* else {
            $response->data['meta']['petSpecies'] = null;
        } */

        // on est dans un filter, on doit donc retourner une valeur
        return $response;
    }

    public static function onSaveAlertPost($post_id, $post, $update)
    {
        $qrCode = file_get_contents('https://api.qrserver.com/v1/create-qr-code/?format=pngsize=300x300&data=http://devfront.alertopet.com/alert/' . $post_id);
        $qrCodeFilename = 'qrcode-alert-id-'. $post_id . '.png';
        $upload_file = wp_upload_bits($qrCodeFilename, null, $qrCode);
        if (!$upload_file['error']) {
            $wp_filetype = wp_check_filetype($qrCodeFilename, null);
            $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_parent' => $post_id,
                    'post_title' => preg_replace('/\.[^.]+$/', '', $qrCodeFilename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
            $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
            }
        }
    }
}