<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

$strTo = "wimble@outlandsheralds.org, wharrow@outlandsheralds.org"; 
?>

<div class="bodyheader">Submit Update</div>
<br />
<br />
<?
IF ($action == "submit") {
	$Browser	= getenv ("HTTP_USER_AGENT");
	$IPAddress  = getenv ("REMOTE_ADDR");
	$Now 		= date("Y-m-d H:i:s");

	$sender_name = "Wimble Web Site";
	$sender_email = "wimble@outlandsheralds.org";
	$email_header    = "From: \"".addslashes($sender_name)."\" <".$sender_email.">\r\n";
	$email_header   .= "Reply-To: ".$sender_email."\r\n";
	$email_header   .= "MIME-Version: 1.0\r\n";
	$email_header   .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	$email_header   .= "X-Priority: 1\r\n";
	$email_header   .= "X-Mailer: PHP / ".phpversion()."\r\n";
	
	$Subject = "[Wimble Update Submission] " . stripslashes($scaname);
	$strBody = "
scaname:       ". stripslashes($scaname)."
mkaname:       ". stripslashes($mkaname)."
local group:   ". stripslashes($localgroup)."
regname:       ". stripslashes($regname)."
regnamedate:   ". stripslashes($regnamedate)."
regblazon:     ". stripslashes($regblazon)."
regdevicedate: ". stripslashes($regdevicedate)."

awards:
". stripslashes($awards)."

comments:
". stripslashes($comments)."


Sent:          $Now 
IP Address:    $IPAddress 
Browser:       $Browser";
		 
	 mail($strTo, $Subject, $strBody, $email_header);
	PRINT "<p>Thank you for your submission!</p>";
} else {
	ShowForm();
}



Function ShowForm() {
	global $db, $action, $scaname, $mkaname, $localgroup, $regname, $regnamedate, $regblazon, $regdevicedate, $awards, $comments; 
?>
	<div class="form">
		<form action="submit_update.php" method="post">
		<input type="hidden" name="action" value="submit" />
		<b>SCA Name:</b><br />
		<input type="text" size="40" name="scaname" class="formfield" value="<?=htmlspecialchars($scaname)?>" /><br /><br />
		<b>Mundane Name:</b><br />
		<input type="text" size="40" name="mkaname" class="formfield" value="<?=htmlspecialchars($mkaname)?>" /><br /><br />
		<b>Local Group:</b><br />
		<input type="text" size="40" name="localgroup" class="formfield" value="<?=htmlspecialchars($localgroup)?>" /><br /><br />
		<b>Registered Name (if different from SCA name):</b><br />
		<input type="text" size="40" name="regname" class="formfield" value="<?=htmlspecialchars($regname)?>" /><br /><br />
		<b>Date of Registration:</b><br />
		<input type="text" size="40" name="regnamedate" class="formfield" value="<?=htmlspecialchars($regnamedate)?>" /><br /><br />
		<b>Registered Device Blazon:</b><br />
		<input type="text" size="40" name="regblazon" class="formfield" value="<?=htmlspecialchars($regblazon)?>" /><br /><br />
		<b>Date of Device Registration:</b><br />
		<input type="text" size="40" name="regdevicedate" class="formfield" value="<?=htmlspecialchars($scaname)?>" /><br /><br />
		<b>Awards Received (please include dates):</b><br />
		<textarea rows="8" name="awards" cols="40" class="formfield"><?=htmlspecialchars($awards)?></textarea><br /><br />
		<b>Additional Notes:</b><br />
		<textarea rows="8" name="comments" cols="40" class="formfield"><?=htmlspecialchars($comments)?></textarea><br /><br />
		<input type="submit" class="button" value="Submit" />
		</form>
	</div>
<?
}



include("includes/footer.php");
?>