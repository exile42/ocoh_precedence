<?
include '../includes/ez_sql.php';
include '../includes/functions.php';
include '../includes/converter.php';
include '../includes/header.php';
?>

<div class="bodyheader">Magical Updating Thingy</div><br />

<?
IF ($results = $db->get_results("SELECT P.PersonID AS PID, P.SCAName AS Name, AG.AwardsGivenID AS AGID, AG.SortRank AS Rank, AG.Visible, AG.Resigned
FROM wPeople AS P, wAwardsGiven AS AG
WHERE P.PersonID = AG.PersonID AND AG.Visible=1 AND AG.Resigned=0
ORDER  BY P.PersonID, AG.SortRank DESC")) {
	PRINT "<table border=\"0\" width=\"560\" cellpadding=\"3\" cellspacing=\"3\"><br />";
	foreach($results as $r) {
		$AGID 		= $r->AGID;
		$PID 		= $r->PID;
		$Rank 		= $r->Rank;
		$Name 		= $r->Name;
		PRINT "<tr>";
		PRINT "<td>$PID</td> <td>$AGID</td> <td>$Name</td> <td>$Rank</td> <td>$RankID</td>";
		IF ($results = $db->get_results("UPDATE wPeople SET SortRank=$Rank WHERE PersonID=$PID"))
			PRINT "<td>FIXT</td>";
		PRINT "</tr>";
	}
	PRINT "</table>";
}

include '../includes/footer.php';
?>