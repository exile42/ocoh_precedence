<?
include 'includes/constants.php';
include 'includes/ez_sql.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" media="print" href="/print.css" type="text/css" />
<title><?php echo $WEAREKINGDOM?> College of Heralds / Office of the <?php echo $PRECEDENCETITLERANK?></title>
<meta name="robots" content="index, follow, nocache, noarchive, snippet" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

</head>

<body>


<div class="bodyheader">List ALLLLLLLLLLLL Groups in the system</div>
<p>Any items which are wonky will be at the very end and only have GroupIDs and KingdomIDs, not names (since I can't tell which of them exist or don't exist).</p><br />
<br />
  <table border="1" cellpadding="4" cellspacing="0">
  <tr><td>Kingdom</td><td>Group</td><td>Designator</td><td>Parent Group</td><td>Inactive?</td><td>Barony Order</td><td>Reg&nbsp;Name/ Reg&nbsp;Device</td><td>Blazon</td><td>Notes</td></tr>

	<?php 

	
	$groupsquery="SELECT G.GroupID, G.Name AS GroupName, G.ParentGroupID, G.KingdomID, G.Active, G.BaronyOrder, G.RegName, G.RegDevice, G.Blazon, G.Notes, K.Name AS KingdomName, G.Designator FROM Groups G INNER JOIN Kingdoms K ON G.KingdomID = K.KingdomID WHERE 1 ORDER BY KingdomName, G.BaronyOrder, GroupName";
	
	if ($results = $db->get_results($groupsquery)) {
		foreach($results as $r) {
			$GroupID	= $r->GroupID;
			$ParentGroupID	= $r->ParentGroupID;
			$Kingdom	= $r->KingdomName;
			$Group	= $r->GroupName;
			$Designator	= $r->Designator;
			$Active	= $r->Active;
			$BaronyOrder	= $r->BaronyOrder;
			$RegName	= $r->RegName;
			$RegDevice	= $r->RegDevice;
			$Blazon	= $r->Blazon;
			$Notes	= $r->Notes;
			if (!$Active) { $Activetext="(inactive/disbanded)"; } else { $Activetext=""; }
		  
		  	// get Parent group if one exists
			if (isset($ParentGroupID)) {
				$db_query_parent="SELECT Name as ParentName FROM Groups WHERE GroupID=$ParentGroupID";
				$result_parent = $db->get_results($db_query_parent);
				if ($result_parent) {
					foreach($result_parent as $r) { 
						$ParentName = $r->ParentName;
					} // end foreach
				} // end if
			} else {
				$ParentName = "";
			}

		  ?>
		  <tr><td><?php echo $Kingdom?></td>
		  <td><?php echo $Group?></td>
		  <td><?php echo $Designator?></td>
		  <td><?php echo $ParentName?></td>
		  <td><?php echo $Activetext?></td>
		  <td><?php echo $BaronyOrder?></td>
		  <td><?php echo $RegName?>/<?php echo $RegDevice?></td>
		  <td><?php echo $Blazon?></td>
		  <td><?php echo $Notes?></td>
		  </tr>
		  <?
		  $ParentName = "";
		} // end foreach
	} else {
    ?><tr><td colspan="2" align="center">
      Bigtime error: no groups!
    </td></tr><?
	} // end if results 
	
 	?>
  </table>

</body>
</html>