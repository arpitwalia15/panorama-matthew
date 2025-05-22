<?php
if (! defined('ABSPATH')) {
	exit;
}
$i = 0;
global $post;
$post_id = $post->ID;
$id = (isset($id) ? $id : $post->ID); ?>

<div id="psp-documents-list">

	<div id="psp-document-nav">
		<input id="psp-documents-live-search" type="text" placeholder="Search..." class="psp-col-md-6">
	</div>

	<ul class="psp-documents-row list">

		<?php

		$documents = get_post_meta($id, '_pano_documents', true);

		if (!empty($documents)) {

			foreach ($documents as $doc):

				if (!isset($doc['title']) || empty($doc['title'])) {
					continue;
				}

				$file = (isset($doc['file']) ? $doc['file'] : false);
				$url  = (isset($doc['link']) ? $doc['link'] : false);

				$doc_link = ($file ? $file : $url);

				$icon = psp_get_icon_class($doc_link);

				$doc_status = psp_translate_doc_status($doc['status']);

		?>

				<li id="psp-project-<?php echo esc_attr($post_id); ?>-doc-<?php echo esc_attr($i); ?>" class="list-item">

					<a href="<?php echo esc_url($doc_link); ?>" class="psp-icon <?php echo esc_attr($icon); ?>"></a>
					<p class="psp-doc-title">
						<a href="<?php echo esc_url($doc_link); ?>" target="_new"><strong class="doc-title"><?php echo esc_html($doc['title']); ?></strong></a>
						<a class="doc-status status-<?php echo esc_attr($doc['status']); ?>" href="#psp-du-doc-<?php echo esc_attr($i); ?>"><?php echo esc_html($doc_status); ?></a>
						<?php if (isset($doc['description']) && !empty($doc['description'])): ?>
							<span class="description"><?php echo esc_html($doc['description']); ?></span>
						<?php endif; ?>
					</p>

				</li>

			<?php $i++;
			endforeach; ?>
		<?php } ?>
	</ul>
</div>