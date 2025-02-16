<?php
  $page_title = 'Login';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<?php

if(isset($_SESSION['logged_in'])){
    header('location: account.php');
    exit;
}


if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $stmt = $db->prepare("SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email = ? LIMIT 1");
    $db->bind_param($stmt, 's', $email); 

   
    if ($db->execute($stmt)) {
        $result = $db->fetch_assoc_result($stmt);

       
        if ($result) {
            
            if (password_verify($password, $result['user_password'])) {
                
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['user_name'] = $result['user_name'];
                $_SESSION['user_email'] = $result['user_email'];
                $_SESSION['logged_in'] = true;
                //TIMEOUT FOUL!!!
                // $_SESSION['last_activity'] = time();
                //
                header('location: account.php?login_success=Logged in successfully');
            } else {
                
                header('location: login.php?error=Invalid credentials');
            }
        } else {
            
            header('location: login.php?error=Invalid credentials');
        }
    } else {
        
        header('location: login.php?error=Something went wrong');
    }
}
?>


<!-- Login --> 
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Login</h2>
        <hr class="mx-auto">

    </div>  

    <div class="mx-auto container">
        <form id="login-form" method="POST" action="login.php">
            <p style="color: red;" class="text-center"><?php if(isset($_GET['error'])) {echo $_GET['error']; }?></p>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label>Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-password"></i>
                <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <input type="submit" class="btn" id="login-btn" name="login_btn" value="Login">
            </div>


            <div class="form-group">
                <a id="register-url" class="btn" href="register.php">Don't have account? Register</a>
            </div>

        </form>
    </div>
</section>


<script>
    let eyeiconPassword = document.getElementById("eyeicon-password");
    let passwordField = document.getElementById("login-password");

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
   
</script>




<!-- <?php include_once('layouts/footer.php'); ?> -->