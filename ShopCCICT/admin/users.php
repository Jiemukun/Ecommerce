<?php
  $page_title = 'Admin';
  require_once('../includes/load.php');  
?>
<?php include_once('../admin_layouts/admin_header.php'); 

?>

<?php 
if (!isset($_SESSION['admin_logged_in'])){
  header('location: login.php');
  exit;
}
//
//include_once('session.php');
//


?>

<div class="container-fluid">
  <div class="row" style="min-height: 1000px">
    <?php include_once('side_menu.php'); ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Dashboard</h1>
      </div>
      <h2 class="mt-5 py-5">Create User</h2>
      <div class="table-responsive me-5">
        <div class="mx-auto container">
          <form id="create-user-form" method="POST" action="create_user.php">
            <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error']; } ?></p>
            
            <!-- User Name -->
            <div class="form-group mt-2">
              <label>User Name</label>
              <input type="text" class="form-control" name="user_name" placeholder="User Name" required>
            </div>

            <!-- User Email -->
            <div class="form-group mt-2">
              <label>Email</label>
              <input type="email" class="form-control" name="user_email" placeholder="Email" required>
            </div>

            <!-- User Password -->
            <div class="form-group mt-2">
              <label>Password</label>
              <input type="password" class="form-control" name="user_password" placeholder="Password" required>
            </div>

            <div class="form-group mt-3">
              <input type="submit" class="btn btn-success" name="create_user" value="Create User">
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</div>

<?php include_once('../admin_layouts/admin_footer.php'); ?>
