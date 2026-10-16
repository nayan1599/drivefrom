<?php

// Edit form helper: POST value gets priority; otherwise database value is shown.
if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('formValue')) {
    function formValue(string $key, array $edit_driver = []) {
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        return $edit_driver[$key] ?? '';
    }
}

$id = $_GET['id'] ?? '';

if (!$id) {
    echo "Invalid ID";
    exit;
}

// ডাটা লোড
$stmt = $pdo->prepare("SELECT * FROM drivers WHERE id = ?");
$stmt->execute([$id]);
$edit_driver = $stmt->fetch();

if (!$edit_driver) {
    echo "Data not found!";
    exit;
}
?>

 
<div class="card border-0 shadow-sm driver-edit-card">

    <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="bi bi-person-badge me-2 text-primary"></i>
                    ড্রাইভারের তথ্য সম্পাদনা
                </h4>

                <p class="text-muted mb-0">
                    প্রয়োজনীয় তথ্য পরিবর্তন করে আপডেট করুন
                </p>
            </div>

            <?php if (!empty($edit_driver['driver_code'])): ?>
                <div class="driver-code-badge">
                    <small>ড্রাইভার কোড</small>
                    <strong><?= e($edit_driver['driver_code']) ?></strong>
                </div>
            <?php endif; ?>

        </div>
    </div>


    <div class="card-body p-4">

        <form
            method="POST"
            action="index.php?page=sql/driver_update"
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


            <!-- =========================================
                 PERSONAL / DRIVER INFORMATION
            ========================================== -->

            <div class="form-section">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h5 class="mb-1">ড্রাইভারের তথ্য</h5>
                        <p class="mb-0">
                            ব্যক্তিগত, যোগাযোগ ও চাকরি সংক্রান্ত তথ্য
                        </p>
                    </div>

                </div>


                <div class="row g-4">


                    <!-- Name -->
                    <div class="col-lg-4 col-md-6">

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
                                value="<?= e(formValue('name', $edit_driver)) ?>"
                                required
                            >

                        </div>

                    </div>


                    <!-- Driver Code -->
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            ড্রাইভার কোড
                            <span class="required">*</span>
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-upc-scan"></i>

                            <input
                                type="text"
                                name="driver_code"
                                class="form-control"
                                placeholder="যেমন: D-12-DE"
                                value="<?= e(formValue('driver_code', $edit_driver)) ?>"
                                required
                            >

                        </div>

                        <small class="form-text">
                            প্রতিটি ড্রাইভারের কোড আলাদা হতে হবে
                        </small>

                    </div>


                    <!-- Mobile -->
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            মোবাইল নম্বর
                            <span class="required">*</span>
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-telephone"></i>

                            <input
                                type="text"
                                name="mobile"
                                class="form-control"
                                placeholder="01XXXXXXXXX"
                                value="<?= e(formValue('mobile', $edit_driver)) ?>"
                                maxlength="11"
                                required
                            >

                        </div>

                    </div>


                    <!-- Gender -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            লিঙ্গ
                        </label>

                        <div class="select-wrapper">

                            <select
                                name="gender"
                                class="form-select"
                            >

                                <option value="">
                                    নির্বাচন করুন
                                </option>

                                <option
                                    value="Male"
                                    <?= formValue('gender', $edit_driver) === 'Male'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    পুরুষ
                                </option>

                                <option
                                    value="Female"
                                    <?= formValue('gender', $edit_driver) === 'Female'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    নারী
                                </option>

                                <option
                                    value="Other"
                                    <?= formValue('gender', $edit_driver) === 'Other'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    অন্যান্য
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- Blood Group -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            রক্তের গ্রুপ
                            <span class="required">*</span>
                        </label>

                        <select
                            name="blood_group"
                            class="form-select"
                            required
                        >

                            <option value="">
                                নির্বাচন করুন
                            </option>

                            <?php
                            $blood_groups = [
                                'A+',
                                'A-',
                                'B+',
                                'B-',
                                'AB+',
                                'AB-',
                                'O+',
                                'O-'
                            ];
                            ?>

                            <?php foreach ($blood_groups as $blood): ?>

                                <option
                                    value="<?= e($blood) ?>"
                                    <?= formValue(
                                        'blood_group',
                                        $edit_driver
                                    ) === $blood
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= e($blood) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- Joining Date -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            যোগদানের তারিখ
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-calendar-check"></i>

                            <input
                                type="date"
                                name="joining_date"
                                class="form-control"
                                value="<?= e(
                                    formValue(
                                        'joining_date',
                                        $edit_driver
                                    )
                                ) ?>"
                            >

                        </div>

                    </div>


                    <!-- Status -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            স্ট্যাটাস
                            <span class="required">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="1"
                                <?= (string) formValue(
                                    'status',
                                    $edit_driver
                                ) === '1'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                <?= (string) formValue(
                                    'status',
                                    $edit_driver
                                ) === '0'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                </div>

            </div>


            <!-- =========================================
                 FOOTER
            ========================================== -->

            <div class="form-footer">

                <div class="footer-info">

                    <i class="bi bi-shield-check"></i>

                    <div>
                        <strong>তথ্য যাচাই করুন</strong>

                        <small>
                            আপডেট করার আগে সকল তথ্য সঠিক কিনা নিশ্চিত করুন
                        </small>
                    </div>

                </div>


                <div class="footer-buttons">

                    <a
                        href="index.php"
                        class="btn btn-light btn-lg"
                    >
                        <i class="bi bi-x-lg me-1"></i>
                        বাতিল
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary btn-lg save-btn"
                    >
                        <i class="bi bi-check2-circle me-2"></i>
                        ড্রাইভারের তথ্য আপডেট করুন
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<style>

/* ================================
   Main Card
================================ */

.driver-edit-card {
    border-radius: 18px;
    overflow: hidden;
}


/* ================================
   Driver Code Badge
================================ */

.driver-code-badge {
    background: #f1f5ff;
    border: 1px solid #dbe5ff;
    padding: 10px 18px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
}

.driver-code-badge small {
    color: #64748b;
    font-size: 11px;
}

.driver-code-badge strong {
    color: #2563eb;
    font-size: 15px;
}


/* ================================
   Form Section
================================ */

.form-section {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 25px;
}


/* ================================
   Section Heading
================================ */

.section-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 25px;
    padding-bottom: 18px;
    border-bottom: 1px solid #edf0f3;
}

.section-heading h5 {
    font-weight: 700;
    color: #1e293b;
}

.section-heading p {
    font-size: 13px;
    color: #94a3b8;
}


/* ================================
   Section Icon
================================ */

.section-icon {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 12px;
    background: #eef4ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}


/* ================================
   Labels
================================ */

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
}

.required {
    color: #dc3545;
}


/* ================================
   Inputs
================================ */

.form-control,
.form-select {
    min-height: 48px;
    border-radius: 10px;
    border: 1px solid #dfe3e8;
    font-size: 14px;
    transition: all .2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #86a9ff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}


/* ================================
   Input With Icon
================================ */

.input-icon {
    position: relative;
}

.input-icon > i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    z-index: 5;
}

.input-icon .form-control {
    padding-left: 43px;
}


/* ================================
   Helper text
================================ */

.form-text {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    margin-top: 6px;
}


/* ================================
   Footer
================================ */

.form-footer {
    margin-top: 25px;
    padding: 20px 0 0;
    border-top: 1px solid #e9ecef;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}


/* ================================
   Footer Information
================================ */

.footer-info {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #64748b;
}

.footer-info > i {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #ecfdf3;
    color: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.footer-info div {
    display: flex;
    flex-direction: column;
}

.footer-info strong {
    font-size: 13px;
    color: #475569;
}

.footer-info small {
    font-size: 11px;
    color: #94a3b8;
}


/* ================================
   Buttons
================================ */

.footer-buttons {
    display: flex;
    gap: 10px;
}

.footer-buttons .btn {
    border-radius: 10px;
    padding: 11px 20px;
    font-size: 14px;
    font-weight: 600;
}

.save-btn {
    min-width: 220px;
}


/* ================================
   Responsive
================================ */

@media (max-width: 767px) {

    .card-body {
        padding: 18px !important;
    }

    .form-section {
        padding: 18px;
    }

    .form-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .footer-buttons {
        flex-direction: column-reverse;
    }

    .footer-buttons .btn {
        width: 100%;
    }

    .save-btn {
        min-width: auto;
    }

}

</style>
 
