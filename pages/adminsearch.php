<?php
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

IF ($accessWimble == 0) {
        PRINT "<p>You do not have access to this file.</p>";
        include("includes/footer.php");
        die;
}

?>



<div class="bodyheader">Search Records</div><br />

<p>Simple search for individual records by SCA Name, Mundane Name, Group, Kingdom, Blazon, and Membership Number.
Will pull up an individual record (or a list of people that match the search, and then ind. records).  Ind.
record page will show output like mocked up <a href="/individual_record.php">here</a>.</p>



<?php

include("includes/footer.php");
?>
