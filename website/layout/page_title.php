 
<?php

$page = $_GET['page'] ?? 'home';
$page = trim($page, '/');

// Security validation
if (!preg_match('/^[a-zA-Z0-9_\/-]+$/', $page)) {
    http_response_code(400);
    exit('Invalid page request');
}

// Remove .php extension if supplied
$page = preg_replace('/\.php$/', '', $page);

// Page titles
$titles = [
    'home'        => 'Home',
    'contact'     => 'Contact Us',
    'about'       => 'About Us',
    'about/index' => 'About Us',
    'services'    => 'Our Services',
    'gallery'     => 'Gallery',
];

$pageName = basename($page);
$pageName = str_replace(['-', '_'], ' ', $pageName);
$pageTitle = $titles[$page] ?? ucwords($pageName);

// Breadcrumb segments
$segments = explode('/', $page);
?>

<section
    class="hero-wrap hero-wrap-2 js-fullheight"
    style="background-image: url('images/bg_3.jpg');"
    data-stellar-background-ratio="0.5"
>
    <div class="overlay"></div>

    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">

            <div class="col-md-9 ftco-animate pb-5">

                <!-- Breadcrumb -->
                <p class="breadcrumbs">

                    <span class="mr-2">
                        <a href="index.php">
                            Home
                            <i class="ion-ios-arrow-forward"></i>
                        </a>
                    </span>

                    <?php if ($page !== 'home'): ?>

                        <?php
                        $currentPath = '';
                        $lastIndex = count($segments) - 1;
                        ?>

                        <?php foreach ($segments as $index => $segment): ?>

                            <?php
                            $currentPath .= ($currentPath !== '' ? '/' : '') . $segment;

                            $segmentName = str_replace(
                                ['-', '_'],
                                ' ',
                                $segment
                            );

                            $segmentTitle = ucwords($segmentName);

                            $segmentTitle = $titles[$currentPath] ?? $segmentTitle;
                            ?>

                            <span class="mr-2">
                                <?php if ($index < $lastIndex): ?>

                                    <a href="index.php?page=<?= htmlspecialchars(
                                        $currentPath,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>">
                                        <?= htmlspecialchars(
                                            $segmentTitle,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                        <i class="ion-ios-arrow-forward"></i>
                                    </a>

                                <?php else: ?>

                                    <span class="current">
                                        <?= htmlspecialchars(
                                            $pageTitle,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                <?php endif; ?>
                            </span>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </p>

                <!-- Page Title -->
                <h1 class="mb-3 bread">
                    <?= htmlspecialchars(
                        $pageTitle,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </h1>

            </div>
        </div>
    </div>
</section>
 