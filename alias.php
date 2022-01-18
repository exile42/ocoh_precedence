<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if (!is_null($HTTP_GET_VARS[Letter]))
	$Letter = $HTTP_GET_VARS[Letter];
	
?>

<div class="bodyheader">List of People and Possible Alias</div><br />

<center>
<? WriteAlpha("alias_letter.php"); ?> <a href="alias_letter.php?Letter=ALL">ALL</a> 
</center>

<p>Please click on a letter above to navigate the alphabetical list of people and their aliases, or click on a group below to view an alphabetical list of people and their aliases in that group.</p>

<?
$db_query=" SELECT GroupID, Name, Active FROM Groups WHERE KingdomID=$MYKINGDOMID ORDER BY Active DESC, Name";
$results = $db->get_results($db_query);
foreach($results as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};
	?>	<p><a href="alias_group.php?GroupID=<? echo $GroupID; ?>"><? print "$Name"; ?></a> <? if ($Active==0) print "(inactive)"; ?></p><? 
};  //end foreach
?>


<?
include("includes/footer.php");
?>