<?php
// helpers/product_crud.php

require_once __DIR__ . '/../config/sql_connection.php';
require_once __DIR__ . '/./slug_helper.php';

// Create (Insert) function
function createProduct($name, $slug, $description, $price, $stock, $image_url)
{
    global $conn;
    $sql = "INSERT INTO products (id, name, slug, description, price, stock, image_url) VALUES (uuid_v4_baru(), ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdis", $name, $slug, $description, $price, $stock, $image_url);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Read (Select) function
function getProducts($limit = 10)
{
    global $conn;
    $sql = "SELECT * FROM products LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAvailableProducts($limit = 10)
{
    global $conn;
    $sql = "SELECT * FROM products WHERE stock >= 1 LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getUnavailableProducts($limit = 10)
{
    global $conn;
    $sql = "SELECT * FROM products WHERE stock = 0 LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Read (Select) single product
function getProductBySlug($slug)
{
    global $conn;
    $sql = "SELECT p.*, 
               COALESCE(AVG(r.rating), 0) AS average_rating
        FROM products p
        LEFT JOIN reviews r ON p.id = r.product_id
        WHERE p.slug = ?
        GROUP BY p.id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Update function
function updateProduct($id, $name, $description, $price, $stock, $image_url)
{
    global $conn;
    $slug = generateSlug($name);
    $sql = "UPDATE products SET name = ?, slug = ?, description = ?, price = ?, stock = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdiss", $name, $slug, $description, $price, $stock, $image_url, $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Delete function
function deleteProduct($id)
{
    global $conn;
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Untuk Monitoring Penjualan
function getDateRange($period)
{
    $end_date = date('Y-m-d H:i:s');
    switch ($period) {
        case 'day':
            $start_date = date('Y-m-d H:i:s', strtotime('-1 day'));
            break;
        case 'week':
            $start_date = date('Y-m-d H:i:s', strtotime('-1 week'));
            break;
        case 'month':
            $start_date = date('Y-m-d H:i:s', strtotime('-1 month'));
            break;
        default:
            throw new Exception("Invalid period specified");
    }
    return [$start_date, $end_date];
}

function countSales($period)
{
    global $conn;
    list($start_date, $end_date) = getDateRange($period);

    $sql = "SELECT COUNT(*) as sales_count FROM orders 
            WHERE created_at BETWEEN ? AND ? AND status = 'completed'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['sales_count'];
}

function calculateRevenue($period)
{
    global $conn;
    list($start_date, $end_date) = getDateRange($period);

    $sql = "SELECT SUM(total_price) as total_revenue FROM orders 
            WHERE created_at BETWEEN ? AND ? AND status = 'completed'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_revenue'] ?? 0;
}

function getRecentSales($period, $limit = 10)
{
    global $conn;
    list($start_date, $end_date) = getDateRange($period);

    $sql = "SELECT o.id, o.user_id, o.total_price, o.created_at, 
            u.email, GROUP_CONCAT(p.name SEPARATOR ', ') as products
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.created_at BETWEEN ? AND ? AND o.status = 'completed'
            GROUP BY o.id
            ORDER BY o.created_at DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $start_date, $end_date, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $recent_sales = [];
    while ($row = $result->fetch_assoc()) {
        $recent_sales[] = $row;
    }

    return $recent_sales;
}

function getTopSalesProducts($period, $limit = 5)
{
    global $conn;

    list($start_date, $end_date) = getDateRange($period);

    $sql = "SELECT 
                p.id, 
                p.name, 
                p.price,
                p.image_url, 
                SUM(oi.quantity) as total_sold,
                SUM(oi.quantity * oi.price) as total_revenue
            FROM 
                products p
            JOIN 
                order_items oi ON p.id = oi.product_id
            JOIN 
                orders o ON oi.order_id = o.id
            WHERE 
                o.created_at BETWEEN ? AND ?
                AND o.status = 'completed'
            GROUP BY 
                p.id
            ORDER BY 
                total_sold DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $start_date, $end_date, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $topProducts = [];
    while ($row = $result->fetch_assoc()) {
        $topProducts[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image_url' => $row['image_url'],
            'total_sold' => $row['total_sold'],
            'total_revenue' => $row['total_revenue']
        ];
    }

    return $topProducts;
}
