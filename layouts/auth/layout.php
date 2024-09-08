<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../public/favicon.ico" type="image/x-icon">
    <title><?php echo $pageTitle; ?></title>

    <link href="../resources/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../resources/admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../resources/admin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../resources/admin/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../resources/admin/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../resources/admin/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../resources/admin/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../resources/admin/css/style.css" rel="stylesheet">
</head>

<body>
    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <a href="index.php" class="logo d-flex align-items-center w-auto">
                                    <span class="d-none d-lg-block">Warung Iyan</span>
                                </a>
                            </div>

                            <?php
                            if (isset($content)) {
                                echo $content;
                            }
                            ?>

                            <?php include('footer.php'); ?>