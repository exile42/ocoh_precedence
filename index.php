<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">Welcome to the <?php=$WEAREKINGDOM?> College of Heralds <?php=$PRECEDENCETITLE?> site!</div><br />
<p><?php=$PRECEDENCETITLERANK?> is the keeper of the <a href="op.php">Order of Precedence</a> and <a href="people_alpha.php">Who's Who</a> lists, among other things.  Our current <?php=$PRECEDENCETITLERANK?> is <?php=$CURRENTPRECEDENCEHERALD?>.
Please report any errors to <a href="mailto:<?php=$PRECEDENCEEMAIL?>"><?php=$PRECEDENCEEMAIL?></a>.</p>

<p><strong>Welcome from the <?php=$PRECEDENCETITLERANK?></strong> </p>
<p>If you are <?php=$IAMKINGDOMER?>, please check your own listing from time to time - the database is only as good as the court reports <?php=$PRECEDENCETITLE?> gets!</p>

<div class="bodyheader">
  <p>NEWS</p>
</div>
<p>January 09, 2016</p>

<p>Hi! I'm THL Thorvald Thorolfsson and I'm the new Wimble Herald!</p>

<p>In service,<br>
<?php=$CURRENTPRECEDENCEHERALD?><br>
<?php=$PRECEDENCETITLERANK?></p>
<?php
include("includes/footer.php");
?>