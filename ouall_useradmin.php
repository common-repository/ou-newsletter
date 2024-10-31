<?php
if(!defined('ABSPATH')) exit;
$ounewsletcode = $_POST['nonce'];
if (!wp_verify_nonce($ounewsletcode, '7uh9779h'))
{
    wp_die();
}

if ( current_user_can('manage_options') )
{
	global $wpdb;
	$ounewsnetterusertable1 = $wpdb->prefix . "onnewsletterusers";
	$ounewsletteruserresult = $wpdb->get_var( "SELECT COUNT(*) FROM $ounewsnetterusertable1" );
		
	if($ounewsletteruserresult ==0)
	{
		?>
		<div style="color: #000000; font-size:27px; text-align:center; padding: 80px 0px;">
			<b><?php echo esc_html("You have 0 users");?></b>
		</div>
		<?php
	}
			
	if($ounewsletteruserresult >=1)
	{
		echo '<div  style="color: #000000; padding:10px 0px 5px 0px;">';
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
						var formData = new FormData(jQuery('#ou_nlformdeleteuser2<?php echo $onnewsletterusers_id;?>')[0]);
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
							jQuery('#ouuseriddelete2<?php echo $onnewsletterusers_id;?>').hide();
						}
						});
					}
					</script>
					<?php
					echo '<tr id="ouuseriddelete2'.$onnewsletterusers_id.'">';
						echo '<td style="width:115px;">'.esc_html($onnewsletterusers_first_name).'</td>';
						echo '<td style="width:125px;">'.esc_html($onnewsletterusers_last_name).'</td>';
						echo '<td style="width:125px;">'.esc_html($onnewsletterusers_email).'</td>';
						echo '<td style="width:145px;">'.esc_html($onnewsletterusers_date).'</td>';
						echo '<td style="width:10px; text-align:center;">';
							echo '<form id="ou_nlformdeleteuser2'.$onnewsletterusers_id.'" enctype="multipart/form-data"  method="POST">';
							echo '<a href="" onclick="ouuserdelete'.$onnewsletterusers_id.'(); return false;" style="font-size:12px;">'.esc_html(X).'</a>';
							echo '</form>';
						echo '</td>';
					echo '</tr>';
				}
				
			echo '</table>';
				
		echo '</div>';
	}		
			
}
?>