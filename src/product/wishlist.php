<?php

require_once __DIR__ . '/../..//helpers/product_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

$userId = $_SESSION['user_id'];
$wishlistItems = getWishlistItems($userId);

// Handle remove from wishlist
if (isset($_POST['remove_from_wishlist'])) {
    $wishlistId = $_POST['wishlist_id'];
    if (removeFromWishlist($wishlistId)) {
        $successMessage = "Product removed from wishlist successfully!";
        $wishlistItems = getWishlistItems($userId); // Refresh the list
    } else {
        $errorMessage = "Failed to remove product from wishlist.";
    }
}

$pageTitle = "My Wishlist | Warung Iyan";

ob_start();
?>

<div class="container mt-5">
    <?php if (isset($successMessage)): ?>
    <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <?php if (empty($wishlistItems)): ?>
    <p>Your wishlist is empty.</p>
    <?php else: ?>
    <div class="row">
        <?php foreach ($wishlistItems as $item): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="card-img-top"
                    alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                    <p class="card-text"><?php echo formatToRupiah($item['price']); ?></p>
                    <a href="detail.php?slug=<?php echo htmlspecialchars($item['slug']); ?>"
                        class="btn btn-primary">View Product</a>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="remove_from_wishlist" value="1">
                        <input type="hidden" name="wishlist_id" value="<?php echo $item['wishlist_id']; ?>">
                        <button type="submit" class="btn btn-danger">Remove</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <a href="checkout.php?from=wishlist" class="btn btn-success">Checkout from Wishlist</a>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include('../../layouts/product/layout.php');
?>