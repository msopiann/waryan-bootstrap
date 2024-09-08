<?php
require_once __DIR__ . '/../../helpers/product_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

$userId = $_SESSION['user_id'];
$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if (!$orderId) {
    header("Location: index.php");
    exit();
}

$order = getOrderDetails($orderId, $userId);

if (!$order) {
    header("Location: index.php");
    exit();
}

$pageTitle = "Order Confirmation | Warung Iyan";

ob_start();
?>

<div class="container mt-5">
    <h1>Order Confirmation</h1>
    <div class="alert alert-success">
        Your order has been successfully placed!
    </div>

    <h2>Order Details</h2>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
    <p><strong>Total:</strong> <?php echo formatToRupiah($order['total_price']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($order['status'])); ?></p>

    <h3>Shipping Information</h3>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['shipping_name']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
    <p><strong>City:</strong> <?php echo htmlspecialchars($order['shipping_city']); ?></p>
    <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($order['shipping_postal_code']); ?></p>

    <h3>Order Items</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo formatToRupiah($item['price']); ?></td>
                    <td><?php echo formatToRupiah($item['price'] * $item['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
</div>

<?php
$content = ob_get_clean();
include('../../layouts/product/layout.php');

function getOrderDetails($orderId, $userId)
{
    global $conn;

    $sql = "SELECT o.*, oi.product_id, oi.quantity, oi.price, p.name
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.id = ? AND o.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    // Ambil baris pertama untuk data pesanan
    $order = $result->fetch_assoc();
    $order['items'] = [];

    // Ulangi result set dari awal
    $result->data_seek(0);

    // Loop untuk mengambil semua item pesanan
    while ($row = $result->fetch_assoc()) {
        $order['items'][] = [
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        ];
    }

    return $order;
}

?>