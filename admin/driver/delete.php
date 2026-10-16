<?php 

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    if ($id > 0) {

        $stmt = $pdo->prepare("DELETE FROM drivers WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: admin.php?page=admin/driver/index&deleted=1");
        exit;
    }
}

?>