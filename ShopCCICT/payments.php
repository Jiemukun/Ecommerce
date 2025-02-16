<?php
$page_title = 'Payment Method';
require_once('includes/load.php');
include_once('layouts/header.php');


$order_id = $_POST['order_id'];
$order_total_price = $_POST['order_total_price'];
//$order_status = $_POST['order_status'];

if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
}

if (isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $order_total_price = $_POST['order_total_price'];
    
    // Check if the order exists
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // No order = redirect to account page
        header('Location: account.php?error=invalid_order');
        exit;
    }
} else {
    // no order = redirect account page
    header('Location: account.php?error=missing_order');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['payment_method'])) {
        $payment_method = $_POST['payment_method'];

        
        if ($payment_method == 'gcash') {
            header('Location: gcash.php?order_id=' . $order_id . '&total=' . $order_total_price);
            exit;
        }

        
        if ($payment_method == 'cash') {
            
            $stmt = $db->prepare("UPDATE orders SET order_status = 'unpaid' WHERE order_id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            
            header('Location: account.php?payment_status=Please proceed to the counter');
            exit;
        }
    }
}
?>

<!-- Payment Method Selection -->
<section id="payment-method" class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="text-center">Choose Payment Method</h2>

        <form method="POST" action="payments.php">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" name="order_total_price" value="<?php echo $order_total_price; ?>">

            <div class="form-group d-flex justify-content-center">
    <div class="col-6 col-md-4"> 
        <label for="payment_method" class="d-block text-center">Select Payment Method</label>
        <select class="form-control mx-auto" name="payment_method" required>
            <option value="cash">Cash</option>
            <option value="gcash">Gcash</option>
        </select>
    </div>
</div><br>


            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>
    </div>
</section>

<?php include_once('layouts/footer.php'); ?>
