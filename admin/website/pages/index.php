  
<?php
$page_title = "Customer Trip Requests";

$stmt = $pdo->query("
    SELECT *
    FROM home_sections
    WHERE status = 'active'
    ORDER BY sort_order ASC, id ASC
");

$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
 function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>



<style>
.trip-card {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    overflow: hidden;
}

.trip-card .card-header {
    background: #fff;
    padding: 18px 22px;
    border-bottom: 1px solid #edf0f5;
}

.trip-table thead th {
    background: #f1f5f9;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    padding: 14px 12px;
    border-bottom: 1px solid #e2e8f0;
}

.trip-table tbody td {
    padding: 13px 12px;
    font-size: 13px;
    color: #475569;
    vertical-align: middle;
}

.trip-table tbody tr:hover {
    background: #f8fafc;
}

.trip-location {
    min-width: 130px;
    max-width: 220px;
    overflow-wrap: anywhere;
}

.trip-name {
    color: #1e293b;
    font-weight: 600;
}

.trip-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: capitalize;
}

.status-pending   { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1e7dd; color: #0f5132; }
.status-cancelled { background: #f8d7da; color: #842029; }

.trip-actions {
    display: flex;
    gap: 5px;
    white-space: nowrap;
}

.trip-actions .btn {
    border-radius: 7px;
    font-size: 12px;
    padding: 5px 9px;
}

@media (max-width: 768px) {
    .trip-card .card-header { padding: 15px; }
    .trip-table thead th,
    .trip-table tbody td { padding: 10px 8px; }
}
</style>

<div class="card trip-card">

    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-1 fw-bold text-dark">
                <i class="bi bi-car-front-fill text-primary me-2"></i>
                <?= e($page_title) ?>
            </h5>
            <small class="text-muted">
                Total Requests: <?= count($sections ) ?>
            </small>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table trip-table table-hover mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Pick-up</th>
                        <th>Drop-off</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($sections )): ?>

                    <?php foreach ($sections as $section): ?>
                        <?php

// print_r($section);


                        $status = $section['status'] ?? 'pending';
                        $statusClass = in_array(
                            $status,
                            ['pending', 'confirmed', 'cancelled'],
                            true
                        ) ? 'status-' . $status : 'status-pending';
                        ?>

                        <tr>
                            <td><?= $key + 1 ?></td>

                            <td class="trip-name">
                                <?= e($section['name'] ?? 'N/A') ?>
                            </td>

                            <td><?= e($section['phone_number'] ?? 'N/A') ?></td>

                            <td class="trip-location">
                                <?= e($section['pickup_location']) ?>
                            </td>

                            <td class="trip-location">
                                <?= e($section['dropoff_location']) ?>
                            </td>

                            <td class="text-nowrap">
                                <?= !empty($section['pickup_date'])
                                    ? date('d M Y', strtotime($section['pickup_date']))
                                    : '—' ?>
                            </td>

                            <td class="text-nowrap">
                                <?= !empty($section['pickup_time'])
                                    ? date('h:i A', strtotime($section['pickup_time']))
                                    : '—' ?>
                            </td>

                            <td>
                                <span class="trip-status <?= e($statusClass) ?>">
                                    <?= e($status) ?>
                                </span>
                            </td>

                            <td>
                                <div class="trip-actions">
                                    <a href="admin.php?page=admin/website/onlinefrom/view&id=<?= (int)$section['id'] ?>"
                                       class="btn btn-success text-white"
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="admin.php?page=admin/website/onlinefrom/edit&id=<?= (int)$section['id'] ?>"
                                       class="btn btn-warning"
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <a href="admin.php?page=admin/website/onlinefrom/delete&id=<?= (int)$section['id'] ?>"
                                       class="btn btn-danger text-white"
                                       title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this request?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No trip requests found.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
 