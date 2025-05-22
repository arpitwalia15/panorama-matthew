<!-- Hidden admin URL so we can do Ajax -->

<input id="psp-ajax-url" type="hidden" value="<?php echo esc_url(admin_url()); ?>admin-ajax.php">



<?php



        $phase_id = 0;

		$p = 0;



		if($style == 'psp-shortcode') { $wrapper_class = 'psp-shortcode-phases'; } else { $wrapper_class = 'psp-row'; } ?>



		<div class="<?php echo esc_attr($wrapper_class); ?> cf psp-total-phases-<?php echo esc_attr(psp_get_phase_count()); ?>">



			<script>



				var chartOptions = {

					responsive: true

				}



                var allCharts = [];



			</script>



		<?php



			$i = 0; $c = 0; while(has_sub_field('phases',$id)): $i++; $c++; $p++;



				if($c == 1) {



					$color = 'blue';



					if(get_option('psp_accent_color_1')) {

						$chex = get_option('psp_accent_color_1');

					} else {

						$chex = '#3299BB';

					}



				} elseif ($c == 2) {



					$color = 'teal';



					if(get_option('psp_accent_color_2')) {

						$chex = get_option('psp_accent_color_2');

					} else {

						$chex = '#4ECDC4';

					}



				} elseif ($c == 3) {



					$color = 'green';



					if(get_option('psp_accent_color_3')) {

						$chex = get_option('psp_accent_color_3');

					} else {

						$chex = '#CBE86B';

					}



				} elseif ($c == 4) {



					$color = 'pink';



					if(get_option('psp_accent_color_4')) {

						$chex = get_option('psp_accent_color_4');

					} else {

						$chex = '#FF6B6B';

					}



				} elseif ($c == 5) {

					$color = 'maroon';



					if(get_option('psp_accent_color_5')) {

						$chex = get_option('psp_accent_color_5');

					} else {

						$chex = '#C44D58';

					}



					$c = 0;

				}



		?>

		<div class="psp-phase color-<?php echo esc_attr($color); ?>" id="phase-<?php echo esc_attr($i); ?>">



			<?php



            // Get an array with critical phase information



			$phase_data  = psp_get_phase_completed($id);



			$completed       = $phase_data[0];

			$tasks           = $phase_data[1];

			$completed_tasks = $phase_data[2];



			$remaining = 100 - $completed;



			?>



			<h3>

				<?php if($style != 'psp-shortcode') { echo '<i>'.esc_html($p).'.</i> '; } the_sub_field('title'); ?>

				<span class="psp-top-complete">

					<b><?php esc_html_e('Complete','psp_projects'); ?></b> <span><?php echo esc_html($completed); ?>%</span>

					<b class="psp-pl-10"><?php esc_html_e('Tasks','psp_projects'); ?></b> <span><?php echo esc_html($completed_tasks).' / '.esc_html($tasks); ?></span>

				</span>

			</h3>



					<div class="psp-phase-overview cf psp-phase-progress-<?php echo esc_attr($completed); ?>">



						<div class="psp-chart">



							<span class="psp-chart-complete"><?php echo esc_html($completed); ?>%</span>



							<canvas class="phase-chart" id="chart-<?php echo esc_attr($i); ?>" width="100%"></canvas>



							<script>



                                jQuery(document).ready(function() {



                                    var data = [

                                        {

                                            value: <?php echo esc_attr($completed); ?>,

                                            color: "<?php echo esc_attr($chex); ?>",

                                            label: "<?php esc_html_e('Completed','psp_projects'); ?>"

                                        },

                                        {

                                            value: <?php echo esc_attr($remaining); ?>,

                                            color: "#efefef",

                                            label: "<?php esc_html_e('Remaining','psp_projects'); ?>"

                                        }

                                    ];





                                    var chart_<?php echo esc_attr($i); ?> = document.getElementById("chart-<?php echo esc_attr($i); ?>").getContext("2d");

    								



                                    allCharts[<?php echo esc_attr($i); ?>] = new Chart(chart_<?php echo esc_attr($i); ?>).Doughnut(data,chartOptions);



                                });



							</script>



						</div>

						<div class="psp-phase-info">



							<h5><?php esc_html_e('Description','psp_projects'); ?></h5>



							<?php echo do_shortcode( get_sub_field( 'description' ) ); ?>



						</div>

					</div> <!-- tasks is '.$taskStyle.'-->



					<div class="psp-task-list-wrapper">



					<?php if((get_sub_field('tasks',$id)) && ($taskStyle != 'no')):



					$taskList = psp_populate_tasks($id,$taskStyle,$phase_id);



					if(get_sub_field('tasks',$id)) {



						if($taskStyle == 'complete') {



						  $taskbar = '<span>'.$taskList[1].' '.__('completed tasks', 'psp_projects').'</span>';



						} elseif ($taskStyle == 'incomplete') {



						  $taskbar = '<span>'.$taskList[1].' '.__('open tasks', 'psp_projects').'</span>';



						} else {



							$remaing_tasks = $tasks - $completed_tasks;

							$taskbar = '<span><b>'.$completed_tasks.'</b> '.__('of','psp_projects').' '.$tasks.' '.__('completed','psp_projects').'</span>';

						}



					} else {



						$taskbar = 'None assigned';



					}



					?>



					<h4><a href="#" class="task-list-toggle"><?php echo wp_kses($taskbar, array('span' => array(),'b' => array())); esc_html_e('Tasks','psp_projects'); ?></a></h4>



                    <ul class="psp-task-list">

                        <?php echo wp_kses_post($taskList[0]); ?>

                    </ul>



				<?php endif; ?>

				</div>

			</div> <!--/.psp-task-list-->

			<?php $phase_id++; endwhile; ?>

		</div>

