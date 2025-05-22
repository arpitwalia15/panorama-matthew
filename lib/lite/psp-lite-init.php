<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$requires = array(
    'psp-lite-shortcodes.php',
    'psp-lite-functions.php',
    'psp-notices.php',
);
foreach( $requires as $require ) require_once($require);

add_action( 'init', 'psppan_load_custom_fields' );
function psppan_load_custom_fields() {

    // Load the custom fields
    require_once( 'psp-lite-fields.php' );

} ?>
