<?php
  $page_title = 'Cart';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  
?>
<?php include_once('layouts/header.php'); ?>
<?php 
    // session_start();
    //include_once('sessionz.php');
    if(isset($_POST['add_to_cart'])){
        
        if(isset($_SESSION['cart'])){
            $products_array_ids = array_column($_SESSION['cart'], "product_id");
            if (!in_array($_POST['product_id'], $products_array_ids)) {
                
                $product_id = $_POST['product_id'];
                $product_array = array(
                    'product_id' => $_POST['product_id'],
                    'product_name' => $_POST['product_name'],
                    'product_price' => $_POST['product_price'],
                    'product_image' => $_POST['product_image'],
                    'product_quantity' => $_POST['product_quantity'],
                );
                
                $_SESSION['cart'][$product_id] = $product_array;
                calculateTotalCart();
            } else {
                echo '<script>alert("Product was already added to cart")</script>';
                $_SESSION['cart'][$_POST['product_id']]['product_quantity'] = $_POST['product_quantity'];
                calculateTotalCart();
                echo '<script>alert("Product quantity updated in the cart.")</script>';
            }
        } else {
            
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image = $_POST['product_image'];
            $product_quantity = $_POST['product_quantity'];

            $product_array = array(
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'product_image' => $product_image,
                'product_quantity' => $product_quantity,
            );

            $_SESSION['cart'][$product_id] = $product_array;
            calculateTotalCart();
        }
    } elseif (isset($_POST['remove_product'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
        calculateTotalCart();
    } elseif (isset($_POST['edit_quantity'])) {
        $product_id = $_POST['product_id'];
        $product_quantity = $_POST['product_quantity'];

        
        $_SESSION['cart'][$product_id]['product_quantity'] = $product_quantity;
        calculateTotalCart();
    }
?>

<?php 
function calculateTotalCart(){
    $total_price = 0;
    $total_quantity = 0;
    foreach($_SESSION['cart'] as $key => $value){
        $product = $_SESSION['cart'][$key];
        $price = $product['product_price'];
        $quantity = $product['product_quantity'];

        $total_price = $total_price + ($price * $quantity);
        $total_quantity = $total_quantity + $quantity;
    }
    $_SESSION['total'] = $total_price;
    $_SESSION['quantity'] = $total_quantity;

}
?>

<!-- Cart -->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Your cart</h2>
        <hr>
    </div>

    <table class="mt-5 pt-5">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $value) { 
                
                if (isset($value['product_name'], $value['product_price'], $value['product_image'], $value['product_quantity'])) {
        ?>
        <tr>
            <td>
                <div class="product-info">
                    <img src="lib/img/<?php echo htmlspecialchars($value['product_image']); ?>" alt="Image">
                    <div>
                        <p><?php echo htmlspecialchars($value['product_name']); ?></p>
                        <small><span>₱</span><?php echo number_format($value['product_price'], 2); ?></small>
                        <br>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                            <input type="submit" class="remove-btn" name="remove_product" value="Remove" style="background-color: white; border: none; width: 100%;"/>                                                     
                        </form>
                    </div>
                </div>
            </td>

            <td>
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                    <input type="number" name="product_quantity" min="1" value="<?php echo $value['product_quantity']; ?>">
                    <input type="submit" class="edit-btn" value="Update" name="edit_quantity" style="background-color: white; border: none; width: 100%;"/>
                </form>
            </td>

            <td>
                <span>₱</span>
                <span class="product-price"><?php echo number_format($value['product_quantity'] * $value['product_price'], 2); ?></span>
            </td>
        </tr>
        <?php 
                } 
            }
        } else {
            echo '<tr><td colspan="3">Your cart is empty.</td></tr>';
        }
        ?>
    </table>

    <!-- Second TABLE -->
    <div class="cart-total">
        <table>
            <tr>
                <td>Total</td>
                <td>₱<?php 
                    $total = 0;
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            
                            if (isset($item['product_price']) && isset($item['product_quantity'])) {
                                $total += $item['product_quantity'] * $item['product_price'];
                                $taxed = $total * 1.1;
                            }
                        }
                        $_SESSION['total'] = $total;
                        $_SESSION['taxed'] = $taxed;
                    }
                    echo number_format($total, 2); 
                ?></td>
            </tr>

            <tr>
                <!-- <td>Total(TAXED)</td>
                <td>₱<?php echo number_format($total * 1.1, 2); //10% VAT?></td> -->
            </tr>
        </table>
    </div>

    <div class="checkout-container">
        <form method="POST" action="checkout.php">
            <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout">      
        </form>
        
    </div>
</section>


<?php include_once('layouts/footer.php'); ?>
