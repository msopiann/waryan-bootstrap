<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <?php
        // Mendapatkan URL saat ini
        $current_page = basename($_SERVER['REQUEST_URI']);
        ?>

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? '' : 'collapsed'; ?>"
                href="dashboard.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'users.php') ? '' : 'collapsed'; ?>" href="users.php">
                <i class="bi bi-people"></i><span>Manage Users</span>
            </a>
        </li><!-- End Manage Users Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'products.php') ? '' : 'collapsed'; ?>" href="products.php">
                <i class="bi bi-box2-heart"></i><span>Manage Products</span>
            </a>
        </li><!-- End Manage Products Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'orders.php') ? '' : 'collapsed'; ?>" href="orders.php">
                <i class="bi bi-wallet2"></i><span>Manage Orders</span>
            </a>
        </li><!-- End Manage Products Nav -->

    </ul>

</aside><!-- End Sidebar-->