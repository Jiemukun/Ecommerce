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

//include_once('session.php');
?>
<?php

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
$stmt = $db->prepare("SELECT * FROM products WHERE product_id=?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$products = $stmt->get_result();
}elseif(isset($_POST['edit_btn'])){
    $product_id = $_POST['product_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $color = $_POST['color'];
    $category = $_POST['category'];
    $stock_quantity = $_POST['stock_quantity'];  // 
    $reorder_level = $_POST['reorder_level'];
    
    $stmt = $db->prepare("UPDATE products SET  product_name= ?, product_description=?, product_price=?,
                                    product_color=?, product_category=?, stock_quantity=?, reorder_level=? WHERE product_id = ?");
    $stmt->bind_param('sssssiii', $title,$description,$price,$color,$category,$stock_quantity,$reorder_level,$product_id);
    if($stmt->execute()){
        header('location: products.php?edit_success_message=Product has been updated successfully');
    }else{
        header('location: products.php?edit_error_message=Error occured, please try again.');
    }

    
}else{
    header('products.php');
    exit;
}
?>


<div class="container-fluid">
      <div class="row" style="min-height: 1000px">
            <?php 
              include_once('side_menu.php');  
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>

                </div>
              </div>
    <h2 class="mt-5 py-5">Edit Product</h2>
    <div class="table-responsive me-5">

        <div class="mx-auto container">
            <form id="edit-form" method="POST" action="edit_product.php">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <div class="form-group mt-2">
                    <?php foreach($products as $product) {?>

                    <input type="hidden" name="product_id" value="<?php echo $product['product_id'];?>">

                    <label>Product Name</label>
                    <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name'];?>" name="title" placeholder="Title" required>
                </div>

                <div class="form-group mt-2">
                    <label>Description</label>
                    <input type="text" class="form-control" id="product-desc" value="<?php echo $product['product_description'];?>" name="description" placeholder="Description" required>
                </div>

                <div class="form-group mt-2">
                    <label>Price</label>
                    <input type="text" class="form-control" id="product-price" min="0" value="<?php echo $product['product_price'];?>" name="price" placeholder="Price" required>
                </div>

                <div class="form-group mt-2">
                    <label>Category</label>
                    <select class="form-select" name="category" required>
                        <?php
                        
                        //
                        $sql = "SELECT category_id, category_name FROM categories";
                        $result = $db->query($sql);

                        
                        while ($category_row = $result->fetch_assoc()) {
                            $category_id = $category_row['category_id'];
                            $category_name = $category_row['category_name'];
                            
                            $selected = ($product['product_category'] == $category_name) ? 'selected' : '';
                            echo "<option value=\"$category_name\" $selected>$category_name</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>Color</label>
                    <input type="text" class="form-control" value="<?php echo $product['product_color'];?>" id="product_color" name="color" placeholder="Color" required>
                </div>


                <!--  -->
                <div class="form-group mt-2">
                    <label>Stock Quantity</label>
                    <input type="number" class="form-control" min="0" value="<?php echo $product['stock_quantity'];?>" name="stock_quantity" placeholder="Stock Quantity" required> <!-- -->
                </div>

                <div class="form-group mt-2">
                    <label>Reorder Level</label>
                    <input type="number" class="form-control" min="0" value="<?php echo $product['reorder_level'];?>" name="reorder_level" placeholder="Reorder Level" required> <!-- -->
                </div>

                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-primary" name="edit_btn" value="Edit">

                </div>

                <?php } ?>
            </form>
        </div>
    </div>
            </main>
      </div>
</div>
    
<?php include_once('../admin_layouts/admin_footer.php'); ?>