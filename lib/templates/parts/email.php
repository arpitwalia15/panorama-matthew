<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Fallback defaults to prevent "undefined variable" errors
$logo     = isset($logo) ? $logo : '';
$user     = isset($user) ? $user : (object) ['first_name' => ''];
$message  = isset($message) ? $message : '';
$progress = isset($progress) ? (int) $progress : 0;
$post_id  = isset($post_id) ? (int) $post_id : 0;
?>

<div style="background:#f1f2f7; padding: 30px;">
    <div style="background:#fff; padding: 4%; border-radius: 12px; font-family: 'Arial','Helvetica','San-Serif'; width: 92%; max-width: 640px; margin: 0 auto;">

        <?php if (! empty($logo)) : ?>
            <img src="<?php echo esc_url($logo); ?>" style="display: block; max-width: 200px; text-align: center; height: auto; margin: 0 auto 30px auto;" alt="">
        <?php endif; ?>

        <?php if (! empty($user->first_name)) : ?>
            <p><?php esc_html_e('Hello', 'psp_projects'); ?> <?php echo esc_html($user->first_name); ?></p>
        <?php endif; ?>

        <?php if (! empty($message)) : ?>
            <p><?php echo wp_kses_post(wpautop($message)); ?></p>
        <?php endif; ?>

        <?php if ($progress > 0) : ?>
            <p style="text-align: center; text-transform: uppercase; font-size: 12px; color: #444; font-weight: bold; margin-top: 40px;"><?php esc_html_e('Current Status', 'psp_projects'); ?></p>
            <div style="background: #f1f1f1; margin: 20px 0; height: 30px;">
                <?php if ($progress >= 10) : ?>
                    <div style="height: 30px; width: <?php echo esc_attr($progress); ?>%; background: #3299bb;">
                        <div style="color: #fff; line-height: 30px; text-align: right; padding-right: 10px;"><?php echo esc_html($progress); ?>%</div>
                    </div>
                <?php else : ?>
                    <div style="height: 30px; width: <?php echo esc_attr($progress); ?>%; background: #3299bb; display: inline-block;"></div>
                    <div style="color: #666; display: inline-block; margin-left: 10px; font-weight: bold;"><?php echo esc_html($progress); ?>%</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <p style="padding-top: 30px; margin-top: 30px; border-top: 1px solid #efefef; text-align: center;">
            <a href="<?php echo esc_url(get_permalink($post_id)); ?>" style="font-weight: bold; color: #0074a2; text-align: center; padding: 10px 25px; border: 1px solid #0074a2; border-radius: 3px; display: inline-block; text-decoration: none;">
                <?php esc_html_e('Click here to view.', 'psp_projects'); ?>
            </a>
        </p>

    </div>
</div>