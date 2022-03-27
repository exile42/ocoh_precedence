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

<div class="bodyheader">List all the Kingdoms in the system</div>
<p>Any items which are wonky will be at the very end, but there had better not be any wonky things in this list or there's a definite problem.  No, the "SCA" kingdom having a Creation Order of zero is NOT wonky - it really is supposed to be like that.</p><br />
<br />
  <div align="center">
  <table border="0" cellpadding="4" cellspacing="0" width="550">
  <tr class="tableheader"><td>Kingdom</td><td>Creation Order</td></tr>

	<?php 
	$kingdomsquery="SELECT K.* FROM Kingdoms K ORDER BY CreationOrder";
		
	if ($results = $db->get_results($kingdomsquery)) {
		foreach($results as $r) {
		  $KingdomID	= $r->KingdomID;
		  $Kingdom	= $r->Name;
		  $CreationOrder	= $r->CreationOrder;
		  ?>
		  <tr><td><?php=$Kingdom?></td>
		  <td><?php=$CreationOrder?></td>
		  </tr>
		  <?php
		} //end foreach
	} else {
    ?>
	<tr><td colspan="2" align="center">
      Bigtime error: no kingdoms!  Seriously, how is this site working at all without that!??!
    </td></tr><?php
	} // end if results for outlandsawardsquery
	
 	?>
  <tr><td colspan="2" align="center">
    <a href="add_kingdom.php">ADD KINGDOM</a>
  </td></tr></table></div>

<?php
include("includes/footer.php");
?>
