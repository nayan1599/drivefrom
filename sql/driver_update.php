 
<?php
  

include "./config/db.php";
// print_r($_POST);

// Update driver data in the database using the provided POST values
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {

    $id = (int) $_POST['id'];

    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $driver_code = trim($_POST['driver_code'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $blood_group = $_POST['blood_group'] ?? '';
    $joining_date = !empty($_POST['joining_date']) ? $_POST['joining_date'] : null;
    $status = isset($_POST['status']) ? (int) $_POST['status'] : 1;

    try {

        $stmt = $pdo->prepare("
            UPDATE drivers SET
                name = :name,
                mobile = :mobile,
                driver_code = :driver_code,
                status = :status,
                gender = :gender,
                joining_date = :joining_date,
                blood_group = :blood_group,
                updated_by = :updated_by,
                updated = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            ':name'         => $name,
            ':mobile'       => $mobile,
            ':driver_code'  => $driver_code,
            ':status'       => $status,
            ':gender'       => $gender,
            ':joining_date' => $joining_date,
            ':blood_group'  => $blood_group,
            ':updated_by'   => $_SESSION['user_id'] ?? null,
            ':id'           => $id
        ]);

        header("Location: index.php?updated=1");
        exit;

    } catch (PDOException $e) {

        echo '<pre>';
        echo 'DATABASE ERROR:' . PHP_EOL;
        echo htmlspecialchars($e->getMessage());
        echo '</pre>';
        exit;
    }
}



?>