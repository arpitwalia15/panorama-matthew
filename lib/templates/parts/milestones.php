<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="<?php echo esc_attr($style); ?>">



	<h2><?php esc_html_e( "Overall Project Completion", "psp_projects" ); ?></h2>



	<div class="psp-holder"></div>



	<?php
// if (!isset($id)) {
//     $id = 0; // Set a default value to prevent errors
// }
	$completed 			= psp_compute_progress( $id );

	$display_milestones = get_field( 'display_milestones',$id);

// if (!isset($options)) {
//     $options = 'no'; 
// }

	if( $options != 'no' ):



		$frequency = get_field( 'milestone_frequency', $id );



		if( get_field( 'milestone_frequency', $id ) == 'quarters' ) {



			$first 	= '25';

			$second = '50';

			$third 	= '75';

			$fourth = '100';



		} else {



			$first 		= '20';

			$second 	= '40';

			$third 		= '60';

			$fourth 	= '80';

			$frequency 	= 'fifths';



		}



		if( $options != 'condensed' ): ?>



		<ul class="top-milestones cf psp-milestones frequency-<?php echo esc_attr($frequency); ?>">

			<li class="psp-<?php echo esc_attr($first); ?>-milestone <?php if($completed >= $first) { echo ' completed'; } ?>">

				<div>

					<?php if($display_milestones) { ?>

						<h4><?php the_field('25%_title',$id); ?></h4>

						<p><?php echo do_shortcode( get_field( '25%_description', $id ) ); ?></p>

					<?php } ?>

					<span><?php echo esc_html($first); ?>%</span>

				</div>

			</li>

			<li class="psp-<?php echo esc_attr($third); ?>-milestone <?php if($completed >= $third) { echo ' completed'; } ?>">

				<div>

				<?php if($display_milestones) { ?>

					<h4><?php the_field('75%_title',$id); ?></h4>

					<p><?php echo do_shortcode( get_field( '75%_description', $id ) ); ?></p>

				<?php } ?>

					<span><?php echo esc_html($third); ?>%</span>

				</div>

			</li>

		</ul>



	<?php



			endif;

	endif; ?>

	<p class="psp-progress">

		<?php

		if( $completed == 0 ):

		elseif( $completed < 10 ): ?>

			<span class="psp-<?php echo esc_attr(psp_compute_progress($id)); ?>"></span>

		<?php else: ?>

			<span class="psp-<?php echo esc_attr(psp_compute_progress($id)); ?>"><?php echo esc_html(psp_compute_progress($id)); ?>%</span>

		<?php endif; ?>

	</p>



	<?php

	if($options != 'no'):

		if($options != 'condensed'): ?>



			<ul class="bottom-milestones cf psp-milestones frequency-<?php echo esc_attr($frequency); ?>">

				<li class="psp-<?php echo esc_attr($second); ?>-milestone <?php if($completed >= $second) { echo ' completed'; } ?>">

					<div>

						<span><?php echo esc_html($second); ?>%</span>

						<?php if($display_milestones) { ?>

							<h4><?php the_field('50%_title',$id); ?></h4>

							<p><?php echo do_shortcode( get_field( '50%_description', $id ) ); ?></p>

						<?php } ?>

					</div>

				</li>



			<?php if($frequency == 'fifths'): ?>



				<li class="psp-<?php echo esc_attr($fourth); ?>-milestone' <?php if($completed >= $fourth) { echo ' completed'; } ?>">

				<div>

					<span><?php echo esc_html($fourth); ?>%</span>

					<?php if($display_milestones) { ?>

						<h4><?php the_field('100%_title',$id); ?></h4>

						<p><?php echo do_shortcode( get_field( '100%_description', $id ) ); ?></p>

					<?php } ?>

				</div>

			</li>



			<?php endif; ?>



		</ul>



		<?php



		endif;



	endif;



	if((get_field('display_milestones',$id))  && ($options != 'no')): ?>

	<div class="progress-table <?php echo esc_attr($style); ?> milestone-options-<?php echo esc_attr($options); ?>">

		<table class="progress-table">

			<tr>

				<th class="psp-milestones <?php if($completed >= $first) { echo 'completed'; } ?>"><span><?php echo esc_html($first); ?>%</span></th>

				<td>

					<h4><?php the_field('25%_title',$id); ?></h4>

					<p><?php echo do_shortcode( get_field( '25%_description', $id ) ); ?></p>

				</td>

			</tr>

			<tr>

				<th class="psp-milestones <?php if($completed >= $second) { echo 'completed'; } ?>"><span><?php echo esc_html($second); ?>%</span></th>

				<td>

					<h4><?php the_field('50%_title',$id); ?></h4>

					<p><?php echo do_shortcode( get_field( '50%_description', $id ) ); ?></p>

				</td>

			</tr>

			<tr>

				<th class="psp-milestones <?php if($completed >= $third) { echo 'completed'; } ?>"><span><?php echo esc_html($third); ?>%</span></th>

				<td>

					<h4><?php the_field('75%_title',$id); ?></h4>

					<p><?php echo do_shortcode( get_field( '75%_description', $id ) ); ?></p>

				</td>

			</tr>

			<?php if($frequency == 'fifths'): ?>

			<tr>

				<th class="psp-milestones <?php if($completed >= $fourth) { echo 'completed'; } ?>"><span><?php echo esc_html($fourth); ?>%</span></th>

				<td>

					<h4><?php the_field('100%_title',$id); ?></h4>

					<p><?php echo do_shortcode( get_field( '100%_description', $id ) ); ?></p>

				</td>

			</tr>

			<?php endif;?>

		</table>

	</div>



<?php endif; ?>



</div>

