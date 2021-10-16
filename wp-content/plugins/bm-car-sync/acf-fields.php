<?php

function cptui_register_my_cpts_car()
{

    /**
     * Post Type: Cars
     */

    $labels = [
        "name" => __("Cars", "custom-post-type-ui"),
        "singular_name" => __("Bil", "custom-post-type-ui"),
    ];

    $args = [
        "label" => __("Cars", "custom-post-type-ui"),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => ["slug" => "bil", "with_front" => true],
        "query_var" => true,
        "supports" => ["title", "editor", "thumbnail"],
        "show_in_graphql" => false,
    ];

    register_post_type("car", $args);
}

add_action('init', 'cptui_register_my_cpts_car');

function car_taxonomy()
{
    register_taxonomy(
        'car_brand',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'car', // post type name
        array(
            'hierarchical' => false,
            'label' => 'Brand', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'brand',    // This controls the base slug that will display before each term
                'with_front' => false  // Don't display the category base before
            )
        )
    );
    register_taxonomy(
        'car_model',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'car', // post type name
        array(
            'hierarchical' => false,
            'label' => 'Model', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'model',    // This controls the base slug that will display before each term
                'with_front' => false  // Don't display the category base before
            )
        )
    );
    register_taxonomy(
        'car_model_year',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'car', // post type name
        array(
            'hierarchical' => false,
            'label' => 'Model Year', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'model_year',    // This controls the base slug that will display before each term
                'with_front' => false  // Don't display the category base before
            )
        )
    );
    register_taxonomy(
        'car_engine',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'car', // post type name
        array(
            'hierarchical' => false,
            'label' => 'Engine', // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'engine',    // This controls the base slug that will display before each term
                'with_front' => false  // Don't display the category base before
            )
        )
    );
}
add_action('init', 'car_taxonomy');


if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
        'key' => 'group_6136725994c39',
        'title' => 'Car tuning stages',
        'fields' => array(
            array(
                'key' => 'field_6136726396f0b',
                'label' => 'Optimeringssteg',
                'name' => 'optimeringssteg',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => 'field_61367387d6337',
                'min' => 1,
                'max' => 5,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_6136783e85a1f',
                        'label' => 'Tab rubrik',
                        'name' => 'tab_title',
                        'type' => 'text',
                        'instructions' => 'Kort namn (ex. Steg 1) som visas som tabb titel',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 'Steg 1',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_613672a6d6331',
                        'label' => 'Effekt före',
                        'name' => 'effect_before',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'hk',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_613672f2d6332',
                        'label' => 'Effekt efter',
                        'name' => 'effect_after',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'hk',
                        'min' => 0,
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_61367321d6333',
                        'label' => 'Skillnad',
                        'name' => 'effect_difference',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'hk',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_6136733ad6334',
                        'label' => 'Vrid före',
                        'name' => 'torque_before',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'Nm',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_61367359d6335',
                        'label' => 'Vrid efter',
                        'name' => 'torque_after',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'Nm',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_6136737ad6336',
                        'label' => 'Vrid skillnad',
                        'name' => 'torque_difference',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'Nm',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_61367387d6337',
                        'label' => 'Pris',
                        'name' => 'price_inc_tax',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 0,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => 'kr',
                        'min' => 0,
                        'max' => 99999,
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_613673b4d6338',
                        'label' => 'Komp. med E85',
                        'name' => 'e85_compatible',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => 'Ja',
                        'ui_off_text' => 'Nej',
                    ),
                    array(
                        'key' => 'field_613673dcd6339',
                        'label' => 'Extrajobb',
                        'name' => 'extra_work_included',
                        'type' => 'textarea',
                        'instructions' => 'Extra steg vid optimering, t.ex. modifikationer, hårdvara, extrajobb som ingår i priset. Skriv NOGA om kostnader tillkommer.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 'Inga extra arbeten krävs för denna optimering.',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => '',
                        'new_lines' => 'br',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'car',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

endif;
