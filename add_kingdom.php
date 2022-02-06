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
if (isset($_REQUEST['KingdomID']))
	if (is_numeric($_REQUEST['KingdomID'])) 
		$KingdomID = $_REQUEST['KingdomID'];
	else
		$err .= " KingdomID has an invalid value";
if (isset($_REQUEST['Name']))
	$Name = mysql_real_escape_string(SanitizeInputGeneral($_REQUEST['Name']));
if (isset($_REQUEST['CreationOrder']))
	if (is_numeric($_REQUEST['CreationOrder']))
		$CreationOrder = $_REQUEST['CreationOrder'];
	else
		$err .= " The CreationOrder is invalid. ";

	$CurrentMaxCreationOrder = $db->get_var("SELECT MAX(CreationOrder) FROM Kingdoms WHERE 1");

?>
<div class="bodyheader">
  <?php=$Action?>
  Kingdom</div>
<br />
<?php
if ($Action == "Submit") { //they've submitted the form
	$err = ""; // no error messages yet
	//first check for errors
	//if $KingdomID == 0 we are adding, check that the CreationOrder is more than the current maximum
	if ($KingdomID == 0 && !($CreationOrder > $CurrentMaxCreationOrder))
		$err = "You cannot create a new kingdom with a Creation Order less than or equal to $CurrentMaxCreationOrder";
	
	// Were there any errors?
	if ($err != "") { //we got errors, make them work for it
    	?><p class="error">
		<?php=$err?></p><?php
		$KingdomIDtrue = $KingdomID;
    	ShowForm();

	} else { //no errors, insert or update as needed
		if ($CreationOrder=='') { $CreationOrder="$CurrentMaxCreationOrder+1"; } else { $CreationOrder="'$CreationOrder'"; }
		if ($KingdomID == 0) { //we're adding a completely new group
			$insertquery="INSERT INTO Kingdoms (KingdomID, Name, CreationOrder) VALUES ('$KingdomID', '$Name', $CreationOrder)";
			if ($db->query($insertquery)) {
				?><p> Kingdom added successfully.</p><?php
			} else {
				?><p>ADD FAIL</p><?php
			}
		} else { //we're updating
			$updatequery="UPDATE Groups SET KingdomID='$KingdomID', Name='$Name', CreationOrder='$CreationOrder', WHERE KingdomID='$KingdomID'";
			echo $updatequery;
			if ($db->query($updatequery)) {
				?><p>Kingdom edited successfully.</p><?php
			} else {
				?><p>EDIT GROUP FAIL</p><?php
			}
		}// end else updating
	} //end if error/action is submit

} elseif ($Action == "Edit") { //they've chosen to edit an existing kingdom, get the record from the db and showform
	$editquery = "SELECT K.* FROM Kingdoms K WHERE K.KingdomID='$KingdomID'";
//echo $editquery;
	$r = $db->get_row($editquery);
	while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
		$$name = $value;
	};
	$KingdomIDtrue = $KingdomID;
	ShowForm();

} else { //they are adding a completely new kingdom, set ID = 0 and show form
	$Action ="Add";
	$KingdomID = 0;
	ShowForm();

}//end if Action


function ShowForm() { 
	global $db, $KingdomID, $KingdomIDtrue, $Name, $CreationOrder, $CurrentMaxCreationOrder, $MYKINGDOMID, $WEAREKINGDOM; ?>
    <p>WARNING: This should almost never be used - only when a new kingdom is added, which is a very rare occurrence.</p>
<div class="form">
  <form action="add_kingdom.php" method="post">
    <input type="hidden" name="Action" value="Submit" />
    <input type="hidden" name="KingdomID" value="<?php=$KingdomIDtrue?>">
    <b>Kingdom Name:</b>
    <input type="text" size="80" name="Name" class="formfield" value="<?php=$Name?>" />
    <br />
    <br />
    <b>Creation Order:</b>
    <input type="text" size="2" name="CreationOrder" class="formfield" value="<?php=$CreationOrder?>" />
    <br />This field is used for sorting kingdoms into their precedential order.  <strong>Start with 1 for the earliest and go up from there.</strong>  This is a required field.  The current highest Creation Order is <?php=$CurrentMaxCreationOrder?>
    <br />
    <br />
	<input type="submit" class="button" value="Submit" />
  </form>
</div>
<?php
}

include("includes/footer.php");
?>
