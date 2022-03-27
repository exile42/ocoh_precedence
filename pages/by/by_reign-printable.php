<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
//include("includes/header.php");

//get reign information
$reignquery = "SELECT R.ReignID, R.RUseName, R.CUseName FROM wReigns R WHERE R.ReignID='$ReignID'";
IF ($results = $db->get_results($reignquery)) {
	foreach($results as $r) { 
		$ReignID 	= $r->ReignID;
		$RUseName	= $r->RUseName;
		$CUseName	= $r->CUseName;
	};
};

?>
<div id="content">

<?php switch($Type) {
	case "Reign": 
		// first we need to sanitize the date
		// for now, we just ask for it in the proper format
		$query = "SELECT AG.AwardsGivenID, AG.SortRank, AG.ReignID, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate, A.AwardID, A.Name as AwardName, A.GroupID, A.KingdomID, P.PersonID as PersonID, P.SCAName as SCAName, P.SearchName as SearchName FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID INNER JOIN wPeople P ON AG.PersonID = P.PersonID WHERE AG.Visible=1 AND AG.Resigned=0 AND AG.ReignID=$ReignID AND ( A.KingdomID=17 OR A.KingdomID=20) ORDER BY AG.Date, AG.SortRank, SearchName";
		IF ($results = $db->get_results($query)) {
		?>
		<br />
		People who received awards during the reign of "<?php echo $RUseName;?>" and "<?php echo $CUseName;?>":<br />
		<br />
		</p>
		<?php 
		foreach($results as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			print "$NiceDate	$SCAName	$AwardName\n";
		}; // end foreach
		?>
		</table>
		<?php
		} else {
			?><p>There were no awards given on <?php echo $SearchMe1; ?>. (that we know of - if you think this is in error, contact Wimble!)</p><?php
		}
		break;
		
	default: // no search term given to us, print "advanced" search options
		?>	

		<div class="menuheader">
		<br /> Search By Date</div>
		<br /><form action="by_reign-printable.php" method="get">
		<select name="ReignID">
		<?php 
		$results = $db->get_results("SELECT ReignID, RUseName, CUseName FROM wReigns WHERE GroupID=96 ORDER BY ReignOrder DESC");
		foreach($results as $r) {
		  $thisRID = $r->ReignID;
		  $Ruler   = $r->RUseName;
		  $Consort = $r->CUseName;
		  IF ($ReignID == $thisRID) { $s = "selected"; } else { $s = ""; }
		  PRINT "<option value=\"$thisRID\" $s>$Ruler and $Consort</option>";
		}
		 ?>
		<option value="">Unknown reign</option>
		</select>
		<input type="hidden" name="Type" value="Reign">
		<input name="Submit" type="submit" value="Go">
		</form>
		<br />YYYY-MM-DD format please.
		
		<?php
		break;
		
	}  // end switch

//include("includes/footer.php");
?>