<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">Welcome to the <?=$WEAREKINGDOM?> College of Heralds <?=$PRECEDENCETITLE?> site!</div><br />
<p><?=$PRECEDENCETITLERANK?> is the keeper of the <a href="op.php">Order of Precedence</a> and <a href="people_alpha.php">Who's Who</a> lists, among other things.  Our current <?=$PRECEDENCETITLERANK?> is <?=$CURRENTPRECEDENCEHERALD?>.
Please report any errors to <a href="mailto:<?=$PRECEDENCEEMAIL?>"><?=$PRECEDENCEEMAIL?></a>.</p>

<p><strong>Welcome from the <?=$PRECEDENCETITLERANK?></strong> </p>
<p>If you are <?=$IAMKINGDOMER?>, please check your own listing from time to time - the database is only as good as the court reports <?=$PRECEDENCETITLE?> gets!</p>

<div class="bodyheader">
  <p>NEWS</p>
</div>
<p>January 09, 2016</p>

<p>Hi! I'm THL Thorvald Thorolfsson and I'm the new Wimble Herald!</p>

<p>In service,<br>
<?=$CURRENTPRECEDENCEHERALD?><br>
<?=$PRECEDENCETITLERANK?></p>
<?
include("includes/footer.php");
?>