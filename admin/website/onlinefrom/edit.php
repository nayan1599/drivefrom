
<?php

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    echo '<div class="alert alert-danger">Invalid ID!</div>';
    exit;
}

// Fetch trip request
$stmt = $pdo->prepare(
    "SELECT * FROM trip_requests WHERE id = ? LIMIT 1"
);
$stmt->execute([$id]);

$edit_driver = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$edit_driver) {
    echo '<div class="alert alert-warning">Trip request not found!</div>';
    exit;
}

$statuses = [
    'pending'   => 'Pending',
    'confirmed' => 'Confirmed',
    'cancelled' => 'Cancelled',
];

$currentStatus = $edit_driver['status'] ?? 'pending';

if (!array_key_exists($currentStatus, $statuses)) {
    $currentStatus = 'pending';
}
?>

<div class="card border-0 shadow-sm trip-edit-card">

    <!-- Card Header -->
    <div class="card-header bg-white border-0 p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-car-front-fill text-primary me-2"></i>
                    Edit Trip Request
                </h4>

                <p class="text-muted mb-0">
                    ট্রিপের তথ্য ও বুকিং স্ট্যাটাস পরিবর্তন করুন।
                </p>
            </div>

            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                Trip ID: #<?= e($edit_driver['id']) ?>
            </span>

        </div>
    </div>

    <div class="card-body p-4">

        <form
            method="POST"
            action="admin.php?page=sql/trip_requests_update"
            class="trip-form"
        >

            <input
                type="hidden"
                name="id"
                value="<?= e($edit_driver['id']) ?>"
            >

            <!-- Location Section -->
            <div class="form-section">

                <div class="section-heading">
                    <div class="section-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div>
                        <h5 class="mb-1">Trip Locations</h5>
                        <p class="mb-0">যাত্রা শুরুর স্থান ও গন্তব্য</p>
                    </div>
                </div>

                <div class="row g-3">
<div class="col-md-6">
                        <label for="pickup_location" class="form-label">
                           Name
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-icon">
                            <i class="bi bi-geo-alt"></i>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                value="<?= e($edit_driver['name'] ?? '') ?>"
                                placeholder="Enter name"
                                maxlength="255"
                                required
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="pickup_location" class="form-label">
                            Phone Number
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-icon">
                            <i class="bi bi-geo-alt"></i>

                            <input
                                type="text"
                                name="phone_number"
                                id="phone_number"
                                class="form-control"
                                value="<?= e($edit_driver['phone_number'] ?? '') ?>"
                                placeholder="Enter phone number"
                                
                                required
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="pickup_location" class="form-label">
                            Pick-up Location
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-icon">
                            <i class="bi bi-geo-alt"></i>

                            <input
                                type="text"
                                name="pickup_location"
                                id="pickup_location"
                                class="form-control"
                                value="<?= e($edit_driver['pickup_location'] ?? '') ?>"
                                placeholder="Enter pick-up location"
                                maxlength="255"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="dropoff_location" class="form-label">
                            Drop-off Location
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-icon">
                            <i class="bi bi-geo"></i>

                            <input
                                type="text"
                                name="dropoff_location"
                                id="dropoff_location"
                                class="form-control"
                                value="<?= e($edit_driver['dropoff_location'] ?? '') ?>"
                                placeholder="Enter destination"
                                maxlength="255"
                                required
                            >
                        </div>
                    </div>

                </div>
            </div>

            <!-- Schedule Section -->
            <div class="form-section">

                <div class="section-heading">
                    <div class="section-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <div>
                        <h5 class="mb-1">Trip Schedule</h5>
                        <p class="mb-0">তারিখ ও সময়ের তথ্য</p>
                    </div>
                </div>

                <div class="row g-3">

                

                    <div class="col-md-4">
                        <label for="pickup_time" class="form-label">
                            Pick-up Time
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="time"
                            name="pickup_time"
                            id="pickup_time"
                            class="form-control"
                            value="<?= e(substr($edit_driver['pickup_time'] ?? '', 0, 5)) ?>"
                            required
                        >
                    </div>

                </div>
            </div>

            <!-- Status Section -->
            <div class="form-section">

                <div class="section-heading">
                    <div class="section-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div>
                        <h5 class="mb-1">Booking Status</h5>
                        <p class="mb-0">ট্রিপের বর্তমান অবস্থা নির্বাচন করুন</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <label for="status" class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select status-select"
                            required
                        >
                            <?php foreach ($statuses as $value => $label): ?>
                                <option
                                    value="<?= e($value) ?>"
                                    <?= $currentStatus === $value ? 'selected' : '' ?>
                                >
                                    <?= e($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <small class="form-text">
                            Pending = অপেক্ষমাণ,
                            Confirmed = নিশ্চিত,
                            Cancelled = বাতিল।
                        </small>

                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="form-footer">

                <div class="footer-info">
                    <i class="bi bi-shield-check"></i>

                    <div>
                        <strong>Trip Request Management</strong>
                        <small>আপডেট করার আগে তথ্য যাচাই করুন।</small>
                    </div>
                </div>

                <div class="footer-buttons">

                    <a
                        href="javascript:history.back()"
                        class="btn btn-light border"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Back
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary save-btn"
                    >
                        <i class="bi bi-check-circle me-2"></i>
                        Update Trip
                    </button>

                </div>
            </div>

        </form>

    </div>
</div>

<style>
.trip-edit-card {
    border-radius: 18px;
    overflow: hidden;
}

.trip-form {
    color: #334155;
}

.form-section {
    background: #fff;
    border: 1px solid #e8edf3;
    border-radius: 15px;
    padding: 24px;
    margin-bottom: 22px;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid #edf0f4;
}

.section-heading h5 {
    font-weight: 700;
    font-size: 16px;
    color: #1e293b;
}

.section-heading p {
    font-size: 12px;
    color: #94a3b8;
}

.section-icon {
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef4ff;
    color: #2563eb;
    border-radius: 12px;
    font-size: 20px;
}

.trip-form .form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 9px;
}

.trip-form .form-control,
.trip-form .form-select {
    min-height: 47px;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    font-size: 13px;
    box-shadow: none;
    transition: border-color .2s, box-shadow .2s;
}

.trip-form .form-control:focus,
.trip-form .form-select:focus {
    border-color: #86a9ff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .09);
}

.input-icon {
    position: relative;
}

.input-icon > i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

.input-icon .form-control {
    padding-left: 40px;
}

.form-text {
    display: block;
    margin-top: 8px;
    font-size: 11px;
    color: #94a3b8;
}

.form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    padding-top: 8px;
}

.footer-info {
    display: flex;
    align-items: center;
    gap: 11px;
}

.footer-info > i {
    width: 42px;
    height: 42px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 11px;
    background: #ecfdf3;
    color: #16a34a;
    font-size: 20px;
}

.footer-info strong,
.footer-info small {
    display: block;
}

.footer-info strong {
    font-size: 12px;
    color: #475569;
}

.footer-info small {
    margin-top: 3px;
    font-size: 11px;
    color: #94a3b8;
}

.footer-buttons {
    display: flex;
    gap: 10px;
}

.footer-buttons .btn {
    padding: 12px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
}

.save-btn {
    min-width: 175px;
}

@media (max-width: 767px) {
    .trip-edit-card .card-header,
    .trip-edit-card .card-body {
        padding: 18px !important;
    }

    .form-section {
        padding: 17px;
    }

    .form-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .footer-buttons {
        width: 100%;
    }

    .footer-buttons .btn {
        flex: 1;
    }

    .save-btn {
        min-width: 0;
    }
}
</style>
