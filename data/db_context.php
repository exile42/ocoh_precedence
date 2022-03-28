<?php
    include 'db_sets/users.php';
    include 'db_sets/user_accesses.php';

	define("DB_USER", "$SQLUSERNAME");	// <-- mysql db user
	define("DB_PASSWORD", "$SQLPASSWORD");		// <-- mysql db password
	define("DB_NAME", "$SQLDATABASE");		// <-- mysql db pname
	define("DB_HOST", "$SQLSERVER");	// <-- mysql server host

    class DBContext{

        private $db;

        function __construct()
        {
            try {
                $db = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
            } catch (PDOException $ex) {
                $error_message = $ex->getMessage();
                //TODO: display error message and set up to shutdown gracefully
                exit;
            }
        }

        //Create data base sets for tables in the database with our CRUD methods
        public $users = new Users($db);
        public $user_accesses = new UserAccesses($db);
    }
 ?>