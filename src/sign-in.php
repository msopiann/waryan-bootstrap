<?php
require_once '../helpers/user_crud.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Attempt to log the user in
    if (login_user($username, $password)) {
        // Redirect to index.php
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
$pageTitle = "Sign In | Warung Iyan";
ob_start();
?>

<div class="card mb-3">
    <div class="card-body">
        <div class="pt-4 pb-2">
            <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
            <p class="text-center small">Enter your username & password to login</p>
        </div>

        <form class="row g-3 needs-validation" action="sign-in.php" method="POST" novalidate>
            <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>

            <div class="col-12">
                <label for="yourUsername" class="form-label">Username</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" name="username" class="form-control" id="yourUsername" required>
                    <div class="invalid-feedback">Please enter your username.</div>
                </div>
            </div>

            <div class="col-12">
                <label for="yourPassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" id="yourPassword"
                    required>
                <div class="invalid-feedback">Please enter your password!</div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary px-5 w-100">Login</button>
            </div>

            <div class="col-12">
                <p class="small mb-0">Don't have account? <a href="sign-up.php" class="text-dark fw-bold">Create an
                        account</a></p>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean();
include('../layouts/auth/layout.php'); ?>