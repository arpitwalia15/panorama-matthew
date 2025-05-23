<?php
/* Custom Single.php for project only view */

global $post, $lpm_doctype;

?>

<!DOCTYPE html>
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
    // Instead of calling wp_enqueue_style and wp_enqueue_script here directly,
    // wrap them inside wp_head hook, because this file removes wp_head call,
    // so we manually print enqueued styles/scripts.

    // Enqueue styles and scripts for this page:
    wp_enqueue_style('psp-frontend',  PSP_PLUGINURI . '/css/psp-frontend.css', [], '1.2.5');
    wp_enqueue_style('psp-custom', PSP_PLUGINURI . '/css/psp-custom.css.php', [], null);

    wp_enqueue_script('psp-frontend-lib', PSP_PLUGINURI . '/js/psp-frontend-lib.min.js', ['jquery'], '1.2.5', true);
    wp_enqueue_script('psp-frontend-behavior', PSP_PLUGINURI . '/js/psp-frontend-behavior.js', ['jquery'], '1.2.5', true);

    wp_localize_script('psp-frontend-behavior', 'lpmMyAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);

    wp_enqueue_script('html5shiv', PSP_PLUGINURI . '/js/html5shiv.min.js', [], null, false);
    wp_script_add_data('html5shiv', 'conditional', 'lte IE 9');

    wp_enqueue_script('lpm-css3-mediaqueries', PSP_PLUGINURI . '/js/css3-mediaqueries.js', [], null, false);
    wp_script_add_data('lpmm-css3-mediaqueries', 'conditional', 'lte IE 9');

    wp_enqueue_style('ie-styles', PSP_PLUGINURI . '/css/ie.css', [], null);
    wp_style_add_data('ie-styles', 'conditional', 'IE');

    // Manually output enqueued styles and scripts here (because wp_head is removed)
    ?>
    <?php
    // Output all enqueued styles
    global $wp_styles;
    if (!empty($wp_styles->queue)) {
        foreach ($wp_styles->queue as $handle) {
            $style = $wp_styles->registered[$handle];
            $href = $style->src;
            if (! preg_match('|^(https?:)?//|', $href)) {
                $href = site_url($href);
            }
            echo '<link rel="stylesheet" id="' . esc_attr($handle) . '-css" href="' . esc_url($href) . '" type="text/css" media="all" />' . "\n";
        }
    }
    ?>

    <?php
    // Output all enqueued scripts that are not in footer (like html5shiv, css3-mediaqueries)
    global $wp_scripts;
    if (!empty($wp_scripts->queue)) {
        foreach ($wp_scripts->queue as $handle) {
            $script = $wp_scripts->registered[$handle];
            // Only output scripts that are NOT in footer here
            if (!$script->args) {
                $src = $script->src;
                if (! preg_match('|^(https?:)?//|', $src)) {
                    $src = site_url($src);
                }
                // Handle conditional comments for IE scripts
                $conditional = isset($wp_scripts->get_data($handle, 'conditional')) ? $wp_scripts->get_data($handle, 'conditional') : false;
                if ($conditional) {
                    echo "<!--[if {$conditional}]>\n";
                }
                echo '<script type="text/javascript" src="' . esc_url($src) . '"></script>' . "\n";
                if ($conditional) {
                    echo "<![endif]-->\n";
                }
            }
        }
    }
    ?>

    <?php do_action('psppan_head'); ?>
</head>

<body <?php body_class('psp-standalone-page'); ?>>

    <?php $panorama_access = panorama_check_access($post->ID); ?>

    <div id="psp-projects" class="psp-standard-template">

        <?php while (have_posts()): the_post(); ?>

            <input type="hidden" id="psp-task-style" value="<?php echo esc_attr(get_field('expand_tasks_by_default', $post->ID)); ?>">

            <?php do_action('psppan_the_header'); ?>

            <?php if ($panorama_access == 1) : ?>

                <?php do_action('psppan_before_overview'); ?>

                <section id="overview" class="wrapper psp-section">

                    <?php do_action('psppan_before_essentials'); ?>
                    <?php do_action('psppan_the_essentials'); ?>
                    <?php do_action('psppan_after_essentials'); ?>

                </section> <!--/#overview-->

                <?php do_action('psppan_between_overview_progress'); ?>

                <section id="psp-progress" class="cf psp-section">

                    <?php do_action('psppan_before_progress'); ?>
                    <?php do_action('psppan_the_progress'); ?>
                    <?php do_action('psppan_after_progress'); ?>

                </section> <!--/#progress-->

                <?php do_action('psppan_between_progress_phases'); ?>

                <section id="psp-phases" class="wrapper psp-section">

                    <?php do_action('psppan_before_phases'); ?>
                    <?php do_action('psppan_the_phases'); ?>
                    <?php do_action('psppan_after_phases'); ?>

                </section>

                <?php do_action('psppan_between_phases_discussion'); ?>

                <!-- Discussion -->
                <section id="psp-discussion" class="psp-section cf">
                    <div class="wrapper">
                        <div class="discussion-content">

                            <?php
                            // Instead of getcwd and separate file, include the comments template from the theme's folder
                            // To keep it all in one file, you can call comments_template() without parameters
                            comments_template();
                            ?>

                        </div>
                    </div>
                </section>

            <?php endif; ?>

            <?php if ($panorama_access == 0): ?>

                <div id="overview" class="psp-comments-wrapper">

                    <?php if ((get_option('psp_logo') != '') && (get_option('psp_logo') != 'http://')) { ?>
                        <div class="psp-login-logo">
                            <img src="<?php echo esc_url(get_option('psp_logo')); ?>" alt="<?php esc_attr_e('Project Logo', 'psp_projects'); ?>">
                        </div>
                    <?php } ?>

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

        <?php endwhile; // ends the loop 
        ?>

    </div> <!--/#psp-projects-->

    <?php
    // Output footer scripts manually since wp_footer() might be removed or no hook
    wp_footer();
    ?>

    <?php
    // Output footer scripts that are in footer queue
    if (!empty($wp_scripts->queue)) {
        foreach ($wp_scripts->queue as $handle) {
            $script = $wp_scripts->registered[$handle];
            if ($script->args) { // footer scripts only
                $src = $script->src;
                if (! preg_match('|^(https?:)?//|', $src)) {
                    $src = site_url($src);
                }
                echo '<script type="text/javascript" src="' . esc_url($src) . '"></script>' . "\n";
            }
        }
    }
    ?>

</body>

</html>