<?php
/* Custom Single.php for project only view */
global $post, $lpm_doctype;


?>

<!DOCTYPE html>
<html <?php language_attributes( $lpm_doctype ); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $client = get_field('client'); ?>
    <title><?php the_title(); ?> | <?php echo esc_html($client); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(get_field('hide_from_search_engines',$post->ID)): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
    <?php // wp_head(); Removed for visual consistency
    wp_enqueue_script('jquery');

           wp_enqueue_style('psp-frontend',  PSP_PLUGINURI . '/css/psp-frontend.css', [], '1.2.5');
           wp_enqueue_style('psp-custom', PSP_PLUGINURI . '/css/psp-custom.css.php', [], null);

            // Enqueue JS
            wp_enqueue_script('psp-frontend-lib', PSP_PLUGINURI . '/js/psp-frontend-lib.min.js', ['jquery'], '1.2.5', true);
            wp_enqueue_script('psp-frontend-behavior', PSP_PLUGINURI . '/js/psp-frontend-behavior.js', ['jquery'], '1.2.5', true);

            // Localize Ajax URL
            wp_localize_script('psp-frontend-behavior', 'lpmMyAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);

            wp_enqueue_script('html5shiv', PSP_PLUGINURI . '/js/html5shiv.min.js', [], null, true);
            wp_script_add_data('html5shiv', 'conditional', 'lte IE 9');

            wp_enqueue_script('lpm-css3-mediaqueries', PSP_PLUGINURI . '/js/css3-mediaqueries.js', [], null, true);
            wp_script_add_data('lpmm-css3-mediaqueries', 'conditional', 'lte IE 9');

            wp_enqueue_style('ie-styles', PSP_PLUGINURI . '/css/ie.css', [], null);
            wp_style_add_data('ie-styles', 'conditional', 'IE');

     ?>

    <?php do_action('psppan_head'); ?>
</head>
<body class="psp-standalone-page">

<div id="psp-projects" class="psp-standard-template" data-post_id="<?php echo esc_attr( get_the_ID() ); ?>" data-autoprogress="<?php echo esc_attr( ( get_field('automatic_progress') ? 'true' : 'false' ) ); ?>" data-autophaseprogress="<?php echo esc_attr( ( get_field('phases_automatic_progress') ? 'true' : 'false' ) ); ?>">
    <?php while(have_posts()): the_post(); ?>
        <?php do_action('psppan_the_header'); ?>
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
                        <?php $commentPath = getcwd().'/psp-comment-part.php'; ?>
                        <?php comments_template($commentPath,true); ?>
                    </div>
                </div>
            </section>
    <?php endwhile; // ends the loop ?>
</div> <!--/#psp-project-->
<?php wp_footer(); ?>
</body>
</html>

