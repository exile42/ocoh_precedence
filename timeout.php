<?php
session_start();

echo $Username . "/" . $_SESSION["LoggedUsername"] . "\n";
echo $UserID . "/" . $_SESSION["LoggedUserID"] . "\n";
echo ($Poke - 1435400000) . "/" . ($_SESSION["LastPoke"] - 1435400000). "\n";
echo (time() - 1435400000 - 10800) . "/" . (time() - 1435400000) . "\n";

//kill the session and all variables
session_destroy(); 
$Username = 0;
$UserID = 0;
$Poke = time() + 10800;
include("includes/constants.php");
include("includes/functions.php");
include("includes/ez_sql.php");
include("includes/header.php");
?>


<div class="bodyheader">Logged Out</div>

<p>You've been logged out for inactivity (3 hours). Log back in to continue working.</p>

<?php
include("includes/footer.php");
?>
