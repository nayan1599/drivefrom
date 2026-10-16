
<?php
$page_title = "Add New Website Menu";

$errors = [];
$menu_name = '';
$menu_url = '';
$parent_id = '';
$sort_order = 0;
$target = '_self';
$status = 'active';

// Parent menu list
$stmt = $pdo->query("
    SELECT id, menu_name
    FROM website_menus
    WHERE parent_id IS NULL
    ORDER BY sort_order ASC, menu_name ASC
");
$parentMenus = $stmt->fetchAll(PDO::FETCH_ASSOC);


function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<style>
.menu-form-card {
    max-width: 900px;
    margin: 0 auto;
    border: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 24px rgba(0,0,0,.07);
}

.menu-form-card .card-header {
    background: #fff;
    border-bottom: 1px solid #edf0f5;
    padding: 20px 24px;
}

.menu-form-card .card-body {
    padding: 24px;
}

.menu-form-card .form-label {
    color: #334155;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

.menu-form-card .form-control,
.menu-form-card .form-select {
    min-height: 44px;
    border-radius: 9px;
    border-color: #dce2ea;
    font-size: 14px;
}

.menu-form-card .form-control:focus,
.menu-form-card .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 .2rem rgba(13,110,253,.10);
}

.menu-form-card .form-text {
    font-size: 12px;
}

.menu-form-card .btn {
    border-radius: 9px;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 600;
}

@media (max-width: 576px) {
    .menu-form-card .card-body,
    .menu-form-card .card-header {
        padding: 18px;
    }
}
</style>

<div class="card menu-form-card">

    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-1 fw-bold">
                <i class="bi bi-list-ul text-primary me-2"></i>
                <?= e($page_title) ?>
            </h5>
            <small class="text-muted">Create a main menu or submenu.</small>
        </div>

        <a href="admin.php?page=admin/website/menu/index"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Menu List
        </a>
    </div>

    <div class="card-body">

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST"
              action="admin.php?page=sql/menu_add">

            <div class="row g-3">

                <!-- Menu Name -->
                <div class="col-md-6">
                    <label class="form-label">
                        Menu Name <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="menu_name"
                        class="form-control"
                        placeholder="e.g. Home, About Us"
                        value="<?= e($menu_name) ?>"
                        maxlength="100"
                        required>
                </div>

                <!-- Menu URL -->
                <div class="col-md-6">
                    <label class="form-label">
                        Menu URL <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="menu_url"
                        class="form-control"
                        placeholder="index.php?page=about"
                        value="<?= e($menu_url) ?>"
                        maxlength="255"
                        required>

                    <div class="form-text">
                        Example: index.php?page=about or #
                    </div>
                </div>

                <!-- Parent Menu -->
                <div class="col-md-6">
                    <label class="form-label">Parent Menu</label>

                    <select name="parent_id" class="form-select">
                        <option value="">Main Menu (No Parent)</option>

                        <?php foreach ($parentMenus as $parent): ?>
                            <option
                                value="<?= (int)$parent['id'] ?>"
                                <?= (string)$parent_id === (string)$parent['id']
                                    ? 'selected'
                                    : '' ?>>

                                <?= e($parent['menu_name']) ?>

                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-text">
                        Select a parent to create a dropdown submenu.
                    </div>
                </div>

                <!-- Sort Order -->
                <div class="col-md-6">
                    <label class="form-label">Sort Order</label>

                    <input
                        type="number"
                        name="sort_order"
                        class="form-control"
                        min="0"
                        value="<?= e($sort_order) ?>"
                        required>
                </div>

                <!-- Target -->
                <div class="col-md-6">
                    <label class="form-label">Open Link In</label>

                    <select name="target" class="form-select">
                        <option value="_self"
                            <?= $target === '_self' ? 'selected' : '' ?>>
                            Same Tab
                        </option>

                        <option value="_blank"
                            <?= $target === '_blank' ? 'selected' : '' ?>>
                            New Tab
                        </option>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">
                        <option value="active"
                            <?= $status === 'active' ? 'selected' : '' ?>>
                            Active
                        </option>

                        <option value="inactive"
                            <?= $status === 'inactive' ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2 flex-wrap">

                <a href="admin.php?page=admin/website/menu/index"
                   class="btn btn-light border">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Menu
                </button>

            </div>

        </form>

    </div>
</div>
