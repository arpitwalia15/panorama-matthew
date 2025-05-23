<?php
add_action('admin_notices', 'lpm_admin_notice');

function lpm_admin_notice()
{
    global $pagenow, $typenow;

    $views = array('post.php', 'post-new.php');

    if (in_array($pagenow, $views) && $typenow === 'psp_projects') {
        $current_user = wp_get_current_user();

        if (!$current_user instanceof WP_User || !$current_user->ID) {
            return;
        }

        $user_id = $current_user->ID;

        if (!get_user_meta($user_id, 'panorama_ignore_notice_new', true)) {
            // Extract only the REQUEST_URI from $_SERVER and sanitize it
            $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

            // Generate the full URL from the sanitized REQUEST_URI
            $full_url = psppan_full_url($request_uri);

            // Escape the URL for output
            $safe_url = esc_url($full_url);

            // Add query arg to the safe URL
            $close_url = add_query_arg('lpm_nag_ignore', '0', $safe_url);

?>

            <div class="updated">
                <p>
                    <img src="<?php echo esc_url(PROJECT_PANORAMA_URI . '/assets/images/panorama-logo.png'); ?>" alt="<?php esc_attr_e('Project Panorama', 'psp_projects'); ?>" height="50">
                </p>
                <p>
                    <strong><?php esc_html_e('Like Project Panorama?', 'psp_projects'); ?></strong>
                    <?php esc_html_e('Did you know we have a', 'psp_projects'); ?>
                    <a href="https://www.projectpanorama.com?utm=lite-admin-notice" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('full featured premium version?', 'psp_projects'); ?>
                    </a>
                    <?php esc_html_e('Features include:', 'psp_projects'); ?>
                </p>

                <ul style="list-style:disc; padding-left: 15px;">
                    <li><?php esc_html_e('Front end task completion', 'psp_projects'); ?></li>
                    <li><?php esc_html_e('Assign tasks to users', 'psp_projects'); ?></li>
                    <li><?php esc_html_e('More sophisticated design', 'psp_projects'); ?></li>
                    <li><?php esc_html_e('Teams', 'psp_projects'); ?></li>
                    <li><?php esc_html_e('Automatic notifications', 'psp_projects'); ?></li>
                    <li><?php esc_html_e('And more!', 'psp_projects'); ?></li>
                </ul>

                <p>
                    <strong>
                        <?php esc_html_e('Use coupon code', 'psp_projects'); ?>
                        <code>litemeup</code>
                        <?php esc_html_e('to save 20%.', 'psp_projects'); ?>
                    </strong>
                    |
                    <a href="<?php echo esc_url($close_url); ?>"><?php esc_html_e('Hide Notice', 'psp_projects'); ?></a>
                </p>
            </div>

    <?php
        }
    }
}


add_action('admin_init', 'lpm_nag_ignore');
function lpm_nag_ignore()
{


    global $current_user;
    $user_id = $current_user->ID;

    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET['lpm_nag_ignore']) && '0' == $_GET['lpm_nag_ignore']) {

        add_user_meta($user_id, 'panorama_ignore_notice_new', 'true', true);
    }
}

add_action('add_meta_boxes', 'psppan_add_promotional_metabox');
function psppan_add_promotional_metabox()
{

    global $current_user;
    $user_id = $current_user->ID;

    if (!get_user_meta($user_id, 'panorama_ignore_notice_new')) {
        add_meta_box('psp_lite_promotional_sidebar', __('Project Panorama', 'psp_projects'), 'psppan_promotional_metabox', 'psp_projects', 'side');
    }
}

function psppan_promotional_metabox()
{ ?>

    <p><img src="<?php echo esc_url(PROJECT_PANORAMA_URI); ?>/assets/images/panorama-logo.png" alt="Project Panorama" height="50"></p>
    <p>Like Project Panorama? <a href="https://www.projectpanorama.com/?discount=litemeup" target="_new">Did you know we have a full featured premium version?</a>. <strong>Use coupon code <code>litemeup</code> to save 20%.</strong></p>

    <p><strong>Features include:</strong></p>

    <ul style="list-style:disc;padding-left: 15px;">
        <li>Front end task completion</li>
        <li>Assign tasks to users</li>
        <li>More sophisticated design</li>
        <li>Teams</li>
        <li>Automatic notifications</li>
        <li>And more!</li>
    </ul>

    <?php
    $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
    $close_url = add_query_arg('lpm_nag_ignore', '0', psppan_full_url($request_uri));
    ?>

    <p><a href="<?php echo esc_url($close_url); ?>">No thanks!</a></p>

<?php }

function psppan_url_origin($s, $use_forwarded_host = false)
{
    $ssl      = (! empty($s['HTTPS']) && $s['HTTPS'] == 'on');
    $sp       = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port     = $s['SERVER_PORT'];
    $port     = ((! $ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function psppan_full_url($s, $use_forwarded_host = false)
{
    return psppan_url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
