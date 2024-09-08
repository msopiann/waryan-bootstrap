<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Layout</title>

    <link rel="shortcut icon" href="../../public/favicon.ico" type="image/x-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../../resources/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../resources/admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../../resources/admin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../../resources/admin/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../../resources/admin/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../../resources/admin/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../../resources/admin/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../../resources/admin/css/style.css" rel="stylesheet">
</head>

<body>

    <?php include('header.php'); ?>

    <?php include('sidebar.php'); ?>

    <main id="main" class="main">
        <div class="pagetitle" style="display: flex; justify-content: space-between; align-items: center;">
            <h1><?php echo isset($pageTitle) ? $pageTitle : 'Halaman Admin'; ?></h1>
            <?php if (isset($pageTitle) && $pageTitle === "Daftar Produk"): ?>
                <a href="product/add.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Product</a>
            <?php endif; ?>
        </div>

        <section class="section dashboard">
            <?php
            if (isset($content)) {
                echo $content;
            }
            ?>
        </section>

    </main>

    <!-- Vendor JS Files -->
    <script src="../../resources/admin/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../../resources/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../resources/admin/vendor/chart.js/chart.umd.js"></script>
    <script src="../../resources/admin/vendor/echarts/echarts.min.js"></script>
    <script src="../../resources/admin/vendor/quill/quill.js"></script>
    <script src="../../resources/admin/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../../resources/admin/vendor/tinymce/tinymce.min.js"></script>
    <script src="../../resources/admin/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../../resources/admin/js/main.js"></script>

</body>

</html>