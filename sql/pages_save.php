<?php
$errors = [];

$title = '';
$slug = '';
$page_type = 'custom';
$content = '';
$meta_title = '';
$meta_description = '';
$status = 'draft';
$sort_order = 0;
$show_in_menu = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $page_type = trim($_POST['page_type'] ?? 'custom');
    $content = $_POST['content'] ?? '';
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $sort_order = (int) ($_POST['sort_order'] ?? 0);
    $show_in_menu = isset($_POST['show_in_menu']) ? 1 : 0;

    // Slug তৈরি
    if ($slug === '') {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
    } else {
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = trim($slug, '-');
    }

    // Validation
    if ($title === '') {
        $errors[] = 'পেজের নাম লিখুন।';
    }

    if ($slug === '') {
        $errors[] = 'সঠিক ইংরেজি Slug লিখুন।';
    }

    $allowedTypes = [
        'home', 'about', 'services',
        'contact', 'blog', 'custom'
    ];

    if (!in_array($page_type, $allowedTypes, true)) {
        $errors[] = 'সঠিক পেজ টাইপ নির্বাচন করুন।';
    }

    $allowedStatuses = ['draft', 'published', 'inactive'];

    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = 'সঠিক স্ট্যাটাস নির্বাচন করুন।';
    }

    // Slug duplicate check
    if (!$errors) {
        $check = $pdo->prepare(
            "SELECT id FROM website_pages WHERE slug = ? LIMIT 1"
        );
        $check->execute([$slug]);

        if ($check->fetch()) {
            $errors[] = 'এই Slug আগে থেকেই ব্যবহার করা হয়েছে।';
        }
    }

    // Save
    if (!$errors) {
     try {

    // Featured image upload
    $featured_image = uploadFile(
        'featured_image',
        'website_pages',
        null,
        'page'
    );

    $stmt = $pdo->prepare("
        INSERT INTO website_pages (
            title,
            slug,
            page_type,
            content,
            featured_image,
            meta_title,
            meta_description,
            status,
            sort_order,
            show_in_menu
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $slug,
        $page_type,
        $content,
        $featured_image,
        $meta_title,
        $meta_description,
        $status,
        $sort_order,
        $show_in_menu
    ]);

    $_SESSION['success'] = 'ওয়েবসাইট পেজ সফলভাবে তৈরি হয়েছে।';

    header('Location: index.php?page=website/pages/index');
    exit;

} catch (Throwable $e) {

    $errors[] = $e instanceof PDOException
        ? 'ডাটাবেজে পেজ সংরক্ষণ করা যায়নি।'
        : $e->getMessage();
}
    }
}