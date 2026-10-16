<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $menu_name = trim($_POST['menu_name'] ?? '');
    $menu_url = trim($_POST['menu_url'] ?? '');
    $parent_id = $_POST['parent_id'] ?? '';
    $sort_order = filter_var(
        $_POST['sort_order'] ?? 0,
        FILTER_VALIDATE_INT
    );
    $target = $_POST['target'] ?? '_self';
    $status = $_POST['status'] ?? 'active';

    // Validation
    if ($menu_name === '') {
        $errors[] = 'Menu Name is required.';
    }

    if ($menu_url === '') {
        $errors[] = 'Menu URL is required.';
    }

    if ($sort_order === false || $sort_order < 0) {
        $errors[] = 'Sort Order must be a non-negative number.';
    }

    if (!in_array($target, ['_self', '_blank'], true)) {
        $errors[] = 'Invalid target selected.';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = 'Invalid status selected.';
    }

    // Check parent menu
    if ($parent_id !== '') {
        $parentIdInt = filter_var($parent_id, FILTER_VALIDATE_INT);

        if (!$parentIdInt || $parentIdInt < 1) {
            $errors[] = 'Invalid parent menu.';
        } else {
            $check = $pdo->prepare("
                SELECT id FROM website_menus
                WHERE id = ? AND parent_id IS NULL
            ");
            $check->execute([$parentIdInt]);

            if (!$check->fetchColumn()) {
                $errors[] = 'Selected parent menu was not found.';
            } else {
                $parent_id = $parentIdInt;
            }
        }
    }

    // Save menu
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO website_menus
                    (menu_name, menu_url, parent_id, sort_order, target, status)
                VALUES
                    (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $menu_name,
                $menu_url,
                $parent_id === '' ? null : $parent_id,
                $sort_order,
                $target,
                $status
            ]);

            header('Location:./admin.php?page=admin/website/menu/index&added=1');
            exit;

        } catch (PDOException $e) {
            $errors[] = 'Menu could not be saved. Please try again.';
        }
    }
} ?>