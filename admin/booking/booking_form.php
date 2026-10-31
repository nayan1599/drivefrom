 <?php
$isEdit = isset($booking['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Booking Form') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h3 class="mb-4"><?= e($pageTitle ?? 'Booking Form') ?></h3>

            <form method="POST">
                <?php require __DIR__ . '/booking_form.php'; ?>
            </form>
        </div>
    </div>
</div>

</body>
</html>