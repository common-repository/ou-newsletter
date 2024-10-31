<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, 'ojjijioh'))
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
	
	$ounewsnetternltablenl1 = $wpdb->prefix . "onnewsletters";
	$ounletterviewa1 = $wpdb->get_results( "SELECT * FROM $ounewsnetternltablenl1 where  onnewsletters_id = $ouidnl ");
	foreach ($ounletterviewa1 as $ounletterviewa2)
	{ 
		$onnewsletters_id = $ounletterviewa2->onnewsletters_id; 
		$onnewsletters_name = $ounletterviewa2->onnewsletters_name; 
		$onnewsletters_subject = $ounletterviewa2->onnewsletters_subject; 
		$onnewsletters_email1 = $ounletterviewa2->onnewsletters_email; 
		$onnewsletters_date = $ounletterviewa2->onnewsletters_date; 
		$onnewsletters_email = stripslashes_deep($onnewsletters_email1);	
		$ounewsnetterusertable1 = $wpdb->prefix . "onnewsletterusers";
		$ounewsletteruserresult = $wpdb->get_var( "SELECT COUNT(*) FROM $ounewsnetterusertable1" );
		
		if($ounewsletteruserresult >=1)
		{
			$ouarrayemail = [];
			$ouadmin_email = get_option('admin_email');
			$ounluser1 = $wpdb->get_results( "SELECT * FROM $ounewsnetterusertable1 ");
			foreach ($ounluser1 as $ounluser2)
			{ 
					$ouarrayemail2 = $ounluser2->onnewsletterusers_email; 
					$ounlheaders[] = 'From: '.get_option('blogname').' <'.$ouadmin_email.'>';
					$ounlheaders[] = 'content-type: text/html';
					wp_mail( $ouarrayemail2, $onnewsletters_subject, $onnewsletters_email, $ounlheaders );
			}
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		}
		
	}
	
}