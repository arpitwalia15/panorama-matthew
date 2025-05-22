<?php

/**

 * psp-assets.php

 * Register and enqueue styles and scripts for Project Panorama

 *

 * @author Ross Johnson

 * @copyright 3.7 MEDIA

 * @license GNU GPL version 3 (or later) {@see license.txt}

 * @package panorama

 **/



function psppan_front_assets($add_psp_scripts = null)
{



    if ((get_post_type() == 'psp_projects') || ($add_psp_scripts == 1)) {



        // Frontend styling



        // wp_register_style('psp-frontend', plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/assets/css/psp-frontend.css', false, '1.2.5');
        wp_register_style(
            'psp-frontend',
            PSPPAN_PLUGIN_URL . 'assets/css/psp-frontend.css',
            array(),
            '1.2.5'
        );


        wp_register_style('psp-custom', plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/assets/css/psp-custom.css.php', false, '1.2.5');

        wp_enqueue_style('psp-frontend');

        wp_enqueue_style('psp-custom');



        // Frontend Scripts

        wp_register_script('psp-frontend-library', PSPPAN_PLUGIN_URL . 'assets/js/psp-frontend-lib.min.js', array('jquery'), '1.2.5', false);

        wp_register_script('psp-frontend-behavior', plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/assets/js/psp-frontend-behavior.js', array('jquery'), '1.2.5', false);



        wp_enqueue_script('jquery');

        wp_enqueue_script('psp-frontend-library');

        wp_enqueue_script('psp-frontend-behavior');
    }
}



function psppan_admin_assets($hook = null)
{



    global $post_type;

    $screen = get_current_screen();



    // Admin Styling



    wp_register_style('psp-admin', plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/assets/css/psp-admin.css', false, '1.2.5');

    wp_register_style(
        'jquery-ui-psp',
        plugins_url('assets/css/jquery-ui-custom.css', __FILE__)
    );
    wp_register_script(
        'psp-admin-lib',
        plugins_url('assets/js/psp-admin-lib.min.js', __FILE__), // Correct URL build
        array(),  // dependencies, better to use array()
        '1.7',
        true     // load in footer (optional but recommended)
    );

    wp_register_script(
        'psp-admin-lite',
        PSPPAN_PLUGIN_URL . 'assets/js/psp-admin-lite-behavior.js',
        array('jquery'),
        PSP_VER,
        false
    );



    wp_enqueue_style('psp-admin');

    wp_enqueue_style('jquery-ui-psp');

    wp_enqueue_style('wp-color-picker');





    // Admin Scripts



    wp_enqueue_media();



    // Determine if we need wp-color-picker or not



    if ($hook == 'psp_projects_page_panorama-license') {

        wp_register_script(
            'pspadmin',
            plugins_url('assets/js/psp-admin-behavior.js', __FILE__),
            array('jquery', 'wp-color-picker'),
            '1.1',
            true
        );
    } else {

        wp_register_script('pspadmin', plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/assets/js/psp-admin-behavior.js', array('jquery'), '1.2.5', true);
    }



    wp_register_script('psp-wysiwyg', plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/assets/js/psp-wysiwyg.js', array('jquery'), '1.2.5', false);

    wp_register_script(
        'psp-chart',
        PSPPAN_PLUGIN_URL . 'assets/js/psp-chart.js',
        array(),   // or null or empty array for dependencies
        '1.2.5',
        false
    );




    // If this is the dashboard load dependencies

    if ($screen->id == 'dashboard') {

        wp_enqueue_script('psp-chart');
    }



    if ($hook == 'calendar') {

        wp_enqueue_script('psp-admin-lib');
    }



    // If this is a Panorama project load dependencies

    if ($post_type == 'psp_projects') {

        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_script('jquery-ui-slider');

        wp_enqueue_script('psp-admin-lite');
    }



    // If this is a project page or settings page load the admin scripts

    if (($post_type == 'psp_projects') || ($hook == 'psp_projects_page_panorama-license')) {

        wp_enqueue_script('pspadmin');
    }



    // If the shortcode helpers are not disabled load the WYSIWYG buttons

    if ((get_option('psp_disable_js') === '0') || (get_option('psp_disable_js') == NULL)) {

        wp_enqueue_script('psp-wysiwyg');
    }
}



// Enqeue All

add_action('admin_enqueue_scripts', 'psppan_admin_assets');

add_action('wp_enqueue_scripts', 'psppan_front_assets');



add_action('psp_head', 'psppan_lite_global_js_vars');

function psppan_lite_global_js_vars()
{ ?>



    <script>
        var pspvars = {

            ajaxurl: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',

            autoprogress: <?php echo (get_field('automatic_progress') ? 'true' : 'false'); ?>,

            autophaseprogress: <?php echo (get_field('phases_automatic_progress') ? 'true' : 'false'); ?>,
            'psp_task_nonce' : wp_create_nonce('psp_task_action'),
        }
    </script>



<?php

}
