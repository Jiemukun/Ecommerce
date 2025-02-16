<?php
  $page_title = 'Add Category';
  require_once('../includes/load.php');  
   include_once('../admin_layouts/admin_header.php'); 
  
  if (!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit;
  }
  //
  //include_once('session.php');
  //
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $product_category = $_POST['product_category'];

   
    $stmt = $db->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->bind_param("s", $product_category);

    if ($stmt->execute()) {
      
      header("Location: create_category.php?success_message=Category added successfully.");
      exit;
    } else {
      
      echo "Error adding category.";
    }
  }
?>



<div class="container-fluid my-5 py-5">
  <div class="row">
    <?php include_once('side_menu.php'); ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <h1 class="h2 mt-5">Add Product Category</h1>

      <?php 
        if (isset($_GET['success_message'])) { 
          echo '<p class="text-center" style="color: green;">' . $_GET['success_message'] . '</p>';
        } 
      ?>

      <form action="create_category.php" method="POST" class="mx-auto">
        <div class="mb-3">
          <label for="product_category" class="form-label">Product Category</label>
          <input type="text" class="form-control" id="product_category" name="product_category" required placeholder="Enter category name" style="width: 250px; height: 35px;">
        </div>
        
        <button type="submit" class="btn btn-primary">Add Category</button>
      </form>
    </main>
  </div>
</div>

<?php include_once('../admin_layouts/admin_footer.php'); ?>
