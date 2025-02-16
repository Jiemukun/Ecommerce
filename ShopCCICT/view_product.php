<?php
  $page_title = 'View Product';
  require_once('includes/load.php'); 
  //include_once('sessionz.php');
//
  $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
  
  
  if ($product_id > 0) {
      
      $sql = "SELECT * FROM products WHERE product_id = $product_id";
      $stmt = $db->query($sql);

      
      $product = $db->while_loop($stmt);

      
      if (empty($product)) {
          header('Location: index.php');
          exit();
      } else {
          $product = $product[0]; 
      }

      
      $product_category = $product['product_category'];
  } else {
      header('Location: index.php');
      exit();
  }

   
   $related_sql = "SELECT * FROM products WHERE product_category = ? AND product_id != ? LIMIT 4";
   $related_stmt = $db->prepare($related_sql);
   $related_stmt->bind_param('si', $product_category, $product_id); 
   $related_stmt->execute();
   $related_products = $related_stmt->get_result();
?>

<?php include_once('layouts/header.php'); ?>

<!-- Single Product Section -->
<section class="container single-product my-5 pt-5">
    <div class="row mt-5">
        
        <div class="main-product col-lg-5 col-md-6 col-sm-12">
            <img class="img-fluid w-100 pb-1" src="lib/img/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Product Image" id="mainImg">
            <div class="small-img-group">
                <div class="view-product small-img-col">
                    <img src="lib/img/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Small Image" width="100%" class="small-img">
                </div>
                <div class="view-product small-img-col">
                    <img src="lib/img/<?php echo htmlspecialchars($product['product_image2']); ?>" alt="Small Image 2" width="100%" class="small-img">
                </div>
                <div class="view-product small-img-col">
                    <img src="lib/img/<?php echo htmlspecialchars($product['product_image3']); ?>" alt="Small Image 3" width="100%" class="small-img">
                </div>
                <div class="view-product small-img-col">
                    <img src="lib/img/<?php echo htmlspecialchars($product['product_image4']); ?>" alt="Small Image 4" width="100%" class="small-img">
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-12 col-sm-12">
            <h6><?php echo htmlspecialchars($product['product_category']); ?></h6>
            <h3 class="py-4">
                <?php 
            if ($product['stock_quantity'] == 0) {
                
                echo "<span style='color: red; font-weight: bold;'>Out of Stock</span>";
            } else {
                
                echo "<span style='color: green;'>Stock: " . htmlspecialchars($product['stock_quantity']) . "</span>";
            }
            ?>
            </h3> 
            <h3 class="py-4"><?php echo htmlspecialchars($product['product_name']); ?></h3>
            <h2>₱ <?php echo number_format($product['product_price'], 2); ?></h2>
           
            <form method="POST" action="cart.php">
            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['product_image']); ?>"/>
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>"/>
            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>"/>
                <input type="number" name="product_quantity" value="1" min="1" class="form-control mb-3" required>
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit" class="buy-btn" name="add_to_cart">Add to Cart</button>
            </form>
            <h4 class="mt-5 mb-5">Product details</h4>
            <span><?php echo nl2br(htmlspecialchars($product['product_description'])); ?></span>
        </div>
        
    </div>
</section>

<!-- Related Products Section -->
<section id="related-products" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3>Related Products</h3>
        <hr class="mx-auto">
    </div>
    <div class="row mx-auto container-fluid">
        
        <?php if ($related_products->num_rows > 0): ?>
            <?php while($related = $related_products->fetch_assoc()): ?>
                <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid mb-3" src="lib/img/<?php echo htmlspecialchars($related['product_image']); ?>" />
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name"><?php echo htmlspecialchars($related['product_name']); ?></h5>
                    <h4 class="p-price">₱<?php echo number_format($related['product_price'], 2); ?></h4>
                    <a style="background-color: orange; text-decoration: none; color: black;" href="view_product.php?product_id=<?php echo $related['product_id']; ?>" class="btn btn-link">
                        View Product
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No related products found.</p>
        <?php endif; ?>

    </div>
</section>

<script>
    var mainImg = document.getElementById("mainImg");
    var smallImg = document.getElementsByClassName("small-img");
    for (let i = 0; i < smallImg.length; i++) {
        smallImg[i].onclick = function() {
            mainImg.src = smallImg[i].src;
        };
    }
</script>

<?php include_once('layouts/footer.php'); ?>
