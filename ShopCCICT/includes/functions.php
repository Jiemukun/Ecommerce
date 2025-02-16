<?php 
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}


function remove_junk($str){
    $str = nl2br($str);
    $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
    return $str;
  }



  function updateStockQuantity($order_details) {
    global $db;

    
    foreach ($order_details as $item) {
        $product_id = $item['product_id'];
        $product_quantity = $item['product_quantity'];

        
        $stmt = $db->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        
        
        if ($stmt === false) {
            die("Error preparing the statement: " . $db->con->error);
        }

        
        $stmt->bind_param("ii", $product_quantity, $product_id);

        
        if (!$stmt->execute()) {
            die("Error executing the statement: " . $stmt->error);
        }
    }
}


function calculateReorderLevel($product_id, $db) {
  
  $stmt = $db->prepare("SELECT SUM(oi.product_quantity) AS total_sales, 
                               DATEDIFF(CURDATE(), MIN(oi.order_date)) AS days_since_first_sale
                        FROM order_items oi
                        WHERE oi.product_id = ?");
  $stmt->bind_param('i', $product_id);
  $stmt->execute();
  $stmt->bind_result($total_sales, $days_since_first_sale);
  $stmt->fetch();
  $stmt->close();

  // Calculate average demand per day
  if ($days_since_first_sale > 0) {
      $average_demand_per_day = $total_sales / $days_since_first_sale;
  } else {
      $average_demand_per_day = 0;
  }

  
  $lead_time_days = 30;

  // Calculate reorder level
  return $average_demand_per_day * $lead_time_days;
}
?>