<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble == 0) {
        echo "<p>You do not have access to this file.</p>";
        include("includes/footer.php");
        die;
}

?>

<div class="bodyheader">Manage Users</div>
<br />
<br />
<?php

//if we get action=delete on the querystring and an id, then delete that user
if ($Action == "Delete" && $ID != "") {
	$db->query("DELETE QUICK FROM users WHERE ID='$ID'");
	?><p class="success">You have successfully deleted <?php=$Name?>.</p><?php
}

//if they didn't die above, then they have admin privs, so list all users

if ($results = $db->get_results("SELECT U.*, UT.UserType 
					FROM Users U
					INNER JOIN UserType UT ON UT.ID=U.UserTypeID
					ORDER BY LastLogin DESC")) {
	//if we are here, then we got results from the users table
	//loop through them 
?>	<table border="0" cellpadding="4" cellspacing="0" class="borderedtable">
	<tr class="tableheader"><td>Name</td>
		<td>Email</td>
		<td>User Type</td>
		<td>Last Login</td>
		<td>Action </td></tr>
<?php	$row = "lightrow";
	foreach($results as $r) {
		$ID 		= $r->ID;
		$Name		= $r->Username;
		$Email		= $r->Email;
		$LastLogin	= format_date_time_pretty($r->LastLogin); //function formats the date so it looks nicer, in functions
		$LastLoginIP= $r->LastLoginIP;
		$UserType	= $r->UserType;
?>
		<tr class="<?php=$row?>"><td><?php=$Name?></td>
			<td><?php=$Email?></td>
			<td><?php=$UserType?></td>
			<td><?php=$LastLogin?></td>

			<td><a 	href="edit_user.php?ID=<?php=$ID?>&Action=Edit">EDIT</a> | 
			
				<a 	href="manage_users.php?Action=Delete&ID=<?php=$ID?>&Name=<?php=$Name?>"
					onClick="return confirm_delete('<?php=$Name?>');">DELETE</a>
			</td>
		</tr>
<?php
		IF ($row == "lightrow") { $row = "darkrow"; } else { $row = "lightrow"; } //alternates rows
	} //end foreach loop
?>	<tr class="<?php=$row?>"><td colspan="5" align="center"><a href="edit_user.php?Action=Add">ADD USER</a></td></tr>
	</table>
<?php
} else {
	?><p>There are currently no users. Would you like to <a href="edit_user.php?Action=Add">add one</a>?</p><?php
}

?>


<?php
include("includes/footer.php");
?>
