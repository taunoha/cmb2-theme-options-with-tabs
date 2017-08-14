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
        ),
        array(
            'id'      => '_ld_main_group_example',
            'type'    => 'group',
            'options' => array(
                'group_title'   => 'Entry {#}',
                'add_button'    => 'Add Another Entry',
                'remove_button' => 'Remove Entry'
            ),
            'fields' => array(
                array(
                    'name' => 'Entry Title',
                    'id'   => 'title',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Entry Image',
                    'id'   => 'image',
                    'type' => 'file'
                )
            )
        )
    );

    return $fields;
}
add_filter('cmb2_theme_options_tab_fields', 'cmb2_ld_theme_options_fields');
