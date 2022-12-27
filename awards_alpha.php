<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';
?>

<div class="bodyheader">Awards (Alphabetically)</div><br />

<center>
<?php WriteAlpha("awards_alpha.php'; ?> <a href="awards_alpha.php?Letter=ALL">ALL</a>
</center>

<?php 		
if (!is_null($_REQUEST[Letter])  && ((strlen($_REQUEST[Letter])==1) || ($_REQUEST[Letter]=="ALL")))
		$Letter = $_REQUEST[Letter];
if (!isset($Letter)) {
	?><p>Please click on a letter above to navigate the List of Awards.</p><?

} else {
	// make the query smarter.
	if ($Letter=="ALL") {
		$letterquery="";
		?><p>Awards starting with any letter</p><?
	} else {
		$Letter = substr($_REQUEST[Letter], 0, 1);
		$letterquery="LEFT( A.Name, 1 )='$Letter' AND";
		?><p>Awards starting with <?php echo strtoupper($Letter);?></p><?
	}
	
	$db_query=" SELECT A.AwardID, A.Name, A.Initials, A.GivenFor, A.Blazon, A.Charter, A.Filename, A.Notes, R.Description FROM wAwards as A, wRanks as R WHERE A.RankID=R.RankID AND R.Rank>=40 AND $letterquery A.KingdomID=$MYKINGDOMID ORDER BY A.Name";
	$results = $db->get_results($db_query);
	
	if ($results) {
		foreach($results as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			?><p><b><?php echo $Name?> (<?php echo $Initials?>)</b>  (<?php echo $Description?>)<?php 
			if ($Charter != "") { 
				?>(<a href="charters.php?AwardID=<?php echo $AwardID?>" onclick="popUp(this.href,'elastic',810,650); return false;" target="newWin">charter</a>) <?
			}
			if ($Filename != "") { 
				?>(<a href="badges.php?AwardID=<?php echo $AwardID?>" onclick="popUp(this.href,'elastic',450,620); return false;" target="newWin">badge</a>) <?
			}
			if ($GivenFor!=NULL) {
				?> <br />Given for <?php echo $GivenFor;
			}
			if ($Blazon!=NULL) {
				?> <br />Badge/Insignia: <?php echo $Blazon?></a><?php 
			}
			if ($Notes!=NULL) {
				?> <br />Notes: <?php echo $Notes?></a><br /></p><?php 
			}
		};  //end foreach
	} else {
		?><br />No awards start with <?php echo strtoupper($Letter)?>.<br /></p>	<?	
	}
}; // end else
?>

<?
include 'includes/footer.php';
?>