<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");


if ($accessWimbleView != 1) {
	?><p>You do not have access to this file.</p><?
	include("includes/footer.php");
	die;
}


if (!is_null($_REQUEST[PersonID]))
	$PersonID = $_REQUEST[PersonID];
else
	$err = "We have no idea who you are talking about";

	
$r = $db->get_row("SELECT * FROM wPeople WHERE PersonID='$PersonID'");

while(list($name,$value) = each($r)) { //name all values from the database exactly as they come out
	$$name = $value;
};

$LastUpdateBy 	= $db->get_var("SELECT Username FROM Users WHERE PersonID='$LastUpdateID'");

?>	
<div class="bodyheader">Individual Record</div>
<br />
<br />
  <div align="center">
	<table border="0" cellpadding="3" cellspacing="3" width="560" class="edittable">
	<tr><td>SCAName:</td>
		<td><?=htmlspecialchars($SCAName)?></td></tr>

	<tr><td>Registered Name:</td>
		<td><? IF ($RegName == 1) { PRINT "yes"; } else { PRINT "no"; } ?></td></tr>
	<tr><td>Date Registered:</td>
		<td><?=htmlspecialchars($RegNameDate)?></td></tr>
	<tr><td>Registered Device:</td>
		<td><? IF ($RegDevice == 1) { PRINT "yes"; } else { PRINT "no"; } ?></td></tr>
	<tr><td>Group:</td>
		<td>
		<? $Group = $db->get_var("SELECT Name FROM Groups WHERE GroupID='$GroupID'");
		PRINT $Group;
		?>
		 </td></tr>
	<tr><td>Kingdom:</td>
		<td>
		<? $Kingdom = $db->get_var("SELECT Name FROM Kingdoms WHERE KingdomID='$KingdomID'");
		PRINT $Kingdom;
		?>
		 </td></tr>

	<tr><td>Active:</td>
		<td><? IF ($Active == 1) { PRINT "yes"; } else { PRINT "no"; } ?></td></tr>
	<tr><td>Deceased:</td>
		<td><? IF ($Deceased == 1) { PRINT "yes"; } else { PRINT "no"; } ?></td></tr>
	<tr><td>Modern Name:</td>
		<td><?=htmlspecialchars($ModernName)?></td></tr>
	<tr><td>Member Number:</td>
		<td><?=htmlspecialchars($MemberNumber)?></td></tr>
	<tr><td colspan="2" class="tableheader">Aliases</td></tr>
	<tr><td valign="top">Aliases:</td>
		<td>
		<?
		IF ($results = $db->get_results("SELECT AliasID, Alias FROM wAliases WHERE PersonID='$PersonID'")) {
			foreach($results as $r) {
				$AliasID = $r->AliasID;
				$Alias 	  = $r->Alias;
				PRINT $Alias . "<br>";
			} //end foreach
			PRINT "</td></tr>";
		} else {
			PRINT "No current aliases.</td></tr>";
		}
		?>	

		<tr><td colspan="2" class="tableheader">Administration</td></tr>
		<tr><td>Date Added:</td>
			<td><?=$DateAdded?></td></tr>
		<tr><td>Last Update:</td>
			<td><?=$LastUpdate?></td></tr>
		<tr><td>Last Update By:</td>
			<td><?=$LastUpdateBy?></td></tr>

	</table>
	</div>

<p><strong>Awards Given</strong></p>
<?

IF ($results = $db->get_results("SELECT g.*, a.Initials as Award, r.RUseName as Ruler, r.CUseName as Consort  
	FROM wAwardsGiven g
	LEFT JOIN wAwards a ON a.AwardID=g.AwardID
	LEFT JOIN wReigns r ON r.ReignID=g.ReignID
	WHERE g.PersonID='$PersonID'")) {
?>	<div align="center"><table border="0" cellpadding="3" cellspacing="3" width="560">
	<tr class="tableheader">
		<td valign="bottom">Award</td>
		<td valign="bottom">Date</td>
		<td valign="bottom">Resigned</td>
		<td valign="bottom">Awarded By</td>
		<td valign="bottom">Verified</td>
		<td valign="bottom">Visible</td>
		<td valign="bottom">Scroll Given</td>
	</tr>
<?		foreach($results as $r) {
			while(list($name,$value) = each($r)) { //name all values from the database 
				$$name = $value;
			};
			$Date = format_date_pretty($Date);
			IF ($Resigned == 1) { $Resigned = "yes"; } else { $Resigned = "no"; }
			IF ($ScrollGiven == 1) { $ScrollGiven = "yes"; } else { $ScrollGiven = "no"; }
			IF ($Visible == 1) { $Visible = "yes"; } else { $Visible = "no"; }
			IF ($Ruler != "") { $rc = "$Ruler and $Consort"; } else { $rc = "";}
		
			PRINT "<tr>
				<td valign=\"top\">$Award</td>
				<td valign=\"top\">$Date</td>
				<td valign=\"top\">$Resigned</td>
				<td valign=\"top\">$Awarded By</td>
				<td valign=\"top\">$Verified</td>
				<td valign=\"top\">$Visible</td>
				<td valign=\"top\">$ScrollGiven</td>
			</tr>
			<tr><td colspan=\"8\">Reign: $rc<br>
			Notes: $Notes</td></tr>";
		} //end foreach

?>
	</table></div>
<?
} else {
	PRINT "<p>No awards given.</p>";
}


include("includes/footer.php");
?>
