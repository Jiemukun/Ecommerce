<?php
require_once('../includes/load.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    
    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header('Location: show_users.php?deleted_successfully=User deleted successfully');
        exit;
    } else {
        header('Location: show_users.php?deleted_error=Failed to delete user');
        exit;
    }
}
?>
