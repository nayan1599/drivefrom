 
<?php

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo '<div class="alert alert-danger">Invalid Driver ID</div>';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM drivers WHERE id = ?");
$stmt->execute([$id]);
$driver = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$driver) {
    echo '<div class="alert alert-warning">Driver not found!</div>';
    exit;
}


/* ================================
   Helper Functions
================================ */

if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

function showValue($value, $default = '—')
{
    if ($value === null || trim((string) $value) === '') {
        return $default;
    }

    return e($value);
}

function formatDateValue($date)
{
    if (empty($date) || $date === '0000-00-00') {
        return '—';
    }

    $time = strtotime($date);

    return $time ? date('d M Y', $time) : e($date);
}

function documentType($path)
{
    return strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
}

function isImageDocument($path)
{
    return in_array(
        documentType($path),
        ['jpg', 'jpeg', 'png', 'webp', 'gif'],
        true
    );
}

$status = (int) ($driver['status'] ?? 0);

?>

<div class="driver-view-page">


    <!-- ==========================================
         PAGE HEADER
    =========================================== -->

    <div class="driver-page-header">

        <div class="row align-items-center g-3">

            <div class="col-lg-7">

                <div class="page-title-box">

                    <div class="page-title-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h2>Driver Details</h2>
                        <p>
                            ড্রাইভারের সম্পূর্ণ তথ্য ও ডকুমেন্ট
                        </p>
                    </div>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="header-actions">

                    <a
                        href="index.php?page=admin/index"
                        class="btn btn-light action-btn"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Back
                    </a>

                    <a
                        href="index.php?page=admin/edit&id=<?= (int) $driver['id'] ?>"
                        class="btn btn-primary action-btn"
                    >
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Driver
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- ==========================================
         PROFILE SUMMARY
    =========================================== -->

    <div class="profile-card">

        <div class="profile-main">

            <div class="profile-photo">

                <?php if (!empty($driver['profile_image'])): ?>

                    <img
                        src="<?= e($driver['profile_image']) ?>"
                        alt="<?= e($driver['name']) ?>"
                    >

                <?php else: ?>

                    <div class="no-profile-photo">
                        <i class="bi bi-person"></i>
                    </div>

                <?php endif; ?>

            </div>


            <div class="profile-details">

                <div class="profile-name-row">

                    <h3>
                        <?= showValue($driver['name']) ?>
                    </h3>

                    <?php if ($status === 1): ?>

                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle-fill"></i>
                            Active
                        </span>

                    <?php else: ?>

                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle-fill"></i>
                            Inactive
                        </span>

                    <?php endif; ?>

                </div>


                <div class="profile-meta">

                    <span>
                        <i class="bi bi-upc-scan"></i>
                        <?= showValue($driver['driver_code']) ?>
                    </span>

                    <span>
                        <i class="bi bi-telephone"></i>
                        <?= showValue($driver['mobile']) ?>
                    </span>

                    <span>
                        <i class="bi bi-droplet-fill"></i>
                        <?= showValue($driver['blood_group']) ?>
                    </span>

                </div>

            </div>

        </div>


        <div class="profile-id-box">

            <span>Driver ID</span>

            <strong>
                #<?= (int) $driver['id'] ?>
            </strong>

        </div>

    </div>



    <!-- ==========================================
         PERSONAL INFORMATION
    =========================================== -->

    <div class="details-card">

        <div class="details-card-header">

            <div class="section-icon personal-icon">
                <i class="bi bi-person"></i>
            </div>

            <div>
                <h5>ব্যক্তিগত তথ্য</h5>
                <p>ড্রাইভারের ব্যক্তিগত ও যোগাযোগের তথ্য</p>
            </div>

        </div>


        <div class="details-grid">

            <div class="info-item">
                <span class="info-label">পূর্ণ নাম</span>
                <strong><?= showValue($driver['name']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">ড্রাইভার কোড</span>
                <strong><?= showValue($driver['driver_code']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">UID</span>
                <strong><?= showValue($driver['uid']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">পিতার নাম</span>
                <strong><?= showValue($driver['father_name']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">মাতার নাম</span>
                <strong><?= showValue($driver['mother_name']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">জন্ম তারিখ</span>
                <strong>
                    <?= formatDateValue($driver['dob']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">লিঙ্গ</span>
                <strong><?= showValue($driver['gender']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">রক্তের গ্রুপ</span>

                <?php if (!empty($driver['blood_group'])): ?>
                    <strong class="blood-group">
                        <i class="bi bi-droplet-fill"></i>
                        <?= e($driver['blood_group']) ?>
                    </strong>
                <?php else: ?>
                    <strong>—</strong>
                <?php endif; ?>

            </div>

            <div class="info-item">
                <span class="info-label">NID নম্বর</span>
                <strong><?= showValue($driver['nid_number']) ?></strong>
            </div>

            <div class="info-item">
                <span class="info-label">মোবাইল নম্বর</span>

                <?php if (!empty($driver['mobile'])): ?>
                    <a
                        href="tel:<?= e($driver['mobile']) ?>"
                        class="info-link"
                    >
                        <?= e($driver['mobile']) ?>
                    </a>
                <?php else: ?>
                    <strong>—</strong>
                <?php endif; ?>

            </div>

            <div class="info-item">
                <span class="info-label">বিকল্প মোবাইল</span>
                <strong>
                    <?= showValue($driver['alternative_mobile']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">ইমেইল</span>

                <?php if (!empty($driver['email'])): ?>
                    <a
                        href="mailto:<?= e($driver['email']) ?>"
                        class="info-link"
                    >
                        <?= e($driver['email']) ?>
                    </a>
                <?php else: ?>
                    <strong>—</strong>
                <?php endif; ?>

            </div>

        </div>


        <div class="address-grid">

            <div class="address-box">

                <div class="address-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <div>
                    <span>বর্তমান ঠিকানা</span>

                    <p>
                        <?= showValue($driver['present_address']) ?>
                    </p>
                </div>

            </div>


            <div class="address-box">

                <div class="address-icon">
                    <i class="bi bi-house"></i>
                </div>

                <div>
                    <span>স্থায়ী ঠিকানা</span>

                    <p>
                        <?= showValue($driver['permanent_address']) ?>
                    </p>
                </div>

            </div>

        </div>

    </div>



    <!-- ==========================================
         LICENSE INFORMATION
    =========================================== -->

    <div class="details-card">

        <div class="details-card-header">

            <div class="section-icon license-icon">
                <i class="bi bi-card-text"></i>
            </div>

            <div>
                <h5>ড্রাইভিং লাইসেন্স</h5>
                <p>লাইসেন্স ও ড্রাইভিং অভিজ্ঞতার তথ্য</p>
            </div>

        </div>


        <div class="details-grid">

            <div class="info-item">
                <span class="info-label">লাইসেন্স নম্বর</span>
                <strong>
                    <?= showValue($driver['license_number']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">লাইসেন্সের ধরন</span>
                <strong>
                    <?= showValue($driver['license_type']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">ইস্যুর তারিখ</span>
                <strong>
                    <?= formatDateValue($driver['license_issue_date']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">মেয়াদ শেষ</span>
                <strong>
                    <?= formatDateValue($driver['license_expiry_date']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">লাইসেন্স কর্তৃপক্ষ</span>
                <strong>
                    <?= showValue($driver['license_authority']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">ড্রাইভিং অভিজ্ঞতা</span>
                <strong>
                    <?= showValue($driver['driving_experience']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">গাড়ির ধরন</span>
                <strong>
                    <?= showValue($driver['vehicle_type']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">Assigned Vehicle ID</span>
                <strong>
                    <?= showValue($driver['assigned_vehicle_id']) ?>
                </strong>
            </div>

        </div>

    </div>


 


    <!-- ==========================================
         EMPLOYMENT INFORMATION
    =========================================== -->

    <div class="details-card">

        <div class="details-card-header">

            <div class="section-icon employment-icon">
                <i class="bi bi-briefcase"></i>
            </div>

            <div>
                <h5>চাকরি সংক্রান্ত তথ্য</h5>
                <p>চাকরি, বেতন ও বর্তমান অবস্থার তথ্য</p>
            </div>

        </div>


        <div class="details-grid">

            <div class="info-item">
                <span class="info-label">কর্মীর ধরন</span>
                <strong>
                    <?= showValue($driver['employee_type']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">যোগদানের তারিখ</span>
                <strong>
                    <?= formatDateValue($driver['joining_date']) ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">মাসিক বেতন</span>

                <?php if (
                    isset($driver['salary']) &&
                    $driver['salary'] !== ''
                ): ?>

                    <strong class="salary-value">
                        ৳ <?= number_format((float) $driver['salary'], 2) ?>
                    </strong>

                <?php else: ?>

                    <strong>—</strong>

                <?php endif; ?>

            </div>

            <div class="info-item">
                <span class="info-label">স্ট্যাটাস</span>

                <?php if ($status === 1): ?>

                    <span class="status-badge status-active">
                        <i class="bi bi-check-circle-fill"></i>
                        Active
                    </span>

                <?php else: ?>

                    <span class="status-badge status-inactive">
                        <i class="bi bi-x-circle-fill"></i>
                        Inactive
                    </span>

                <?php endif; ?>

            </div>

        </div>

    </div>



    <!-- ==========================================
         DOCUMENTS
    =========================================== -->

    <div class="details-card">

        <div class="details-card-header">

            <div class="section-icon document-icon">
                <i class="bi bi-folder2-open"></i>
            </div>

            <div>
                <h5>ড্রাইভারের ডকুমেন্ট</h5>
                <p>সংরক্ষিত ছবি ও প্রয়োজনীয় ডকুমেন্ট</p>
            </div>

        </div>


        <?php

        $documents = [

            [
                'title' => 'প্রোফাইল ছবি',
                'field' => 'profile_image',
                'icon'  => 'bi-person-square'
            ],

            [
                'title' => 'NID - সামনের অংশ',
                'field' => 'nid_front',
                'icon'  => 'bi-card-image'
            ],

            [
                'title' => 'NID - পিছনের অংশ',
                'field' => 'nid_back',
                'icon'  => 'bi-card-image'
            ],

            [
                'title' => 'লাইসেন্স - সামনের অংশ',
                'field' => 'license_front',
                'icon'  => 'bi-file-earmark-text'
            ],

            [
                'title' => 'লাইসেন্স - পিছনের অংশ',
                'field' => 'license_back',
                'icon'  => 'bi-file-earmark-text'
            ],

            [
                'title' => 'বায়ো ডাটা / CV',
                'field' => 'medical_certificate',
                'icon'  => 'bi-file-earmark-person'
            ],

            [
                'title' => 'Academic Certificate',
                'field' => 'police_clearance',
                'icon'  => 'bi-mortarboard'
            ]

        ];

        ?>


        <div class="row g-4">

            <?php foreach ($documents as $document): ?>

                <?php

                $field = $document['field'];
                $path  = $driver[$field] ?? '';

                ?>

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="document-card">

                        <?php if (!empty($path)): ?>


                            <?php if (isImageDocument($path)): ?>

                                <a
                                    href="<?= e($path) ?>"
                                    target="_blank"
                                    class="document-preview"
                                >
                                    <img
                                        src="<?= e($path) ?>"
                                        alt="<?= e($document['title']) ?>"
                                    >

                                    <div class="preview-overlay">
                                        <i class="bi bi-eye"></i>
                                    </div>
                                </a>

                            <?php else: ?>

                                <a
                                    href="<?= e($path) ?>"
                                    target="_blank"
                                    class="document-file-preview"
                                >
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>

                            <?php endif; ?>


                            <div class="document-content">

                                <div class="document-title">

                                    <i class="bi <?= e($document['icon']) ?>"></i>

                                    <span>
                                        <?= e($document['title']) ?>
                                    </span>

                                </div>


                                <a
                                    href="<?= e($path) ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary w-100"
                                >
                                    <i class="bi bi-eye me-1"></i>
                                    দেখুন
                                </a>

                            </div>


                        <?php else: ?>


                            <div class="document-empty">

                                <div class="empty-document-icon">
                                    <i class="bi <?= e($document['icon']) ?>"></i>
                                </div>

                                <strong>
                                    <?= e($document['title']) ?>
                                </strong>

                                <small>
                                    কোনো ডকুমেন্ট নেই
                                </small>

                            </div>


                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>



    <!-- ==========================================
         REMARKS
    =========================================== -->

    <?php if (!empty($driver['remarks'])): ?>

        <div class="details-card">

            <div class="details-card-header">

                <div class="section-icon remarks-icon">
                    <i class="bi bi-chat-left-text"></i>
                </div>

                <div>
                    <h5>Remarks</h5>
                    <p>ড্রাইভার সম্পর্কে অতিরিক্ত তথ্য</p>
                </div>

            </div>


            <div class="remarks-box">
                <?= nl2br(e($driver['remarks'])) ?>
            </div>

        </div>

    <?php endif; ?>



    <!-- ==========================================
         SYSTEM INFORMATION
    =========================================== -->

    <div class="system-info">

        <div>
            <i class="bi bi-calendar-plus"></i>

            <span>
                Created:
                <strong>
                    <?= !empty($driver['created'])
                        ? e(date(
                            'd M Y, h:i A',
                            strtotime($driver['created'])
                        ))
                        : '—'
                    ?>
                </strong>
            </span>
        </div>


        <div>
            <i class="bi bi-clock-history"></i>

            <span>
                Last Updated:
                <strong>
                    <?= !empty($driver['updated'])
                        ? e(date(
                            'd M Y, h:i A',
                            strtotime($driver['updated'])
                        ))
                        : '—'
                    ?>
                </strong>
            </span>
        </div>

    </div>

</div>



<style>

/* =====================================
   PAGE
===================================== */

.driver-view-page {
    padding-bottom: 30px;
}


/* =====================================
   HEADER
===================================== */

.driver-page-header {
    background: #ffffff;
    border: 1px solid #e8edf3;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, .05);
}

.page-title-box {
    display: flex;
    align-items: center;
    gap: 14px;
}

.page-title-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;
    border-radius: 13px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eef4ff;
    color: #2563eb;
    font-size: 23px;
}

.page-title-box h2 {
    margin: 0;
    color: #172033;
    font-size: 22px;
    font-weight: 700;
}

.page-title-box p {
    margin: 4px 0 0;
    color: #94a3b8;
    font-size: 13px;
}

.header-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.action-btn {
    border-radius: 9px;
    padding: 9px 16px;
    font-weight: 600;
}


/* =====================================
   PROFILE CARD
===================================== */

.profile-card {
    position: relative;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;

    background: linear-gradient(
        135deg,
        #ffffff 0%,
        #f7faff 100%
    );

    border: 1px solid #e4eaf2;
    border-radius: 18px;
    padding: 25px;

    margin-bottom: 20px;

    box-shadow: 0 5px 20px rgba(15, 23, 42, .05);
}

.profile-main {
    display: flex;
    align-items: center;
    gap: 20px;
}

.profile-photo {
    width: 100px;
    height: 100px;
    min-width: 100px;

    border-radius: 18px;
    overflow: hidden;

    background: #eef2f7;

    border: 4px solid #ffffff;

    box-shadow: 0 5px 18px rgba(15, 23, 42, .12);
}

.profile-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-profile-photo {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #94a3b8;
    font-size: 42px;
}

.profile-name-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.profile-name-row h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #172033;
}

.profile-meta {
    margin-top: 12px;

    display: flex;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;

    color: #64748b;
    font-size: 13px;
}

.profile-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.profile-meta i {
    color: #2563eb;
}

.profile-id-box {
    text-align: right;
}

.profile-id-box span {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.profile-id-box strong {
    color: #2563eb;
    font-size: 24px;
}


/* =====================================
   STATUS
===================================== */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    width: fit-content;

    padding: 5px 10px;
    border-radius: 20px;

    font-size: 11px;
    font-weight: 700;
}

.status-active {
    background: #ecfdf3;
    color: #15803d;
}

.status-inactive {
    background: #fef2f2;
    color: #dc2626;
}


/* =====================================
   DETAILS CARD
===================================== */

.details-card {
    background: #ffffff;

    border: 1px solid #e8edf3;
    border-radius: 16px;

    padding: 24px;

    margin-bottom: 20px;

    box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
}

.details-card-header {
    display: flex;
    align-items: center;
    gap: 13px;

    padding-bottom: 17px;
    margin-bottom: 20px;

    border-bottom: 1px solid #edf0f4;
}

.details-card-header h5 {
    margin: 0;
    color: #1e293b;
    font-size: 16px;
    font-weight: 700;
}

.details-card-header p {
    margin: 3px 0 0;
    color: #94a3b8;
    font-size: 12px;
}

.section-icon {
    width: 43px;
    height: 43px;
    min-width: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    font-size: 19px;
}

.personal-icon {
    background: #eef4ff;
    color: #2563eb;
}

.license-icon {
    background: #f3e8ff;
    color: #9333ea;
}

.emergency-icon {
    background: #fff1f2;
    color: #e11d48;
}

.employment-icon {
    background: #ecfdf5;
    color: #059669;
}

.document-icon {
    background: #fff7ed;
    color: #ea580c;
}

.remarks-icon {
    background: #f1f5f9;
    color: #475569;
}


/* =====================================
   DETAILS GRID
===================================== */

.details-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 14px;
}

.info-item {
    min-height: 82px;

    background: #f8fafc;

    border: 1px solid #eef2f6;
    border-radius: 11px;

    padding: 14px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
}

.info-label {
    color: #94a3b8;
    font-size: 11px;
}

.info-item strong {
    color: #334155;
    font-size: 14px;
    font-weight: 600;
    word-break: break-word;
}

.info-link {
    color: #2563eb;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.info-link:hover {
    text-decoration: underline;
}

.blood-group {
    color: #dc2626 !important;
}

.salary-value {
    color: #059669 !important;
}


/* =====================================
   ADDRESS
===================================== */

.address-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;

    gap: 14px;

    margin-top: 14px;
}

.address-box {
    display: flex;
    gap: 13px;

    background: #f8fafc;
    border: 1px solid #eef2f6;
    border-radius: 11px;

    padding: 16px;
}

.address-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #eef4ff;
    color: #2563eb;
}

.address-box span {
    display: block;

    color: #94a3b8;
    font-size: 11px;

    margin-bottom: 4px;
}

.address-box p {
    margin: 0;

    color: #334155;
    font-size: 13px;
    font-weight: 500;

    line-height: 1.6;
}


/* =====================================
   DOCUMENTS
===================================== */

.document-card {
    height: 100%;

    background: #ffffff;

    border: 1px solid #e8edf3;
    border-radius: 13px;

    overflow: hidden;

    transition: .2s ease;
}

.document-card:hover {
    transform: translateY(-2px);

    box-shadow: 0 7px 20px rgba(15, 23, 42, .08);
}

.document-preview {
    position: relative;

    display: block;

    height: 170px;

    overflow: hidden;

    background: #f1f5f9;
}

.document-preview img {
    width: 100%;
    height: 100%;

    object-fit: cover;

    transition: transform .25s ease;
}

.document-preview:hover img {
    transform: scale(1.04);
}

.preview-overlay {
    position: absolute;
    inset: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(15, 23, 42, .45);

    color: #ffffff;
    font-size: 25px;

    opacity: 0;

    transition: .2s ease;
}

.document-preview:hover .preview-overlay {
    opacity: 1;
}

.document-file-preview {
    height: 170px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #fff5f5;

    color: #dc2626;
    font-size: 55px;

    text-decoration: none;
}

.document-content {
    padding: 14px;
}

.document-title {
    display: flex;
    align-items: center;
    gap: 8px;

    margin-bottom: 12px;

    color: #334155;
    font-size: 13px;
    font-weight: 600;
}

.document-title i {
    color: #64748b;
}

.document-empty {
    min-height: 235px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    padding: 20px;

    text-align: center;
}

.empty-document-icon {
    width: 55px;
    height: 55px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #f1f5f9;

    border-radius: 50%;

    color: #94a3b8;

    font-size: 22px;

    margin-bottom: 12px;
}

.document-empty strong {
    color: #475569;
    font-size: 13px;
}

.document-empty small {
    color: #94a3b8;
    font-size: 11px;

    margin-top: 4px;
}


/* =====================================
   REMARKS
===================================== */

.remarks-box {
    padding: 16px;

    background: #f8fafc;

    border-left: 3px solid #2563eb;
    border-radius: 8px;

    color: #475569;

    font-size: 13px;
    line-height: 1.7;
}


/* =====================================
   SYSTEM INFO
===================================== */

.system-info {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;

    padding: 14px 18px;

    background: #f8fafc;

    border: 1px solid #e8edf3;
    border-radius: 11px;

    color: #94a3b8;
    font-size: 11px;
}

.system-info div {
    display: flex;
    align-items: center;
    gap: 6px;
}

.system-info strong {
    color: #64748b;
}


/* =====================================
   RESPONSIVE
===================================== */

@media (max-width: 1199px) {

    .details-grid {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

}


@media (max-width: 991px) {

    .details-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .header-actions {
        justify-content: flex-start;
    }

}


@media (max-width: 767px) {

    .driver-page-header,
    .details-card,
    .profile-card {
        padding: 18px;
    }

    .profile-card {
        align-items: flex-start;
    }

    .profile-photo {
        width: 75px;
        height: 75px;
        min-width: 75px;

        border-radius: 14px;
    }

    .profile-name-row h3 {
        font-size: 19px;
    }

    .profile-id-box {
        display: none;
    }

    .profile-meta {
        gap: 9px;
        flex-direction: column;
        align-items: flex-start;
    }

    .address-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 575px) {

    .details-grid {
        grid-template-columns: 1fr;
    }

    .profile-main {
        align-items: flex-start;
    }

    .header-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .action-btn {
        width: 100%;
    }

    .system-info {
        flex-direction: column;
    }

}

</style>
 
