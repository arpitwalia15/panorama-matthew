<?php



/* Display Current Projects */



function psppan_current_projects($atts)
{



	extract(
		shortcode_atts(

			array(

				'type'      => 'all',

				'status'    => 'all',

				'access'    => 'user',

				'count'     => '10',

				'sort'	    => 'default',

				'order'	    => 'ASC'

			),
			$atts
		)

	);



	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$cuser = wp_get_current_user();

	$cid   = $cuser->ID;



	unset($meta_args);

	unset($status_args);



	// Determine the sorting



	if ($sort == 'start') {



		$meta_sort    = 'start_date';

		$order_by     = 'meta_value';
	} elseif ($sort == 'end') {



		$meta_sort    = 'end_date';

		$order_by     = 'meta_value';
	} elseif ($sort == 'title') {



		$meta_sort    = NULL;

		$order_by     = 'title';
	} else {



		$meta_sort    = 'start_date';

		$order_by     = 'menu_order';
	}



	// Set the initial arguments



	$args = array(

		'post_type' 		=> 'psp_projects',

		'paged'				=> $paged,

		'posts_per_page'	=> $count,

		'meta_key' 			=>	$meta_sort,

		'orderby'			=>	$order_by,

		'order'				=>	$order

	);



	// If a type has been selected, add it to the argument



	if ((!empty($type)) && ($type != 'all')) {

		$tax_args = array('psp_tax' => $type);

		$args = array_merge($args, $tax_args);
	}



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



		$args = array_merge($args, $status_args);
	}



	if ($status == 'completed') {

		$status_args = array(
			'tax_query' => array(

				array(

					'taxonomy'	=>	'psp_status',

					'field'		=>	'slug',

					'terms'		=>	'completed',

				)

			)

		);



		$args = array_merge($args, $status_args);
	}



	if ($access == 'user') {



		// Just restricting access, not worried about active or complete





		if (!current_user_can('edit_others_psp_projects')) {



			$cuser = wp_get_current_user();



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
	}



	$projects = new WP_Query($args);



	if (($access == 'user') && (!is_user_logged_in())) { ?>
		<div id="psp-projects">

			<div id="psp-overview">

				<div id="psp-login" class="shortcode-login">

					<h2><?php esc_html_e('Please Login to View Projects', 'psp_projects'); ?></h2>

					<?php
					// Step 1: Sanitize the raw $_SERVER['REQUEST_URI']
					$request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

					// Step 2: Build safe redirect URL
					$redirect_url = esc_url_raw(add_query_arg([], $request_uri));

					// Step 3: Display WP login form
					wp_login_form(array(
						'redirect' => $redirect_url,
					));

					?>

				</div> <!-- /#psp-login -->

			</div>

		</div>


	<?php



		psp_front_assets(1);



		return;
	}





	if ($projects->have_posts()):

		ob_start();



	?>

		<div id="psp-projects">

			<table class="psp_project_list">

				<thead>

					<tr>

						<th class="psp_pl_col1"><?php esc_html_e('Project', 'psp_projects'); ?></th>

						<th class="psp_pl_col2"><?php esc_html_e('Progress', 'psp_projects'); ?></th>

						<th class="psp_pl_col3"><?php esc_html_e('Start', 'psp_projects'); ?></th>

						<th class="psp_pl_col4"><?php esc_html_e('End', 'psp_projects'); ?></th>

					</tr>

				</thead>



				<?php while ($projects->have_posts()): $projects->the_post();
					global $post; ?>



					<tr>

						<td>

							<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>

							<div class="psp-table-meta">

								<p><strong><?php esc_html_e('Client', 'psp_projects'); ?>:</strong> <?php the_field('client'); ?><br> <strong><?php esc_html_e('Last Updated', 'psp_projects'); ?>:</strong> <?php the_modified_date(); ?></p>

							</div>

						</td>

						<td>

							<?php global $post;
							$completed = psp_compute_progress($post->ID);



							if (!empty($completed)) {

								echo '<p class="psp-progress"><span class="psp-' . esc_attr($completed) . '"><b>' . esc_html($completed) . '%</b></span></p>';
							} ?>

						</td>

						<td>

							<?php psp_the_start_date($post->ID); ?>

						</td>

						<td>

							<?php psp_the_end_date($post->ID); ?>

						</td>

					</tr>



				<?php endwhile; ?>



			</table>



			<p><?php echo wp_kses(get_next_posts_link('&laquo; More Projects', $projects->max_num_pages), array('a' => array('href'  => array()))) . ' ' . wp_kses(get_previous_posts_link('Previous Projects &raquo;'), array('a' => array('href'  => array()))); ?></p>

		</div>



	<?php psp_front_assets(1);



		// Clear out this query


		wp_reset_postdata();



		return ob_get_clean();



	else:



		return '<p>' . __('No projects found', 'psp_projects') . '</p>';



	endif;
}

add_shortcode('project_list', 'psppan_current_projects');



function psppan_archive_project_listing($projects, $page = 1)
{





	if ($projects->have_posts()):

		ob_start(); ?>



		<table class="psp-archive-list psp-table-pagination">

			<thead>

				<tr>

					<th><?php esc_html_e('Project', 'psp_projects'); ?></th>

					<th><?php esc_html_e('Progress', 'psp_projects'); ?></th>

					<th><?php esc_html_e('Time Elapsed', 'psp_projects'); ?></th>

				</tr>

			</thead>

			<tbody>

				<?php

				while ($projects->have_posts()): $projects->the_post(); ?>



					<tr class="psp-archive-list-item">



						<?php global $post; ?>



						<?php



						$startDate = psp_text_date(get_field('start_date', $post->ID));

						$endDate = psp_text_date(get_field('end_date', $post->ID));



						?>



						<td class="psp-ali-header">

							<a href="<?php the_permalink(); ?>">



								<span class="psp-ali-client"><?php the_field('client'); ?></span>



								<span class="psp-ali-title"><?php the_title(); ?></span>



								<span class="psp-ali-dates"><?php echo esc_html($startDate); ?> <b>&#8594;</b> <?php echo esc_html($endDate); ?></span>



							</a>

						</td>



						<td class="psp-ali-progress">



							<?php $completed = psp_compute_progress($post->ID); ?>

							<p class="psp-progress">

								<?php if ($completed > 0): ?>

									<span class="psp-<?php echo esc_attr($completed); ?>">

										<?php if ($completed > 15): ?>

											<b><?php echo esc_html($completed); ?>%</b>

										<?php endif; ?>

									</span>

								<?php endif; ?>

							</p>



						</td>

						<td class="psp-ali-progress">

							<?php psp_the_timebar($post->ID); ?>

						</td>



					</tr> <!--/psp-archive-list-item-->



				<?php endwhile; ?>



			</tbody>

		</table>





		<?php if (!is_archive()): ?>



			<p><?php echo wp_kses(get_next_posts_link('<span class="psp-ajax-more-projects">&laquo; More Projects</span>', $projects->max_num_pages), array('a' => array('href'  => array()))) . ' ' . wp_kses(get_previous_posts_link('<span class="psp-ajax-prev-projects">Previous Projects &raquo;</span>'), array('a' => array('href'  => array()))); ?></p>



		<?php endif; ?>



	<?php psp_front_assets(1);



		return ob_get_clean();



	else:



		return '<p>' . __('No projects found', 'psp_projects') . '</p>';



	endif;
}



function psppan_project_listing_dialog()
{



	$psp_taxes = get_terms('psp_tax');

	$psp_tax_list = '';



	foreach ($psp_taxes as $tax) {

		$psp_tax_list .= '<option value="' . $tax->slug . '">' . $tax->name . '</option>';
	}

	?>



	<div class="psp-dialog" style="display:none">

		<div id="psp-project-listing-dialog">

			<h3><?php echo esc_html_e('Project Listing', 'psp_projects'); ?></h3>

			<p><?php echo esc_html_e('Select from the options below to output a list of projects.', 'psp_projects'); ?></p>

			<table class="form-table">

				<tr>

					<th><label for="psp-project-taxonomy"><?php echo esc_html_e('Project Type', 'psp_projects'); ?></label></th>

					<td>

						<select id="psp-project-taxonomy" name="psp-project-taxonomy">

							<option value="all">Any</option>

							<?php echo wp_kses($psp_tax_list, array('option' => array('value'  => array()))); ?>

						</select>

					</td>

				</tr>

				<tr>

					<th><label for="psp-project-status"><?php echo esc_html_e('Project Status', 'psp_projects'); ?></label></th>

					<td>

						<select id="psp-project-status" name="psp-project-status">

							<option value="all"><?php echo esc_html_e('All', 'psp_projects'); ?></option>

							<option value="active"><?php echo esc_html_e('Active', 'psp_projects'); ?></option>

							<option value="completed"><?php echo esc_html_e('Completed', 'psp_projects'); ?></option>

						</select>

					</td>

				</tr>

				<tr>

					<th><label for="psp-project-sort"><?php echo esc_html_e('Order By', 'psp_projects'); ?></label></th>

					<td>

						<select id="psp-project-sort" name="psp-project-sort">

							<option value="none"><?php echo esc_html_e('Creation Date', 'psp_projects'); ?></option>

							<option value="start"><?php echo esc_html_e('Start Date', 'psp_projects'); ?></option>

							<option value="end"><?php echo esc_html_e('End Date', 'psp_projects'); ?></option>

							<option value="title"><?php echo esc_html_e('Title', 'psp_projects'); ?></option>

						</select>

					</td>

				</tr>

				<tr>

					<th><label for="psp-project-count"><?php echo esc_html_e('Projects to show', 'psp_projects'); ?></label></th>

					<td>

						<select id="psp-project-count" name="psp-project-count">

							<option value="10">10</option>

							<option value="25">25</option>

							<option value="50">50</option>

							<option value="100">100</option>

							<option value="-1">All</option>

						</select>

					</td>

				</tr>

			</table>



			<p>

				<input class="button-primary" type="button" onclick="InsertPspProjectList();" value="<?php echo esc_html_e('Insert Project List', 'psp_projects'); ?>">

				<a class="button" onclick="tb_remove(); return false;" href="#"><?php echo esc_html_e('Cancel', 'psp_projects'); ?></a>

			</p>



		</div>

	</div>

<?php

	//echo $output;



}



function psppan_buttons()
{



	// Make sure the buttons are enabled



	if ((get_option('psp_disable_js') === '0') || (get_option('psp_disable_js') == NULL)) {



		add_filter('mce_external_plugins', 'psppan_add_buttons');

		add_filter('mce_buttons', 'psppan_register_buttons');
	}
}



function psppan_add_buttons($plugin_array)
{

	$plugin_array['pspbuttons'] = plugins_url() . '/psp_projects/assets/js/psp-buttons.js';

	return $plugin_array;
}



function psppan_register_buttons($buttons)
{



	array_push($buttons, 'currentprojects', 'singleproject');



	return $buttons;
}



function psppan_refresh_mce($ver)
{

	$ver += 3;

	return $ver;
}



add_filter('tiny_mce_version', 'psppan_refresh_mce');

add_action('init', 'psppan_buttons');





/**

 *

 * Function psppan_dashboard_shortcode

 *

 * Outputs the Dashboard Widget in Shortcode Format

 *

 * @param (variable) ($atts) Attributes from the shortcode - currently none

 * @return ($output) (Content from psp_populate_dashboard_widget() )

 *

 */



function psppan_dashboard_shortcode($atts)
{



	$output = '<div class="psp-dashboard-widget">' . psp_populate_dashboard_widget() . '</div>';

	return $output;
}



add_shortcode('panorama_dashboard', 'psppan_dashboard_shortcode');



add_shortcode('psp-before-milestone', 'psppan_before_milestone_shortcode');

function psppan_before_milestone_shortcode($atts, $content = NULL)
{



	return '<div class="psp-before-milestone">' . wpautop($content) . '</div>';
}


add_shortcode('after-milestone', function($atts, $content = null) {
    return psppan_after_milestone_shortcode($atts, $content, 'after_milestone');
});



add_shortcode('psp-before-phase', 'psppan_before_phase');

function psppan_before_phase($atts, $content = NULL)
{



	return '<div class="psp-before-phase">' . $content . '</div>';
}



add_shortcode('during-phase', 'psppan_during_phase');

function psppan_during_phase($atts, $content = NULL)
{



	return '<div class="psppan-during-phase">' . $content . '</div>';
}



add_shortcode('after-phase', function($atts, $content = null) {
    return psppan_after_milestone_shortcode($atts, $content, 'after-phase');
});
function psppan_after_milestone_shortcode($atts, $content = null, $tag = '')
{
    $class = 'psp-' . str_replace('_', '-', $tag);
    $output = '<div class="' . esc_attr($class) . '">';
    $output .= ($tag === 'after_milestone') ? wpautop($content) : $content;
    $output .= '</div>';
    return $output;
}