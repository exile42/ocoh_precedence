<?php require_once('Connections/wimble.php'); ?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<? 
mysql_select_db($database_wimble, $wimble);
$querya = "select * from awards";
echo $querya;
$resultsa = mysql_query($querya, $wimble) or die(mysql_error());

while ($rowa = mysql_fetch_assoc($resultsa)) { 
	$aid = $rowa['id'];
	$ascaname = $rowa['person'];
	$queryp = "select id from persons where scaname=\"$ascaname\"";
	echo $queryp;
	$resultsp = mysql_query($queryp, $wimble) or die(mysql_error());
	$rowp = mysql_fetch_assoc($resultsp);
	$pid = $rowp['id'];
	$queryu = "update awards set personid=$pid where id=$aid";
	echo $queryu;
	$resultsu = mysql_query($queryu, $wimble) or die(mysql_error());
}
?>
</body>
</html>