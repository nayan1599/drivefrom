<?php

$host = "localhost";
$dbname = "driver_rg";
$username = "nayan";
$password = "123456";

// online server 

// $dbname = "oncalldr_apply";
// $username = "oncalldr_kamal";
// $password = "@nayan1122";


try {

    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die(
        "Database connection failed: " .
        $e->getMessage()
    );
}