<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, 'u9u097g'))
{
    wp_die();
}

if ( current_user_can('manage_options') )
{
	$ouiduser = intval($_POST['iduser']);

    if(!$ouiduser)
    {
        wp_die();
    }
	
	global $wpdb;
	$ouusernewslettert2 = $wpdb->prefix . "onnewsletterusers";
	$wpdb->delete($ouusernewslettert2, array( 'onnewsletterusers_id' => $ouiduser ) );
}
?>
?>