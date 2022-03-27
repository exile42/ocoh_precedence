<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if (isset($_REQUEST['GroupID']) && (is_numeric($_REQUEST['GroupID']) || ($_REQUEST['GroupID']=="ALL")))
	$GroupID = $_REQUEST['GroupID'];
else
	$err .= " Invalid value for GroupID. ";

// get info about the group
$db_query_group="SELECT Name, Notes FROM Groups WHERE GroupID=$GroupID";
$results_group = $db->get_results($db_query_group);
foreach($results_group as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};
} // end foreach

// get the subordinate groups, if any
$db_query_child_groups="SELECT GroupID as ChildGroupID FROM Groups WHERE ParentGroupID=$GroupID";
$results_child_groups = $db->get_results($db_query_child_groups);
if ($results_child_groups) {
	foreach($results_child_groups as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
			$subgroups .= "OR GroupID =$ChildGroupID ";
		};
	} // end foreach
} // end if


?>

<div class="bodyheader"><?php print "$Name"; ?></div>

<p><?php print "$Notes"; ?></p>

<table border="0" cellpadding="0" cellspacing="10" width="500">
<?php 
$db_query_recipients="SELECT PersonID, SCAName, Active FROM wPeople WHERE GroupID=$GroupID $subgroups ORDER BY Active DESC,SearchName";
$results_recipients = $db->get_results($db_query_recipients);
if ($results_recipients) {
	foreach($results_recipients as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?><tr>
		<td><a href="individual_record.php?PersonID=<?php=$PersonID?>"><?php=$SCAName?></a> <?php if ($Active==0) echo "(inactive)";?></td>
		<?php
		if ($accessWimble =="1") {
			?><td valign="middle" align="right" nowrap><a href="edit_user.php?PersonID=<?php=$PersonID?>&Action=Edit">EDIT</a> |
			<a href="edit_awards.php?PersonID=<?php=$PersonID?>">AWARDS</a>
			</td><?php
		}
		?></tr><?php 
	};  //end foreach
} else {
	?>No one is currently listed with this as their primary group<?php
} // end if($results_recipients_
?>
</table>


<?php
include("includes/footer.php");
?>
