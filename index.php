<?php
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';
?>

<div class="bodyheader">Welcome to the <?php echo $WEAREKINGDOM; ?> College of Heralds <?php echo $PRECEDENCETITLE; ?> site!</div><br />
<p><?php echo $PRECEDENCETITLERANK; ?> is the keeper of the <a href="op.php">Order of Precedence</a> and <a href="people_alpha.php">Who's Who</a> lists, among other things.  Our current <?php echo $PRECEDENCETITLERANK; ?> is <?php echo $CURRENTPRECEDENCEHERALD; ?>.
Please report any errors to <a href="mailto:<?php echo $PRECEDENCEEMAIL; ?>"><?php echo $PRECEDENCEEMAIL; ?></a>.</p>

<p><strong>Welcome from the <?php echo $PRECEDENCETITLERANK; ?></strong> </p>
<p>If you are <?php echo $IAMKINGDOMER; ?>, please check your own listing from time to time - the database is only as good as the court reports <?php echo $PRECEDENCETITLE; ?> gets!</p>

<div class="bodyheader">
  <p>NEWS</p>
</div>

<div>
<p>September 6, 2020</p>

<p>This is a test of the new and improved Wimble DB environment. Wheeeee</p>
<p>
	<?php echo $CURRENTPRINCIPALHERALD; ?><br>
	<?php echo $PRINCIPALHERALD; ?>
</p>
</div>
<hr width="50%">
<div>
<p>January 09, 2016</p>

<p>Hi! I'm THL Thorvald Thorolfsson and I'm the new Wimble Herald!</p>

<p>In service,<br>
<?php echo $CURRENTPRECEDENCEHERALD; ?><br>
<?php echo $PRECEDENCETITLERANK; ?></p>
</div>
<?php
include 'includes/footer.php';
?>