<?php
// src/admin/products.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    // Redirect to login page if not an admin
    header("Location: ../sign-in-admin.php");
    exit;
}

require_once __DIR__ . '/../../helpers/product_crud.php';
require_once __DIR__ . '/../../lib/formatCurrency.php';

// Ambil semua produk
$availableProducts = getAvailableProducts();
$unavailableProducts = getUnavailableProducts();

$pageTitle = "Daftar Produk";

ob_start();
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Produk Tersedia</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($availableProducts as $product): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($product['name']); ?></th>
                    <td><?php echo htmlspecialchars($product['slug']); ?></td>
                    <td><?php echo formatToRupiah($product['price']); ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <a href="product/update.php?id=<?php echo $product['id']; ?>"><i
                                class="text-primary bi bi-pencil-square"></i></a> |
                        <a href="product/delete.php?id=<?php echo $product['id']; ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"><i
                                class="text-danger bi bi-trash3-fill"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Produk Tidak Tersedia</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unavailableProducts as $product): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($product['name']); ?></th>
                    <td><?php echo htmlspecialchars($product['slug']); ?></td>
                    <td><?php echo formatToRupiah($product['price']); ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <a href="product/update.php?id=<?php echo $product['id']; ?>"><i
                                class="text-primary bi bi-pencil-square"></i></a> |
                        <a href="product/delete.php?id=<?php echo $product['id']; ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"><i
                                class="text-danger bi bi-trash3-fill"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();

// Sertakan layout utama
include('../../layouts/admin/layout.php');
?>