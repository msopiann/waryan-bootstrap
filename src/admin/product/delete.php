<?php
// src\admin\product\delete.php

session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirect ke halaman lain jika bukan admin
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../../helpers/product_crud.php';

$id = $_GET['id'] ?? '';

// Periksa apakah ID valid
if (empty($id)) {
    // Tampilkan pesan kesalahan jika ID kosong
    echo "ID produk tidak valid.";
    exit;
}

// Fungsi hapus produk
if (deleteProduct($id)) {
    echo "Produk berhasil dihapus.";
} else {
    echo "Gagal menghapus produk.";
}

// Redirect kembali ke halaman produk setelah penghapusan
header('Location: ../products.php');
exit;
