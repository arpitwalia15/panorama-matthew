<?php

// ==============
// = Data Model =
// ==============

/* The custom post types and taxonomies */

// Register Custom Post Type
function psp_projects()
{

	$psp_slug = get_option('psp_slug', 'panorama');

	$labels = array(
		'name'                => _x('Projects', 'Post Type General Name', 'psp_projects'),
		'singular_name'       => _x('Project', 'Post Type Singular Name', 'psp_projects'),
		'menu_name'           => __('Projects', 'psp_projects'),
		'parent_item_colon'   => __('Parent Project:', 'psp_projects'),
		'all_items'           => __('All Projects', 'psp_projects'),
		'view_item'           => __('View Project', 'psp_projects'),
		'add_new_item'        => __('Add New Project', 'psp_projects'),
		'add_new'             => __('New Project', 'psp_projects'),
		'edit_item'           => __('Edit Project', 'psp_projects'),
		'update_item'         => __('Update Project', 'psp_projects'),
		'search_items'        => __('Search Projects', 'psp_projects'),
		'not_found'           => __('No projects found', 'psp_projects'),
		'not_found_in_trash'  => __('No projects found in Trash', 'psp_projects'),
	);
	$rewrite = array(
		'slug'                => $psp_slug,
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __('psp_projects', 'psp_projects'),
		'description'         => __('Projects', 'psp_projects'),
		'labels'              => $labels,
		'supports'            => array('title', 'comments', 'revisions',),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => array('psp_project', 'psp_projects'),
		//            'capability_type'     => array('psp_project','psp_projects'),
		'map_meta_cap'        => true,
	);
	register_post_type('psp_projects', $args);
}

// Hook into the 'init' action
add_action('init', 'psp_projects');

// Modify custom post type display

// Modify custom post type display

function psppan_project_header($defaults)
{

	$new = array();

	foreach ($defaults as $key => $title) {

		if ($key == 'title') {
			$new[$key] 			= $title;
			$new['client'] 		= __('Client', 'psp_projects');
			$new['assigned'] 	= __('Users', 'psp_projects');
			$new['td-progress'] = __('% Complete', 'psp_projects');
			$new['timing'] 		= __('Time Elapsed', 'psp_projects');
		} else {
			$new[$key] = $title;
		}
	}

	return $new;
}

function psppan_project_header_content($column_name, $post_ID)
{
	if ($column_name == 'client') {
		$client = get_field('client');
		if ($client) {
			echo wp_kses_post($client);
		}
	} elseif ($column_name == 'td-progress') {
		$completed = psp_compute_progress($post_ID);
		if ($completed > 10) {
			echo '<p class="psp-progress"><span class="psp-' . esc_attr($completed) . '"><strong>%' . esc_attr($completed) . '</strong></span></p>';
		} else {
			echo '<p class="psp-progress"><span class="psp-' . esc_attr($completed) . '"></span></p>';
		}
	} elseif ($column_name == 'assigned') {

		if (have_rows('allowed_users', $post_ID)) {
			while (have_rows('allowed_users', $post_ID)) {
				the_row();

				$user = get_sub_field('user');
				echo esc_html(psppan_user_name($user));
			}
		}
	} elseif ($column_name == 'timing') {

		psp_the_timing_bar($post_ID);
	}
}

add_filter('manage_psp_projects_posts_columns', 'psppan_project_header');
add_action('manage_psp_projects_posts_custom_column', 'psppan_project_header_content', 10, 2);


// Get the users name

function psppan_user_name($user)
{

	if (empty($user)) {
		return;
	}

	if (!empty($user['user_firstname']) && !empty($user['user_lastname'])) {
		$name = $user['user_firstname'] . ' ' . $user['user_lastname'];
	} else {
		$name = $user['user_nicename'];
	}

	return '<p class="psp_user_assigned"><a href="edit.php?post_type=psp_projects&page=psp_user_list&user=' . $user['ID'] . '">' . $user['user_avatar'] . ' <span>' . $name . '</span></a></p>';
}

// Custom Project Taxonomies

function psppan_project_taxonomy()
{

	$labels = array(
		'name'                       => _x('Project Types', 'Taxonomy General Name', 'psp_projects'),
		'singular_name'              => _x('Project Type', 'Taxonomy Singular Name', 'psp_projects'),
		'menu_name'                  => __('Project Types', 'psp_projects'),
		'all_items'                  => __('All Project Types', 'psp_projects'),
		'parent_item'                => __('Parent Project Type', 'psp_projects'),
		'parent_item_colon'          => __('Parent Project Type:', 'psp_projects'),
		'new_item_name'              => __('New Project Type', 'psp_projects'),
		'add_new_item'               => __('Add Project Type', 'psp_projects'),
		'edit_item'                  => __('Edit Project Type', 'psp_projects'),
		'update_item'                => __('Update Project Type', 'psp_projects'),
		'separate_items_with_commas' => __('Separate items with commas', 'psp_projects'),
		'search_items'               => __('Search Project Types', 'psp_projects'),
		'add_or_remove_items'        => __('Add or remove project types', 'psp_projects'),
		'choose_from_most_used'      => __('Choose from the most used items', 'psp_projects'),
		'not_found'                  => __('Not Found', 'psp_projects'),
	);
	$rewrite = array(
		'slug'                       => 'panorama-projects',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite
	);
	register_taxonomy('psppan_tax', array('psp_projects'), $args);

	$labels = array(
		'name'                       => _x('Project Status', 'Taxonomy General Name', 'psp_projects'),
		'singular_name'              => _x('Project Status', 'Taxonomy Singular Name', 'psp_projects'),
		'menu_name'                  => __('Project Status', 'psp_projects'),
		'all_items'                  => __('All Project Statuses', 'psp_projects'),
		'parent_item'                => __('Parent Project Status', 'psp_projects'),
		'parent_item_colon'          => __('Parent Project Status:', 'psp_projects'),
		'new_item_name'              => __('New Project Status', 'psp_projects'),
		'add_new_item'               => __('Add Project Status', 'psp_projects'),
		'edit_item'                  => __('Edit Project Status', 'psp_projects'),
		'update_item'                => __('Update Project Status', 'psp_projects'),
		'separate_items_with_commas' => __('Separate items with commas', 'psp_projects'),
		'search_items'               => __('Search Project Statuses', 'psp_projects'),
		'add_or_remove_items'        => __('Add or remove project statues', 'psp_projects'),
		'choose_from_most_used'      => __('Choose from the most used items', 'psp_projects'),
		'not_found'                  => __('Not Found', 'psp_projects'),
	);
	$rewrite = array(
		'slug'                       => 'panorama-status',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => false,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite
	);
	register_taxonomy('psppan_status', array('psp_projects'), $args);
}

// Hook into the 'init' action
add_action('init', 'psppan_project_taxonomy', 0);

add_action('save_post', 'psppan_mark_as_complete', 10, 2);
function psppan_mark_as_complete($post_id, $post)
{

	// Bail if we're doing an auto save
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	$project_completion = psp_compute_progress($post->ID);
	$current_status = get_post_meta($post->ID, '_psp_completed', true);

	if ($current_status == '') {
		update_post_meta($post->ID, '_psp_completed', '0');
	}

	if ($project_completion == '100') {
		update_post_meta($post->ID, '_psp_completed', '1');
		wp_set_post_terms($post->ID, 'completed', 'psppan_status');
	} else {
		update_post_meta($post->ID, '_psp_completed', '0');
		wp_set_post_terms($post->ID, 'incomplete', 'psppan_status');
	}

	if (get_post_type($post_id) == 'psp_projects') {
		update_post_meta($post->ID, '_psp_lite_project', '1');
	}
}

add_filter('views_edit-psp_projects', 'psppan_update_project_quicklinks', 1);
function psppan_update_project_quicklinks($views)
{
	// Only allow users who can edit posts (or manage projects)
	if (!current_user_can('edit_posts')) {
		return $views;
	}

	$published_projects = psp_get_all_my_projects('publish');
	$draft_projects = psp_get_all_my_projects('draft');
	$trash_projects = psp_get_all_my_projects('trash');

	$my_projects = psp_get_all_my_projects();
	$project_overview = psp_my_projects_overview($my_projects);

	// Reset defaults
	$completed_class = '';
	$publish_class = '';
	$draft_class = '';
	$trash_class = '';
	$active_class = '';

	$post_status = '';
	if (isset($_GET['post_status'])) {
		$post_status = sanitize_key($_GET['post_status']);
	}

	switch ($post_status) {
		case 'completed':
			$completed_class = 'current';
			break;
		case 'publish':
			$publish_class = 'current';
			break;
		case 'draft':
			$draft_class = 'current';
			break;
		case 'trash':
			$trash_class = 'current';
			break;
		default:
			$active_class = 'current';
			break;
	}

	$views['all'] = '<a class="' . $active_class . '" href="edit.php?post_type=psp_projects">Active <span class="count">(' . $project_overview['active'] . ')</span></a>';

	if ($published_projects->post_count > 0) {
		$views['publish'] = '<a class="' . $publish_class . '" href="edit.php?post_status=publish&post_type=psp_projects">Published <span class="count">(' . $published_projects->post_count . ')</span></a>';
	}

	if ($draft_projects->post_count > 0) {
		$views['draft'] = '<a class="' . $draft_class . '" href="edit.php?post_status=draft&post_type=psp_projects">Draft <span class="count">(' . $draft_projects->post_count . ')</span></a>';
	}

	if ($trash_projects->post_count > 0) {
		$views['trash'] = '<a class="' . $trash_class . '" href="edit.php?post_status=trash&post_type=psp_projects">Trash <span class="count">(' . $trash_projects->post_count . ')</span></a>';
	}

	$completed_link = '<a class="' . $completed_class . '" href="edit.php?post_type=psp_projects&post_status=completed">' . __('Completed', 'psp_projects') . ' <span class="count">(' . $project_overview['completed'] . ')</span></a>';

	// Insert 'completed' after 'all'
	$views = array_slice($views, 0, 1, true) +
		['completed' => $completed_link] +
		array_slice($views, 1, null, true);

	return $views;
}

add_action('admin_menu', 'psppan_lite_add_extra_links', 999);
function psppan_lite_add_extra_links()
{

	global $submenu;
	$submenu['edit.php?post_type=psp_projects'][] = array(__('Dashboard', 'psp_projects'), 'read', get_post_type_archive_link('psp_projects'));
}
