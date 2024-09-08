<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirect ke halaman lain jika bukan admin
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../../helpers/product_crud.php';
require_once __DIR__ . '/../../../helpers/slug_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $slug = generateSlug($name);
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];

    if (createProduct($name, $slug, $description, $price, $stock, $image_url)) {
        echo "Produk berhasil ditambahkan.";
        header('Location: ../products.php');
        exit;
    } else {
        echo "Gagal menambahkan produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../../../resources/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <h1>Tambah Produk</h1>
    <form action="add.php" method="post">
        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Harga:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="stock">Stok:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="image_url">URL Gambar:</label>
        <input type="text" id="image_url" name="image_url">

        <button type="submit">Tambah Produk</button>
    </form>
    <a href="../products.php">Kembali ke Daftar Produk</a>
</body>

</html>