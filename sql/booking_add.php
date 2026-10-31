<?php

// print_r($_POST);

// Array ( [booking_date] => 2026-10-31 [client_name] => nayan [client_phone] => 01621881846 [location] => kamal [service_time] => 5 [driver_name] => rajib [driver_phone] => 01825293404 [duty_start] => 2026-10-31T11:52 [duty_end] => 2026-11-01T19:55 [total_bill] => 50000 [driver_payment] => 450 [payment_status] => Not Paid [note] => )

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $booking_date = trim($_POST['booking_date'] ?? '');
    $client_name = trim($_POST['client_name'] ?? '');
    $client_phone = trim($_POST['client_phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $service_time = trim($_POST['service_time'] ?? '');
    $driver_name = trim($_POST['driver_name'] ?? '');
    $driver_phone = trim($_POST['driver_phone'] ?? '');
    $duty_start = trim($_POST['duty_start'] ?? '');
    $duty_end = trim($_POST['duty_end'] ?? '');
    $total_bill = filter_var(
        $_POST['total_bill'] ?? 0,
        FILTER_VALIDATE_FLOAT
    );
    $driver_payment = filter_var(
        $_POST['driver_payment'] ?? 0,
        FILTER_VALIDATE_FLOAT
    );
    $payment_status = $_POST['payment_status'] ?? 'Not Paid';
    $note = trim($_POST['note'] ?? '');
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO bookings (
                    booking_date, client_name, client_phone, location, service_time,
                    driver_name, driver_phone, duty_start, duty_end,
                    total_bill, driver_payment, payment_status, note
                ) VALUES (
                    :booking_date, :client_name, :client_phone, :location, :service_time,
                    :driver_name, :driver_phone, :duty_start, :duty_end,
                    :total_bill, :driver_payment, :payment_status, :note
                )
            ");

            $stmt->execute([
                ':booking_date' => $booking_date,
                ':client_name' => $client_name,
                ':client_phone' => $client_phone,
                ':location' => $location,
                ':service_time' => $service_time,
                ':driver_name' => $driver_name,
                ':driver_phone' => $driver_phone,
                ':duty_start' => $duty_start,
                ':duty_end' => $duty_end,
                ':total_bill' => $total_bill,
                ':driver_payment' => $driver_payment,
                ':payment_status' => $payment_status,
                ':note' => $note
            ]);

            // Redirect to the booking list page after successful insertion
            header('Location: admin.php?page=admin/booking/index');
            exit;

        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
        }
    }
}



?>