# CMB2 Theme Options Page with Tabs (WPML Compatible)

## Description

A simple way to create your own theme options page. This plugin requires [CMB2](https://github.com/WebDevStudios/CMB2) to work.

## Installation

Include cmb-theme-options-tabs library in `functions.php`

```php
if( defined('CMB2_LOADED') ) {
    require_once('cmb-theme-options-tabs/cmb-theme-options-tabs.php');
}
```

## Usage

Define your theme options in functions.php

```php
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
```

You can get the options like this:

```php
echo ld_get_theme_option('_ld_main_404_title');
```

If you prefer to define your own tabs just use `cmb2_theme_options_tab_tabs` filter to do this.
But keep in mind that in this situation you have to add additional parameter to `ld_get_theme_option()` function to get your theme option.

```php
function cmb2_theme_options_tab_tabs_callback($tabs)
{
    return array(
        'global' => array(
            'translated_name' => 'Global',
            'language_code' => 'global'
        )
    );
}
add_filter('cmb2_theme_options_tab_tabs', 'cmb2_theme_options_tab_tabs_callback');

echo ld_get_theme_option('_ld_main_404_title', 'global');

```
