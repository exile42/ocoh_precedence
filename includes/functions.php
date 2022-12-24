<?php
//this file contains global functions that are used on multiple pages.

function ShowLoginForm() {
?> <div align="center">
   <div class="formarea" style="width:350px;">
	<form action="login.php" method="post">
	<fieldset class="formfieldset">
    	<legend>Login</legend>
		<input type="hidden" name="Action" value="Login" />
		<label for="Username" class="required">Username:</label>
		<input type="text" name="Username" id="Username" value="" maxlength="16" />
		<br />
		<br />
		<label for="Password" class="required">Password:</label>
		<input type="Password" name="Password" id="Password" maxlength="16" />
		<br />
		<br />
		<label for="send"></label>
		<input type="submit" name="send" id="send" class="button" value="Login" /><br /><br />
		<a href="forgot_password.php">Forgot password?</a>
	</fieldset>
	</form>
	</div>
	</div>
<?php
}
////////////////////////////////////////////////////////////////////////////////////
function format_date_pretty($inputDate) { //from mysql to pretty, date only
  $split = explode("-", $inputDate);
  $outputDate = "" . $split[1] . "/" . $split[2] . "/" . $split[0];
  Return $outputDate;
}
////////////////////////////////////////////////////////////////////////////////////
function NiceDate($in) {
  	$split = explode("-", $in);
	$output = date("F j, Y",mktime(0,0,0,$split[1],$split[2], $split[0]));
	return $output;
}
////////////////////////////////////////////////////////////////////////////////////
function format_date_no_time($in_date) { //for display
	$splitdate	= explode(" ", $in_date);
	$date		= $splitdate[0];
	$split 		= explode("-", $date);
	$outputDate = "" . $split[1] . "." . $split[2] . "." . $split[0];
	return $outputDate;
}
////////////////////////////////////////////////////////////////////////////////////
function format_date_time_pretty($in_date) { //from mysql to pretty with time
	global $timediff;
	If ($in_date != "") {
		$splitdate	= explode(" ", $in_date);
		$date		= $splitdate[0];
		$time 	 	= $splitdate[1];
		$timesplit  = explode(":", $time);
		$hour 	 	= $timesplit[0] + $timediff;
		$minute 	= $timesplit[1];
		$time 	 	= $hour . ":" . $minute;
		$split 		= explode("-", $date);
		$outputDate = "" . $split[1] . "." . $split[2] . "." . $split[0];
		$outputDate .= " at " . $time;
	} else { $outputDate = "N/A"; }
  Return $outputDate;
}
////////////////////////////////////////////////////////////////////////////////////
Function WriteAnyDropDown($Current, $Which) {
	PRINT "<select name=\"" . $Which .  "\">";
	$Current = trim($Current);
	If ($Current == 1 ) { 
		PRINT "<option value=\"1\" selected>$Which</option>
			<option value=\"0\">Not $Which</option>";
	} elseif ($Current == 0 ) {
		PRINT "<option value=\"1\">$Which</option>
			<option value=\"0\" selected>Not $Which</option>";
	} else {
		PRINT "<option value=\"0\" select>-- SELECT ONE --</option>
			<option value=\"1\">$Which</option>
			<option value=\"0\">Not $Which</option>";
	}
	PRINT "</select>";
}
////////////////////////////////////////////////////////////////////////////////////
Function WriteYNDropDown($Current, $Which) {
	PRINT "<select name=\"" . $Which .  "\">";
	$Current = trim($Current);
	If ($Current == 1 ) { 
		PRINT "<option value=\"1\" selected>Yes</option>
			<option value=\"0\">No</option>";
	} elseif ($Current == 0) {
		PRINT "<option value=\"1\">Yes</option>
			<option value=\"0\" selected>No</option>";
	} elseif ($Current == "") {
		PRINT "<option value=\"0\" selected>-- SELECT ONE --</option>
			<option value=\"1\">Yes</option>
			<option value=\"0\">No</option>";
	}
	PRINT "</select>";
}
////////////////////////////////////////////////////////////////////////////////////
Function YNFromBinary($Binary, $Inverse=0) {
	if (!$Inverse) {
		return $Binary ? "Yes" : "No";
	} else {
		return $Binary ? "No" : "Yes";
	}
	return "Unknown";
}
////////////////////////////////////////////////////////////////////////////////////
function extract_int ($str) {
 ereg ('[^0-9]*([0-9]+)[^0-9]*', $str, $regs);
 return (intval ($regs[1]));
}
////////////////////////////////////////////////////////////////////////////////////
function WriteDropDown($VarName, $Current, $TableName) {
	global $db;
	IF ($Collect = $db->get_results("SELECT * FROM $TableName")) {
		PRINT "<select name=\"$VarName\" class=\"formfield\">";
		If ($Current == 0 ) {
			PRINT "<option value=\"0\" selected>SELECT ONE</option>";
		}
		foreach($Collect as $ar) {
			$ID 		= $ar->ID;
			$ThisName 	= $ar->$TableName;
			PRINT "<option value=\"$ID\"";
			IF ($Current == $ID) {
				PRINT " selected";
			} 
			PRINT ">$ThisName</option>";
		} //end foreach
		PRINT "</select>";
	} //end if
}
////////////////////////////////////////////////////////////////////////////////////
function CheckEmail($weenie) {
	$host = substr($weenie,strpos($weenie,"@") + 1);
		if (eregi("^([[:alnum:]_%+=.-]+)@([[:alnum:]_.-]+)\.([a-z]{2,3}|[0-9]{1,3})$",$weenie)) {
		return true;
	} else {
		return false;
	}
}
////////////////////////////////////////////////////////////////////////////////////
function FormatPhone( $PhoneNumber ) { 
	$PhoneNumber = ereg_replace( "[^0-9]", "", $PhoneNumber ); // Strip out non-numerics     
	if( ereg( "^([0-9]{3})([0-9]{3})([0-9]{4})$", $PhoneNumber, $NumberParts ) )                                    
    	return "(" . $NumberParts[1] . ") " . $NumberParts[2] . "-" . $NumberParts[3]; 
	else 	
		return false; 
}
////////////////////////////////////////////////////////////////////////////////////
function format_date_only($date) { //get it ready for Mysql
	$date = str_replace("/", "-", $date);
	$date = str_replace(".", "-", $date);
	$split = explode("-", $date);
	$newdate = $split[2] . "-" . $split[0] . "-" . $split[1];
	return $newdate;
}
////////////////////////////////////////////////////////////////////////////////////
function format_date_nospace($date) { //get it ready for Mysql
	$date = str_replace("/", "-", $date);
	$date = str_replace(".", "-", $date);
	$split = explode("-", $date);
	$newdate = $split[2] . $split[0] . $split[1];
	return $newdate;
}
////////////////////////////////////////////////////////////////////////////////////
function isdate($date) {
	if (ereg ("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})", $date, $regs) OR ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $date, $regs)) {
    	return false;
	} else {
		return true;
	}
}

////////////////////////////////////////////////////////////////////////////////////
function isdateyyyymmstrict($date) {
	if (preg_match ("/([0-9]{4})\\-([0-9]{2})/", $date) OR
	 preg_match ("/([0-9]{4})\/([0-9]{2})/", $date)  OR
	 preg_match ("/([0-9]{4})\.([0-9]{2})/", $date)) {
    	return true;
	} else {
		return false;
	}
}

////////////////////////////////////////////////////////////////////////////////////
function formatfixyyyymmdate($date) {
	$date = str_replace("/", "-", $date);
	$date = str_replace(".", "-", $date);
	return $date;
}
////////////////////////////////////////////////////////////////////////////////////
function isemail($weenie) {
	$host = substr($weenie,strpos($weenie,"@") + 1);
		if (eregi("^([[:alnum:]_%+=.-]+)@([[:alnum:]_.-]+)\.([a-z]{2,3}|[0-9]{1,3})$",$weenie)) {
		return true;
	} else {
		return false;
	}
}

////////////////////////////////////////////////////////////////////////////////////
Function WriteAlpha($filename) {
	global $Sort;
	$Letters = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	Print "<div style=\"text-transform:uppercase;\">";
	foreach($Letters as $l) {
		If ($Sort == $l) {
			Print "<strong>$l</strong> | ";
		} else {
			Print "<a href=\"$filename?Letter=$l\">$l</a>  ";
		} //end if
	}//end foreach
	Print "</div>";
}// end function
////////////////////////////////////////////////////////////////////////////////////

function KingdomNameFromKingdomID($KingdomID, $db) {
	$kingdomquery = "SELECT K.Name FROM Kingdoms K WHERE K.KingdomID = $KingdomID";
	if ($results = $db->get_results($kingdomquery)) {
		foreach($results as $k) { 
			$kName		= $k->Name;
		};
		return $kName;
	} else {
		return "Unknown";
	}
	
} // end function KingdomFromKingdomID
////////////////////////////////////////////////////////////////////////////////////

function KingdomNameFromGroupID($GroupID, $db) {
	$kingdomquery = "SELECT K.Name FROM Kingdoms K INNER JOIN Groups G WHERE K.KingdomID = G.KingdomID AND G.GroupID = $GroupID";
	if ($results = $db->get_results($kingdomquery)) {
		foreach($results as $k) { 
			$kName		= $k->Name;
		};
		return $kName;
	} else {
		return "Unknown";
	}
	
} // end function KingdomFromGroup
//////////////////////////////////////////////////////////////////

function GroupNameFromGroupID($GroupID, $db) {
	$groupquery = "SELECT G.Name FROM Groups G WHERE G.GroupID = $GroupID";
	if ($results = $db->get_results($groupquery)) {
		foreach($results as $g) { 
			$gName		= $g->Name;
		};
		return $gName;
	} else {
		return "Unknown";
	}
	
} // end function KingdomFromGroup
//////////////////////////////////////////////////////////////////

function SCANameFromPersonID($PersonID, $db) {
	$query = "SELECT P.SCAName FROM wPeople P WHERE P.PersonID = $PersonID";
	if ($Name = $db->get_var($query)) {
		return $Name;
	} else {
		return "Unknown";
	}
	
} // end function SCANameFromPersonID
//////////////////////////////////////////////////////////////////

function StrippedNameFromPersonID($PersonID, $db) {
	$query = "SELECT P.SearchName FROM wPeople P WHERE P.PersonID = $PersonID";
	if ($results = $db->get_results($query)) {
		foreach($results as $r) { 
			$Name		= $r->SearchName;
		};
		$Name = ereg_replace( "[^A-Za-z]", "", $Name ); // Strip out non-numerics 
		return $Name;
	} else {
		return "Unknown";
	}
	
} // end function StrippedNameFromPersonID
//////////////////////////////////////////////////////////////////

function SanitizeInputAlphaOnly($dirty) {
	$clean = preg_replace('/[^a-z ]/i', '', $dirty);
	return $clean;	
} // end SanitizeInput
//////////////////////////////////////////////////////////////////

function SanitizeInputAlphaNumeric($dirty) {
	$clean = preg_replace('/[^a-z0-9]/i', '', $dirty);
	return $clean;	
} // end SanitizeInput
//////////////////////////////////////////////////////////////////

function SanitizeInputNumeric($dirty) {
	$clean = preg_replace('/[^0-9]/i', '', $dirty);
	return $clean;	
} // end SanitizeInput
//////////////////////////////////////////////////////////////////

function SanitizeInputDate($dirty) {
	$clean = preg_replace('/[^a-z0-9\-]/i', '', $dirty);
	return $clean;	
} // end SanitizeInput
//////////////////////////////////////////////////////////////////

function SanitizeInputGeneral($dirty) {
	$clean = preg_replace('/;\"/i', '', $dirty);
	return $clean;	
} // end SanitizeInput
//////////////////////////////////////////////////////////////////

function SanitizeInput($dirty) {
	$clean = preg_replace('/;/', '', $dirty);
	return $clean;	
} // end SanitizeInput
//////////////////////////////////////////////////////////////////

function DumpSession() {

echo $LoggedUsername . "\n";
echo $LoggedUserID . "\n";
echo $LastPoke . "\n";
echo time() . "\n";

}// end DumpSession

?>
