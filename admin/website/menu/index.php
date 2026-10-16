
<?php
$page_title = "Website Menu List";

// Parent Menu-সহ সব Menu
$stmt = $pdo->query("
    SELECT
        m.*,
        p.menu_name AS parent_name
    FROM website_menus m
    LEFT JOIN website_menus p ON p.id = m.parent_id
    ORDER BY m.sort_order ASC, m.id DESC
");

$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<style>
.menu-card {
    border: 0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
}

.menu-card .card-header {
    background: #fff;
    padding: 18px 22px;
    border-bottom: 1px solid #edf0f5;
}

.menu-table th {
    background: #f1f5f9;
    color: #334155;
    font-size: 13px;
    white-space: nowrap;
    padding: 13px;
}

.menu-table td {
    padding: 12px 13px;
    font-size: 13px;
    vertical-align: middle;
}

.menu-table tbody tr:hover {
    background: #f8fafc;
}

.menu-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.menu-active {
    color: #146c43;
    background: #d1e7dd;
}

.menu-inactive {
    color: #842029;
    background: #f8d7da;
}

.menu-actions {
    display: flex;
    gap: 5px;
    white-space: nowrap;
}

.menu-actions .btn {
    border-radius: 7px;
    font-size: 12px;
}
</style>

<div class="card menu-card">

    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

        <div>
            <h5 class="mb-1 fw-bold">
                <i class="bi bi-list-ul text-primary me-2"></i>
                <?= e($page_title) ?>
            </h5>

            <small class="text-muted">
                Total Menus: <?= count($menus) ?>
            </small>
        </div>

        <a href="admin.php?page=admin/website/menu/add"
           class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>
            Add New Menu
        </a>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table menu-table table-hover mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Menu Name</th>
                        <th>Menu URL</th>
                        <th>Parent Menu</th>
                        <th>Order</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($menus)): ?>

                    <?php foreach ($menus as $key => $menu): ?>

                        <tr>
                            <td><?= $key + 1 ?></td>

                            <td class="fw-semibold text-dark">
                                <?php if (!empty($menu['parent_id'])): ?>
                                    <i class="bi bi-arrow-return-right text-muted me-1"></i>
                                <?php else: ?>
                                    <i class="bi bi-list-nested text-primary me-1"></i>
                                <?php endif; ?>

                                <?= e($menu['menu_name']) ?>
                            </td>

                            <td>
                                <code><?= e($menu['menu_url']) ?></code>
                            </td>

                            <td>
                                <?= e($menu['parent_name'] ?? 'Main Menu') ?>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= (int)$menu['sort_order'] ?>
                                </span>
                            </td>

                            <td>
                                <?= e($menu['target'] ?? '_self') ?>
                            </td>

                            <td>
                                <?php if (($menu['status'] ?? '') === 'active'): ?>
                                    <span class="menu-badge menu-active">Active</span>
                                <?php else: ?>
                                    <span class="menu-badge menu-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="menu-actions">

                                    <a
                                        href="admin.php?page=admin/website/menu/edit&id=<?= (int)$menu['id'] ?>"
                                        class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <a
                                        href="admin.php?page=admin/website/menu/delete&id=<?= (int)$menu['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this menu?')">
                                        <i class="bi bi-trash"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No menu found.
                            <br>
                            <small>Click "Add New Menu" to create your first menu.</small>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>