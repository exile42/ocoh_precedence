<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");

if (!is_null($_GET[AwardID]))
	$AwardID = $_GET[AwardID];

$db_query_award="SELECT Name, Charter, Filename FROM wAwards WHERE AwardID=$AwardID";

$results_award = $db->get_row ($db_query_award);

$Name =$results_award->Name;
$Charter = $results_award->Charter;
$Filename = $results_award->Filename;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" href="http://wimble.outlandsheralds.org/css/charter.css" type="text/css" />
<title><?php=$Name?></title>
</head>
<br />
<div class="bodyheader"><?php=$Name?></div>
<br />
<br />
<center>
<img src="images/badges/<?php=$Filename?>" border="0" />
</center>


</body>
</html>
