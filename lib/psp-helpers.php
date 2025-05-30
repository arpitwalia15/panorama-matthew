<?php



/**

 * psp-helpers.php

 * A library of helper and utility functions for Project Panorama

 *

 * @author Ross Johnson

 * @copyright 3.7 MEDIA

 * @license GNU GPL version 3 (or later) {@see license.txt}

 * @package panorama

 **/



/**

 * Builds a list of tasks and returns an array of list items and task count

 *

 *

 * @param integer $id post ID

 * @param string $taskStyle (optional) for shortcodes, the type of tasks to return

 * @return array including a collection of tasks in list format and a count of items

 **/



function psppan_populate_tasks($id, $taskStyle, $phase_id)
{



	if (empty($id)) {

		global $post;

		$id = $post->ID;
	}



	include(psp_template_hierarchy('/parts/tasks.php'));



	return $taskList;
}



/**

 * Checks to see if the user can edit the current project, returns true or false

 *

 *

 * @param integer $id post ID

 * @return bool

 **/



function psppan_can_edit_project($id)
{

	if (current_user_can('publish_psp_projects')) {



		// User can publish projects, so they can edit all projects

		return true;
	} elseif (current_user_can('edit_psp_projects')) {



		// User can edit projects but not publish, see if they are assigned to this project



		$current_user = wp_get_current_user();

		$allowed_users = get_field('allowed_users', $id);



		// Loop through all the allowed users and add them to an array

		if (!empty($allowed_users)) {

			foreach ($allowed_users as $allowed_user) {



				// If the current user matches this user ID return true

				if ($current_user->ID == $allowed_user['user']['ID']) {

					return true;
				}
			} //end.foreach

		} //end.if



	} else {



		// User can't edit this project, return false

		return false;
	}
}



function psppan_can_edit_task($post_id, $phase_id, $task_id)
{



	if (psppan_can_edit_project($post_id)) {



		return true;
	} else {



		$current_user = wp_get_current_user();



		$phases = get_field('phases', $post_id);



		if ($phases[$phase_id]['tasks'][$task_id]['assigned'] == $current_user->ID) {
			return true;
		}
	}



	return false;
}



/**

 * Computes the total project progress and outputs it with a visual indication by phase

 *

 *

 * @param integer $id post ID

 * @return HTML markup of a progress bar

 **/



function psppan_phased_complete($id)
{



	if (get_field('automatic_progress', $id)) {



		$completed = 0;

		$phases = 0;

		$phase_completion = 0;

		$phase_total = 0;



		$total_phases = count(get_field('phases', $id));



		$phase_breakdown = array();



		while (has_sub_field('phases', $id)) {



			$phase_info = array();



			// Add the row number into the array

			array_push($phase_info, $phases);



			if (get_sub_field('weighting')) {

				$phases++;

				$phase_total += 100 * get_sub_field('weight');
			} else {

				$phases++;

				$phase_total += 100;
			}



			if (get_sub_field('auto_progress')) {



				$tasks = 0;

				$task_completion = 0;



				while (has_sub_field('tasks')) {

					$tasks++;

					$task_completion += get_sub_field('status');
				}



				if ($tasks > 1) {



					if (get_sub_field('weighting')) {



						$relative_status = ceil($task_completion / $tasks / $total_phases * get_sub_field('weight'));

						$actual_status = ceil($task_completion / $tasks);



						array_push($phase_info, $relative_status);

						array_push($phase_info, $actual_status);
					} else {



						$relative_status = ceil($task_completion / $tasks / $total_phases);

						$actual_status = ceil($task_completion / $tasks);



						array_push($phase_info, $relative_status);

						array_push($phase_info, $actual_status);
					}
				} else {

					$phase_completion += $task_completion;

					array_push($phase_info, $task_completion);

					array_push($phase_info, $task_completion);
				}
			} else {

				$phase_completion += get_sub_field('percent_complete');



				$phase_total = ceil(get_sub_field('percent_complete') / $total_phases);



				array_push($phase_info, $phase_total);

				array_push($phase_info, get_sub_field('percent_complete'));
			}



			array_push($phase_breakdown, $phase_info);
		}



		if ($phase_total != 0) {



			echo '<p class="psp-progress">';

			$c = 1;

			$phases = get_field('phases');



			foreach ($phase_breakdown as $phase) {



				$pid = $phase[0];



				if ($c == 1) {

					$color = 'blue';

					$chex = '#3299BB';
				} elseif ($c == 2) {

					$color = 'teal';

					$chex = '#4ECDC4';
				} elseif ($c == 3) {

					$color = 'green';

					$chex = '#CBE86B';
				} elseif ($c == 4) {

					$color = 'pink';

					$chex = '#FF6B6B';
				} elseif ($c == 5) {

					$color = 'maroon';

					$chex = '#C44D58';

					$c = 0;
				}



				echo '<span class="psp-' . esc_attr($phase[1]) . ' color-' . esc_attr($color) . '" title="' . esc_attr($phases[$pid]['title']) . ' - ' . esc_attr($phase[2]) . '% complete"></span>';

				$c++;
			}

			// return ceil($phase_completion / $phase_total * 100);

			echo '</p>';
		} else {

			echo '<p class="psp-progress"><span class="psp-0"><b>0%</b></span></p>';
		}
	} else {



		echo '<p class="psp-progress"><span class="psp-' . esc_attr(get_field('percent_complete', $id)) . '"><b>' . esc_html(get_field('percent_complete', $id)) . '%</b></span></p>';
	}
}



/**

 * Computes the total project progress based on total number of hours

 *

 *

 * @param integer $id post ID

 * @return a string containing a number from 1 - 100 (percentage of completion)

 **/



function psppan_hourly_progress($id)
{

	// Count the number of hours



	$total_hours = 0;

	$completed_hours = 0;



	while (has_sub_field('phases', $id)) {



		$tasks = 0;

		$task_completion = 0;

		$phase_total = 0;



		$total_hours += get_sub_field('hours');

		$phase_hours = get_sub_field('hours');



		while (has_sub_field('tasks')) {

			$tasks++;

			$task_completion += get_sub_field('status');
		}



		// If a phase doesn't have any tasks, skip

		if ($tasks == 0) {
			continue;
		}



		$phase_total = $tasks * 100;

		$completed_hours += (($task_completion / $phase_total) * $phase_hours);
	}



	if ($completed_hours != 0) {



		return ceil($completed_hours / $total_hours * 100);
	} else {



		return '0';
	}
}



/**

 * Computes the total project progress by weighting the phases

 *

 *

 * @param integer $id post ID

 * @return a string containing a number from 1 - 100 (percentage of completion)

 **/



function psppan_weighted_progress($id)
{



	$phase_completion = 0;

	$phase_total = 0;



	while (has_sub_field('phases', $id)) {



		$weight = get_sub_field('weight');



		if (empty($weight)) {

			$phase_total += 100;
		} else {

			$phase_total += 100 * get_sub_field('weight');
		}

		$tasks = 0;
		$task_completion = 0;



		while (has_sub_field('tasks')) {

			$tasks++;

			$task_completion += get_sub_field('status');
		}



		if ($tasks > 1) {



			$weight = get_sub_field('weight');

			if (empty($weight)) {
				$weight = 1;
			}



			$phase_completion += ceil($task_completion / $tasks * $weight);
		} else {

			$phase_completion += $task_completion;
		}
	}



	if ($phase_total != 0) {

		return ceil($phase_completion / $phase_total * 100);
	} else {

		return '0';
	}
}



function psppan_lite_get_phase_completion($post_id = null, $phase = null)
{



	$post_id = ($post_id == null ? get_the_ID() : $post_id);



	if (empty($phase)) {

		return;
	}



	if (get_field('automatic_progress', $post_id) && get_field('phases_automatic_progress', $post_id)) {



		$progress = 0;



		if (isset($phase['tasks']) && !empty($phase['tasks'])) {



			$task_stats = array(

				'count'			=>	0,

				'completion'	=>	0

			);



			foreach ($phase['tasks'] as $task) {



				$task_stats['count']++;

				if (isset($task['complete'])) {

					$task_stats['completion'] += $task['complete'];
				}
			}



			if ($task_stats['count'] > 0) {

				$progress = ceil($task_stats['completion'] / $task_stats['count']);
			}
		}



		return $progress;
	}



	// Standard phase calculation

	return (isset($phase['percentage_complete']) ? $phase['percentage_complete'] : 0);
}



/**

 * Computes the total project progress with no weighting or hours

 *

 *

 * @param integer $id post ID

 * @return a string containing a number from 1 - 100 (percentage of completion)

 **/



function psppan_standard_progress($post_id)
{



	$stats = array(

		'phase_completion'  => 0,

		'phase_total'		=> 0,

		'phases'			=> 0,

	);



	$phases = get_post_meta($post_id, '_pano_phases', true);



	if (empty($phases)) {

		return 0;
	}



	foreach ($phases as $phase) {



		$stats['phases']++;

		$stats['phase_total'] += 100;



		if (isset($phase['tasks']) && !empty($phase['tasks'])) {



			$task_stats = array(

				'count'			=>	0,

				'completion'	=>	0

			);



			foreach ($phase['tasks'] as $task) {



				$task_stats['count']++;

				if (isset($task['complete'])) {

					$task_stats['completion'] += $task['complete'];
				}
			}



			if ($task_stats['count'] > 0) {

				$stats['phase_completion'] += ceil($task_stats['completion'] / $task_stats['count']);
			}
		}
	}



	if ($stats['phase_total'] == 0) {

		return 0;
	}



	return ceil($stats['phase_completion'] / $stats['phase_total'] * 100);
}



/**

 * Computes the total project progress

 *

 *

 * @param integer $id post ID

 * @return a string containing a number from 1 - 100 (percentage of completion)

 **/



function psppan_compute_progress($post_id)
{



	if (get_field('automatic_progress', $post_id) && get_field('phases_automatic_progress', $post_id)) {

		return psppan_standard_progress($post_id);
	}



	if (get_field('automatic_progress', $post_id)) {



		$phases_stats = array(

			'count'			=>	0,

			'completion'	=>	0,

			'total'			=>	0

		);



		$phases = get_post_meta($post_id, '_pano_phases', true);



		if (empty($phases)) {

			return 0;
		}



		foreach ($phases as $phase) {



			$phase_stats['count']++;



			$phase_stats['completion'] += intval($phase['percentage_complete']);
		}



		$phase_stats['total'] = $phase_stats['count'] * 100;



		if ($phase_stats['total'] == 0) {

			return 0;
		}
	}



	// Not automatically computing progress, just return the slider value

	return get_field('percent_complete', $post_id);
}





/**

 * Checks to see if the user has access to the project, returns 1 if access is granted, 0 if false

 *

 *

 * @param integer $post_id post ID

 * @return int 1 or 0

 **/



function pan_panorama_check_access($post_id)
{



	$access_level = get_field('restrict_access_to_specific_users', $post_id);



	if (current_user_can('manage_options')):



		return 1;

	elseif (post_password_required()):



		return 0;

	elseif (!$access_level):



		return 1;

	elseif (($access_level) && (!is_user_logged_in())):



		return 0;



	elseif (($access_level) && (is_user_logged_in())):



		$allowed_users = array();

		$current_user = wp_get_current_user();



		while (has_sub_field('allowed_users', $post_id)):



			$allowed_user = get_sub_field('user');



			array_push($allowed_users, $allowed_user['ID']);



		endwhile;



		foreach ($allowed_users as $user):



			echo '<p>Current user ID is ' . esc_html($current_user->ID) . ' </p>';



			if ($user == $current_user->ID) {



				return 1;
			}



		endforeach;

	else:

		return 1;

	endif;
}





// custom filter to replace '=' with 'LIKE'

function psppan_posts_where($where)

{

	$where = str_replace("meta_key = 'allowed_users_%_user'", "meta_key LIKE 'allowed_users_%_user'", $where);



	return $where;
}



add_filter('posts_where', 'psppan_posts_where');





/*

 * Limits what projects are available to the ones the user has access to

 *

 */

	

add_filter('pre_get_posts', 'pan_limit_psp_to_granted_users');

function pan_limit_psp_to_granted_users($query)
{



	global $pagenow;

	$user_ID = get_current_user_id();



	// Check to see if were in the admin panel and project edit page



	if (($query->is_admin) && ($pagenow == 'edit.php') && ($_GET['post_type'] == 'psp_projects')) {



		// If the users is an admin, they can see everything

		if ((!current_user_can('publish_pages')) && (psppan_get_current_user_role() != 'Project Manager')) {



			// Users can see open projects and projects they have access to

			$query->set(
				'meta_query',

				array(

					'relation' => 'OR',

					array(

						'key' => 'allowed_users_%_user',

						'value' => $user_ID

					),

					array(

						'key' => 'restrict_access_to_specific_users',

						'value' => ''

					)

				)

			);
		}
	}
}



add_filter('pre_get_posts', 'psppan_limit_to_completed_projects', 99999);

function psppan_limit_to_completed_projects($query)
{



	global $pagenow;



	if (isset($_GET['post_status'])) {



		if (($pagenow == 'edit.php') && ($_GET['post_type'] == 'psp_projects') && ($query->is_main_query())) {



			if ($_GET['post_status'] == 'completed') {



				$query->set(
					'tax_query',
					array(

						array(

							'taxonomy'	=>	'psp_status',

							'field'		=>	'slug',

							'terms'		=>	'completed',

						)

					)

				);
			}
		}
	} elseif (($pagenow == 'edit.php') && ($_GET['post_type'] == 'psp_projects') && ($query->is_main_query())) {



		$query->set(
			'tax_query',
			array(

				array(

					'taxonomy'	=>	'psp_status',

					'field'		=>	'slug',

					'terms'		=>	'completed',

					'operator'	=>	'NOT IN'

				),

			)

		);



		$query->set('post_status', 'publish');
	}
}



/*

 * Adds two roles for users

 *

 */



add_action('admin_init', 'psppan_add_project_roles');

function psppan_add_project_roles()
{



	add_role(
		'psp_project_owner',

		'Project Owner',

		array(

			'read' => true,

			'edit_posts' => false,

			'delete_posts' => false,

			'publish_posts' => false,

			'upload_files' => true,

		)

	);



	add_role(
		'psp_project_manager',

		'Project Manager',

		array(

			'read' => true,

			'edit_posts' => false,

			'delete_posts' => false,

			'publish_posts' => false,

			'upload_files' => true,

		)

	);
}



/*

* Assigns capabilities to the project roles

*/





function psppan_make_role_project_owner($role)
{



	$role->add_cap('edit_psp_project');

	$role->add_cap('edit_psp_projects');

	$role->add_cap('edit_others_psp_projects');

	$role->add_cap('edit_published_psp_projects');

	$role->add_cap('read_psp_project');

	$role->add_cap('read_private_psp_project');
}



function psppan_make_role_project_manager($role)
{



	$role->add_cap('read');

	$role->add_cap('read_psp_project');

	$role->add_cap('read_private_psp_projects');



	$role->add_cap('edit_psp_project');

	$role->add_cap('edit_psp_projects');

	$role->add_cap('edit_others_psp_projects');

	$role->add_cap('edit_published_psp_projects');



	$role->add_cap('publish_psp_projects');



	$role->add_cap('delete_psp_projects');

	$role->add_cap('delete_others_psp_projects');

	$role->add_cap('delete_private_psp_projects');

	$role->add_cap('delete_published_psp_projects');



	$role->add_cap('copy_posts');
}



add_action('admin_init', 'psppan_add_role_caps', 999);

function psppan_add_role_caps()
{



	$owners = get_role('psp_project_owner');

	if (!empty($owners)) {

		psppan_make_role_project_owner($owners);
	}



	$manager = get_role('psp_project_manager');

	if (!empty($manager)) {

		psppan_make_role_project_manager($manager);
	}



	$admin = get_role('administrator');

	if (!empty($admin)) {

		psppan_make_role_project_manager($admin);
	}



	$editor = get_role('editor');

	if (!empty($editor)) {

		psppan_make_role_project_manager($editor);
	}
}



/*

    Remove the add button for project owners

*/



add_action('admin_menu', 'psppan_remove_add_project');

function psppan_remove_add_project()
{



	global $submenu;

	if (psppan_get_current_user_role() == 'Project Owner') {



		$submenu['edit.php?post_type=psp_projects'][10][1] = '';
	}
}



/**

 * Returns the translated role of the current user. If that user has

 * no role for the current blog, it returns false.

 *

 * @return string The name of the current role

 **/

function psppan_get_current_user_role()
{

	global $wp_roles;

	$current_user = wp_get_current_user();

	$roles = $current_user->roles;

	$role = array_shift($roles);

	return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role]) : false;
}



/**

 * Outputs a list of projects assigned to a particular user

 *

 * @return HTML table

 **/



add_action('admin_menu', 'projpa82_register_user_project_list');

function projpa82_register_user_project_list()
{
	add_submenu_page(
		'project-panorama',             // ✅ Use the correct parent slug
		'Projects By User',
		'Projects by User',
		'manage_options',
		'projpa82_user_list',
		'projpa82_user_project_list'
	);
}




function projpa82_user_project_list()
{
	// Permission check
	if (!current_user_can('edit_posts')) {
		wp_die(__('You do not have permission to view this page.', 'psp_projects'));
	}

	// Sanitize and validate the 'user' GET param
	$user_id = isset($_GET['user']) ? absint($_GET['user']) : 0;
	if (!$user_id || !get_user_by('id', $user_id)) {
		wp_die(__('Invalid user ID.', 'psp_projects'));
	}

	$user = get_user_by('id', $user_id);
	$username = esc_html(psppan_username_by_id($user_id));
?>

	<div class="wrap">
		<h2 class="psp-user-list-title">
			<?php echo get_avatar($user_id); ?>
			<span><?php esc_html_e('Projects Assigned to', 'psp_projects'); ?> <?php echo $username; ?></span>
		</h2>

		<br style="clear:both">

		<table id="psp-user-list-table" class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th><?php esc_html_e('Title', 'psp_projects'); ?></th>
					<th><?php esc_html_e('Client', 'psp_projects'); ?></th>
					<th><?php esc_html_e('% Complete', 'psp_projects'); ?></th>
					<th><?php esc_html_e('Timing', 'psp_projects'); ?></th>
					<th><?php esc_html_e('Project Types', 'psp_projects'); ?></th>
					<th><span class="vers" title="Comments" class="comment-grey-bubble"></span></th>
					<th><?php esc_html_e('Last Updated', 'psp_projects'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$args = array(
					'post_type' => 'psp_projects',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => 'allowed_users_%_user',
							'value' => $user_id
						)
					)
				);

				$projects = new WP_Query($args);
				$i = 0;

				while ($projects->have_posts()) : $projects->the_post();
					global $post;
				?>
					<tr <?php if ($i % 2 === 0) echo 'class="alternate"'; ?>>
						<td>
							<strong>
								<a href="<?php echo esc_url(admin_url('post.php?post=' . $post->ID . '&action=edit')); ?>">
									<?php echo esc_html(get_the_title()); ?>
								</a>
							</strong>
						</td>

						<td><?php echo esc_html(get_field('client')); ?></td>

						<td>
							<?php
							$completed = psppan_compute_progress($post->ID);
							if ($completed > 10) {
								echo '<p class="psp-progress"><span class="psp-' . esc_attr($completed) . '"><strong>%' . esc_html($completed) . '</strong></span></p>';
							} else {
								echo '<p class="psp-progress"><span class="psp-' . esc_attr($completed) . '"></span></p>';
							}
							?>
						</td>

						<td><?php psp_the_timing_bar($post->ID); ?></td>

						<td>
							<?php
							$terms = get_the_terms($post->ID, 'psp_tax');
							if (!empty($terms) && !is_wp_error($terms)) {
								$term_names = wp_list_pluck($terms, 'name');
								echo esc_html(implode(', ', $term_names));
							}
							?>
						</td>

						<td>
							<div class="post-com-count-wrapper">
								<a href="<?php echo esc_url(admin_url('edit-comments.php?p=' . $post->ID)); ?>" class="post-com-count">
									<span class="comment-count"><?php comments_number('0', '1', '%'); ?></span>
								</a>
							</div>
						</td>

						<td><?php echo esc_html(get_the_modified_date()); ?></td>
					</tr>
				<?php $i++;
				endwhile;
				wp_reset_postdata();
				?>
			</tbody>
		</table>
	</div>

<?php
}




/**

 * Looks up a users name by ID and returns their full name (if available) or their display name as a fallback

 *

 * @return HTML table

 **/



function psppan_username_by_id($user_id)
{



	$user = get_user_by('id', $user_id);



	if (!empty($user)) {



		if ($user->first_name) {

			return $user->first_name . ' ' . $user->last_name;
		} else {

			return $user->display_name;
		}
	}
}



function psppan_get_icon_class($file)
{



	$site_url = site_url();

	$site_url = substr($site_url, 7);



	$domain = wp_parse_url($file, PHP_URL_HOST);



	if ($site_url != $domain) {

		return 'fa-link';
	}



	$ext = substr($file, -3);



	if (($ext == 'doc') || ($ext == 'odc') || ($ext == 'txt')) {

		return 'fa-file-text-o';
	}



	if (($ext == 'pdf')) {

		return 'fa-file-pdf-o';
	}



	if (($ext == 'jpg') || ($ext == 'bmp') || ($ext == 'png') || ($ext == 'tif') || ($ext == 'peg')) {

		return 'fa-file-image-o';
	}



	if (($ext == '.ai') || ($ext == 'psd') || ($ext == 'eps')) {

		return 'fa-paint-brush';
	}



	return 'fa-file-o';
}



/**

 * Counts the number of phases in the current project

 *

 *

 * @param int $post_id the id of a project

 * @return int number of phases

 **/







function psppan_get_phase_count($post_id = null)
{



	if (empty($post_id)) {



		global $post;



		$post_id = $post->ID;
	}



	$phases = get_field('phases');



	if (is_array($phases)) {

		return count($phases);
	}



	return '0';
}



/**

 * Loads an external .php file in the /lib/pro/fields directory if the file doesn't exist in the theme/panoram/fields folder

 *

 *

 * @param string $$template name of the file with or without .php

 * @return null -- includes file

 **/



function psppan_load_field_template($template)
{



	// Get the template slug

	$template_slug = rtrim($template, '.php');



	if (!function_exists('update_sub_field')) {



		// This must be ACF4 or bundled, use default fields

		$template = $template_slug . '.php';
	} else {



		// This must be ACF5, load special fields

		$template = $template_slug . '-acf5.php';
	}



	// Check if a custom template exists in the theme folder, if not, load the plugin template file

	if ($theme_file = locate_template(array('panorama/fields' . $template))) {

		$file = $theme_file;
	} else {

		$file = PSP_BASE_DIR . '/pro/fields/' . $template;
	}



	include_once($file);
}



function psppan_get_nice_username($user)
{



	$fullname = $user["user_firstname"] . ' ' . $user["user_lastname"];



	if ($fullname == ' ') {

		$username = $user["display_name"];
	} else {

		$username = $fullname;
	}



	return $username;
}



function psppan_translate_doc_status($status)
{



	if ($status == 'Approved') {

		return __('Approved', 'psp_projects');
	}



	if ($status == 'In Review') {

		return __('In Review', 'psp_projects');
	}



	if ($status == 'Revisions') {

		return __('Revisions', 'psp_projects');
	}



	if ($status == 'Rejected') {

		return __('Rejected', 'psp_projects');
	}
}



/* Lookup the current users projects, count their status and return them in an array */

function psppan_my_projects_overview($projects = null)
{



	if (empty($projects)) {



		$projects = psppan_get_all_my_projects();
	}



	$total_projects = $projects->found_posts;

	$completed_projects = 0;

	$inactive_projects = 0;



	while ($projects->have_posts()): $projects->the_post();



		global $post;



		if (has_term('completed', 'psp_status')) {

			$completed_projects++;
		}



		if (psppan_compute_progress($post->ID) == 0) {

			$inactive_projects++;
		}



	endwhile;



	$closed_projects = $completed_projects + $inactive_projects;



	if (($total_projects > 0) && ($total_projects > $closed_projects)) {

		$active_projects = $total_projects - $completed_projects - $inactive_projects;
	} else {

		$active_projects = 0;
	}



	return array(

		'total'	=>	$total_projects,

		'completed'	=>	$completed_projects,

		'inactive'	=>	$inactive_projects,

		'active'	=>	$active_projects

	);
}



/* Get all the projects assigned to the current logged in user */

function psppan_get_all_my_projects($status = null)
{



	$cuser = wp_get_current_user();



	$args = array(

		'post_type'			=>		'psp_projects',

		'posts_per_page'	=>		-1,

	);



	if (!empty($status)) {



		if ($status == 'active') {



			$status_args = array(
				'tax_query' => array(

					array(

						'taxonomy'	=>	'psp_status',

						'field'		=>	'slug',

						'terms'		=>	'completed',

						'operator'	=>	'NOT IN'

					)

				)

			);
		} else {



			$status_args = array('post_status' => $status);
		}



		$args = array_merge($args, $status_args);
	}



	if (!current_user_can('edit_others_psp_projects')) {



		$meta_query = array(

			'meta_query' => array(

				'relation' => 'OR',

				array(

					'key' 		=> '_pano_users',

					'value' 	=> strval($cuser->ID),

					'compare'	=> 'LIKE'

				),

				array(

					'key' 		=> 'restrict_access_to_specific_users',

					'value' 	=> ''

				)

			),

			'has_password'	=>	false

		);



		$args = array_merge($args, $meta_query);
	}



	if (!current_user_can('edit_others_psp_projects')) {

		psp_lite_comb_projects($projects);
	}



	// Query with the above arguments

	$projects = new WP_Query($args);



	return $projects;
}



function psppan_get_active_projects($projects = NULL)
{



	if ($projects == null) {

		return false;
	}



	$active = array();



	while ($projects->have_posts()) {



		$projects->the_post();



		if (has_term('completed', 'psp_status')) {



			continue;
		} else {



			$title = get_the_title();

			$permalink = get_permalink();



			array_push($active, array('title' => $title, 'permalink' => $permalink));
		}
	}



	return $active;
}



function psppan_get_completed_projects()
{



	$cuser = wp_get_current_user();



	$args = array(

		'post_type'			=>		'psp_projects',

		'posts_per_page'	=>		-1,

		'tax_query' => 		array(

			array(

				'taxonomy'	=>	'psp_status',

				'field'		=>	'slug',

				'terms'		=>	'completed',

			)

		)

	);



	if (!current_user_can('manage_options')) {



		$meta_query = array(

			'meta_query' => array(

				'relation' => 'OR',

				array(

					'key' => 'allowed_users_%_user',

					'value' => $cuser->ID

				),

				array(

					'key' => 'restrict_access_to_specific_users',

					'value' => ''

				)

			)

		);



		array_merge($args, $meta_query);
	}



	$projects = new WP_Query($args);



	return $projects;
}



function psppan_get_item_count($post, $user_id = NULL)
{



	$phases = 0;

	$tasks = 0;

	$completed = 0;

	$started = 0;



	while (have_rows('phases', $post->ID)) {



		the_row();



		$phases++;



		while (have_rows('tasks')) {



			the_row();



			if ($user_id == NULL) {



				$tasks++;



				if (get_sub_field('status') == 100)

					$completed++;



				if (get_sub_field('status') != 0)

					$started++;
			} elseif ($user_id == get_sub_field('assigned')) {



				$tasks++;



				if (get_sub_field('status') == 100)

					$completed++;



				if (get_sub_field('status') != 0)

					$started++;
			}
		}
	}



	return array('phases' => $phases, 'tasks' => $tasks, 'completed' => $completed, 'started' => $started);
}



function psppan_lite_populate_tasks($tasks = null)
{



	if ($tasks == null) {

		return false;
	}



	$count = array(

		'total'	 	=>	count($tasks),

		'complete'	=>	0,

	);



	foreach ($tasks as $task) {

		if (isset($task['complete']) && $task['complete'] == 100) {

			$count['complete']++;
		}
	}



	return $count;
}



add_action('wp_ajax_psppan_lite_update_task_fe', 'psppan_lite_update_task_fe');

function psppan_lite_update_task_fe()
{
	// Verify nonce
	if (
		!isset($_POST['psp_task_nonce']) ||
		!wp_verify_nonce($_POST['psp_task_nonce'], 'psp_task_action')
	) {
		wp_send_json_error(['message' => __('Security check failed', 'psp_projects')]);
	}

	// Check user capability
	if (!current_user_can('edit_posts')) {
		wp_send_json_error(['message' => __('Permission denied', 'psp_projects')]);
	}

	// Required fields check
	$reqs = array('post_id', 'task_index', 'phase_index', 'complete');
	foreach ($reqs as $post_req) {
		if (!isset($_POST[$post_req])) {
			wp_send_json_error(['message' => __('Missing data, please try again', 'psp_projects')]);
		}
	}

	// Sanitize input
	$complete     = sanitize_text_field($_POST['complete']);
	$post_id      = intval($_POST['post_id']);
	$task_index   = intval($_POST['task_index']);
	$phase_index  = intval($_POST['phase_index']) - 1; // Already decremented here

	// Validate data
	if ($post_id <= 0 || $task_index < 0 || $phase_index < 0) {
		wp_send_json_error(['message' => __('Invalid input data', 'psp_projects')]);
	}

	// Update post meta safely
	$phases = get_post_meta($post_id, '_pano_phases', true);
	if (!is_array($phases) || !isset($phases[$phase_index]['tasks'][$task_index])) {
		wp_send_json_error(['message' => __('Task not found', 'psp_projects')]);
	}

	$phases[$phase_index]['tasks'][$task_index]['complete'] = $complete;
	update_post_meta($post_id, '_pano_phases', $phases);

	wp_send_json_success();
}




add_action('wp_ajax_psppan_update_total_fe', 'psppan_update_total_fe');

function psppan_update_total_fe()
{
	// Check nonce for security - 'psp_task_nonce' should be sent in $_POST
	if (
		! isset($_POST['psp_task_nonce']) ||
		! wp_verify_nonce($_POST['psp_task_nonce'], 'psp_task_action')
	) {
		wp_send_json_error(array('message' => __('Security check failed.', 'psp_projects')));
		wp_die();
	}

	// Optional: check user capabilities - adjust capability as needed
	if (! current_user_can('edit_posts')) {
		wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'psp_projects')));
		wp_die();
	}

	// Sanitize input
	$post_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;

	if (empty($post_id)) {
		wp_send_json_error(array('message' => __('Missing project ID, please try again.', 'psp_projects')));
		wp_die();
	}

	$progress = psppan_compute_progress($post_id);

	echo esc_html($progress);
	wp_die();
}
