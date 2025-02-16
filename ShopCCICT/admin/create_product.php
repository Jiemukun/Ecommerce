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
if (isset($_POST['create_product'])) {
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_category = $_POST['category']; 
    $product_color = $_POST['color'];
    $stock_quantity = $_POST['stock_quantity'];  // 
    $reorder_level = $_POST['reorder_level'];

    
    $sql = "SELECT category_name FROM categories WHERE category_id = ?"; 
    $stmt = $db->prepare($sql); 
    $stmt->bind_param('i', $product_category); 
    $stmt->execute(); 
    $stmt->bind_result($product_category_name); 
    $stmt->fetch(); 
    $stmt->close(); 

    //
    $image1 = $_FILES['image1'];
    $image2 = $_FILES['image2'];
    $image3 = $_FILES['image3'];
    $image4 = $_FILES['image4'];

    // (JPG, PNG, JPEG)
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

    $image_name1 = upload_image($image1, $product_name, 1, $allowed_types, $upload_dir);
    $image_name2 = upload_image($image2, $product_name, 2, $allowed_types, $upload_dir);
    $image_name3 = upload_image($image3, $product_name, 3, $allowed_types, $upload_dir);
    $image_name4 = upload_image($image4, $product_name, 4, $allowed_types, $upload_dir);

    if ($image_name1 && $image_name2 && $image_name3 && $image_name4) {
        
        
        $stmt = $db->prepare("INSERT INTO products (product_name, product_description, product_price,
                                                    product_image, product_image2, product_image3, product_image4,
                                                    product_category, product_color, stock_quantity, reorder_level)
                                                        VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                                                        
        $stmt->bind_param('sssssssssii', $product_name, $product_description, $product_price,
        $image_name1, $image_name2, $image_name3, $image_name4, $product_category_name, $product_color, $stock_quantity, $reorder_level); // use category_name here

        if ($stmt->execute()) {
            header('location: products.php?product_created=Product has been created successfully');
        } else {
            header('location: products.php?product_error=Error occurred, please try again.');
        }
    } else {
        
        header('location: products.php?product_error=Error uploading images, please check the file types.');
    }
}
?>

<?php include_once('../admin_layouts/admin_footer.php'); ?>
