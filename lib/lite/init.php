<?php

    require_once('lite_shortcodes.php');
    require_once('notices.php');

    add_action('init', 'psppan_load_custom_fields');
    function psppan_load_custom_fields() {
        // Load the custom fields
        require_once('lite_fields.php');
    }

?>