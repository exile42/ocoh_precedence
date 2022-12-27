<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';
include 'includes/converter.php';

if ($accessPH != 1) {
        ?><p>You do not have access to this file.</p><?
        include 'includes/footer.php';
        die;
}

$err = ""; // no error messages yet

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
if (isset($_REQUEST['Delete']))
	$Delete = $_REQUEST['Delete'];
else
	$Delete = "NOPE";

?>

<div class="bodyheader"><?php echo $Action?> User</div>
<br />
<br />
<?

if ($Delete == "DeleteUser") {
	if (isset($_REQUEST['WorkingUserID']))
		if (is_numeric($_REQUEST['WorkingUserID']))
			$WorkingUserID = $_REQUEST['WorkingUserID'];
		else
			$err .= " That User doesn't exist. ";
	
 	if ($err)
		die($err);
	else {	
		$Username = $db->get_var("SELECT Username as User FROM Users WHERE UserID='$WorkingUserID'");

		$query = "DELETE QUICK FROM Users WHERE UserID='$WorkingUserID'";
		$db->query($query);
		?><p class="success">You have successfully deleted <?php echo $Username?>.</p><?
		$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'deleted user $User u: $Username', '$IPAddress')");

	}
}

// extract variables from the request, with error handling now
if (isset($_REQUEST['WorkingUserID']))
	if (is_numeric($_REQUEST['WorkingUserID']))
		$WorkingUserID = $_REQUEST['WorkingUserID'];
	else
		$err .= " That User doesn't exist. ";
if (isset($_REQUEST['User']))
	$User = mysql_real_escape_string(SanitizeInputGeneral($_REQUEST['User']));
if (isset($_REQUEST['Password']))
	$Password = mysql_real_escape_string(SanitizeInputGeneral($_REQUEST['Password']));
if (isset($_REQUEST['Email']) && isemail($_REQUEST['Email']))
	$Email = mysql_real_escape_string($_REQUEST['Email']);


if (isset($_REQUEST['PHeraldPerm']) && is_numeric($_REQUEST['PHeraldPerm']))
	$PHeraldPerm = $_REQUEST['PHeraldPerm'];
if (isset($_REQUEST['OPHeraldPerm']) && is_numeric($_REQUEST['OPHeraldPerm']))
	$OPHeraldPerm = $_REQUEST['OPHeraldPerm'];
if (isset($_REQUEST['OPMinionPerm']) && is_numeric($_REQUEST['OPMinionPerm']))
	$OPMinionPerm = $_REQUEST['OPMinionPerm'];
if (isset($_REQUEST['OPViewPerm']) && is_numeric($_REQUEST['OPViewPerm']))
	$OPViewPerm = $_REQUEST['OPViewPerm'];
if (isset($_REQUEST['ROAHeraldPerm']) && is_numeric($_REQUEST['ROAHeraldPerm']))
	$ROAHeraldPerm = $_REQUEST['ROAHeraldPerm'];

if ($Action == "Submit") { //they've submitted the form

	//first check for errors
	if ($err != "") { //we got errors, make them work for it
		?><p class="error"><?php echo $err?></p><?
		ShowForm();
	} else { //no errors, insert or update as needed
	
		
		// Are adding or updating?
		if ($WorkingUserID == 0) { //we're adding the user
			$insertquery="INSERT INTO Users (Username, Password, Email, CreatedBy, CreatedOn) VALUES ('$User', '$Password', '$Email', '$Username', '$Now')";
			//echo $insertquery;

			//Do the update
			if ($results = $db->query($insertquery)) {
				// so we updated the user successfully
				?><p class="success">You have successfully added <?php echo $User?> <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'added User $User u: $Username', '$IPAddress')");
				
				// now update their permissions
				$WorkingUserID = $db->get_var("SELECT MAX(UserID) FROM Users WHERE Username='$User'");
				$insertpermissionsquery="INSERT INTO UserAccess (UserID, PrincipalHerald, Wimble, OPMinion, OPView, Wharrow) VALUES ('$WorkingUserID', '$OPHeraldPerm', '$OPHeraldPerm', '$OPMinionPerm', '$OPViewPerm', '$ROAHeraldPerm')";
				if ($results = $db->query($insertpermissionsquery)) {
					?> and the permissions were successfully updated.<?
					$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'added permissions for User $User u: $Username', '$IPAddress')");
				} else {
					?> but something went wrong and the user permissions weren't added. :( <?
					$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED adding permissions for User $User u: $Username', '$IPAddress')");
				} // end if updatequery
			} else {
				?> Something went wrong and the user info wasn't added (nor their permissions). :( <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED adding User $User u: $Username', '$IPAddress')");
			} // end if insertquery
			?>
            </p>

			<p>Go back to <a href="list_users.php">List of Users</a>.</p><?

			// end of adding

		} else { //so we're updating
			$updatequery="UPDATE Users SET
			Username='$User',
			Password='$Password',
			Email='$Email'
			WHERE UserID='$WorkingUserID'";
			//echo $updatequery;
			$updatepermissionsquery="UPDATE UserAccess SET
			PrincipalHerald='$PHeraldPerm',
			Wimble='$OPHeraldPerm',
			OPMinion='$OPMinionPerm',
			OPView='$OPViewPerm',
			Wharrow='$ROAHeraldPerm'
			WHERE UserID='$WorkingUserID'";
			//echo $updatepermissionsquery;
			
			//Do the update
			if ($results = $db->query($updatequery)) {
				// so we updated the user successfully
				?><p class="success">You have successfully updated <?php echo $User?> <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'edited User $User u: $Username', '$IPAddress')");
			} else {
				?> Either the user info wasn't changed or something went wrong. <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED edited User $User u: $Username', '$IPAddress')");
			} // end if updatequery
				
			// now update their permissions
			if ($results = $db->query($updatepermissionsquery)) {
				?> The permissions were successfully updated.<?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'edited permissions for User $User u: $Username', '$IPAddress')");
			} else {
				?> Either the permissions weren't changed or something went wrong. <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED editing permissions for User $User u: $Username', '$IPAddress')");
			} // end if updatequery
			?>
            </p>

			<p>Go back to <a href="list_users.php">List of Users</a>.</p><?
		}	
	} //end if error
	
} elseif ($Action == "Edit") { //they've chosen to edit someone, get the record from the db and showform
	$r = $db->get_row("SELECT UserID as WorkingUserID, Username as User, Password, Email, CreatedOn, CreatedBy FROM Users WHERE UserID='$WorkingUserID'");
	
	while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
		$$name = $value;
	};
	$PHeraldPerm = $db->get_var("SELECT PrincipalHerald FROM UserAccess WHERE UserID='$WorkingUserID'");
	$OPHeraldPerm = $db->get_var("SELECT Wimble FROM UserAccess WHERE UserID='$WorkingUserID'");
	$OPMinionPerm = $db->get_var("SELECT OPMinion FROM UserAccess WHERE UserID='$WorkingUserID'");
	$OPViewPerm = $db->get_var("SELECT OPView FROM UserAccess WHERE UserID='$WorkingUserID'");
	$ROAHeraldPerm = $db->get_var("SELECT Wharrow FROM UserAccess WHERE UserID='$WorkingUserID'");

	ShowForm();

} else { // assume they're adding, set ID = 0 and show form

	$WorkingUserID = 0;
	$User = "";
	$Password = "";
	$Email = "";
	$PHeraldPerm = 0;
	$OPHeraldPerm = 0;
	$OPMinionPerm = 0;
	$OPViewPerm = 0;
	$ROAHeraldPerm = 0;
	ShowForm();

}//end if Action


Function ShowForm() {
	global $db, $Action, $WorkingUserID, $User, $Password, $Email, $PHeraldPerm, $OPHeraldPerm, $OPMinionPerm, $OPViewPerm, $ROAHeraldPerm, $CreatedOn, $CreatedBy;
?>	
	<div align="center">
	<form action="add_user.php" method="post">
	<input type="hidden" name="Action" value="Submit">
	<input type="hidden" name="WorkingUserID" value="<?php echo $WorkingUserID?>">
	<table border="0" cellpadding="4" cellspacing="0" width="550" class="edittable">
	<tr><td colspan="2" class="tableheader"><?php echo $Action?> User</td></tr>
	<tr><td>Username:</td>
		<td><input type="text" name="User" value="<?php echo $User?>" size="50" maxlength="80"></td></tr>
	<tr><td>Password:</td>
		<td><input type="text" name="Password" value="<?php echo $Password?>" size="50" maxlength="80"></td></tr>
	<tr><td>Email:</td>
		<td><input type="text" name="Email" value="<?php echo $Email?>" size="50" maxlength="80"></td></tr>
	<tr><td colspan="2">Note: Permissions do not cascade - give each permission necessary</td></tr>
	<tr><td><?php WriteYNDropDown($PHeraldPerm, "PHeraldPerm"); ?></td>
		<td>Principal Herald Permission</td></tr>
	<tr><td><?php WriteYNDropDown($OPHeraldPerm, "OPHeraldPerm"); ?></td>
		<td>Full OP Herald (Wimble) Permission (necessary to edit things)</td></tr>
	<tr><td><?php WriteYNDropDown($OPMinionPerm, "OPMinionPerm"); ?></td>
		<td>"OP Minion" (does nothing at this time)</td></tr>
	<tr><td><?php WriteYNDropDown($OPViewPerm, "OPViewPerm"); ?></td>
		<td>"OP View" Permission, give to anyone who needs to view/access anything on the OP site, including Wharrow</td></tr>
	<tr><td><?php WriteYNDropDown($ROAHeraldPerm, "ROAHeraldPerm"); ?></td>
		<td>Full Roll of Arms Herald (Wharrow) Permission</td></tr>

<?	if ($Action == "Edit") { ?>
		<tr><td colspan="2" class="tableheader">Administration</td></tr>
		<tr><td>Date Created:</td>
			<td><?php echo $CreatedOn?></td></tr>
		<tr><td>Created By:</td>
			<td><?php echo $CreatedBy?></td></tr>
<?	} ?>
	
	<tr><td></td>
		<td><input type="submit" name="submit" value="<?php echo $Action?> User"></td></tr>
	</table>
	</form>
	</div>


<?
}

include 'includes/footer.php';
?>
