<?
session_start();
//kill the session and all variables
session_destroy(); 
$LoggedUserID 		= "";
$LoggedUserName		= "";
$LastPoke			= time() - 100000;
include 'includes/constants.php';
include 'includes/functions.php';
include 'includes/ez_sql.php';
include 'includes/header.php';
?>


<div class="bodyheader">Logged Out</div>

<p>You've logged out. You can now close your browser session.</p>

<?
include 'includes/footer.php';
?>
