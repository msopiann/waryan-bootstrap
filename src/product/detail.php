<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../helpers/product_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug) {
    $product = getProductBySlug($slug);

    if (!$product) {
        echo "Produk tidak ditemukan.";
        exit;
    }

    $storeName = 'Warung Iyan';
    $pageTitle = htmlspecialchars($product['name']) . ' | ' . $storeName;
} else {
    echo "Slug produk tidak ditentukan.";
    exit;
}

if (isset($_POST['add_to_wishlist'])) {
    // Check if the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        // Redirect to sign-in page if not authenticated
        header('Location: ../sign-in.php');
        exit;
    }

    $userId = $_SESSION['user_id'];
    $productId = $product['id'];
    if (addToWishlist($userId, $productId)) {
        $successMessage = "Product added to wishlist successfully!";
    } else {
        $errorMessage = "Failed to add product to wishlist.";
    }
}

ob_start();
?>
<section class="pt-5">
    <div class="container">
        <div class="row gx-5">
            <aside class="col-lg-6">
                <div class="border rounded-4 mb-3 d-flex justify-content-center">
                    <a data-fslightbox="mygalley" class="rounded-4" target="_blank" data-type="image"
                        href="<?php echo htmlspecialchars($product['image_url']); ?>">
                        <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit"
                            src="<?php echo htmlspecialchars($product['image_url']); ?>" />
                    </a>
                </div>
            </aside>
            <main class="col-lg-6">
                <div class="ps-lg-3">
                    <h4 class="title text-dark">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h4>
                    <div class="d-flex flex-row my-3">
                        <div class="text-warning mb-1 me-2">
                            <i class="fa fa-star"></i>
                            <span class="ms-1">
                                <?php echo htmlspecialchars(number_format($product['average_rating'], 1)); ?>
                            </span>
                        </div>
                        <span class="text-muted mx-3"><i
                                class="fas fa-shopping-basket fa-sm mx-1"></i><?php echo htmlspecialchars($product['sold_count']); ?>
                            orders</span>
                        <span class="<?php echo $product['stock'] > 0 ? 'text-success' : 'text-danger'; ?> ml-2">
                            <?php echo $product['stock'] > 0 ? 'In stock' : 'Out of stock'; ?>
                            (<?php echo htmlspecialchars($product['stock']); ?>)
                        </span>

                    </div>

                    <div class="mb-3">
                        <span class="h5"><?php echo formatToRupiah($product['price']); ?></span>
                        <span class="text-muted">/per item</span>
                    </div>

                    <p>
                        <?php echo htmlspecialchars($product['description']); ?>
                    </p>

                    <div class="row mb-4">
                        <div class="col-md-4 col-6 mb-3">
                            <label class="mb-2 d-block">Quantity</label>
                            <div class="input-group mb-3" style="width: 170px;">
                                <button class="btn btn-white border border-secondary px-3" type="button"
                                    id="button-decrease" data-mdb-ripple-color="dark"
                                    <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="text" class="form-control text-center border border-secondary"
                                    id="quantity-input" value="<?php echo $product['stock'] > 0 ? '1' : '0'; ?>"
                                    aria-label="Example text with button addon" aria-describedby="button-addon1" />
                                <button class="btn btn-white border border-secondary px-3" type="button"
                                    id="button-increase" data-mdb-ripple-color="dark"
                                    <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <form action="checkout.php" method="get" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['slug'] ?>">
                        <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                        <button type="submit" class="btn btn-warning shadow-0">
                            Buy Now
                        </button>
                    </form>

                    <form method="post" style="display: inline;">
                        <input type="hidden" name="add_to_wishlist" value="1">
                        <button type="submit" class="btn btn-primary shadow-0">
                            <i class="me-1 fa fa-heart"></i> Add to Wishlist
                        </button>
                    </form>
                    <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                    <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stock = <?php echo $product['stock']; ?>;
    const quantityInput = document.getElementById('quantity-input');
    const buyNowQuantityInput = document.getElementById('buy-now-quantity');
    const increaseButton = document.getElementById('button-increase');
    const decreaseButton = document.getElementById('button-decrease');

    function updateQuanitity(newValue) {
        if (newValue < 1) newValue = 1;
        if (newValue > stock) newValue = stock;
        quantityInput.value = newValue;
        buyNowQuantityInput.value = newValue;
    }

    increaseButton.addEventListener('click', function() {
        updateQuanitity(parseInt(quantityInput.value) + 1);
    });

    decreaseButton.addEventListener('click', function() {
        updateQuanitity(parseInt(quantityInput.value) - 1);
    });

    quantityInput.addEventListener('input', function() {
        updateQuanitity(parseInt(quantityInput.value));
    });
});
</script>

<?php $content = ob_get_clean();
include('../../layouts/product/layout.php'); ?>