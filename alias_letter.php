<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';

if (!is_null($HTTP_GET_VARS[Letter]))
	$Letter = $HTTP_GET_VARS[Letter];

if (!is_null($HTTP_GET_VARS[by]))
	$by = $HTTP_GET_VARS [by];
else
	$by = "alias";

if ($by=="alias") {
	$sortby="a.Alias";
	$switchby="name";
} elseif ($by=="name") {
	$sortby="p.SearchName";
	$switchby="alias";
} else {
	die ("BZZT.  Bad sort value.  Try again");
}

?>

<div class="bodyheader">List of People and Possible Alias</div><br />
<center><?php WriteAlpha("alias_letter.php'; ?> <a href="alias_letter.php?Letter=ALL">ALL</a>
</center>
<p>Sort: <?php echo $by?> | <a href="alias_letter.php?Letter=<?php echo $Letter?>&amp;by=<?php echo $switchby?>"><?php echo $switchby?></a></p>

<table border="0" cellpadding="3" cellspacing="3" width="515">
<?php 
if ($Letter=="ALL")
	$letterquery="";
else
	$letterquery="AND LEFT( SCAName, 1 )='$Letter'";


$db_query="SELECT a.Alias, p.SCAName, p.PersonID FROM wPeople p, wAliases a WHERE p.PersonID = a.PersonID $letterquery ORDER BY $sortby";
$results = $db->get_results($db_query);
if ($results) {
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		}; // end while
		?><tr><?php 
		if ($by=="alias") {
			?><td align="left"><?php echo $Alias?></td>
			<td align="right"><a href="individual_record.php?PersonID=<?php echo $PersonID?>"><?php echo $SCAName?></a></td><?php 
		} else { // $by=="name"
			?><td align="left"><a href="individual_record.php?PersonID=<?php echo $PersonID?>"><?php echo $SCAName?></a></td>
			<td align="right"><?php echo $Alias?></td><?
		} // end if
		?></tr><?php 
	};  //end foreach
} else { 
	if ($by=="alias") {
		?>Nobody has an known alias that starts with <?php echo strtoupper($Letter)?>.<?
	} else { // $by=="name" 
		?>Nobody whose name starts with <?php echo strtoupper($Letter)?> has a known alias.<?
	} // end if sortby
} // end if
?>
</table>

<?
include 'includes/footer.php';
?>