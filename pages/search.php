<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");



if (isset($_REQUEST['Type'])) {
	if ($_REQUEST['Type'] == "Name") {
		$Type = "Name";
	} elseif ($_REQUEST['Type'] == "MemberNumber") {
		$Type = "MemberNumber";
	} elseif ($_REQUEST['Type'] == "Date") {
		$Type = "Date";
	} else {
		$err .= "<br />That type of search is not available.  To search for people by the group that they live in, use the <a href=\"people_alpha.php\">Alphabetically</a> or <a href=\"people_group.php\">by Local Group</a> page.";
		$Type ="";
	}
} else {
	$Type = "";
}

if (isset($_REQUEST['SearchMe'])) {
	if (strlen($_REQUEST['SearchMe']) < 3) {
		$err .= "<br />You did not provide a search string of at least 3 letters.";
		$Type = "";
		$SearchMe="";
	}
	if ($Type == "Name")
		$SearchMe = SanitizeInputAlphaOnly(trim($_REQUEST['SearchMe']));
	elseif ($Type == "MemberNumber")
		$SearchMe = SanitizeInputNumeric(trim($_REQUEST['SearchMe']));
	elseif ($Type == "Date")
		$SearchMe = SanitizeInputDate(trim($_REQUEST['SearchMe']));
	else
		$SearchMe = "";
}


if (isset($_REQUEST['Scope']) && is_numeric($_REQUEST['Scope']))
	$Scope = $_REQUEST['Scope'];
else
	$Scope = "0";


//$aliasquery = "SELECT PersonID, Alias, KingdomID FROM wPeople WHERE RegDevice=1 AND (Alias LIKE '%$SearchMe%' OR SearchAlias LIKE '$SearchMe%') AND KingdomID=$MYKINGDOMID ORDER BY SearchAlias";

?>

<div id="content">

<?php switch($Type) {
	case "Name": 
		// first we check names
		// if you have Wimble access and the SearchMe is more than 5 characters, you can search legal names)
		$legalnamequery="";
		if ($accessWimble== "1" && strlen($SearchMe)>5) {
			$legalnamequery = "OR ModernName LIKE '%$SearchMe%'";
		}
		$kingdomlimiter="";
		if (!$Scope)
			$kingdomlimiter="AND KingdomID=$MYKINGDOMID";
//		$namequery = "SELECT PersonID, SCAName FROM wPeople WHERE (SCAName LIKE '%$SearchMe%' OR SearchName LIKE '%$SearchMe%' $legalnamequery) $kingdomlimiter ORDER BY SearchName";
		$namequery = "(SELECT PersonID, SCAName as Name, SearchName as Search, 0 as IsAlias, Deceased FROM wPeople WHERE (SCAName LIKE '%$SearchMe%' OR SearchName LIKE '%$SearchMe%' $legalnamequery) $kingdomlimiter) UNION ALL (SELECT A.PersonID, A.Alias as Name, A.SearchAlias as Search, 1 as IsAlias, P.Deceased FROM wAliases as A, wPeople as P WHERE A.PersonID = P.PersonID AND (A.Alias LIKE '%$SearchMe%' OR A.SearchAlias LIKE '%$SearchMe%' $legalnamequery) $kingdomlimiter) ORDER BY Search";
		if ($results = $db->get_results($namequery)) {
			?><br />People whose names or aliases contain "<?php=$SearchMe?>":<br /><br /><?php
			foreach($results as $r) {
				$PersonID 	= $r->PersonID;
				$SCAName 	= $r->Name;
				$IsAlias	= $r->IsAlias;
				$Deceased	= $r->Deceased;
				?><a href="individual_record.php?PersonID=<?php=$PersonID?>"><?php=$SCAName; ?></a> <?php
				if ($IsAlias) { ?> (alias) <?php };
				if ($Deceased) { ?> (deceased) <?php };
				if ($accessOPView== "1") {
				?> <a href="view_person.php?PersonID=<?php=$PersonID?>">(DETAILS)</a><?php
				}
				if ($accessWimble== "1") {
					?> <a href="edit_user.php?PersonID=<?php=$PersonID?>&amp;Action=Edit">(EDIT)</a><?php
				}
				?><br /><?php
			}
		} else {
			?><p>There are no people whose names or aliases contain "<?php=$SearchMe?>".</p><?php
		}

/*		// now we check aliases
		$aliasquery = "SELECT P.PersonID, A.Alias, P.SCAName FROM wPeople as P, wAliases as A WHERE P.PersonID = A.PersonID AND (A.Alias LIKE '%$SearchMe%' OR A.SearchAlias LIKE '%$SearchMe%') $kingdomlimiter ORDER BY SearchAlias";
		if ($results = $db->get_results($aliasquery)) {
			?><br />People whose aliases contain "<?php=$SearchMe?>":<br /><br /><?php
			foreach($results as $r) {
				$PersonID 	= $r->PersonID;
				$Alias		= $r->Alias;
				$SCAName 	= $r->SCAName;
				?> <?php echo $Alias; ?> (<a href="individual_record.php?PersonID=<?php=$PersonID?>"><?php=$SCAName?></a>)<?php
				if ($accessOPView== "1") {
					?> <a href="view_person.php?PersonID=<?php=$PersonID?>">(DETAILS)</a><?php
				}
				if ($accessWimble== "1") {
					?> <a href="edit_user.php?PersonID=<?php=$PersonID?>&amp;Action=Edit">(EDIT)</a><?php
				}
				?><br /><?php
			}
		} else {
			?><p>There are no people whose aliases contain "<?php=$SearchMe?>".</p><?php
		}
*/
// End case "Name"
		break;

	case "MemberNumber": 
		$numberquery = "SELECT PersonID, SCAName as Name, SearchName, Deceased FROM wPeople WHERE MemberNumber = $SearchMe ORDER BY SearchName";
		if ($results = $db->get_results($numberquery)) {
			?><br />People whose membership number is <?php=$SearchMe?>:<br /><br /><?php
			foreach($results as $r) {
				$PersonID 	= $r->PersonID;
				$SCAName 	= $r->Name;
				$Deceased	= $r->Deceased;
				?><a href="individual_record.php?PersonID=<?php=$PersonID?>"><?php=$SCAName; ?></a> <?php
				if ($Deceased) { ?> (deceased) <?php };
				if ($accessOPView== "1") {
				?> <a href="view_person.php?PersonID=<?php=$PersonID?>">(DETAILS)</a><?php
				}
				if ($accessWimble== "1") {
					?> <a href="edit_user.php?PersonID=<?php=$PersonID?>&amp;Action=Edit">(EDIT)</a><?php
				}
				?><br /><?php
			}
		} else {
			?><p>There are no people whose membership number is <?php=$SearchMe?>.</p><?php
		}
// end case "MemberNumber"
		break;


	case "Date": 
		// first we need to sanitize the date
		// for now, we just ask for it in the proper format
		$datequery = "SELECT AG.AwardsGivenID, AG.SortRank, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate, A.AwardID, A.Name as AwardName, P.PersonID as PersonID, P.SCAName as SCAName, P.SearchName as SearchName 
		FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID INNER JOIN wPeople P ON AG.PersonID = P.PersonID 
		WHERE AG.Date='$SearchMe' AND AG.Visible=1 AND AG.Resigned=0 ORDER BY AG.SortRank, SearchName";
		if ($results = $db->get_results($datequery)) {
			?><br />People who received awards on  "<?php=$SearchMe?>":<br /><br />
			<table border="0" cellpadding="3" cellspacing="3" width="560">
			<?php foreach($results as $r) { 
				while(list($name,$value) = each($r)) { //name all values
					$$name = $value;
				};
				?>
			<tr>
			<td valign="middle" align="left"><a href="individual_record.php?PersonID=<?php=$PersonID?>"><?php=$SCAName?></a>
			<?php if ($accessOPView== "1") {
					?> <a href="view_person.php?PersonID=<?php=$PersonID?>">(DETAILS)</a><?php
				} ?>
			<?php if ($accessWimble== "1") {
					?> <a href="edit_user.php?PersonID=<?php=$PersonID?>&amp;Action=Edit">(EDIT)</a><?php
				} ?>
			</td>
			<td valign="middle" align="right"><?php=$AwardName?></td>
			</tr>
			<?php 
			};  //end foreach
			?>
			</table>
			<?php
		} else {
			?><p>There were no awards given on <?php=$SearchMe?>. (that we know of - if you think this is in error, contact <?php=$PRECEDENCETITLE?>!)</p><?php
		}
// end case "Date"
		break;
		
	default: // no search term given to us, print "advanced" search options
		?>
		<div class="form">
		<br /> 
		<div class="menuheader">
		Search By Name or Alias</div>
		<br /><form action="search.php" method="get">
		<input type="text" name="SearchMe" value="" size="20" maxlength="60">
		<input type="hidden" name="Type" value="Name">
		<input name="Submit" type="submit" value="Go"><br />
		<input type="checkbox" name="Scope" value="1"> Check this box to also search out-of-kingdom names 
		</form>
			
		<br />		

<?php		
// If Wimble, show membership number search
		if ($accessWimble== "1") { ?>
            <div class="menuheader">
            Search By Membership Number</div>
            <br /><form action="search.php" method="get">
            <input type="text" name="SearchMe" value="" size="10" maxlength="15">
            <input type="hidden" name="Type" value="MemberNumber">
            <input name="Submit" type="submit" value="Go"><br />
            </form>
                
            <br />
			This only does exact matches.	<?php
		}
// end If Wimble, show membership number search
?>

        <div class="menuheader">Search By Group</div>
		<br /><form action="by_group.php" method="get">
		<select name="GroupID">
		<option value="">SELECT ONE</option>
		<?php 
		$results = $db->get_results("SELECT GroupID, Name FROM Groups WHERE KingdomID=$MYKINGDOMID and Active=1 ORDER BY Name");
		foreach($results as $r) {
			$GroupID = $r->GroupID;
			$GroupName   = $r->Name;
			?><option value="<?php=$GroupID?>"><?php=$GroupName?></option><?php
		}
		?>
		</select>
		<input name="Submit" type="submit" value="Go">
		</form>

		<br />		<div class="menuheader">Search By Kingdom Award</div>
		<br /><form action="by_single_award.php" method="get">
		<select name="AwardID">
		<option value="">SELECT ONE</option>
		<?php 
		// tricky.  only want kingdom awards, but also "Society" awards like AoA and peerages
		$results = $db->get_results("SELECT AwardID, Name FROM wAwards WHERE ((KingdomID=$MYKINGDOMID AND GroupID=$MYGROUPID) OR KingdomID=$SOCIETYKINGDOMID) AND Rank >= 10 AND Closed=0 ORDER BY Rank asc, Name");
		foreach($results as $r) {
			$AwardID = $r->AwardID;
			$Name   = $r->Name;
			?><option value="<?php=$AwardID?>"><?php=$Name?></option><?php
		}
		?>
		</select>
		<input name="Submit" type="submit" value="Go">
		</form>
		<br />		

		<div class="menuheader">Search By Date</div>
		<br /><form action="search.php" method="get">
		<input type="text" name="SearchMe" value="" size="20" maxlength="60"> 
		<input type="hidden" name="Type" value="Date">
		<input name="Submit" type="submit" value="Go">
		</form>
		YYYY-MM-DD format please.

		<br />		
        <div class="menuheader">Search By Reign</div>
		<br /><form action="by_reign.php" method="get">
		<select name="ReignID">
		<?php 
		$results = $db->get_results("SELECT ReignID, RUseName, CUseName FROM wReigns WHERE GroupID=$MYGROUPID ORDER BY ReignOrder DESC");
		foreach($results as $r) {
			$thisRID = $r->ReignID;
			$Ruler   = $r->RUseName;
			$Consort = $r->CUseName;
			if ($ReignID == $thisRID) { $s = "selected"; } else { $s = ""; }
			?><option value="<?php=$thisRID?>" <?php=$s?>><?php=$Ruler?> and <?php=$Consort?></option><?php
		}
		?><option value="">Unknown reign</option>
		</select>
		<input type="hidden" name="Type" value="Reign">
		<input name="Submit" type="submit" value="Go">
		</form>
		<br />
		Not acurate before the reign of B&eacute;la II and Claudia.
	
	  </div>	
		<?php
		break;
		
	}  // end switch
?>
</div>

<?php
include("includes/footer.php");
?>
