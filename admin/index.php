<?
include("../includes/constants.php");
include("../includes/ez_sql.php");
include("../includes/functions.php");
include("../includes/header.php");


if ($accessWimble != 1) { ?>
<div class="bodyheader"><strong>Administrative Area</strong></div>
	<p>You must be logged in as Wimble to access these pages.</p>
	<?
	include("../includes/footer.php");
	die;
	}
?>
	<p>Welcome Wimble!  Would you like to play a game?</p>
	<p><a href="convertA.php">Convert Awards</a></p>
	<p><a href="convertAG.php">Convert Awards Given</a></p>
	<p><a href="convertP.php">Convert People</a></p>
	<?

include("../includes/footer.php");
?>