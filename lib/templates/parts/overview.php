<?php



global $post;



$id = ( !empty( $id ) ? $id : $post->ID ); ?>



		<div id="psp-essentials" class="<?php echo esc_attr($style); ?> cf">



			<div id="psp-description-documents" class="psp-overview-box cf">



				<div id="psp-description">



					<div class="summary">

						<h4><?php esc_html_e('Project Description','psp_projects'); ?></h4>

						<?php the_field('project_description',$id); ?>

					</div>



				</div>



				<?php if($docs != 'none'): ?>



					<div id="psp-documents" class="<?php echo esc_attr($style); ?>">



						<h4><?php esc_html_e('Documents','psp_projects'); ?></h4>



						<?php if(PSP_PLUGIN_TYPE == 'professional') {



								if(get_field('documents',$id)) {

									include( psp_template_hierarchy( '/parts/documents-lite.php' ) );

								} else {

									echo '<p>'.esc_html_e("No documents at this time.", 'psp_projects').'</p>';

								}



							} else {



								$documents_text = get_field('documents2');



								$documents_fields = get_post_meta($id,'_pano_documents');



								if((empty($documents_text)) && (empty($documents_fields))) {

									echo '<p>'.esc_html_e("No documents at this time.", 'psp_projects').'</p>';

								} else {



									echo wp_kses_post($documents_text);



									include(psp_template_hierarchy('/parts/documents-lite.php'));



								}



							}



							do_action('psppan_after_documents'); ?>



						</div> <!--/#project-documents-->



				<?php endif; ?>



			</div> <!--/.psp-overview-box-->



			<div id="psp-quick-overview">



				<?php include(psp_template_hierarchy('/parts/short-progress.php')); ?>



				<?php echo wp_kses_post(psp_the_timing($id)); ?>



			</div>



		</div> <!--/#psp-essentials-->

