<?php
// src/sign-up-admin.php
require_once '../helpers/user_crud.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (register_user($username, $email, $password, $is_admin)) {
        // Redirect to admin dashboard if admin
        if ($is_admin) {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error_message = "Registration failed. Please try again.";
    }
}

$pageTitle = "Admin Sign Up | Warung Iyan";
ob_start();
?>

<div class="card mb-3">
    <div class="card-body">
        <div class="pt-4 pb-2">
            <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
            <p class="text-center small">Enter your personal details to create account</p>
        </div>

        <form class="row g-3 needs-validation" action="sign-up-admin.php" method="POST" novalidate>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>
            <div class="col-12">
                <label for="yourUsername" class="form-label">Username</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" name="username" placeholder="User Name" id="yourUsername"
                        required>
                    <div class="invalid-feedback">Please choose a username.</div>
                </div>

            </div>
            <div class="col-12">
                <label for="yourEmail" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Your Email Address" id="yourEmail"
                    required>
                <div class="invalid-feedback">Please enter a valid Email adddress!</div>
            </div>
            <div class="col-12">
                <label for="yourPassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" id="yourPassword"
                    required>
                <div class="invalid-feedback">Please enter your password!</div>

            </div>
            <div class="col-12">
                <input type="checkbox" name="is_admin" value="1"> Register as Admin
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">Create Admin Account</button>
            </div>
            <div class="col-12">
                <p class="small mb-0">Already have an Admin account? <a href="sign-in-admin.php"
                        class="text-dark fw-bold">Sign
                        In</a></p>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean();
include('../layouts/auth/layout.php'); ?>