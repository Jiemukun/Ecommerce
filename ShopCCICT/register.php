<?php
  $page_title = 'Register';
  require_once('includes/load.php');

  include_once('layouts/header.php'); 
?>


<?php




if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: account.php'); 
    exit;
}


if(isset($_POST['register'])){
    
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    
    if($password !== $confirmPassword){
        header('location: register.php?error=Passwords do not match');
        exit; 
    }

    
    elseif(strlen($password) < 8) {
        header('location: register.php?error=Passwords must be at least 8 characters');
        exit;
    }

    elseif(!preg_match('/[A-Za-z]/', $password) || 
           !preg_match('/[0-9]/', $password) || 
           !preg_match('/[\W_]/', $password)) 
        { 
            header('location: register.php?error=Password must contain at least 1 uppercase letter, 1 number, and 1 special character');
        exit;
        }

    
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: register.php?error=Invalid email format');
        exit;
    }

    
    else {
        
        $stmt1 = $db->prepare("SELECT count(*) FROM users WHERE user_email = ?");
        $db->bind_param($stmt1, 's', $email);
        $result = $db->execute($stmt1);
        $num_rows = $db->fetch_result($result)->fetch_row()[0];

        if($num_rows != 0) {
            header('location: register.php?error=User with this email already exists');
            exit;
        } else {
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            
            $stmt = $db->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)");
            $db->bind_param($stmt, 'sss', $name, $email, $hashedPassword);
            if($db->execute($stmt)){
                $user_id = $stmt->insert_id;
                $_SESSION['user_id'] = $user_id;

                
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $name;
                $_SESSION['logged_in'] = true;
                header('location: account.php?register_success=You registered successfully');
            }else{
                
                header('location: register.php?error=Could not create account an the moment');
                exit;
            }
        }
    }

}elseif(isset($_SESSION['logged_in'])){  
    header('location: account.php');
    exit;
}
?>



<!-- Register -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Register</h2>
        <hr class="mx-auto">
    </div>  

    <div class="mx-auto container">
        <form id="register-form" method="POST" action="register.php">
            <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; }?></p>
            
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" id="register-email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label>Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-password"></i> 
                <div class="input-container" >
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label><i class="fas fa-eye-slash" style="cursor: pointer;" id="eyeicon-confirm-password"></i>
                <div class="input-container">
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required>
                </div>
            </div>  
            <div class="icon-container mb-3">
                <i class="fas fa-check-circle" id="emailCheck"> Email must be real</i><br>
                <i class="fas fa-check-circle" id="uppercaseCheck">At least 1 uppercase letter</i><br>
                <i class="fas fa-check-circle" id="lowercaseCheck">At least 1 lowercase letter</i><br>
                <i class="fas fa-check-circle" id="minLengthCheck">At least 8 characters</i><br>
                <i class="fas fa-check-circle" id="numberCheck">At least 1 number (0-9)</i><br>
                <i class="fas fa-check-circle" id="specialCharCheck">At least 1 special symbol (!, ., $, etc.)</i>
            </div>

            <div class="form-group">
                <input type="submit" class="btn" id="register-btn" name="register" value="Register">
            </div>

            <div class="form-group">
                <a id="login-url" class="btn" href="login.php">Do you have an account? Login</a>
            </div>

        </form>
    </div>
</section>


<script>
    
    let eyeiconPassword = document.getElementById("eyeicon-password");
    let passwordField = document.getElementById("register-password");
    let eyeiconConfirmPassword = document.getElementById("eyeicon-confirm-password");
    let confirmPasswordField = document.getElementById("register-confirm-password");
    let emailField = document.getElementById("register-email");
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


let emailCheck = document.getElementById("emailCheck");
let uppercaseCheck = document.getElementById("uppercaseCheck");
let lowercaseCheck = document.getElementById("lowercaseCheck");
let numberCheck = document.getElementById("numberCheck");
let minLengthCheck = document.getElementById("minLengthCheck");
let specialCharCheck = document.getElementById("specialCharCheck");

function validateEmail() {
    let email = emailField.value;

    
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    //const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{3,}$/;  /.com

    if (emailRegex.test(email)) {
        emailCheck.style.color = 'green'; 
    } else {
        emailCheck.style.color = ''; 
    }
}
emailField.addEventListener('input', validateEmail);

function validatePasswords() {
    let password = passwordField.value;
    let confirmPassword = confirmPasswordField.value;

    
    if (password.length >= 8) {
        minLengthCheck.style.color = 'green';
    } else {
        minLengthCheck.style.color = '';
    }

    
    if (/[A-Z]/.test(password)) {
        uppercaseCheck.style.color = 'green';
    } else {
        uppercaseCheck.style.color = '';
    }

    
    if (/[a-z]/.test(password)) {
        lowercaseCheck.style.color = 'green';
    } else {
        lowercaseCheck.style.color = '';
    }

    
    if (/\d/.test(password)) {
        numberCheck.style.color = 'green';
    } else {
        numberCheck.style.color = '';
    }

    
    if (/[\W_]/.test(password)) {
        specialCharCheck.style.color = 'green';
    } else {
        specialCharCheck.style.color = '';
    }

    
    if (password === confirmPassword) {
        confirmPasswordField.style.borderColor = 'green'; 
    } else {
        confirmPasswordField.style.borderColor = ''; 
    }
}


passwordField.addEventListener('input', validatePasswords);
confirmPasswordField.addEventListener('input', validatePasswords);


</script>

<!-- <?php include_once('layouts/footer.php'); ?> -->
