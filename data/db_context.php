<?php
	define("DB_USER", "$SQLUSERNAME");	// <-- mysql db user
	define("DB_PASSWORD", "$SQLPASSWORD");		// <-- mysql db password
	define("DB_NAME", "$SQLDATABASE");		// <-- mysql db pname
	define("DB_HOST", "$SQLSERVER");	// <-- mysql server host

    class DBContext{

        
        public static function getDB()
        {
            if (!isset(self::$db)) {
                try {
                    self::$db = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
                } catch (PDOException $ex) {
                    $error_message = $ex->getMessage();
                    include '../errors/datebase_error.php';
                    exit;
                }
            }
            return self::$db;
        }
    }
 ?>