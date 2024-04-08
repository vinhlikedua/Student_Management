<?php
require "database/database.php";

function updateUserById($data){
    $checkUpdate = false;
    $db = connectionDb();
    $sql = "UPDATE `users` SET 
    `full_name` = :full_name,
    `extra_code` = :extra_code,
    `email` = :email,
    `phone` = :phone,
    `address` = :address,
    `gender` = :gender,
    `role_id` = :role_id,
    `birthday` = :birthday,
    `avatar` = :avatar,
    `updated_at` = :updated_at
    WHERE `id` = :id AND `deleted_at` IS NULL";
    $updateTime = date('Y-m-d H:i:s');
    $stmt = $db->prepare($sql);
    if($stmt){
        $stmt->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
        $stmt->bindParam(':extra_code', $data['extra_code'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_INT);
        $stmt->bindParam(':role_id', $data['role_id'], PDO::PARAM_INT);
        $stmt->bindParam(':birthday', $data['birthday'], PDO::PARAM_STR);
        $stmt->bindParam(':avatar', $data['avatar'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updateTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        if($stmt->execute()){
            $checkUpdate = true;
        }
    }
    disconnectDb($db);
    return $checkUpdate;
}

function getDetailUserById($id = 0){
    $sql = "SELECT * FROM `users` WHERE `id` = :id AND `deleted_at` IS NULL";
    $db = connectionDb();
    $data = [];
    $stmt = $db->prepare($sql);
    if($stmt){
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db);
    return $data;
}

function deleteUserById($id = 0){
    $sql = "UPDATE `users` SET `deleted_at` = :deleted_at WHERE `id` = :id";
    $db = connectionDb();
    $checkDelete = false;
    $deleteTime = date("Y-m-d H:i:s");
    $stmt = $db->prepare($sql);
    if($stmt){
        $stmt->bindParam(':deleted_at', $deleteTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            $checkDelete = true;
        }
    }
    disconnectDb($db);
    return $checkDelete;
}

function insertUser($data){
    // viet cau lenh sql insert vao bang user
    $sqlInsert = "INSERT INTO `users`(`full_name`, `extra_code`, `email`, `phone`, `address`, `gender`, `role_id`, `birthday`, `avatar`, `created_at`, `status`) VALUES(:full_name, :extra_code, :email, :phone, :address, :gender, :role_id, :birthday, :avatar, :createdAt, :status)";
    $checkInsert = false;
    $db = connectionDb();
    $stmt = $db->prepare($sqlInsert);
    $currentDate = date('Y-m-d H:i:s');
    if($stmt){
        $stmt->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
        $stmt->bindParam(':extra_code', $data['extra_code'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_INT);
        $stmt->bindParam(':role_id', $data['role_id'], PDO::PARAM_INT);
        $stmt->bindParam(':birthday', $data['birthday'], PDO::PARAM_STR);
        $stmt->bindParam(':avatar', $data['avatar'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        $stmt->bindParam(':createdAt', $currentDate, PDO::PARAM_STR);
        if($stmt->execute()){
            $checkInsert = true;
        }
    }
    disconnectDb($db); // ngat ket noi toi database
    // tra ve true insert thanh cong va nguoc lai
    return $checkInsert;
} 
function getAllDataUser($keyword = null){
    $db   = connectionDb();
    $key = "%{$keyword}%";
    $sql = "SELECT * FROM `users` WHERE `full_name` LIKE :nameUser AND `deleted_at` IS NULL";
    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $data = [];
    if($stmt){
        $stmt->bindParam(':nameUser', $key, PDO::PARAM_STR);
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db);
    return $data;
} 
function getAllDataUserByPage($keyword = null, $start = 0, $limit = 4){
    $key = "%{$keyword}%";
    $sql = "SELECT * FROM `users` WHERE `full_name` LIKE :nameUser AND `deleted_at` IS NULL  LIMIT :startData, :limitData";
    $db = connectionDb();
    $stmt = $db->prepare($sql);
    $data = [];
    if($stmt){
        $stmt->bindParam(':nameUser', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectDb($db);
    return $data;
} 
function formatDate($date) {
    return date('Y-m-d', strtotime($date));
}