<?php
require_once('load.php');  


$stmt = $db->query("SELECT * FROM products WHERE product_category='Featured' LIMIT 4");


$featured_products = $db->while_loop($stmt);



//clothes
$stmt = $db->query("SELECT * FROM products WHERE product_category='Clothes' LIMIT 4");
$clothes = $db->while_loop($stmt);


?>
