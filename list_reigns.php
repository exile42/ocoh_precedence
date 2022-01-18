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

<div class="bodyheader">List All the Reigns in the system</div>
<p>Any items which are wonky will be at the very end.</p><br />
<br />
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550">
  <tr class="tableheader"><td>Kingdom</td><td>Group</td><td>Reign Order</td><td>Ruler and Consort</td><td>Dates</td></tr>

	<? 
	$ourkingdom_kingdomreigns_query="SELECT R.ReignID, R.GroupID, R.ReignOrder, R.RUseName, R.CUseName, DATE_FORMAT(R.Start, '%Y-%m') AS StartDate, DATE_FORMAT(R.End, '%Y-%m') AS EndDate FROM wReigns R WHERE R.GroupID = $MYGROUPID ORDER BY ReignOrder";
		
	if ($results = $db->get_results($ourkingdom_kingdomreigns_query)) {
		foreach($results as $r) {
		  $ReignID	= $r->ReignID;
		  $GroupID	= $r->GroupID;
		  $ReignOrder	= $r->ReignOrder;
		  $Ruler	= $r->RUseName;
		  $Consort	= $r->CUseName;
		  $Start	= $r->StartDate;
		  $End		= $r->EndDate;
		  ?>
		  <tr><td><?=KingdomNameFromGroupID($GroupID, $db)?></td>
		  <td><?=GroupNameFromGroupID($GroupID, $db)?></td>
		  <td><?=$ReignOrder?></td>
		  <td><?=$Ruler?><br /> <?=$Consort?></td>
		  <td><?=$Start?> - <?=$End?></td>
		  <td><a href="add_reign.php?Action=Edit&ReignID=<?=$ReignID?>">EDIT</a></td>
		  </tr>
		  <?
		} //end foreach
	} else {
    ?>
	<tr><td colspan="2" align="center">
      Bigtime error: no reigns! for <?=$WEAREKINGDOMCOLLECTIVE?>!  Are you perhaps just setting this up for the first time?  Click the "Add Reign" link to add some before you list any!
    </td></tr><?
	} // end if results for our kingdom's royal reigns.

	/// now for baronies in our kingdom .. 
	$ourkingdom_kingdomreigns_query="SELECT R.ReignID, R.GroupID, R.ReignOrder, R.RUseName, R.CUseName, DATE_FORMAT(R.Start, '%Y-%m') AS StartDate, DATE_FORMAT(R.End, '%Y-%m') AS EndDate FROM wReigns R, Groups G WHERE R.GroupID = G.GroupID AND G.KingdomID = $MYKINGDOMID AND G.GroupID != $MYGROUPID ORDER BY G.Designator DESC, G.Name, R.ReignOrder";
		
	if ($results = $db->get_results($ourkingdom_kingdomreigns_query)) {
		foreach($results as $r) {
		  $ReignID	= $r->ReignID;
		  $GroupID	= $r->GroupID;
		  $ReignOrder	= $r->ReignOrder;
		  $Ruler	= $r->RUseName;
		  $Consort	= $r->CUseName;
		  $Start	= $r->StartDate;
		  $End		= $r->EndDate;
		  ?>
		  <tr><td><?=KingdomNameFromGroupID($GroupID, $db)?></td>
		  <td><?=GroupNameFromGroupID($GroupID, $db)?></td>
		  <td><?=$ReignOrder?></td>
		  <td><?=$Ruler?><br /> <?=$Consort?></td>
		  <td><?=$Start?> - <?=$End?></td>
		  <td><a href="add_reign.php?Action=Edit&ReignID=<?=$ReignID?>">EDIT</a></td>
		  </tr>
		  <?
		} //end foreach
	} else {
    ?>
	<tr><td colspan="2" align="center">
      Bigtime error: no reigns! for <?=$WEAREKINGDOMCOLLECTIVE?>!  Are you perhaps just setting this up for the first time?  Click the "Add Reign" link to add some before you list any!
    </td></tr><?
	} // end if results for our kingdom's royal reigns.

/*	
	$otherkingdoms_groupsquery="SELECT G.GroupID, G.Name AS GroupName, G.ParentGroupID, G.KingdomID, K.Name AS KingdomName, G.Designator FROM Groups G INNER JOIN Kingdoms K ON G.KingdomID = K.KingdomID WHERE (G.KingdomID != $MYKINGDOMID OR G.KingdomID IS NULL) ORDER BY KingdomName, GroupName";
	
	if ($results = $db->get_results($otherkingdoms_groupsquery)) {
		foreach($results as $r) {
		  $GroupID	= $r->GroupID;
		  $Kingdom	= $r->KingdomName;
		  $Group	= $r->GroupName;
		  $Designator	= $r->Designator;
		  ?>
		  <tr><td><?=$Kingdom?></td>
		  <td><?=$Group?> (<?=$Designator?>)</td>
		  <td><?=$Award?></td>
		  <td><a href="add_group.php?Action=Edit&GroupID=<?=$GroupID?>">EDIT</a></td>
		  </tr>
		  <?
		} // end foreach
	} else {
    ?><tr><td colspan="2" align="center">
      Bigtime error: no groups from other kingdoms!
    </td></tr><?
	} // end if results for other kingdoms and their sub groups
*/	

 	?>
  <tr><td colspan="2" align="center">
    <a href="add_reign.php">ADD REIGN</a>
  </td></tr></table></div>

<?
include("includes/footer.php");
?>
