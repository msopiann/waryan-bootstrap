<?php

require_once __DIR__ . '/../helpers/product_crud.php';
require_once __DIR__ . '/../lib/formatCurrency.php';

$products = getProducts(3);
$productsCarousel = getProducts(10);

ob_start();
?>

<div class="owl-carousel owl-single home-slider">
    <div class="item">
        <div class="waryan_co-hero"
            style="background-image: url('../resources/frontpage/images/hero-slider-1-min.jpg');">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">

                        <h1 class="mb-4 heading" data-aos="fade-up" data-aos-delay="100">Mau Apa? Semua Ada
                            di <a href="index.php">Warung Iyan</a>!</h1>
                        <div class="mb-5 text-white desc mx-auto" data-aos="fade-up" data-aos-delay="200">
                        </div>

                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300"><a href="catalog.php"
                                class="btn btn-outline-black">Cari Sekarang</a></p>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="item">
        <div class="waryan_co-hero"
            style="background-image: url('../resources/frontpage/images/hero-slider-2-min.jpg');">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">

                        <h1 class="mb-4 heading" data-aos="fade-up" data-aos-delay="100">Mau Apa? Semua Ada di <a
                                href="index.php">Warung Iyan</a>!</h1>
                        <div class="mb-5 text-white desc mx-auto" data-aos="fade-up" data-aos-delay="200">
                        </div>
                        <p class="mb-0" data-aos="fade-up" data-aos-delay="300"><a href="catalog.php"
                                class="btn btn-outline-black">Cari Sekarang</a></p>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="waryan_co-section">
    <div class="container">
        <div class="deal-hero overlay"
            style="background-image: url('../resources/frontpage/images/hero-slider-4-min.jpg')">
            <div class="deal-contents">
                <span class="subtitle">Diskon Hingga 70%</span>
                <h2 class="title mb-4"><a href="#">Promo Gila-Gilaan</a></h2>
                <p class="mb-5">Mau Apa? Semua Pokoknya Ada! Tunggu Apalagi?</p>
                <a href="catalog.php" class="btn btn-black">Belanja Sekarang</a>
            </div>
        </div>
    </div>
</div>

<div class="waryan_co-section">
    <div class="container">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-sm-6 col-md-6 mb-4 col-lg-4">
                    <div class="product-item">
                        <a href="product/detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>"
                            class="product-img">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
                        </a>
                        <h3 class="title"><a
                                href="product/detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        <div class="price mt-2 font-weight-bold">
                            <span><?php echo formatToRupiah($product['price']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="waryan_co-section">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <h2 class="h3">Popular Items</h2>
            </div>
            <div class="col-sm-6 carousel-nav text-sm-right">
                <a href="#" class="prev js-custom-prev-v2">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle"
                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path fill-rule="evenodd"
                            d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z" />
                        <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z" />
                    </svg>
                </a>
                <a href="#" class="next js-custom-next-v2">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-circle"
                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path fill-rule="evenodd"
                            d="M7.646 11.354a.5.5 0 0 1 0-.708L10.293 8 7.646 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z" />
                        <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z" />
                    </svg>
                </a>
            </div>
        </div> <!-- /.heading -->
        <div class="owl-3-slider owl-carousel">
            <?php foreach ($productsCarousel as $product): ?>
                <div class="item">
                    <div class="product-item">
                        <a href="product/detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>"
                            class="product-img">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
                        </a>
                        <h3 class="title"><a
                                href="product/detail.php?slug=<?php echo htmlspecialchars($product['slug']); ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                        </h3>
                        <div class="price">
                            <span><?php echo formatToRupiah($product['price']); ?></span>
                        </div>
                    </div>
                </div> <!-- /.item -->
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="waryan_co-section bg-light">
    <div class="container">
        <div class="row align-items-stretch">
            <div class="col-12 col-sm-6 col-md-4 mb-3 mb-md-0">
                <div class="feature h-100">
                    <div class="icon mb-4">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-truck" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5v7h-1v-7a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5v1A1.5 1.5 0 0 1 0 10.5v-7zM4.5 11h6v1h-6v-1z" />
                            <path fill-rule="evenodd"
                                d="M11 5h2.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5h-1v-1h1a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4.5h-1V5zm-8 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 1a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                            <path fill-rule="evenodd"
                                d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 1a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                        </svg>
                    </div>
                    <h3>Worldwide Delivery</h3>
                    <p>Far far away, behind the word mountains, far from the countries.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3 mb-md-0">
                <div class="feature h-100">
                    <div class="icon mb-4">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-shield-lock" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.443 1.991a60.17 60.17 0 0 0-2.725.802.454.454 0 0 0-.315.366C1.87 7.056 3.1 9.9 4.567 11.773c.736.94 1.533 1.636 2.197 2.093.333.228.626.394.857.5.116.053.21.089.282.11A.73.73 0 0 0 8 14.5c.007-.001.038-.005.097-.023.072-.022.166-.058.282-.111.23-.106.525-.272.857-.5a10.197 10.197 0 0 0 2.197-2.093C12.9 9.9 14.13 7.056 13.597 3.159a.454.454 0 0 0-.315-.366c-.626-.2-1.682-.526-2.725-.802C9.491 1.71 8.51 1.5 8 1.5c-.51 0-1.49.21-2.557.491zm-.256-.966C6.23.749 7.337.5 8 .5c.662 0 1.77.249 2.813.525a61.09 61.09 0 0 1 2.772.815c.528.168.926.623 1.003 1.184.573 4.197-.756 7.307-2.367 9.365a11.191 11.191 0 0 1-2.418 2.3 6.942 6.942 0 0 1-1.007.586c-.27.124-.558.225-.796.225s-.526-.101-.796-.225a6.908 6.908 0 0 1-1.007-.586 11.192 11.192 0 0 1-2.417-2.3C2.167 10.331.839 7.221 1.412 3.024A1.454 1.454 0 0 1 2.415 1.84a61.11 61.11 0 0 1 2.772-.815z" />
                            <path d="M9.5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                            <path
                                d="M7.411 8.034a.5.5 0 0 1 .493-.417h.156a.5.5 0 0 1 .492.414l.347 2a.5.5 0 0 1-.493.585h-.835a.5.5 0 0 1-.493-.582l.333-2z" />
                        </svg>
                    </div>
                    <h3>Secure Payments</h3>
                    <p>Far far away, behind the word mountains, far from the countries.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3 mb-md-0">
                <div class="feature h-100">
                    <div class="icon mb-4">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M12.83 6.706a5 5 0 0 0-7.103-3.16.5.5 0 1 1-.454-.892A6 6 0 1 1 2.545 5.5a.5.5 0 1 1 .91.417 5 5 0 1 0 9.375.789z" />
                            <path fill-rule="evenodd"
                                d="M7.854.146a.5.5 0 0 0-.708 0l-2.5 2.5a.5.5 0 0 0 0 .708l2.5 2.5a.5.5 0 1 0 .708-.708L5.707 3 7.854.854a.5.5 0 0 0 0-.708z" />
                        </svg>
                    </div>
                    <h3>Simple Returns</h3>
                    <p>Far far away, behind the word mountains, far from the countries.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean();
include('../layouts/frontpage/layout.php'); ?>