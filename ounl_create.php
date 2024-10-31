<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, 'cirgb'))
{
    wp_die();
}

if ( current_user_can('manage_options') )
{
	$onnewslettercreatenewsletter_name =  sanitize_text_field($_POST['onnewslettercreatenewsletter_name']);
	$onnewslettercreatenewsletter_subject =  sanitize_text_field($_POST['onnewslettercreatenewsletter_subject']);
	$onnewslettercreatenewsletter_message = wp_unslash($_POST['onnewslettercreatenewsletter_message']);
	global $wpdb;
	$outable = $wpdb->prefix . "onnewsletters";
	if(!empty($onnewslettercreatenewsletter_name) && !empty($onnewslettercreatenewsletter_subject) && !empty($onnewslettercreatenewsletter_message))
	{
		$onnewslettercreatenewsletter_message1 = wp_kses_allowed_html('post');
		$onnewslettercreatenewsletter_message2 = wp_kses($onnewslettercreatenewsletter_message, $onnewslettercreatenewsletter_message1);
		$onnewslettercreatenewsletter_message3 = wp_slash($onnewslettercreatenewsletter_message2);

		$ounlcurrenttime = current_time('d m Y H:i');
		$wpdb->insert( $outable, array( 'onnewsletters_date' => $ounlcurrenttime, 'onnewsletters_email' => $onnewslettercreatenewsletter_message3, 'onnewsletters_subject' => $onnewslettercreatenewsletter_subject, 'onnewsletters_name' => $onnewslettercreatenewsletter_name ) );
	}
}
?>