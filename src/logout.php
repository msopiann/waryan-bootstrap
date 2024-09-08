<?php
// src/logout.php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to sign-in page
header("Location: sign-in.php");
exit;
