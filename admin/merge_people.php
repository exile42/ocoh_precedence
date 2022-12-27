<?
include '../includes/constants.php';
include '../includes/ez_sql.php';
include '../includes/functions.php';
include '../includes/header.php';

if ($accessWimble != 1) {
        ?><p>You do not have access to this file.</p><?
        include '../includes/footer.php';
        die;
}

$err = ""; // no error messages yet

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
else
	$Action = "";
if (isset($_REQUEST['AcceptingPersonID']))
	if (is_numeric($_REQUEST['AcceptingPersonID']))
		$AcceptingPersonID = $_REQUEST['AcceptingPersonID'];
	else
		$err .= " Invalid value for AcceptingPersonID (the primary-to-be person); surprising, since that's a pulldown menu! ";
if (isset($_REQUEST['MergingPersonID']))
	if (is_numeric($_REQUEST['MergingPersonID']))
		$MergingPersonID = $_REQUEST['MergingPersonID'];
	else
		$err .= " Invalid value for MergingPersonID (the secondary-to-be person); surprising, since that's a pulldown menu! ";

// check the Heraldry table to see if we're going to have more than 2 Devices.
$query= "SELECT HeraldryID FROM wHeraldry WHERE (PersonID='$MergingPersonID' OR PersonID='$AcceptingPersonID') AND Type LIKE '%Device%'";
$results = $db->query($query);
//echo $query;
if ($results >=2) {
	$err .= "The resulting Person would have two or more devices.  This must be resolved before the merge can proceed. ";	
}

	
?>

<div class="bodyheader"><?php echo $Action?> Merge People</div>
<br />
<br />
<?
if ($Action == "Merge") { //they've confirmed the merge
	//first check for errors
	if ($err != "") { //we got errors, make them work for it
    	?><p class="error"><?php echo $err?></p>"<?
    	ShowForm();
	} else { //no errors, insert or update as needed
		$Accepting = SCANameFromPersonID($AcceptingPersonID, $db);
		$Merging = SCANameFromPersonID($MergingPersonID, $db);

		// check the Reigns table
		// AWKWARDLY, I need to do two sweeps, one for each possible PersonID location 
		if ($results = $db->get_results("SELECT ReignID FROM wReigns WHERE RPersonID='$MergingPersonID'")) {
				$updatequeryruler="UPDATE wReigns SET RPersonID=$AcceptingPersonID WHERE RPersonID='$MergingPersonID'";
				//echo $updatequeryruler;
				//Do the update
				if ($db->query($updatequeryruler) >= 0) {
					?><p class="success">Reign for <?php echo $Merging?> (as Ruler) has been merged into <?php echo $Accepting?> (the name has not changed, only the ID number the name points to)</p><?
					$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'merged Reign as Ruler for $Merging into $Accepting u: $Username', '$IPAddress')");
				} else {
					?> <p>Attempted merge of reigns (as Ruler) failed. :( NOTE TO SYSADMIN: <?php echo $updatequeryruler?></p><?
					$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED merged Reign as Ruler for $Merging into $Accepting u: $Username', '$IPAddress')");
				} // end if update query for reigns as ruler

		} // end if results of reigns query NO ELSE because if no reign as ruler nothing to merge
		if ($results = $db->get_results("SELECT ReignID FROM wReigns WHERE CPersonID='$MergingPersonID'")) {
				$updatequeryconsort="UPDATE wReigns SET CPersonID=$AcceptingPersonID WHERE CPersonID='$MergingPersonID'";
//				echo $updatequeryconsort;
				//Do the update
				if ($db->query($updatequeryconsort) >= 0) {
					?><p class="success">Reigns for <?php echo $Merging?> (as Consort) have been merged into <?php echo $Accepting?> (the name has not changed, only the ID number the name points to)</p><?
					$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'merged Reign as Consort for $Merging into $Accepting u: $Username', '$IPAddress')");
				} else {
					?> <p>Attempted merge of reigns (as Consort) failed. :( NOTE TO SYSADMIN: <?php echo $updatequeryconsort?></p><?
					$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED merged Reign as Consort for $Merging into $Accepting u: $Username', '$IPAddress')");
				} // end if update query for reigns as consort

		} // end if results of reigns query NO ELSE because if no reign as consort nothing to merge

/////////////		
		// check the Heraldry table
			if ($results = $db->get_results("SELECT HeraldryID FROM wHeraldry WHERE PersonID='$MergingPersonID'")) {
					$updatequeryheraldry="UPDATE wHeraldry SET PersonID=$AcceptingPersonID WHERE PersonID='$MergingPersonID'";
//					echo $updatequeryheraldry;
					//Do the update
					if ($db->query($updatequeryheraldry) >= 0) {
						?><p class="success">Heraldry for <?php echo $Merging?> has been merged into <?php echo $Accepting?></p><?
						$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'merged Heraldry for $Merging into $Accepting u: $Username', '$IPAddress')");
					} else {
						?> <p>Attempted merge of heraldry failed. :( NOTE TO SYSADMIN: <?php echo $updatequeryheraldry?></p><?
						$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED merged Heraldry for $Merging into $Accepting u: $Username', '$IPAddress')");
					} // end if update query for heraldry

			} // end if results of heraldry query NO ELSE because if no heraldry nothing to merge
		
		// check the Aliases table
			if ($results = $db->get_results("SELECT AliasID FROM wAliases WHERE PersonID='$MergingPersonID'")) {
					$updatequeryaliases="UPDATE wAliases SET PersonID=$AcceptingPersonID WHERE PersonID='$MergingPersonID'";
//					echo $updatequeryaliases;
					//Do the update
					if ($db->query($updatequeryaliases) >= 0) {
						?><p class="success">Aliases for <?php echo $Merging?> have been merged into <?php echo $Accepting?></p><?
						$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'merged Aliases for $Merging into $Accepting u: $Username', '$IPAddress')");
					} else {
						?> <p>Attempted merge of <?php echo $Merging?>'s aliases failed. :( NOTE TO SYSADMIN: <?php echo $updatequeryaliases?></p><?
						$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED merged Aliases for $Merging into $Accepting u: $Username', '$IPAddress')");
					} // end if update query for aliases

			} // end if results of alias query NO ELSE because if no aliases nothing to merge
		
		// check the AwardsGiven table
			if ($results = $db->get_results("SELECT AwardsGivenID FROM wAwardsGiven WHERE PersonID='$MergingPersonID'")) {
					$updatequeryawards="UPDATE wAwardsGiven SET PersonID=$AcceptingPersonID WHERE PersonID='$MergingPersonID'";
//					echo $updatequeryawards;
					//Do the update
					if ($db->query($updatequeryawards) >= 0) {
						?><p class="success">AwardsGiven for <?php echo $Merging?> have been merged into <?php echo $Accepting?></p><?
						$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'merged AwardsGiven for $Merging into $Accepting u: $Username', '$IPAddress')");
					} else {
						?> <p>Attempted merge of awards given failed. :( NOTE TO SYSADMIN: <?php echo $updatequeryawards?></p><?
						$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED merged AwardsGiven for $Merging into $Accepting u: $Username', '$IPAddress')");
					} // end if update query for AwardsGiven

			} // end if results of AwardsGiven query NO ELSE because if no awards given nothing to merge
		
		// add the old name as an Alias
		// but first, we're going to need some information  (for version 3, also get IsRegistered and RegistrationDate
		$infoquery="SELECT SearchName FROM wPeople WHERE PersonID = $MergingPersonID";
		$inforesults = $db->get_results($infoquery);
		if ($inforesults) {
			foreach ($inforesults as $m){
				while(list($name,$value) = each($m)) { //name all values
				$MergingSearchName		= $m->SearchName;
				};
			}// end foreach
		} // end if inforesults
				
		// now we can do the thing with the aliases
		$addaliasquery = "INSERT INTO wAliases (PersonID, Alias, SearchAlias) VALUES ('$AcceptingPersonID', '$Merging', '$MergingSearchName')";
//		echo $addaliasquery;
		if ($results = $db->query($addaliasquery)) {
			?><p class="success"><?php echo $Merging?> was added as an alias to <?php echo $Accepting?>.<?
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'added adding $Merging as alias $Accepting u: $Username', '$IPAddress')");
		} else {
			?> Something went wrong and <?php echo $Merging?> FAILED to be added as an alias to <?php echo $Accepting?>. :( <?
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED adding $Merging as alias $Accepting u: $Username', '$IPAddress')");
		} // end if aliasquery

		
		// delete the old Person
		$deletequery = "DELETE QUICK FROM wPeople WHERE PersonID='$MergingPersonID'";
//		echo $deletequery;
		if ($results = $db->query($deletequery)) {
			?><p class="success"><?php echo $Merging?>'s Person record was deleted.<?
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'SUCCESS deleting $Merging Person record (merging into $Accepting) u: $Username', '$IPAddress')");
		} else {
			?> Something went wrong and <?php echo $Merging?>'s Person record FAILED to be deleted. :( <?
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES  ('$Now', '$UserID', 'FAILED deleting $Merging Person record (merging into $Accepting) u: $Username', '$IPAddress')");
		} // end if deletequery

	} //end if error

} elseif ($Action == "Confirm") { //they've requested a merge, we need them to confirm
	//first check for errors
	if ($err != "") { //we got errors, make them work for it
    	?><p class="error"><?php echo $err?></p>"<?
    	ShowForm();
	} else { //no errors, but get them to confirm the merge
		$Accepting = SCANameFromPersonID($AcceptingPersonID, $db);
		$Merging = SCANameFromPersonID($MergingPersonID, $db);
		?>
		<div class="form">
			<form action="merge_people.php" method="post">
			<input type="hidden" name="Action" value="Merge">
            <input type="hidden" name="AcceptingPersonID" value="<?php echo $AcceptingPersonID?>">
            <input type="hidden" name="MergingPersonID" value="<?php echo $MergingPersonID?>">
            Do you really want to merge all of (<?php echo $MergingPersonID?>) <?php echo $Merging?>'s records into (<?php echo $AcceptingPersonID?>) <?php echo $Accepting?> 's records?  (<?php echo $MergingPersonID?>) <?php echo $Merging?> will still show up as an alias for (<?php echo $AcceptingPersonID?>) <?php echo $Accepting?> (you can delete it manually later if absolutely necessary)?
            <strong>THIS CANNOT BE UNDONE.</strong>
            <br />
            <br />
            <br />
            <input type="submit" class="button" value="Confirm Merge" />
            </p>
			</form>
		</div>
		<?
	} //end if error

} else { //they need to pick who is getting merged, show form
	$Action ="";
	ShowForm();
}//end if Action
?>

<?php function ShowForm() { 
	global $db, $AcceptingPersonID, $MergingPersonID; 
?>
	<div class="form">
		<form action="merge_people.php" method="post">
		<input type="hidden" name="Action" value="Confirm">
		  <p><b>Person ACCEPTING the merge (all records will be pointed at THIS ONE):</b><br />
		<select name="AcceptingPersonID">
			<option value="BADDATA" selected>Select the Person</option>
		<?php $acceptingquery = "SELECT PersonID, SCAName, SearchName FROM wPeople ORDER BY SearchName";
		if ($results = $db->get_results($acceptingquery)) {
			foreach($results as $a) { 
				$aPersonID	= $a->PersonID;
				$aSCAName		= $a->SCAName; 
			  ?><option value="<?php echo $aPersonID?>"><?php echo $aPersonID?>: <?php echo $aSCAName?></option><?
			};
		};?>
    	</select>
		    <br />
	      </p>
		  <p><b>Person BEING merged (will disappear except as alias of other person):</b><br />
            <select name="MergingPersonID">
                <option value="BADDATA" selected>Select the Person</option>
            <?php $mergingquery = "SELECT PersonID, SCAName, SearchName FROM wPeople ORDER BY SearchName";
            if ($results = $db->get_results($mergingquery)) {
                foreach($results as $m) { 
                    $mPersonID	= $m->PersonID;
                    $mSCAName		= $m->SCAName; 
                  ?><option value="<?php echo $mPersonID?>"><?php echo $mPersonID?>: <?php echo $mSCAName?></option><?
                };
            };?>
            </select>
            <br />
	        <br />
		    <br />
		    
	        <input type="submit" class="button" value="Check Merge" />
	        </p>
	  </form>
	</div>
<?
}; //end funtion ShowForm();
?>

<?
include '../includes/footer.php';
?>
