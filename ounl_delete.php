<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, 'yfutfutff'))
{
    wp_die();
}

if ( current_user_can('manage_options') )
{
	$ouidnl = intval($_POST['idnl']);

    if(!$ouidnl)
    {
        wp_die();
    }
	
	global $wpdb;
	$ounwslettert2 = $wpdb->prefix . "onnewsletters";
	$wpdb->delete($ounwslettert2, array( 'onnewsletters_id' => $ouidnl ) );
}
?>