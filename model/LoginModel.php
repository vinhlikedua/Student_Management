<?php 
require 'database/database.php';

function checkLoginUser($username, $password){
    $db = connectionDb(); 
    $sql = "SELECT a.*, u.`full_name`, u.`email`, u.`phone` FROM `accounts` AS a INNER JOIN `users` AS u ON a.user_id = u.id WHERE `username` = :user AND `password` = :pass AND a.`status` = 1 LIMIT 1";
    $statement = $db->prepare($sql); 
    $dataUser = [];
    if($statement){
        $statement->bindParam(':user', $username, PDO::PARAM_STR);
        $statement->bindParam(':pass', $password, PDO::PARAM_STR);
        if($statement->execute()){
            if($statement->rowCount() > 0){
                $dataUser = $statement->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db); 
    return $dataUser; 
}