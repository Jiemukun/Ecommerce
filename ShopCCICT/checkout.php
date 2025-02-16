<?php
  $page_title = 'Checkout';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false); }
?>
<?php include_once('layouts/header.php'); ?>

<?php 
// session_start();
//include_once('sessionz.php');
if(!empty($_SESSION['cart']) && isset($_POST['checkout'])) {
  
} else {
  header('location: index.php');
}
?>

<!-- Checkout -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <p style="color: red;"><?php if(isset($_GET['error'])) { echo $_GET['error']; } ?></p>
        <h2 class="form-weight-bold">Check Out</h2>
        <hr class="mx-auto">
    </div>  

    <div class="mx-auto container">
        <form id="checkout-form" method="POST" action="place_order.php">
            <div class="form-group checkout-small-element">
                <label>Name</label>
                <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Name" required>
            </div>
            
            <div class="form-group checkout-small-element">
                <label>Email</label>
                <input type="email" class="form-control" id="checkout-email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group checkout-small-element">
                <label>Phone</label>
                <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Phone" required>
            </div>
            
            <div class="form-group checkout-small-element">
                <label>City</label>
                <input type="text" class="form-control" id="checkout-city" name="city" placeholder="City" required>
            </div>

            <div class="form-group checkout-large-element">
                <label>Address</label>
                <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required>
            </div>

            <div class="form-group checkout-btn-container">
                <p>Total amount: ₱ <?php echo number_format($_SESSION['total']); ?></p>
                <!-- <p>Taxed: ₱ <?php echo number_format($_SESSION['taxed']); ?></p> -->
                <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Place order">
            </div>
        </form>
    </div>
</section>

<?php include_once('layouts/footer.php'); ?>

<script>

document.getElementById("checkout-form").addEventListener("submit", function(event) {
    var phone = document.getElementById("checkout-phone").value;
    var email = document.getElementById("checkout-email").value;
    var phonePattern = /^09\d{9}$/;  // Ph

    
    if (!phonePattern.test(phone)) {
        alert("Please enter a valid phone number"); //09123456789
        event.preventDefault();  
        return false;
    }

    
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();  
        return false;
    }

    return true;  
});
</script>
