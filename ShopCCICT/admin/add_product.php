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
//
//include_once('session.php');
//
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
    <h2 class="mt-5 py-5">Create product</h2>
    <div class="table-responsive me-5">

        <div class="mx-auto container">
            <form id="create-form" enctype="multipart/form-data" method="POST" action="create_product.php">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <div class="form-group mt-2">
                    

                    <input type="hidden" name="product_id">

                    <label>Product Name</label>
                    <input type="text" class="form-control" id="product-name"  name="name" placeholder="Product Name" required>
                </div>

                <div class="form-group mt-2">
                    <label>Description</label>
                    <input type="text" class="form-control" id="product-desc"  name="description" placeholder="Description" required>
                </div>

                <div class="form-group mt-2">
                    <label>Price</label>
                    <input type="text" class="form-control" id="product-price"  name="price" placeholder="Price" required>
                </div>

                <div class="form-group mt-2">
                    <label>Category</label>
                    <select class="form-select" required name="category">
                    <?php
                                    
                                    $sql = "SELECT category_id, category_name FROM categories";
                                    $result = $db->query($sql);  

                                    
                                    if ($result->num_rows > 0) {
                                        
                                        while ($row = $result->fetch_assoc()) {
                                            $category_id = $row['category_id'];
                                            $category_name = $row['category_name'];
                                            echo "<option value=\"$category_id\">$category_name</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No categories available</option>";
                                    }
                                ?>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>Color</label>
                    <input type="text" class="form-control" id="product_color" name="color" placeholder="Color" required>
                </div>

                <div class="form-group mt-2">
                    <label>Image 1</label>
                    <input type="file" class="form-control" id="image1" accept="image/*" name="image1" placeholder="Image 1" required>
                </div>


                <div class="form-group mt-2">
                    <label>Image 2</label>
                    <input type="file" class="form-control" id="image2" accept="image/*" name="image2" placeholder="Image 2" required>
                </div>


                <div class="form-group mt-2">
                    <label>Image 3</label>
                    <input type="file" class="form-control" id="image3" accept="image/*" name="image3" placeholder="Image 3" required>
                </div>


                <div class="form-group mt-2">
                    <label>Image 4</label>
                    <input type="file" class="form-control" id="image4" accept="image/*" name="image4" placeholder="Image 4" required>
                </div>


                <!--  -->
            <div class="form-group mt-2">
              <label>Stock Quantity</label>
              <input type="number" class="form-control" min="0" id="stock_quantity" name="stock_quantity" placeholder="Stock Quantity" required>
            </div>

            <!--  -->
            <div class="form-group mt-2">
              <label>Reorder Level</label>
              <input type="number" class="form-control" min="0" id="reorder_level" name="reorder_level" placeholder="Reorder Level" required>
            </div>

                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-success" name="create_product" value="Create">

                </div>

                
            </form>
        </div>
    </div>
            </main>
      </div>
</div>
    



<?php include_once('../admin_layouts/admin_footer.php'); ?>