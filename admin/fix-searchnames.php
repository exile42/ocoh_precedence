<?
include '../includes/constants.php';
include '../includes/ez_sql.php';
include '../includes/functions.php';
include '../includes/converter.php';
include '../includes/header.php';
?>

<div class="bodyheader">Magical Updating Thingy - updates wPeople and wAliases</div><br />

<?
if ($results = $db->get_results("SELECT PersonID, SCAName, SearchName FROM wPeople WHERE 1 ORDER BY PersonID DESC")) {
	?><table border="0" width="560" cellpadding="3" cellspacing="3"><br /><?
	foreach($results as $r) {
		$PersonID 		= $r->PersonID;
		$SCAName 		= $r->SCAName;
		$SearchName 		= $r->SearchName;
		$NewSearchName	= convert_from_utf8_to_ascii($SCAName);
		$NewSearchName = str_replace("'", "", $NewSearchName);
		$NewSearchName = str_replace("`", "", $NewSearchName);

		?><tr>
		<td><?php echo $PersonID?></td> <td><?php echo $SCAName?></td> <td>o: <?php echo $SearchName?></td> <td>n: <?php echo $NewSearchName?></td><?
		$query = "UPDATE wPeople SET SearchName='$NewSearchName' WHERE PersonID=$PersonID";
		if ($results = $db->get_results($query) || mysql_affected_rows() == 0) {
			?><td>SUCCESS</td><?
		} else {
			?><td>FAIL <?php echo $query?></td><?
		}
		?></tr><?
	}
	?></table><?
}

if ($results = $db->get_results("SELECT AliasID, PersonID, Alias, SearchAlias FROM wAliases WHERE 1 ORDER BY AliasID DESC")) {
	?><table border="0" width="560" cellpadding="3" cellspacing="3"><br /><?
	foreach($results as $r) {
		$PersonID 	= $r->PersonID;
		$AliasID 	= $r->AliasID;
		$Alias 		= $r->Alias;
		$SearchAlias	= $r->SearchAlias;
		$SCAName = SCANameFromPersonID($PersonID, $db);
		$NewSearchAlias	= convert_from_utf8_to_ascii($Alias);
		$NewSearchAlias = str_replace("'", "", $NewSearchAlias);
		$NewSearchAlias = str_replace("`", "", $NewSearchAlias);
		?><tr>
		<td><?php echo $PersonID?></td> <td><?php echo $SCAName?></td> <td><?php echo $Alias?></td> <td>o: <?php echo $SearchAlias?></td> <td>n: <?php echo $NewSearchAlias?></td><?
		$query = "UPDATE wAliases SET SearchAlias='$NewSearchAlias' WHERE AliasID=$AliasID";
		if ($results = $db->get_results($query) || mysql_affected_rows() == 0) {
			?><td>SUCCESS</td><?
		} else {
			?><td>FAIL <?php echo $query?></td><?
		}
		?></tr><?
	}
	?></table><?
}

include '../includes/footer.php';
?>