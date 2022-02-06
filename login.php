<?php
include("includes/constants.php");
include("includes/functions.php");
include("includes/ez_sql.php");
//header is called conditionally below

if ($_SESSION["LoggedUserID"] ==0 OR $_SESSION["LoggedUserID"] == "") { //they're not logged in already

	$IPAddress  = getenv ("REMOTE_ADDR"); //for logging purposes
	$Now 		= date("Y-m-d H:i:s");    //for logging purposes
	
	if ($_POST["Action"] == "Login") {
		$Username = trim($_POST['Username']); //from the form
		$Password = trim($_POST['Password']);
		if ($r = $db->get_row("SELECT UserID FROM Users WHERE Username='$Username' 
			AND Password='$Password'")) { 
			//if you get results, then process the login
			$UserID	   		= $r->UserID;
			$LastPoke		= time();
			//set last login information
			//log that they've logged in from this address

			$db->query("UPDATE Users SET LastLogin='$Now', LastLoginIP='$IPAddress' WHERE UserID='$UserID'");
			$db->query("INSERT INTO ActivityLog
				(WhenDone, UserID, Activity, IPAddress) 
				VALUES 
				('$Now', '$UserID', 'successful login u: $Username', '$IPAddress')");

			//set the session
			session_start();
			$_SESSION["LoggedUsername"] = $Username;
			$_SESSION["LoggedUserID"] = $UserID;
			$_SESSION["LastPoke"] = $LastPoke;
			header("location: index.php"); //redirects user to the homepage with their logged in status
		} else {
			
			//login failure, let them try again, but log it as well.
			$UserID = 0;
			include("includes/header.php");
			?><p class="error">Your login failed. Please try again.</p><?php
			ShowLoginForm(); //this function is in includes/functions.php
			$db->query("INSERT INTO ActivityLog (WhenDone, UserID, Activity, IPAddress) VALUES ('$Now', '$UserID', 'failed login: u: $Username p: $Password', '$IPAddress')");
		}
	} else  {
		//no submit
		include("includes/header.php");
		?><p>To login, please complete the form below.</p><?php
		ShowLoginForm(); //this function is in includes/functions.php
	}
} else {
	?><p>You're already logged in. </p><?php
	include("includes/footer.php");
	die;

} //end if
include("includes/footer.php");
?>
