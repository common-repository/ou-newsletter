<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, 'btyuiyv'))
{
    wp_die();
}

if ( current_user_can('manage_options') )
{
	$onnewslettera_add_user_firstname =  sanitize_text_field($_POST['onnewslettera_add_user_firstname']);
	$onnewslettera_add_user_lastname =  sanitize_text_field($_POST['onnewslettera_add_user_lastname']);
	$onnewslettera_add_email = sanitize_email($_POST['onnewslettera_add_user_email']);
	
	global $wpdb;
	
	if(!empty($onnewslettera_add_user_firstname) && !empty($onnewslettera_add_user_lastname) && !empty($onnewslettera_add_email))
	{
		$ouusernewslettert1 = $wpdb->prefix . "onnewsletterusers";
		$ounlcurrenttime = current_time('d m Y H:i');
		$wpdb->insert( $ouusernewslettert1, array( 'onnewsletterusers_date' => $ounlcurrenttime, 'onnewsletterusers_last_name' => $onnewslettera_add_user_lastname, 'onnewsletterusers_email' => $onnewslettera_add_email, 'onnewsletterusers_first_name' => $onnewslettera_add_user_firstname  ) );
	}
}
else
{
	wp_die();
}

?>