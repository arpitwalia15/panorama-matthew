<?php



	/* Init.php



	Master file, builds everything.



	*/





	// Include Advanced Custom Fields - NOTE: Premium "Repeater Field" Add-on is NOT to be used or distributed outside of this plugin per original copyright information from ACF - http://www.advancedcustomfields.com/resources/getting-started/including-lite-mode-in-a-plugin-theme/



// if( file_exists( dirname(__FILE__) . '/pro/init.php' ) ) {



// 	define('PSP_PLUGIN_TYPE','professional');

// 	define('PSPPAN_PLUGIN_DIR','project-panorama');



// } else {



// 	define('PSP_PLUGIN_TYPE','lite');

// 	define('PSPPAN_PLUGIN_DIR','psp_projects');



// }



// global $acf;



// if( !$acf ) {



// 	define( 'MY_ACF_PATH', PROJECT_PANARAM_DIR . '/lib/acf/master/' );

// 	define( 'MY_ACF_URL', PROJECT_PANARAM_URI . '/lib/acf/master/' );



// 	include_once( 'acf/master/acf.php' );



// }





// function psp_acf_settings_path( $path ) {



//     // update path

//     $path = PSPPAN_PLUGIN_DIR . '/lib/acf/master';



//     // return

//     return $path;



// }





// // 2. customize ACF dir



// function psp_acf_settings_dir( $dir ) {



//     // update path

//     $dir = PSPPAN_PLUGIN_DIR . '/acf/';



//     // return

//     return $dir;



// }





// if( file_exists( dirname(__FILE__) . '/pro/init.php' ) ) {



//     require_once('pro/init.php');



//     if(!class_exists('acf_field_repeater')) {



//         include_once('acf/repeater/acf-repeater.php');



// 	}





// } else {



//     require_once('lite/init.php');



// }



// if(!function_exists('duplicate_post_is_current_user_allowed_to_copy')) {

//     include_once('clone/duplicate-post.php');

// }



// include_once('acf/slider/acf-slider.php');

require_once('data_model.php');

require_once('custom_templates.php');

require_once('view.php');

require_once('front_end.php');

require_once('custom_comments.php');

require_once('helper.php');

require_once('shortcodes.php');

