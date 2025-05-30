<?php
/**
 * Created by PhpStorm.
 * User: rossjohnson
 * Date: 1/3/15
 * Time: 1:18 PM
 */
?>

<div id="psp-short-progress">
	<h4><?php esc_html_e('Project Progress','psp_projects'); ?></h4>
	<p class="psp-progress">
		<?php if( psp_compute_progress( $id ) == 0 ): ?>
		<?php elseif( psp_compute_progress( $id ) < 10 ): ?>
			<span class="psp-<?php echo esc_attr(psp_compute_progress($id)); ?>"></span>
		<?php else: ?>
			<span class="psp-<?php echo esc_attr(psp_compute_progress($id)); ?>"><?php echo esc_html(psp_compute_progress($id)); ?>%</span>
		<?php endif; ?>
	</p>
</div>
