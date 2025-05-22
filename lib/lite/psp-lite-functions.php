<?php
/* Custom functions for PSP Lite */
function psppan_lite_documents( $id = NULL ) {

	global $post;

	$id = ( isset( $id ) ? $id : $post->ID );

	ob_start();

	include(psp_template_hierarchy('/parts/documents-lite.php'));

	return ob_get_clean();

}
