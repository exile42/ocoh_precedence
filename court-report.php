<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">What <?php=$PRECEDENCETITLE?> Wants in a Court Report</div>
<br />
<p>Court reports should be sent to the nobility who hosted the court, <?php=$PRECEDENCETITLE?>,
  the Kingdom Scribe, the local herald or the court herald (whichever one you
  aren't), and a courtesy copy to <?php=$PRINCIPALHERALD?>. <?php=$PRECEDENCETITLE?> can be reached at
  <a href="mailto:<?php=$PRECEDENCEEMAIL?>"><?php=$PRECEDENCEEMAIL?></a>, and <?php=$PRINCIPALHERALD?> at <a href="mailto:<?php=$PRINCIPALHERALDEMAIL?>"><?php=$PRINCIPALHERALDEMAIL?></a>. Other updates and corrections should be
sent to the <?php=$PRECEDENCETITLERANK?> (please allow time for <?php=$PRECEDENCETITLE?> to doublecheck).</p>
<p>Please use &quot;Court Report&quot; and the name of the event in the subject line, so
  that I can easily find your email.</p>
<p>When sending court reports, updates, or corrections, please include as much
of the following as possible:</p>
<ul>
  <li>Name of event and DATE of award/court (especially important for multi-day
    events) </li>
  <li>SCA and legal names of the recipient (used as a cross-reference)</li>
  <li>Award name </li>
  <li>Home branch of recipient </li>
  <li>If a scroll was given, if it was a promissory or final, and the name of the scribe(s) </li>
  <li>Membership number, if available (used as a backup cross-reference)</li>
</ul>
<p>Thank you for your diligence - the Wimble database is only as good as the
  information you send me.</p>
<p>If you are a baronial herald, don't be afraid to report the kingdom awards
  as well. This makes a lovely backup for the report of <?php=$COURTHERALD?>.</p>
<p>Someday, I hope to have a method where you can submit Court reports through
  this website, but for now email will suffice.</p>
<?php
include("includes/footer.php");
?>