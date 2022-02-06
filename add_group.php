<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble != 1) {        
	?> <p>You do not have access to this file.</p> <?php
        include("includes/footer.php");
        die;
}

  $err = ""; // no error messages yet

if (isset($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
if (isset($_REQUEST['GroupID']))
	if (is_numeric($_REQUEST['GroupID'])) 
		$GroupID = $_REQUEST['GroupID'];
	else
		$err .= " Group has an invalid value";
if (isset($_REQUEST['KingdomID']))
	if (is_numeric($_REQUEST['KingdomID'])) 
		$KingdomID = $_REQUEST['KingdomID'];
	else
		$err .= " KingdomID has an invalid value";
if (isset($_REQUEST['ParentGroupID']))
	if (is_numeric($_REQUEST['ParentGroupID'])) 
		$ParentGroupID = $_REQUEST['ParentGroupID'];
	else
		$err .= " ParentGroup has an invalid value";
if (isset($_REQUEST['Name']))
	$Name = mysql_real_escape_string($_REQUEST['Name']);
if (isset($_REQUEST['Designator']))
	$Designator = mysql_real_escape_string($_REQUEST['Designator']);
if (isset($_REQUEST['BaronyOrder']))
	$BaronyOrder = mysql_real_escape_string($_REQUEST['BaronyOrder']);
if (isset($_REQUEST['Active']))
	if (is_numeric($_REQUEST['Active'])) 
		$Active = $_REQUEST['Active'];
	else
		$err .= " Active has an invalid value";
if (isset($_REQUEST['Incipient']))
	if (is_numeric($_REQUEST['Incipient'])) 
		$Incipient = $_REQUEST['Incipient'];
	else
		$err .= " Incipient has an invalid value";
if (isset($_REQUEST['Notes']))
	$Notes = mysql_real_escape_string($_REQUEST['Notes']);
if (isset($_REQUEST['RegName']))
	$RegName = $_REQUEST['RegName'];
if (isset($_REQUEST['RegDevice']))
	$RegDevice = $_REQUEST['RegDevice'];
if (isset($_REQUEST['Blazon']))
	$Blazon = mysql_real_escape_string($_REQUEST['Blazon']);
if (isset($_REQUEST['Filename']))
	$Filename = mysql_real_escape_string($_REQUEST['Filename']);

?>
<div class="bodyheader">
  <?php=$Action?>
  Group</div>
<br />
<?php
if ($Action == "Submit") { //they've submitted the form
	$err = ""; // no error messages yet
	//first check for errors
	if ($err != "") { //we got errors, make them work for it
    	?><p class="error">
		<?php=$err?></p><?php
		$GroupIDtrue = $GroupID;
		$ParentGroupIDtrue = $ParentGroupID;
    	ShowForm();

	} else { //no errors, insert or update as needed
		if ($BaronyOrder=='') { $BaronyOrder="NULL"; } else { $BaronyOrder="'$BaronyOrder'"; }
		if ($Notes=='') { $Notes="NULL"; } else { $Notes="'$Notes'"; }
		if ($Blazon=='') { $Blazon="NULL"; } else { $Blazon="'$Blazon'"; }
		if ($Filename=='') { $Filename="NULL"; } else { $Filename="'$Filename'"; }
		if ($GroupID == 0) { //we're adding a completely new group
			$insertquery="INSERT INTO Groups (GroupID, KingdomID, ParentGroupID, Name, Designator, BaronyOrder, Active, Incipient, Notes, RegName, RegDevice, Blazon, Filename) 
			VALUES ('$GroupID', '$KingdomID', '$ParentGroupID', '$Name', '$Designator', $BaronyOrder, '$Active', '$Incipient', $Notes, '$RegName', '$RegDevice', $Blazon, $Filename)";
			if ($db->query($insertquery)) {
				?><p> <?php=$insertquery?>: Item added successfully.</p><?php
			} else {
				?><p>ADD FAIL</p><?php
			}
		} else { //we're updating
			$updatequery="UPDATE Groups SET KingdomID='$KingdomID', ParentGroupID='$ParentGroupID', Name='$Name', 
			Designator='$Designator', BaronyOrder=$BaronyOrder, Active='$Active', Incipient='$Incipient', 
			Notes=$Notes, RegName='$RegName', RegDevice='$RegDevice', Blazon=$Blazon, Filename=$Filename
			 WHERE GroupID='$GroupID'";
			if ($db->query($updatequery)) {
				?><p>Item edited successfully.</p><?php
			} else {
				?><p>EDIT GROUP FAIL</p><?php
			}
		}// end else updating
	} //end if error/action is submit

} elseif ($Action == "Edit") { //they've chosen to edit an existing group, get the record from the db and showform
	$editquery = "SELECT G.* FROM Groups G WHERE G.GroupID='$GroupID'";
//echo $editquery;
	$r = $db->get_row($editquery);
	while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
		$$name = $value;
	};
	$GroupIDtrue = $GroupID;
	$ParentGroupIDtrue = $ParentGroupID;
	ShowForm();

} else { //they are adding a completely new group, set ID = 0 and show form
	$Action ="Add";
	$Active = 1;
	$Incipient = 0;
	$GroupID = 0;
	$ParentGroupIDtrue = $MYGROUPID;
	ShowForm();

}//end if Action


function ShowForm() { 
	global $db, $GroupID, $KingdomID, $ParentGroupID, $GroupIDtrue, $ParentGroupIDtrue, $Name, $Designator, $BaronyOrder, $Active, $Incipient, $Notes, $RegName, $RegDevice, $Blazon, $Filename, $MYKINGDOMID, $WEAREKINGDOM; ?>
<div class="form">
  <form action="add_group.php" method="post">
    <input type="hidden" name="Action" value="Submit" />
    <input type="hidden" name="GroupID" value="<?php=$GroupIDtrue?>">
    <b>Group Name:</b>
    <input type="text" size="80" name="Name" class="formfield" value="<?php=$Name?>" />
    <br />
    <br />
    <b>Designator:</b>
    <select name="Designator">
      <option value="<?php=$Designator?>"> <?php=$Designator?>  
      <option value="Shire"> Shire
      <option value="Canton"> Canton
      <option value="College"> College
      <option value="Barony"> Barony
      <option value="Province"> Province
      <option value="Principality"> Principality
      <option value="Kingdom"> Kingdom
      <option value="Riding"> Riding
      <option value="Stronghold"> Stronghold
      <option value="Port"> Port
      <option value="Crown Principality"> Crown Principality (transitioning to full Principality)
      <option value="March"> March (may be archaic)
      <option value="Freehold"> Freehold (archaic)
      <option value="Barony-Marche"> Barony-Marche (acts like a barony)
      <option value="Crown Province"> Crown Province (acts like a barony, has viceroy/vicereine)
      <option value="Dominion"> Dominion (acts like a shire)
      <option value="Shire-March"> Shire-March (acts like a shire)
    </select>
    <br />
    <br />
    <b>Status (Activity):</b>
	<input type="radio" name="Active" value="1" <?php=(($Active) ? 'checked': '') ?>> Active 
	<input type="radio" name="Active" value="0" <?php=(($Active) ? '': 'checked') ?>> Inactive/Disbanded
	<br />
    <br />
    <b>Status (Incipiency):</b>
	<input type="radio" name="Incipient" value="1" <?php=(($Incipient) ? 'checked': '') ?>> Incipient 
	<input type="radio" name="Incipient" value="0" <?php=(($Incipient) ? '': 'checked') ?>> Fully recognized group
	<br /> Note that there is no option for "proto-" groups until they are actually incipient.
	<br />
    <br />
	<b>Kingdom:</b>  
	<select name="KingdomID">
		<option value="<?php=$MYKINGDOMID?>" selected><?php=$WEAREKINGDOM?></option>
	<?php $kingdomquery = "SELECT K.KingdomID, K.Name FROM Kingdoms K ORDER BY K.Name";
	if ($results = $db->get_results($kingdomquery)) {
		foreach($results as $k) { 
			$kKingdomID	= $k->KingdomID;
			$kName		= $k->Name;
		  if ($kKingdomID == $KingdomID) { $s = "selected"; } else { $s = ""; }
		  ?><option value="<?php=$kKingdomID?>" <?php=$s?>><?php=$kName?></option>
		  <?php
		};
	};
	?>
    </select>
    <br />
    <br />
	<b>Parent Group: </b> 
	<select name="ParentGroupID">
	<option value="">SELECT ONE IF YOU KNOW IT</option><?php
	$mykingdomgroupquery = "SELECT G.GroupID, G.Name FROM Groups G WHERE G.KingdomID = $MYKINGDOMID AND (Designator LIKE '%Kingdom%' OR Designator LIKE '%Principality%' OR Designator LIKE '%Barony%' OR Designator LIKE '%Province%') ORDER BY Name";
	if ($results = $db->get_results($mykingdomgroupquery)) {
		foreach($results as $r) { 
			$gParentGroupID	= $r->GroupID;
			$gName		= $r->Name;
			if ($gParentGroupID == $ParentGroupIDtrue) { $s = "selected"; } else { $s = ""; }
			?><option value="<?php=$gParentGroupID?>" <?php=$s?>><?php=$WEAREKINGDOM?>: <?php=$gName?></option>
			<?php
		}; // end foreach
	}; // end in-kingdom query action
	$otherkingdomgroupquery = "SELECT G.GroupID, G.KingdomID, G.Name, K.KingdomID, K.Name as KName FROM Groups G, Kingdoms K WHERE G.KingdomID !=$MYKINGDOMID AND G.KingdomID = K.KingdomID  AND (Designator LIKE '%Kingdom%' OR Designator LIKE '%Principality%' OR Designator LIKE '%Barony%' OR Designator LIKE '%Province%') ORDER BY KName, Name";
	if ($results = $db->get_results($otherkingdomgroupquery)) {
		foreach($results as $r) { 
			$gParentGroupID	= $r->GroupID;
			$gKingdomID	= $r->KingdomID;
			$gName		= $r->Name;
			$kName		= $r->KName;
			if ($gParentGroupID == $ParentGroupIDtrue) { $s = "selected"; } else { $s = ""; }
			?><option value="<?php=$gParentGroupID?>" <?php=$s?>><?php=$kName?>: <?php=$gName?></option>
			<?php
		};
	};  ?>
    </select>
    <br />For a principality, barony, province, shire, or other group which is directly under the Crown, rather than part of a principality/barony/province, select the kingdom. Yes, even though you already selected it in the kingdom box above.  For any group which is under or part of another group (such as a canton or college, but also groups within a principality), select that group as the 'parent group'.
	<br />
	<br />
    <b>Principality/Barony Order:</b>
    <input type="text" size="2" name="BaronyOrder" class="formfield" value="<?php=$BaronyOrder?>" />
    <br />This field is used for sorting baronies within a kingdom into their precedential order (or principalities within a kingdom, if a kingdom happens to have multiple principalities).  If you don't know what order the baronies go in for a given kingdom, just leave this blank.  <strong>Start with 1 for the earliest and go up from there.</strong>  Note that this is not sanity checked - it's totally possible to set up two baronies with the same spot in the ordering.
    <br />
    <br />
	<b>Registered Name:</b>
	<?php WriteYNDropDown($RegName, "RegName"); ?>
    <br />
    <br />
	<b>Registered Device:</b>
	<?php WriteYNDropDown($RegDevice, "RegDevice"); ?>
    <br />
    <br />
    <b>Blazon:</b><br />
    <input name="Blazon" type="text" class="formfield" size="80" maxlength="200" value="<?php=$Blazon?>" /><br /><br />
    <b>Notes:</b><br />
    <input name="Notes" type="text" class="formfield" size="80" maxlength="150"value="<?php=$Notes?>" /><br /><br />
    <br />
    <input type="submit" class="button" value="Submit" />
  </form>
</div>
<?php
}

include("includes/footer.php");
?>
