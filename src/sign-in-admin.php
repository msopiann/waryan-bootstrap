<?php
// src/sign-in-admin.php
require_once '../helpers/user_crud.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Please fill in both fields.";
    } else {
        // Attempt to log the admin in
        $user = login_user($username, $password);

        if ($user && isset($user['is_admin']) && $user['is_admin']) {
            // Store session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $user['is_admin'];

            // Debug: Check if user is successfully logged in
            echo "Login successful!";
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error_message = "Invalid credentials or not an admin.";
        }
    }
}
$pageTitle = "Admin Sign In | Warung Iyan";
ob_start();
?>

<div class="card mb-3">
    <div class="card-body">
        <div class="pt-4 pb-2">
            <h5 class="card-title text-center pb-0 fs-4">Login to Your Admin Account</h5>
            <p class="text-center small">Enter your username & password to login</p>
        </div>

        <form class="row g-3 needs-validation" action="sign-in-admin.php" method="POST" novalidate>
            <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>

            <div class="col-12">
                <label for="yourUsername" class="form-label">Username</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" name="username" class="form-control" required>
                    <div class="invalid-feedback">Please enter your username.</div>
                </div>
            </div>

            <div class="col-12">
                <label for="yourPassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <div class="invalid-feedback">Please enter your password!</div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary px-5 w-100">Login</button>
            </div>

            <div class="col-12">
                <p class="small mb-0">Don't have account? <a href="sign-up-admin.php" class="text-dark fw-bold">Create
                        an
                        account</a></p>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean();
include('../layouts/auth/layout.php'); ?>