<?php
  $page_title = 'Admin Dashboard';
  require_once('../includes/load.php');  
  include_once('../admin_layouts/admin_header.php');  
  //
  //include_once('session.php');
  //
  if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
  }

  // total sales for 'Not Paid' orders
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status = 'unpaid'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_not_paid = $data['total_sales'] ? $data['total_sales'] : 0;

  // total sales for 'Paid' orders
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status = 'paid'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_paid = $data['total_sales'] ? $data['total_sales'] : 0;


  // total sales all order status(ALL-TIME)
  $stmt = $db->prepare("SELECT SUM(order_cost) AS total_sales FROM orders WHERE order_status IN ('unpaid', 'paid')");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales = $data['total_sales'] ? $data['total_sales'] : 0;



  //count

  // total sales for 'Not Paid' orders
  $stmt = $db->prepare("SELECT COUNT(order_cost) AS total_sales FROM orders WHERE order_status = 'unpaid'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_not_paid_count = $data['total_sales'] ? $data['total_sales'] : 0;

  // total sales for 'Paid' orders
  $stmt = $db->prepare("SELECT COUNT(order_cost) AS total_sales FROM orders WHERE order_status = 'paid'");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_paid_count = $data['total_sales'] ? $data['total_sales'] : 0;


  // total sales all order status(ALL-TIME)
  $stmt = $db->prepare("SELECT COUNT(order_cost) AS total_sales FROM orders WHERE order_status IN ('unpaid', 'paid')");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_count = $data['total_sales'] ? $data['total_sales'] : 0;

  //
  $stmt = $db->prepare("SELECT COUNT(order_cost) AS total_sales FROM orders WHERE order_status IN ('payment_processing')");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $total_sales_countp = $data['total_sales'] ? $data['total_sales'] : 0;
?>

<div class="container-fluid">
  <div class="row">
    <?php include_once('side_menu.php'); ?>  
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">Dashboard</h1>
      </div>

      <h2 class="mt-4 py-4">Sales Overview</h2>

      
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Total Sales (All Time)</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--  'Not Paid' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Unpaid</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales_not_paid, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--  for 'Paid' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Paid</h5>
            </div>
            <div class="card-body">
              <p><strong>₱<?php echo number_format($total_sales_paid, 2); ?></strong></p>
            </div>
          </div>
        </div>
      </div>



<br>
<!-- -->
<div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Total Sales (All Time)</h5>
            </div>
            <div class="card-body">
              <p><strong><?php echo number_format($total_sales_count, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--  'Not Paid' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Unpaid</h5>
            </div>
            <div class="card-body">
              <p><strong><?php echo number_format($total_sales_not_paid_count, 2); ?></strong></p>
            </div>
          </div>
        </div>

        <!--  for 'Paid' orders -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>Sales - Paid</h5>
            </div>
            <div class="card-body">
              <p><strong><?php echo number_format($total_sales_paid_count, 2); ?></strong></p>
            </div>
          </div>
        </div>
      </div>

<br>
      <!--  for 'processing' orders -->
      <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <h5>Processing</h5>
            </div>
            <div class="card-body">
              <p><strong><?php echo number_format($total_sales_countp, 2); ?></strong></p>
            </div>
          </div>
        </div>
      </div>

    


<?php include_once('../admin_layouts/admin_footer.php'); ?>