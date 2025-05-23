<?php



/* Custom Single.php for project only view */

global $post, $lpm_doctype;



$cuser = wp_get_current_user();

?>

<!DOCTYPE html>

<html <?php language_attributes( $lpm_doctype ); ?>>

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo esc_html($cuser->first_name.' '.$cuser->last_name); ?> <?php esc_html_e('Projects','psp_projects'); ?></title>



    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  

    <meta name="robots" content="noindex, nofollow">
  <?php


           wp_enqueue_style('psp-frontends',  PSP_PLUGINURI . '/css/psp-frontend.css', [], '1.2.5');
           wp_enqueue_style('psp-custom', PSP_PLUGINURI . '/css/psp-custom.css.php', [], null);

            // Enqueue JS
            // wp_enqueue_script('psp-jquery', PSP_PLUGINURI . '/js/jquery.js', [], '1.2.5', true);
            wp_enqueue_script('psp-frontend-lib', PSP_PLUGINURI . '/js/psp-frontend-lib.min.js', ['jquery'], '1.2.5', true);
            wp_enqueue_script('psp-frontend-behavior', PSP_PLUGINURI . '/js/psp-frontend-behavior.js', ['jquery'], '1.2.5', true);

            // Localize Ajax URL
            wp_localize_script('psp-frontend-behavior', 'lpmMyAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);

            wp_enqueue_script('html5shiv', PSP_PLUGINURI . '/js/html5shiv.min.js', [], null, false);
            wp_script_add_data('html5shiv', 'conditional', 'lte IE 9');

            wp_enqueue_script('lpm-css3-mediaqueries', PSP_PLUGINURI . '/js/css3-mediaqueries.js', [], null, false);
            wp_script_add_data('lpm-css3-mediaqueries', 'conditional', 'lte IE 9');

            wp_enqueue_style('ie-styles', PSP_PLUGINURI . '/css/ie.css', [], null);
            wp_style_add_data('ie-styles', 'conditional', 'IE');
    ?>




    <?php do_action('psppan_head'); ?>



</head>

<body id="psp-projects" class="psp-standalone-page psp-dashboard-page">


		<div class="psp-dashboard">



			<div id="psp-archive-content" class="psp-dashboard__content">



			    <?php if((get_option('psp_logo') != '') && (get_option('psp_logo') != 'http://')) { ?>



			        <section id="psp-branding" class="wrapper">

			            <div class="psp-branding-wrapper">

			                <a href="<?php echo esc_url(site_url()); ?>"><img src="<?php echo esc_url(get_option('psp_logo')); ?>"></a>

			            </div>

			        </section>



			    <?php } ?>



				<div class="psp-grid-row">



					<?php

                   $projects = array(

                       'active'    =>  psp_get_all_my_projects('active'),

                       'all'       =>  psp_get_all_my_projects(),

                   );



                   $project_overview = psp_my_projects_overview( $projects['all'] ); ?>



					<ul class="psp-project-tiles cf">

						<li class="psp-col-md-3 psp-col-sm-6">

							<div class="psp-project-tile">

								<?php esc_html_e('Total','psp_projects'); ?> <span><?php echo esc_html($project_overview['total']); ?></span>

							</div>

						</li>

						<li class="psp-col-md-3 psp-col-sm-6">

							<div class="psp-project-tile">

								<?php esc_html_e('Active','psp_projects'); ?> <span><?php echo esc_html($project_overview['active']); ?></span>

							</div>

						</li>

						<li class="psp-col-md-3 psp-col-sm-6">

							<div class="psp-project-tile">

								<?php esc_html_e('Completed','psp_projects'); ?> <span><?php echo esc_html($project_overview['completed']); ?></span>

							</div>

						</li>

						<li class="psp-col-md-3 psp-col-sm-6">

							<div class="psp-project-tile">

								<?php esc_html_e('Unstarted','psp_projects'); ?> <span><?php echo esc_html($project_overview['inactive']); ?></span>

							</div>

						</li>

					</ul>



				</div>



				<div class="psp-archive-section">



					<h2 class="psp-box-title"><?php esc_html_e('Active Projects','psp_projects'); ?></h2>



					<div class="psp-archive-list-wrapper">

						<?php echo wp_kses_post(psp_archive_project_listing( $projects['active'] )); ?>

					</div>



				</div>



                    <?php

                    if( current_user_can('publish_psp_projects') ): ?>

                         <div class="psp-archive-actions">

                              <a href="<?php echo esc_url( admin_url() . 'post-new.php?post_type=psp_projects' ); ?>" class="pano-btn"><?php esc_html_e( 'Add Project', 'psp_projects' ); ?></a>

                         </div>

                    <?php endif; ?>



			</div> <!--/.psp-md-8-->



			<div id="psp-archive-menu" class="psp-dashboard__nav">



				<div class="psp-archive-user">



					<?php $cuser = wp_get_current_user(); ?>



					<?php echo get_avatar($cuser->ID); ?>

					<p><?php echo esc_html(psp_username_by_id($cuser->ID)); ?></p>



				</div> <!--/.psp-archive-user-->



				<div class="psp-archive-projects">



					<h2><?php esc_html_e('Active Projects','psp_projects'); ?></h2>



					<?php

                   if( $projects['active']->have_posts() ): ?>

						<ul class="psp-project-list">

                           <?php while( $projects['active']->have_posts() ): $projects['active']->the_post(); ?>

								<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

							<?php endwhile; ?>

						</ul>

					<?php else: ?>

						<p><em><?php esc_html_e('No active projects at this time','psp_projects'); ?></em></p>

					<?php endif; ?>



				</div>



                    <?php

                    if( current_user_can('publish_psp_projects') ): ?>

                         <div class="psp-archive-actions">

                              <a href="<?php echo esc_url( admin_url() . 'post-new.php?post_type=psp_projects' ); ?>" class="pano-btn"><?php esc_html_e( 'Add Project', 'psp_projects' ); ?></a>

                         </div>

                    <?php endif; ?>



			</div> <!--/.psp-row-grid-->

		</div> <!--/.psp-container-->



	</div>


<?php  wp_footer(); ?>


</body>

</html>

