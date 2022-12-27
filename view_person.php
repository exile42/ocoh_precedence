<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';
include 'includes/converter.php';

if ($accessOPView != 1) {
        ?><p>You do not have access to this file.</p><?
        include 'includes/footer.php';
        die;
}

if (!is_null($_REQUEST[PersonID]))
	$PersonID = $_REQUEST[PersonID];

?>

<div class="bodyheader">View Person</div>
<br />
<br />
<?
	
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
		<td><?php echo htmlspecialchars($SCAName)?></td></tr>
	<tr><td>Registered Name:</td>
		<td><?php echo YNFromBinary($RegName)?></td></tr>
	<tr><td>Date Name Registered:</td>
		<td><?php echo htmlspecialchars($RegNameDate)?></td></tr>
	<tr><td>Registered Device:</td>
		<td><?php echo YNFromBinary($RegDevice)?></td></tr>
	<tr><td>Date Device Registered:</td>
		<td><?php echo htmlspecialchars($RegDeviceDate)?></td></tr>
	<tr><td>Group:</td>
		<td><?php echo GroupNameFromGroupID($GroupID,$db)?></td></tr>
	<tr><td>Kingdom:</td>
		<td><?php echo KingdomNameFromKingdomID($KingdomID,$db)?></td></tr>

	<tr><td>Active:</td>
		<td><?php echo YNFromBinary($Active)?></td></tr>
	<tr><td>Deceased:</td>
		<td><?php echo YNFromBinary($Deceased)?></td></tr>
	<tr><td>Modern Name:</td>
		<td><?php echo htmlspecialchars($ModernName)?></td></tr>
	<tr><td>Member Number:</td>
		<td><?php echo htmlspecialchars($MemberNumber)?></td></tr>
	<tr><td colspan="2" class="tableheader">Aliases</td></tr>
	<tr><td valign="top">Aliases:</td>
		<td>

		<?
		$count = 0;
		if ($results = $db->get_results("SELECT AliasID, Alias FROM wAliases WHERE PersonID='$PersonID'")) {
			foreach($results as $r) {
				$AliasID = $r->AliasID;
				$Alias 	  = $r->Alias;
				?><?php echo htmlspecialchars($Alias)?><br><?php 
				$count++;
			} //end foreach
			?></td></tr><?
		} else {
			?><tr><td colspan="2">No current aliases.</td></tr><?
		}
		?>	

		<tr><td colspan="2" class="tableheader">Administration</td></tr>
		<tr><td>Date Added:</td>
			<td><?php echo $DateAdded?></td></tr>
		<tr><td>Last Update:</td>
			<td><?php echo $LastUpdate?></td></tr>
		<tr><td>Last Update By:</td>
			<td><?php echo $LastUpdateBy?></td></tr>
	</table>
	</div>
<?
include 'includes/footer.php';
?>
