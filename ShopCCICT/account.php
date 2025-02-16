<?php
  $page_title = 'Account';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  
?>

<?php include_once('layouts/header.php'); ?>
<?php 

if(!isset($_SESSION['logged_in'])){
    header('location: login.php');
    exit;
}


if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
        session_start();  
        session_unset();  
        session_destroy(); 
        header('location: login.php');
        exit;
    }
}


//Change pass
if(isset($_POST['change_password'])){
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $user_email = $_SESSION['user_email'];

    
    if($password !== $confirmPassword){
        header('location: account.php?error=Passwords do not match');
        exit; 
    }

    
    elseif(strlen($password) < 8) {
        header('location: account.php?error=Passwords must be at least 8 characters');
        exit;
    }

    elseif(!preg_match('/[A-Za-z]/', $password) || 
           !preg_match('/[0-9]/', $password) || 
           !preg_match('/[\W_]/', $password)) 
        { 
            header('location: account.php?error=Password must contain at least 1 uppercase letter, 1 number, and 1 special character');
        exit;
        }

        else {
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
            
            $stmt = $db->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
            $stmt->bind_param("ss", $hashedPassword, $user_email);
    
            if ($stmt->execute()) {
                
                header('location: account.php?message=Password updated successfully');
            } else {
                
                header('location: account.php?error=Error updating password');
            }
        }

}



if(isset($_SESSION['logged_in'])){
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $db->execute($stmt);
    
    $orders = $db->fetch_result($stmt);
}
?>



<!-- Account --> 
<section class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
            <h3 class="font-weight-bold">Account info</h3>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['register_success'])){ echo $_GET['register_success']; }?></p>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['login_success'])){ echo $_GET['login_success']; }?></p>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['order_status'])){ echo $_GET['order_status']; }?></p>
            <p class="text-center" style="color: green;"><?php if(isset($_GET['payment_status'])){ echo $_GET['payment_status']; }?></p>
            <hr class="mx-auto">
            <div class="account-info">
                <p>Name: <span><?php if(isset($_SESSION['user_name'])) {echo $_SESSION['user_name']; }?></span></p>
                <p>Email: <span><?php if(isset($_SESSION['user_email'])) {echo $_SESSION['user_email']; } ?></span></p>
                <p><a href="#orders" id="orders-btn">Your orders</a></p>
                <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
            </div>
        </div>

        <div class="text-center col-lg-6 col-md-12 col-sm-12">
            <form id="account-form" method="POST" action="account.php">
                <p class="text-center" style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
                <p class="text-center" style="color: green;"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
                <h3>Change password</h3>
                <hr class="mx-auto">
                <div class="form-group">
                    <label>Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-password"></i>
                    <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-confirm-password"></i>
                    <input type="password" class="form-control" id="account-confirm-password" name="confirmPassword" placeholder="Confirm Password" required/>
                </div>

                <div class="form-group">
                    <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn"/>
                </div>

            </form>
        </div>
    </div>
</section>


<!-- Orders -->
<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Your cart</h2>
        <hr class="mx-auto">
    </div>

    



    <!-- -->
<ul class="nav nav-tabs" id="orderTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="unpaid-tab" data-bs-toggle="tab" href="#unpaid" role="tab" aria-controls="unpaid" aria-selected="true" style="background-color: red; color: black; border: 2px solid #000;";>Unpaid Orders</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="paid-tab" data-bs-toggle="tab" href="#paid" role="tab" aria-controls="paid" aria-selected="false" style="background-color: lightgreen; color: black; border: 2px solid #000;">Paid Orders</a>
    </li>

    <li class="nav-item" role="presentation">
        <a class="nav-link" id="processing-tab" data-bs-toggle="tab" href="#processing" role="tab" aria-controls="processing" aria-selected="false" style="background-color: orange; color: black; border: 2px solid #000;">Processing Orders</a>
    </li>
</ul>

<div class="tab-content mt-3" id="orderTabsContent">
    <!-- Unpaid -->
    <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
        <table class="table table-striped mt-5 pt-5">
            <thead>
                <tr>
                    <th style="background-color: #fb774b; border: 1px solid #000;">Order ID</th>
                    <th style="background-color: #fb774b; border: 1px solid #000;">Order Cost</th>
                    <th style="background-color: #fb774b; border: 1px solid #000;">Order Status</th>
                    <th style="background-color: #fb774b; border: 1px solid #000;">Order Date</th>
                    <th style="background-color: #fb774b; border: 1px solid #000;">Order Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($orders as $row) {
                    if ($row['order_status'] == 'unpaid') { ?>
                        <tr style="border: 1px solid black;">
                            <td style="text-align: center; background-color: lightblue; border: 1px solid #000">
                                <span><?php echo $row['order_id']; ?></span>
                            </td>
                            <td>
                                <span>₱<?php echo number_format($row['order_cost'], 2); ?></span>
                            </td>
                            <td>
                                <span><?php echo $row['order_status']; ?></span>
                            </td>
                            <td>
                                <span><?php echo $row['order_date']; ?></span>
                            </td>
                            <td>
                                <form method="POST" action="order_details.php">
                                    <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status">
                                    <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id">
                                    <input class="btn btn-info" name="order_details_btn" style="color: white; background-color: #fb774b;" type="submit" value="Details">
                                </form>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>

    <!-- Paid Orders Tab Content -->
    <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
        <table class="table table-striped mt-5 pt-5">
            <thead>
                <tr style="background-color: #5cb85c; ">
                    <th style="background-color: green; border: 1px solid #000;">Order ID</th>
                    <th style="background-color: green; border: 1px solid #000;">Order Cost</th>
                    <th style="background-color: green; border: 1px solid #000;">Order Status</th>
                    <th style="background-color: green; border: 1px solid #000;">Order Date</th>
                    <th style="background-color: green; border: 1px solid #000;">Order Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($orders as $row) {
                    if ($row['order_status'] == 'paid') { ?>
                        <tr style="border: 1px solid black; ">
                            <td style="text-align: center; background-color: lightgreen; border: 1px solid #000">
                                <span><?php echo $row['order_id']; ?></span>
                            </td>
                            <td>
                                <span>₱<?php echo number_format($row['order_cost'], 2); ?></span>
                            </td>
                            <td>
                                <span><?php echo $row['order_status']; ?></span>
                            </td>
                            <td>
                                <span><?php echo $row['order_date']; ?></span>
                            </td>
                            <td>
                                <form method="POST" action="order_details.php">
                                    <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status">
                                    <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id">
                                    <input class="btn btn-success" name="order_details_btn" style="color: white; background-color: #5cb85c;" type="submit" value="Details">
                                </form>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>
    <!-- Processing Orders -->
    <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
        <table class="table table-striped mt-5 pt-5">
            <thead>
                <tr style="background-color: orange;">
                    <th style="background-color: orange; border: 1px solid #000;">Order ID</th>
                    <th style="background-color: orange; border: 1px solid #000;">Order Cost</th>
                    <th style="background-color: orange; border: 1px solid #000;">Order Status</th>
                    <th style="background-color: orange; border: 1px solid #000;">Order Date</th>
                    <th style="background-color: orange; border: 1px solid #000;">Order Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $row) {
                    if ($row['order_status'] == 'payment_processing') { ?>
                        <tr style="border: 1px solid black;">
                            <td style="text-align: center; background-color: #ffd699; border: 1px solid #000"><?php echo $row['order_id']; ?></td>
                            <td>₱<?php echo number_format($row['order_cost'], 2); ?></td>
                            <td><?php echo $row['order_status']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <form method="POST" action="order_details.php">
                                    <input type="hidden" value="<?php echo $row['order_status']; ?>" name="order_status">
                                    <input type="hidden" value="<?php echo $row['order_id']; ?>" name="order_id">
                                    <input class="btn btn-warning" name="order_details_btn" style="color: white; background-color: #ffbc00;" type="submit" value="Details">
                                </form>
                            </td>
                        </tr>
                    <?php } } ?>
            </tbody>
        </table>
    </div>


    
</div>



</section>

<script>
    let eyeiconPassword = document.getElementById("eyeicon-password");
    let passwordField = document.getElementById("account-password");
    let eyeiconConfirmPassword = document.getElementById("eyeicon-confirm-password");
    let confirmPasswordField = document.getElementById("account-confirm-password");

    eyeiconPassword.onclick = function () {
        if (passwordField.type === "password") {
          
            passwordField.type = "text";
            eyeiconPassword.classList.add("fa-eye");
            eyeiconPassword.classList.remove("fa-eye-slash");
        } else {
         
            passwordField.type = "password";
            eyeiconPassword.classList.add("fa-eye-slash");
            eyeiconPassword.classList.remove("fa-eye");
        }
    };
    eyeiconConfirmPassword.onclick = function () {
        if (confirmPasswordField.type === "password") {
           
            confirmPasswordField.type = "text";
            eyeiconConfirmPassword.classList.add("fa-eye");
            eyeiconConfirmPassword.classList.remove("fa-eye-slash");
        } else {
          
            confirmPasswordField.type = "password";
            eyeiconConfirmPassword.classList.add("fa-eye-slash");
            eyeiconConfirmPassword.classList.remove("fa-eye");
        }
    };


    let uppercaseCheck = document.getElementById("uppercaseCheck");
    let lowercaseCheck = document.getElementById("lowercaseCheck");
    let numberCheck = document.getElementById("numberCheck");
    let minLengthCheck = document.getElementById("minLengthCheck");
    let specialCharCheck = document.getElementById("specialCharCheck");

    function validatePasswords() {
    let password = passwordField.value;
    let confirmPassword = confirmPasswordField.value;

    // Check for at least 8 characters
    if (password.length >= 8) {
        minLengthCheck.style.color = 'green';
    } else {
        minLengthCheck.style.color = '';
    }

    // Check for uppercase letter
    if (/[A-Z]/.test(password)) {
        uppercaseCheck.style.color = 'green';
    } else {
        uppercaseCheck.style.color = '';
    }

    // Check for lowercase letter
    if (/[a-z]/.test(password)) {
        lowercaseCheck.style.color = 'green';
    } else {
        lowercaseCheck.style.color = '';
    }

    // Check for number
    if (/\d/.test(password)) {
        numberCheck.style.color = 'green';
    } else {
        numberCheck.style.color = '';
    }

    // Check for special character
    if (/[\W_]/.test(password)) {
        specialCharCheck.style.color = 'green';
    } else {
        specialCharCheck.style.color = '';
    }

    // Check if passwords match
    if (password === confirmPassword) {
        confirmPasswordField.style.borderColor = 'green'; 
    } else {
        confirmPasswordField.style.borderColor = ''; 
    }
}


passwordField.addEventListener('input', validatePasswords);
confirmPasswordField.addEventListener('input', validatePasswords);
</script>




<?php include_once('layouts/footer.php'); ?>