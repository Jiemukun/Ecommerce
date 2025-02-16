<?php
$page_title = 'Home';
require_once('includes/load.php');
include_once('layouts/header.php');

//include_once('sessionz.php');
//
if (isset($_POST['order_details_btn']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    
    $stmt = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $order_details = $result->fetch_all(MYSQLI_ASSOC);
    

    $order_total_price = calculateTotalOrderPrice($order_details);

    //
    if ($order_status == 'paid') {
        updateStockQuantity($order_details); //
    }
    //
} else {
    header('location: account.php');
    exit;
}

function calculateTotalOrderPrice($order_details) {
    $total = 0;

    foreach ($order_details as $row) {
        $product_price = $row['product_price'];
        $product_quantity = $row['product_quantity'];

        $total += $product_price * $product_quantity;
    }

    return $total;
}

?>

<!-- Order details -->
<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center">Order details</h2>
        <hr class="mx-auto">
    </div>

    <table class="mt-5 pt-5 mx-auto">
        <tr style="background-color: #fb774b;">
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($order_details as $row) { ?>
            <tr style="border: 1px solid black">
                <td style="text-align: center;">
                    <div class="product-info">
                        <img src="lib/img/<?php echo $row['product_image']; ?>" alt="">
                        <div>
                            <p class="mt-3"><?php echo $row['product_name']; ?></p>
                        </div>
                    </div>
                </td>

                <td>
                    <span>₱<?php echo $row['product_price']; ?></span>
                </td>

                <td>
                    <span><?php echo $row['product_quantity']; ?></span>
                </td>
             
            </tr>
        <?php } ?>
    </table>



    
    <div style="margin-top: 20px; text-align: right; font-weight: bold;">
        <p>Total: ₱<?php echo number_format($order_total_price, 2); ?></p>
    </div>



    <?php if ($order_status == "unpaid" || $order_status == "Unpaid") { ?>
        <form style="float: right;" method="POST" action="payments.php">
            <input type="hidden" name="order_total_price" value="<?php echo $order_total_price; ?>">
            <input type="hidden" name="order_status" value="<?php echo $order_status; ?>">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"> 
            <input type="submit" name="order_pay_btn" class="btn btn-success" value="Pay Now">
        </form>
    <?php } ?>
</section>

<?php include_once('layouts/footer.php'); ?>
