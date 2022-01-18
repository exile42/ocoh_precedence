<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

iif ($accessWimble == 0) {
        PRINT "<p>You do not have access to this file.</p>";
        include("includes/footer.php");
        die;
}

?>

<div class="bodyheader">Manage Users</div>
<br />
<br />
<?

//if we get action=delete on the querystring and an id, then delete that user
if ($Action == "Delete" && $ID != "") {
	$db->query("DELETE QUICK FROM users WHERE ID='$ID'");
	?><p class="success">You have successfully deleted <?=$Name?>.</p><?
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
<?	$row = "lightrow";
	foreach($results as $r) {
		$ID 		= $r->ID;
		$Name		= $r->Username;
		$Email		= $r->Email;
		$LastLogin	= format_date_time_pretty($r->LastLogin); //function formats the date so it looks nicer, in functions
		$LastLoginIP= $r->LastLoginIP;
		$UserType	= $r->UserType;
?>
		<tr class="<?=$row?>"><td><?=$Name?></td>
			<td><?=$Email?></td>
			<td><?=$UserType?></td>
			<td><?=$LastLogin?></td>

			<td><a 	href="edit_user.php?ID=<?=$ID?>&Action=Edit">EDIT</a> | 
			
				<a 	href="manage_users.php?Action=Delete&ID=<?=$ID?>&Name=<?=$Name?>"
					onClick="return confirm_delete('<?=$Name?>');">DELETE</a>
			</td>
		</tr>
<?
		IF ($row == "lightrow") { $row = "darkrow"; } else { $row = "lightrow"; } //alternates rows
	} //end foreach loop
?>	<tr class="<?=$row?>"><td colspan="5" align="center"><a href="edit_user.php?Action=Add">ADD USER</a></td></tr>
	</table>
<?
} else {
	?><p>There are currently no users. Would you like to <a href="edit_user.php?Action=Add">add one</a>?</p><?
}

?>


<?
include("includes/footer.php");
?>
