<?php
  $page_title = 'Admin';
require_once('../includes/load.php');  
?>
<?php include_once('../admin_layouts/admin_header.php'); 
?>

<?php 
if (!isset($_SESSION['admin_logged_in'])){
  header('location: login.php');
  exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $stmt = $db->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->bind_param('i', $product_id);
    if($stmt->execute()){
        header('location: products.php?deleted_successfully=Product has been deleted successfully');
    }else{
        header('location: products.php?deleted_error=Could not delete product');
    }
}
?>
