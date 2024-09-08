<?php
require_once __DIR__ . '/../../helpers/product_crud.php';
require_once __DIR__ . '/../../helpers/user_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';
require_once __DIR__ . '/../../config/sql_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Check if coming from wishlist or direct product purchase
$fromWishlist = isset($_GET['from']) && $_GET['from'] === 'wishlist';
$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($fromWishlist) {
    $items = getWishlistItems($userId);
} elseif ($productId) {
    $product = getProductBySlug($productId);
    $items = $product ? [$product] : [];
    $items[0]['quantity'] = $quantity; // Add quantity to the product
} else {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
    $postalCode = htmlspecialchars($_POST['postal_code'], ENT_QUOTES, 'UTF-8');

    if (!$name || !$address || !$city || !$postalCode) {
        $errorMessage = "Please fill in all required fields.";
    } else {
        // Process the order
        $orderId = processOrder($userId, $items, $name, $address, $city, $postalCode);
        if ($orderId) {
            // Clear wishlist if checkout was from wishlist
            if ($fromWishlist) {
                clearWishlist($userId);
            }
            header("Location: order-confirmation.php?order_id=" . $orderId);
            exit();
        } else {
            $errorMessage = "Failed to process the order. Please try again.";
            // Log the detailed error or show it if needed
            $errorMessage .= "<br>Check logs for detailed error information.";
        }
    }
}


$pageTitle = "Checkout | Warung Iyan";

ob_start();
?>

<div class="container mt-5">
    <h1>Checkout</h1>

    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="row">
            <div class="col-md-8">
                <h3>Order Summary</h3>
                <?php foreach ($items as $item): ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                    class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <p class="card-text">Price: <?php echo formatToRupiah($item['price']); ?></p>
                                    <p class="card-text">Quantity: <?php echo $item['quantity'] ?? 1; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <h3>Shipping Information</h3>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="mb-3">
                    <label for="postal_code" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Order Total</h3>
                <p>Total: <?php echo formatToRupiah(calculateTotal($items)); ?></p>
                <button type="submit" class="btn btn-primary">Place Order</button>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include('../../layouts/product/layout.php');

function calculateTotal($items)
{
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * ($item['quantity'] ?? 1);
    }
    return $total;
}

function processOrder($userId, $items, $name, $address, $city, $postalCode)
{
    global $conn;

    try {
        $conn->begin_transaction();

        // Create the order
        $orderId = bin2hex(random_bytes(16));
        $totalPrice = calculateTotal($items);
        $status = 'pending';

        $sql = "INSERT INTO orders (id, user_id, total_price, status, shipping_name, shipping_address, shipping_city, shipping_postal_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement for creating order: " . $conn->error);
        }
        $stmt->bind_param("ssdsssss", $orderId, $userId, $totalPrice, $status, $name, $address, $city, $postalCode);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement for creating order: " . $stmt->error);
        }

        // Create order items
        $sql = "INSERT INTO order_items (id, order_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement for creating order items: " . $conn->error);
        }

        foreach ($items as $item) {
            $orderItemId = bin2hex(random_bytes(16));
            $quantity = $item['quantity'] ?? 1;
            $stmt->bind_param("sssid", $orderItemId, $orderId, $item['id'], $quantity, $item['price']);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute statement for order item: " . $stmt->error);
            }

            // Update product stock
            $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
            $updateStmt = $conn->prepare($sql);
            if (!$updateStmt) {
                throw new Exception("Failed to prepare statement for updating product stock: " . $conn->error);
            }
            $updateStmt->bind_param("is", $quantity, $item['id']);
            if (!$updateStmt->execute()) {
                throw new Exception("Failed to execute statement for updating product stock: " . $updateStmt->error);
            }
        }

        $conn->commit();
        return $orderId;
    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage()); // Log the error for debugging purposes
        return false; // To keep the user-friendly message, we just return false
    }
}


function clearWishlist($userId)
{
    global $conn;
    $sql = "DELETE FROM wishlist WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
}
?>