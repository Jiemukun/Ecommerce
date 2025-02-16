<?php
  $page_title = 'Edit User';
  require_once('../includes/load.php');
?>

<?php include_once('../admin_layouts/admin_header.php'); ?>

<?php 
if (!isset($_SESSION['admin_logged_in'])){
  header('location: login.php');
  exit;
}
//
//include_once('session.php');
//

if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if(!$user) {
        
        header('Location: show_users.php');
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT); 

    
    $update_stmt = $db->prepare("UPDATE users SET user_name = ?, user_email = ?, user_password = ? WHERE user_id = ?");
    $update_stmt->bind_param("sssi", $user_name, $user_email, $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        
        header('Location: show_users.php?edit_success_message=User updated successfully');
        exit;
    } else {
        
        header('Location: show_users.php?edit_error_message=Failed to update user');
        exit;
    }
}
?>

<div class="container-fluid">
  <div class="row" style="min-height: 1000px">
    <?php include_once('side_menu.php'); ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Edit User</h1>
      </div>

      <h2 class="mt-5 py-5">Edit User Details</h2>

      <?php if(isset($_GET['edit_success_message'])) { ?>
        <p class="text-center" style="color: green;"><?php echo $_GET['edit_success_message'];?></p>
      <?php } ?>

      <?php if(isset($_GET['edit_error_message'])) { ?>
        <p class="text-center" style="color: red;"><?php echo $_GET['edit_error_message'];?></p>
      <?php } ?>

      <div class="table-responsive me-5">
        <form method="POST" action="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="container">
          <div class="form-group mt-2">
            <label for="user_name">User Name</label>
            <input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo $user['user_name']; ?>" required>
          </div>

          <div class="form-group mt-2">
            <label for="user_email">Email</label>
            <input type="email" class="form-control" name="user_email" id="user_email" value="<?php echo $user['user_email']; ?>" required>
          </div>

          <div class="form-group mt-2">
            <label for="user_password">Password</label>
            <input type="password" class="form-control" name="user_password" id="user_password" placeholder="Enter new password" required>
          </div>

          <div class="form-group mt-3">
            <input type="submit" class="btn btn-success" value="Update User">
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<?php include_once('../admin_layouts/admin_footer.php'); ?>
