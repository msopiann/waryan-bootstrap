<?php

require_once __DIR__ . '/../helpers/product_crud.php';
require_once __DIR__ . '/../lib/formatCurrency.php';

$products = getProducts(100);

ob_start();
?>


<div class="page-heading bg-light">
    <div class="container">
        <div class="row align-items-end text-center">
            <div class="col-lg-7 mx-auto">
                <h1>Shop</h1>
                <p class="mb-4"><a href="../src/index.php">Home</a> / <strong>Shop</strong></p>
            </div>
        </div>
    </div>
</div>

<div class="waryan_co-section pt-3">
    <div class="container">

        <div class="row align-items-center mb-5">
            <div class="col-lg-8">
                <h2 class="mb-3 mb-lg-0">Products</h2>
            </div>
            <div class="col-lg-4">

                <div class="d-flex sort align-items-center justify-content-lg-end">
                    <strong class="mr-3">Sort by:</strong>
                    <form action="#">
                        <select class="bg-transparent" required>
                            <option value="">Newest Items</option>
                            <option value="1">Best Selling</option>
                            <option value="2">Price: Ascending</option>
                            <option value="2">Price: Descending</option>
                            <option value="3">Rating(High to Low)</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
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
                                <div class="price">
                                    <span><?php echo formatToRupiah($product['price']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean();
include('../layouts/frontpage/layout.php'); ?>