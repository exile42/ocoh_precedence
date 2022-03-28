<?php
    include("db_sets/users.php");
    include("db_sets/user_accesses.php");

    class DBContext
    {


        private $db;

        function __construct()
        {
            try {
                $connection_string='mysql:host='.$SQLSERVER.';dbname='.$SQLDATABASE.';charset=utf8';
                $db = new PDO($connection_string, $SQLUSERNAME, $SQLDATABASE);
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