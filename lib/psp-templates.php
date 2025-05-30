<?php

// ====================
// = Custom Templates =
// ====================

/*
  |--------------------------------------------------------------------------
  | CONSTANTS
  |--------------------------------------------------------------------------
  */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (! defined('PSPPAN_BASE_FILE'))
  define('PSPPAN_BASE_FILE', __FILE__);
if (! defined('PSPPAN_BASE_DIR'))
  define('PSPPAN_BASE_DIR', dirname(PSPPAN_BASE_FILE));
if (! defined('PSPPAN_PLUGIN_URL'))
  define('PSPPAN_PLUGIN_URL', plugin_dir_url(__FILE__));


add_filter('template_include', 'psppan_template_chooser', 9999);

function psppan_template_chooser($template)
{

  // Post ID
  $post_id = get_the_ID();

  // If this isn't a Panorama project or Panorama archive, return as normal
  if (get_post_type($post_id) != 'psp_projects' && !is_post_type_archive('psp_projects')) {
    return $template;
  }

  $use_custom_template  = get_option('psp_use_custom_template');
  $custom_template      = get_option('psppan_custom_template');

  if (!psp_lite_check_for_access()) {
    return PROJECT_PANORAMA_DIR . '/lib/templates/login.php';
  }

  // Is this a single project
  if (is_single()) {

    if (PSP_PLUGIN_TYPE == 'professional') {
      $psp_type = 'single';
    } else {
      $psp_type = 'single-lite';
    }

    if ($use_custom_template && !empty($custom_template)) {
      return psppan_custom_template($custom_template);
    }

    return psppan_template_hierarchy($psp_type);
  }

  // Is this an archive TODO: Add support for lite version and custom template
  if (is_post_type_archive()) {
    return psppan_template_hierarchy('archive-psp_projects');
  }
}

/**
 * Get the custom template if is set
 *
 * @since 1.0
 */

function psppan_template_hierarchy($template)
{

  // Get the template slug
  $template_slug = rtrim($template, '.php');
  $template = $template_slug . '.php';

  if ($theme_file = locate_template(array('panorama/' . $template))) {
    $file = $theme_file;
  } else {
    $file = PSPPAN_BASE_DIR . '/templates/' . $template;
  }

  return apply_filters('rc_repl_template' . $template, $file);
}

function psppan_custom_template($template)
{

  if ($theme_file = locate_template(array($template))) {

    $file = $theme_file;

    return apply_filters('rc_repl_template' . $template, $file);
  } else {

    psppan_template_hierarchy($template);
  }
}

function eddpan_panorama_inject_into_custom_template($content)
{

  global $post;

  if (empty($post)) return;


  $use_custom_template = get_option('psp_use_custom_template');
  $custom_template = get_option('psppan_custom_template');
  $custom_template = !empty($custom_template) ? $custom_template : false;

  if (is_single() && (bool) $use_custom_template && $custom_template && get_post_type($post->ID) == 'psp_projects') {

    $post->comment_status = "closed";

    ob_start();
    include PSPPAN_BASE_DIR . '/templates/custom-template-single.php';
    $content = ob_get_contents();
    ob_end_clean();
  }

  return $content;
}
add_filter('the_content', 'eddpan_panorama_inject_into_custom_template');
