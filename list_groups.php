<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if ($accessWimble != 1) {
        ?><p>You do not have access to this file.</p><?php
        include("includes/footer.php");
        die;
}

?>

<div class="bodyheader">List ALLLLLLLLLLLL Groups in the system</div>
<p>Any items which are wonky will be at the very end and only have GroupIDs and KingdomIDs, not names (since I can't tell which of them exist or don't exist).</p><br />
<br />
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550">
  <tr class="tableheader"><td>Kingdom</td><td>Group</td><td>Active?</td><td>Action</td></tr>

	<?php 
	$ourkingdom_groupsquery="SELECT G.GroupID, G.Name AS GroupName, G.ParentGroupID, G.KingdomID, K.Name AS KingdomName, G.Designator, G.Active FROM Groups G INNER JOIN Kingdoms K ON G.KingdomID = K.KingdomID WHERE G.KingdomID = $MYKINGDOMID ORDER BY GroupName";
		
	if ($results = $db->get_results($ourkingdom_groupsquery)) {
		foreach($results as $r) {
		  $GroupID	= $r->GroupID;
		  $Kingdom	= $r->KingdomName;
		  $Group	= $r->GroupName;
		  $Designator	= $r->Designator;
		  $Active	= $r->Active;
		  if (!$Active) { $a="(inactive/disbanded)"; } else { $a=""; }
		  ?>
		  <tr><td><?php=$Kingdom?></td>
		  <td><?php=$Group?> (<?php=$Designator?>)</td>
		  <td><?php=$a?></td>
		  <td><a href="add_group.php?Action=Edit&GroupID=<?php=$GroupID?>">EDIT</a></td>
		  </tr>
		  <?php
		} //end foreach
	} else {
    ?>
	<tr><td colspan="2" align="center">
      Bigtime error: no groups for <?php=$WEAREKINGDOMCOLLECTIVE?>!
    </td></tr><?php
	} // end if results for outlandsawardsquery

	
	$otherkingdoms_groupsquery="SELECT G.GroupID, G.Name AS GroupName, G.ParentGroupID, G.KingdomID, G.Active, K.Name AS KingdomName, G.Designator FROM Groups G INNER JOIN Kingdoms K ON G.KingdomID = K.KingdomID WHERE (G.KingdomID != $MYKINGDOMID OR G.KingdomID IS NULL) ORDER BY KingdomName, GroupName";
	
	if ($results = $db->get_results($otherkingdoms_groupsquery)) {
		foreach($results as $r) {
		  $GroupID	= $r->GroupID;
		  $Kingdom	= $r->KingdomName;
		  $Group	= $r->GroupName;
		  $Designator	= $r->Designator;
		  $Active	= $r->Active;
		  if (!$Active) { $a="(inactive/disbanded)"; } else { $a=""; }
		  ?>
		  <tr><td><?php=$Kingdom?></td>
		  <td><?php=$Group?> (<?php=$Designator?>)</td>
		  <td><?php=$a?></td>
		  <td><a href="add_group.php?Action=Edit&GroupID=<?php=$GroupID?>">EDIT</a></td>
		  </tr>
		  <?php
		} // end foreach
	} else {
    ?><tr><td colspan="2" align="center">
      Bigtime error: no groups from other kingdoms!
    </td></tr><?php
	} // end if results for otherawardsquery
	
 	?>
  <tr><td colspan="2" align="center">
    <a href="add_group.php">ADD GROUP</a>
  </td></tr></table></div>

<?php
include("includes/footer.php");
?>
