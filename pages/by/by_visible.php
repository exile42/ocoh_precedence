<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble == 0) {
        PRINT "<p>You do not have access to this file.</p>";
        include("includes/footer.php");
        die;
}


?>
<div id="content">

<?php
		// first we need to sanitize the date
		// for now, we just ask for it in the proper format
		$query = "SELECT AG.AwardsGivenID as AGID, AG.SortRank, AG.ReignID, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate, A.AwardID, A.Name as AwardName, A.GroupID, A.KingdomID, P.PersonID as PersonID, P.SCAName as SCAName, P.SearchName as SearchName FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID INNER JOIN wPeople P ON AG.PersonID = P.PersonID WHERE AG.Visible=0 AND AG.Resigned=0 AND ( A.KingdomID=17 OR A.KingdomID=20) ORDER BY AG.Date, AG.SortRank, SearchName";
		if ($results = $db->get_results($query)) {
		?>
		<br />
		People whose awards are invisible:<br />
		<br />
		</p>
		<table border="1" cellpadding="3" cellspacing="3" width="560">
		<?php 
		foreach($results as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
				?>
				<tr>
				<td valign="top" align="right"><?php echo $NiceDate; ?></td>
				<td valign="top" align="left"><a href="individual_record.php?PersonID=<?php echo $PersonID ; ?>"><?php echo $SCAName;?></a></td>
				<td valign="top" align="right"><a href="edit_awards.php?Action=Edit&PersonID=<?php echo $PersonID ; ?>&AwardsGivenID=<?php echo $AGID; ?>"><?php echo $AwardName; ?></a></td>
				</tr>
				<?php 
		}; // end foreach
		?>
		</table>
		<?php
		} 

include("includes/footer.php");
?>
