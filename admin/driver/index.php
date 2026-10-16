
<?php

$page_title = "Driver Management";

// Search inputs
// $searchCode    = trim($_GET['code'] ?? '');
$searchAddress = trim($_GET['address'] ?? '');
$searchStatus  = $_GET['status'] ?? '';
$searchemployee_type = $_GET['employee_type'] ?? '';

// Build SQL query
$sql = "SELECT * FROM drivers WHERE 1=1";
$params = [];

// Search by Driver Code
// if ($searchCode !== '') {
//     $sql .= " AND driver_code LIKE :code";
//     $params[':code'] = '%' . $searchCode . '%';
// }

// Search by Address
if ($searchAddress !== '') {
    $sql .= " AND present_address LIKE :present_address";
    $params[':present_address'] = '%' . $searchAddress . '%';
}
// Search by Employee Type
if ($searchemployee_type !== '' && in_array($searchemployee_type, ['permanent', 'contract', 'part_time'], true)) {
    $sql .= " AND employee_type = :employee_type";
    $params[':employee_type'] = $searchemployee_type;
}
// Search by Status
if ($searchStatus !== '' && in_array($searchStatus, ['1', '0'], true)) {
    $sql .= " AND status = :status";
    $params[':status'] = (int) $searchStatus;
}

// Latest records first
$sql .= " ORDER BY id DESC";

// Execute query safely
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


    <div class="d-flex justify-content-between align-items-center mb-4">

<!-- Driver Search Form -->
<div class="card border-0 shadow-sm mb-4 col-md-12">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="bi bi-search me-2"></i>
            Search Drivers
        </h5>
    </div>

    <div class="card-body">
        <form method="GET" action="admin.php">
            <input type="hidden" name="page"
                   value="admin/driver/index">

            <div class="row g-3 align-items-end">

                <!-- Driver Code -->
                <!-- <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">
                        Driver Code
                    </label>

                    <input
                        type="text"
                        name="code"
                        class="form-control"
                        placeholder="Enter driver code"
                        value="<?= htmlspecialchars($searchCode ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div> -->

<!-- employee_type -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">
                        Employee Type
                    </label>

                    <select name="employee_type" class="form-select">
                        <option value="">All Types</option>

                        <option value="permanent"
                            <?= ($searchemployee_type ?? '') === 'Permanent' ? 'selected' : '' ?>>
                           পার্ট টাইম (ঘণ্টাপ্রতি/বদলি ডিউটি )
                        </option>

                        <option value="contract"
                            <?= ($searchemployee_type ?? '') === 'Contract' ? 'selected' : '' ?>>
                            ফুল টাইম (পারমানেন্ট)
                        </option>

                        <option value="part_time"
                            <?= ($searchemployee_type ?? '') === 'Part Time' ? 'selected' : '' ?>>
                          রাতের ডিউটি  (ঘণ্টাপ্রতি/বদলি ডিউটি ) 
                        </option>
                    </select>
                </div>


                <!-- Address -->
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-semibold">
                        Address
                    </label>

                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        placeholder="Enter driver address"
                        value="<?= htmlspecialchars($searchAddress ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <!-- Status -->
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select name="status" class="form-select">
                        <option value="">All Status</option>

                        <option value="1"
                            <?= ($searchStatus ?? '') === '1' ? 'selected' : '' ?>>
                            Active
                        </option>

                        <option value="0"
                            <?= ($searchStatus ?? '') === '0' ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit"
                                class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>

                        <a href="admin.php?page=admin/driver/index"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>


<style>
/* =====================================
   Driver Management Page Header
===================================== */

.driver-page-header {
    background: #ffffff;
    border: 1px solid #e9edf3;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
}


/* Title Area */

.page-title-box {
    display: flex;
    align-items: center;
    gap: 14px;
}

.page-title-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eef4ff;
    color: #2563eb;

    border-radius: 13px;
    font-size: 23px;
}

.page-title-box h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.3;
}

.page-title-box p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #94a3b8;
}


/* Add Driver Button */

.add-driver-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    background: #2563eb;
    color: #ffffff;

    border: none;
    border-radius: 10px;

    padding: 11px 18px;

    font-size: 14px;
    font-weight: 600;

    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.20);

    transition: all 0.2s ease;
}

.add-driver-btn:hover {
    background: #1d4ed8;
    color: #ffffff;

    transform: translateY(-1px);

    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
}

.add-driver-btn:active {
    transform: translateY(0);
}


/* Mobile */

@media (max-width: 767px) {

    .driver-page-header {
        padding: 18px;
    }

    .page-title-box h2 {
        font-size: 19px;
    }

    .page-title-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        font-size: 20px;
    }

    .add-driver-btn {
        width: 100%;
        margin-top: 5px;
    }
}
</style>
 

       

        <?php if ($edit_driver): ?>

            <a
                href="admin.php"
                class="btn btn-secondary"
            >
                + Add New Driver
            </a>

        <?php endif; ?>

    </div>


    <?php if ($message): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= htmlspecialchars($message) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>

     <!-- =========================================================
         DRIVER LIST
    ========================================================== -->

    <div class="card">

        <div class="card-header">

            <strong>
                Driver List
            </strong>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Driver
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Mobile
                            </th>

                            <th>
                            Blood Group
                            </th>
                            <th>
                                Status
                            </th>
                            <th width="200">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if ($drivers): ?>

                        <?php foreach ($drivers as $key => $driver): ?>

                            <tr>

                                <td>
                                    <?= $key + 1 ?>
                                </td>


                                <td>

                                    <?php if (!empty($driver['profile_image'])): ?>

                                        <img
                                            src="<?= htmlspecialchars($driver['profile_image']) ?>"
                                            width="40"
                                            height="40"
                                            class="rounded-circle object-fit-cover me-2"
                                        >

                                    <?php endif; ?>

                                    <strong>
                                        <?= htmlspecialchars($driver['name']) ?>
                                    </strong>

                                </td>


                                <td>
                                    <?= htmlspecialchars($driver['driver_code']) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($driver['mobile']) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars($driver['blood_group']) ?>
                                </td>

 

                                <td>

                                    <?php if ($driver['status']): ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <a
                                        href="admin.php?page=admin/driver/edit&id=<?= $driver['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>

  <a
                                        href="admin.php?page=admin/driver/view&id=<?= $driver['id'] ?>"
                                        class="btn btn-sm btn-success"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="admin.php?page=admin/driver/delete&id=<?= $driver['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this driver?')"
                                    >
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="10"
                                class="text-center py-4"
                            >
                                No drivers found.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
 
 