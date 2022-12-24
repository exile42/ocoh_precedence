<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble != 1) {
        ?><p>You do not have access to this file.</p><?php
        include("includes/footer.php");
        die;
}

  $err = ""; // no error messages yet

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
else
	$Action = "Add";
if (isset($_REQUEST['AwardID']))
	if (is_numeric($_REQUEST['AwardID']))
		$AwardID = $_REQUEST['AwardID'];
	else
		$err .= " That Award doesn't exist. ";
if (isset($_REQUEST['Name']))
	$Name = mysql_real_escape_string($_REQUEST['Name']);
if (isset($_REQUEST['RankID']))
	if (is_numeric($_REQUEST['RankID']))
		$RankID = $_REQUEST['RankID'];
	else
		$err .= " Invalid value for RankID. ";
if (isset($_REQUEST['Rank']))
	if (is_numeric($_REQUEST['Rank']))
		$Rank = $_REQUEST['Rank'];
	else
		$err .= " Invalid value for Rank. ";
if (isset($_REQUEST['Initials']))
	$Initials = mysql_real_escape_string($_REQUEST['Initials']);
if (isset($_REQUEST['Stylization']))
	$Stylization = mysql_real_escape_string($_REQUEST['Stylization']);
if (isset($_REQUEST['Closed']))
	if (is_numeric($_REQUEST['Closed']))
		$Closed = $_REQUEST['Closed'];
	else
		$err .= " Invalid value for Closed. ";
if (isset($_REQUEST['GivenFor']))
	$GivenFor = mysql_real_escape_string($_REQUEST['GivenFor']);
if (isset($_REQUEST['ShortFor']))
	$ShortFor = mysql_real_escape_string($_REQUEST['ShortFor']);
if (isset($_REQUEST['GiverType']))
	if (($_REQUEST['GiverType'] == "B") || ($_REQUEST['GiverType'] == "R") || ($_REQUEST['GiverType'] == "C"))
		$GiverType = $_REQUEST['GiverType'];
	else
		$err .= " Invalid value for GiverType. ";
if (isset($_REQUEST['GroupID']))
	if (is_numeric($_REQUEST['GroupID']))
		$GroupID = $_REQUEST['GroupID'];
	else
		$err .= " Invalid value for GroupID. ";
if (isset($_REQUEST['KingdomID']))
	if (is_numeric($_REQUEST['KingdomID']))
		$KingdomID = $_REQUEST['KingdomID'];
	else
		$err .= " Invalid value for KingdomID. ";
if (isset($_REQUEST['Blazon']))
	$Blazon = mysql_real_escape_string($_REQUEST['Blazon']);
if (isset($_REQUEST['Charter']))
	$Charter = mysql_real_escape_string($_REQUEST['Charter']);
if (isset($_REQUEST['Notes']))
	$Notes = mysql_real_escape_string($_REQUEST['Notes']);
if (isset($_REQUEST['Filename']))
	$Filename = mysql_real_escape_string($_REQUEST['Filename']);


	
?>

<div class="bodyheader"><?php echo $Action; ?> Award</div>
<br />
<br />
<?php
if ($Action == "Submit") { //they've submitted the form
	$err = ""; // no error messages yet
	//first check for errors
	if ($err != "") { //we got errors, make them work for it
    	?><p class="error"><?php echo $err; ?></p>"<?php
    	ShowForm();
	} else { //no errors, insert or update as needed
	
		//get Rank from wRanks based on the RankID
		$rankquery = "SELECT R.Rank FROM wRanks R WHERE RankID=$RankID";
		if ($results = $db->get_results($rankquery)) {
			foreach($results as $r) 
				$Rank		= $r->Rank;
		};

		if ($AwardID == 0) { //we're adding the award
			$insertquery="INSERT INTO wAwards (Name, RankID, Rank, Initials, Stylization, Closed, GivenFor, ShortFor, GiverType, GroupID, KingdomID, Blazon, Charter, Notes, Filename) 
			VALUES ('$Name', '$RankID', '$Rank', '$Initials', '$Stylization', '$Closed', '$GivenFor', '$ShortFor', '$GiverType', '$GroupID', '$KingdomID', '$Blazon', '$Charter', '$Notes', '$Filename')";
			if ($db->query($insertquery)) {
				?><p>Item added successfully.</p> <?php
			} else {
				?><p>ADD FAIL</p> <?php
			}

		} else { //we're updating
			$updatequery="UPDATE wAwards SET Name='$Name', RankID='$RankID', Rank='$Rank', Initials='$Initials', 
			Stylization='$Stylization', Closed='$Closed', GivenFor='$GivenFor', ShortFor='$ShortFor', GiverType='$GiverType', 
			GroupID='$GroupID', KingdomID='$KingdomID', Blazon='$Blazon', Charter='$Charter', Notes='$Notes', 
			Filename='$Filename' WHERE AwardID='$AwardID'";
			if ($db->query($updatequery)) {
				?><p>Item edited successfully.</p> <?php
			} else {
				?><p>EDIT FAIL</p> <?php
			}
		}
	} //end if error

} elseif ($Action == "Edit") { //they've chosen to edit an existing award, get the record from the db and showform
	$editquery = "SELECT A.* FROM wAwards A WHERE A.AwardID='$AwardID'";
	$r = $db->get_row($editquery);
	while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
		$$name = $value;
	};
	$GroupIDtrue = $GroupID;
	ShowForm();

} else { //they -will-be- adding, set ID = 0 and show form
	$Action ="Add";
	$AwardID = 0;
	ShowForm();
}//end if Action
?>

<?php function ShowForm() { 
	global $db, $AwardID, $Name, $RankID, $Rank, $Initials, $Stylization, $Closed, $GivenFor, $ShortFor, $GiverType, $GroupIDtrue, $KingdomID, $Blazon, $Charter, $Notes, $Filename, $MYKINGDOMID, $WEAREKINGDOM; 
	include("includes/constants.php");
?>
	<div class="form">
		<form action="add_award.php" method="post">
		<input type="hidden" name="Action" value="Submit" />
		<input type="hidden" name="AwardID" value="<?=$AwardID?>">
		<b>Name of Award/Order:</b><br />
		<input type="text" size="80" name="Name" class="formfield" value="<?=$Name?>" /><br />
        Do not include "Order of", "Award of", "Ordre de", etc. here.<br /><br />
		<b>Initials:</b><br />
		<input type="text" size="10" name="Initials" class="formfield" value="<?=$Initials?>" /><br /><br />
	  <b>Rank:</b><br />
		<select name="RankID">
		<?php $rankquery = "SELECT R.RankID, R.Rank, R.Description FROM wRanks R ORDER BY R.Rank DESC";
		if ($results = $db->get_results($rankquery)) {
			foreach($results as $r) { 
				$rRankID 	= $r->RankID;
				$rRank		= $r->Rank;
				$rDescription = $r->Description;
			  if ($RankID == $rRankID) { $s = "selected"; } else { $s = ""; }
			  ?><option value="<?php echo $rRankID;?>" <?php echo $s;?>><?php echo $rDescription;?> (<?php echo $rRank;?>)</option><?php
			};
		};?>
    </select><br /><br />
<b>Stylization:</b><br />
	<input type="text" size="80" name="Stylization" class="formfield" value="<?php echo $Stylization; ?>" /><br /><br />
<b>Award Status:</b><br />
	<input type="radio" name="Closed" value="1" <?php (($Closed) ? 'checked': ''); ?>> Closed 
	<input type="radio" name="Closed" value="0" <?php (($Closed) ? '': 'checked'); ?>>Open
	<br /><br />
<b>Given For:</b><br />
	<input name="GivenFor" type="text" class="formfield" size="80" maxlength="150"  value="<?php echo $GivenFor;?>" />
	<br /><br />
<b>Short form of the Given For (40 characters max):</b><br />
	<input name="ShortFor" type="text" class="formfield" size="40" maxlength="40"  value="<?=$ShortFor?>" /> (for example, "A&S", "Service", "Fighting")
	<br /><br />
<b>GiverType:</b><br />
	<select name="GiverType">
		<option value="B" <?=(($GiverType=='B')? 'selected': '') ?>> Both Ruler and Consort
		<option value="R" <?=(($GiverType=='R')? 'selected': '') ?>> Ruler Only
		<option value="C" <?=(($GiverType=='C')? 'selected': '') ?>> Consort Only
    </select><br /><br />
		<b>Kingdom:</b><br />
		<select name="KingdomID">
			<option value="<?=$MYKINGDOMID?>" selected><?=$WEAREKINGDOM?></option>
		<?php $kingdomquery = "SELECT K.KingdomID, K.Name FROM Kingdoms K ORDER BY CreationOrder";
		if ($results = $db->get_results($kingdomquery)) {
			foreach($results as $k) { 
				$kKingdomID	= $k->KingdomID;
				$kName		= $k->Name;
			  if ($kKingdomID == $KingdomID) { $s = "selected"; } else { $s = ""; }
			  ?><option value="<?=$kKingdomID?>" <?=$s?>><?=$kName?></option><?
			};
		};?>
    </select><br /><br />
		<b>Group:</b><br />
		<select name="GroupID">
		<?php $groupsinkingdomquery = "SELECT G.GroupID, G.Name FROM Groups G WHERE G.KingdomID=$MYKINGDOMID ORDER BY Name";
		if ($results = $db->get_results($groupsinkingdomquery)) {
			foreach($results as $r) { 
				$gGroupID	= $r->GroupID;
				$gName		= $r->Name;
				$kName		= KingdomNameFromGroupID($gGroupID, $db);
			  if ($gGroupID == $GroupIDtrue) { $s = "selected"; } else { $s = ""; }
			  ?><option value="<?=$gGroupID?>" <?=$s?>><?=$gName?> (<?=$kName?>)</option><?
			};
		};?>
		<?php $groupsoutofkingdomquery = "SELECT G.GroupID, G.Name FROM Groups G WHERE G.KingdomID!=$MYKINGDOMID ORDER BY Name";
		if ($results = $db->get_results($groupsoutofkingdomquery)) {
			foreach($results as $r) { 
				$gGroupID	= $r->GroupID;
				$gName		= $r->Name;
				$kName		= KingdomNameFromGroupID($gGroupID, $db);
			  if ($gGroupID == $GroupIDtrue) { $s = "selected"; } else { $s = ""; }
			  ?><option value="<?=$gGroupID?>" <?=$s;?>><?=$gName?> (<?=$kName?>)</option><?
			};
		};?>
    </select><br /><br />
		<b>Blazon:</b><br />
		<input name="Blazon" type="text" class="formfield" size="80" maxlength="200" value="<?=$Blazon?>" /><br /><br />
		<b>Filename (for badge):</b><br />
		<input name="Filename" type="text" class="formfield" size="50" maxlength="60"  value="<?=$Filename?>" />
		<br /><br />
		<b>Charter (filename thereof):</b><br />
		<input name="Charter" type="text" class="formfield" size="50" maxlength="100"  value="<?=$Charter?>" />
		<br /><br />
		<b>Charter Notes:</b><br />
		<input name="Notes" type="text" class="formfield" size="80" maxlength="150"value="<?=$Notes?>" /><br /><br />

		<input type="submit" class="button" value="Submit" />
		</form>
	</div>
<?
}; //end funtion ShowForm();
?>

<?
include("includes/footer.php");
?>
