<?php
    include '../db_context.php';
    include '../models/user_accesses.php';

    class UserAccesses
    {
        public function getAccesesByUserId($Username,$UserID)
        {
            $db = db_context->getDB();
            $query = $db->prepare("SELECT * FROM Users as U, UserAccess as UA WHERE U.UserID=UA.UserAccessID and U.Username=:Username and U.UserID=:UserID");
            $query->bindParam(':Username',$Username);
            $query->bindParam(':UserID',$UserID);
            $query->execute();
            $data = $query->fetchAll();
            return $data;		
        }

        
    }
?>