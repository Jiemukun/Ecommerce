<?php
session_start(); 

$page_title = 'Place order';
require_once('includes/load.php');
//include_once('sessionz.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    
    header('Location: login.php?error=You must be logged in to place an order');
    exit; 
}


if (isset($_POST['place_order'])) {
    
    if (!isset($_SESSION['total']) || !isset($_SESSION['user_id']) || !isset($_SESSION['cart'])) {
        
        die("Session variables are not set correctly.");
    }

    
    $name = $_POST['name'];
    $email = $_POST['email']; 
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $order_cost = $_SESSION['total']; 
    $order_status = "unpaid";
    $user_id = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');
    
    
    $stmt = $db->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");

    
    $db->bind_param($stmt, 'dssssss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

    
    $db->execute($stmt);

    
    $order_id = $db->insert_id();

    
    if ($db->affected_rows() > 0) {
        echo "Order placed successfully!";
    } else {
        echo "Error placing the order: " . $db->error;
    }

    
    foreach ($_SESSION['cart'] as $key => $value) {
        $product = $_SESSION['cart'][$key];
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_image = $product['product_image'];
        $product_price = $product['product_price'];
        $product_quantity = $product['product_quantity'];

        
        $stmt1 = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        
        $db->bind_param($stmt1, 'iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);

        
        $db->execute($stmt1);
    }

    
    header('location: account.php?order_status=Order placed successfully!');
    
    $stmt->close();
    $stmt1->close();
}
?>
