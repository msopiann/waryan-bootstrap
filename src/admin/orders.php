<?php
// src/admin/orders.php

require_once __DIR__ . '/../../helpers/product_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';
require_once __DIR__ . '/../../lib/formatDate.php';

session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: /src/sign-in.php');
    exit();
}

// Handle form submission for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    if (updateOrderStatus($orderId, $newStatus)) {
        $message = "Order status updated successfully.";
    } else {
        $error = "Failed to update order status.";
    }
}

// Fetch all orders
$orders = getAllOrders();

$pageTitle = "Daftar Status Pembelian";

ob_start();
?>

<div class="container mt-5">
    <?php if (isset($message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['email']; ?></td>
                    <td><?php echo formatToRupiah($order['total_price']); ?></td>
                    <td><?php
                        $statusClass = '';
                        switch ($order['status']) {
                            case 'completed':
                                $statusClass = 'text-success';
                                break;
                            case 'pending':
                                $statusClass = 'text-warning';
                                break;
                            case 'processing':
                                $statusClass = 'text-secondary';
                                break;
                            case 'shipped':
                                $statusClass = 'text-primary';
                                break;
                            case 'delivered':
                                $statusClass = 'text-info';
                                break;
                            case 'cancelled':
                                $statusClass = 'text-danger';
                                break;
                        }
                        ?>
                        <span class="<?php echo $statusClass; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td><?php echo formatDate($order['created_at']); ?></td>
                    <td>
                        <button type="button" class="btn" data-bs-toggle="modal"
                            data-bs-target="#statusModal<?php echo $order['id']; ?>">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="statusModal<?php echo $order['id']; ?>" tabindex="-1"
                            aria-labelledby="statusModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel<?php echo $order['id']; ?>">Change
                                            Order Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to change the status of this order?</p>
                                        <form action="" method="POST">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <select name="new_status" class="form-select mb-3">
                                                <option value="pending"
                                                    <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending
                                                </option>
                                                <option value="processing"
                                                    <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>
                                                    Processing
                                                </option>
                                                <option value="delivered"
                                                    <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>
                                                    Delivered
                                                </option>
                                                <option value="shipped"
                                                    <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped
                                                </option>
                                                <option value="completed"
                                                    <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>
                                                    Completed</option>
                                                <option value="cancelled"
                                                    <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>
                                                    Cancelled</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();

// Sertakan layout utama
include('../../layouts/admin/layout.php');
?>