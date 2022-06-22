<?php

namespace aop\Api;

class PostMetaApi
{

    static public function register()
    {
        /* // array for posts metas
        // as postype => postmetas
        $metasArray = [
            "alert" => [
                'datetime',
                'localization',
                'petId',
                'petBreed',
                'petName',
                'petAge',
                'petColor',
                'petSize',
                'petWeight',
                'petDescription',
                'contactPhone',
                'contactMail'
                ]
        ];

        // register meta
        foreach ($metasArray as $postType) {
            foreach ($postType as $meta)
                register_meta('post', $meta, [
                    'object_subtype' => $postType,
                    'show_in_rest' => true
                ]);
        } */
        
        

        /* // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
        register_rest_field(
            'experience',
            'subtitle',
            array(
                'get_callback' => 'get_post_meta_for_api',
                'update_callback'   => 'update_post_meta_for_exp',
                'schema' => null,
            )
        ); */
    }

    /* function update_post_meta_for_exp($object, $meta_value)
    {
        $havemetafield  = get_post_meta($object['id'], 'experience', false);
        if ($havemetafield) {
            $ret = update_post_meta($object['id'], 'subtitle', $meta_value);
        } else {
            $ret = add_post_meta($object['id'], 'subtitle', $meta_value, true);
        }
        return true;
    } */

    /* function get_post_meta_for_api($object)
    {
        //get the id of the post object array
        $post_id = $object['id'];

        $meta = get_post_meta($post_id);

        if (isset($meta['subtitle']) && isset($meta['subtitle'][0])) {
            //return the post meta
            return $meta['subtitle'][0];
        }

        // meta not found
        return false;
    } */

    /* function create_api_posts_meta_field_time()
    {

        // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
        register_rest_field(
            'experience',
            'timing_of_experience',
            array(
                'get_callback' => 'get_post_meta_for_api_time',
                'update_callback'   => function ($meta_value) {
                    $havemetafield  = get_post_meta(1, 'experience', false);
                    if ($havemetafield) {
                        $ret = update_post_meta(1, 'timing_of_experience', $meta_value);
                        return true;
                    } else {
                        $ret = add_post_meta(1, 'timing_of_experience', $meta_value, true);
                        return true;
                    }
                },
                'schema' => null,
            )
        );
    } */

    /* function get_post_meta_for_api_time($object)
    {
        //get the id of the post object array
        $post_id = $object['id'];

        //return the post meta
        return get_post_meta($post_id)["timing_of_experience"][0];
    } */
}
