<?php

// for security, zero these out NOW
$accessWimble = 0;
$accessCourtReport = 0;
$accessWimbleView = 0;

	//Only do this if we are not in the process of logging in
	if ($_REQUEST["Action"] != "Login") 
	{ 
		//Start the Session
		session_start();

		// for security, zero these out first
		// Poke is 3-hour adjusted to stop non-logged-in timeout
		$Username = "";
		$UserID = 0;
		$Poke = time() + 10800;

		// then pull from the session variables
		// to make all authenticated goodness
		if (isset ($_SESSION["LoggedUsername"]) && isset($_SESSION["LoggedUserID"]) && isset($_SESSION["LastPoke"]) ){
			$Username	= $_SESSION["LoggedUsername"];
			$UserID		= $_SESSION["LoggedUserID"];
			$Poke		= $_SESSION["LastPoke"];
			$WasSet = 1;
			// Check if the last time you loaded a 
			// page was more than 3 hours ago
			// if so, you get timed out and logged out.
			if (time() > ( 10800 + $Poke))
			{
				header("location: timeout.php");
			}
			// we're not expired, update the timestamp to now.
			// this lets you keep pushing out the 3 hour limit.
			$_SESSION["LastPoke"] = time();
		} // end if session is set

		// Now we need to figure out what you have access to.
		$access_query = "SELECT * FROM Users as U, UserAccess as UA WHERE U.PersonID=UA.UserAccessID and U.Username='$Username' and U.PersonID=$UserID";
		if ($access_results  = $db->get_results($access_query)) {
			foreach($access_results as $r) {
				$accessWimble		= $r->Wimble;
				$accessCourtReport	= $r->CourtReport;
				$accessWimbleView	= $r->WimbleView;
			} //end foreach
		} //end if $access_results
	} //end if pagename
	$Now = date("Y-m-d H:i:s");
	$IPAddress  = getenv ("REMOTE_ADDR");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" href="/css/print.css" type="text/css" />
<title><?php=$WEAREKINGDOM?> College of Heralds / Office of the <?php=$PRECEDENCETITLERANK?></title>
<meta name="robots" content="index, follow, nocache, noarchive, snippet" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script language="JavaScript" type="text/javascript">
	function confirm_delete(str)
	{
		if (confirm("Are you sure you want to delete " + str + "?")==true)
			return true;
		else
			return false;
	}
</script>

<script type="text/javascript" src="js/popup.js"></script>

</head>

<body>

<div id="container">

<div id="page">

<div id="login">
<?php
if ($Username == "" or $UserID == 0) {?>	
	you are not logged in |
	<a href="login.php">sign in</a>
<?php 	} else { ?>
	you are logged in as <strong><?php echo $Username;?></strong><br /> 
	[ <a href="/logout.php">sign out</a> ]<br />
<?php	} ?>
</div>

<div id="menu">
<div class="menucontent">


<div class="menuheader">People of <?php=$WEAREKINGDOMCOLLECTIVE?></div><br />
<a href="/people_alpha.php">Alphabetically</a><br />
<a href="/people_group.php">By Local Group</a><br />
<a href="/alias.php">By Alias (alphabetically)</a><br />
<a href="/people_award.php">Award Membership Lists</a> <strong>(renamed!)</strong><br />
<a href="/op.php">Order of Precedence</a><br />
<br />

<div class="menuheader">Award Information</div><br />
<a href="/awards_alpha.php">Alphabetically</a> <strong>(updated!)</strong><br />
<a href="/awards_initials.php">By Initials</a> <strong>(updated!)</strong><br />
<a href="/awards_group.php">By Kingdom or Barony</a> <strong>(updated!)</strong><br />
<a href="/awards_all.php">All Awards and Orders for the Outlands and Baronies</a> <strong>(new!)</strong><br />
<br />

<div class="menuheader"><?php=$WEAREKINGDOM?> Royalty</div><br />
<a href="/tribute.php">A Tribute to Our Royalty</a><br />
<!-- <a href="awards_reign.php">Awards Given By Reign</a><br /> // add this in later -->
<br />

<div class="menuheader">Search</div><br />
<a href="/search.php">Search by Name, Alias, Group, Date</a><br />
<br />

<div class="menuheader">Information/Status</div><br />
<a href="/court-report.php">How to Write a Court Report</a><br />
<a href="/news.php">News from Wimble</a><br />
<br />

<?php
if ($accessWimble == 1) {
?>
<div class="menuheader">Admin</div><br >

<a href="/edit_user.php?Action=Add">Add Person</a><br />
<a href="/people_alpha.php">Manage Records</a><br />
<a href="by_visible.php">Find invisible awards</a><br />
<a href="/by_reign.php">List Awards by Reign</a><br />
<a href="/list_awards.php">List Awards</a> / <a href="/add_award.php">Add Awards</a><br />
<a href="/list_groups.php">List Groups</a> / <a href="/add_group.php">Add Group</a><br />
<a href="/list_reigns.php">List Reigns</a> / <a href="/add_reign.php">Add Reign</a><br />
<a href="/admin/">Converter Scripts</a><br />
<br />
<?php
}// end if $accessWimble

if ($accessWimbleView == 1) {
?>
<div class="menuheader">LOCAL HERALDS</div><br >
<!-- below for admins and local heralds -->
<a href="/browse_records.php">Browse Records</a><br />
<a href="/submit_update.php">Submit Update</a><br />
<a href="/print_report.php">Generate Report</a><br />
<a href="/search.php">Search Records</a><br /><br />
<?php
} // end if $accessWimbleView
?>

<div class="menuheader">Useful Links</div><br />
<a href="<?php=$KINGDOMURL?>">Kingdom of <?php=$WEAREKINGDOMCOLLECTIVE?></a><br />
<a href="<?php=$HERALDURL?>"><?php=$WEAREKINGDOM?> College of Heralds</a><br />
<a href="<?php=$SCRIBEURL?>"><?php=$WEAREKINGDOM?> College of Scribes</a><br />
<a href="/other_ops.php">Other Orders of Precedence</a><br /><br />

<div class="menuheader">Contact</div><br />
<a href="mailto:<?php=$PRECEDENCEEMAIL?>">Email <?php=$PRECEDENCETITLERANK?></a><br /><br />

<div class="menuheader">Disclaimer</div><br />
This site is not sanctioned by, or an official instrument of the Society for Creative Anachronism (SCA), Inc., nor is it sanctioned by, or an official instrument of any office or subgroups of the SCA and as such does not delinate policy or regulations for the above. This site exists to provide information and to educate.<br /><br />
</div>
</div>


<div id="body">
<div class="bodycontent">
<div id="title">
<a href="/index.php"><img src="/images/title.jpg" height="87" width="406" alt="<?php=$WEAREKINGDOM?> College of Heralds - Office of the <?php=$PRECEDENCETITLERANK?>" title="<?php=$WEAREKINGDOM?> College of Heralds - Office of the <?php=$PRECEDENCETITLERANK?>" border="0" /></a>
</div>


