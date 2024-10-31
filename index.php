<?php
/*
Plugin Name: OU Newsletter
Plugin URI: http://oleksandrustymenko.com/ounewsletter
Description: A simple plugin for creating and sending newsletters. Support for HTML tags. Simply enter the [newsletter] shortcode in a post or page.
Version: 1.0
Author: Oleksandr Ustymenko
Author URI: http://oleksandrustymenko.com
*/

/*  
	Copyright 2016 oleksandr87 (email:ustymenkooleksandrnew@gmail.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if(!defined('ABSPATH')) exit;
global $jal_db_version;
$jal_db_version = "1.0";

function onnewsletter_db_activ() 
{
	global $wpdb;
	global $jal_db_version;
    $ounewslettertablecreate2 = $wpdb->prefix . "onnewsletterusers";
	if($wpdb->get_var("show tables like '$ounewslettertablecreate2'") != $ounewslettertablecreate2)
	{     
        $sql = "CREATE TABLE " .$ounewslettertablecreate2. " (
		onnewsletterusers_id INTEGER NOT NULL AUTO_INCREMENT,
		onnewsletterusers_email TEXT,
		onnewsletterusers_first_name TEXT,
		onnewsletterusers_last_name TEXT,
        onnewsletterusers_date TEXT,
		UNIQUE KEY  (onnewsletterusers_id));"; 
	  
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option("jal_db_version", $jal_db_version);  
	}
	
	$ounewslettertablecreate3 = $wpdb->prefix . "onnewsletters";
	if($wpdb->get_var("show tables like '$ounewslettertablecreate3'") != $ounewslettertablecreate3)
	{     
        $sql = "CREATE TABLE " .$ounewslettertablecreate3. " (
		onnewsletters_id INTEGER NOT NULL AUTO_INCREMENT,
		onnewsletters_name TEXT,
		onnewsletters_subject TEXT,
		onnewsletters_email TEXT,
        onnewsletters_date TEXT,
		UNIQUE KEY  (onnewsletters_id));"; 
	  
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option("jal_db_version", $jal_db_version);  
	}
	
}
register_activation_hook(__FILE__,'onnewsletter_db_activ');

function ounewsletterdeactivate()
{
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}onnewsletters");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}onnewsletterusers");
}
register_uninstall_hook(__FILE__, 'ounewsletterdeactivate');


function ounewsletter_file()
{
	wp_enqueue_script( 'jquery');
	wp_localize_script( 'jquery', 'oulewsletterjaxcode', 
	array(
   'oucode_url'   => admin_url('admin-ajax.php'),
   'oucode_nonce' => wp_create_nonce('ounewslettercreatenonce')
	));

}

add_action('wp_enqueue_scripts', 'ounewsletter_file');


add_action('admin_menu', 'ounewsletter_setup_menu');
 
function ounewsletter_setup_menu()
{
        add_menu_page( 'Newsletter', 'Newsletter', 'manage_options', 'newsletter-admin', 'ounewsletter_init' );
}
 
 function ounlcreateuserfunc()
 {
	require_once( plugin_dir_path(__FILE__).'oucreate_useradmin.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounlcreateuser', 'ounlcreateuserfunc');
 
 function oucreateusernoadmin2()
{
	require_once( plugin_dir_path(__FILE__).'oucreate_user.php');
	exit;
}

add_action( 'wp_ajax_nopriv_oucreateusernoadmin2', 'oucreateusernoadmin2');
add_action( 'wp_ajax_oucreateusernoadmin2', 'oucreateusernoadmin2');
 
 
 function ounldeleteuserfunc()
 {
	require_once( plugin_dir_path(__FILE__).'oudelete_useradmin.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounldeleteuser', 'ounldeleteuserfunc');
 
 
function ounldisplayusersfunc()
 {
	require_once( plugin_dir_path(__FILE__).'ouall_useradmin.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounldisplayusers', 'ounldisplayusersfunc');
 
function ounldisplayuserssearchfunc()
 {
	require_once( plugin_dir_path(__FILE__).'ousearch_useradmin.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounldisplayuserssearch', 'ounldisplayuserssearchfunc');
 
function ounewsletcreatefunc()
 {
	require_once( plugin_dir_path(__FILE__).'ounl_create.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounewsletcreate', 'ounewsletcreatefunc');
 
function ounldeletenlfunc()
 {
	require_once( plugin_dir_path(__FILE__).'ounl_delete.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounldeletenl', 'ounldeletenlfunc');
 
 function ounlssendnlfunc()
 {
	require_once( plugin_dir_path(__FILE__).'ounl_send.php');
	exit;
 }
 
 add_action( 'wp_ajax_ounlssendnl', 'ounlssendnlfunc');
 
function ounewsletter_init()
{
	global $wpdb;
	?>
	<style>
	table 
	{
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 580px;
		font-size:12px;
	}
	td, th
	{
		border: 1px solid #0059b3;
		text-align: left;
		padding: 8px;
	}
	</style>
	
	<script>
	function ounl_add_user()
	{
		 jQuery("#ounewsletter_user_zero").hide();
		 jQuery("#ounewsletter_displayvv").hide();
		 jQuery("#ounewsletter_search_user").hide();
		 jQuery("#ounewsletter_display_user").hide();
		 jQuery("#ounewsletter_add_user").show();
	}
	</script>
	
	<script>
	function oucreateuser_cancel()
	{
		var formData = new FormData(jQuery('#ou_nlformallusers')[0]);
		formData.append('action', 'ounldisplayusers');
		formData.append('nonce', '<?php echo wp_create_nonce('7uh9779h');?>');
		jQuery.ajax({
		type: "post",
		url: "admin-ajax.php",
		data: formData,
		contentType:false,
		processData:false,
		beforeSend: function() 
		{
			jQuery("#ounewsletter_user_zero").hide();
			jQuery("#ounewsletter_displayvv").hide();
			jQuery("#ounewsletter_search_user").hide();
			jQuery("#ounewsletter_add_user").hide();
			jQuery("#ounewsletter_display_user").hide();
		},
		success: function(html)
		{
			jQuery("#ounewsletter_display_user").empty();
			jQuery("#ounewsletter_display_user").append(html);
			jQuery("#ounewsletter_display_user").show();
		}
		});
	}
	</script>
	
	<script>
	function ousearchuser_cancel()
	{
		var formData = new FormData(jQuery('#ou_nlformallusers')[0]);
		formData.append('action', 'ounldisplayusers');
		formData.append('nonce', '<?php echo wp_create_nonce('7uh9779h');?>');
		jQuery.ajax({
		type: "post",
		url: "admin-ajax.php",
		data: formData,
		contentType:false,
		processData:false,
		beforeSend: function() 
		{
			jQuery("#ounewsletter_user_zero").hide();
			jQuery("#ounewsletter_displayvv").hide();
			jQuery("#ounewsletter_search_user").hide();
			jQuery("#ounewsletter_add_user").hide();
			jQuery("#ounewsletter_display_user").hide();
		},
		success: function(html)
		{
			jQuery("#ounewsletter_display_user").empty();
			jQuery("#ounewsletter_display_user").append(html);
			jQuery("#ounewsletter_display_user").show();
		}
		});
	}
	</script>
	
	<script>
	function oucreateuser_create()
	{
		var oufirstname = jQuery('#onnewsletter_add_user_firstname').val().length;
		var oulastname = jQuery('#onnewsletter_add_user_lastname').val().length;
		var ouemail = jQuery('#onnewsletter_add_user_email').val();
		
		jQuery('#onnewsletter_add_user_email_label').css('color','#000000');
		jQuery('#onnewsletter_add_user_email_label').hide();
		jQuery('#onnewsletter_add_user_firstname_label').css('color','#000000');
		jQuery('#onnewsletter_add_user_firstname_label').hide();
		jQuery('#onnewsletter_add_user_lastname_label').css('color','#000000');
		jQuery('#onnewsletter_add_user_lastname_label').hide();
		
		var re2 = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;
		if (re2.test(ouemail)) 
		{
			 if(oufirstname >=1)
			 {
				if(oulastname >=1)
				{
					var formData = new FormData(jQuery('#ou_nlform1')[0]);
					formData.append('action', 'ounlcreateuser');
					formData.append('nonce', '<?php echo wp_create_nonce('btyuiyv');?>');
					jQuery.ajax({
					type: "post",
					url: "admin-ajax.php",
					data: formData,
					contentType:false,
					processData:false,
					beforeSend: function() 
					{
						jQuery("#onnewslettercreateusercu").hide();
						jQuery("#onnewslettercreateuserpleasewait").show();
					},
					success: function(html)
					{
						location.reload();
					}
					});
				}
				else
				{
					jQuery('#onnewsletter_add_user_lastname_label').css('color','#990000');
					jQuery('#onnewsletter_add_user_lastname_label').show();
				}
			 }
			 else
			 {
				jQuery('#onnewsletter_add_user_firstname_label').css('color','#990000');
				jQuery('#onnewsletter_add_user_firstname_label').show();
				if(oulastname ==0)
				{
					jQuery('#onnewsletter_add_user_lastname_label').css('color','#990000');
					jQuery('#onnewsletter_add_user_lastname_label').show();
				}
			 }
		}
		else
		{
			 jQuery('#onnewsletter_add_user_email_label').css('color','#990000');
			 jQuery('#onnewsletter_add_user_email_label').show();
			 
			 if(oufirstname ==0)
			 {
				jQuery('#onnewsletter_add_user_firstname_label').css('color','#990000');
				jQuery('#onnewsletter_add_user_firstname_label').show();
			 }
			 if(oulastname ==0)
			 {
				jQuery('#onnewsletter_add_user_lastname_label').css('color','#990000');
				jQuery('#onnewsletter_add_user_lastname_label').show();
			 }
		}
		
	}
	</script>
	
	<script>
	function ounl_create_newsletter()
	{
		jQuery('#ounewsletterpir1').hide();
		jQuery('#ounewsletterpir2').hide();
		jQuery('#ounewsletter_newsletter_allresults').hide();
		jQuery('#ounewsletter_create_newsletter').show();
	}
	
	</script>
	
	<script>
	function onnewsletter_createnewsletterbutton2crnl()
	{
		var ouname = jQuery('#onnewsletter_createnewsletter_name').val().length;
		var ousubject = jQuery('#onnewsletter_createnewsletter_subject').val().length;
		var oumessage = jQuery('#onnewsletter_createnewsletter_message').val().length;
		
		jQuery('#onnewsletter_createnewslettername_label').css('color','#000000');
		jQuery('#onnewsletter_createnewslettername_label').hide();
		jQuery('#onnewsletter_createnewslettersubject_label').css('color','#000000');
		jQuery('#onnewsletter_createnewslettersubject_label').hide();
		jQuery('#onnewsletter_createnewslettermessage_label').css('color','#000000');
		jQuery('#onnewsletter_createnewslettermessage_label').hide();
		
		if(ouname >=1)
		{
			if(ousubject >=1)
			{
				if(oumessage >=1)
				{
					var formData = new FormData(jQuery('#ou_nlformcreate_newsletter')[0]);
					formData.append('action', 'ounewsletcreate');
					formData.append('nonce', '<?php echo wp_create_nonce('cirgb');?>');
					jQuery.ajax({
					type: "post",
					url: "admin-ajax.php",
					data: formData,
					contentType:false,
					processData:false,
					beforeSend: function() 
					{
						jQuery("#onnewsletter_createnewsletterbutton").hide();
						jQuery("#onnewsletter_createnewsletterpleasewait").show();
					},
					success: function(html)
					{
						location.reload();
					}
					});
				}
				else
				{
					jQuery('#onnewsletter_createnewslettermessage_label').css('color','#990000');
					jQuery('#onnewsletter_createnewslettermessage_label').show();
				}
			}
			else
			{
				jQuery('#onnewsletter_createnewslettersubject_label').css('color','#990000');
				jQuery('#onnewsletter_createnewslettersubject_label').show();
				
				if(oumessage ==0)
				{
					jQuery('#onnewsletter_createnewslettermessage_label').css('color','#990000');
					jQuery('#onnewsletter_createnewslettermessage_label').show();
				}
			}
			
		}
		else
		{
			jQuery('#onnewsletter_createnewslettername_label').css('color','#990000');
			jQuery('#onnewsletter_createnewslettername_label').show();
			
			if(ousubject ==0)
			{
				jQuery('#onnewsletter_createnewslettersubject_label').css('color','#990000');
				jQuery('#onnewsletter_createnewslettersubject_label').show();
			}
			if(oumessage ==0)
			{
				jQuery('#onnewsletter_createnewslettermessage_label').css('color','#990000');
				jQuery('#onnewsletter_createnewslettermessage_label').show();
			}
		}
		
	}
	</script>
	
	<div style="margin:10px; width:600px; border: 1px solid #0059b3; background: #ffffff; min-height: 60px;">
		<div style="background:#0059b3; width:600px;">
			<div style="padding:10px; font-size:18px; color: #ffffff;">
				<b><?php echo esc_html("Newsletter");?></b>
			</div>
		</div>
		
		<div style="margin:10px; width:580px; min-height:40px;">
			<div style="font-size:14px; color:#000000; text-align:right;">
				<button class="button button-primary" onclick="ounl_create_newsletter(); return false;"><?php echo esc_html("Create Newsletter");?></button>
			</div>
			
			<?php
			$ounewsnetternltablenl1 = $wpdb->prefix . "onnewsletters";
			$ounewsnetternltablenl1result = $wpdb->get_var( "SELECT COUNT(*) FROM $ounewsnetternltablenl1" );
		
			if($ounewsnetternltablenl1result ==0)
			{
				?>
				<div id="ounewsletterpir1" style="color: #000000; font-size:27px; text-align:center; padding: 80px 0px;">
					<b><?php echo esc_html("You have 0 newsletters");?></b>
				</div>
				<?php
			}
			if($ounewsnetternltablenl1result >=1)
			{
				echo '<div id="ounewsletterpir2" style="color: #000000; padding:10px 0px 5px 0px;">';
					echo '<b>'.esc_html("Last 20 newsletters").'</b>';
					echo '<table>';
				
						echo '<tr>';
							echo '<th>'.esc_html("Name").'</th>';
							echo '<th>'.esc_html("Subject").'</th>';
							echo '<th>'.esc_html("Date").'</th>';
							echo '<th>'.esc_html("Send").'</th>';
							echo '<th>'.esc_html("X").'</th>';
						echo '</tr>';
						
						$ounletterviewa1 = $wpdb->get_results( "SELECT * FROM $ounewsnetternltablenl1 where onnewsletters_subject !='' ORDER BY onnewsletters_date DESC LIMIT 20 ");
						foreach ($ounletterviewa1 as $ounletterviewa2)
						{ 
							$onnewsletters_id = $ounletterviewa2->onnewsletters_id; 
							$onnewsletters_name = $ounletterviewa2->onnewsletters_name; 
							$onnewsletters_subject = $ounletterviewa2->onnewsletters_subject; 
							$onnewsletters_email = $ounletterviewa2->onnewsletters_email; 
							$onnewsletters_date = $ounletterviewa2->onnewsletters_date; 
							?>
							<script>
							function ounletterdeldelete<?php echo $onnewsletters_id;?>()
							{
								var formData = new FormData(jQuery('#ou_nlformdeletenewsletter<?php echo $onnewsletters_id;?>')[0]);
								formData.append('action', 'ounldeletenl');
								formData.append('nonce', '<?php echo wp_create_nonce('yfutfutff');?>');
								formData.append('idnl', '<?php echo $onnewsletters_id;?>');
								jQuery.ajax({
								type: "post",
								url: "admin-ajax.php",
								data: formData,
								contentType:false,
								processData:false,
								success: function(html)
								{
									jQuery('#ounewsletterdeiddelete<?php echo $onnewsletters_id;?>').hide();
								}
								});
							}
							</script>
							
							<script>
							function ounlettersend<?php echo $onnewsletters_id;?>()
							{
								var formData = new FormData(jQuery('#ou_nlformsendnewsletter<?php echo $onnewsletters_id;?>')[0]);
								formData.append('action', 'ounlssendnl');
								formData.append('nonce', '<?php echo wp_create_nonce('ojjijioh');?>');
								formData.append('idnl', '<?php echo $onnewsletters_id;?>');
								jQuery.ajax({
								type: "post",
								url: "admin-ajax.php",
								data: formData,
								contentType:false,
								processData:false,
								beforeSend: function() 
								{
									jQuery('#ou_nflsda<?php echo $onnewsletters_id;?>').hide();
									jQuery('#ou_nflsdb<?php echo $onnewsletters_id;?>').show();
								},
								success: function(html)
								{
									jQuery('#ou_nflsdb<?php echo $onnewsletters_id;?>').hide();
									jQuery('#ou_nflsda<?php echo $onnewsletters_id;?>').show();
								}
								});
							}
							</script>
							
							<?php
							echo '<tr id="ounewsletterdeiddelete'.$onnewsletters_id.'">';
								echo '<td style="min-width:143px;">'.esc_html($onnewsletters_name).'</td>';
								echo '<td style="min-width:162px;">'.esc_html($onnewsletters_subject).'</td>';
								echo '<td style="min-width:100px;">'.esc_html($onnewsletters_date).'</td>';
								echo '<td style="width:50px; text-align:left;">';
									echo '<form id="ou_nlformsendnewsletter'.$onnewsletters_id.'" enctype="multipart/form-data"  method="POST">';
										echo '<span id="ou_nflsda'.$onnewsletters_id.'" >';
											echo '<a href="" onclick="ounlettersend'.$onnewsletters_id.'(); return false;" style="font-size:12px;">'.esc_html("Send").'</a>';
										echo '</span>';
										echo '<span id="ou_nflsdb'.$onnewsletters_id.'" style="font-size:12px; display:none;">';
											echo esc_html("Please wait!");
										echo '</span>';
									echo '</form>';
								echo '</td>';
								echo '<td style="width:10px; text-align:center;">';
									echo '<form id="ou_nlformdeletenewsletter'.$onnewsletters_id.'" enctype="multipart/form-data"  method="POST">';
										echo '<a href="" onclick="ounletterdeldelete'.$onnewsletters_id.'(); return false;" style="font-size:12px;">'.esc_html("X").'</a>';
									echo '</form>';
								echo '</td>';
							echo '</tr>';
							
						}
						
					echo '</table>';
				echo '</div>';
			}
			?>
			<div id="ounewsletter_newsletter_allresults" style="display:none; color: #000000; padding:10px 0px 5px 0px;"></div>
			<script>
			function onnewsletter_createnewsletter_cancel()
			{
				location.reload();
			}
			</script>
			<div id="ounewsletter_create_newsletter" style="display:none; color: #000000; padding:10px 0px 5px 0px;">
				<form id="ou_nlformcreate_newsletter" enctype="multipart/form-data"  method="POST">
					<div style="font-size:18px;">
						<b><?php echo esc_html("Create Newsletter");?></b>
					</div>
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Name");?></b> <span id="onnewsletter_createnewslettername_label" style="display:none;"><?php echo esc_html("Please enter a  name");?></span>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_createnewsletter_name" autocomplete="off" placeholder="<?php echo esc_html('Name');?>" style="font-size:14px; width:100%;" name="onnewslettercreatenewsletter_name">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Subject");?></b> <span id="onnewsletter_createnewslettersubject_label" style="display:none;"><?php echo esc_html("Please enter a  subject");?></span>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_createnewsletter_subject" autocomplete="off" placeholder="<?php echo esc_html('Subject');?>" style="font-size:14px; width:100%;" name="onnewslettercreatenewsletter_subject">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Message");?></b> <span id="onnewsletter_createnewslettermessage_label" style="display:none;"><?php echo esc_html("Please enter a  message");?></span>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<textarea type="text" id="onnewsletter_createnewsletter_message" autocomplete="off" placeholder="<?php echo esc_html('Message (Support for HTML tags)');?>" style="font-size:14px; height: 120px; resize:none; width:100%;" name="onnewslettercreatenewsletter_message"></textarea>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 5px 0px; ">
						<span id="onnewsletter_createnewsletterbutton"><button  onclick="onnewsletter_createnewsletterbutton2crnl(); return false;" class="button button-primary"><?php echo esc_html("Create Newsletter");?></button></span>
						<span id="onnewsletter_createnewsletterpleasewait" style="display:none;"><b><?php echo esc_html("Please wait...");?></b></span>
						<button onclick="onnewsletter_createnewsletter_cancel(); return false;" class="button button-primary"><?php echo esc_html("Cancel");?></button>
					</div>
					
				</form>
			</div>
		
		</div>
		
		
		
		
	</div>
	
	<script>
	function ounl_search_user()
	{
		jQuery("#ounewsletter_user_zero").hide();
		jQuery("#ounewsletter_displayvv").hide();
		jQuery("#ounewsletter_add_user").hide();
		jQuery("#ounewsletter_display_user").hide();
		jQuery("#ounewsletter_search_user").show();
	}
	</script>
	
	<script>
	function ounl_alldisplay_user()
	{
		var formData = new FormData(jQuery('#ou_nlformallusers')[0]);
		formData.append('action', 'ounldisplayusers');
		formData.append('nonce', '<?php echo wp_create_nonce('7uh9779h');?>');
		jQuery.ajax({
		type: "post",
		url: "admin-ajax.php",
		data: formData,
		contentType:false,
		processData:false,
		beforeSend: function() 
		{
			jQuery("#ounewsletter_user_zero").hide();
			jQuery("#ounewsletter_displayvv").hide();
			jQuery("#ounewsletter_search_user").hide();
			jQuery("#ounewsletter_add_user").hide();
			jQuery("#ounewsletter_display_user").hide();
		},
		success: function(html)
		{
			jQuery("#ounewsletter_display_user").empty();
			jQuery("#ounewsletter_display_user").append(html);
			jQuery("#ounewsletter_display_user").show();
		}
		});
	}
	</script>
	
	<div style="margin:10px; width:600px; border: 1px solid #0059b3; background: #ffffff; min-height: 60px;">
		<div style="background:#0059b3; width:600px;">
			<div style="padding:10px; font-size:18px; color: #ffffff;">
				<b><?php echo esc_html("Users");?></b>
			</div>
		</div>
		
		<form id= "ou_nlformallusers" enctype="multipart/form-data"  method="POST"></form>
		
		<div style="margin:10px; width:580px; min-height:40px;">
			<div style="font-size:14px; color:#000000; text-align:right;">
				<button class="button button-primary" onclick="ounl_search_user(); return false;"><?php echo esc_html("Search User");?></button> <button onclick="ounl_add_user(); return false;" class="button button-primary"><?php echo esc_html("Add User");?></button> <button onclick="ounl_alldisplay_user(); return false;" class="button button-primary"><?php echo esc_html("Users");?></button>
			</div>
			
			<?php
			$ounewsnetterusertable1 = $wpdb->prefix . "onnewsletterusers";
			$ounewsletteruserresult = $wpdb->get_var( "SELECT COUNT(*) FROM $ounewsnetterusertable1" );
		
			if($ounewsletteruserresult ==0)
			{
				?>
				<div id="ounewsletter_user_zero" style="color: #000000; font-size:27px; text-align:center; padding: 80px 0px;">
					<b><?php echo esc_html("You have 0 users");?></b>
				</div>
				<?php
			}
			if($ounewsletteruserresult >=1)
			{
				echo '<div id="ounewsletter_displayvv" style="color: #000000; padding:10px 0px 5px 0px;">';
					echo '<b>'.esc_html("Last 20 users").'</b> | '.esc_html("Users: ").esc_html($ounewsletteruserresult);
					echo '<table>';
				
						echo '<tr>';
							echo '<th>'.esc_html("Fist name").'</th>';
							echo '<th>'.esc_html("Last name").'</th>';
							echo '<th>'.esc_html("Email").'</th>';
							echo '<th>'.esc_html("Date").'</th>';
							echo '<th>'.esc_html("X").'</th>';
						echo '</tr>';
						
						
						$ouusernewslettert2 = $wpdb->prefix . "onnewsletterusers";
						$ounluser1 = $wpdb->get_results( "SELECT * FROM $ouusernewslettert2 where onnewsletterusers_email !='' ORDER BY onnewsletterusers_date DESC LIMIT 20 ");
						foreach ($ounluser1 as $ounluser2)
						{ 
							$onnewsletterusers_id = $ounluser2->onnewsletterusers_id; 
							$onnewsletterusers_email = $ounluser2->onnewsletterusers_email; 
							$onnewsletterusers_first_name = $ounluser2->onnewsletterusers_first_name; 
							$onnewsletterusers_last_name = $ounluser2->onnewsletterusers_last_name; 
							$onnewsletterusers_date = $ounluser2->onnewsletterusers_date; 
							?>
							<script>
							function ouuserdelete<?php echo $onnewsletterusers_id;?>()
							{
								var formData = new FormData(jQuery('#ou_nlformdeleteuser<?php echo $onnewsletterusers_id;?>')[0]);
								formData.append('action', 'ounldeleteuser');
								formData.append('nonce', '<?php echo wp_create_nonce('u9u097g');?>');
								formData.append('iduser', '<?php echo $onnewsletterusers_id;?>');
								jQuery.ajax({
								type: "post",
								url: "admin-ajax.php",
								data: formData,
								contentType:false,
								processData:false,
								success: function(html)
								{
									jQuery('#ouuseriddelete<?php echo $onnewsletterusers_id;?>').hide();
								}
								});
							}
							</script>
							<?php
							echo '<tr id="ouuseriddelete'.$onnewsletterusers_id.'">';
								echo '<td style="width:115px;">'.esc_html($onnewsletterusers_first_name).'</td>';
								echo '<td style="width:125px;">'.esc_html($onnewsletterusers_last_name).'</td>';
								echo '<td style="width:125px;">'.esc_html($onnewsletterusers_email).'</td>';
								echo '<td style="width:145px;">'.esc_html($onnewsletterusers_date).'</td>';
								echo '<td style="width:10px; text-align:center;">';
									echo '<form id="ou_nlformdeleteuser'.$onnewsletterusers_id.'" enctype="multipart/form-data"  method="POST">';
										echo '<a href="" onclick="ouuserdelete'.$onnewsletterusers_id.'(); return false;" style="font-size:12px;">'.esc_html("X").'</a>';
									echo '</form>';
								echo '</td>';
							echo '</tr>';
						}
				
					echo '</table>';
				
				echo '</div>';
			}
			?>
			<script>
			function ousearch_users2()
			{
				var formData = new FormData(jQuery('#ou_nlformsearchuser')[0]);
				formData.append('action', 'ounldisplayuserssearch');
				formData.append('nonce', '<?php echo wp_create_nonce('vbmuyif4054');?>');
				jQuery.ajax({
				type: "post",
				url: "admin-ajax.php",
				data: formData,
				contentType:false,
				processData:false,
				beforeSend: function() 
				{
					jQuery("#ounewsletter_display_user").hide();
					jQuery("#onnewslettersearchusercu").hide();
					jQuery("#onnewslettersearchuserpleasewait").show();
				},
				success: function(html)
				{
					jQuery("#ounewsletter_display_user").empty();
					jQuery("#ounewsletter_display_user").append(html);
					jQuery("#onnewslettersearchuserpleasewait").hide();
					jQuery("#onnewslettersearchusercu").show();
					jQuery("#ounewsletter_display_user").show();
				}
				});
			}
			</script>
			<div id="ounewsletter_search_user" style="display:none; color: #000000; padding:10px 0px 5px 0px;">
				<form id="ou_nlformsearchuser" enctype="multipart/form-data"  method="POST">
					<div style="font-size:18px;">
						<b><?php echo esc_html("Search");?></b>
					</div>
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("First name");?></b> 
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_add_user_firstname2" autocomplete="off" placeholder="<?php echo esc_html('First name');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_firstname2">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Last name");?></b>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_add_user_lastname2" autocomplete="off" placeholder="<?php echo esc_html('Last name');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_lastname2">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Email");?></b> 
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_add_user_email2" autocomplete="off" placeholder="<?php echo esc_html('Email');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_email2">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 5px 0px; ">
						<span id="onnewslettersearchusercu"><button  onclick="ousearch_users2(); return false;" class="button button-primary"><?php echo esc_html("Search User");?></button></span>
						<span id="onnewslettersearchuserpleasewait" style="display:none;"><b><?php echo esc_html("Please wait...");?></b></span>
						<button onclick="ousearchuser_cancel(); return false;" class="button button-primary"><?php echo esc_html("Cancel");?></button>
					</div>
					
				</form>
			</div>
			
			
			<div id="ounewsletter_display_user" style="display:none; color: #000000; padding:10px 0px 5px 0px;"></div>
		
			<div id="ounewsletter_add_user" style="display:none; color: #000000; padding:10px 0px 5px 0px;">
				<form id="ou_nlform1" enctype="multipart/form-data"  method="POST">
					<div style="font-size:18px;">
						<b><?php echo esc_html("Add User");?></b>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("First name");?></b> <span id="onnewsletter_add_user_firstname_label" style="display:none;"><?php echo esc_html("Please enter a  first name");?></span>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_add_user_firstname" autocomplete="off" placeholder="<?php echo esc_html('First name');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_firstname">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Last name");?></b> <span id="onnewsletter_add_user_lastname_label" style="display:none;"><?php echo esc_html("Please enter a  last name");?></span>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_add_user_lastname" autocomplete="off" placeholder="<?php echo esc_html('Last name');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_lastname">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<b><?php echo esc_html("Email");?></b> <span id="onnewsletter_add_user_email_label" style="display:none;"><?php echo esc_html("Please enter a valid email address");?></span>
					</div>
					
					<div style="font-size:14px; padding:5px 0px 0px 0px; ">
						<input type="text" id="onnewsletter_add_user_email" autocomplete="off" placeholder="<?php echo esc_html('Email');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_email">
					</div>
					
					<div style="font-size:14px; padding:5px 0px 5px 0px; ">
						<span id="onnewslettercreateusercu"><button  onclick="oucreateuser_create(); return false;" class="button button-primary"><?php echo esc_html("Create User");?></button></span>
						<span id="onnewslettercreateuserpleasewait" style="display:none;"><b><?php echo esc_html("Please wait...");?></b></span>
						<button onclick="oucreateuser_cancel(); return false;" class="button button-primary"><?php echo esc_html("Cancel");?></button>
					</div>
					
				</form>
			</div>
		
		</div>
		
	</div>
	<?php
}




function ounlshortcode_function()
{
	?>
	<script>
	function oucreateuser_signupuser()
	{
		var oufirstname1 = jQuery('#onnewsletter_add_user_firstname1').val().length;
		var oulastname1 = jQuery('#onnewsletter_add_user_lastname1').val().length;
		var ouemail1 = jQuery('#onnewsletter_add_user_email1').val();
		
		jQuery('#onnewsletter_add_user_email_label1').css('color','#000000');
		jQuery('#onnewsletter_add_user_email_label1').hide();
		jQuery('#onnewsletter_add_user_firstname_label1').css('color','#000000');
		jQuery('#onnewsletter_add_user_firstname_label1').hide();
		jQuery('#onnewsletter_add_user_lastname_label1').css('color','#000000');
		jQuery('#onnewsletter_add_user_lastname_label1').hide();
		
		var re2 = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;
		if (re2.test(ouemail1)) 
		{
			 if(oufirstname1 >=1)
			 {
				if(oulastname1 >=1)
				{
					var formData = new FormData(jQuery('#ou_nlform2')[0]);
					formData.append('action', 'oucreateusernoadmin2');
					formData.append('nonce', '<?php echo wp_create_nonce('gtruhcfuf');?>');
					jQuery.ajax({
					type: "post",
					url: oulewsletterjaxcode.oucode_url,
					data: formData,
					contentType:false,
					processData:false,
					beforeSend: function() 
					{
						
						jQuery("#ounewsletter_display_userbutton2").hide();
					},
					success: function(html)
					{
						jQuery("#ounewsletter_display_user2").empty();
						jQuery("#ounewsletter_display_user2").append(html);
						
					}
					});
				}
				else
				{
					jQuery('#onnewsletter_add_user_lastname_label1').css('color','#990000');
					jQuery('#onnewsletter_add_user_lastname_label1').show();
				}
			 }
			 else
			 {
				jQuery('#onnewsletter_add_user_firstname_label1').css('color','#990000');
				jQuery('#onnewsletter_add_user_firstname_label1').show();
				if(oulastname1 ==0)
				{
					jQuery('#onnewsletter_add_user_lastname_label1').css('color','#990000');
					jQuery('#onnewsletter_add_user_lastname_label1').show();
				}
			 }
		}
		else
		{
			 jQuery('#onnewsletter_add_user_email_label1').css('color','#990000');
			 jQuery('#onnewsletter_add_user_email_label1').show();
			 
			 if(oufirstname1 ==0)
			 {
				jQuery('#onnewsletter_add_user_firstname_label1').css('color','#990000');
				jQuery('#onnewsletter_add_user_firstname_label1').show();
			 }
			 if(oulastname1 ==0)
			 {
				jQuery('#onnewsletter_add_user_lastname_label1').css('color','#990000');
				jQuery('#onnewsletter_add_user_lastname_label1').show();
			 }
		}
		
	}
	</script>
	
	<div style="margin:10px; width: 460px; border: 1px solid #0059b3; background: #ffffff; min-height:50px; border-radius: 10px; ">
		<div style="margin:10px; width:440px;">
			<div style="color: #0059b3; padding:5px 0px 0px 0px; font-size: 20px; text-align:center;">
				<b>Newsletter Sign Up</b>
			</div>
			<form id="ou_nlform2" enctype="multipart/form-data"  method="POST">
				<div style="color: #000000; padding:5px 0px 0px 0px; font-size: 14px; text-align:left;">
					<b><?php echo esc_html("First name");?></b> <span id="onnewsletter_add_user_firstname_label1" style="display:none;"><?php echo esc_html("Please enter a  first name");?></span>
				</div>
			
				<div style="color: #000000; padding:5px 0px 0px 0px; font-size: 14px; text-align:left;">
					<input type="text" id="onnewsletter_add_user_firstname1" autocomplete="off" placeholder="<?php echo esc_html('First name');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_firstname1">
				</div>
				
				<div style="color: #000000; padding:5px 0px 0px 0px; font-size: 14px; text-align:left;">
					<b><?php echo esc_html("Last name");?></b>  <span id="onnewsletter_add_user_lastname_label1" style="display:none;"><?php echo esc_html("Please enter a  last name");?></span>
				</div>
				
				<div style="color: #000000; padding:5px 0px 0px 0px; font-size: 14px; text-align:left;">
					<input type="text" id="onnewsletter_add_user_lastname1" autocomplete="off" placeholder="<?php echo esc_html('Last name');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_lastname1">
				</div>
				
				<div style="color: #000000; padding:5px 0px 0px 0px; font-size: 14px; text-align:left;">
					<b><?php echo esc_html("Email");?></b> <span id="onnewsletter_add_user_email_label1" style="display:none;"><?php echo esc_html("Please enter a valid email address");?></span>
				</div>
				
				<div style="color: #000000; padding:5px 0px 0px 0px; font-size: 14px; text-align:left;">
					<input type="text" id="onnewsletter_add_user_email1" autocomplete="off" placeholder="<?php echo esc_html('Email');?>" style="font-size:14px; width:100%;" name="onnewslettera_add_user_email1">
				</div>
				
				<div style="color: #000000; padding:5px 0px 10px 0px; font-size: 14px; text-align:left;">
					<span id="ounewsletter_display_userbutton2"><button onclick="oucreateuser_signupuser(); return false;" ><?php echo esc_html("Sign Up");?></button></span>
					<span id="ounewsletter_display_user2" style="font-size:14px; color: #000000;"></span>
				</div>
				
			</form>
		</div>
	
	</div>
	<?php
}

add_shortcode('newsletter', 'ounlshortcode_function');

?>