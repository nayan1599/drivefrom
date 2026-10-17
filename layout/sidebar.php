<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
        <div class="sidebar-header">
            <a class="brand-mark" href="admin.php" aria-label="<?php echo $_SESSION['user_name']; ?>">
                <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                <span class="brand-copy">
                    <span class="brand-title"><?php echo $_SESSION['user_name']; ?></span>
                    <!-- <span class="brand-subtitle">Admin Template</span> -->
                </span>
            </a>
        </div>
        <nav class="sidebar-nav p-3">
      
            <a class="nav-link active" href="admin.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- Customer / Cars -->

            <!-- <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#carMenu"> <span><i class="bi bi-car-front"></i> Cars & Customers</span> <i
                    class="bi bi-chevron-down toggle-icon"></i> </a>

            <div class="collapse" id="carMenu">
                <div class="submenu">
                    <a class="nav-link" href="admin.php?page=admin/driver/index">All Drivers</a>
                    <a class="nav-link" href="admin.php?page=admin/website/onlinefrom/index">online submet from</a>
 
                </div>
            </div> -->




                <!-- Customer / Cars -->

          <!-- ================= WEBSITE MANAGEMENT ================= -->

    <a
        class="nav-link d-flex justify-content-between align-items-center"
        data-bs-toggle="collapse"
        href="#websiteMenu"
        role="button"
        aria-expanded="false"
        aria-controls="websiteMenu">

        <span>
            <i class="bi bi-globe me-2"></i>
            Website Management
        </span>

        <i class="bi bi-chevron-down toggle-icon"></i>
    </a>

    <div class="collapse" id="websiteMenu">

        <div class="submenu">

            <!-- Website Menu -->
            <a
                class="nav-link"
                href="admin.php?page=admin/website/menu/index">

                <i class="bi bi-list me-2"></i>
                Website Menu
            </a>
   <!-- Website Menu -->
            <a
                class="nav-link"
                href="admin.php?page=admin/website/homepage/index">

                <i class="bi bi-list me-2"></i>
                Home section
            </a>
            <!-- Customer Trip Requests -->
            <a
                class="nav-link"
                href="admin.php?page=admin/website/onlinefrom/index">

                <i class="bi bi-car-front me-2"></i>
                Customer Trip Requests
            </a>

            <!-- Website Pages -->
            <a
                class="nav-link"
                href="admin.php?page=admin/website/pages/index">

                <i class="bi bi-file-earmark-text me-2"></i>
                Website Pages
            </a>

            <!-- Contact Messages -->
            <a
                class="nav-link"
                href="admin.php?page=admin/website/contact/index">

                <i class="bi bi-envelope me-2"></i>
                Contact Messages
            </a>

            <!-- Website Settings -->
            <a
                class="nav-link"
                href="admin.php?page=admin/website/settings/index">

                <i class="bi bi-gear me-2"></i>
                Website Settings
            </a>

        </div>

    </div>
 
 
<!-- ================= DRIVER MANAGEMENT ================= -->

 

    <a
        class="nav-link d-flex justify-content-between align-items-center"
        data-bs-toggle="collapse"
        href="#driverMenu"
        role="button"
        aria-expanded="false"
        aria-controls="driverMenu">

        <span>
            <i class="bi bi-person-vcard me-2"></i>
            Driver Management
        </span>

        <i class="bi bi-chevron-down toggle-icon"></i>
    </a>

    <div class="collapse" id="driverMenu">

        <div class="submenu">

            <!-- All Drivers -->
            <a
                class="nav-link"
                href="admin.php?page=admin/driver/index">
                <i class="bi bi-people me-2"></i>
                All Drivers
            </a>

            <!-- Add Driver -->
            <a
                class="nav-link"
                href="admin.php?page=admin/driver/add">
                <i class="bi bi-person-plus me-2"></i>
                Add New Driver
            </a>

            <!-- Driver Applications -->
            <a
                class="nav-link"
                href="admin.php?page=admin/driver/applications">
                <i class="bi bi-file-earmark-person me-2"></i>
                Driver Applications
            </a>

            <!-- Driver Documents -->
            <a
                class="nav-link"
                href="admin.php?page=admin/driver/documents">
                <i class="bi bi-card-checklist me-2"></i>
                Driver Documents
            </a>

        </div>

    </div>

 
 
        </nav>


    </aside>

    <!-- <style> .submenu { padding-left: 25px; } /* icon rotate */ .nav-link[aria-expanded="true"] .toggle-icon { transform: rotate(180deg); transition: 0.3s; } .nav-link .toggle-icon { transition: 0.3s; } </style> -->