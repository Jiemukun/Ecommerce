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



<?php


      if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no = $_GET['page_no'];
      }else{
        $page_no = 1;
      }

      $stmt1 = $db->prepare("SELECT COUNT(*) As total_records FROM products");
      $stmt1->execute();
      $stmt1->bind_result($total_records);
      $stmt1->store_result();
      $stmt1->fetch();

      //prod per page
      $total_records_per_page = 10;
      $offset = ($page_no-1) * $total_records_per_page;
      $previous_page = $page_no - 1;
      $next_page = $page_no + 1;

      $adjacents = "2";

      $total_no_of_pages = ceil($total_records/$total_records_per_page);

      //get all prod
      $stmt2 = $db->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
      $stmt2->execute();
      $products = $stmt2->get_result();


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

            

<h2 class="mt-5 py-5">Products</h2> 
<?php if(isset($_GET['edit_success_message'])) {?>
  <p class="text-center" style="color: green;"><?php echo $_GET['edit_success_message'];?></p>
<?php } ?>

<?php if(isset($_GET['edit_error_message'])) {?>
  <p class="text-center" style="color: red;"><?php echo $_GET['edit_error_message'];?></p>
<?php } ?>

<?php if(isset($_GET['deleted_successfully'])) {?>
  <p class="text-center" style="color: green;"><?php echo $_GET['deleted_successfully'];?></p>
<?php } ?>

<?php if(isset($_GET['deleted_error'])) {?>
  <p class="text-center" style="color: red;"><?php echo $_GET['deleted_error'];?></p>
<?php } ?>

<?php if(isset($_GET['product_created'])) {?>
  <p class="text-center" style="color: green;"><?php echo $_GET['product_created'];?></p>
<?php } ?>

<?php if(isset($_GET['product_error'])) {?>
  <p class="text-center" style="color: red;"><?php echo $_GET['product_error'];?></p>
<?php } ?>

<?php if(isset($_GET['product_updated'])) {?>
  <p class="text-center" style="color: green;"><?php echo $_GET['product_updated'];?></p>
<?php } ?>
<div class="table-responsive me-5">
  <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th scope="col">Product ID</th>
          <th scope="col">Product Image</th>
          <th scope="col">Product Name</th>
          <th scope="col">Product Price</th>
          
          <th scope="col">Product Category</th>
          <th scope="col">Product Color</th>
          <!--                             -->
          <th scope="col">Stock Quantity</th>
          <th scope="col">Reorder Level</th>
          <th scope="col">Edit Image</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($products as $product) {
          // $reorderLevel = calculateReorderLevel($product['product_id'], $db);
        ?>

        <tr>
          <td style="background-color: <?php echo ($product['stock_quantity'] < $product['reorder_level']) ? 'red' : 'inherit'; ?>;"><?php echo $product['product_id']; ?></td>
          <td><img src="<?php echo "../lib/img/" . $product['product_image'];?>" style="width: 70px; height: 70px;"></td>
          <td><?php echo $product['product_name']; ?></td>
          <td><?php echo "₱" . number_format($product['product_price'], 2); ?></td>
          <!-- <td><?php echo $product['product_special_offer']; ?></td> -->
          <td><?php echo $product['product_category']; ?></td>
          <td><?php echo $product['product_color']; ?></td>
          <!-- stock quantity reorder level -->
          <td style="background-color: <?php echo ($product['stock_quantity'] == 0) ? 'red' : 'inherit'; ?>;"><?php echo $product['stock_quantity']; ?></td>
          <td style="background-color: <?php echo ($product['stock_quantity'] < $product['reorder_level']) ? 'red' : 'inherit'; ?>;"><?php echo $product['reorder_level']; ?></td>  <!--  $reorderLevel-->
          <td><a class="btn btn-success" href="<?php echo "edit_images.php?product_id=" . $product['product_id'] . "&product_name=" . $product['product_name'];?>">Image</a></td>
          <td><a class="btn btn-primary" href="edit_product.php?product_id=<?php echo $product['product_id'];?>">Edit</a></td>
          <td><a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $product['product_id']; ?>)">Delete</a></td>
        </tr>
        <?php } ?>
      </tbody>
  </table>

  <!--Page OR Pagination-->
  <nav aria-label="Page navigation example">
                <ul class="pagination mt-5">
                    <li class="page-item <?php if($page_no<=1){echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no <= 1) { echo '#'; }else{ echo "?page_no=".($page_no-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>

                    <?php if($page_no >=3) {?>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="<?php echo "?page_no=" . $page_no;?>"><?php echo ($page_no);?></a></li>
                    <?php } ?>


                    <li class="page-item <?php if($page_no >= $total_no_of_pages){ echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no >= $total_no_of_pages) {echo '#';} else{ echo "?page_no=" . ($page_no+1);}?>">Next</a></li>

                </ul>
            </nav>
          </div>
        </main>
      </div>
</div>

<script type="text/javascript">
    function confirmDelete(productId) {
        
        var confirmation = confirm("Are you sure you want to delete this product?");
        
        
        if (confirmation) {
            window.location.href = "delete_product.php?product_id=" + productId;
        }
    }
</script>


<?php include_once('../admin_layouts/admin_footer.php'); ?>