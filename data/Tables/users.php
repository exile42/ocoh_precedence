<?php
    include '../models/user.php';
    include '../db_context.php';

    class Users

    {
        private $db;
        
        function __construct($db)
        {
            $this->db = $db;
        }

        public function getAll()
        {
    
        }

        public function getById($id)
        {

        }

        public function update($user)
        {

        }

        public function delete($id)
        {

        }
    }
?>