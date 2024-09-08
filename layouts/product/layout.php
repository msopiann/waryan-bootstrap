<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="msopiann">
    <link rel="shortcut icon" href="../../resources/frontpage/favicon.ico" type="image/x-icon">

    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="shortcut icon" href="../../public/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,400;0,700;1,700&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../../resources/frontpage/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../resources/frontpage/css/animate.min.css">
    <link rel="stylesheet" href="../../resources/frontpage/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../resources/frontpage/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../../resources/frontpage/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../../resources/frontpage/fonts/icomoon/style.css">
    <link rel="stylesheet" href="../../resources/frontpage/fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="../../resources/frontpage/css/aos.css">
    <link rel="stylesheet" href="../../resources/frontpage/css/style.css">

    <title><?php echo $pageTitle; ?></title>
</head>

<body>

    <?php include('header.php'); ?>


    <div class="my-5">
        <?php
        if (isset($content)) {
            echo $content;
        }
        ?>
    </div>

    <?php include('footer.php'); ?>