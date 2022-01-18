<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessPH != 1) {
        ?><p>You do not have access to this file.</p><?
        include("includes/footer.php");
        die;
}

$err = "";

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
if (isset($_REQUEST['Delete']))
	$Delete = $_REQUEST['Delete'];
else
	$Delete = "NOPE";

if ($Delete == "DeleteUser") {
	$User = "";
	if (isset($_REQUEST['WorkingUserID']))
		if (is_numeric($_REQUEST['WorkingUserID']))
			$WorkingUserID = $_REQUEST['WorkingUserID'];
		else
			$err .= " That User doesn't exist. ";
	
 	if ($err)
		die($err);
	else {	
		$User = $db->get_var("SELECT Username as User FROM Users WHERE UserID='$WorkingUserID'");

		$query = "DELETE QUICK FROM Users WHERE UserID='$WorkingUserID'";
		if ($results=$db->query($query)) {
			?><p class="success">You have successfully deleted <?=$User?>.</p><?
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'deleted user $User u: $Username', '$IPAddress')");
		} else {
			?><p class="failure">Something went wrong in deleting <?=$User?></p><?
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED deleted user $User u: $Username', '$IPAddress')");
		}
	}
}


?>

<div class="bodyheader">List all Users in the system</div>
<br />
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550">
  <tr class="tableheader"><td>Username</td><td>Email</td><td>Last Login</td><td>Action</td></tr>

	<? 
	
	// Don't use Username or UserID, or it will override the ones we pulled from the session cookie!
	
	$usersquery="SELECT UserID as WorkingUserID, Username as User, Email, DATE_FORMAT(LastLogin, '%Y-%m-%d') AS LastLoginDate FROM Users WHERE UserID !=5 ORDER BY UserID";
		
	if ($results = $db->get_results($usersquery)) {
		foreach($results as $r) {
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			?>
			<tr><td><?=$User?></td>
			<td><?=$Email?></td>
			<td><?=$LastLoginDate?></td>
			<td><a href="add_user.php?Action=Edit&WorkingUserID=<?=$WorkingUserID?>">EDIT</a> | <a href="list_users.php?Delete=DeleteUser&Action=Edit&WorkingUserID=<?=$WorkingUserID?>"	onClick="return confirm_delete('<?=$User?> entirely')">Delete?</a><br></td>

			</tr>
			<?
		} //end foreach
	} else {
    ?>
	<tr><td colspan="2" align="center">
      Bigtime error: no users!
    </td></tr> <?
	} // end if results for usersquery

	
?>  <tr><td colspan="2" align="center">
    <a href="add_user.php?Action=Add">ADD NEW USER</a>
  </td></tr></table></div>

<?
include("includes/footer.php");
?>
