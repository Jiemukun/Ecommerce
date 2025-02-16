<?php
require_once('../includes/load.php');
//
//include_once('session.php');
//
if (isset($_POST['create_user'])) {
    
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

  
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    
    $query = "SELECT * FROM users WHERE user_email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        header('Location: create_user_form.php?error=Email already exists');
        exit;
    }

   
    $query = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $user_name, $user_email, $hashed_password);

    if ($stmt->execute()) {
        
        header('Location: user_dashboard.php');
        exit;
    } else {
        
        header('Location: create_user_form.php?error=Failed to create user');
        exit;
    }
}
?>
