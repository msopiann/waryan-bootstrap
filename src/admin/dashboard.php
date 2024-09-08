<?php
// src/admin/dashboard.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    // Redirect to login page if not an admin
    header("Location: ../sign-in-admin.php");
    exit;
}

require_once __DIR__ . '/../../helpers/user_crud.php';
require_once __DIR__ . '/../../helpers/product_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';

// Menentukan periode berdasarkan filter
$period = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Mapping filter ke parameter fungsi
$periodMapping = [
    'today' => 'day',
    'this-week' => 'week',
    'this-month' => 'month'

];

$currentPeriod = $periodMapping[$period] ?? 'day';

$currentSales = countSales($currentPeriod);
$currentRevenue = calculateRevenue($currentPeriod);
$recentSales = getRecentSales($currentPeriod, 10);
$topProducts = getTopSalesProducts($currentPeriod);

// Menghitung penjualan untuk periode sebelumnya (untuk perbandingan)
$previousPeriod = $period === 'today' ? 'day' : ($period === 'this-week' ? 'week' : 'month');
$previousSales = countSales($previousPeriod);
$previousRevenue = calculateRevenue($previousPeriod);

// Menghitung persentase perubahan
$percentageSalesChange = $previousSales > 0 ? (($currentSales - $previousSales) / $previousSales) * 100 : 0;
$percentageRevenueChange = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

// Fungsi untuk menghasilkan URL dengan filter
function getFilterUrl($filter)
{
    $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');
    return $currentUrl . '?filter=' . $filter;
}

// Menentukan judul berdasarkan filter
$filterTitles = [
    'today' => 'Today',
    'this-week' => 'This Week',
    'this-month' => 'This Month'

];
$filterTitle = $filterTitles[$period] ?? 'Today';

$totalCustomers = countNonAdminUsers();

$pageTitle = "Dashboard";

ob_start();
?>

<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row">

                <!-- Sales Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('today'); ?>">Today</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-week'); ?>">This
                                        Week</a></li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-month'); ?>">This
                                        Month</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Sales <span>|
                                    <?php echo htmlspecialchars($filterTitle); ?></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo number_format($currentSales); ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Sales Card -->

                <!-- Revenue Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('today'); ?>">Today</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-week'); ?>">This
                                        Week</a></li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-month'); ?>">This
                                        Month</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Revenue <span>|
                                    <?php echo htmlspecialchars($filterTitle); ?></span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-2">
                                    <h6><?php echo formatToRupiah($currentRevenue); ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Revenue Card -->

                <!-- Customers Card -->
                <div class="col-xxl-4 col-xl-12">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Customers</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $totalCustomers; ?></h6>
                                    <span class="text-success small pt-1 fw-bold">Pelanggan Aktif</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Customers Card -->

                <!-- Recent Sales -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('today'); ?>">Today</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-month'); ?>">This
                                        Month</a></li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-year'); ?>">This
                                        Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Recent Sales <span>|
                                    <?php echo htmlspecialchars($filterTitle); ?></span></h5>

                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentSales as $sale): ?>
                                        <tr>
                                            <th scope="row"><a href="#">#<?php echo htmlspecialchars($sale['id']); ?></a>
                                            </th>
                                            <td><?php echo htmlspecialchars($sale['email']); ?></td>
                                            <td><a href="#"
                                                    class="text-primary"><?php echo htmlspecialchars($sale['products']); ?></a>
                                            </td>
                                            <td><?php echo formatToRupiah($sale['total_price']); ?></td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Recent Sales -->

                <!-- Top Selling -->
                <div class="col-12">
                    <div class="card top-selling overflow-auto">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('today'); ?>">Today</a></li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-week'); ?>">This
                                        Week</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo getFilterUrl('this-month'); ?>">This
                                        Month</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Top Selling <span>|
                                    <?php echo htmlspecialchars($filterTitle); ?>
                                </span></h5>

                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Preview</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Sold</th>
                                        <th scope="col">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $period = isset($_GET['period']) ? $_GET['period'] : 'day';

                                    foreach ($topProducts as $product) {
                                        echo '<tr>';
                                        echo '<th scope="row"><a href="#" class="text-primary fw-bold">' . htmlspecialchars($product['name']) . '</a></th>';
                                        $imageUrl = !empty($product['image_url']) ? $product['image_url'] : 'assets/img/default-product.jpg';
                                        $altText = htmlspecialchars($product['name']);
                                        echo '<td><a href="#"><img src="' . htmlspecialchars($imageUrl) . '" alt="' . $altText . '" width="50" height="50"></a></td>';
                                        echo '<td>' . formatToRupiah($product['price']) . '</td>';
                                        echo '<td class="fw-bold">' . $product['total_sold'] . '</td>';
                                        echo '<td>' . formatToRupiah($product['total_revenue']) . '</td>';
                                        echo '</tr>';
                                    }

                                    // Jika tidak ada produk yang ditemukan
                                    if (empty($topProducts)) {
                                        echo '<tr><td colspan="5" class="text-center">No top-selling products found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
                <!-- End Top Selling -->


            </div>
        </div><!-- End Left side columns -->

    </div>
</section>

<?php $content = ob_get_clean();
include('../../layouts/admin/layout.php'); ?>