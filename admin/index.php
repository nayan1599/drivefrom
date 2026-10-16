<?php $stmt = $pdo->query("SELECT * FROM drivers ORDER BY id DESC ");
$drivers = $stmt->fetchAll(); 
$page_title = "Driver Management";

?>

    <div class="d-flex justify-content-between align-items-center mb-4">

<div class="driver-page-header">
    <div class="row align-items-center g-3">

        <!-- Title -->
        <div class="col-md-6 col-sm-12">
            <div class="page-title-box">
                <div class="page-title-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>
                    <h2>Driver Management</h2>
                    <p>Manage driver information and records</p>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div class="col-md-6 col-sm-12">
            <div class="text-md-end">

                <a
                    href="add.php"
                    class="btn add-driver-btn"
                >
                    <i class="bi bi-plus-lg me-2"></i>
                    Add New Driver
                </a>

            </div>
        </div>

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
                href="index.php"
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
                                        href="index.php?page=admin/edit&id=<?= $driver['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>

  <a
                                        href="index.php?page=admin/view&id=<?= $driver['id'] ?>"
                                        class="btn btn-sm btn-success"
                                    >
                                        View
                                    </a>

                                    <a
                                        href="index.php?page=admin/delete&id=<?= $driver['id'] ?>"
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
 
 