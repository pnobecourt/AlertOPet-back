<?php

namespace aop\PostType;

// cette classe récupère toutes les méthodes et propriétés (public et protected) de la classe parente
class PetPostType extends PostType {

    // ici, dans la classe fille, on définit les données qui sont spécifiques à ce CPT
    const POST_TYPE_KEY = 'pets';
    const POST_TYPE_LABEL = 'Pets';
    const POST_TYPE_SLUG = 'animaux';

    const CAPABILITIES = [
        // [cap par défaut, existante dans WP] => [cap custom qui correpond à la même action mais pour ce CPT distinct]
        'edit_posts' => 'edit_pets', // on décide du nom de la capability à associer au comportement par défaut "edit_posts"
        'publish_posts' => 'publish_pets',
        'edit_post' => 'edit_pet',
        'read_post' => 'read_pet',
        'delete_post' => 'delete_pet',
        'edit_others_posts' => 'edit_others_pets',
        'delete_others_posts' =>  'delete_others_pets', // la notion "others" s'appuie sur l'auteur du post, il faut donc que ce CPT déclare le support de la feature "author"
        'delete_published_posts' => 'delete_published_pets',
    ];

    // la liste des custom caps pour ce CPT que je veux associer à l'administrateur
    const DEFAULT_ROLES_CAPS = [
        'administrator' => [
            'edit_pets' => true, 
            'publish_pets' => true,
            'edit_pet' => true,
            'read_pet' => true,
            'delete_pet' => true,
            'edit_others_pets' => true,
            'delete_others_pets' => true,
        ],
        'contributor' => [
            'edit_pets' => true, 
            'publish_pets' => false,
            'edit_pet' => true,
            'read_pet' => true,
            'delete_pet' => true,
            'edit_others_pets' => false,
            'delete_others_pets' => false,
        ]
    ];

    static public function registerPetMeta()
    {
        global $wpdb;
        $wpdb->petmeta = "{$wpdb->prefix}petmeta";
    }    

    static public function addCustomFields()
    {
        add_post_type_support(
            'pets',
            'custom-fields',
            array(
                'breed' => '',
                'name' => '',
                'birth_date' => '',
                'color' => '',
                'size' => '',
                'weight' => '',
                'identification' => '',
                'description' => '',
            )
        );
    }

    static public function add_post_meta_boxes() { 
        add_meta_box(
             'birth_date', // id de la metabox
             'Date de naissance', // titre de la custom box
             [self::class, 'render_post_meta_boxes'],  // appel de la fonction qui affiche le html
             'pets',  // Nom du post type
             'side',
             'low'
         );
     }
 
 
    static public function render_post_meta_boxes() {
        global $post;
        $custom = get_post_custom($post->ID);
        $fieldData = $custom['_birth_date'][0];
        echo "<input type='date' name='_birth_date' value='$fieldData' placeholder='Birth Date'/>";

        /* $value = get_post_meta( $post->ID, '_meta-data', true );
        ?>
            <label for="fblink">Liens Facebook</label>
            <input type="text" id="fblink" name="fblink">
        <?php  */


    }

    static public function save_post_meta_boxes() {
        global $post;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (array_key_exists('_birth_date', $_POST)) {
            update_post_meta($post->ID, '_birth_date', $_POST['_birth_date']);
        }
    }
    
}   