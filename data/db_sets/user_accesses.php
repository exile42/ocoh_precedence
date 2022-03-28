<?php
    include '../db_context.php';
    include '../models/user_accesses.php';

    class UserAccesses
    {    private $db;
        
        function __construct($db)
        {
            $this->db = $db;
        }

        public function getAccesesByUserId($Username,$UserID)
        {
            $query = $db->prepare("SELECT * FROM Users as U, UserAccess as UA WHERE U.UserID=UA.UserAccessID and U.Username=:Username and U.UserID=:UserID");
            $query->bindParam(':Username',$Username);
            $query->bindParam(':UserID',$UserID);
            $query->execute();
            $data = $query->fetchAll();
            return $data;		
        }        
    }
?>