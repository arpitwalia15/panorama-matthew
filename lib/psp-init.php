<?php



	/* Init.php



	Master file, builds everything.



	NOTE: Premium "Repeater Field" Add-on is NOT to be used or distributed outside of this plugin per original copyright information from ACF

	http://www.advancedcustomfields.com/resources/getting-started/including-lite-mode-in-a-plugin-theme/



	*/



/**

 * Load CMB2 if it's not already loaded and this isn't pro

 */

if ( ! class_exists( 'CMB2', false ) && ! file_exists( dirname( __FILE__ ) . '/pro/init.php' ) ) {
	require_once 'lite/CMB2/init.php';
}




/** User search render field **/

if( !function_exists('cmb2_user_search_render_field') ) {

	require_once( 'lite/CMB2-user-search/cmb2_user_search_field.php' );

}



require_once( 'lite/CMB2-conditionals/cmb2-conditionals.php' );



require_once( 'lite/CMB2-task/cmb2-task.php' );



if( file_exists( dirname(__FILE__) . '/pro/init.php' ) ) {



	define('PSPPAN_PLUGIN_TYPE','professional');

	define('PSPPAN_PLUGIN_DIR','project-panorama');



} else {



	define('PSPPAN_PLUGIN_TYPE','lite');

	define('PSPPAN_PLUGIN_DIR','project-panorama-lite');



}



// global $acf;



// if( !$acf ) {



// 	define( 'MY_ACF_PATH', PROJECT_PANORAMA_DIR . '/lib/acf/master/' );

// 	define( 'MY_ACF_URL', PROJECT_PANORAMA_URI . '/lib/acf/master/' );



// 	include_once( MY_ACF_PATH . 'acf.php' );

// 	// Customize the url setting to fix incorrect asset URLs.

// 	add_filter('acf/settings/url', 'psp_acf_settings_url');





// }



// function psp_acf_settings_url( $url ) {

//     return MY_ACF_URL;

// }





// function psp_acf_settings_path( $path ) {



//     // update path

//     $path = plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/lib/acf/master/';



//     // return

//     return $path;



// }





// // 2. customize ACF dir



// function psp_acf_settings_dir( $dir ) {



//     // update path

//     $dir = plugins_url() . '/' . PSPPAN_PLUGIN_DIR . '/acf/master/';



//     // return

//     return $dir;



// }



add_action('plugins_loaded','psppan_global_init');

function psppan_global_init() {





    if(file_exists(dirname(__FILE__).'/pro/psp-pro-init.php')) {



		// This is a professional version, define constants and load libraries



        include_once( 'pro/psp-pro-init.php' );



        // if( ( !class_exists( 'acf_field_repeater' ) ) && ( !file_exists( ABSPATH . '/wp-content/plugins/acf-repeater/acf-repeater.php' ) ) ) { include_once( 'acf/repeater/acf-repeater.php' ); }



        if( !function_exists( 'acf_repeater_collapser_assets' ) ) { 
            // include_once( 'acf/collapse/acf_repeater_collapser.php' );
         }



    } else {



		// This is a free version, load the stripped down libraries



        include_once( 'lite/psp-lite-init.php' );

    }



	// Load duplicate post regardless



    // if(!function_exists('duplicate_post_is_current_user_allowed_to_copy')) { include_once('clone/duplicate-post.php'); }



    $standard_includes = array(

        // 'acf/slider/acf-slider',

        'psp-data-model',

        'psp-templates',

        'psp-view',

        'psp-assets',

        'psp-comments',

        'psp-helpers',

        'psp-base-shortcodes',

        'psp-widgets',

        'psp-timing',

		'psp-permissions'

    );







    foreach ($standard_includes as $include) {

        include_once($include.'.php');

    }







}





?>

