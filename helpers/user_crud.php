<?php
// helpers/user_crud.php

require_once __DIR__ . '/../config/sql_connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Register
function register_user($username, $email, $password, $is_admin = false)
{
    global $conn;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Generate a unique ID
    $userId = bin2hex(random_bytes(16));

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL statement using prepared statements
    $stmt = $conn->prepare("INSERT INTO users (id, username, email, password, is_admin) VALUES (UUID(), ?, ?, ?, ?)");

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $is_admin);

        // Execute the statement
        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            return false; // Registration failed
        }
    } else {
        return false; // Could not prepare statement
    }
}

// Login
function login_user($username, $password)
{
    global $conn;

    // Prepare the SQL query to fetch the user's info based on username
    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");

    if ($stmt) {
        // Bind the username parameter
        $stmt->bind_param("s", $username);

        // Execute the query
        $stmt->execute();

        // Store result
        $stmt->store_result();

        // Check if a user with that username exists
        if ($stmt->num_rows > 0) {
            // Bind the result to variables
            $stmt->bind_result($userId, $hashedPassword, $isAdmin);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;

                return [
                    'user_id' => $userId,
                    'is_admin' => $isAdmin
                ]; // Login successful
            } else {
                return false; // Incorrect password
            }
        } else {
            return false; // No such user found
        }
    } else {
        return false; // Could not prepare statement
    }
}

// Fetch data admin
function getAdminUsers($limit = 10)
{
    global $conn;

    $sql = "SELECT id, username, email, is_admin, created_at 
            FROM users 
            WHERE is_admin = TRUE 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();

    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $users;
}

// Fetch data user
function getNonAdminUsers($limit = 10)
{
    global $conn;

    $sql = "SELECT id, username, email, is_admin, created_at 
            FROM users 
            WHERE is_admin = FALSE 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();

    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $users;
}
function countNonAdminUsers()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM users WHERE is_admin = FALSE";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}
