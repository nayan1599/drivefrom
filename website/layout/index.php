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
<?php // print_r($_GET)?>
    <!-- Header Section -->
    <?php
    include './website/header/header_area.php';
    ?>

    <!-- Navigation Section -->
    <?php
    include './website/section/navbar.php';
    include './website/section/banner.php';
    include './website/section/order.php';
     include './website/section/feeatured_vehicles.php';
    ?>

    <!-- Main Content Section -->
    <main>
        <?php
        include './website/layout/main.php';
        ?>
    </main>

    <!-- Footer Section -->
    <?php
    include './website/fooder/fooder.php';
    ?>

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

    <!-- Google Maps -->
    <!-- API key নিরাপদে সংরক্ষণ করে ব্যবহার করুন -->
    <script src="./website/js/google-map.js"></script>

    <!-- Main Website JavaScript -->
    <script src="./website/js/main.js"></script>

</body>

</html>