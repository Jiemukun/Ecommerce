<?php
session_start();
    
if(isset($_GET['logout']) && $_GET['logout'] == 1){
    if(isset($_SESSION['admin_logged_in'])){
        // unset($_SESSION['admin_logged_in']);
        // unset($_SESSION['admin_email']);
        // unset($_SESSION['admin_name']);
        session_start();  
        session_unset();  
        session_destroy(); 
        header('location: login.php');
        exit;
    }
}

?>