<?php

 

$search = trim($_GET['search'] ?? '');
$status = $_GET['payment_status'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(
        booking_id LIKE ?
        OR client_name LIKE ?
        OR client_phone LIKE ?
        OR driver_name LIKE ?
        OR driver_phone LIKE ?
    )";

    for ($i = 0; $i < 5; $i++) {
        $params[] = "%{$search}%";
    }
}

if (in_array($status, ['Paid', 'Not Paid'], true)) {
    $where[] = 'payment_status = ?';
    $params[] = $status;
}

if ($dateFrom !== '') {
    $d = DateTime::createFromFormat('!Y-m-d', $dateFrom);

    if ($d && $d->format('Y-m-d') === $dateFrom) {
        $where[] = 'booking_date >= ?';
        $params[] = $dateFrom;
    } else {
        $dateFrom = '';
    }
}

if ($dateTo !== '') {
    $d = DateTime::createFromFormat('!Y-m-d', $dateTo);

    if ($d && $d->format('Y-m-d') === $dateTo) {
        $where[] = 'booking_date <= ?';
        $params[] = $dateTo;
    } else {
        $dateTo = '';
    }
}

$sqlWhere = $where
    ? ' WHERE ' . implode(' AND ', $where)
    : '';

$stmt = $pdo->prepare(
    "SELECT * FROM bookings $sqlWhere
     ORDER BY booking_date DESC, id DESC"
);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

$summary = $pdo->prepare(
    "SELECT
        COUNT(*) AS total_bookings,
        COALESCE(SUM(total_bill), 0) AS total_bill,
        COALESCE(SUM(driver_payment), 0) AS driver_payment,
        COALESCE(SUM(profit), 0) AS total_profit,
        COALESCE(SUM(payment_status = 'Paid'), 0) AS paid_count,
        COALESCE(SUM(payment_status = 'Not Paid'), 0) AS unpaid_count
     FROM bookings $sqlWhere"
);
$summary->execute($params);
$totals = $summary->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Tracking</title>
 </head>
<body class="bg-light">

<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Booking Tracking</h2>
            <p class="text-muted mb-0">Manage client bookings and driver payments.</p>
        </div>

        <a href="admin.php?page=admin/booking/add" class="btn btn-primary">
            + Add Booking
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php
            $messages = [
                'added' => 'Booking created successfully.',
                'updated' => 'Booking updated successfully.',
                'deleted' => 'Booking deleted successfully.',
            ];

            echo e($messages[$_GET['success']] ?? 'Operation completed.');
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Bookings</div>
                    <h4><?= (int)$totals['total_bookings'] ?></h4>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Bill</div>
                    <h5>৳<?= number_format((float)$totals['total_bill'], 2) ?></h5>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Driver Payment</div>
                    <h5>৳<?= number_format((float)$totals['driver_payment'], 2) ?></h5>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Profit</div>
                    <h5 class="text-success">৳<?= number_format((float)$totals['total_profit'], 2) ?></h5>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Paid</div>
                    <h5 class="text-success"><?= (int)$totals['paid_count'] ?></h5>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Not Paid</div>
                    <h5 class="text-danger"><?= (int)$totals['unpaid_count'] ?></h5>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Search</label>
                    <input
                        type="search"
                        name="search"
                        class="form-control"
                        placeholder="Booking ID, client, driver, phone..."
                        value="<?= e($search) ?>"
                    >
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Paid" <?= $status === 'Paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="Not Paid" <?= $status === 'Not Paid' ? 'selected' : '' ?>>Not Paid</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="<?= e($dateFrom) ?>">
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="<?= e($dateTo) ?>">
                </div>

                <div class="col-lg-2 col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="index.php" class="btn btn-outline-secondary">Reset</a>
                </div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Booking ID</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Client Phone</th>
                            <th>Driver</th>
                            <th>Duty Time</th>
                            <th>Total Bill</th>
                            <th>Driver Payment</th>
                            <th>Profit</th>
                            <th>Status</th>
                            <th style="min-width: 180px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($bookings): ?>
                        <?php foreach ($bookings as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= e($row['booking_id']) ?></td>
                                <td><?= e($row['booking_date']) ?></td>

                                <td>
                                    <strong><?= e($row['client_name']) ?></strong>
                                    <br>
                                    <small><?= e($row['location']) ?></small>
                                </td>

                                <td><?= e($row['client_phone']) ?></td>
                                <td>
                                    <?= e($row['driver_name']) ?>
                                    <br>
                                    <small><?= e($row['driver_phone']) ?></small>
                                </td>

                                <td>
                                    <?= e($row['duty_start'] ?: '-') ?>
                                    <br>
                                    <?= e($row['duty_end'] ?: '-') ?>
                                </td>

                                <td>৳<?= number_format((float)$row['total_bill'], 2) ?></td>
                                <td>৳<?= number_format((float)$row['driver_payment'], 2) ?></td>

                                <td class="<?= (float)$row['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    ৳<?= number_format((float)$row['profit'], 2) ?>
                                </td>

                                <td>
                                    <?php if ($row['payment_status'] === 'Paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Not Paid</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <a
                                            href="view.php?id=<?= (int)$row['id'] ?>"
                                            class="btn btn-sm btn-info"
                                        >View</a>

                                        <a
                                            href="edit.php?id=<?= (int)$row['id'] ?>"
                                            class="btn btn-sm btn-warning"
                                        >Edit</a>

                                        <form
                                            action="delete.php"
                                            method="POST"
                                            onsubmit="return confirm('Delete this booking?')"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$row['id'] ?>"
                                            >
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                No bookings found.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>