<?php
// src/admin/user.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    // Redirect to login page if not an admin
    header("Location: ../sign-in-admin.php");
    exit;
}

require_once __DIR__ . '/../../helpers/user_crud.php';

// Ambil semua produk
$adminUsers  = getAdminUsers(10);

$nonAdminUsers  = getNonAdminUsers(10);

$pageTitle = "Daftar Akun";

ob_start();
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Daftar Admin</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Dibuat pada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adminUsers  as $user): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($user['username']); ?></th>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo date('d-m-Y H:i:s', strtotime($user['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Daftar Pelanggan</h5>
        <?php echo "Current System date: " . date('Y-m-d H:i:s') ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Dibuat pada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nonAdminUsers  as $user): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($user['username']); ?></th>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo date('d-m-Y H:i:s', strtotime($user['created_at'])); ?></td>
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