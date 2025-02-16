<?php
  $page_title = 'Admin';
  require_once('../includes/load.php');  
?>

<?php include_once('../admin_layouts/admin_header.php'); ?>

<?php 

if (!isset($_SESSION['admin_logged_in'])){
  header('location: login.php');
  exit;
}
//
//include_once('session.php');
//

if(isset($_GET['product_id'])){
    $product_id = (int)$_GET['product_id']; 
    $product_name = htmlspecialchars($_GET['product_name']); 
} else {
    header('location: products.php');
    exit;
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
?>

<div class="container-fluid">
    <div class="row" style="min-height: 1000px">
        <?php include_once('side_menu.php'); ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Dashboard</h1>
            </div>
            <h2 class="mt-5 py-5">Edit Images</h2>
            <div class="table-responsive me-5">
                <div class="mx-auto container">
                    <form id="edit-image-form" enctype="multipart/form-data" method="POST" action="update_images.php">
                        <p style="color: red;"><?php if (isset($_GET['error'])) { echo $_GET['error']; } ?></p>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">

                        <!-- Image 1 -->
                        <div class="form-group mt-2">
                            <label>Image 1</label>
                            <input type="file" class="form-control" id="image1" accept="image/*" name="image1" placeholder="Image 1">
                            <?php if (!empty($product['product_image'])): ?>
                                <p>Current Image 1:</p>
                                <img src="../lib/img/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Current Image 1" style="max-width: 200px; margin-top: 10px;">
                            <?php else: ?>
                                <p>No current image.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Image 2 -->
                        <div class="form-group mt-2">
                            <label>Image 2</label>
                            <input type="file" class="form-control" id="image2" accept="image/*" name="image2" placeholder="Image 2">
                            <?php if (!empty($product['product_image2'])): ?>
                                <p>Current Image 2:</p>
                                <img src="../lib/img/<?php echo htmlspecialchars($product['product_image2']); ?>" alt="Current Image 2" style="max-width: 200px; margin-top: 10px;">
                            <?php else: ?>
                                <p>No current image.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Image 3 -->
                        <div class="form-group mt-2">
                            <label>Image 3</label>
                            <input type="file" class="form-control" id="image3" accept="image/*" name="image3" placeholder="Image 3">
                            <?php if (!empty($product['product_image3'])): ?>
                                <p>Current Image 3:</p>
                                <img src="../lib/img/<?php echo htmlspecialchars($product['product_image3']); ?>" alt="Current Image 3" style="max-width: 200px; margin-top: 10px;">
                            <?php else: ?>
                                <p>No current image.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Image 4 -->
                        <div class="form-group mt-2">
                            <label>Image 4</label>
                            <input type="file" class="form-control" id="image4" accept="image/*" name="image4" placeholder="Image 4">
                            <?php if (!empty($product['product_image4'])): ?>
                                <p>Current Image 4:</p>
                                <img src="../lib/img/<?php echo htmlspecialchars($product['product_image4']); ?>" alt="Current Image 4" style="max-width: 200px; margin-top: 10px;">
                            <?php else: ?>
                                <p>No current image.</p>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-success" name="edit-image-btn" value="Edit Images">
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once('../admin_layouts/admin_footer.php'); ?>
