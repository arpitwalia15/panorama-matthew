<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$panorama_access = panorama_check_access( $post->ID );
?>

<div id="psp-projects" class="psp-theme-template psp-reset">
    
    <input type="hidden" id="psp-task-style" value="<?php echo esc_attr( get_field( 'expand_tasks_by_default', $post->ID ) ); ?>">

    <?php // do_action( 'psp_the_header' ); ?>

    <?php if ( $panorama_access === 1 ) : ?>

        <?php do_action( 'psppan_before_overview' ); ?>

        <section id="overview" class="wrapper psp-section">
            <?php
            do_action( 'psppan_before_essentials' );
            do_action( 'psppan_the_essentials' );
            do_action( 'psppan_after_essentials' );
            ?>
        </section><!-- /#overview -->

        <?php do_action( 'psppan_between_overview_progress' ); ?>

        <section id="psp-progress" class="cf psp-section">
            <?php
            do_action( 'psppan_before_progress' );
            do_action( 'psppan_the_progress' );
            do_action( 'psppan_after_progress' );
            ?>
        </section><!-- /#psp-progress -->

        <?php do_action( 'psppan_between_progress_phases' ); ?>

        <section id="psp-phases" class="wrapper psp-section">
            <?php
            do_action( 'psppan_before_phases' );
            do_action( 'psppan_the_phases' );
            do_action( 'psppan_after_phases' );
            ?>
        </section>

        <?php do_action( 'psppan_between_phases_discussion' ); ?>

    <?php elseif ( $panorama_access === 0 ) : ?>

        <div id="overview" class="wrapper">
            <div id="psp-login">

                <?php if ( isset( $access_granted ) && $access_granted == 0 && get_field( 'restrict_access_to_specific_users' ) ) : ?>
                    <h2><?php esc_html_e( 'This Project Requires a Login', 'psp_projects' ); ?></h2>
                    <?php
                    if ( ! is_user_logged_in() ) {
                        panorama_login_form();
                    } else {
                        echo '<p>' . esc_html__( "You don't have permission to access this project", 'psp_projects' ) . '</p>';
                    }
                    ?>
                <?php endif; ?>

                <?php if ( post_password_required() && ! current_user_can( 'manage_options' ) ) : ?>
                    <h2><?php esc_html_e( 'This Project is Password Protected', 'psp_projects' ); ?></h2>
                    <?php
                    // Safe because WordPress outputs sanitized HTML
                    echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                <?php endif; ?>

            </div>
        </div>

    <?php endif; ?>

</div><!-- /#psp-projects -->
