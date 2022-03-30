<?php

include 'constants.php';
include 'functions.php';

function db ($dbuser, $dbpassword, $dbname, $dbhost) 
{
        $cstring = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
    if ($cstring->connect_error) {
	    die("Connection failed: " . $cstring->connect_error);
	}
    return $cstring;
}   

?>