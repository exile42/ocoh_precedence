<?php
    include '../models/user.php';
    include '../db_context.php';

    class Users
    {
        public function getAll()
        {
            $users = array();
            $user = new user();
            $user->$user_id = 1;
            $user->$username = "aperson@aserver.net";
            $user->$password = "Password123!";
            $user->$email="aperson@aserver.net";
            //$user->$last_login;
            //$user->$last_login_ip;
            //$user->$created_on;
            //$user->$created_by;
            //$user->$user_type_id;
            array_push($users,$user);
            return $users;
        }

        public function getById($id)
        {
            $user = new user();
            $user->$user_id = 1;
            $user->$username = "aperson@aserver.net";
            $user->$password = "Password123!";
            $user->$email="aperson@aserver.net";
            //$user->$last_login;
            //$user->$last_login_ip;
            //$user->$created_on;
            //$user->$created_by;
            //$user->$user_type_id;
            return $users;
        }

        public function update($user)
        {

        }

        public function delete($id)
        {

        }
    }
?>