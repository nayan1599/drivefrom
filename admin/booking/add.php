<?php
// booking_form.php

$booking = $booking ?? [];

function bookingValue($field, $booking = [])
{
    return htmlspecialchars(
        (string)($_POST[$field] ?? $booking[$field] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function bookingDateTimeValue($field, $booking = [])
{
    $value = $_POST[$field] ?? $booking[$field] ?? '';

    if ($value === '') {
        return '';
    }

    return date('Y-m-d\TH:i', strtotime($value));
}
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 p-4">
        <h4 class="mb-1">
            <?= !empty($booking['id']) ? 'Edit Booking' : 'Create New Booking' ?>
        </h4>
        <p class="text-muted mb-0">
            Booking information পূরণ করুন।
        </p>
    </div>

    <div class="card-body p-4">

        <form method="POST" action="admin.php?page=sql/booking_add" enctype="multipart/form-data">

            <!-- Booking Information -->
            <h5 class="mb-3">Booking Information</h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Booking Date *</label>
                    <input
                        type="date"
                        name="booking_date"
                        class="form-control"
                        value="<?= bookingValue('booking_date', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="form-label">Client Name *</label>
                    <input
                        type="text"
                        name="client_name"
                        class="form-control"
                        placeholder="Enter client name"
                        value="<?= bookingValue('client_name', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="form-label">Client Phone *</label>
                    <input
                        type="tel"
                        name="client_phone"
                        class="form-control"
                        placeholder="01XXXXXXXXX"
                        value="<?= bookingValue('client_phone', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Pickup / Location *</label>
                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        placeholder="Enter location"
                        value="<?= bookingValue('location', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Service Time</label>
                    <input
                        type="text"
                        name="service_time"
                        class="form-control"
                        placeholder="e.g. 5 hours / Full day"
                        value="<?= bookingValue('service_time', $booking) ?>"
                    >
                </div>

            </div>

            <hr class="my-4">

            <!-- Driver Information -->
            <h5 class="mb-3">Driver Information</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Driver Name *</label>
                    <input
                        type="text"
                        name="driver_name"
                        class="form-control"
                        placeholder="Enter driver name"
                        value="<?= bookingValue('driver_name', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Driver Phone *</label>
                    <input
                        type="tel"
                        name="driver_phone"
                        class="form-control"
                        placeholder="01XXXXXXXXX"
                        value="<?= bookingValue('driver_phone', $booking) ?>"
                        required
                    >
                </div>

            </div>

            <hr class="my-4">

            <!-- Duty Information -->
            <h5 class="mb-3">Duty Information</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Duty Start *</label>
                    <input
                        type="datetime-local"
                        name="duty_start"
                        class="form-control"
                        value="<?= bookingDateTimeValue('duty_start', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Duty End *</label>
                    <input
                        type="datetime-local"
                        name="duty_end"
                        class="form-control"
                        value="<?= bookingDateTimeValue('duty_end', $booking) ?>"
                        required
                    >
                </div>

            </div>

            <hr class="my-4">

            <!-- Payment Information -->
            <h5 class="mb-3">Payment Information</h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Total Bill (৳) *</label>
                    <input
                        type="number"
                        name="total_bill"
                        id="total_bill"
                        class="form-control"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        value="<?= bookingValue('total_bill', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="form-label">Driver Payment (৳) *</label>
                    <input
                        type="number"
                        name="driver_payment"
                        id="driver_payment"
                        class="form-control"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        value="<?= bookingValue('driver_payment', $booking) ?>"
                        required
                    >
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profit (৳)</label>
                    <input
                        type="text"
                        id="profit"
                        class="form-control bg-light"
                        value="0.00"
                        readonly
                    >
                    <small class="text-muted">
                        Total Bill − Driver Payment
                    </small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Status *</label>
                    <select
                        name="payment_status"
                        class="form-select"
                        required
                    >
                        <option value="Not Paid"
                            <?= bookingValue('payment_status', $booking) === 'Not Paid' ? 'selected' : '' ?>>
                            Not Paid
                        </option>

                        <option value="Paid"
                            <?= bookingValue('payment_status', $booking) === 'Paid' ? 'selected' : '' ?>>
                            Paid
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Note</label>
                    <textarea
                        name="note"
                        class="form-control"
                        rows="2"
                        placeholder="Additional information"
                    ><?= bookingValue('note', $booking) ?></textarea>
                </div>

            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-4">

                <a href="index.php" class="btn btn-light border">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary px-4">
                    <?= !empty($booking['id']) ? 'Update Booking' : 'Save Booking' ?>
                </button>

            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalBill = document.getElementById('total_bill');
    const driverPayment = document.getElementById('driver_payment');
    const profit = document.getElementById('profit');

    function calculateProfit() {
        const total = parseFloat(totalBill.value) || 0;
        const driver = parseFloat(driverPayment.value) || 0;

        profit.value = (total - driver).toFixed(2);
    }

    totalBill.addEventListener('input', calculateProfit);
    driverPayment.addEventListener('input', calculateProfit);

    calculateProfit();
});
</script>