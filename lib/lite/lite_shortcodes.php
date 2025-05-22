<?php

function psppan_project_part($atts) {

    extract( shortcode_atts(
            array(
                'id' => '',
                'display' => '',
                'style' => ''
            ), $atts )
    );

    $project = get_post($id);

    if($project):

        $output = '<div class="psp-part-project">';

        if($display == 'overview') {

            $output .= psp_essentials($id,'psp-shortcode','none');

        } elseif ($display == 'documents') {

            $output .= '<div id="psp-essentials" class="psp-shortcode"><div id="project-documents">';

            $output .= psp_documents( $id, 'psp-shortcode' );

            $output .= '</div></div>';

        } elseif ($display == 'progress') {

            $output .= psp_total_progress($id,'psp-shortcode',$style);

        }

        $output .= '</div>';

        psp_front_styles(1);
        psp_front_scripts(1);

        return $output;

    else:

        return '<p>'.__('No project with that ID','psp_projects').'</p>';


    endif;

}

add_shortcode('project_status_part','psppan_project_part');


function psppan_single_project($atts) {
    extract( shortcode_atts(
            array(
                'id' => '',
                'overview' => '',
                'progress' => '',
                'milestones' => ''
            ), $atts )
    );

    // If attributes are not set, let's use defaults.

    if($overview == '')
        $overview = 'yes';

    if($progress == '')
        $progress == 'yes';

    if($milestones == '')
        $milestones = 'condensed';


    $project = get_post($id);
    if($project):

        $psp_shortcode = '

			<div class="psp-single-project">

				<h1>'.$project->post_title.'</h1>';

        // Is the overview to be displayed?

        if($overview == 'yes') {

            $psp_shortcode .= psp_essentials( $id, 'psp-shortcode' );

        }

        if($progress == 'yes') {

            // Display the progress bar

            $psp_shortcode .= psp_total_progress($id,'psp-shortcode',$milestones);

        }

        $psp_shortcode .= '</div>';

        psp_front_styles(1);
        psp_front_scripts(1);

        return $psp_shortcode;

    else:

        return '<p>'.__('No project with that ID','psp_projects').'</p>';


    endif;

}

add_shortcode('project_status','psppan_single_project');

function psppan_single_project_dialog() {
?>
<script>

	function psp_full_project() {

		jQuery("#psp-full-project-table").show();
		jQuery("#psp-part-project-table").hide();

	}

	function psp_part_project() {

		jQuery("#psp-full-project-table").hide();
		jQuery("#psp-part-project-table").show();


	}

	function psp_part_change() {

		target = jQuery("#psp-part-display").val();
		jQuery("tr.psp-part-option-row").hide();
		jQuery("#psp-part-" + target + "-option").show();

	}


	jQuery(document).ready(function() {

		jQuery("#psp-full-project").attr("checked",false);
		jQuery("#psp-part-project").attr("checked",false);

		psp_part_change();

	});

</script>
<div class="psp-dialog" style="display:none">
	<div id="psp-single-project-diaglog">
		<h3><?php echo esc_html_e('Insert a Project Overview','psp_projects'); ?></h3>
		<h3><?php echo esc_html_e('Insert a Project Overview','psp_projects'); ?></h3>
		<h3><?php echo esc_html_e('Insert a Project Overview','psp_projects'); ?></h3>
		<p><?php echo esc_html_e('Select a project below to add it to your post or page.','psp_projects'); ?></p>
		<table class="form-table">
			<tr>
				<th>Project</th>
				<td>
					<div class="psp-loading"></div>
					<div id="psp-single-project-list"></div>
				</td>
			</tr>
			<tr>
				<th><label for="psp-display-style">Style</label></th>
				<td>
					<label for="psp-display-style">
						<input unchecked type="radio" name="psp-display-style" onClick="psp_full_project();" id="psp-full-project" value="full">
						<?php echo esc_html_e('Full Project','psp_projects'); ?>
					</label>&nbsp;&nbsp;&nbsp;
					<label for="psp-display-style">
						<input type="radio" unchecked name="psp-display-style" onClick="psp_part_project()" id="psp-part-project" value="part">
						<?php echo esc_html_e('Portion of Project','psp_projects'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th><label for="psp-display-style">Style</label></th>
				<td>
					<label for="psp-display-style">
						<input unchecked type="radio" name="psp-display-style" onClick="psp_full_project();" id="psp-full-project" value="full">
						<?php echo esc_html_e('Full Project','psp_projects'); ?>
					</label>&nbsp;&nbsp;&nbsp;
					<label for="psp-display-style">
						<input type="radio" unchecked name="psp-display-style" onClick="psp_part_project()" id="psp-part-project" value="part">
						<?php echo esc_html_e('Portion of Project','psp_projects'); ?>
					</label>
				</td>
			</tr>
		</table>
		<table class="form-table psp-hide-table" id="psp-full-project-table">
			<tr>
				<th><label for="psp-single-overview"><?php echo esc_html_e('Overview','psp_projects'); ?></label></th>
				<td>
					<select id="psp-single-overview">
						<option value="yes"><?php echo esc_html_e('Show Overview','psp_projects'); ?></option>
						<option value="no"><?php echo esc_html_e('No Overview','psp_projects'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="psp-single-progress"><?php echo esc_html_e('Progress Bar','psp_projects'); ?></label></th>
				<td>
					<select id="psp-single-progress">
						<option value="yes"><?php echo esc_html_e('Show Progress Bar','psp_projects'); ?></option>
						<option value="no"><?php echo esc_html_e('No Progress Bar','psp_projects'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="psp-single-milestones">'.__('Milestones','psp_projects').'</label></th>
				<td>
					<select id="psp-single-milestones">
						<option value="condensed"><?php echo esc_html_e('Condensed','psp_projects'); ?></option>
						<option value="full"><?php echo esc_html_e('Full Width','psp_projects'); ?></option>
						<option value="no"><?php echo esc_html_e('No Milestones','psp_projects'); ?></option>
					</select>
				</td>
			</tr>
		</table>
		<table class="form-table psp-hide-table" id="psp-part-project-table">
			<tr>
				<th><label for="psp-part-display"><?php echo esc_html_e('Display','psp_projects'); ?></label></th>
				<td>
					<select id="psp-part-display" onChange="psp_part_change();">
						<option value="overview"><?php echo esc_html_e('Overview','psp_projects'); ?></option>
						<option value="documents"><?php echo esc_html_e('Documents','psp_projects'); ?></option>
						<option value="progress"><?php echo esc_html_e('Overall Progress','psp_projects'); ?></option>
						<option value="phases"><?php echo esc_html_e('Phases','psp_projects'); ?></option>
						<option value="tasks"><?php echo esc_html_e('Tasks','psp_projects'); ?></option>
					</select>
				</td>
			</tr>
			<tr id="psp-part-progress-option" class="psp-part-option-row">
				<th><label for="psp-part-overview-progress-select"><?php echo esc_html_e('Milestones','psp_projects'); ?></label></th>
				<td><select id="psp-part-overview-progress-select">
						<option value="full"><?php echo esc_html_e('Full Width','psp_projects'); ?></option>
						<option value="condensed"><?php echo esc_html_e('Condensed','psp_projects'); ?></option>
						<option value="no"><?php echo esc_html_e('None','psp_projects'); ?></option>
					</select>
				</td>
			</tr>
		</table>
		<p><input class="button-primary" type="button" onclick="InsertPspProject();" value="<?php echo esc_html_e('Insert Project','psp_projects'); ?>"> <a class="button" onclick="tb_remove(); return false;" href="#"><?php echo esc_html_e('Cancel','psp_projects'); ?></a></p>
	</div>
</div>
<?php
}

add_action('admin_footer-post.php', 'psppan_single_project_dialog'); // Fired on the page with the posts table
add_action('admin_footer-edit.php', 'psppan_single_project_dialog'); // Fired on the page with the posts table
add_action('admin_footer-post-new.php', 'psppan_single_project_dialog'); // Fired on the page with the posts table

add_action('admin_footer-post.php', 'psp_project_listing_dialog'); // Fired on the page with the posts table
add_action('admin_footer-edit.php', 'psp_project_listing_dialog'); // Fired on the page with the posts table
add_action('admin_footer-post-new.php', 'psp_project_listing_dialog'); // Fired on the page with the posts table

add_action('wp_ajax_psp_get_projects', 'psppan_ajax_project_list');


function psppan_ajax_project_list() {

    echo '<p><select id="psp-single-project-id">';

    $projectQuery = new WP_Query(array('post_type'=>'psp_projects','posts_per_page' => '-1'));
    while($projectQuery->have_posts()): $projectQuery->the_post();
        global $post;
        echo '<option value="'.esc_attr($post->ID).'">'.esc_html(get_the_title()).'</option>';
    endwhile;
    echo '</select></p>';

}
