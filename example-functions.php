<?php

defined('ABSPATH') or die();

function cmb2_ld_theme_options_fields()
{
    $fields = array(
        array(
            'name'    => '404 page settings',
            'id'      => '_ld_main_404_box_title',
            'type'    => 'title'
        ),
        array(
            'name'    => 'Title',
            'id'      => '_ld_main_404_title',
            'type'    => 'text'
        ),
        array(
            'name'    => 'Content',
            'id'      => '_ld_main_404_content',
            'type'    => 'wysiwyg',
            'options' => array(
                'textarea_rows' => 5
            )
        )
    );

    return $fields;
}
add_filter('cmb2_theme_options_tab_fields', 'cmb2_ld_theme_options_fields');
