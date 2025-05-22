<?php

// ====================
// = Custom Templates =
// ====================

/*
	|--------------------------------------------------------------------------
	| CONSTANTS
	|--------------------------------------------------------------------------
	*/
if (! defined('ABSPATH')) {
	exit;
}
if (! defined('PSPPAN_BASE_FILE'))
	define('PSPPAN_BASE_FILE', __FILE__);
if (! defined('PSPPAN_BASE_DIR'))
	define('PSPPAN_BASE_DIR', dirname(PSPPAN_BASE_FILE));
if (! defined('PSPPAN_PLUGIN_URL'))
	define('PSPPAN_PLUGIN_URL', plugin_dir_url(__FILE__));


add_filter('template_include', 'psppan_template_chooser');

function psppan_template_chooser($template)
{
	// Post ID
	$post_id = get_the_ID();

	// For all other CPT
	if (get_post_type($post_id) != 'psp_projects') {
		return $template;
	}

	// Else use custom template
	if (is_single()) {
		if (PSP_PLUGIN_TYPE == 'professional') {
			return psppan_template_hierarchy('single');
		} else {
			return psppan_template_hierarchy('single-lite');
		}
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

	// Check if a custom template exists in the theme folder, if not, load the plugin template file
	if ($theme_file = locate_template(array('psp-templates/' . $template))) {
		$file = $theme_file;
	} else {
		$file = PSPPAN_BASE_DIR . '/templates/' . $template;
	}

	return apply_filters('rc_repl_template_' . $template, $file);
}
