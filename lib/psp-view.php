<?php



/**

 * Call the psppan_essentials function and echo it to the screen. Adds it to the page using the psp_the_essentials hook

 *

 *

 * @param NULL

 * @return NULL

 **/



add_action('psp_the_essentials', 'psppan_echo_essentials');

function psppan_echo_essentials($id)
{



    global $post;



    $id = (isset($id) ? $id : $post->ID);



    include(psp_template_hierarchy('/parts/overview.php'));

    //echo psppan_essentials( $id );



}



/**

 * Outputs all the overview information to the page

 *

 *

 * @param $id, int post ID. $style string, $docs string

 * @return HTML output

 **/



function psppan_essentials($id, $style = null, $docs = null)
{



    ob_start();



    include(psp_template_hierarchy('/parts/overview.php'));



    return ob_get_clean();
}





/**

 * Outputs a doughnut chart of all project progress

 *

 *

 * @param $id (current post ID)

 * @return HTML output

 **/



function psppan_short_progress($id)
{



    include(psp_template_hierarchy('/parts/short-progress.php'));
}



/* Use an action to add the progress indicator to the template */

add_action('psp_the_progress', 'psppan_echo_total_progress');

function psppan_echo_total_progress()
{



    global $post;



    include(psp_template_hierarchy('/parts/milestones.php'));

    //echo psppan_total_progress($post->ID);



}



function psppan_total_progress($id, $style = null, $options = null)
{



    ob_start();



    include(psp_template_hierarchy('/parts/milestones.php'));



    return ob_get_clean();
}



function psppan_get_phase_completion($tasks, $id)
{



    $completed = 0;

    $task_count = 0;

    $task_completion = 0;



    if (get_field('phases_automatic_progress', $id)) {



        foreach ($tasks as $task) {

            $task_count++;

            $task_completion += $task['status'];
        }



        if ($task_count >= 1) {

            $completed += ceil($task_completion / $task_count);
        } elseif ($task_count == 1) {

            $completed = 0;
        } else {

            $completed += $task_completion;
        }



        return $completed;
    }
}



function psppan_get_phase_completed($id)
{



    $completed = 0;

    $tasks = 0;

    $task_completion = 0;

    $completed_tasks = 0;



    $phase_details = array();



    if (get_field('phases_automatic_progress', $id)) {



        while (has_sub_field('tasks', $id)) {

            $tasks++;

            $task_completion += get_sub_field('status');

            if (get_sub_field('status') == '100') {
                $completed_tasks++;
            }
        }



        if ($tasks >= 1) {
            $completed += ceil($task_completion / $tasks);
        } elseif ($tasks == 1) {
            $completed = 0;
        } else {
            $completed += $task_completion;
        }
    } else {



        while (has_sub_field('tasks', $id)) {

            $tasks++;

            $task_completion += get_sub_field('status');

            if (get_sub_field('status') == '100') {
                $completed_tasks++;
            }
        }



        $completed = get_sub_field('percent_complete');
    }



    array_push($phase_details, $completed, $tasks, $completed_tasks);



    return $phase_details;
}







add_action('psp_the_phases', 'psppan_echo_phases');

function psppan_echo_phases()
{



    global $post;

    $id = $post->ID;

    if (PSP_PLUGIN_TYPE == 'lite') {



        include(psp_template_hierarchy('parts/phases-lite.php'));
    } else {



        include(psp_template_hierarchy('/parts/phases.php'));
    }

    //echo psppan_phases($post->ID);



}



function psppan_phases($id, $style = null, $taskStyle = null)
{



    ob_start();



    if (PSP_PLUGIN_TYPE == 'lite') {



        include(psp_template_hierarchy('parts/phases-lite.php'));
    } else {



        include(psp_template_hierarchy('/parts/phases.php'));
    }



    return ob_get_clean();
}


function panorama_login_form()
{
    // Sanitize $_SERVER['REQUEST_URI']
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : home_url();

    // Secure the redirect URL
    $redirect_url = esc_url_raw(add_query_arg([], $request_uri));

    // Capture the login form HTML
    ob_start();
    wp_login_form(array('redirect' => $redirect_url));
    return ob_get_clean();
}






/**

 *

 * Function psppan_task_table

 *

 * Returns a table of tasks which can be open, complete or all

 *

 * @param $post_id (int), $shortcode (BOOLEAN), $taskStyle (string)

 * @return $output

 *

 */



function psppan_task_table($post_id, $shortcode = null, $taskStyle = null)
{



    $output = '

    <table class="psp-task-table">

            <tr>

                <th class="psp-tt-tasks">' . __('Task', 'psp_projects') . '</th>

                <th class="psp-tt-phase">' . __('Phase', 'psp_projects') . '</th>';



    if ($taskStyle != 'complete') {

        $output .= '<th class="psp-tt-complete">' . __('Completion', 'psp_projects') . '</th>';
    }



    $output .= '</tr>';



    while (has_sub_field('phases', $post_id)):



        $phaseTitle = get_sub_field('title');



        while (has_sub_field('tasks', $post_id)):



            $taskCompleted = get_sub_field('status');



            // Continue if you want to show incomplete tasks only and this task is complete

            if (($taskStyle == 'incomplete') && ($taskCompleted == '100')) {
                continue;
            }



            // Continue if you want to show completed tasks and this task is not complete

            if (($taskStyle == 'complete') && ($taskCompleted != '100')) {
                continue;
            }



            $output .= '<tr><td>' . get_sub_field('task') . '</td><td>' . $phaseTitle . '</td>';



            if ($taskStyle != 'complete') {
                $output .= '<td><span class="psp-task-bar"><em class="status psp-' . get_sub_field('status') . '"></em></span></td></tr>';
            }



        endwhile;



    endwhile;



    $output .= '</table>';



    return $output;
}



/**

 *

 * Function psppan_documents

 *

 * Stores all of the psppan_documents into an unordered list and returns them

 *

 * @param $post_id

 * @return $psp_docs

 *

 */



function psppan_documents($id, $style)
{



    ob_start();



    include(psp_template_hierarchy('/parts/documents-lite.php'));



    return ob_get_clean();
}





/**

 *

 * Function psp_single_template_logo

 *

 * Adds the logo to the top of the Panorama single.php if the option is turned on.

 *

 * @param

 * @return

 *

 */



add_action('psp_before_overview', 'psppan_single_template_masthead');

function psppan_single_template_masthead()
{



    if ((get_option('psp_logo') != '') && (get_option('psp_logo') != 'http://')) { ?>



        <section id="psp-branding" class="wrapper">

            <div class="psp-branding-wrapper">

                <img src="<?php echo esc_url(get_option('psp_logo')); ?>">

            </div>

        </section>



    <?php

    }
}



/**

 *

 * Function psppan_the_navigation

 *

 * Adds the navigation to the Project Panorama header

 *

 * @param

 * @return

 *

 */

add_action('psppan_the_navigation', 'psppan_single_template_navigation');

function psppan_single_template_navigation()
{ ?>



    <?php $back = get_option('psp_back'); ?>



    <nav class="nav" id="psp-main-nav">

        <ul>

            <li id="nav-menu"><a href="#">Menu</a>

                <ul>

                    <li id="nav-over"><a href="#overview"><?php esc_html_e('Overview', 'psp_projects'); ?></a></li>

                    <li id="nav-complete"><a href="#psp-progress"><?php esc_html_e('% Complete', 'psp_projects'); ?></a></li>

                    <li id="nav-milestones"><a href="#psp-phases"><?php esc_html_e('Phases', 'psp_projects'); ?></a></li>

                    <li id="nav-talk"><a href="#psp-discussion"><?php esc_html_e('Discussion', 'psp_projects'); ?></a></li>

                    <?php if ((isset($back)) && (!empty($back))): ?>

                        <li id="nav-back"><a href="<?php echo esc_url($back); ?>"><?php esc_html_e('Back', 'psp_projects'); ?></a></li>

                    <?php else: ?>

                        <li id="nav-back"><a href="<?php echo esc_url(get_post_type_archive_link('psp_projects')); ?>"><?php esc_html_e('Dashboard', 'psp_projects'); ?></a></li>

                    <?php endif; ?>

                    <?php do_action('psppan_menu_items'); ?>

                    <?php if (is_user_logged_in()): ?>

                        <?php
                        // Sanitize the current URL for logout redirect
                        $redirect_url = esc_url_raw(add_query_arg([], isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : home_url()));
                        ?>

                        <li id="nav-logout">
                            <a href="<?php echo esc_url(wp_logout_url($redirect_url)); ?>">
                                <?php esc_html_e('Logout', 'psp_projects'); ?>
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>

            </li>

        </ul>

    </nav>



    <?php }



/**

 *

 * Function psppan_single_template_header

 *

 * Adds the header to the Project Panorama single.php template

 *

 * @param

 * @return

 *

 */

add_action('psp_the_header', 'psppan_single_template_header');

function psppan_single_template_header()
{



    global $post;

    $panorama_access = panorama_check_access($post->ID);

    if ($panorama_access == 1):

    ?>



        <header id="psp-title" class="cf">

            <div class="wrapper">

                <h1><?php the_title(); ?> <span><?php the_field('client', $post->ID); ?></span></h1>

                <?php if ($panorama_access == 1): ?>

                    <?php do_action('psppan_the_navigation'); ?>

                <?php endif; ?>

            </div>

        </header>



    <?php endif;
}



/**

 *

 * Function psppan_add_dashboard_widgets

 *

 * Defines the dashboard widget slug, title and display function

 *

 * @param

 * @return

 *

 */



function psppan_add_dashboard_widgets()
{



    // Make sure the user has the right permissions



    if (current_user_can('publish_psp_projects')) {



        wp_add_dashboard_widget(

            'psp_dashboard_overview',         // Widget slug.

            'Projects',         // Title.

            'psppan_dashboard_overview_widget_function' // Display function.

        );
    }
}

add_action('wp_dashboard_setup', 'psppan_add_dashboard_widgets');



/**

 *

 * Function psppan_dashboard_overview_widget_function

 *

 * Echo's the output of psppan_populate_dashboard_widget

 *

 * @param

 * @return contents of psppan_populate_dashboard_widget

 *

 */



function psppan_dashboard_overview_widget_function()
{



    //echo psppan_populate_dashboard_widget();

    $projects = get_posts(array('post_type' => 'psp_projects', 'posts_per_page' => '-1'));

    $total_projects = count($projects);

    $taxonomies = get_terms('psp_tax');

    $recent = new WP_Query(array('post_type' => 'psp_projects', 'posts_per_page' => '10', 'orderby' => 'modified', 'order' => 'DESC', 'post_status' => 'publish'));



    // Calculate the number of completed projects



    $completed_projects = 0;

    $not_started        = 0;

    $active             = 0;



    foreach ($projects as $project):



        if (get_post_meta($project->ID, '_psp_completed', true) == '1')

            $completed_projects++;

        if (psp_compute_progress($project->ID) == 0)

            $not_started++;

        else

            $active++;

    endforeach;



    if ($total_projects == 0) {



        $percent_complete = 0;

        $percent_not_started = 0;
    } else {



        $percent_complete = floor($completed_projects / $total_projects * 100);

        $percent_not_started = floor($not_started / $total_projects * 100);
    }



    $percent_remaining = 100 - $percent_complete - $percent_not_started;





    ob_start(); ?>



    <div class="psp-chart">

        <canvas id="psp-dashboard-chart" width="100%" height="150"></canvas>

    </div>



    <script>
        jQuery(document).ready(function() {



            var chartOptions = {

                responsive: true

            }



            var data = [

                {

                    value: <?php echo esc_attr($percent_complete); ?>,

                    color: "#2a3542",

                    label: "Completed"

                },

                {

                    value: <?php echo esc_attr($percent_remaining); ?>,

                    color: "#3299bb",

                    label: "In Progress"

                },

                {

                    value: <?php echo esc_attr($percent_not_started); ?>,

                    color: "#666666",

                    label: "Not Started"

                }

            ];





            var psp_dashboard_chart = document.getElementById("psp-dashboard-chart").getContext("2d");



            try {

                var active_dashboard_chart = new Chart(psp_dashboard_chart).Doughnut(data, chartOptions);

            } catch (err) {

                console.log(err);

            } finally {

                if (typeof active_dashboard_chart !== "undefined") {

                    var active_dashboard_chart = new Chart(psp_dashboard_chart).Doughnut(data, chartOptions);

                }

            }



        });
    </script>





    <ul data-pie-id="psp-dashboard-chart" class="dashboard-chart-legend">

        <li data-value="<?php echo esc_attr($percent_not_started); ?>"><span><?php echo esc_html($percent_not_started); ?>% <?php esc_html_e('Not Started', 'psp_projects'); ?></span></li>

        <li data-value="<?php echo esc_attr($percent_remaining); ?>"><span><?php echo esc_html($percent_remaining); ?>% <?php esc_html_e('In Progress', 'psp_projects'); ?></span></li>

        <li data-value="<?php echo esc_attr($percent_complete); ?>"><span><?php echo esc_html($percent_complete); ?>% <?php esc_html_e('Complete', 'psp_projects'); ?></span></li>

    </ul>



    <ul class="psp-projects-overview">

        <li><span class="psp-dw-projects"><?php echo esc_html($total_projects); ?></span> <strong><?php esc_html_e('Projects', 'psp_projects'); ?></strong> </li>

        <li><span class="psp-dw-completed"><?php echo esc_html($completed_projects); ?></span> <strong><?php esc_html_e('Completed', 'psp_projects'); ?></strong></li>

        <li><span class="psp-dw-active"><?php echo esc_html($active); ?></span> <strong><?php esc_html_e('active', 'psp_projects'); ?></strong></li>

      <li><span class="psp-dw-types"><?php echo esc_html(implode(', ', $taxonomies)); ?></span> <strong><?php esc_html_e('Types', 'psp_projects'); ?></strong></li>

    </ul>



    <hr>



    <h4><?php esc_html_e('Recently Updated', 'psp_projects'); ?></h4>

    <table class="psp-dashboard-widget-table">

        <tr>

            <th><?php esc_html_e('Project', 'psp_projects'); ?></th>

            <th><?php esc_html_e('Progress', 'psp_projects'); ?></th>

            <th><?php esc_html_e('Updated', 'psp_projects'); ?></th>

            <th>&nbsp;</th>

        </tr>



        <?php while ($recent->have_posts()): $recent->the_post();
            global $post; ?>

            <tr>

                <td><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php the_title(); ?></a></td>

                <td>

                    <?php

                    $completed = psp_compute_progress($post->ID);



                    if ($completed > 10): ?>

                        <p class="psp-progress"><span class="psp-<?php echo esc_attr($completed); ?>"><strong>%<?php echo esc_html($completed); ?></strong></span></p>

                    <?php else: ?>

                        <p class="psp-progress"><span class="psp-<?php echo esc_html($completed); ?>"></span></p>

                    <?php endif; ?>

                </td>

                <td class="psp-dwt-date"><?php echo esc_html(get_the_modified_date("m/d/Y")); ?></td>

                <td class="psp-dwt-date">
                    <a href="<?php echo esc_url(get_permalink()); ?>" target="_new" class="psp-dw-view">
                        <?php esc_html_e('View', 'psp_projects'); ?>
                    </a>
                </td>


            </tr>

        <?php endwhile; ?>

    </table>



<?php



}





/**

 *

 * Function psppan_populate_dashboard_widget

 *

 * Gathers the dashboard content and returns it in a variable

 *

 * @param

 * @return (variable) ($output)

 *

 */



function psppan_populate_dashboard_widget()
{



    $projects = get_posts(array('post_type' => 'psp_projects', 'posts_per_page' => '-1'));

    $total_projects = count($projects);

    $taxonomies = get_terms('psp_tax');

    $recent = new WP_Query(array('post_type' => 'psp_projects', 'posts_per_page' => '10', 'orderby' => 'modified', 'order' => 'DESC', 'post_status' => 'publish'));



    // Calculate the number of completed projects



    $completed_projects = 0;

    $not_started        = 0;

    $active             = 0;



    foreach ($projects as $project):



        if (get_post_meta($project->ID, '_psp_completed', true) == '1')

            $completed_projects++;

        if (psp_compute_progress($project->ID) == 0)

            $not_started++;

        else

            $active++;

    endforeach;



    if ($total_projects == 0) {



        $percent_complete = 0;

        $percent_not_started = 0;
    } else {



        $percent_complete = floor($completed_projects / $total_projects * 100);

        $percent_not_started = floor($not_started / $total_projects * 100);
    }



    $percent_remaining = 100 - $percent_complete - $percent_not_started;





    ob_start(); ?>



    <div class="psp-chart">

        <canvas id="psp-dashboard-chart" width="100%" height="150"></canvas>

    </div>



    <script>
        jQuery(document).ready(function() {



            var chartOptions = {

                responsive: true

            }



            var data = [

                {

                    value: <?php echo esc_attr($percent_complete); ?>,

                    color: "#2a3542",

                    label: "Completed"

                },

                {

                    value: <?php echo esc_attr($percent_remaining); ?>,

                    color: "#3299bb",

                    label: "In Progress"

                },

                {

                    value: <?php echo esc_attr($percent_not_started); ?>,

                    color: "#666666",

                    label: "Not Started"

                }

            ];





            var psp_dashboard_chart = document.getElementById("psp-dashboard-chart").getContext("2d");



            try {

                var active_dashboard_chart = new Chart(psp_dashboard_chart).Doughnut(data, chartOptions);

            } catch (err) {

                console.log(err);

            } finally {

                if (typeof active_dashboard_chart !== "undefined") {

                    var active_dashboard_chart = new Chart(psp_dashboard_chart).Doughnut(data, chartOptions);

                }

            }



        });
    </script>





    <ul data-pie-id="psp-dashboard-chart" class="dashboard-chart-legend">

        <li data-value="<?php echo esc_attr($percent_not_started); ?>"><span><?php echo esc_html($percent_not_started); ?>% <?php esc_html_e('Not Started', 'psp_projects'); ?></span></li>

        <li data-value="<?php echo esc_attr($percent_remaining); ?>"><span><?php echo esc_html($percent_remaining); ?>% <?php esc_html_e('In Progress', 'psp_projects'); ?></span></li>

        <li data-value="<?php echo esc_attr($percent_complete); ?>"><span><?php echo esc_html($percent_complete); ?>% <?php esc_html_e('Complete', 'psp_projects'); ?></span></li>

    </ul>



    <ul class="psp-projects-overview">

        <li><span class="psp-dw-projects"><?php echo esc_html($total_projects); ?></span> <strong><?php esc_html_e('Projects', 'psp_projects'); ?></strong> </li>

        <li><span class="psp-dw-completed"><?php echo esc_html($completed_projects); ?></span> <strong><?php esc_html_e('Completed', 'psp_projects'); ?></strong></li>

        <li><span class="psp-dw-active"><?php echo esc_html($active); ?></span> <strong><?php esc_html_e('active', 'psp_projects'); ?></strong></li>

        <li><span class="psp-dw-types"><?php echo esc_html($taxonomies); ?></span> <strong><?php esc_html_e('Types', 'psp_projects'); ?></strong></li>

    </ul>



    <hr>



    <h4><?php esc_html_e('Recently Updated', 'psp_projects'); ?></h4>

    <table class="psp-dashboard-widget-table">

        <tr>

            <th><?php esc_html_e('Project', 'psp_projects'); ?></th>

            <th><?php esc_html_e('Progress', 'psp_projects'); ?></th>

            <th><?php esc_html_e('Updated', 'psp_projects'); ?></th>

            <th>&nbsp;</th>

        </tr>



        <?php while ($recent->have_posts()): $recent->the_post();
            global $post; ?>

            <tr>

                <td><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php the_title(); ?></a></td>

                <td>

                    <?php

                    $completed = psp_compute_progress($post->ID);



                    if ($completed > 10): ?>

                        <p class="psp-progress"><span class="psp-<?php echo esc_attr($completed); ?>"><strong>%<?php echo esc_html($completed); ?></strong></span></p>

                    <?php else: ?>

                        <p class="psp-progress"><span class="psp-<?php echo esc_html($completed); ?>"></span></p>

                    <?php endif; ?>

                </td>

                <td class="psp-dwt-date"><?php echo esc_html(get_the_modified_date("m/d/Y")); ?></td>

                <td class="psp-dwt-date"><a href="<?php esc_url(the_permalink()); ?>" target="_new" class="psp-dw-view"><?php esc_html_e('View', 'psp_projects'); ?></a></td>

            </tr>

        <?php endwhile; ?>

    </table>



<?php

    return ob_get_clean();
} ?>