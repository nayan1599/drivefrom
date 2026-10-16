<?php

// Database connection
 include '../config/db.php';


// print_r($_POST);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Receive form data
$name             = trim($_POST['name'] ?? '');
$phone_number     = trim($_POST['phone_number'] ?? '');
$pickup_location  = trim($_POST['pickup_location'] ?? '');
$dropoff_location = trim($_POST['dropoff_location'] ?? '');
$pickup_date      = trim($_POST['pickup_date'] ?? '');
$pickup_time      = trim($_POST['pickup_time'] ?? '');
$status           = isset($_POST['status']) ? (int) $_POST['status'] : 1;

 
try {

    // Insert data into database
    $sql = "UPDATE trip_requests SET
                name = :name,
                phone_number = :phone_number,
                pickup_location = :pickup_location,
                dropoff_location = :dropoff_location,
                pickup_date = :pickup_date,
                pickup_time = :pickup_time,
                status = :status
                
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':name'             => $name,
        ':phone_number'     => $phone_number,
        ':pickup_location'  => $pickup_location,
        ':dropoff_location' => $dropoff_location,
        ':pickup_date'      => $pickup_date,
        ':pickup_time'      => $pickup_time,
        ':status'           => $status,
        ':id'               => $_POST['id']
    ]);

    // Redirect after successful save
    header('Location: ./admin.php?page=admin/website/onlinefrom/index&success=1');
    exit;

} catch (PDOException $e) {

    // Log database error
    error_log($e->getMessage());

    http_response_code(500);
    exit('ডাটাবেজে তথ্য সংরক্ষণ করা যায়নি। ডাটাবেজ সেটিংস পরীক্ষা করুন।');
}
