<?php
$page_title = 'Gcash Payment';
require_once('includes/load.php');
include_once('layouts/header.php');

if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
}

if (isset($_GET['order_id']) && isset($_GET['total'])) {
    $order_id = $_GET['order_id'];
    $order_total_price = $_GET['total'];
    
    
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?"); //AND user
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']); //
    $stmt->execute();
    $order_result = $stmt->get_result();

    //
    if ($order_result->num_rows === 0) {
        header('Location: account.php?error=invalid_order');
        exit;
    }

    $order_details = $order_result->fetch_assoc();
} else {
    
    header('Location: payments.php');
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['gcash_reference_number']) && isset($_FILES['payment_image'])) {
        $gcash_reference_number = $_POST['gcash_reference_number'];
        $payment_image = $_FILES['payment_image'];

        // Validate Gcash(digits)
        if (!ctype_digit($gcash_reference_number)) {
            echo "Invalid Gcash reference number. It should only contain digits.";
            exit;
        }

        if (strlen($gcash_reference_number) < 10 || strlen($gcash_reference_number) > 15) {
            echo "Invalid Gcash reference number. It should be between 10 and 15 digits.";
            exit;
        }
        
        $gcash_reference_number = filter_var($gcash_reference_number, FILTER_SANITIZE_STRING);

        $upload_dir = 'uploads/gcash_proofs/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        
        if (!in_array($payment_image['type'], $allowed_types)) {
            echo "Invalid image type. Please upload a JPEG, PNG, or GIF image.";
            exit;
        }

        // (5MB limit)
        if ($payment_image['size'] > 5000000) {
            echo "File size is too large. Maximum allowed size is 5MB.";
            exit;
        }

        
        $unique_filename = uniqid('gcash_', true) . '.' . pathinfo($payment_image['name'], PATHINFO_EXTENSION);
        $uploaded_image = $upload_dir . $unique_filename;

        if (move_uploaded_file($payment_image['tmp_name'], $uploaded_image)) {
            
            $stmt = $db->prepare("UPDATE orders SET order_status = 'payment_processing', gcash_reference_number = ?, payment_image = ? WHERE order_id = ?");
            $stmt->bind_param("ssi", $gcash_reference_number, $uploaded_image, $order_id);
            $stmt->execute();

            
            header('Location: account.php?payment_status=Payment is being processed');
            exit;
        } else {
            echo "Error uploading payment image.";
        }
    }
}
?>

<!-- Gcash Payment Form -->
<section id="gcash-payment" class="my-5 py-5">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <div class="col-12 text-center mb-4">
                <h2>Complete Your Payment</h2>
            </div>

            <div class="col-12 col-md-8 col-lg-6 mx-auto">
                <!-- Order details -->
                <div class="order-details mb-4">
                    <p><strong>Order ID:</strong> <?php echo $order_details['order_id']; ?></p>
                    <p><strong>Total Price:</strong> ₱<?php echo number_format($order_total_price, 2); ?></p>
                </div>

                <!-- Payment form -->
                <form id="gcash-payment-form" method="POST" enctype="multipart/form-data" action="gcash.php?order_id=<?php echo $order_id; ?>&total=<?php echo $order_total_price; ?>">
                    <div class="form-group">
                        <label for="gcash_reference_number">Gcash Reference Number</label>
                        <input type="text" class="form-control" name="gcash_reference_number" id="gcash_reference_number" placeholder="Enter Gcash Reference Number" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_image">Upload Payment Proof (Image)</label>
                        <input type="file" class="form-control" name="payment_image" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
</section>



<script>

document.getElementById('gcash-payment-form').addEventListener('submit', function(event) {
    var gcashReferenceNumber = document.getElementById('gcash_reference_number').value;
    var gcashPattern = /^[0-9]{10,15}$/;  

    
    if (!gcashPattern.test(gcashReferenceNumber)) {
        alert('Invalid Gcash reference number. It should only contain digits and be between 10 and 15 characters long.');
        event.preventDefault();
        document.getElementById('gcash_reference_number').focus();

        return false;  
    }

    return true;
});

</script>


<?php include_once('layouts/footer.php'); ?>
