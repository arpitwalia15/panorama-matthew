<?php





/************************************

* the code below is just a standard

* options page. Substitute with

* your own.

*************************************/



function eddpan_panorama_license_menu() {

	add_submenu_page( 'edit.php?post_type=psp_projects','Project Panorama Settings', 'Settings', 'manage_options', 'panorama-license', 'eddpan_panorama_license_page' );

}

add_action('admin_menu', 'eddpan_panorama_license_menu');



function eddpan_panorama_license_page() {

	$license 	= get_option( 'eddpan_panorama_license_key' );

	$status 	= get_option( 'eddpan_panorama_license_status' );

	?>

	<div class="wrap">

		<h2><?php esc_html_e('Project Panorama License Options','psp_projects'); ?></h2>



        <?php if($_GET['settings-updated'] == 'true') {



            flush_rewrite_rules(); ?>



            <div class="updated">

                <p>The Project Panorama settings have been updated.</p>

            </div>



        <?php } ?>



		<form method="post" action="options.php">



			<?php settings_fields('eddpan_panorama_license'); ?>



        <?php if(PSP_PLUGIN_TYPE == 'professional'): ?>

        <table class="form-table">

				<tbody>

					<tr valign="top">

						<th scope="row" valign="top">

							<?php esc_html_e('License Key','psp_projects'); ?>

						</th>

						<td>

							<input id="eddpan_panorama_license_key" name="eddpan_panorama_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />

							<label class="description" for="eddpan_panorama_license_key"><?php esc_html_e('Enter your license key','psp_projects'); ?></label>

						</td>

					</tr>

					<?php if( false !== $license ) { ?>

						<tr valign="top">

							<th scope="row" valign="top">

								<?php esc_html_e('Activate License','psp_projects'); ?>

							</th>

							<td>

								<?php if( $status !== false && $status == 'valid' ) { ?>

									<span style="color:green;" class="psp-activation-notice"><?php esc_html_e('Active','psp_projects'); ?></span>

									<?php wp_nonce_field( 'edd_panorama_nonce', 'edd_panorama_nonce' ); ?>

									<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php esc_html_e('Deactivate License','psp_projects'); ?>"/>

								<?php } else { ?>

                                    <span style="color:red;" class="psp-activation-notice"><?php esc_html_e('Inactive','psp_projects'); ?></span>

									<?php wp_nonce_field( 'edd_panorama_nonce', 'edd_panorama_nonce' ); ?>

									<input type="submit" class="button-secondary" name="edd_license_activate" value="<?php esc_html_e('Activate License','psp_projects'); ?>"/>

								<?php } ?>

							</td>

						</tr>

					<?php } ?>

			</table>

			<?php endif; ?>



				<table class="form-table">

					<tr>

						<td colspan="2"><hr></td>

					</tr>

					<tr>

						<th scope="row" valign="top">

							<label for="psppan_slug"><?php esc_html_e('Project Slug','psp_projects'); ?></label>

						</th>

						<td>

							<input id="psppan_slug" value="<?php echo esc_attr(get_option('psppan_slug','panorama')); ?>" type="text" name="psppan_slug">

						</td>

					</tr>

					<tr>

						<th scope="row" valign="top">

							<label for="psppan_logo"><?php esc_html_e('Logo for Project Pages','psp_projects'); ?></label>

						</th>

						<td>

						    <input id="psppan_logo" type="text" size="36" name="psppan_logo" value="<?php echo esc_url(get_option('psppan_logo','http://')); ?>" />

						    <input id="psp_upload_image_button" class="button" type="button" value="Upload Image" />

						</td>

					</tr>

					<tr>

						<td colspan="2">

							<hr><br>

							<p><em>If you're having trouble with WordPress SEO sitemaps.xml or getting 404 errors on other pages, try checking this option and <a href="options-permalink.php">resaving your permalinks</a>.</em></p>

						</td>

					</tr>

					<tr>

						<th scope="row" valign="top">

							<label for="psppan_flush_rewrites"><?php esc_html_e('Disable Flush Rewrites','psp_projects'); ?></label>

						</th>

						<td>

							<input id="psppan_flush_rewrites" type="checkbox" name="psppan_flush_rewrites" <?php if(get_option('psppan_flush_rewrites') == 'on') { echo 'checked'; }?>>

						</td>

					</tr>

				</tbody>

			</table>

			<?php submit_button(); ?>



		</form>

	<?php

}



function eddpan_panorama_register_option() {

    // Register License Key with Sanitization

    register_setting('eddpan_panorama_license', 'eddpan_panorama_license_key', 'eddpan_sanitize_license');



    // Additional Options with Proper Sanitization

    register_setting('eddpan_panorama_license', 'psppan_slug', 'sanitize_text_field');

    register_setting('eddpan_panorama_license', 'psppan_logo', 'esc_url_raw');

    register_setting('eddpan_panorama_license', 'psppan_flush_rewrites', 'psppan_sanitize_boolean');



    // Set Default Values

    add_option('psppan_slug', 'panorama');

    add_option('psppan_logo', '');

    add_option('psppan_flush_rewrites', '0'); // Default to '0' (false)

}

add_action('admin_init', 'eddpan_panorama_register_option');

// Sanitize Boolean Values (Checkboxes/Toggles)

function psppan_sanitize_boolean($input) {

    return filter_var($input, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';

}



function eddpan_sanitize_license( $new ) {

	$old = get_option( 'eddpan_panorama_license_key' );

	if( $old && $old != $new ) {

		delete_option( 'eddpan_panorama_license_status' ); // new license has been entered, so must reactivate

	}

	return $new;

}







/************************************

* this illustrates how to activate

* a license key

*************************************/



function eddpan_panorama_activate_license() {



	// listen for our activate button to be clicked

	if( isset( $_POST['edd_license_activate'] ) ) {



		// run a quick security check

	 	if( ! check_admin_referer( 'edd_panorama_nonce', 'edd_panorama_nonce' ) )

			return; // get out if we didn't click the Activate button



		// retrieve the license from the database

		$license = trim( get_option( 'eddpan_panorama_license_key' ) );





		// data to send in our API request

		$api_params = array(

			'edd_action'=> 'activate_license',

			'license' 	=> $license,

			'item_name' => urlencode( EDD_PROJECT_PANORAMA ) // the name of our product in EDD

		);



		// Call the custom API.

		$response = wp_remote_get( add_query_arg( $api_params, PROJECT_PANORAMA_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );



		// make sure the response came back okay

		if ( is_wp_error( $response ) )

			return false;



		// decode the license data

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );



		// $license_data->license will be either "active" or "inactive"



		update_option( 'eddpan_panorama_license_status', $license_data->license );



	}







}

add_action('admin_init', 'eddpan_panorama_activate_license',0);





/***********************************************

* Illustrates how to deactivate a license key.

* This will descrease the site count

***********************************************/



function eddpan_panorama_deactivate_license() {



	// listen for our activate button to be clicked

	if( isset( $_POST['edd_license_deactivate'] ) ) {



		// run a quick security check

	 	if( ! check_admin_referer( 'edd_panorama_nonce', 'edd_panorama_nonce' ) )

			return; // get out if we didn't click the Activate button



		// retrieve the license from the database

		$license = trim( get_option( 'eddpan_panorama_license_key' ) );





		// data to send in our API request

		$api_params = array(

			'edd_action'=> 'deactivate_license',

			'license' 	=> $license,

			'item_name' => urlencode( EDD_PROJECT_PANORAMA ) // the name of our product in EDD

		);



		// Call the custom API.

		$response = wp_remote_get( add_query_arg( $api_params, PROJECT_PANORAMA_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );



		// make sure the response came back okay

		if ( is_wp_error( $response ) )

			return false;



		// decode the license data

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );



		// $license_data->license will be either "deactivated" or "failed"

		if( $license_data->license == 'deactivated' )

			delete_option( 'eddpan_panorama_license_status' );



	}

}

add_action('admin_init', 'eddpan_panorama_deactivate_license');





/************************************

* this illustrates how to check if

* a license key is still valid

* the updater does this for you,

* so this is only needed if you

* want to do something custom

*************************************/



function eddpan_panorama_check_license() {



	global $wp_version;



	$license = trim( get_option( 'eddpan_panorama_license_key' ) );



	$api_params = array(

		'edd_action' => 'check_license',

		'license' => $license,

		'item_name' => urlencode( EDD_PROJECT_PANORAMA )

	);



	// Call the custom API.

	$response = wp_remote_get( add_query_arg( $api_params, PROJECT_PANORAMA_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );



	if ( is_wp_error( $response ) )

		return false;



	$license_data = json_decode( wp_remote_retrieve_body( $response ) );



	if( $license_data->license == 'valid' ) {

		echo 'valid'; exit;

		// this license is still valid

	} else {

		echo 'invalid'; exit;

		// this license is no longer valid

	}

}

