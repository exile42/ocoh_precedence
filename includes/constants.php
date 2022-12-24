<?php
// Constants!
// This is a file that contains the constants for running the precedence site.

$WEAREKINGDOM = "Outlands";  // Outlands, East, Trimaris, An Tir, etc.
$WEAREKINGDOMCOLLECTIVE = "the Outlands"; // The Outlands, The Middle, Ansteorra, Lochac, etc.
$IAMKINGDOMER = "an Outlander"; // an Outlander, a Lochacian, a Drachenwalder, a Trimaran, etc.
$WEAREKINGDOMERS = "Outlanders"; // Outlanders, An Tirians, Westies, Ealsormerians, etc.

$PRINCIPALHERALD = "White Stag"; // White Stag, Brigantia, Vesper, etc.
$PRINCIPALHERALDEMAIL = "whitestag@outlands.org";
$COURTHERALD = "Blue Iris"; // The title for whoever does courts most often or "the Crown's Herald"
$PRECEDENCETITLERANK = "Wimble Herald"; // Wimble Herald, Shepards Crook Herald, Someone Pursuivant
$PRECEDENCETITLE = "Wimble"; // Wimble, Shepards Crook, Someone
$PRECEDENCEEMAIL = "wimble@outlandsheralds.org";

$CURRENTPRINCIPALHERALD = "THL AEthelwulf Munuc";
$CURRENTPRECEDENCEHERALD = "THL Thorvald Thorolfsson";

$HERALDDOMAIN = "outlandsheralds.org";
$KINGDOMURL = "http://www.outlands.org/";
$SCRIBEURL = "http://scribes.outlands.org/";
$HERALDURL = "http://www.outlandsheralds.org";
$PRECEDENCEURL = "http://wimble.outlandsheralds.org";
$ROLLOFARMSURL = "http://wharrow.outlandsheralds.org";

$SOCIETYKINGDOMID = 20; // DO NOT CHANGE THIS
$SOCIETYGROUPID = 114; // DO NOT CHANGE THIS
$MYKINGDOMID = 17; // See the Kingdoms table in the database to find the KingdomID for your kingdom (even if you're using this for a sub-kingdom group)
$MYGROUPID = 96; // See the Groups table in the database to find the GroupID for your group (yes, kingoms have both KingdomID and GroupID)

$OPPERPAGESHORT = 40; // for the OP list short form (names only), how many per page (when paged)?
$OPPERPAGELONG = 25; // for the OP list long form (names+awards), how many per page (when paged)?

//Database things
$SQLSERVER = "";
$SQLUSERNAME = "";
$SQLPASSWORD = "";
$SQLDATABASE = "";

// Prevent shared server sessions stuff from causing random timeouts
//DO NOT CHANGE THIS
$SESSIONPATH = dirname(dirname(dirname(__FILE__))) . "/sessiondata";
session_save_path($SESSIONPATH);
ini_set('session.gc_maxlifetime', 3*60*60); // 3 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

?>
