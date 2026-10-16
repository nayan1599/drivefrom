<?php

include "./config/db.php";

$message = "";
$message_type = "success";

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirectTo($url)
{
    header("Location: " . $url);
    exit;
}

/*
|--------------------------------------------------------------------------
| Upload Configuration
|--------------------------------------------------------------------------
|
| Filesystem path:
| /your-project/uploads/drivers/
|
| Database path:
| uploads/drivers/filename.jpg
|
*/

$upload_dir = __DIR__ . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "drivers" . DIRECTORY_SEPARATOR;
$upload_url = "uploads/drivers/";

/*
|--------------------------------------------------------------------------
| Create Upload Directory
|--------------------------------------------------------------------------
*/

if (!is_dir($upload_dir)) {

    if (!mkdir($upload_dir, 0777, true)) {
        die("Unable to create upload directory: " . e($upload_dir));
    }
}

if (!is_writable($upload_dir)) {
    die("Upload directory is not writable:<br>" . e($upload_dir));
}

/*
|--------------------------------------------------------------------------
| Delete Physical File
|--------------------------------------------------------------------------
*/

function deleteUploadedFile($file)
{
    global $upload_dir;

    if (empty($file)) {
        return;
    }

    /*
     * Database path example:
     * uploads/drivers/driver_xxx.jpg
     *
     * Convert to actual filesystem path.
     */
    $filename = basename($file);

    if ($filename) {

        $physical_file = $upload_dir . $filename;

        if (file_exists($physical_file)) {
            @unlink($physical_file);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Upload File
|--------------------------------------------------------------------------
*/

function uploadFile($field, $oldFile = null)
{
    global $upload_dir, $upload_url;

    /*
     * No input
     */
    if (!isset($_FILES[$field])) {
        return $oldFile;
    }

    $file = $_FILES[$field];

    /*
     * No file selected
     */
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldFile;
    }

    /*
     * Upload error
     */
    if ($file['error'] !== UPLOAD_ERR_OK) {

        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds upload_max_filesize.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds MAX_FILE_SIZE.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload.'
        ];

        $error_message = $errors[$file['error']] ?? 'Unknown upload error.';

        die(
            "<div style='font-family:Arial;padding:30px'>" .
            "<h3>File Upload Error</h3>" .
            "<p><strong>Field:</strong> " . e($field) . "</p>" .
            "<p><strong>Error:</strong> " . e($error_message) . "</p>" .
            "<p><strong>Error Code:</strong> " . e($file['error']) . "</p>" .
            "</div>"
        );
    }

    /*
     * File size
     *
     * 5 MB
     */
    $max_size = 5 * 1024 * 1024;

    if ($file['size'] > $max_size) {

        die(
            "File too large for <strong>" .
            e($field) .
            "</strong>. Maximum size is 5 MB."
        );
    }

    /*
     * Empty file
     */
    if ($file['size'] <= 0) {
        return $oldFile;
    }

    /*
     * Allowed extension
     */
    $allowed_extensions = [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'pdf'
    ];

    $extension = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );

    if (!in_array($extension, $allowed_extensions, true)) {

        die(
            "Invalid file type for <strong>" .
            e($field) .
            "</strong>.<br><br>" .
            "Allowed: JPG, JPEG, PNG, WEBP, PDF"
        );
    }

    /*
     * MIME validation
     */
    $allowed_mimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf'
    ];

    $mime = '';

    if (function_exists('finfo_open')) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo) {
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
        }
    }

    if ($mime && !in_array($mime, $allowed_mimes, true)) {

        die(
            "Invalid MIME type for <strong>" .
            e($field) .
            "</strong>.<br>" .
            "Detected: " . e($mime)
        );
    }

    /*
     * Generate unique filename
     */
    $filename =
        'driver_' .
        date('YmdHis') .
        '_' .
        bin2hex(random_bytes(8)) .
        '.' .
        $extension;

    /*
     * Absolute destination
     */
    $destination = $upload_dir . $filename;

    /*
     * Move uploaded file
     */
    if (!move_uploaded_file($file['tmp_name'], $destination)) {

        die(
            "Failed to move uploaded file.<br>" .
            "Destination: " . e($destination)
        );
    }

    /*
     * Set permission
     */
    @chmod($destination, 0644);

    /*
     * Delete old file only after new file uploaded successfully
     */
    if (!empty($oldFile)) {
        deleteUploadedFile($oldFile);
    }

    /*
     * Return DB path
     */
    return $upload_url . $filename;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
     * ID
     *
     * 0 = Add
     * >0 = Update
     */
    $id = isset($_POST['id'])  ? (int)$_POST['id'] : 0;

    /*
     * Basic Information
     */
    $uid = 'UID' . date('d') . '-' . strtoupper(bin2hex(random_bytes(2)));

    $driver_code =  'D-' . date('d') . '-' . strtoupper(bin2hex(random_bytes(1)));

    $name = trim(
        $_POST['name'] ?? ''
    );

    $father_name = trim(
        $_POST['father_name'] ?? ''
    );

    $mother_name = trim(
        $_POST['mother_name'] ?? ''
    );

    $dob = !empty($_POST['dob'])
        ? $_POST['dob']
        : null;

    $gender = trim(
        $_POST['gender'] ?? ''
    );

    $nid_number = trim(
        $_POST['nid_number'] ?? ''
    );

    $mobile = trim(
        $_POST['mobile'] ?? ''
    );

    $alternative_mobile = trim(
        $_POST['alternative_mobile'] ?? ''
    );

    $email = trim(
        $_POST['email'] ?? ''
    );

    $present_address = trim(
        $_POST['present_address'] ?? ''
    );

    $permanent_address = trim(
        $_POST['permanent_address'] ?? ''
    );

    $blood_group = trim(
        $_POST['blood_group'] ?? ''
    );

    /*
     * License
     */
    $license_number = trim(
        $_POST['license_number'] ?? ''
    );

    $license_type = trim(
        $_POST['license_type'] ?? ''
    );

    $license_issue_date = !empty($_POST['license_issue_date'])
        ? $_POST['license_issue_date']
        : null;

    $license_expiry_date = !empty($_POST['license_expiry_date'])
        ? $_POST['license_expiry_date']
        : null;

    $license_authority = trim(
        $_POST['license_authority'] ?? ''
    );

    $driving_experience = trim(
        $_POST['driving_experience'] ?? ''
    );

    $vehicle_type = trim(
        $_POST['vehicle_type'] ?? ''
    );

    /*
     * Emergency
     */
    $emergency_contact_name = trim(
        $_POST['emergency_contact_name'] ?? ''
    );

    $emergency_contact_relation = trim(
        $_POST['emergency_contact_relation'] ?? ''
    );

    $emergency_contact_mobile = trim(
        $_POST['emergency_contact_mobile'] ?? ''
    );

    /*
     * Employment
     */
    $employee_type = trim(
        $_POST['employee_type'] ?? ''
    );

    $joining_date = !empty($_POST['joining_date'])
        ? $_POST['joining_date']
        : null;

    $salary = !empty($_POST['salary'])
        ? (float)$_POST['salary']
        : 0;

    $assigned_vehicle_id = !empty(
        $_POST['assigned_vehicle_id']
    )
        ? (int)$_POST['assigned_vehicle_id']
        : null;

    /*
     * Status
     */
    $status = isset($_POST['status']) ? 1 : 0;

    $remarks = trim(
        $_POST['remarks'] ?? ''
    );

    /*
     * User IDs
     *
     * Change this later according to login user.
     */
    $created_by = 1;
    $updated_by = 1;

    /*
     |--------------------------------------------------------------------------
     | Validation
     |--------------------------------------------------------------------------
     */

    if ($name === '') {
        die("Driver name is required.");
    }

    /*
     |--------------------------------------------------------------------------
     | ADD
     |--------------------------------------------------------------------------
     */

    if ($id === 0) {

        /*
         * Upload files
         */
        $profile_image = uploadFile(
            'profile_image'
        );

        $nid_front = uploadFile(
            'nid_front'
        );

        $nid_back = uploadFile(
            'nid_back'
        );

        $license_front = uploadFile(
            'license_front'
        );

        $license_back = uploadFile(
            'license_back'
        );

        $medical_certificate = uploadFile(
            'medical_certificate'
        );

        $police_clearance = uploadFile(
            'police_clearance'
        );

        /*
         * INSERT
         *
         * Total columns = 38
         */
        $sql = "
            INSERT INTO drivers (
                uid,
                driver_code,
                name,
                father_name,
                mother_name,
                dob,
                gender,
                nid_number,
                mobile,
                alternative_mobile,
                email,
                present_address,
                permanent_address,
                blood_group,
                license_number,
                license_type,
                license_issue_date,
                license_expiry_date,
                license_authority,
                driving_experience,
                vehicle_type,
                emergency_contact_name,
                emergency_contact_relation,
                emergency_contact_mobile,
                employee_type,
                joining_date,
                salary,
                assigned_vehicle_id,
                profile_image,
                nid_front,
                nid_back,
                license_front,
                license_back,
                medical_certificate,
                police_clearance,
                status,
                remarks,
                created_by
            )
            VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?
            )
        ";

        try {

            $stmt = $pdo->prepare($sql);

            $stmt->execute([

                $uid,
                $driver_code,
                $name,
                $father_name,
                $mother_name,
                $dob,
                $gender,
                $nid_number,
                $mobile,
                $alternative_mobile,

                $email,
                $present_address,
                $permanent_address,
                $blood_group,

                $license_number,
                $license_type,
                $license_issue_date,
                $license_expiry_date,
                $license_authority,
                $driving_experience,
                $vehicle_type,

                $emergency_contact_name,
                $emergency_contact_relation,
                $emergency_contact_mobile,

                $employee_type,
                $joining_date,
                $salary,
                $assigned_vehicle_id,

                $profile_image,
                $nid_front,
                $nid_back,
                $license_front,
                $license_back,
                $medical_certificate,
                $police_clearance,

                $status,
                $remarks,
                $created_by
            ]);

            redirectTo(
                "add.php"
            );

        } catch (PDOException $e) {

            /*
             * If DB insert fails after file upload,
             * remove uploaded files.
             */
            deleteUploadedFile($profile_image);
            deleteUploadedFile($nid_front);
            deleteUploadedFile($nid_back);
            deleteUploadedFile($license_front);
            deleteUploadedFile($license_back);
            deleteUploadedFile($medical_certificate);
            deleteUploadedFile($police_clearance);

            die(
                "<div style='font-family:Arial;padding:30px'>" .
                "<h3>Driver Save Error</h3>" .
                "<pre>" .
                e($e->getMessage()) .
                "</pre>" .
                "</div>"
            );
        }
    }

    /*
     |--------------------------------------------------------------------------
     | UPDATE
     |--------------------------------------------------------------------------
     |
     | IMPORTANT:
     | This is now ELSE/ID > 0 logic.
     | So Add করার পর Update execute হবে না.
     |--------------------------------------------------------------------------
     */

    else {

        /*
         * Get old driver
         */
        $stmt = $pdo->prepare("
            SELECT
                *
            FROM drivers
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $old = $stmt->fetch(
            PDO::FETCH_ASSOC
        );

        if (!$old) {
            die("Driver not found.");
        }

        /*
         * Upload new files
         *
         * If no new file selected,
         * old file will remain.
         */
        $profile_image = uploadFile(
            'profile_image',
            $old['profile_image']
        );

        $nid_front = uploadFile(
            'nid_front',
            $old['nid_front']
        );

        $nid_back = uploadFile(
            'nid_back',
            $old['nid_back']
        );

        $license_front = uploadFile(
            'license_front',
            $old['license_front']
        );

        $license_back = uploadFile(
            'license_back',
            $old['license_back']
        );

        $medical_certificate = uploadFile(
            'medical_certificate',
            $old['medical_certificate']
        );

        $police_clearance = uploadFile(
            'police_clearance',
            $old['police_clearance']
        );

        /*
         * UPDATE
         */
        $sql = "
            UPDATE drivers SET

                uid = ?,
                driver_code = ?,
                name = ?,
                father_name = ?,
                mother_name = ?,
                dob = ?,
                gender = ?,
                nid_number = ?,
                mobile = ?,
                alternative_mobile = ?,
                email = ?,
                present_address = ?,
                permanent_address = ?,
                blood_group = ?,

                license_number = ?,
                license_type = ?,
                license_issue_date = ?,
                license_expiry_date = ?,
                license_authority = ?,
                driving_experience = ?,
                vehicle_type = ?,

                emergency_contact_name = ?,
                emergency_contact_relation = ?,
                emergency_contact_mobile = ?,

                employee_type = ?,
                joining_date = ?,
                salary = ?,
                assigned_vehicle_id = ?,

                profile_image = ?,
                nid_front = ?,
                nid_back = ?,
                license_front = ?,
                license_back = ?,
                medical_certificate = ?,
                police_clearance = ?,

                status = ?,
                remarks = ?,
                updated_by = ?

            WHERE id = ?
        ";

        try {

            $stmt = $pdo->prepare($sql);

            $stmt->execute([

                $uid,
                $driver_code,
                $name,
                $father_name,
                $mother_name,
                $dob,
                $gender,
                $nid_number,
                $mobile,
                $alternative_mobile,

                $email,
                $present_address,
                $permanent_address,
                $blood_group,

                $license_number,
                $license_type,
                $license_issue_date,
                $license_expiry_date,
                $license_authority,
                $driving_experience,
                $vehicle_type,

                $emergency_contact_name,
                $emergency_contact_relation,
                $emergency_contact_mobile,

                $employee_type,
                $joining_date,
                $salary,
                $assigned_vehicle_id,

                $profile_image,
                $nid_front,
                $nid_back,
                $license_front,
                $license_back,
                $medical_certificate,
                $police_clearance,

                $status,
                $remarks,
                $updated_by,

                $id
            ]);

            redirectTo(
                "index.php?message=updated"
            );

        } catch (PDOException $e) {

            die(
                "<div style='font-family:Arial;padding:30px'>" .
                "<h3>Driver Update Error</h3>" .
                "<pre>" .
                e($e->getMessage()) .
                "</pre>" .
                "</div>"
            );
        }
    }
}

/*
|--------------------------------------------------------------------------
| Message
|--------------------------------------------------------------------------
*/

if (isset($_GET['message'])) {

    switch ($_GET['message']) {

        case 'added':
            $message = "Driver added successfully.";
            $message_type = "success";
            break;

        case 'updated':
            $message = "Driver updated successfully.";
            $message_type = "success";
            break;

        case 'deleted':
            $message = "Driver deleted successfully.";
            $message_type = "danger";
            break;
    }
}
  
/*
|--------------------------------------------------------------------------
| Form Helper
|--------------------------------------------------------------------------
*/

function formValue($field, $edit_driver)
{
    return $edit_driver[$field] ?? '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
<!-- fivicon -->
 <link rel="stylesheet"  href="https://oncalldriverbd.com/assets/car-BDmjYeeJ.png" type="image/x-icon">
    <title>
        Driver Management
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

 
</head>

<body>

<div class="container-fluid py-4">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Driver Management
            </h3>

        
        </div>
 

    </div>


    <!-- Message -->

    <?php if ($message): ?>

        <div
            class="alert alert-<?= e($message_type) ?> alert-dismissible fade show"
            role="alert"
        >

            <?= e($message) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- Form -->

    <div class="card page-card mb-4">

 
<div class="card-body p-4">

    <form
        method="POST"
        enctype="multipart/form-data"
        class="driver-form"
    >

        <?php if ($edit_driver): ?>

            <input
                type="hidden"
                name="id"
                value="<?= e($edit_driver['id']) ?>"
            >

        <?php endif; ?>


        <!-- ==============================
             ব্যক্তিগত তথ্য
        =============================== -->

        <div class="form-section">

            <div class="section-heading">

       

                <div>
                    <h5>ব্যক্তিগত তথ্য</h5>
                    <p>ড্রাইভারের ব্যক্তিগত ও যোগাযোগের তথ্য</p>
                </div>

            </div>


            <div class="row g-4">
 

                <!-- Name -->

                <div class="col-md-4">

                    <label class="form-label">
                       আপনার নাম
                        <span class="required">*</span>
                    </label>

                    <div class="input-icon">

                        <i class="bi bi-person"></i>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="ড্রাইভারের পূর্ণ নাম লিখুন"
                            required
                            value="<?= e(formValue('name', $edit_driver)) ?>"
                        >

                    </div>

                </div>


                <!-- Gender -->

   


                <!-- Mobile -->

                <div class="col-md-4">

                    <label class="form-label">
                        মোবাইল নম্বর <span class="required">*</span>
                    </label>

                    <div class="input-icon">

                        <i class="bi bi-telephone"></i>

                        <input
                            type="text"
                            name="mobile"
                            class="form-control"
                            placeholder="01XXXXXXXXX"
                            required
                            value="<?= e(formValue('mobile', $edit_driver)) ?>"
                        >

                    </div>

                </div>

 

                <!-- Blood -->

         


                <!-- Present Address -->

                <div class="col-md-6">

                    <label class="form-label">
                        বর্তমান ঠিকানা <span class="required">*</span>
                    </label>

                    <textarea
                        name="present_address"
                        class="form-control"
                        rows="3"
                        placeholder="বর্তমান ঠিকানা লিখুন"
                        required
                    ><?= e(formValue('present_address', $edit_driver)) ?></textarea>

                </div>


                <!-- Permanent Address -->

                <div class="col-md-6">

                    <label class="form-label">
                        স্থায়ী ঠিকানা
                    </label>

                    <textarea
                        name="permanent_address"
                        class="form-control"
                        rows="3"
                        placeholder="স্থায়ী ঠিকানা লিখুন"
                    ><?= e(formValue('permanent_address', $edit_driver)) ?></textarea>

                </div>

            </div>

        </div>


 

        <!-- ==============================
             জরুরি যোগাযোগ
        =============================== -->

        <div class="form-section">

            <div class="section-heading">

                <div class="section-icon emergency-icon">
                    <i class="bi bi-telephone-plus"></i>
                </div>

                <div>
                    <h5>জরুরি যোগাযোগ</h5>
                    <p>প্রয়োজনে যোগাযোগের জন্য ব্যক্তির তথ্য</p>
                </div>

            </div>


            <div class="row g-4">

                <div class="col-md-4">

                    <label class="form-label">
                        ব্যক্তির নাম <span class="required">*</span>
                    </label>

                    <div class="input-icon">

                        <i class="bi bi-person-heart"></i>

                        <input
                            type="text"
                            name="emergency_contact_name"
                            class="form-control"
                            placeholder="নাম"
                            required
                            value="<?= e(formValue('emergency_contact_name', $edit_driver)) ?>"
                        >

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        সম্পর্ক <span class="required">*</span>
                    </label>

                    <div class="input-icon">

                        <i class="bi bi-people"></i>

                        <input
                            type="text"
                            required
                            name="emergency_contact_relation"
                            class="form-control"
                            placeholder="যেমন: বাবা / স্ত্রী / ভাই"
                            value="<?= e(formValue('emergency_contact_relation', $edit_driver)) ?>"
                        >

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        মোবাইল নম্বর <span class="required">*</span>
                    </label>

                    <div class="input-icon">

                        <i class="bi bi-telephone"></i>

                        <input
                            type="text"
                            required
                            name="emergency_contact_mobile"
                            class="form-control"
                            placeholder="01XXXXXXXXX"
                            value="<?= e(formValue('emergency_contact_mobile', $edit_driver)) ?>"
                        >

                    </div>

                </div>

            </div>

        </div>


        <!-- ==============================
             চাকরি সংক্রান্ত তথ্য
        =============================== -->

        <div class="form-section">

            <div class="section-heading">

                <div class="section-icon employment-icon">
                    <i class="bi bi-briefcase"></i>
                </div>

                <div>
                    <h5>চাকরি সংক্রান্ত তথ্য</h5>
                    <p>কর্মী, বেতন ও গাড়ি সংক্রান্ত তথ্য</p>
                </div>

            </div>


            <div class="row g-4">

                <div class="col-md-4">

                    <label class="form-label">
                        ডিউটি ধরন / কাজের ধরন<span class="required">*</span>
                    </label>

                    <select
                        name="employee_type"
                        class="form-select"
                        required
                    >

                        <option value="">
                            নির্বাচন করুন
                        </option>

                        <option
                            value="Permanent"
                            <?= formValue('employee_type', $edit_driver) === 'Permanent'
                                ? 'selected'
                                : '' ?>
                        >
                            পার্ট টাইম (ঘণ্টাপ্রতি/বদলি ডিউটি )
                        </option>

                        <option
                            value="Contract"
                            <?= formValue('employee_type', $edit_driver) === 'Contract'
                                ? 'selected'
                                : '' ?>
                        >
                            ফুল টাইম (পারমানেন্ট)
                        </option>

                        <option
                            value="Part Time"
                            <?= formValue('employee_type', $edit_driver) === 'Part Time'
                                ? 'selected'
                                : '' ?>
                        >
রাতের ডিউটি  (ঘণ্টাপ্রতি/বদলি ডিউটি )                        </option>

                    </select>

                </div>


            <div class="col-md-4">

    <label class="form-label">
        কি ধরণের গাড়ি চালানোর অভিজ্ঞতা আছে ? <span class="required">*</span>
    </label>

    <select
        name="vehicle_type"
        class="form-select"
        required
    >
        <option value="">-- গাড়ির ধরন নির্বাচন করুন --</option>

        <option value="মোটরসাইকেল"
            <?= formValue('vehicle_type', $edit_driver) == 'মোটরসাইকেল' ? 'selected' : '' ?>>
            মোটরসাইকেল
        </option>

        <option value="প্রাইভেট কার"
            <?= formValue('vehicle_type', $edit_driver) == 'প্রাইভেট কার' ? 'selected' : '' ?>>
            প্রাইভেট কার
        </option>

        <option value="মাইক্রোবাস"
            <?= formValue('vehicle_type', $edit_driver) == 'মাইক্রোবাস' ? 'selected' : '' ?>>
            মাইক্রোবাস
        </option>

        

    </select>

</div>


                <div class="col-md-4">

                    <label class="form-label">
                       ড্রাইভিং অভিজ্ঞতা কত বছর <span class="required">*</span>
                    </label>

                    <div class="input-icon">

                        <i class="bi bi-cash-stack"></i>

                        <input
                            type="number"
                            step="0"
                            requied
                            name="driving_experience"
                            class="form-control"
                            placeholder="0"
                            value="<?= e(formValue('driving_experience', $edit_driver)) ?>"
                        >

                    </div>

                </div>


 

          

            </div>

        </div>


        <!-- ==============================
             ডকুমেন্ট
        =============================== -->

        <div class="form-section">

            <div class="section-heading">

                <div class="section-icon document-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>

                <div>
                    <h5>ড্রাইভারের ডকুমেন্ট</h5>
                    <p>প্রয়োজনীয় ছবি ও ডকুমেন্ট আপলোড করুন</p>
                </div>

            </div>


            <div class="row g-4">

                <!-- Profile -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-person-square"></i>
                            </div>

                            <div>

                                <h6>প্রোফাইল ছবি <span class="required">*</span></h6>

                                <small>
                                    JPG, PNG, WEBP
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="profile_image"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['profile_image'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    বর্তমান ছবি পাওয়া গেছে
                                </span>

                                <a
                                    href="<?= e($edit_driver['profile_image']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- NID Front -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-card-image"></i>
                            </div>

                            <div>

                                <h6>NID - সামনের অংশ <span class="required">*</span></h6>

                                <small>
                                    JPG, PNG, WEBP, PDF
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="nid_front"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            required
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['nid_front'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>বর্তমান ডকুমেন্ট আছে</span>

                                <a
                                    href="<?= e($edit_driver['nid_front']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- NID Back -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-card-image"></i>
                            </div>

                            <div>

                                <h6>NID - পিছনের অংশ <span class="required">*</span></h6>

                                <small>
                                    JPG, PNG, WEBP, PDF
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="nid_back"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            required
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['nid_back'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>বর্তমান ডকুমেন্ট আছে</span>

                                <a
                                    href="<?= e($edit_driver['nid_back']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- License Front -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>

                            <div>

                                <h6>লাইসেন্স - সামনের অংশ <span class="required">*</span></h6>

                                <small>
                                    JPG, PNG, WEBP, PDF
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="license_front"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            required
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['license_front'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>বর্তমান ডকুমেন্ট আছে</span>

                                <a
                                    href="<?= e($edit_driver['license_front']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- License Back -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>

                            <div>

                                <h6>লাইসেন্স - পিছনের অংশ <span class="required">*</span></h6>

                                <small>
                                    JPG, PNG, WEBP, PDF
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="license_back"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            required
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['license_back'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>বর্তমান ডকুমেন্ট আছে</span>

                                <a
                                    href="<?= e($edit_driver['license_back']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- Medical -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-file-medical"></i>
                            </div>

                            <div>

                                <h6>বায়ো ডাটা / CV</h6>

                                <small>
                                    JPG, PNG, WEBP, PDF
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="medical_certificate"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['medical_certificate'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>বর্তমান ডকুমেন্ট আছে</span>

                                <a
                                    href="<?= e($edit_driver['medical_certificate']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- Police -->

                <div class="col-md-6">

                    <div class="upload-card">

                        <div class="upload-top">

                            <div class="upload-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div>

                                <h6>শিক্ষাগত যোগ্যতার সনদপত্র / Academic Certificate</h6>

                                <small>
                                    JPG, PNG, WEBP, PDF
                                </small>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="police_clearance"
                            class="form-control mt-3"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <?php if (
                            $edit_driver &&
                            !empty($edit_driver['police_clearance'])
                        ): ?>

                            <div class="existing-file">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>বর্তমান ডকুমেন্ট আছে</span>

                                <a
                                    href="<?= e($edit_driver['police_clearance']) ?>"
                                    target="_blank"
                                >
                                    দেখুন
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- ==============================
             REMARKS
        =============================== -->
 

        <!-- ==============================
             SUBMIT
        =============================== -->

        <div class="form-footer">

            <div class="footer-info">

                <i class="bi bi-shield-check"></i>

                <span>
                    তথ্য সঠিকভাবে পূরণ করে সংরক্ষণ করুন
                </span>

            </div>


            <div class="footer-buttons">

                <?php if ($edit_driver): ?>

                    <a
                        href="index.php"
                        class="btn btn-light btn-lg"
                    >
                        <i class="bi bi-x-lg me-1"></i>
                        বাতিল
                    </a>

                <?php endif; ?>


                <button
                    type="submit"
                    class="btn btn-primary btn-lg save-btn"
                >

                    <i class="bi
                        <?= $edit_driver
                            ? 'bi-check2-circle'
                            : 'bi-person-plus'
                        ?>
                        me-2"
                    ></i>

                    <?= $edit_driver
                        ? 'ড্রাইভারের তথ্য আপডেট করুন'
                        : 'ড্রাইভার সংরক্ষণ করুন'
                    ?>

                </button>

            </div>

        </div>

    </form>

</div>


<style>

/* ==========================================
   DRIVER FORM DESIGN
========================================== */

.driver-form {
    color: #293042;
}

.form-section {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 18px;
    padding: 26px;
    margin-bottom: 24px;
    box-shadow: 0 5px 22px rgba(30, 42, 60, 0.045);
    transition: all .25s ease;
}

.form-section:hover {
    box-shadow: 0 8px 28px rgba(30, 42, 60, 0.07);
}


/* Section heading */

.section-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 25px;
    padding-bottom: 18px;
    border-bottom: 1px solid #edf0f5;
}

.section-heading h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #202939;
}

.section-heading p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #8992a3;
}

.section-icon {
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    color: #0d6efd;
    background: #edf5ff;
}

.license-icon {
    color: #7c3aed;
    background: #f3efff;
}

.emergency-icon {
    color: #e11d48;
    background: #fff0f3;
}

.employment-icon {
    color: #059669;
    background: #ecfdf5;
}

.document-icon {
    color: #d97706;
    background: #fff8e8;
}

.remarks-icon {
    color: #0891b2;
    background: #ecfeff;
}


/* Labels */

.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #4b5565;
}

.required {
    color: #dc3545;
    font-weight: 700;
}


/* Inputs */

.form-control,
.form-select {
    min-height: 46px;
    border-radius: 11px;
    border: 1px solid #dfe4ec;
    color: #303846;
    background-color: #fff;
    font-size: 14px;
    transition: all .2s ease;
}

.form-control::placeholder {
    color: #b0b7c3;
}

.form-control:hover,
.form-select:hover {
    border-color: #b9c2d0;
}

.form-control:focus,
.form-select:focus {
    border-color: #6ea8fe;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, .08);
}

textarea.form-control {
    min-height: 110px;
    resize: vertical;
}


/* Input icon */

.input-icon {
    position: relative;
}

.input-icon > i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa3b2;
    z-index: 2;
    pointer-events: none;
}

.input-icon .form-control {
    padding-left: 40px;
}


/* Status */

.status-box {
    min-height: 70px;
    border: 1px solid #e1e6ee;
    border-radius: 12px;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fafbfc;
}

.status-box .form-check {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

.status-box .form-check-input {
    width: 42px;
    height: 23px;
    cursor: pointer;
}

.status-box .form-check-label {
    font-size: 13px;
    font-weight: 600;
}


/* Upload cards */

.upload-card {
    border: 1px dashed #d6dce6;
    border-radius: 16px;
    padding: 18px;
    background: #fbfcfe;
    transition: all .25s ease;
}

.upload-card:hover {
    border-color: #86b7fe;
    background: #f7faff;
    transform: translateY(-2px);
}

.upload-top {
    display: flex;
    align-items: center;
    gap: 13px;
}

.upload-top h6 {
    margin: 0 0 4px;
    font-weight: 700;
    color: #343b49;
}

.upload-top small {
    color: #929baa;
    font-size: 12px;
}

.upload-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf5ff;
    color: #0d6efd;
    font-size: 20px;
    flex: 0 0 45px;
}

.upload-card .form-control {
    background: #fff;
}


/* Existing file */

.existing-file {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 10px;
    padding: 8px 10px;
    background: #ecfdf5;
    border-radius: 8px;
    font-size: 12px;
    color: #16835a;
}

.existing-file i {
    font-size: 14px;
}

.existing-file a {
    margin-left: auto;
    color: #0d6efd;
    text-decoration: none;
    font-weight: 600;
}

.existing-file a:hover {
    text-decoration: underline;
}


/* Footer */

.form-footer {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 18px;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    box-shadow: 0 5px 22px rgba(30, 42, 60, .05);
}

.footer-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #7d8797;
    font-size: 13px;
}

.footer-info i {
    color: #198754;
    font-size: 18px;
}

.footer-buttons {
    display: flex;
    align-items: center;
    gap: 10px;
}

.save-btn {
    min-width: 220px;
    box-shadow: 0 5px 15px rgba(13, 110, 253, .20);
}


/* Buttons */

.btn {
    border-radius: 10px;
    font-weight: 600;
}

.btn-primary {
    border: 0;
}

.btn-primary:hover {
    transform: translateY(-1px);
}


/* Mobile */

@media (max-width: 767px) {

    .form-section {
        padding: 18px;
        border-radius: 14px;
    }

    .section-heading {
        gap: 11px;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        flex-basis: 42px;
        font-size: 18px;
    }

    .section-heading h5 {
        font-size: 16px;
    }

    .form-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .footer-info {
        justify-content: center;
    }

    .footer-buttons {
        width: 100%;
        flex-direction: column;
    }

    .footer-buttons .btn,
    .save-btn {
        width: 100%;
    }

    .status-box {
        align-items: flex-start;
        gap: 10px;
    }

}

</style>
 

    </div>


 

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>