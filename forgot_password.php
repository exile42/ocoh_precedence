<?
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';

	$sender_name = "Wimble Web Site";
	$sender_email = "wimble@outlandsheralds.org";
	$email_header    = "From: \"".addslashes($sender_name)."\" <".$sender_email.">\r\n";
	$email_header   .= "Reply-To: ".$sender_email."\r\n";
	$email_header   .= "MIME-Version: 1.0\r\n";
	$email_header   .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	$email_header   .= "X-Priority: 1\r\n";
	$email_header   .= "X-Mailer: PHP / ".phpversion()."\r\n";

IF ($Action == "Submit") {
	IF ($Username != "") {
		$Username = trim($Username);
		IF ($r = $db->get_row("SELECT * FROM Users WHERE Username LIKE '$Username'")) {
			$Email 		= $r->Email;
			$Password 	= $r->Password;
			
			$Subject = "[Wimble] Important Information ";
	$strBody = "Someone, hopefully you, has requested login information to the wimble site. 
	
username: $Username
password: $Password
";
		 
			mail($Email, $Subject, $strBody, $email_header);

			PRINT "<p>Your password has been sent to the email address on file.</p>";
			
		} else {
			PRINT "<p>No matching username.</p>";
			ShowForm();
		}

	
	} else {
		PRINT "<p>You must enter the username.</p>";
		ShowForm();
	}
} else {
	ShowForm();
}

Function ShowForm() {
	global $db;
?>
  <form action="forgot_password.php" method="post">
	<input type="hidden" name="Action" value="Submit">
	<div align="center">
	<table border="0" cellspacing="3" cellpadding="3" width="560">
		<tr><td colspan="2" class="tableheader">Retrieve Password</td></tr>
	<tr><td>Username:</td>
		<td><input type="text" name="Username" size="16" maxlength="16" /></td></tr>
	<tr><td></td>
		<td><input type="submit" name="submit" value="Get Password" /></td></tr>
	</table>
	</form>
	</div>
<?
	
}

include 'includes/footer.php';
?>