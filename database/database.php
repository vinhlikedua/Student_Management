<?php
function connectionDb(){
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=students_management;charset=utf8', 'root', '');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh; 
    } catch (PDOException $e) {
        // attempt to retry the connection after some timeout for example
        echo "Can not connect to database";
        print_r($e);
        die();
    }
}

function disconnectDb($connection){
    $connection = null;
}