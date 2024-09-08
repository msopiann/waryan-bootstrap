<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<div class="search-form" id="search-form">
    <form action="">
        <input type="search" class="form-control" placeholder="Enter keyword to search...">
        <button class="button">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                <path fill-rule="evenodd"
                    d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
            </svg>
        </button>
        <button class="button">
            <div class="close-search">
                <span class="icofont-close js-close-search"></span>
            </div>
        </button>

    </form>
</div>

<div class="site-mobile-menu">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
            <span class="icofont-close js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div>

<nav class="site-nav mb-5">
    <div class="sticky-nav js-sticky-header">

        <div class="container position-relative">
            <div class="site-navigation text-center dark">
                <a href="../index.php" class="logo menu-absolute m-0">Warung Iyan<span class="text-primary">.</span></a>

                <?php
                $current_url = $_SERVER['REQUEST_URI'];

                $menu_items = [
                    '../index.php' => 'Home',
                    '../catalog.php' => 'Shop',
                ];
                ?>

                <ul class="js-clone-nav pl-0 d-none d-lg-inline-block site-menu">
                    <?php foreach ($menu_items as $page => $label): ?>
                        <li class="<?php echo (strpos($current_url, $page) !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo $page; ?>"><?php echo $label; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="menu-icons d-flex align-items-center">
                    <a href="#" class="btn-custom-search" id="btn-search">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                            <path fill-rule="evenodd"
                                d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                        </svg>
                    </a>

                    <a href="wishlist.php" class="cart mr-2">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                        </svg>
                    </a>

                    <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
                        <div class="dropdown ml-2 user-profile">
                            <button class="dropdown-toggle d-flex align-items-center border-0 shadow-none bg-transparent"
                                type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person mr-2"
                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M13 14s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002.002zM3.022 13h9.956a.274.274 0 0 0 .014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 0 0 .022.004zm9.974.056v-.002.002zM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                </svg>
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../logout.php">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="../sign-in.php" class="user-profile ml-2">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M13 14s1 0 1-1-1-4-6-4-6 3-6 4 1 1 1 1h10zm-9.995-.944v-.002.002zM3.022 13h9.956a.274.274 0 0 0 .014-.002l.008-.002c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664a1.05 1.05 0 0 0 .022.004zm9.974.056v-.002.002zM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            </svg>
                        </a>
                    <?php endif; ?>

                </div>

                <a href="#" class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none"
                    data-toggle="collapse" data-target="#main-navbar">
                    <span></span>
                </a>

            </div>
        </div>
    </div>
</nav>