<?php
// src/sign-up.php
require_once __DIR__ . '/../helpers/user_crud.php';

$success = false;
$error = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $errorMessage = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = true;
        $errorMessage = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $error = true;
        $errorMessage = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $error = true;
        $errorMessage = "Password must contain at least one number.";
    } else {
        // Call the function to register the user
        if (register_user($username, $email, $password, false)) {
            $success = true;
        } else {
            $error = true;
            $errorMessage = "Could not register user.";
        }
    }
}

$pageTitle = "Sign Up | Warung Iyan";
ob_start();
?>

<style>
.info-icon {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.info-icon .tooltip-text {
    visibility: hidden;
    width: 200px;
    background-color: #000;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    /* Position the tooltip above the icon */
    left: 50%;
    margin-left: -100px;
    /* Center the tooltip */
    opacity: 0;
    transition: opacity 0.3s;
}

.info-icon:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

.toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1055;
}

.loading-bar {
    position: relative;
    height: 4px;
    background-color: #f1f1f1;
    animation: load 5s linear forwards;
    margin-top: 10px;
    border-radius: 2px;
}

@keyframes load {
    from {
        width: 0;
    }

    to {
        width: 100%;
    }
}

.toast.toast-error .loading-bar {
    background-color: #dc3545;
}
</style>


<div class="card mb-3">
    <div class="card-body">
        <div class="pt-4 pb-2">
            <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
            <p class="text-center small">Enter your personal details to create account</p>
        </div>

        <form class="row g-3 needs-validation" action="sign-up.php" method="POST" novalidate>
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
                <input type="email" class="form-control" name="email" placeholder="Your Email Address" id="yourEmail"
                    required pattern="(?=.*\d)(?=.*[A-Z]).{8,}"
                    title="Must contain at least one number and one uppercase letter, and at least 8 or more characters">
                <div class="invalid-feedback">Please enter a valid Email adddress!</div>
            </div>
            <div class="col-12">
                <label for="yourPassword" class="form-label">Password</label>
                <div class="info-icon d-flex-inline ml-1">
                    <i class="bi bi-info-circle"></i>
                    <div class="tooltip-text">Password must be at least 8 characters long, include at least one
                        uppercase
                        letter, and contain at least one number.</div>
                </div>
                <input type="password" class="form-control" name="password" placeholder="Password" id="yourPassword"
                    required>
                <div class="invalid-feedback">Please enter your password!</div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100">Create Account</button>
            </div>
            <div class="col-12">
                <p class="small mb-0">Already have an account? <a href="sign-in.php" class="text-dark fw-bold">Sign
                        In</a></p>
            </div>
        </form>
    </div>
</div>

<div class="toast-container">
    <?php if ($success): ?>
    <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Account registered successfully. You will be redirected to the sign in page shortly..
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
        <div class="loading-bar"></div>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Error: <?php echo htmlspecialchars($errorMessage); ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
        <div class="loading-bar"></div>
    </div>
    <?php endif; ?>
</div>

<script src="../resources/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
<?php if ($success || $error): ?>
var toastEl = document.querySelector('.toast');
var toast = new bootstrap.Toast(toastEl, {
    delay: 5000
});
toast.show();

<?php if ($success): ?>
// Redirect to sign-in page after 5 seconds when success
setTimeout(function() {
    window.location.href = 'sign-in.php';
}, 5000);
<?php endif; ?>
<?php endif; ?>

// Add client-side validation for password
document.getElementById('yourPassword').addEventListener('input', function(event) {
    const password = event.target.value;
    const isValid = password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password);

    if (isValid) {
        event.target.setCustomValidity('');
    } else {
        event.target.setCustomValidity(
            'Password must be at least 8 characters long, contain at least one uppercase letter and one number.'
        );
    }
});
</script>

<?php $content = ob_get_clean();
include('../layouts/auth/layout.php'); ?>