<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';


if (!is_null($HTTP_GET_VARS[AwardID]))
	$AwardID = $HTTP_GET_VARS[AwardID];

$db_query_award="SELECT Name, Charter, Filename FROM wAwards WHERE AwardID=$AwardID";

$results_award = $db->get_row ($db_query_award);

$Name =$results_award->Name;
$Charter = $results_award->Charter;
$Filename = $results_award->Filename;

?>

<?
include 'charters/$Charter");
?>
