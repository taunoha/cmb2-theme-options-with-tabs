<?php
/**
 * Plugin Name: CMB2 Theme Options Page with Tabs
 * Description: A simple way to create your own theme options page. WPML Compatible.
 * Version: 1.1.1
 * Author: LOOM Digital
 * Author URI: https://www.loomdigital.ee
 *
 * @package     CMB2
 * @category    WordPress_Plugin
 * @author      LOOM Digital
 *
 */

defined('ABSPATH') or die();

/**
 * Display notification after update
 *
 * @since 1.0.0
 *
 */
function cmb2_theme_options_tab_notices()
{
    if( isset($_POST['object_id']) && strpos($_POST['object_id'], '_ld_theme_options_') !== false )
    {
        add_settings_error("ld-notices", '', __('Options updated'), 'updated');
    }
}
add_action('admin_menu', 'cmb2_theme_options_tab_notices');

/**
 * Display tabs and their content
 *
 * @since 1.0.0
 *
 */
function cmb2_theme_options_tab_content_callback($cmb)
{

    $box = $cmb->__get('cmb');
    $tabs = $box->prop('tabs');
    $fields = $box->prop('fields');

    $current_tab = ( isset($_GET['tab']) ) ? esc_attr($_GET['tab']) : '';

    if( empty($current_tab) ) {
        $first_tab = reset($tabs);
        $current_tab = $first_tab['language_code'];
    }

    ?>
    <div class="wrap cmb2-options-page option-<?php echo $box->prop('option_key')[0]; ?>">
        <?php if ( $box->prop('title') ) : ?>
            <h2><?php echo wp_kses_post( $box->prop('title') ); ?></h2>
        <?php endif; ?>
        <?php if( empty($fields) ) : ?>
            <div class="notice-warning settings-error notice">
                <p>It seems that you haven't defined fields yet. Please use the <code>cmb2_options_tab_fields</code> hook to define fields. For futher information look at <em>example-functions.php</em>.</p>
            </div>
        <?php else : ?>
            <div class="ld-in-page-tab">
                <div class="nav-tab-wrapper in-page-tab">
                    <?php foreach( $tabs as $code => $tab ) { ?>
                        <a class="nav-tab <?php echo ( $code == $current_tab ) ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('tab', $code)); ?>"><?php echo $tab['translated_name']; ?></a>
                    <?php $first_tab = false; } ?>
                </div>
            </div>
            <?php echo cmb2_metabox_form('_ld_theme_option_' . $current_tab . '_metaboxes', '_ld_theme_options_' . $current_tab, array('save_button' => esc_html__('Save changes'))); ?>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Create theme option metaboxes
 *
 * @since 1.1.0     Disabled the options autoload
 * @since 1.0.0
 *
 */
function cmb2_theme_options_tab_init_callback($fields)
{
    if( function_exists('icl_get_languages') ) {
        $languages = icl_get_languages();
    } else {
        $languages['default']= array('language_code' => 'default', 'translated_name' => esc_html__('Global'));
    }

    $fields = apply_filters('cmb2_theme_options_tab_fields', array());
    $languages = apply_filters('cmb2_theme_options_tab_tabs', $languages);

    $options = new_cmb2_box( array(
            'id'            => '_ld_theme_option_metaboxes',
            'title'         => apply_filters('cmb2_theme_options_tabs_page_title', sprintf(__('%s options'), wp_get_theme())),
            'object_types'  => array('options-page'),
            'autoload'      => false,
            'option_key'    => '_ld_theme_options',
            'parent_slug'   => 'options-general.php',
            'display_cb'    => 'cmb2_theme_options_tab_content_callback',
            'tabs'          => $languages,
            'fields'        => $fields
        )
    );

    foreach( $languages as $code => $data )
    {
        $tab = new_cmb2_box( array(
                'id'            => '_ld_theme_option_' . $code . '_metaboxes',
                'title'         => $data['translated_name'],
                'hookup'        => false,
                'autoload'      => false,
                'object_types'  => array('options-page'),
                'option_key'    => '_ld_theme_options_' . $code
            )
        );

        foreach( $fields as $field )
        {
            if( isset($field['show_in_tab']) && $code !== $field['show_in_tab'] ) {
                continue;
            }

            $tab->add_field($field);
        }

    }
}
add_action('cmb2_admin_init', 'cmb2_theme_options_tab_init_callback');

/**
 * Get theme option(s).
 *
 * @since 1.1.0     Updated theme option cache handling
 * @since 1.0.0
 *
 */
function ld_get_theme_option($key = 'all', $lang = 'default', $default = false)
{
    $language = 'default';

    if( defined('ICL_LANGUAGE_CODE') ) {
        $language = ICL_LANGUAGE_CODE;
    }

    if( !empty($lang) ) {
        $language = $lang;
    }

    $option_key = '_ld_theme_options_' . $language;
    $options = wp_cache_get($option_key);

    if( false === $options )
    {
        $options = map_deep(get_option($option_key, array() ), 'maybe_unserialize');
        wp_cache_set($option_key, $options);
    }

    if( 'all' !== $key ) {
        return ( isset($options[$key]) ) ? $options[$key] : '';
    }

    return $options;
}

/**
 * Add theme options link to admin bar menu
 *
 * @since 1.1.0
 *
 */

function ld_theme_options_admin_bar_menu($wp_admin_bar)
{
    $id = sanitize_key( apply_filters('cmb2_theme_options_tabs_page_title', sprintf(__('%s options'), wp_get_theme())) );

    $wp_admin_bar->add_node(array(
        'parent' => 'site-name',
        'title' => apply_filters('cmb2_theme_options_tabs_page_title', sprintf(__('%s options'), wp_get_theme())),
        'href' => esc_url( add_query_arg(array('page' => '_ld_theme_options'), admin_url('options-general.php')) ),
        'id' => $id
    ));
}
add_action('admin_bar_menu', 'ld_theme_options_admin_bar_menu');

