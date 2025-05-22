<?php

/**
 * Plugin Name: Project Panorama - Project Management
 * Plugin URI: https://www.projectpanorama.com
 * Description: WordPress Project Management and Client Dashboard Plugin
 * Version: 1.6.0
 * Author: SnapOrbital
 * Author URI: https://www.projectpanorama.com
 * Text Domain: psp_projects
 * License: GPLv2 or later
 * Domain Path: /languages
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

/**

 * If Panorama Pro isn't enabled...

 */


if (! defined('ABSPATH')) {
    exit;
}




$constants = array(
    'PROJECT_PANORAMA_URI'   => plugins_url('', __FILE__),
    'PROJECT_PANORAMA_DIR'   => __DIR__,
    'PSP_VER'                => '1.6.0',
    'PSP_LITE_USE_TASKS'     => true,
);



foreach ($constants as $constant => $val) {
    if (!defined($constant)) define($constant, $val);
}
define('PSP_NAME', "psp_projects");
define('PSP_PLUGINURI', PROJECT_PANORAMA_URI . '/assets');

if (!function_exists('psp_initalize_application')) {
    ob_start();
    include_once('lib/psp-init.php');
    include_once('psp-license.php');
    ob_end_clean();
} else {
    // Fail silently
    return;
}

// ================
// = Localization =
// ================

function psppan_load_plugin_textdomain()
{
    load_plugin_textdomain('psp_projects', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'psppan_load_plugin_textdomain');


function psppan_check_acf_before_activation()
{
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        wp_die(
            esc_html__('Project Panorama requires Advanced Custom Fields (ACF) to be installed and activated.', 'psp_projects'),
            esc_html__('Plugin Activation Error', 'psp_projects'),
            ['back_link' => true]
        );
    }
}
register_activation_hook(__FILE__, 'psppan_check_acf_before_activation');

/**
 * Automatically deactivate this plugin if ACF is deactivated.
 */
function psppan_deactivate_if_acf_missing()
{
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
    }
}
add_action('admin_init', 'psppan_deactivate_if_acf_missing');

/**
 * Show admin notice if ACF is missing.
 */
function psppan_admin_notice_acf_missing()
{
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        echo '<div class="notice notice-error"><p><strong>Project Panorama</strong> requires <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields (ACF)</a> to function properly. Please install and activate ACF.</p></div>';
    }
}
add_action('admin_notices', 'psppan_admin_notice_acf_missing');

// ============================
// = Plugin Update Management =
// ============================

function psppan_lite_check_database()
{
    $psp_database_version = get_option('psp_database_version');
    if ($psp_database_version != '2') {
        psp_database_notice();
    }
}



/**
 * Nag to pay the bills
 *
 *
 * @param
 * @return NULL
 **/
// add_action('admin_notices', 'psppan_lite_notice');

function psppan_lite_notice()
{
    if (get_option('psppan_lite_notice') != 1) { ?>
        <div class="updated">
            <p><img src="<?php echo esc_url(PROJECT_PANORAMA_URI); ?>/assets/images/panorama-logo.png" alt="Project Panorama"></p>
            <p><?php esc_html_e('Like Project Panorama Lite? We have a full featured premium version with front end task completion, automatic notifications and alot more features!', 'psp_projects'); ?> <a href="https://www.projectpanorama.com/?utm=admin-notice" target="_new"><?php esc_html_e('Check it out here.', 'psp_projects'); ?> | <a href="<?php echo esc_url(site_url()); ?>/wp-admin/index.php?psppan_no_lite_notice=0"><?php esc_html_e('No thanks!', 'psp_projects'); ?></a>.</p>
        </div>
<?php
    }
}
add_action('admin_init', 'psppan_check_lite_notice');

function psppan_check_lite_notice()
{
    if (isset($_GET['psppan_no_lite_notice']) && '0' == $_GET['panorama_ignore_db']) {
        update_option('psppan_no_lite_notice', 1);
    }
}

function psppan_duplicate_post_link($actions, $post)
{
    if ($post->post_type === 'psp_projects') {
        $clone_url = wp_nonce_url(
            admin_url('admin-post.php?action=psppan_duplicate_post&post_id=' . $post->ID),
            'psppan_duplicate_post_' . $post->ID
        );

        $actions['duplicate'] = sprintf(
            '<a href="%s" title="%s" rel="permalink">%s</a>',
            esc_url($clone_url),
            esc_attr__('Duplicate this item', 'psp_projects'),
            esc_html__('Clone', 'psp_projects')
        );
    }
    return $actions;
}
add_filter('post_row_actions', 'psppan_duplicate_post_link', 10, 2);
add_filter('page_row_actions', 'psppan_duplicate_post_link', 10, 2);


function psppan_duplicate_post()
{
    if (!isset($_GET['post_id']) || !isset($_GET['_wpnonce'])) {
        wp_die('No post to duplicate has been supplied!');
    }

    $post_id = absint($_GET['post_id']);
    if (!wp_verify_nonce($_GET['_wpnonce'], 'psppan_duplicate_post_' . $post_id)) {
        wp_die('Security check failed.');
    }

    $post = get_post($post_id);
    if (!$post) {
        wp_die('Post not found.');
    }

    // Create a new post object
    $new_post = array(
        'post_title'   => $post->post_title . ' (Duplicate)',
        'post_content' => $post->post_content,
        'post_status'  => 'draft',
        'post_type'    => $post->post_type,
        'post_author'  => get_current_user_id(),
    );

    // Insert the duplicated post
    $new_post_id = wp_insert_post($new_post);

    // Copy custom fields (including serialized data)
    $meta_data = get_post_meta($post_id);
    if ($meta_data) {
        foreach ($meta_data as $key => $values) {
            foreach ($values as $value) {
                update_post_meta($new_post_id, $key, maybe_unserialize($value));
            }
        }
    }

    // Copy taxonomies (categories, tags, custom taxonomies)
    $taxonomies = get_object_taxonomies($post->post_type);
    foreach ($taxonomies as $taxonomy) {
        $terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'ids'));
        if (!empty($terms)) {
            wp_set_object_terms($new_post_id, $terms, $taxonomy);
        }
    }

    // Copy featured image (post thumbnail)
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) {
        $new_thumbnail_id = psppan_duplicate_post_thumbnail($thumbnail_id, $new_post_id);
        if ($new_thumbnail_id) {
            set_post_thumbnail($new_post_id, $new_thumbnail_id);
        }
    }

    // Redirect to the new post edit page
    wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
    exit;
}
add_action('admin_post_psppan_duplicate_post', 'psppan_duplicate_post');

function psppan_duplicate_post_thumbnail($thumbnail_id, $new_post_id)
{
    $thumbnail = get_post($thumbnail_id);
    if (!$thumbnail) {
        return false;
    }

    // Get thumbnail file
    $file_path = get_attached_file($thumbnail_id);
    $file_info = pathinfo($file_path);
    $new_file = $file_info['dirname'] . '/' . wp_unique_filename($file_info['dirname'], $file_info['basename']);

    if (!copy($file_path, $new_file)) {
        return false;
    }

    // Create a new attachment
    $attachment = array(
        'guid'           => str_replace(basename($thumbnail->guid), basename($new_file), $thumbnail->guid),
        'post_mime_type' => $thumbnail->post_mime_type,
        'post_title'     => $thumbnail->post_title . ' (Duplicate)',
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $new_attachment_id = wp_insert_attachment($attachment, $new_file, $new_post_id);
    if (!$new_attachment_id) {
        return false;
    }

    // Generate new attachment metadata
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($new_attachment_id, $new_file);
    wp_update_attachment_metadata($new_attachment_id, $attach_data);

    return $new_attachment_id;
}

function psppan_duplicate_post_admin_bar($wp_admin_bar) {
    // Only show in admin area
    if (!is_admin()) {
        return;
    }

    // Check for valid post ID and correct post type
    if (!isset($_GET['post']) || get_post_type($_GET['post']) !== 'psp_projects') {
        return;
    }

    $post_id = absint($_GET['post']);

    // Generate secure duplication URL
    $url = wp_nonce_url(
        admin_url('admin-post.php?action=psppan_duplicate_post&post_id=' . $post_id),
        'psppan_duplicate_post_' . $post_id
    );

    // Add a node to the admin bar
    $wp_admin_bar->add_node(array(
        'id'    => 'psppan_duplicate_post',
        'title' => esc_html__('Duplicate Project', 'psp_projects'),
        'href'  => esc_url($url),
        'meta'  => array(
            'title' => esc_attr__('Duplicate this project', 'psp_projects'),
        ),
    ));
}
add_action('admin_bar_menu', 'psppan_duplicate_post_admin_bar', 100);

