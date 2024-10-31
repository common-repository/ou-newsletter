<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, 'vbmuyif4054'))
{
    wp_die();
}

if ( current_user_can('manage_options') )
{
	
	$onnewslettera_add_user_firstname =  sanitize_text_field($_POST['onnewslettera_add_user_firstname2']);
	$onnewslettera_add_user_lastname =  sanitize_text_field($_POST['onnewslettera_add_user_lastname2']);
	$onnewslettera_add_email = sanitize_email($_POST['onnewslettera_add_user_email2']);
	
	if(!empty($onnewslettera_add_user_firstname) || !empty($onnewslettera_add_user_lastname) || !empty($onnewslettera_add_email) )
	{
		if(!empty($onnewslettera_add_user_firstname))
		{
			$onnewslettera_add_user_firstname_new = ' AND onnewsletterusers_first_name = "'.$onnewslettera_add_user_firstname.'"';
		}
		
		if(!empty($onnewslettera_add_user_lastname))
		{
			$onnewslettera_add_user_lastname_new = ' AND onnewsletterusers_last_name = "'.$onnewslettera_add_user_lastname.'"';
		}
		
		if(!empty($onnewslettera_add_email))
		{
			$onnewslettera_add_email_new = ' AND onnewsletterusers_email = "'.$onnewslettera_add_email.'"';
		}
		
		global $wpdb;
		$ouusernewslettert2 = $wpdb->prefix . "onnewsletterusers";
		$ounewsletteruserresult3 = $wpdb->get_var( "SELECT COUNT(*) FROM $ouusernewslettert2  where onnewsletterusers_date !='' $onnewslettera_add_user_firstname_new  $onnewslettera_add_user_lastname_new  $onnewslettera_add_email_new " );
	
		if($ounewsletteruserresult3 == 0)
		{
			echo '<div style="color: #000000; padding:10px 0px 5px 0px;">';
				echo '<b>'.esc_html("Nothing found").'</b>';
			echo '</div>';
		}
		else
		{
			echo '<div id="ounewsletter_displayvv" style="color: #000000; padding:10px 0px 5px 0px;">';
				echo '<b>'.esc_html("Found ").'</b>';
				echo '<b>'.esc_html($ounewsletteruserresult3).'</b>';
				echo '<b>'.esc_html(" users").'</b>';
				echo '<table>';
				
					echo '<tr>';
						echo '<th>'.esc_html("Fist name").'</th>';
						echo '<th>'.esc_html("Last name").'</th>';
						echo '<th>'.esc_html("Email").'</th>';
						echo '<th>'.esc_html("Date").'</th>';
						echo '<th>'.esc_html("X").'</th>';
					echo '</tr>';
						
					$ounluser1 = $wpdb->get_results( "SELECT * FROM $ouusernewslettert2 where onnewsletterusers_date !='' $onnewslettera_add_user_firstname_new  $onnewslettera_add_user_lastname_new  $onnewslettera_add_email_new  ");
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
							var formData = new FormData(jQuery('#ou_nlformdeleteuser3<?php echo $onnewsletterusers_id;?>')[0]);
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
								jQuery('#ouuseriddelete3<?php echo $onnewsletterusers_id;?>').hide();
							}
							});
						}
						</script>
						<?php
						echo '<tr id="ouuseriddelete3'.$onnewsletterusers_id.'">';
							echo '<td style="width:115px;">'.esc_html($onnewsletterusers_first_name).'</td>';
							echo '<td style="width:125px;">'.esc_html($onnewsletterusers_last_name).'</td>';
							echo '<td style="width:125px;">'.esc_html($onnewsletterusers_email).'</td>';
							echo '<td style="width:145px;">'.esc_html($onnewsletterusers_date).'</td>';
							echo '<td style="width:10px; text-align:center;">';
								echo '<form id="ou_nlformdeleteuser3'.$onnewsletterusers_id.'" enctype="multipart/form-data"  method="POST">';
									echo '<a href="" onclick="ouuserdelete'.$onnewsletterusers_id.'(); return false;" style="font-size:12px;">'.esc_html("X").'</a>';
								echo '</form>';
							echo '</td>';
						echo '</tr>';
					}		
				echo '</table>';		
			echo '</div>';
		}
	
	}
	else
	{
		echo '<div style="color: #000000; padding:10px 0px 5px 0px;">';
			echo '<b>'.esc_html("Nothing found").'</b>';
		echo '</div>';
	}
	
}
?>