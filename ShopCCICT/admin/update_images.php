<?php
$page_title = 'Admin';
require_once('../includes/load.php');
?>

<?php include_once('../admin_layouts/admin_header.php'); ?>

<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
//
//include_once('session.php');
//

if (isset($_POST['product_id']) && isset($_POST['product_name'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
} else {
    header('location: products.php');
    exit;
}


$allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
$upload_dir = "../lib/img/";

function generate_filename($product_name, $index) {
    
    $product_name_clean = preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($product_name));
    $timestamp = time(); 
    return $product_name_clean . '-' . $timestamp . '-' . $index;
}

function upload_image($image, $product_name, $index, $allowed_types, $upload_dir) {
    if (in_array($image['type'], $allowed_types)) {
        
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

        
        $unique_name = generate_filename($product_name, $index);
        $image_name = $unique_name . '.' . $ext;

        
        if (move_uploaded_file($image['tmp_name'], $upload_dir . $image_name)) {
            return $image_name; 
        } else {
            return false; 
        }
    } else {
        return false; 
    }
}



$stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('location: products.php?error=Product not found.');
    exit;
}


$image_name1 = $product['product_image'];
$image_name2 = $product['product_image2'];
$image_name3 = $product['product_image3'];
$image_name4 = $product['product_image4'];


if ($_FILES['image1']['error'] === UPLOAD_ERR_OK) {
    $image_name1 = upload_image($_FILES['image1'], $product_name, 1, $allowed_types, $upload_dir);
}
if ($_FILES['image2']['error'] === UPLOAD_ERR_OK) {
    $image_name2 = upload_image($_FILES['image2'], $product_name, 2, $allowed_types, $upload_dir);
}
if ($_FILES['image3']['error'] === UPLOAD_ERR_OK) {
    $image_name3 = upload_image($_FILES['image3'], $product_name, 3, $allowed_types, $upload_dir);
}
if ($_FILES['image4']['error'] === UPLOAD_ERR_OK) {
    $image_name4 = upload_image($_FILES['image4'], $product_name, 4, $allowed_types, $upload_dir);
}


$stmt = $db->prepare("UPDATE products SET 
                        product_image = ?, 
                        product_image2 = ?, 
                        product_image3 = ?, 
                        product_image4 = ? 
                        WHERE product_id = ?");

$stmt->bind_param('ssssi', $image_name1, $image_name2, $image_name3, $image_name4, $product_id);

if ($stmt->execute()) {
    header('location: products.php?product_updated=Product images updated successfully');
} else {
    header('location: edit_images.php?product_id=' . $product_id . '&product_name=' . urlencode($product_name) . '&error=Error occurred while updating images.');
}
?>
