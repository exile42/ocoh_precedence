<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble != 1) {
        ?><p>You do not have access to this file.</p><?
        include("includes/footer.php");
        die;
}

  $err = ""; // no error messages yet

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
if (isset($_REQUEST['Delete']))
	$Delete = $_REQUEST['Delete'];
else
	$Delete = "NOPE";
if (isset($_REQUEST['PersonID']))
	if (is_numeric($_REQUEST['PersonID']))
		$PersonID = $_REQUEST['PersonID'];
	else
		$err .= " That Person doesn't exist. ";
if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
else
	$Action = "List";

if (isset($_REQUEST['PersonID']))
	if (is_numeric($_REQUEST['PersonID']))
		$PersonID = $_REQUEST['PersonID'];
	else
		$err .= " That Person doesn't exist. ";
if (isset($_REQUEST['AwardID']))
	if (is_numeric($_REQUEST['AwardID']))
		$AwardID = $_REQUEST['AwardID'];
	else
		$err .= " That Honor doesn't exist. ";
if (isset($_REQUEST['AwardsGivenID']))
	if (is_numeric($_REQUEST['AwardsGivenID']))
		$AwardsGivenID = $_REQUEST['AwardsGivenID'];
	else
		$err .= " That instance of the Honor doesn't exist. ";
if (isset($_REQUEST['Award']))
	$Award = $_REQUEST['Award'];
if (isset($_REQUEST['ReignID']))
	if (is_numeric($_REQUEST['ReignID']))
		$ReignID = $_REQUEST['ReignID'];
	else
		$err .= " That Reign doesn't exist. ";
if (isset($_REQUEST['Date']))
	if (isdate($_REQUEST['Date']) && ($_REQUEST['Date']!="00.00.0000") && ($_REQUEST['Date']!="00/00/0000"))
		$Date = $_REQUEST['Date'];
	else
		$err .= " The Date is improperly formatted. ";
if (isset($_REQUEST['SortRank']))
	if (is_numeric($_REQUEST['SortRank']))
		$SortRank = $_REQUEST['SortRank'];
	else
		$err .= " There's a SortRank problem. ";
if (isset($_REQUEST['Resigned']))
	if (is_numeric($_REQUEST['Resigned']))
		$Resigned = $_REQUEST['Resigned'];
	else
		$err .= " The Resigned field isn't valid. ";
if (isset($_REQUEST['Premier']))
	if (is_numeric($_REQUEST['Premier']))
		$Premier = $_REQUEST['Premier'];
	else
		$err .= " The Premier field isn't valid. ";
if (isset($_REQUEST['AwardedBy']))
	$AwardedBy = mysql_real_escape_string($_REQUEST['AwardedBy']);
else
	$AwardedBy = "";
if (isset($_REQUEST['Verified']))
	$Verified = mysql_real_escape_string($_REQUEST['Verified']);
if (isset($_REQUEST['Visible']))
	if (is_numeric($_REQUEST['Visible']))
		$Visible = $_REQUEST['Visible'];
	else
		$err .= " The Visible field isn't valid. ";
if (isset($_REQUEST['ScrollGiven']))
	if (is_numeric($_REQUEST['ScrollGiven']))
		$ScrollGiven = $_REQUEST['ScrollGiven'];
	else
		$err .= " The ScrollGiven field isn't valid. ";
if (isset($_REQUEST['Scribe']))
	$Scribe = mysql_real_escape_string($_REQUEST['Scribe']);
if (isset($_REQUEST['Reason']))
	$Reason = mysql_real_escape_string($_REQUEST['Reason']);
if (isset($_REQUEST['Notes']))
	$Notes = mysql_real_escape_string($_REQUEST['Notes']);
if (isset($_REQUEST['SCAName']))
	$SCAName = mysql_real_escape_string($_REQUEST['SCAName']);

?>

<div class="bodyheader"><?=$Action?> Award</div>
<br />
<br />
<?

if ($Action == "Submit") { //they've submitted the form
  if ($err != "") { //we got errors, make them work for it
    ?><p class="error"><?=$err ?></p><?
    ShowForm();
  } else { //no errors, insert or update as needed
    $Date = format_date_nospace($Date);
		
		//get Rank from wRanks based on the RankID
		$rankquery = "SELECT A.Rank FROM wAwards A WHERE AwardID=$AwardID";
		if ($results = $db->get_results($rankquery)) {
			foreach($results as $r) 
				$Rank		= $r->Rank;
		};
		$SortRank = $Date*$Rank;
		$r = "";
		if ($Resigned || ($Visible==0)) { // HOWEVER if it's invisible or resigned, it gets an infinite SortRank instead
			$SortRank = 4294967295;
			$r = "(set to infinite because resigned or invisible)";
		}
		print "this honor: $SortRank $r";
		
		//get the Person's current SortRank
		$oldPersonSortRank = $db->get_var("SELECT MIN(SortRank) FROM wAwardsGiven WHERE PersonID='$PersonID'");

    if ($AwardsGivenID == 0) { //we're adding the award
			$insertquery = "INSERT INTO wAwardsGiven (PersonID, AwardID, ReignID, Date, SortRank, Resigned, Premier, AwardedBy, Verified, Visible, ScrollGiven, Scribe, Reason, Notes, DateAdded, LastUpdate, LastUpdateID) VALUES ('$PersonID', '$AwardID', '$ReignID', '$Date', '$SortRank', '$Resigned', '$Premier', '$AwardedBy', '$Verified', '$Visible', '$ScrollGiven', '$Scribe', '$Reason', '$Notes', '$Now', '$Now', '$LoggedUserID')";
			//echo "INSERT: $insertquery";
      $db->query($insertquery);
      $AwardsGivenID = $db->get_var("SELECT MAX(AwardsGivenID) FROM wAwardsGiven WHERE PersonID='$PersonID'");
    } else { //we're updating
      $editquery="UPDATE wAwardsGiven SET AwardID='$AwardID', ReignID='$ReignID', Date='$Date', SortRank='$SortRank', Resigned='$Resigned', Premier='$Premier', AwardedBy='$AwardedBy', Verified='$Verified', Visible='$Visible', ScrollGiven='$ScrollGiven', Scribe='$Scribe', Reason='$Reason', Notes='$Notes', LastUpdate='$Now', LastUpdateID='$LoggedUserID' WHERE AwardsGivenID='$AwardsGivenID'";
			//echo "EDIT: $editquery";
			$db->query($editquery);
    }

	// either way, we need to update the Person's overall SortRank
	// get their new SortRank
	$newPersonSortRank = $db->get_var("SELECT MIN(SortRank) FROM wAwardsGiven WHERE PersonID='$PersonID'");

	if ($newPersonSortRank < $oldPersonSortRank) {
		// update their sortrank
		$updatesortrankquery = "UPDATE wPeople SET SortRank=$newPersonSortRank WHERE PersonID=$PersonID";
		if ($results = $db->get_results($updatesortrankquery))
			echo "Person's SortRank updated successfully";
		// update their number?

	}// end update their sortrank

		
    ShowForm2();
  } //end Action == Submit
	
} elseif ($Action == "Submit2") { //they've chosen to edit an existing award-given, get the record from the db and showform
    if ($AwardedBy != "") {
      $db->query("UPDATE wAwardsGiven SET AwardedBy='$AwardedBy' WHERE AwardsGivenID='$AwardsGivenID'");
    }
    ?><p class="success">You have successfully updated the award for <?=stripslashes($SCAName)?></p>   
    <p><a href="edit_awards.php?PersonID=<?=$PersonID?>">Edit Awards for <?=$SCAName?></a><br />
        <a href="individual_record.php?PersonID=<?=$PersonID?>">View Individual Record for <?=$SCAName?></a></p><?

} elseif ($Action == "Edit") { //they've chosen to edit someone, get the record from the db and showform
  $r = $db->get_row("SELECT a.*, p.SCAName FROM wAwardsGiven a INNER JOIN wPeople p ON a.PersonID=p.PersonID WHERE a.PersonID='$PersonID' AND a.AwardsGivenID='$AwardsGivenID'");
  while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
    $$name = $value;
  };
  $LastUpdateBy   = $db->get_var("SELECT Username FROM Users WHERE UserID='$LastUpdateID'");
  $Date = format_date_no_time($Date);
  ShowForm();
	
} elseif($Action == "Delete") { //they're adding, set ID = 0 and show form
?>  <p>Are you sure you want to delete <?=$Award?> from the profile?</p>
  <div align="center">
  <form action="edit_awards.php" method="post">
  <input type="hidden" name="Action" value="deleteit">
  <input type="hidden" name="PersonID" value="<?=$PersonID?>">
  <input type="hidden" name="AwardsGivenID" value="<?=$AwardsGivenID?>">
  <input type="hidden" name="Award" value="<?=$Award?>">
  <input type="submit" name="submit" value="DELETE">
  </form>

  <form action="edit_awards.php" method="post">
  <input type="hidden" name="PersonID" value="<?=$PersonID?>">
  <input type="submit" name="submit" value="CANCEL">
  </form>
  </div>
  <?  

} elseif($Action == "deleteit") { 
  $db->query("DELETE QUICK FROM wAwardsGiven WHERE AwardsGivenID='$AwardsGivenID'");

	// update their sortrank
	$newPersonSortRank = $db->get_var("SELECT MIN(SortRank) FROM wAwardsGiven WHERE PersonID='$PersonID'");
	$updatesortrankquery = "UPDATE wPeople SET SortRank=$newPersonSortRank WHERE PersonID=$PersonID";
	if ($results = $db->get_results($updatesortrankquery))
		echo "Person's SortRank updated successfully";
	// update their number?

  ?><p>You have successfully deleted <?=$Award?>.</p><?
  ListAwards();

} elseif($Action == "Add") { //they're adding, set ID = 0 and show form
  $AwardsGivenID = 0;
  $Visible = 1; //guess that the new award will be visible
  $ScrollGiven = 1; //guess that the new award got a scroll
  $Premier = 0; // guess that they are not the premier of the order
  $SCAName = $db->get_var("SELECT SCAName FROM wPeople WHERE PersonID='$PersonID'");
  ShowForm();
	
} else {
  if ($PersonID != "") {
    $SCAName = $db->get_var("SELECT SCAName FROM wPeople WHERE PersonID='$PersonID'");
    ListAwards();
  }
}//end if Action



Function ShowForm2() {
  global $db, $PersonID, $Action, $AwardsGivenID, $AwardID, $ReignID, $Date, $Resigned, $AwardedBy, $Verified, $Visible, $ScrollGiven, $Scribe, $Reason, $Notes, $DateAdded, $LastUpdate, $LastUpdateBy, $SCAName;
  $SCAName = stripslashes($SCAName);
  $GiverType = $db->get_var("SELECT GiverType FROM wAwards WHERE AwardID='$AwardID'");
  $AwardedBy = $db->get_var("SELECT AwardedBy FROM wAwardsGiven WHERE AwardsGivenID='$AwardsGivenID'");
?>  
  <form action="edit_awards.php" method="post">
  <input type="hidden" name="Action" value="Submit2">
  <input type="hidden" name="PersonID" value="<?=$PersonID?>">
  <input type="hidden" name="SCAName" value="<?=htmlspecialchars($SCAName)?>">
  <input type="hidden" name="AwardsGivenID" value="<?=$AwardsGivenID?>">
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550" class="edittable">
  <tr><td colspan="2" class="tableheader">Add Award for <?=$SCAName?></td></tr>
  <tr><td>Awarded By:</td>
    <td><select name="AwardedBy">
      <option value="">SELECT ONE</option><?
    if ($GiverType == "B") {
      $results = $db->get_results("SELECT DISTINCT RUseName FROM wReigns WHERE RUseName !='' ORDER BY RUseName"); 
      foreach($results as $r) {
        $RUseName = $r->RUseName;
        if ($RUseName == $AwardedBy) { $s = "selected"; } else { $s = ""; }
        ?><option value="<?=$RUseName?>" <?=$s?>><?=$RUseName?></option><?
      }
      $results = $db->get_results("SELECT DISTINCT CUseName FROM wReigns WHERE CUseName !='' ORDER BY CUseName"); 
      foreach($results as $r) {
        $CUseName = $r->CUseName;
        if ($CUseName == $AwardedBy) { $s = "selected"; } else { $s = ""; }
        ?><option value="<?=$CUseName?>" <?=$s?>><?=$CUseName?></option><?
      }

    } elseif ($GiverType == "C") {
      $results = $db->get_results("SELECT DISTINCT CUseName FROM wReigns WHERE CUseName !='' ORDER BY CUseName"); 
      foreach($results as $r) {
        $CUseName = $r->CUseName;
        if ($CUseName == $AwardedBy) { $s = "selected"; } else { $s = ""; }
        ?><option value="<?=$CUseName?>" <?=$s?>><?=$CUseName?></option><?
      }

    } elseif ($GiverType == "R") {
      $results = $db->get_results("SELECT DISTINCT RUseName FROM wReigns WHERE RUseName !='' ORDER BY RUseName"); 
      foreach($results as $r) {
        $RUseName = $r->RUseName;
        if ($RUseName == $AwardedBy) { $s = "selected"; } else { $s = ""; }
        ?><option value="<?=$RUseName?>" <?=$s?>><?=$RUseName?></option><?
      }

    }
    
    
    ?>
    </select></td></tr>

  <tr><td></td>
    <td><input type="submit" name="submit" value="Submit"></td></tr>
  </table>
  </div>
  </form>
  </div>


<?
}
///



Function ShowForm() {
  global $db, $SCAName, $PersonID, $Action, $AwardsGivenID, $AwardID, $ReignID, $Date, $Resigned, $Premier, $AwardedBy, $Verified, $Visible, $ScrollGiven, $Scribe, $Reason, $Notes, $DateAdded, $LastUpdate, $LastUpdateBy;
  global $SOCIETYKINGDOMID, $SOCIETYGROUPID, $MYKINGDOMID, $MYGROUPID;
?>  
  <form action="edit_awards.php" method="post">
  <input type="hidden" name="Action" value="Submit">
  <input type="hidden" name="PersonID" value="<?=$PersonID?>">
  <input type="hidden" name="SCAName" value="<?=htmlspecialchars($SCAName)?>">
  <input type="hidden" name="AwardsGivenID" value="<?=$AwardsGivenID?>">
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550" class="edittable">
  <tr><td colspan="2" class="tableheader"><?=$Action?> Award for <?=$SCAName?></td></tr>
  <tr><td>Award:</td>
    <td>
    <select name="AwardID">
    <option value="">SELECT ONE</option>
    <? 
	// sca-wide things
    $results = $db->get_results("SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, A.Closed FROM wAwards A WHERE ( A.KingdomID = $SOCIETYKINGDOMID OR A.GroupID = $SOCIETYGROUPID)  ORDER BY A.Name");
    foreach($results as $r) {
      $thisAID = $r->AwardID;
      $AName   = $r->Name;
      if ($AwardID == $thisAID) { $s = "selected"; } else { $s = ""; }
      ?><option value="<?=$thisAID?>" <?=$s?>><?=$AName?></option><?
    }
	// our kingdom things
    $results = $db->get_results("SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, A.Closed FROM wAwards A WHERE A.KingdomID = $MYKINGDOMID AND A.GroupID = $MYGROUPID ORDER BY A.Name");
    foreach($results as $r) {
      $thisAID = $r->AwardID;
      $AName   = $r->Name;
      if ($AwardID == $thisAID) { $s = "selected"; } else { $s = ""; }
      ?><option value="<?=$thisAID?>" <?=$s?>><?=$AName?></option><?
    }
	// our kingdom subgroups
    $results = $db->get_results("SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, A.Closed FROM wAwards A WHERE A.KingdomID = $MYKINGDOMID AND (A.GroupID != $MYGROUPID OR A.GroupID IS NULL)  ORDER BY A.Name");
    foreach($results as $r) {
      $thisAID = $r->AwardID;
      $AName   = $r->Name;
      $AGroup   = GroupNameFromGroupID($r->GroupID, $db);
      if ($AwardID == $thisAID) { $s = "selected"; } else { $s = ""; }
      ?><option value="<?=$thisAID?>" <?=$s?>><?=$AGroup;?>: <?=$AName?></option><?
    } 
	// out-kingdom things
    $results = $db->get_results("SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, A.Closed FROM wAwards A WHERE A.KingdomID != $MYKINGDOMID AND A.KingdomID != $SOCIETYKINGDOMID AND (A.GroupID != $MYGROUPID OR A.GroupID IS NULL)  ORDER BY A.KingdomID, A.GroupID, A.Name");
    foreach($results as $r) {
      $thisAID = $r->AwardID;
      $AName   = $r->Name;
      $AKingdom   = KingdomNameFromKingdomID($r->KingdomID, $db);
      $AGroup   = GroupNameFromGroupID($r->GroupID, $db);
      if ($AwardID == $thisAID) { $s = "selected"; } else { $s = ""; }
      ?><option value="<?=$thisAID?>" <?=$s?>><?=$AKingdom;?>: <?=$AGroup;?>: <?=$AName?></option><?
    }
     ?>
     </select>
     </td></tr>
  <tr><td>Reign:</td>
    <td>
    <select name="ReignID">
    <? 
    $results = $db->get_results("SELECT ReignID, RUseName, CUseName FROM wReigns WHERE GroupID=96 ORDER BY ReignOrder DESC");
    foreach($results as $r) {
      $thisRID = $r->ReignID;
      $Ruler   = $r->RUseName;
      $Consort = $r->CUseName;
      if ($ReignID == $thisRID) { $s = "selected"; } else { $s = ""; }
      ?><option value="<?=$thisRID?>" <?=$s?> ><?=$Ruler?> and <?=$Consort?></option><?
    }
    if ($ReignID == 60) { $s = "selected"; } else { $s = ""; }
     ?>
    <option value="60" <?=$s?>>Unknown reign</option>
     </select>
     </td></tr>
  <tr><td>Date:</td>
    <td><input type="text" name="Date" value="<?=$Date?>" size="10" maxlength="10"></td></tr>
  <tr><td></td>
    <td>(MM/DD/YY)</td></tr>
  <tr><td valign="top">Reason Given:</td>
    <td><input type="text" name="Reason" value="<?=$Reason?>" size="30" maxlength="30"><br>
	For Awards with multiple options such as Trefoil, Militant Bard, Treasured Jewels</td></tr>

  <tr><td>Premier:</td>
    <td><? WriteYNDropDown($Premier, "Premier"); ?></td></tr>
  <tr><td>Resigned:</td>
    <td><? WriteYNDropDown($Resigned, "Resigned"); ?></td></tr>
  <tr><td>Verified:</td>
    <td><input type="text" name="Verified" value="<?=htmlspecialchars($Verified)?>" size="50" maxlength="50"></td></tr>
  <tr><td>Visible:</td>
    <td><? WriteYNDropDown($Visible, "Visible"); ?></td></tr>

  <tr><td>Scroll Given:</td>
    <td><? WriteYNDropDown($ScrollGiven, "ScrollGiven"); ?></td></tr>
  <tr><td>Scribe/Illuminator:</td>
    <td><input type="text" name="Scribe" value="<?=$Scribe?>" size="60" maxlength="60"></td></tr>

  <tr><td valign="top">Notes:</td>
    <td><textarea cols="48" rows="10" name="Notes"><?=htmlspecialchars($Notes)?></textarea></td></tr>

<?  if ($Action == "Edit") {?>
    <tr><td colspan="2" class="tableheader">Administration</td></tr>
    <tr><td>Date Added:</td>
      <td><?=$DateAdded?></td></tr>
    <tr><td>Last Update:</td>
      <td><?=$LastUpdate?></td></tr>
    <tr><td>Last Update By:</td>
      <td><?=$LastUpdateBy?></td></tr>
<?  }  ?>  
  <tr><td></td>
    <td><input type="submit" name="submit" value="Next &raquo;"></td></tr>
  </table>
  </div>
  </form>
<?
}

Function ListAwards() {
  global $db, $PersonID;
?>
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550">
  <tr class="tableheader"><td>Award</td><td>Action</td></tr>
<?
  if ($results = $db->get_results("SELECT g.AwardsGivenID, a.Name FROM wAwards a
    INNER JOIN wAwardsGiven g
    ON a.AwardID=g.AwardID
    WHERE g.PersonID='$PersonID'")) {
    foreach($results as $r) {
      $AwardsGivenID  = $r->AwardsGivenID;
      $Award      = $r->Name;
      ?><tr><td><?=$Award?></td>
            <td><a href="edit_awards.php?Action=Edit&PersonID=<?=$PersonID?>&AwardsGivenID=<?=$AwardsGivenID?>">EDIT</a> | 
            <a href="edit_awards.php?Action=Delete&PersonID=<?=$PersonID?>&AwardsGivenID=<?=$AwardsGivenID?>&Award=<?=$Award?>">DELETE</a></td></tr><?
    
    } //end foreach
  } else {
    ?><tr><td colspan="2" align="center">
      No current awards entered.
    </td></tr><?
    
  }
  ?><tr><td colspan="2" align="center">
    <a href="edit_awards.php?Action=Add&PersonID=<?=$PersonID?>">ADD AWARD</a>
  </td></tr></table></div><?
} //end listawards

include("includes/footer.php");
?>
