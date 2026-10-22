<!DOCTYPE html>
<html lang="bn">

<head>
    <!-- Basic Meta Information -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ONCallDriverbd - নির্ভরযোগ্য অন-কল ড্রাইভার সার্ভিস।">
    <meta name="keywords" content="ONCallDriverbd, On Call Driver, Driver Service, Bangladesh">
    <meta name="author" content="ONCallDriverbd">
    <title>ONCallDriverbd | অন-কল ড্রাইভার সার্ভিস</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons & Main Stylesheets -->
    <link rel="stylesheet" href="./website/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="./website/css/animate.css">
    <link rel="stylesheet" href="./website/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./website/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="./website/css/magnific-popup.css">
    <link rel="stylesheet" href="./website/css/aos.css">
    <link rel="stylesheet" href="./website/css/ionicons.min.css">
    <link rel="stylesheet" href="./website/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="./website/css/jquery.timepicker.css">
    <link rel="stylesheet" href="./website/css/flaticon.css">
    <link rel="stylesheet" href="./website/css/icomoon.css">
    <!-- Main Website CSS -->
    <link rel="stylesheet" href="./website/css/style.css">
</head>

<body>

    <?php
    // URL থেকে page নেওয়া
    $page = $_GET['page'] ?? '';
    if (!empty($page)) {
        include './website/section/navbar.php';
        include './website/layout/page_title.php';
        $page = trim($page, '/');
    } else {
        $page = 'home';
    }
    // শুধু অনুমোদিত অক্ষর
    if (!preg_match('/^[a-zA-Z0-9_\/-]+$/', $page)) {
        http_response_code(400);
        exit('Invalid page request');
    }

    // .php একবার যোগ করা
    if (!str_ends_with($page, '.php')) {
        $page .= '.php';
    }

    // website-এর page ফোল্ডারের absolute path
    $basePath = __DIR__ . '/page/';
    // print_r($basePath);
// সম্পূর্ণ ফাইলের path
    $pagePath = $basePath . $page;

    // ফাইল আছে কি না যাচাই
    if (is_file($pagePath)) {

        include $pagePath;

    } else {

        http_response_code(404);

        echo '<div class="alert alert-danger">';
        echo '<h4>Page Not Found!</h4>';
        echo '<p>ফাইল পাওয়া যায়নি: ' .
            htmlspecialchars($page, ENT_QUOTES, 'UTF-8') .
            '</p>';
        echo '<p>সঠিক ফাইলের অবস্থান যাচাই করুন।</p>';
        echo '</div>';

    }

    ?>
    <?php include './website/fooder/footer.php'; ?>

    <!-- Page Loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px" viewBox="0 0 48 48" aria-hidden="true">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"></circle>

            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#b31212"></circle>
        </svg>
    </div>

    <!-- JavaScript Libraries -->
    <script src="./website/js/jquery.min.js"></script>
    <script src="./website/js/jquery-migrate-3.0.1.min.js"></script>
    <script src="./website/js/popper.min.js"></script>
    <script src="./website/js/bootstrap.min.js"></script>
    <script src="./website/js/jquery.easing.1.3.js"></script>
    <script src="./website/js/jquery.waypoints.min.js"></script>
    <script src="./website/js/jquery.stellar.min.js"></script>
    <script src="./website/js/owl.carousel.min.js"></script>
    <script src="./website/js/jquery.magnific-popup.min.js"></script>
    <script src="./website/js/aos.js"></script>
    <script src="./website/js/jquery.animateNumber.min.js"></script>
    <script src="./website/js/bootstrap-datepicker.js"></script>
    <script src="./website/js/jquery.timepicker.min.js"></script>
    <script src="./website/js/scrollax.min.js"></script>
    <script src="./website/js/google-map.js"></script>
    <script src="./website/js/main.js"></script>

</body>

</html>