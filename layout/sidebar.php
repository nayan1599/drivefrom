<div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
        <div class="sidebar-header">
            <a class="brand-mark" href="index.php" aria-label="<?php echo $_SESSION['user_name']; ?>">
                <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                <span class="brand-copy">
                    <span class="brand-title"><?php echo $_SESSION['user_name']; ?></span>
                    <!-- <span class="brand-subtitle">Admin Template</span> -->
                </span>
            </a>
        </div>
        <nav class="sidebar-nav p-3">
      
            <a class="nav-link active" href="index.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- Customer / Cars -->

            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#carMenu"> <span><i class="bi bi-car-front"></i> Cars & Customers</span> <i
                    class="bi bi-chevron-down toggle-icon"></i> </a>

            <div class="collapse" id="carMenu">
                <div class="submenu">
                    <a class="nav-link" href="index.php?page=admin/index">All Drivers</a>
                    <a class="nav-link" href="public/index.php">Add New Car</a>
 
                </div>
            </div>
 
       
        </nav>


    </aside>

    <!-- <style> .submenu { padding-left: 25px; } /* icon rotate */ .nav-link[aria-expanded="true"] .toggle-icon { transform: rotate(180deg); transition: 0.3s; } .nav-link .toggle-icon { transition: 0.3s; } </style> -->