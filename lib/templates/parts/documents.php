<?php $i = 0; global $post; $id = $post->id;?>

<div id="psp-documents-list">
	
	<div id="psp-document-nav">
		<input id="psp-documents-live-search" type="text" placeholder="Search..." class="psp-col-md-6"> 
	</div>
		
	<ul class="psp-documents-row list">

        <?php

		while(has_sub_field('documents',$post_id)):

			$file = get_sub_field('file');
			$url = get_sub_field('url');

			// Check to see if there is a file, if not, use the manually entered URL

			if(!empty($file)) {
				$doc_link = $file['url'];
			} else {
				$doc_link = $url;
			}

			$icon = psp_get_icon_class($doc_link);
			
			$doc_status = psp_translate_doc_status(get_sub_field('status'));

			?>

			<li id="psp-project-<?php echo esc_attr($post_id); ?>-doc-<?php echo esc_attr($i); ?>" class="list-item <?php if((psp_can_edit_project($id)) && (get_post_type() == 'psp_projects')) { echo 'psp-can-edit'; } ?>">
                <form method="post" action="<?php echo esc_url(get_permalink($post_id)); ?>" class="document-update-form">

                    <?php if((is_user_logged_in()) && (get_post_type() == 'psp_projects')) { ?>

                        <input type="hidden" name="psp-project-id" value="<?php echo esc_attr($post_id); ?>">
                        <input type="hidden" name="psp-document-id" value="<?php echo esc_attr($i); ?>">
                        <input type="hidden" name="psp-document-name" value="<?php the_sub_field('title'); ?>">
                        <input type="hidden" name="psp-current-user" value="<?php echo esc_attr(psp_username_by_id(get_current_user_id())); ?>">

                    <?php } ?>

				    <a href="<?php echo esc_url($doc_link); ?>" class="psp-icon <?php echo esc_attr($icon); ?>"></a>
				    <p class="psp-doc-title">
					    <a href="<?php echo esc_url($doc_link); ?>" target="_new"><strong class="doc-title"><?php the_sub_field('title'); ?></strong></a>
					    <a class="doc-status status-<?php the_sub_field('status'); ?>" href="#psp-du-doc-<?php echo esc_attr($i); ?>"><?php echo esc_html($doc_status); ?> <?php if((psp_can_edit_project($id)) && (get_post_type() == 'psp_projects')) { ?><span class="fa fa-pencil" href="#"></span><?php } ?></a>
					    <span class="description"><?php the_sub_field('description'); ?></span>

				    </p>


                        <?php if((psp_can_edit_project($id)) && (get_post_type() == 'psp_projects')) { ?>

                        <div class="document-update-dialog psp-hide" id="psp-du-doc-<?php echo esc_attr($i); ?>">

							<div class="psp-document-form">
								
                            	<h4><?php esc_html_e('Update Status','psp_projects'); ?><strong><?php the_sub_field('title'); ?></strong></h4>
                            	<p><label for="psp-doc-status-field"><?php esc_html_e('Status','psp_projects'); ?></label>
                            		<select class="psp-doc-status-field" id="psp-pro-<?php echo esc_attr($post_id); ?>-doc-<?php echo esc_attr($i); ?>">
                                		<option value="<?php the_sub_field('status'); ?>"><?php the_sub_field('status'); ?></option>
                                    	<option value="<?php the_sub_field('status'); ?>">---</option>
                                    	<option value="Approved"><?php esc_html_e('Approved','psp_projects'); ?></option>
                                    	<option value="In Review"><?php esc_html_e('In Review','psp_projects'); ?></option>
                                    	<option value="Revisions"><?php esc_html_e('Revisions','psp_projects'); ?></option>
                                    	<option value="Rejected"><?php esc_html_e('Rejected','psp_projects'); ?></option>
                                	</select>
                            	</p>

                            	<p><label for="psp-doc-notify"><?php esc_html_e('Notify','psp_projects'); ?></label></p>

                           	 	<ul class="psp-notify-list">
                            		<li class="all-line"><input type="checkbox" class="all-checkbox" name="psp-notify-all" value="all"> All Users</li>
                                	<?php
                                	$project_id = $post->ID; // Get the project ID

                                	while (has_sub_field('allowed_users', $project_id)) {

                                		$user = get_sub_field('user');
										$username = psp_get_nice_username($user);
									
                                    	echo '<li><input type="checkbox" name="psp-user[]" value="' . esc_attr($user["ID"]) . '" class="psp-notify-user-box"> ' . esc_html($username) . '</li>';

                                	}  ?>
                            	</ul>

                           	 	<p><label for="psp-doc-message"><?php esc_html_e('Message','psp_projects'); ?></label></p>

                            	<p><textarea name="psp-doc-message"></textarea></p>
							
							</div> <!--/.psp-document-form-->
							
							<div class="psp-hide psp-message-form">
								
								<h4><?php esc_html_e('Document Status Updated','psp_projects'); ?></h4>
								<p class="psp-hide psp-confirm-note"><?php esc_html_e('Notifications have been sent.','psp_projects'); ?></p>
								
							</div>
							
							<div class="pano-modal-actions">
								<p><input type="submit" name="update" value="update"> <a href="#" class="modal-close">Cancel</a></p>
							</div> <!--/.pano-modal-actions-->

                        </div>
                        <?php } // end can edit ?>
                </form>
			</li>

		<?php $i++; endwhile; ?>

	</ul>
</div>
