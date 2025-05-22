<?php

/*

  Plugin URI: http://mte90.net

  Description: Custom field for CMB2 which adds a user-search dialog for searching/attaching user IDs

  Author: Mte90

  Author URI: http://mte90.net

  Version: 0.2.0

  License: GPLv2

 */



// 1. Render Field: ONLY outputs HTML markup, no direct file loading here.
function cmb2_user_search_render_field($field, $escaped_value, $object_id, $object_type, $field_type)
{
	$select_type = $field->args('select_type');
	$roles = is_array($field->args('roles')) ? implode(',', $field->args('roles')) : '';

	echo $field_type->input(array(
		'data-roles' => $roles,
		'data-selecttype' => ('radio' === $select_type) ? 'radio' : 'checkbox',
		'autocomplete' => 'off',
		'style' => 'display:none',
	)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	echo '<ul style="cursor:move">';

	if (!empty($field->escaped_value)) {
		$list = explode(',', $field->escaped_value);
		foreach ($list as $value) {
			$user = get_user_by('id', $value);
			if (!$user) continue;
			$name = $user->display_name ? $user->display_name : $user->user_login;

			echo '<li data-id="' . esc_attr(trim($value)) . '"><b>' . esc_html__('Name', 'psp_projects') . ':</b> ' . esc_html($name);
			echo '<div title="' . esc_attr__('Remove', 'psp_projects') . '" style="color: #999; margin: -0.1em 0 0 2px; cursor: pointer;" class="cmb-user-search-remove dashicons dashicons-no"></div>';
			echo '</li>';
		}
	}

	echo '</ul>';
}
add_action('cmb2_render_user_search_text', 'cmb2_user_search_render_field', 10, 5);

// 2. Enqueue scripts and load admin core files safely in admin context
add_action('admin_enqueue_scripts', function ($hook_suffix) {
	// Optional: restrict to certain admin pages
	// if (!in_array($hook_suffix, ['post.php', 'post-new.php'])) return;

	wp_enqueue_script('jquery');
	wp_enqueue_script('wp-backbone');
	wp_enqueue_script('jquery-ui-sortable');

	require_once ABSPATH . 'wp-admin/includes/template.php';
});

// 3. Output inline JavaScript in admin footer (only in admin)
add_action('admin_footer', function () {
	// Localization strings for JS
	$error_msg = esc_js(__('An error has occurred. Please reload the page and try again.', 'psp_projects'));
	$find_msg  = esc_js(__('Find Posts or Pages', 'psp_projects'));

?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			'use strict';

			var l10n = {
				error: '<?php echo $error_msg; ?>',
				find: '<?php echo $find_msg; ?>'
			};

			// Your Backbone View & JS code goes here, for example:

			var UserSearchView = Backbone.View.extend({
				// implement your logic here...
				// e.g. events, initialize, render, ajax calls, sortable, etc.
			});

			window.cmb2_user_search = new UserSearchView();

			// NOTE: Replace with your actual JS code as needed.
		});
	</script>
<?php
});








/**

 * Add the find posts div via a hook so we can relocate it manually

 */
function projpan_add_find_user_div()
{
	add_action('wp_footer', 'find_users_div');
}




add_action('cmb2_user_search_field_add_find_users_div', 'cmb2_user_search_field_add_find_users_div');



/**

 *

 * Based on find_posts

 *

 * @param type $found_action

 */

function find_users_div($found_action = '')
{

?>

	<style>
		#find-users-close {

			width: 36px;

			height: 36px;

			position: absolute;

			top: 0px;

			right: 0px;

			cursor: pointer;

			text-align: center;

			color: #666;

		}

		#find-users-close::before {

			font: 400 20px/36px dashicons;

			vertical-align: top;

			content: "";

		}

		#find-users-close:hover {

			color: #00A0D2;

		}
	</style>

	<div id="find-users" class="find-box" style="display: none;">

		<div id="find-users-head" class="find-box-head">

			<?php esc_html_e('Users', 'psp_projects'); ?>

			<div id="find-users-close"></div>

		</div>

		<div class="find-box-inside">

			<div class="find-box-search">

				<?php if ($found_action) { ?>

					<input type="hidden" name="found_action" value="<?php echo esc_attr($found_action); ?>" />

				<?php } ?>

				<input type="hidden" name="affected" id="affected" value="" />

				<?php wp_nonce_field('find-users', '_ajax_nonce', false); ?>

				<label class="screen-reader-text" for="find-users-input"><?php esc_html_e('Search', 'psp_projects'); ?></label>

				<input type="text" id="find-users-input" name="ps" value="" autocomplete="off" />

				<span class="spinner"></span>

				<input type="button" id="find-users-search" value="<?php esc_attr_e('Search', 'psp_projects'); ?>" class="button" />

				<div class="clear"></div>

			</div>

			<div id="find-users-response"></div>

		</div>

		<div class="find-box-buttons">

			<?php submit_button(__('Select', 'psp_projects'), 'button-primary alignright', 'find-users-submit', false); ?>

			<div class="clear"></div>

		</div>

	</div>

<?php

}



/**

 * Ajax handler for querying posts for the Find Users modal.

 *

 * @see window.findPosts

 *

 * @since 3.1.0

 */

function projpan_ajax_find_users()
{
	check_ajax_referer('find-users');

	// Sanitize roles
	if (isset($_POST['roles']) && !empty($_POST['roles'])) {
		$roles_raw = wp_unslash($_POST['roles']);
		$roles_array = explode(',', $roles_raw);
		$roles = array_map('sanitize_key', $roles_array);
	} else {
		$roles = array(); // Use empty array instead of empty string
	}

	// Sanitize search term
	$s = isset($_POST['ps']) ? sanitize_text_field(wp_unslash($_POST['ps'])) : '';

	$users = array();

	if (!empty($roles)) {
		foreach ($roles as $role) {
			$users_query = new WP_User_Query(array(
				'role' => $role,
				'orderby' => 'display_name',
				'search' => '*' . esc_attr($s) . '*',
				'search_columns' => array('user_login', 'user_email', 'user_nicename', 'display_name')
			));

			$results = $users_query->get_results();
			if ($results) {
				$users = array_merge($users, $results);
			}
		}
	} else {
		$user_query = new WP_User_Query(array(
			'orderby' => 'display_name',
			'search' => '*' . esc_attr($s) . '*',
			'search_columns' => array('user_login', 'user_email', 'user_nicename', 'display_name')
		));

		$users = $user_query->get_results();
	}

	if (empty($users)) {
		wp_send_json_error(__('No items found.', 'psp_projects'));
	}

	// Build HTML response
	$html = '<table class="widefat"><thead><tr><th class="found-radio"><br /></th><th>' . __('Name', 'psp_projects') . '</th><th class="no-break">' . __('Email', 'psp_projects') . '</th></tr></thead><tbody>';
	$alt = '';

	foreach ($users as $user) {
		$title = $user->display_name ? $user->display_name : $user->user_login;
		$alt = ('alternate' == $alt) ? '' : 'alternate';

		$html .= '<tr class="' . trim('found-users ' . $alt) . '">';
		$html .= '<td class="found-radio"><input type="radio" id="found-' . esc_attr($user->ID) . '" name="found_post_id" value="' . esc_attr($user->ID) . '"></td>';
		$html .= '<td><label for="found-' . esc_attr($user->ID) . '">' . esc_html($title) . '</label></td>';
		$html .= '<td>' . esc_html($user->user_email) . '</td></tr>' . "\n\n";
	}

	$html .= '</tbody></table>';

	wp_send_json_success($html);
}


add_action('wp_ajax_find_users', 'projpan_ajax_find_users');


/**

 * Add support for search on Dsiplay Name field

 *

 * @param array $search_columns

 * @return string

 */

function cmb2_user_search_field_display_name($search_columns)
{

	$search_columns[] = 'display_name';

	return $search_columns;
}

add_filter('user_search_columns', 'cmb2_user_search_field_display_name');
