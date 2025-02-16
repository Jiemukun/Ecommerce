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

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $stmt = $db->prepare("DELETE FROM orders WHERE order_id=?");
    $stmt->bind_param('i', $order_id);
    if($stmt->execute()){
        header('location: index.php?deleted_successfully=Product has been deleted successfully');
    }else{
        header('location: index.php?deleted_error=Could not delete product');
    }
}
?>
