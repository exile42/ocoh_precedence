<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">People of <?=$WEAREKINGDOMCOLLECTIVE?> (Alphabetically)</div><br />

<center>
<? WriteAlpha("people_alpha.php"); ?>
</center>

<? 	
if (isset($_REQUEST['Letter']) && !is_null($_REQUEST['Letter']) && (strlen($_REQUEST['Letter'])==1))
	$Letter = $_REQUEST['Letter'];

if (!isset($Letter)) {
	?><p>Please click on a letter above to navigate the alphabetical list of people in <?=$WEAREKINGDOMCOLLECTIVE?>.</p><?	
} else {
	$alpha_query = "SELECT PersonID, SCAName FROM wPeople WHERE SearchName LIKE '$Letter%' OR SearchName LIKE '`$Letter%' ORDER BY SearchName";
	if ($alpha_results = $db->get_results($alpha_query)) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><br /><?
		foreach($alpha_results as $r) {
			$PersonID 	= $r->PersonID;
			$SCAName 	= $r->SCAName;
			
			?><tr><td><a href="individual_record.php?PersonID=<?=$PersonID?>"><?=$SCAName?></a></td><?

			if ($accessWimble =="1") {
				?><td valign="middle" align="right" nowrap><a href="edit_user.php?PersonID=<?=$PersonID?>&Action=Edit">EDIT</a> |
				<a href="edit_awards.php?PersonID=<?=$PersonID?>">AWARDS</a> 
				</td><?
			}
			?></tr><?
		} // end foreach
		?></table><?
	} else {
		?><p>There are no people that start with letter <?=$Letter?>.</p><?
	}

} // end if Letter

include("includes/footer.php");
?>
