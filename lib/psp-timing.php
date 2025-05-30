<?php
function psppan_the_start_date($id)
{

    $months = array(
        __('Jan', 'psp_projects'),
        __('Feb', 'psp_projects'),
        __('Mar', 'psp_projects'),
        __('Apr', 'psp_projects'),
        __('May', 'psp_projects'),
        __('Jun', 'psp_projects'),
        __('Jul', 'psp_projects'),
        __('Aug', 'psp_projects'),
        __('Sep', 'psp_projects'),
        __('Oct', 'psp_projects'),
        __('Nov', 'psp_projects'),
        __('Dec', 'psp_projects')
    );

    $startDate  = get_field('start_date', $id);

    $s_year     = substr($startDate, 0, 4);
    $s_month    = substr($startDate, 4, 2);
    $s_day      = substr($startDate, 6, 2);

    if (!empty($startDate)): ?>

        <div class="psp-date">
            <span class="cal">
                <span class="month"><?php echo esc_html($months[$s_month - 1]); ?></span>
                <span class="day"><?php echo esc_html($s_day); ?></span>
            </span>
            <b><?php echo esc_html($s_year); ?></b>
        </div>

    <?php
    endif;
}

function psppan_the_end_date($id)
{

    $months = array(
        __('Jan', 'psp_projects'),
        __('Feb', 'psp_projects'),
        __('Mar', 'psp_projects'),
        __('Apr', 'psp_projects'),
        __('May', 'psp_projects'),
        __('Jun', 'psp_projects'),
        __('Jul', 'psp_projects'),
        __('Aug', 'psp_projects'),
        __('Sep', 'psp_projects'),
        __('Oct', 'psp_projects'),
        __('Nov', 'psp_projects'),
        __('Dec', 'psp_projects')
    );

    $endDate = get_field('end_date', $id);

    if (!empty($endDate)):

        $e_year     = substr($endDate, 0, 4);
        $e_month    = substr($endDate, 4, 2);
        $e_day      = substr($endDate, 6, 2); ?>

        <div class="psp-date">
            <span class="cal">
                <span class="month"><?php echo esc_html($months[$e_month - 1]); ?></span>
                <span class="day"><?php echo esc_html($e_day); ?></span>
            </span>
            <b><?php echo esc_html($e_year); ?></b>
        </div>

    <?php
    endif;
}

function psppan_text_date($date)
{

    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2);

    return $month . '/' . $day . '/' . $year;
}

function psppan_the_timebar($id)
{

    $startDate  = get_field('start_date', $id);
    $endDate    = get_field('end_date', $id);

    $s_year     = substr($startDate, 0, 4);
    $s_month    = substr($startDate, 4, 2);
    $s_day      = substr($startDate, 6, 2);

    $e_year     = substr($endDate, 0, 4);
    $e_month    = substr($endDate, 4, 2);
    $e_day      = substr($endDate, 6, 2);

    $textStartDate = psppan_text_date($startDate);
    $textEndDate   = psppan_text_date($endDate);


    if (empty($startDate) || empty($endDate)) return;

    global $post;
    $all_time = psppan_calculate_timing($id);

    if ($all_time[0] < 0) $all_time[0] = 100;

    $psp_tt_10 = $all_time[0] >= 10 ? 'active' : null;
    $psp_tt_20 = $all_time[0] >= 20 ? 'active' : null;
    $psp_tt_30 = $all_time[0] >= 30 ? 'active' : null;
    $psp_tt_40 = $all_time[0] >= 40 ? 'active' : null;
    $psp_tt_50 = $all_time[0] >= 50 ? 'active' : null;
    $psp_tt_60 = $all_time[0] >= 60 ? 'active' : null;
    $psp_tt_70 = $all_time[0] >= 70 ? 'active' : null;
    $psp_tt_80 = $all_time[0] >= 80 ? 'active' : null;
    $psp_tt_90 = $all_time[0] >= 90 ? 'active' : null; ?>

    <div class="psp-timebar">

        <?php
        if ($all_time[2] > $all_time[1]) {
            $days_left = '<span class="psp-time-details">' . $all_time[2] . __('days past project end date.', 'psp_projects') . '</span>';
        } else {
            $days_left = '<span class="psp-time-details">' . $all_time[2] . __(' days remaining', 'psp_projects') . '</span>';
        } ?>

        <p class="psp-time-start-end"><?php echo esc_html($textStartDate); ?> <span><?php echo esc_html($textEndDate); ?></span></p>

        <div class="psp-time-progress">

            <p class="psp-time-bar"><span class="psp-<?php echo esc_attr($all_time[0]); ?>"></span></p>

            <ol class="psp-time-ticks">
                <li class="psp-tt-10 <?php echo esc_attr($psp_tt_10); ?>"></li>
                <li class="psp-tt-20 <?php echo esc_attr($psp_tt_20); ?>"></li>
                <li class="psp-tt-30 <?php echo esc_attr($psp_tt_30); ?>"></li>
                <li class="psp-tt-40 <?php echo esc_attr($psp_tt_40); ?>"></li>
                <li class="psp-tt-50 <?php echo esc_attr($psp_tt_50); ?>"></li>
                <li class="psp-tt-60 <?php echo esc_attr($psp_tt_60); ?>"></li>
                <li class="psp-tt-70 <?php echo esc_attr($psp_tt_70); ?>"></li>
                <li class="psp-tt-80 <?php echo esc_attr($psp_tt_80); ?>"></li>
                <li class="psp-tt-90 <?php echo esc_attr($psp_tt_90); ?>"></li>
            </ol>

            <span class="psp-time-indicator" style="left: <?php echo esc_attr($all_time[0]); ?>%"><span></span><?php echo esc_html($all_time[0]); ?>%</span>

        </div> <!--/.psp-time-progress-->

    </div> <!--/.psp-timebar-->

<?php

}

function psppan_the_timing($id)
{

    $startDate = get_field('start_date', $id);
    $endDate = get_field('end_date', $id);

    $s_year = substr($startDate, 0, 4);
    $s_month = substr($startDate, 4, 2);
    $s_day = substr($startDate, 6, 2);

    $e_year = substr($endDate, 0, 4);
    $e_month = substr($endDate, 4, 2);
    $e_day = substr($endDate, 6, 2);

    $textStartDate = $s_month . '/' . $s_day . '/' . $s_year;
    $textEndDate = $e_month . '/' . $e_day . '/' . $e_year;


    if ((empty($startDate)) || (empty($endDate))) {
        return;
    }

    global $post;
    $all_time = psppan_calculate_timing($id);

    if ($all_time[0] < 0) {
        $all_time[0] = 100;
    }

    $psp_tt_10 = $all_time[0] >= 10 ? 'active' : null;
    $psp_tt_20 = $all_time[0] >= 20 ? 'active' : null;
    $psp_tt_30 = $all_time[0] >= 30 ? 'active' : null;
    $psp_tt_40 = $all_time[0] >= 40 ? 'active' : null;
    $psp_tt_50 = $all_time[0] >= 50 ? 'active' : null;
    $psp_tt_60 = $all_time[0] >= 60 ? 'active' : null;
    $psp_tt_70 = $all_time[0] >= 70 ? 'active' : null;
    $psp_tt_80 = $all_time[0] >= 80 ? 'active' : null;
    $psp_tt_90 = $all_time[0] >= 90 ? 'active' : null;
?>


    <div id="psp-time-overview">

        <div class="project-timing cf">

            <?php

            if (($startDate) || ($endDate)): ?>

                <h4><?php esc_html_e('Project Timing', 'psp_projects'); ?></h4>

                <ul class="psp-timing cf">
                    <?php if ($startDate): ?>
                        <li><strong><?php esc_html_e('Start', 'psp_projects'); ?></strong>
                            <?php psppan_the_start_date($id); ?>
                        </li>
                    <?php endif;
                    if ($endDate): ?>
                        <li><strong><?php esc_html_e('End', 'psp_projects'); ?></strong>
                            <?php psppan_the_end_date($id); ?>
                        </li>
                    <?php endif; ?>
                </ul>


                <?php if (($startDate) && ($endDate)): ?>

                    <div class="psp-timebar">

                        <?php
                        if ($all_time[2] > $all_time[1]) {
                            $days_left = '<span class="psp-time-details">' . $all_time[2] . __('days past project end date.', 'psp_projects') . '</span>';
                        } else {
                            $days_left = '<span class="psp-time-details">' . $all_time[2] . __(' days remaining', 'psp_projects') . '</span>';
                        } ?>

                        <p class="psp-time-start-end"><?php echo esc_html($textStartDate); ?> <span><?php echo esc_html($textEndDate); ?></span></p>

                        <div class="psp-time-progress">

                            <p class="psp-time-bar"><span class="psp-<?php echo esc_attr($all_time[0]); ?>"></span></p>

                            <ol class="psp-time-ticks">
                                <li class="psp-tt-10 <?php echo esc_attr($psp_tt_10); ?>"></li>
                                <li class="psp-tt-20 <?php echo esc_attr($psp_tt_20); ?>"></li>
                                <li class="psp-tt-30 <?php echo esc_attr($psp_tt_30); ?>"></li>
                                <li class="psp-tt-40 <?php echo esc_attr($psp_tt_40); ?>"></li>
                                <li class="psp-tt-50 <?php echo esc_attr($psp_tt_50); ?>"></li>
                                <li class="psp-tt-60 <?php echo esc_attr($psp_tt_60); ?>"></li>
                                <li class="psp-tt-70 <?php echo esc_attr($psp_tt_70); ?>"></li>
                                <li class="psp-tt-80 <?php echo esc_attr($psp_tt_80); ?>"></li>
                                <li class="psp-tt-90 <?php echo esc_attr($psp_tt_90); ?>"></li>
                            </ol>

                            <span class="psp-time-indicator" style="left: <?php echo esc_attr($all_time[0]); ?>%"><span></span><?php echo esc_html($all_time[0]); ?>%</span>

                        </div> <!--/.psp-time-progress-->

                    </div> <!--/.psp-timebar-->
                <?php endif; // if start and end date 
                ?>

            <?php endif; // if start or end date 
            ?>
        </div> <!--/.project-timing-->
    <?php
}

function psppan_calculate_timing($post_id)
{

    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }

    $startDate  = get_field('start_date', $post_id);
    $endDate    = get_field('end_date', $post_id);

    $today      = time();
    $s_year     = substr($startDate, 0, 4);
    $s_month    = substr($startDate, 4, 2);
    $s_day      = substr($startDate, 6, 2);

    $e_year     = substr($endDate, 0, 4);
    $e_month    = substr($endDate, 4, 2);
    $e_day      = substr($endDate, 6, 2);

    $startDate  = strtotime($s_year . '-' . $s_month . '-' . $s_day);
    $endDate    = strtotime($e_year . '-' . $e_month . '-' . $e_day);

    $total_days = abs($startDate - $endDate);
    $total_days = floor($total_days / (60 * 60 * 24));

    $datediff   = abs($today - $endDate);

    $time_completed = floor($datediff / (60 * 60 * 24));

    if ($startDate > $today) {

        $time_percentage = 0;
    } elseif ($endDate < $today) {

        $time_percentage = 100;
    } elseif ($total_days == 0) {

        $time_percentage = 100;
    } else {

        $time_percentage = floor(100 - ($time_completed / $total_days * 100));
    }

    $all_time = array($time_percentage, $total_days, $time_completed);

    return $all_time;
}

function psppan_verbal_status($all_time, $calc_completed)
{

    if ($all_time[0] > $calc_completed) {
        return 'behind';
    } else {
        return 'time';
    }
}

function psppan_the_timing_bar($post_id)
{

    $time_elapsed   = psppan_calculate_timing($post_id);
    $completed      = psp_compute_progress($post_id);

    if ($completed < $time_elapsed[0]) {
        $progress_class = 'psp-behind';
    } else {
        $progress_class = 'psp-ontime';
    }

    if ($time_elapsed[0] < 0) {
        $time_elapsed[0] = 100;
    }

    echo '<p class="psp-timing-progress psp-progress ' . esc_attr($progress_class) . '"><span class="psp-' . esc_attr($time_elapsed[0]) . '"><strong>%' . esc_html($time_elapsed[0]) . '</strong></span></p>';
}

/*
 * Calendar
 */

add_action('admin_menu', 'psppan_lite_add_calendar_page');
function psppan_lite_add_calendar_page()
{

    global $psppan_lite_add_calendar_page;

    $psppan_lite_add_calendar_page = add_submenu_page('edit.php?post_type=psp_projects', 'Project Calendar', 'Calendar', 'manage_options', 'panorama-calendar', 'psppan_lite_project_calendar_page');
}

function psppan_lite_project_calendar_page()
{
    psp_admin_assets('calendar'); ?>

        <div class="wrap">

            <h1><?php esc_html_e('Project Calendar', 'psp_projects'); ?></h1>

            <br>

            <?php
            $cuser = wp_get_current_user();

            // Generate nonce for 'psp_dates_action'
            $nonce = wp_create_nonce('psp_dates_action');

            // Add nonce to URL
            $date_url = add_query_arg(array(
                'psp_dates' => $cuser->ID,
                'psp_nonce' => $nonce,
            ), site_url('/index.php'));
            ?>

            <div id="psp-project-calendar"></div>

            <script>
                jQuery(document).ready(function($) {
                    $('#psp-project-calendar').fullCalendar({
                        events: '<?php echo esc_url($date_url); ?>',
                        <?php if (get_option('psp_calendar_language')) { ?>
                            lang: '<?php echo esc_attr(get_option('psp_calendar_language')); ?>',
                        <?php } ?>
                        eventRender: function(event, element) {
                            element.qtip({
                                content: {
                                    title: event.title,
                                    text: event.description
                                },
                                style: 'qtip-light',
                                position: {
                                    my: 'bottom center',
                                    at: 'top center',
                                }
                            });
                        }
                    });
                });
            </script>

        </div>

    <?php
}


function psppan_lite_output_project_calendar()
{

    $cuser      = wp_get_current_user();

    $date_url = site_url() . '/index.php?psp_dates=' . $cuser->ID;

    ob_start(); ?>

        <div id="psp-project-calendar"></div>

        <script>
            jQuery(document).ready(function($) {
                $('#psp-project-calendar').fullCalendar({
                    events: '<?php echo esc_url($date_url); ?>',
                    <?php if (get_option('psp_calendar_language')) { ?>
                        lang: '<?php echo esc_attr(get_option('psp_calendar_language')); ?>',
                    <?php } ?>
                    eventRender: function(event, element) {
                        element.qtip({
                            content: {
                                title: event.title,
                                text: event.description
                            },
                            style: 'qtip-light',
                            position: {
                                my: 'bottom center',
                                at: 'top center',
                            }
                        });
                    }
                });
            });
        </script>

    <?php
    return ob_get_clean();
}

add_action('template_redirect', 'psppan_lite_dates_endpoint_data');
function psppan_lite_dates_endpoint_data()
{
    // Only proceed if 'psp_dates' is set in GET
    if (!isset($_GET['psp_dates'])) {
        return;
    }

    // Check nonce - expecting 'psp_nonce' in GET as well
    if (
        !isset($_GET['psp_nonce']) ||
        !wp_verify_nonce($_GET['psp_nonce'], 'psp_dates_action')
    ) {
        wp_send_json_error(['message' => __('Invalid nonce', 'psp_projects')]);
        exit;
    }

    $date_tag = sanitize_text_field($_GET['psp_dates']);

    // Permission check - keep this for authorization
    if (!$date_tag || !current_user_can('manage_options')) {
        wp_send_json_error(['message' => __('Unauthorized', 'psp_projects')]);
        exit;
    }

    $date_data = [];

    $args = [
        'post_type'      => 'psp_projects',
        'posts_per_page' => -1,
    ];

    $projects = new WP_Query($args);

    if ($projects->have_posts()) :
        while ($projects->have_posts()) : $projects->the_post();

            if (get_field('start_date')) {

                $start_date = get_field('start_date');
                $end_date   = get_field('end_date');

                $s_year  = substr($start_date, 0, 4);
                $s_month = substr($start_date, 4, 2);
                $s_day   = substr($start_date, 6, 2);

                $e_year  = substr($end_date, 0, 4);
                $e_month = substr($end_date, 4, 2);
                $e_day   = substr($end_date, 6, 2);

                $date_data[] = [
                    'title'       => __('Start: ', 'psp_projects') . get_the_title(),
                    'start'       => "{$s_year}-{$s_month}-{$s_day}",
                    'url'         => get_permalink(),
                    'description' => get_field('client'),
                    'color'       => '#3299BB',
                ];

                $date_data[] = [
                    'title'       => __('End: ', 'psp_projects') . get_the_title(),
                    'start'       => "{$e_year}-{$e_month}-{$e_day}",
                    'url'         => get_permalink(),
                    'description' => get_field('client'),
                    'color'       => '#C44D58',
                ];
            }

        endwhile;
        wp_reset_postdata();
    endif;

    wp_send_json(apply_filters('psppan_date_data_json', $date_data, $post));
}
