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

if (!is_null($_REQUEST['Action']))
	$Action = $_REQUEST['Action'];
if (isset($_REQUEST['ReignID']))
	if (is_numeric($_REQUEST['ReignID']))
		$ReignID = $_REQUEST['ReignID'];
	else
		$err .= " That Reign doesn't exist. ";
if (isset($_REQUEST['GroupID']))
	if (is_numeric($_REQUEST['GroupID'])) 
		$GroupID = $_REQUEST['GroupID'];
	else
		$err .= " Group has an invalid value";
if (!is_null($_REQUEST['Ruler']))
	$Ruler = mysql_real_escape_string($_REQUEST['Ruler']);
if (!is_null($_REQUEST['Consort']))
	$Consort = mysql_real_escape_string($_REQUEST['Consort']);
if (!is_null($_REQUEST['RUseName']))
	$RUseName = mysql_real_escape_string($_REQUEST['RUseName']);
if (!is_null($_REQUEST['CUseName']))
	$CUseName = mysql_real_escape_string($_REQUEST['CUseName']);
if (isset($_REQUEST['RPersonID']))
	if (is_numeric($_REQUEST['RPersonID']))
		$RPersonID = $_REQUEST['RPersonID'];
	else
		$err .= " That Ruler doesn't exist in the People table yet. ";
if (isset($_REQUEST['CPersonID']))
	if (is_numeric($_REQUEST['CPersonID']))
		$CPersonID = $_REQUEST['CPersonID'];
	else
		$err .= " That Consort doesn't exist in the People table yet. ";
if (!is_null($_REQUEST['RUseTitle']))
	$RUseTitle = mysql_real_escape_string($_REQUEST['RUseTitle']);
if (!is_null($_REQUEST['CUseTitle']))
	$CUseTitle = mysql_real_escape_string($_REQUEST['CUseTitle']);
if (isset($_REQUEST['ReignOrder']))
	if (is_numeric($_REQUEST['ReignOrder']))
		$ReignOrder = $_REQUEST['ReignOrder'];
	else
		$err .= " The ReignOrder is invalid. ";
if (!is_null($_REQUEST['Notes']))
	$Notes = mysql_real_escape_string($_REQUEST['Notes']);
if (!is_null($_REQUEST['StartDate']))
	$StartDate = mysql_real_escape_string($_REQUEST['StartDate']);
if (!is_null($_REQUEST['EndDate']))
	$EndDate = mysql_real_escape_string($_REQUEST['EndDate']);


?>
<div class="bodyheader">
  <?php=$Action?>
  Reign</div>
<br />
<?php
if ($Action == "Submit") { //they've submitted the form
	$err = ""; // no error messages yet
	//first check for errors
	if ($err != "") { //we got errors, make them work for it
    	?><p class="error"><?php=$err?></p><?php
		$GroupIDtrue = $GroupID;
		$ReignIDtrue = $ReignID;
    	ShowForm($db, $ReignIDtrue, $GroupIDtrue, $ReignOrder, $Ruler, $Consort, $RPersonID, $CPersonID, $RUseName, $CUseName, $RUseTitle, $CUseTitle, $StartDate, $EndDate, $Notes);

	} else { //no errors, insert or update as needed
		if ($ReignOrder=='') { $ReignOrder="NULL"; } else { $ReignOrder="'$ReignOrder'"; }
		if ($Consort=='') { $Consort="NULL"; } else { $Consort="'$Consort'"; }
		if ($RPersonID=='') { $RPersonID="NULL"; } else { $RPersonID="'$RPersonID'"; }
		if ($CPersonID=='') { $CPersonID="NULL"; $CUseTitle="NULL";} else { $CPersonID="'$CPersonID'";  $CUseTitle="'$CUseTitle'";}
		if ($RUseName=='') { $RUseName="NULL"; } else { $RUseName="'$RUseName'"; }
		if ($CUseName=='') { $CUseName="NULL"; } else { $CUseName="'$CUseName'"; }
		if ($Notes=='') { $Notes="NULL"; } else { $Notes="'$Notes'"; }

		if ($ReignID == 0) { //we're adding a completely new reign
			$insertquery="INSERT INTO wReigns (GroupID, ReignOrder, Ruler, Consort, RPersonID, CPersonID, RUseName, CUseName, RUseTitle, CUseTitle, Start, End, Notes) 
			VALUES ('$GroupID', $ReignOrder, '$Ruler', $Consort, $RPersonID, $CPersonID, $RUseName, $CUseName, '$RUseTitle', $CUseTitle, '$StartDate', '$EndDate', $Notes)";
//			echo $insertquery;
			if ($db->query($insertquery)) {
				?><p> <?php=$insertquery?>: Item added successfully.</p><?php
			} else {
				?><p>ADD REIGN FAIL</p><?php
			}
		} else { //we're updating an existing reign
			$updatequery="UPDATE wReigns SET GroupID='$GroupID', ReignOrder=$ReignOrder, Ruler='$Ruler', Consort=$Consort, RPersonID=$RPersonID, CPersonID=$CPersonID, RUseName=$RUseName, CUseName=$CUseName, RUseTitle='$RUseTitle', CUseTitle=$CUseTitle, Start='$StartDate', End='$EndDate', Notes=$Notes WHERE  ReignID='$ReignID'";
//			echo $updatequery;
			if ($db->query($updatequery)) {
				?><p> <?php=$updatequery?>: Item edited successfully.</p><?php
			} else {
				?><p>EDIT REIGN FAIL</p><?php
			}
		}// end else updating
	} //end if error/action is submit

} elseif ($Action == "Edit") { //they've chosen to edit an existing reign, get the record from the db and showform
	$editquery = "SELECT R.* FROM wReigns R WHERE R.ReignID='$ReignID'";
	//echo $editquery;
	$r = $db->get_row($editquery);
	while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
		$$name = $value;
	};
	$ReignIDtrue = $ReignID;
	$GroupIDtrue = $GroupID;
	ShowForm($db, $GroupIDtrue, $ReignIDtrue, $ReignOrder, $Ruler, $Consort, $RPersonID, $CPersonID, $RUseName, $CUseName, $RUseTitle, $CUseTitle, $Start, $End, $Notes);

} else { //they are adding a completely new reign, set ID = 0 and show form
	$Action ="Add";
	$GroupIDtrue = $MYGROUPID;
	ShowForm($db, $GroupIDtrue);

}//end if Action


function ShowForm($db, $GroupIDtrue, $ReignIDtrue=0, $ReignOrder=0, $Ruler='', $Consort='', $RPersonID='', $CPersonID='', $RUseName='', $CUseName='', $RUseTitle='King', $CUseTitle='Queen', $StartDate='', $EndDate='9999-12-31', $Notes='') { 
//ReignID 	GroupID 	ReignOrder 	Ruler 	Consort 	RPersonID 	CPersonID 	RUseName 	CUseName 	RUseTitle 	CUseTitle 	Start 	End 	Notes
//	global $db, $ReignID, $GroupIDtrue, $ReignOrder, $Ruler, $Consort, $RPersonID, $CPersonID, $RUseName, $CUseName, $RUseTitle, $CUseTitle, $Start, $End, $Notes; 

include("includes/constants.php");

?>
<div class="form">
  <form action="add_reign.php" method="post">
    <input type="hidden" name="Action" value="Submit" />
    <input type="hidden" name="GroupID" value="<?php=$GroupIDtrue?>">
    <input type="hidden" name="ReignID" value="<?php=$ReignIDtrue?>">

	<b>Information about the Group</b>
    <br />That is, the branch that this reign is for.  Pretty straightforward.
    <br />
    <br />

		<b>Group:</b><br />
		<select name="GroupID">
		<?php $groupquery = "SELECT G.GroupID, G.KingdomID, G.Name FROM Groups G WHERE G.KingdomID = $MYKINGDOMID ORDER BY Name";
		if ($results = $db->get_results($groupquery)) {
			foreach($results as $r) { 
				$gGroupID	= $r->GroupID;
				$gKingdomID	= $r->KingdomID;
				$gName		= $r->Name;
			  if ($gGroupID == $GroupIDtrue) { $s = "selected"; } else { $s = ""; }
			  ?><option value="<?php=$gGroupID?>" <?php=$s?>><?php=$gName?></option>
			  <?php
			};
		};?>
    </select><br /><br />

	<b>Information about the Ruler</b>
    <br />That is, the person who won the tourney (or competition or whatever).  If there is only one person in the chair, such as a sole ruling Baroness or Baron, put them in the Ruler slot regardless of gender.  In the case of same-sex couples not chosen by competition, see which one is listed first most of the time or throw lots or whatever makes sense for you and your kingdom.
    <br />
    <br />
    <b>Ruler FULL Name:</b>
    <input type="text" size="80" name="Ruler" class="formfield" value="<?php=$Ruler?>" />
    <br />This is their full SCA name <i>at the time of reign</i>.  Even if they change it later (put the new name in the Notes field).  Even if they use a different name while reigning (put that name in the Use Name field).
    <br />
    <br />
    <b>Ruler USE Name:</b>
    <input type="text" size="60" name="RUseName" class="formfield" value="<?php=$RUseName?>" />
    <br />This is the name that they went by during the reign - typically a single name (ex. "Cuthbert" or "Samwise" or "Rowan").  This is often the given name of their full name, but in some cases may be a "coronation" name that they are using specifically for the reign.  In the case where the same person rules in the same Group more than once, put in numbers (usually roman numerals, "II", "III", "IV", etc.) for each reign after the first one.  Please do this for both male and female rulers - it makes it easier to correctly match up reign-specific tokens (like the Cypher of King Someone), if we can tell them apart in the pulldown list of reigns.
    <br />
    <br />
    <b>Ruler Title:</b>
    <input type="text" size="20" name="RUseTitle" class="formfield" value="<?php=$RUseTitle?>" />
    <br />
    This is the Title that they used during the reign. King, Prince, and Baron are standards for men, but they may have chosen one from the <a href="http://heraldry.sca.org/titles.html">Alternate Titles list</a>, such as Konig, Amir, or Barun OR may be female and have a female title instead.
    <br />
    <br />
    <b>Ruler PersonID:</b>
	<input type="text" name="RPersonID" value="<?php=$RPersonID?>" size="10" maxlength="10">
    <br />This is the PersonID tag for this person in the database (it's the one at the end of the link for their Individual Record page).  It's kind of awkward to enter manually, but making a giant list would be just as awkward, unfortunately.
    <br />
    <br />


	<b>Information about the Consort</b>
    <br />
    That is, the other person, not the one who won the tourney (or competition or whatever).  If there is only one person in the chair, such as a sole ruling Baroness or Baron, put them in the Ruler slot, regardless of gender.  In the case of same-sex couples not chosen by competition, see which one is listed first most of the time or throw lots or whatever makes sense for you and your kingdom.
    <br />
    <br />
    <b>Consort FULL Name:</b>
    <input type="text" size="80" name="Consort" class="formfield" value="<?php=$Consort?>" />
    <br />This is their full SCA name <i>at the time of reign</i>.  Even if they change it later (put the new name in the Notes field).  Even if they use a different name while reigning (put that name in the Use Name field).
    <br />
    <br />
    <b>Consort USE Name:</b>
    <input type="text" size="60" name="CUseName" class="formfield" value="<?php=$CUseName?>" />
    <br />This is the name that they went by during the reign - typically a single name (ex. "Maryam" or "Alys" or "Guiseppe").  This is often the given name of their full name, but in some cases may be a "coronation" name that they are using specifically for the reign.  In the case where the same person rules in the same Group more than once, put in numbers (usually roman numerals, "II", "III", "IV", etc.) for each reign after the first one.  Please do this for both male and female consorts - it makes it easier to correctly match up reign-specific tokens (like the Cypher of Baroness Someone), if we can tell them apart in the pulldown list of reigns.
    <br />
    <br />
    <b>Consort Title:</b>
    <input type="text" size="20" name="CUseTitle" class="formfield" value="<?php=$CUseTitle?>" />
    <br />This is the Title that they used during the reign. Queen, Princess, and Baroness are standards for women, but they may have chosen one from the <a href="http://heraldry.sca.org/titles.html">Alternate Titles list</a>, such as Regina, Hlaefdige, or Posadnitsa (or may be male and have a male title instead).
    <br />
    <br />
    <b>Consort PersonID:</b>
	<input type="text" name="CPersonID" value="<?php=$CPersonID?>" size="10" maxlength="10">
    <br />This is the PersonID tag for this person in the database (it's the one at the end of the link for their Individual Record page).  It's kind of awkward to enter manually, but making a giant list would be just as awkward, unfortunately.
    <br />
    <br />

	<b>Information about the Reign</b>
    <br />Tell me about this reign specifically.  Remember, this is for posterity, so be accurate!
    <br />
    <br />
    <b>Reign Number:</b>
	<input type="text" name="ReignOrder" value="<?php=$ReignOrder?>" size="10" maxlength="10">
    <br />This field is used for sorting the reigns. <strong>Start with 1 for the earliest and go up from there.</strong>  Note that this is not sanity checked - it's totally possible to set up two reigns with the same spot in the ordering.  Please try to avoid that.
    <br />
    <br />
    <b>Start Date:</b>
    <input type="text" name="StartDate" value="<?php=$StartDate?>" size="10" maxlength="10">
    <br />What is the start date for this reign?  This MUST be in the format YYYY-MM-DD.
    <br />
    <br />
    <b>End Date:</b>
    <input type="text" name="EndDate" value="<?php=$EndDate?>" size="10" maxlength="10">
    <br />What is the end date for this reign?  This MUST be in the format YYYY-MM-DD.  If this is the current reign, the default is 9999-12-31.
    <br />
    <br />
    <b>Notes:</b>
    <input type="text" name="Notes" class="formfield" value="<?php=$Notes?>" size="50" maxlength="150">
    <br />Is there anything special that we should make PUBLIC KNOWLEDGE about this reign?  For example, did one of them take a coronation name or later change their SCA name?  This is displayed on the "list of royalty" page, so be brief.
    <br />
    <br />
    <input type="submit" class="button" value="Submit" />
  </form>
</div>
<?php
}

include("includes/footer.php");
?>
