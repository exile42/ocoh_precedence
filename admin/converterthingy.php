<?
include '../includes/ez_sql.php';
include '../includes/functions.php';
include '../includes/converter.php';
include '../includes/header.php';
?>

<div class="bodyheader">Magical Updating Thingy</div><br />

<?
IF ($results = $db->get_results(" SELECT AG.AwardsGivenID AS AGID, A.AwardID AS AwardID, DATE_FORMAT( AG.Date,  '%Y%m%d'  )  AS Dater, A.Rank AS Rank, A.Name AS Name
FROM wAwardsGiven AG, wAwards A WHERE AG.AwardID=A.AwardID")) {
	PRINT "<table border=\"0\" width=\"560\" cellpadding=\"3\" cellspacing=\"3\"><br />";
	foreach($results as $r) {
		$AGID 		= $r->AGID;
		$AwardID 	= $r->AwardID;
		$Date 		= $r->Dater;
		$Rank 		= $r->Rank;
		$Name 		= $r->Name;
		$Thingy		= $Date*$Rank;
		PRINT "<tr>";
		PRINT "<td>$AGID</td> <td>$AwardID</td> <td>$Name</td> <td>$Date</td> <td>$Rank</td> <td>$Thingy</td>";
		IF ($results = $db->get_results("UPDATE wAwardsGiven SET SortRank=$Thingy WHERE AwardsGivenID=$AGID"))
			PRINT "<td>FIXT</td>";
		PRINT "</tr>";
	}
	PRINT "</table>";
}

include '../includes/footer.php';
?>