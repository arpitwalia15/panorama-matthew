<?php
function psppan_cmb2_task_field( $metakey, $post_id = 0 ) {
    echo psppan_cmb2_get_task_field( $metakey, $post_id );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Template tag for returning a task field from CMB2 (front-end).
 *
 * @since 0.1.0
 *
 * @param string $metakey The 'id' of the task field (meta key).
 * @param int    $post_id Optional post ID. Defaults to current post in the loop.
 * @return string HTML output of the task field.
 */
function psppan_cmb2_get_task_field( $metakey, $post_id = 0 ) {
    $post_id = $post_id ? $post_id : get_the_ID();

    $task = get_post_meta( $post_id, $metakey, true );

    // Set default values for each task key
    $task = wp_parse_args( $task, array(
        'title'    => '',
        'complete' => '',
    ) );

    $output  = '<div class="cmb2-task">';
    $output .= '<p><strong>Task:</strong> ' . esc_html( $task['title'] ) . '</p>';
    $output .= '<p><strong>Completion:</strong> ' . esc_html( $task['complete'] ) . '</p>';
    $output .= '</div><!-- .cmb2-task -->';

    return apply_filters( 'psppan_cmb2_get_task_field', $output );
}

function cmb2_init_task_field() {
    require_once dirname( __FILE__ ) . '/class-cmb2-render-task-field.php';
    CMB2_Render_Task_Field::init();
}
add_action( 'cmb2_init', 'cmb2_init_task_field' );
