<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

IF ($accessWimbleView == 0) {
        PRINT "<p>You do not have access to this file.</p>";
        include("includes/footer.php");
        die;
}

?>

<div class="bodyheader">Browse Records</div><br />

<center>
<?php WriteAlpha("browse_records.php");
?>
</center>

<?php 	IF (!$Letter) {?>
<p>Please click on a letter above to navigate the alphabetical list of people in the Outlands.</p>
<?php	} else {
		IF ($results = $db->get_results("SELECT PersonID, SCAName 
				FROM wPeople 
				WHERE SCAName LIKE '$Letter%' OR SCAName LIKE '`$Letter%'
				ORDER BY SearchName")) {
			PRINT "<div align=\"center\"><table border=\"0\" width=\"560\" ><br />";
			foreach($results as $r) {
				$PersonID 	= $r->PersonID;
				$SCAName 	= $r->SCAName;
				PRINT "<tr><td><a href=\"individual_record.php?PersonID=$PersonID\">$SCAName</a></td>";
				IF ($accessWimbleView ==1) {
					PRINT "<td align=\"right\" nowrap><a href=\"person.php?PersonID=$PersonID&Action=Edit\">VIEW DETAILS</a></td>";
				}
				PRINT "</tr>";
			}
			PRINT "</table></div>";
		} else {
		?>
			<p>There are no people that start with letter <?php=$Letter?>.</p>
		<?php
		}

	}

include("includes/footer.php");
?>
