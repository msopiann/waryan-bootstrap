<?php
// Mulai sesi dan periksa apakah user adalah admin
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirect ke halaman lain jika bukan admin
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../../../helpers/product_crud.php';
require_once __DIR__ . '/../../../helpers/slug_helper.php';

$id = $_GET['id'] ?? '';

// Ambil detail produk berdasarkan ID
if (empty($id)) {
    echo "ID produk tidak valid.";
    exit;
}

// Query untuk mendapatkan data produk
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $slug = generateSlug($name);
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_url = $_POST['image_url'];

    // Panggil fungsi untuk update produk
    if (updateProduct($id, $name, $description, $price, $stock, $image_url)) {
        echo "Produk berhasil diperbarui.";
        header('Location: ../products.php');
        exit;
    } else {
        echo "Gagal memperbarui produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="../../../resources/bootstrap/css/bootstrap.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        a {
            text-decoration: none;
            outline: none;
            color: #444;
        }

        a:hover {
            color: #444;
        }

        ul {
            margin-bottom: 0;
            padding-left: 0;
        }

        a:hover,
        a:focus,
        input,
        textarea {
            text-decoration: none;
        }


        .form-01-main,
        html,
        body {
            height: 100%;

        }

        .form-01-main {
            background-position: center;
            background-size: 100%;
            background-repeat: no-repeat;
            position: relative;
            text-align: center;
            width: 100%;
            z-index: 1;
        }

        .form-sub-main {
            width: 90%;
            margin: 10px auto;

        }

        @media screen and (max-width:767px) {
            .form-sub-main {
                padding: 30px;
            }
        }

        .form-control {
            min-height: 50px;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 1px solid rgba(0, 0, 0, 0.8);
        }

        .form-sub-main {
            color: #545454;
            font-size: 16px;
            margin-top: 2%;
        }

        .form-group {
            position: relative;
            z-index: 9;
        }


        .form-group .form-control:focus {
            background: transparent;
            box-shadow: none;
            border-color: #495057;
            color: #495057;
        }

        .btn_uy {
            position: relative;
            z-index: 9;
            display: block;
            margin: 20px 0px;
        }

        .btn_uy a {
            padding: 10px 20px;
            background: #37a000;
            text-transform: uppercase;
            text-align: center;
            font-size: 16px;
            font-weight: 400;
            white-space: nowrap;
            line-height: normal;
            border-radius: 5px;
            color: #fff;
            width: 100%;
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="form-01-main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-sub-main">
                        <div>
                            <h1>Edit Produk <?php echo htmlspecialchars($product['name']); ?></h1>
                        </div>

                        <form action="update.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
                            <div class="form-group mb-1">
                                <label class="form-label" for="name">Nama Produk:</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>


                            <div class="form-group mb-1"><label class="form-label" for="description">Deskripsi:</label>
                                <textarea class="form-control" id="description" name="description"
                                    required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>

                            <div class="form-group mb-1"><label class="form-label" for="price">Harga:</label>
                                <input class="form-control" type="number" id="price" name="price"
                                    value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
                            </div>

                            <div class="form-group mb-1">
                                <label class="form-label" for="stock">Stok:</label>
                                <input class="form-control" type="number" id="stock" name="stock"
                                    value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                            </div>

                            <div class="form-group mb-1">
                                <label class="form-label" for="image_url">URL Gambar:</label>
                                <input class="form-control" type="text" id="image_url" name="image_url"
                                    value="<?php echo htmlspecialchars($product['image_url']); ?>">
                            </div>

                            <div class="form-button mt-3">
                                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                            </div>
                        </form>
                        <a href="../products.php">Kembali ke Daftar Produk</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>