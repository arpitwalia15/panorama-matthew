<?php
/* Custom Single.php for project only view */

global $post, $lpm_doctype;
?><!DOCTYPE html>
<html <?php language_attributes($lpm_doctype); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $client = get_field('client'); ?>
    <title><?php the_title(); ?> | <?php echo esc_html($client); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if (get_field('hide_from_search_engines', $post->ID)): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>

    <?php
    // Properly enqueue styles and scripts
    wp_enqueue_style('psp-frontend', PSP_PLUGINURI . '/css/psp-frontend.css', [], '1.2.5');
    wp_enqueue_style('psp-custom', PSP_PLUGINURI . '/css/psp-custom.css.php', [], null);
    wp_enqueue_style('ie-styles', PSP_PLUGINURI . '/css/ie.css', [], null);
    wp_style_add_data('ie-styles', 'conditional', 'IE');

    wp_enqueue_script('psp-frontend-lib', PSP_PLUGINURI . '/js/psp-frontend-lib.min.js', ['jquery'], '1.2.5', true);
    wp_enqueue_script('psp-frontend-behavior', PSP_PLUGINURI . '/js/psp-frontend-behavior.js', ['jquery'], '1.2.5', true);
    wp_localize_script('psp-frontend-behavior', 'lpmMyAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);

    wp_enqueue_script('html5shiv', PSP_PLUGINURI . '/js/html5shiv.min.js', [], null, false);
    wp_script_add_data('html5shiv', 'conditional', 'lte IE 9');

    wp_enqueue_script('lpm-css3-mediaqueries', PSP_PLUGINURI . '/js/css3-mediaqueries.js', [], null, false);
    wp_script_add_data('lpm-css3-mediaqueries', 'conditional', 'lte IE 9');

    // Print enqueued styles and head scripts
    wp_print_styles();
    wp_print_head_scripts();
    ?>

    <?php do_action('psppan_head'); ?>
</head>

<body <?php body_class('psp-standalone-page'); ?>>

    <?php $panorama_access = panorama_check_access($post->ID); ?>

    <div id="psp-projects" class="psp-standard-template">

        <?php while (have_posts()): the_post(); ?>

            <input type="hidden" id="psp-task-style" value="<?php echo esc_attr(get_field('expand_tasks_by_default', $post->ID)); ?>">

            <?php do_action('psppan_the_header'); ?>

            <?php if ($panorama_access == 1): ?>

                <?php do_action('psppan_before_overview'); ?>

                <section id="overview" class="wrapper psp-section">
                    <?php do_action('psppan_before_essentials'); ?>
                    <?php do_action('psppan_the_essentials'); ?>
                    <?php do_action('psppan_after_essentials'); ?>
                </section>

                <?php do_action('psppan_between_overview_progress'); ?>

                <section id="psp-progress" class="cf psp-section">
                    <?php do_action('psppan_before_progress'); ?>
                    <?php do_action('psppan_the_progress'); ?>
                    <?php do_action('psppan_after_progress'); ?>
                </section>

                <?php do_action('psppan_between_progress_phases'); ?>

                <section id="psp-phases" class="wrapper psp-section">
                    <?php do_action('psppan_before_phases'); ?>
                    <?php do_action('psppan_the_phases'); ?>
                    <?php do_action('psppan_after_phases'); ?>
                </section>

                <?php do_action('psppan_between_phases_discussion'); ?>

                <section id="psp-discussion" class="psp-section cf">
                    <div class="wrapper">
                        <div class="discussion-content">
                            <?php comments_template(); ?>
                        </div>
                    </div>
                </section>

            <?php endif; ?>

            <?php if ($panorama_access == 0): ?>

                <div id="overview" class="psp-comments-wrapper">

                    <?php if ((get_option('psp_logo') != '') && (get_option('psp_logo') != 'http://')): ?>
                        <div class="psp-login-logo">
                            <img src="<?php echo esc_url(get_option('psp_logo')); ?>" alt="<?php esc_attr_e('Project Logo', 'psp_projects'); ?>">
                        </div>
                    <?php endif; ?>

                    <div id="psp-login">

                        <?php if (($panorama_access == 0) && (get_field('restrict_access_to_specific_users'))): ?>
                            <h2><?php esc_html_e('This Project Requires a Login', 'psp_projects'); ?></h2>
                            <?php if (!is_user_logged_in()) {
                                panorama_login_form();
                            } else {
                                echo '<p>' . esc_html__('You don\'t have permission to access this project', 'psp_projects') . '</p>';
                            } ?>
                        <?php endif; ?>

                        <?php if ((post_password_required()) && (!current_user_can('manage_options'))): ?>
                            <h2><?php esc_html_e('This Project is Password Protected', 'psp_projects'); ?></h2>
                            <?php echo get_the_password_form(); ?>
                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>

        <?php endwhile; ?>

    </div>

    <?php wp_footer(); ?>
    <?php wp_print_footer_scripts(); ?>

</body>
</html>
