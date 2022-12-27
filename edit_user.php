<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';
include 'includes/converter.php';

if ($accessWimble != 1) {
        ?><p>You do not have access to this file.</p><?
        include 'includes/footer.php';
        die;
}

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
if (isset($_REQUEST['Delete']))
	$Delete = $_REQUEST['Delete'];
else
	$Delete = "NOPE";
?>

<div class="bodyheader"><?php echo $Action?> Person</div>
<br />
<br />
<?

// If we're deleting an alias, let's do that first.
if ($Delete == "DeleteAlias") {
	if (isset($_REQUEST['PersonID']))
		if (is_numeric($_REQUEST['PersonID']))
			$PersonID = $_REQUEST['PersonID'];
		else
			$err .= " That Person doesn't exist. ";
	if (isset($_REQUEST['AliasID']))
		if (is_numeric($_REQUEST['AliasID']))
			$AliasID = $_REQUEST['AliasID'];
		else
			$err .= " That Alias doesn't exist. ";
	
	if ($err)
		die($err);
	else {
		$query = "SELECT Alias FROM wAliases WHERE AliasID='$AliasID' AND PersonID='$PersonID'";
		$Alias = $db->get_var($query);
		$query = "SELECT SCAName FROM wPeople WHERE PersonID='$PersonID'";
		$Name = $db->get_var($query);
		
		$query = "DELETE QUICK FROM wAliases WHERE AliasID='$AliasID' AND PersonID='$PersonID'";
		$db->query($query);
		?><p class="success">You have successfully deleted <?php echo $Alias?> from the profile.</p><?
		$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'deleted an alias $Alias for $Name u: $Username', '$IPAddress')");
	} // end if err
	
} // end if Delete == DeleteAlias

// extract variables from the request, with error handling now
if (isset($_REQUEST['PersonID']))
	if (is_numeric($_REQUEST['PersonID']))
		$PersonID = $_REQUEST['PersonID'];
	else
		$err .= " That Person doesn't exist. ";
if (isset($_REQUEST['SCAName']))
	$SCAName = mysql_real_escape_string(SanitizeInputGeneral($_REQUEST['SCAName']));
	else
		$err .= " There is no SCA Name. That's not ok. ";
if (isset($_REQUEST['RegName']))
	if (is_numeric($_REQUEST['RegName']))
		$RegName = $_REQUEST['RegName'];
	else
		$err .= "Registered Name flag has an invalid value. ";
if (isset($_REQUEST['RegNameDate']))
	$RegNameDate = mysql_real_escape_string($_REQUEST['RegNameDate']);
if (isset($_REQUEST['RegDevice']))
	$RegDevice = mysql_real_escape_string($_REQUEST['RegDevice']);
if (isset($_REQUEST['Blazon']))
	$Blazon = mysql_real_escape_string($_REQUEST['Blazon']);
if (isset($_REQUEST['GroupID']))
	if (is_numeric($_REQUEST['GroupID'])) 
		$GroupID = $_REQUEST['GroupID'];
	elseif ($_REQUEST['GroupID'] == "")
		$GroupID = "NULL";
	else
		$err .= " Group has an invalid value";
if (isset($_REQUEST['KingdomID']) && is_numeric($_REQUEST['KingdomID']))
	$KingdomID = $_REQUEST['KingdomID'];
if (isset($_REQUEST['Active']) && is_numeric($_REQUEST['Active']))
	$Active = $_REQUEST['Active'];
if (isset($_REQUEST['Deceased'])  && is_numeric($_REQUEST['Deceased']))
	$Deceased = $_REQUEST['Deceased'];
if (isset($_REQUEST['ModernName']))
	$ModernName = mysql_real_escape_string(SanitizeInputGeneral($_REQUEST['ModernName']));
	else
		$err .= " There is legal name. ";

if (isset($_REQUEST['MemberNumber']) && is_numeric($_REQUEST['MemberNumber']))
	$MemberNumber = $_REQUEST['MemberNumber'];
if (isset($_REQUEST['AliasCount']))
	$AliasCount = $_REQUEST['AliasCount'];
if (isset($_REQUEST['AddAlias']))
	$AddAlias = mysql_real_escape_string($_REQUEST['AddAlias']);
else
	$AddAlias = "";
// $Alias is NOT sanitized here because it's an array and the sanitization is handled as it is processed, below.

// NOW let's figure out what Action we're taking here

if ($Action == "Submit") { //they've submitted the form

	//first check for errors
	if ($err != "") { //we got errors, make them work for it
		?><p class="error"><?php echo $err?></p><?
		ShowForm();
	} else { //no errors, insert or update as needed
	
		$SearchName	= convert_from_utf8_to_ascii($SCAName);
		$SearchName = str_replace("'", "", $SearchName);
		$SearchName = str_replace("`", "", $SearchName);

		if ($PersonID == 0) { //we're adding the user
			$insertquery="INSERT INTO wPeople 
			(SCAName, SearchName, RegName, RegNameDate, RegDevice, GroupID, 
			KingdomID, Active, Deceased, ModernName, MemberNumber, DateAdded, LastUpdate, LastUpdateID) 
			VALUES 
			('$SCAName', '$SearchName', '$RegName', '$RegNameDate', '$RegDevice', '$GroupID', 
			'$KingdomID', '$Active', '$Deceased', '$ModernName', '$MemberNumber', '$Now', '$Now', '$LoggedUserID')";
			//echo $insertquery;
			// Do the insert
			if ($results = $db->query($insertquery)) {
				?><p class="success">You have successfully added <?php echo $SCAName?>.<?
				$PersonID = $db->get_var("SELECT MAX(PersonID) FROM wPeople WHERE SCAName='$SCAName'");
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'added new Person $SCAName u: $Username', '$IPAddress')");
				?><p><a href="view_person.php?PersonID=<?php echo $PersonID?>">VIEW DETAILS</a><?
				?> | <a href="edit_user.php?PersonID=<?php echo $PersonID?>&Action=Edit">EDIT PERSON</a> |
				<a href="edit_awards.php?PersonID=<?php echo $PersonID?>">EDIT AWARDS</a> </p><?
			} else {
				?> Something went wrong and the person wasn't added. :( <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED added new Person $SCAName u: $Username', '$IPAddress')");
			} // end if insertquery

			// end of adding section

		} else { //we're updating
			$updatequery="UPDATE wPeople SET
			SCAName='$SCAName',
			SearchName='$SearchName',
			RegName='$RegName',
			RegNameDate='$RegNameDate',
			RegDevice='$RegDevice',
			GroupID='$GroupID',
			KingdomID='$KingdomID',
			Active='$Active',
			Deceased='$Deceased',
			ModernName='$ModernName',
			MemberNumber='$MemberNumber',
			LastUpdate='$Now',
			LastUpdateID='$LoggedUserID'
			WHERE PersonID='$PersonID'";
			//echo $updatequery;
			//Do the update
			if ($results = $db->query($updatequery)) {
				?><p class="success">You have successfully updated <?php echo $SCAName?>.<?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'edited Person $SCAName u: $Username', '$IPAddress')");
				?><p><a href="view_person.php?PersonID=<?php echo $PersonID?>">VIEW DETAILS</a><?
   	    		?> | <a href="edit_user.php?PersonID=<?php echo $PersonID?>&Action=Edit">EDIT PERSON</a> |
	            <a href="edit_awards.php?PersonID=<?php echo $PersonID?>">EDIT AWARDS</a> </p><?
			} else {
				?> Something went wrong and the person wasn't updated. :( <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED edited Person $SCAName u: $Username', '$IPAddress')");
			} // end if insertquery

//echo "<br>AliasCount: $AliasCount<br>";	
			if ($AliasCount > 0) {
				$i = 0; 
				WHILE ($i < $AliasCount): 
					$AliasID = $_REQUEST['AliasID-'.$i]; 
					$Alias 	 = mysql_real_escape_string($_REQUEST['Alias-'.$i]); 
					$SearchAlias	= convert_from_utf8_to_ascii($Alias);
					$SearchAlias = str_replace("'", "", $SearchAlias);
					$SearchAlias = str_replace("`", "", $SearchAlias);
					$query = "UPDATE wAliases SET Alias='$Alias', SearchAlias='$SearchAlias' WHERE AliasID='$AliasID'";
					$db->query($query);
					//echo "$query <br>";
				$i++; //increments i by one
				ENDWHILE;
			} //end if AliasCount
			
			?><p class="success">You have successfully edited <?php echo $SCAName?>.</p>
			<p>Go back to <a href="individual_record.php?PersonID=<?php echo $PersonID?>"><?php echo $SCAName?></a>.</p><?
		}
		
		//for both add and edit
		if ($AddAlias != "") {
			$AddAlias = mysql_real_escape_string($AddAlias);
			$SearchAlias	= convert_from_utf8_to_ascii($AddAlias);
			$SearchAlias = str_replace("'", "", $SearchAlias);
			$SearchAlias = str_replace("`", "", $SearchAlias);
			$aliasquery = "INSERT INTO wAliases (PersonID, Alias, SearchAlias) VALUES ('$PersonID', '$AddAlias', '$SearchAlias')";
			//echo $query;
			//Do the update
			if ($results = $db->query($aliasquery)) {
				?><p class="success">You have successfully added the alias <?php echo $AddAlias?> to <?php echo $SCAName?>.<?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'added new alias $AddAlias to $SCAName u: $Username', '$IPAddress')");
			} else {
				?> Something went wrong and the new alias wasn't added. :( <?
				$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED adding new alias $AddAlias to $SCAName u: $Username', '$IPAddress')");
			} // end if aliasquery
		}
		
	} //end if error
	
} elseif ($Action == "Edit") { //they've chosen to edit someone, get the record from the db and showform
	$r = $db->get_row("SELECT * FROM wPeople WHERE PersonID='$PersonID'");
	
	while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
		$$name = $value;
	};
	$LastUpdateBy 	= $db->get_var("SELECT Username FROM Users WHERE UserID='$LastUpdateID'");
	ShowForm();

} elseif($Action == "Add") { //they're adding, set ID = 0 and show form

	$PersonID = 0;
	$Active = "1";
	$Deceased = "";
	$RegName = "";
	$KingdomID = $MYKINGDOMID;
	ShowForm();

}//end if Action


Function ShowForm() {
	global $db, $PersonID, $Action, $SCAName, $RegName, $RegNameDate, $RegDevice, $GroupID, $KingdomID, $Active, $Deceased, $ModernName, $MemberNumber, $DateAdded, $LastUpdate, $LastUpdateBy, $MYKINGDOMID;
?>	
	<div align="center">
	<form action="edit_user.php" method="post">
	<input type="hidden" name="Action" value="Submit">
	<input type="hidden" name="PersonID" value="<?php echo $PersonID?>">
	<table border="0" cellpadding="4" cellspacing="0" width="550" class="edittable">
	<tr><td colspan="2" class="tableheader"><?php echo $Action?> Person</td></tr>
	<tr><td>SCA Name:</td>
		<td><input type="text" name="SCAName" value="<?php echo htmlspecialchars($SCAName)?>" size="50" maxlength="80"></td></tr>
	<tr><td>Registered Name:</td>
		<td><?php WriteYNDropDown($RegName, "RegName"); ?></td></tr>
	<tr><td>Date Registered:</td>
		<td><input type="text" name="RegNameDate" value="<?php echo htmlspecialchars($RegNameDate)?>" size="10" maxlength="10"></td></tr>
	<tr><td></td>
		<td><span class="small">(YYYY-MM)</span></td></tr>
	<tr><td>Registered Device:</td>
		<td><?php WriteYNDropDown($RegDevice, "RegDevice"); ?></td></tr>
	<tr><td>Group:</td>
		<td>
		<select name="GroupID">
		<option value="">SELECT ONE</option>
		<?php 
		$results = $db->get_results("SELECT GroupID, Name, KingdomID FROM Groups WHERE KingdomID = $MYKINGDOMID ORDER BY Name");
		foreach($results as $r) {
			$thisGroupID = $r->GroupID;
			$GroupName   = $r->Name;
			if ($GroupID == $thisGroupID) { $s = "selected"; } else { $s = ""; }
			?><option value="<?php echo $thisGroupID?>" <?php echo $s?>><?php echo $GroupName?></option><?
		}
		$results = $db->get_results("SELECT GroupID, Name, KingdomID FROM Groups WHERE KingdomID != $MYKINGDOMID ORDER BY KingdomID, Name");
		foreach($results as $r) {
			$thisGroupID = $r->GroupID;
			$GroupName   = $r->Name;
			if ($GroupID == $thisGroupID) { $s = "selected"; } else { $s = ""; }
			?><option value="<?php echo $thisGroupID?>" <?php echo $s?>><?php echo $GroupName?></option><?
		}
		 ?>
		 </select>
		 </td></tr>
	<tr><td>Kingdom:</td>
		<td>
		<select name="KingdomID">		
		<option value="">SELECT ONE</option>
		<?php 
		$results = $db->get_results("SELECT KingdomID, Name FROM Kingdoms ORDER BY Name");
		foreach($results as $r) {
			$thisKID = $r->KingdomID;
			$KName   = $r->Name;
			if ($KingdomID == $thisKID) { $s = "selected"; } else { $s = ""; }
			?><option value="<?php echo $thisKID?>" <?php echo $s?> ><?php echo $KName?></option><?
		}
		 ?>
		 </select>
		 </td></tr>

	<tr><td>Active:</td>
		<td><?php WriteYNDropDown($Active, "Active"); ?></td></tr>
	<tr><td>Deceased:</td>
		<td><?php WriteYNDropDown($Deceased, "Deceased"); ?></td></tr>
	<tr><td>Modern Name:</td>
		<td><input type="text" name="ModernName" value="<?php echo htmlspecialchars($ModernName)?>" size="50" maxlength="150"></td></tr>
	<tr><td>Member Number:</td>
		<td><input type="text" name="MemberNumber" value="<?php echo htmlspecialchars($MemberNumber)?>" size="10" maxlength="10"></td></tr>
	<tr><td colspan="2" class="tableheader">Aliases</td></tr>
	<tr><td valign="top">Aliases:</td>
		<td>

		<?
		$count = 0;
		if ($results = $db->get_results("SELECT AliasID, Alias FROM wAliases WHERE PersonID='$PersonID'")) {
			foreach($results as $r) {
				$AliasID = $r->AliasID;
				$Alias 	  = $r->Alias;
				?>
				<input type="hidden" name="AliasID-<?php echo $count?>" value="<?php echo $AliasID?>">
				<input type="text" name="Alias-<?php echo $count?>" value="<?php echo htmlspecialchars($Alias)?>" size="40" maxlength="80">
				<a 	href="edit_user.php?Delete=DeleteAlias&Action=Edit&PersonID=<?php echo $PersonID?>&Alias=<?php echo $Alias?>&AliasID=<?php echo $AliasID?>"
					onClick="return confirm_delete('<?php echo $Alias?>')">Delete?</a><br>
				<?php 
				$count++;
			} //end foreach
			?></td></tr><?
		} else {
			?><tr><td colspan="2">No current aliases.</td></tr><?
		}
		?>	
		<tr><td>Add Alias:</td>
			<td><input type="text" name="AddAlias" value="<?php echo htmlspecialchars($AddAlias)?>" size="40" maxlength="80"></td></tr>

		<input type="hidden" name="AliasCount" value="<?php echo $count?>">



<?	if ($Action == "Edit") {
?>			<tr><td colspan="2" class="tableheader">Administration</td></tr>
		<tr><td>Date Added:</td>
			<td><?php echo $DateAdded?></td></tr>
		<tr><td>Last Update:</td>
			<td><?php echo $LastUpdate?></td></tr>
		<tr><td>Last Update By:</td>
			<td><?php echo $LastUpdateBy?></td></tr>

<?
	}

?>	
	<tr><td></td>
		<td><input type="submit" name="submit" value="<?php echo $Action?> Person"></td></tr>
	</table>
	</form>
	</div>


<?
}

include 'includes/footer.php';
?>
