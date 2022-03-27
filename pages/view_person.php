<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
include("includes/converter.php");

if ($accessOPView != 1) {
        ?><p>You do not have access to this file.</p><?php
        include("includes/footer.php");
        die;
}

if (!is_null($_REQUEST[PersonID]))
	$PersonID = $_REQUEST[PersonID];

?>

<div class="bodyheader">View Person</div>
<br />
<br />
<?php
	
$r = $db->get_row("SELECT * FROM wPeople WHERE PersonID='$PersonID'");

while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
	$$name = $value;
};

$LastUpdateBy 	= $db->get_var("SELECT Username FROM Users WHERE UserID='$LastUpdateID'");
$RegDeviceDate = $db->get_var("SELECT RegDeviceDate FROM wHeraldry WHERE PersonID=$PersonID AND Type='Device'");

?>	<div align="center">
	<table border="0" cellpadding="4" cellspacing="0" width="550" class="edittable">
	<tr><td colspan="2" class="tableheader">View Person</td></tr>
	<tr><td>SCA Name:</td>
		<td><?php=htmlspecialchars($SCAName)?></td></tr>
	<tr><td>Registered Name:</td>
		<td><?php=YNFromBinary($RegName)?></td></tr>
	<tr><td>Date Name Registered:</td>
		<td><?php=htmlspecialchars($RegNameDate)?></td></tr>
	<tr><td>Registered Device:</td>
		<td><?php=YNFromBinary($RegDevice)?></td></tr>
	<tr><td>Date Device Registered:</td>
		<td><?php=htmlspecialchars($RegDeviceDate)?></td></tr>
	<tr><td>Group:</td>
		<td><?php=GroupNameFromGroupID($GroupID,$db)?></td></tr>
	<tr><td>Kingdom:</td>
		<td><?php=KingdomNameFromKingdomID($KingdomID,$db)?></td></tr>

	<tr><td>Active:</td>
		<td><?php=YNFromBinary($Active)?></td></tr>
	<tr><td>Deceased:</td>
		<td><?php=YNFromBinary($Deceased)?></td></tr>
	<tr><td>Modern Name:</td>
		<td><?php=htmlspecialchars($ModernName)?></td></tr>
	<tr><td>Member Number:</td>
		<td><?php=htmlspecialchars($MemberNumber)?></td></tr>
	<tr><td colspan="2" class="tableheader">Aliases</td></tr>
	<tr><td valign="top">Aliases:</td>
		<td>

		<?php
		$count = 0;
		if ($results = $db->get_results("SELECT AliasID, Alias FROM wAliases WHERE PersonID='$PersonID'")) {
			foreach($results as $r) {
				$AliasID = $r->AliasID;
				$Alias 	  = $r->Alias;
				?><?php=htmlspecialchars($Alias)?><br><?php 
				$count++;
			} //end foreach
			?></td></tr><?php
		} else {
			?><tr><td colspan="2">No current aliases.</td></tr><?php
		}
		?>	

		<tr><td colspan="2" class="tableheader">Administration</td></tr>
		<tr><td>Date Added:</td>
			<td><?php=$DateAdded?></td></tr>
		<tr><td>Last Update:</td>
			<td><?php=$LastUpdate?></td></tr>
		<tr><td>Last Update By:</td>
			<td><?php=$LastUpdateBy?></td></tr>
	</table>
	</div>
<?php
include("includes/footer.php");
?>
