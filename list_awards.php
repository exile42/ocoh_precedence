<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble != 1) {
        ?><p>You do not have access to this file.</p><?
        include("includes/footer.php");
        die;
}

?>

<div class="bodyheader">List ALLLLLLLLLLLL Awards in the system</div>
<p>Any items which are wonky will be at the very end and only have GroupIDs and KingdomIDs, not names (since I can't tell which of them exist or don't exist).</p><br />
<br />
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550">
  <tr class="tableheader"><td>Kingdom</td><td>Group</td><td>Award</td><td>Action</td></tr>

	<? 
	$ourkingdom_awardsquery="SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, K.Name AS KingdomName, G.Name AS GroupName, G.Designator FROM wAwards A INNER JOIN Kingdoms K INNER JOIN Groups G ON A.KingdomID = K.KingdomID AND A.GroupID = G.GroupID WHERE A.KingdomID = $MYKINGDOMID AND A.GroupID = $MYGROUPID ORDER BY A.Name";
		
	if ($results = $db->get_results($ourkingdom_awardsquery)) {
		foreach($results as $r) {
		  $AwardID	= $r->AwardID;
		  $Award	= $r->Name;
		  $Kingdom	= $r->KingdomName;
		  $Group	= $r->GroupName;
		  ?>
		  <tr><td><?=$Kingdom?></td>
		  <td><?=$Group?></td>
		  <td><?=$Award?></td>
		  <td><a href="add_award.php?Action=Edit&AwardID=<?=$AwardID?>">EDIT</a></td>
		  </tr>
		  <?
		} //end foreach
	} else {
    ?>
	<tr><td colspan="2" align="center">
      Bigtime error: no awards for <?=$WEAREKINGDOMCOLLECTIVE?>!
    </td></tr><?
	} // end if results for outlandsawardsquery

	$ourkingdom_subgroups_awardsquery="SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, K.Name AS KingdomName, G.Name AS GroupName, G.Designator FROM wAwards A INNER JOIN Kingdoms K INNER JOIN Groups G ON A.KingdomID = K.KingdomID AND A.GroupID = G.GroupID WHERE A.KingdomID = $MYKINGDOMID AND (A.GroupID != $MYGROUPID OR A.GroupID IS NULL) ORDER BY GroupName, A.Name";
		
	if ($results = $db->get_results($ourkingdom_subgroups_awardsquery)) {
		foreach($results as $r) {
		  $AwardID	= $r->AwardID;
		  $Award	= $r->Name;
		  $Kingdom	= $r->KingdomName;
		  $Group	= $r->GroupName;
		  ?><tr><td><?=$Kingdom?></td>
		  <td><?=$Group?></td>
		  <td><?=$Award?></td>
		  <td><a href="add_award.php?Action=Edit&AwardID=<?=$AwardID?>">EDIT</a></td></tr>
		  <?
		
		} //end foreach
	} else {
    ?><tr><td colspan="2" align="center">
      Bigtime error: no awards for subgroups of <?=$WEAREKINGDOMCOLLECTIVE?>!
    </td></tr><?
	} // end if results for outlandssubgroupsawardquery
	
	$otherkingdoms_awardsquery="SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID, K.Name AS KingdomName, G.Name AS GroupName, G.Designator FROM wAwards A INNER JOIN Kingdoms K INNER JOIN Groups G ON A.KingdomID = K.KingdomID AND A.GroupID = G.GroupID WHERE ( A.KingdomID != $MYKINGDOMID OR A.KingdomID IS NULL) ORDER BY KingdomName, GroupName, A.Name";
		
	if ($results = $db->get_results($otherkingdoms_awardsquery)) {
		foreach($results as $r) {
		  $AwardID	= $r->AwardID;
		  $Award	= $r->Name;
		  $Kingdom	= $r->KingdomName;
		  $Group	= $r->GroupName;
		  ?><tr><td><?=$Kingdom?></td>
		  <td><?=$Group?></td>
		  <td><?=$Award?></td>
		  <td><a href="add_award.php?Action=Edit&AwardID=<?=$AwardID?>">EDIT</a></td></tr>
		  <?
		
		} //end foreach
	} else {
    ?><tr><td colspan="2" align="center">
      Bigtime error: no foreign awards!
    </td></tr><?
	} // end if results for otherawardsquery
	
		$broken_awardsquery="SELECT A.AwardID, A.Name, A.GroupID, A.KingdomID FROM wAwards A WHERE (A.KingdomID = 0 OR A.KingdomID IS NULL) OR (A.GroupID = 0 OR A.GroupID IS NULL) ORDER BY A.Name";
		
	if ($results = $db->get_results($broken_awardsquery)) {
		foreach($results as $r) {
		  $AwardID	= $r->AwardID;
		  $KingdomID	= $r->KingdomID;
		  $GroupID	= $r->GroupID;
		  $Award	= $r->Name;
		  ?><tr><td><?=$KingdomID?></td>
		  <td><?=$GroupID?></td>
		  <td><?=$Award?></td>
		  <td><a href="add_award.php?Action=Edit&AwardID=<?=$AwardID?>">EDIT</a></td></tr>
		  <?
		
		} //end foreach
	} else {
    ?><tr><td colspan="2" align="center">
      HOORAY! No wonky awards!
    </td></tr><?
	} // end if results for outlandssubgroupsawardquery

 	?>
  <tr><td colspan="2" align="center">
    <a href="add_award.php">ADD AWARD</a>
  </td></tr></table></div>

<?
include("includes/footer.php");
?>
