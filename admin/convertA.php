<?
include("../includes/constants.php");
include("../includes/ez_sql.php");
include("../includes/functions.php");
include("../includes/converter.php");
include("../includes/header.php");
?>

<div class="bodyheader">Magical Updating Thingy - updates wAwards</div><br />

<?
IF ($results = $db->get_results("SELECT A.AwardID, A.Name, A.RankID AS ARank, R.RankID AS RRank, R.Rank 
				FROM wAwards A, wRanks R WHERE R.RankID=A.RankID")) {
	PRINT "<table border=\"0\" width=\"560\" cellpadding=\"3\" cellspacing=\"3\"><br />";
	foreach($results as $r) {
		$AwardID 	= $r->AwardID;
		$ARank	 	= $r->ARank;
		$RRank	 	= $r->RRank;
		$Name 		= $r->Name;
		$Rank		= $r->Rank;
		PRINT "<tr>";
		PRINT "<td>$AwardID</td><td>$Name</td><td>$ARank</td><td>$RRank</td><td>$Rank</td>";
		IF ($results = $db->get_results("UPDATE wAwards SET Rank=$Rank WHERE AwardID=$AwardID"))
			PRINT "<td>FIXT</td>";
		PRINT "</tr>";
	}
	PRINT "</table>";
}

include("../includes/footer.php");
?>