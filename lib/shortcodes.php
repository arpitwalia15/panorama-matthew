<?php



/* Display Current Projects */





function psppan_current_projects($atts) {



    extract( shortcode_atts(

            array(

                'type' => 'all',

                'status' => 'all',

                'access' => 'user'

            ), $atts )

    );



    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;



    $args = array(

        'post_type' => 'psp_projects',

        'paged'	=> $paged,

        'posts_per_page' => '10'

    );



    // If a type has been selected, add it to the argument



    if((!empty($type)) && ($type != 'all')) {

        $tax_args = array('psp_tax' => $type);

        $args = array_merge($args,$tax_args);



    }



    // If you want completed only



    if($status == 'active') {



        $meta_args = array(

            'meta_query' => array(

                'relation'	=> 'OR',

                array(

                    'key' => '_psp_completed',

                    'value' => '1',

                    'compare' => '!='

                ),

                array(

                    'key' => '_psp_completed',

                    'value'	=> '0',

                    'compare' => 'NOT EXISTS'

                )



            )

        );



        $args = array_merge($args,$meta_args);



    }



    if($status == 'completed') {

        $meta_args = array(

            'meta_query' => array(

                array(

                    'key' => '_psp_completed',

                    'value' => '1',

                )

            )

        );

        $args = array_merge($args,$meta_args);

    }



    if($access == 'user') {



        if( !current_user_can('edit_others_psp_projects') ) {



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



            $args = array_merge( $args, $meta_query );



        }

    }



    $projects = new WP_Query($args);



    if($projects->have_posts()):

        ob_start(); ?>



        <table class="psp_project_list">

            <thead>

            <tr>

                <th class="psp_pl_col1"><?php esc_html_e('Project','psp_projects'); ?></th>

                <th class="psp_pl_col2"><?php esc_html_e('Progress','psp_projects'); ?></th>

            </tr>

            </thead>



            <?php while($projects->have_posts()): $projects->the_post(); global $post; ?>



                <tr>

                    <td><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></td>

                    <td>

                        <?php global $post; $completed = psp_compute_progress($post->ID);



                        if(!empty($completed)) {

                            echo '<p class="progress"><span class="psp-'.esc_attr($completed).'"><b>'.esc_html($completed).'%</b></span></p>';

                        } ?>

                    </td>

                </tr>



            <?php endwhile; ?>



        </table>



        <?php get_next_posts_link('&laquo; More Projects',$projects->max_num_pages).' '.get_previous_posts_link('Previous Projects &raquo;'); ?>



        <?php psp_front_styles(1);



        return ob_get_clean();



    else:



        return '<p>'.__('No projects found','psp_projects').'</p>';



    endif;



}

add_shortcode('project_list','psppan_current_projects');



function psppan_project_listing_dialog() {



    $psp_taxes = get_terms('psp_tax');

    $psp_tax_list = '';



    foreach($psp_taxes as $tax) {

        $psp_tax_list .= '<option value="'.$tax->slug.'">'.$tax->name.'</option>';

    }

	

	$allowed_html = array(

		'option' => array(

			'value'  => array(),

		 ),

	);

	

	?>

    

<div class="psp-dialog" style="display:none">

	<div id="psp-project-listing-dialog">

		<h3><?php echo esc_html_e('Project Listing','psp_projects'); ?></h3>

		<p><?php echo esc_html_e('Select from the options below to output a list of projects.','psp_projects'); ?></p>

		<table class="form-table">

			<tr>

				<th><label for="psp-project-taxonomy"><?php echo esc_html_e('Project Type','psp_projects'); ?></label></th>

				<td>

					<select id="psp-project-taxonomy" name="psp-project-taxonomy">

						<option value="all">Any</option>

						<?php echo esc_html($psp_tax_list, $allowed_html); ?>

					</select>

				</td>

			</tr>

			<tr>

				<th><label for="psp-project-status"><?php echo esc_html_e('Project Status','psp_projects'); ?></label></th>

				<td>

					<select id="psp-project-status" name="psp-project-status">

						<option value="all"><?php echo esc_html_e('All','psp_projects'); ?></option>

						<option value="active"><?php echo esc_html_e('Active','psp_projects'); ?></option>

						<option value="completed"><?php echo esc_html_e('Completed','psp_projects'); ?></option>

					</select>

				</td>

			</tr>

			<tr>

				<th colspan="2">

					<input type="checkbox" name="psp-user-access" id="psp-user-access" checked>

					<label for="psp-user-access"><?php echo esc_html_e('Only display projects current user has permission to access','psp_projects'); ?></label>

				</th>

			</tr>

		</table>



		<p><input class="button-primary" type="button" onclick="InsertPspProjectList();" value="<?php echo esc_html_e('Insert Project List','psp_projects'); ?>"> <a class="button" onclick="tb_remove(); return false;" href="#"><?php echo esc_html_e('Cancel','psp_projects'); ?></a></p>



	</div>

</div>

<?php

    //echo $output;



}



function psppan_buttons() {

    add_filter('mce_external_plugins','psppan_add_buttons');

    add_filter('mce_buttons','psppan_register_buttons');

}



function psppan_add_buttons($plugin_array) {

    $plugin_array['pspbuttons'] = plugins_url(). '/psp_projects/assets/js/psp-buttons.js';

    return $plugin_array;

}



function psppan_register_buttons($buttons) {



    array_push($buttons,'currentprojects','singleproject');



    return $buttons;

}



function psppan_refresh_mce($ver) {

    $ver += 3;

    return $ver;

}



add_filter( 'tiny_mce_version', 'psppan_refresh_mce');

add_action('init','psppan_buttons');





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



function psppan_dashboard_shortcode($atts) {



    $output = '<div class="psp-dashboard-widget">'.psp_populate_dashboard_widget().'</div>';

    return $output;



}



add_shortcode('panorama_dashboard','psppan_dashboard_shortcode');





/**

 *

 * Function psppan_my_projects

 *

 * Outputs the Dashboard Widget in Shortcode Format

 *

 * @return ($output) (List of projects related to a particular user)

 *

 */



function psppan_my_projects() {



    /* Ensure user is logged in */



    if(is_logged_in()):



        // Get the current user

        $curuser = wp_get_current_user();



        $projects = new WP_Query(array('type' => 'psp_project'));



    else:



    endif;



}



?>

