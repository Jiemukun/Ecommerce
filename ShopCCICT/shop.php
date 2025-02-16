<?php
  $page_title = 'Shop';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  
?>
<?php include_once('layouts/header.php'); ?>
<?php
include_once('sessionz.php');

if (isset($_POST['search'])) {
    $category = $_POST['category'];
    $price = $_POST['price'];

    
    if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no = $_GET['page_no'];
    }else{
        $page_no = 1;
    }

    $stmt1 = $db->prepare("SELECT COUNT(*) As total_records FROM products WHERE product_category=? AND product_price<=?");
    $stmt1->bind_param('si', $category, $price);
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    
    $total_records_per_page = 2;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;

    $adjacents = "2";

    $total_no_of_pages = ceil($total_records/$total_records_per_page);

    
    $stmt2 = $db->prepare("SELECT * FROM products WHERE product_category=? AND product_price<=? LIMIT $offset, $total_records_per_page");
    $stmt2->bind_param("si", $category, $price);
    $stmt2->execute();
    $products = $stmt2->get_result();



    
    if ($category == 'All') {
        
        $stmt = $db->prepare("SELECT * FROM products WHERE product_price <= ?");
        $stmt->bind_param("i", $price);  
    } else {
        
        $stmt = $db->prepare("SELECT * FROM products WHERE product_category = ? AND product_price <= ?");
        $stmt->bind_param("si", $category, $price);  
    }

    $stmt->execute();
    $products = $stmt->get_result();
} else {
    

    if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no = $_GET['page_no'];
    }else{
        $page_no = 1;
    }

    $stmt1 = $db->prepare("SELECT COUNT(*) As total_records FROM products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    
    $total_records_per_page = 2;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;

    $adjacents = "2";

    $total_no_of_pages = ceil($total_records/$total_records_per_page);

    
    $stmt2 = $db->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();
}

?>


<style>
    .pagination a{
        color: coral;
    }

    .pagination li:hover a{
        color: #fff;
        background-color: coral;
    }




#search {

position: fixed;

left: 0;

top: 0; 

height: 100vh; 

padding-top: 20px; 

padding-bottom: 20px; 

overflow-y: auto; 

z-index: 100; 

}




</style>





<section id="search" style="position: fixed; left: 0; top: -60px;" class="my-5 py-5 ms-2">
    <div class="container mt-5 py-5">
        <p>Search products</p>
        <hr>
    </div>

        <form action="shop.php" method="POST">
            <div class="row mx-auto container">
                <div class="col-lg-12 col-md-12 col-sm-12">

                <?php

$sql = "SELECT category_id, category_name FROM categories";
$result = $db->query($sql);
?>

<p style="margin-top: -50px;">Category</p>

<?php 

?>
<div class="form-check">
                <input class="form-check-input" value="All" type="radio" name="category" id="category_all" <?php if(isset($category) && $category == 'All'){echo 'checked'; }?>>
                <label class="form-check-label" for="category_all">
                    All
                </label>
            </div>

                    <?php

                    while ($row = $result->fetch_assoc()) {
                        $category_id = $row['category_id'];
                        $category_name = $row['category_name'];
                    ?>
                        <div class="form-check">
                            <input class="form-check-input" value="<?php echo $category_name; ?>" type="radio" name="category" id="category_<?php echo $category_id; ?>" <?php if(isset($category) && $category == $category_name){echo 'checked'; }?>>
                            <label class="form-check-label" for="category_<?php echo $category_id; ?>">
                                <?php echo $category_name; ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>

                        
                </div>
            </div>

            <div class="row mx-auto container mt-5">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <p>Price</p>
                    <input type="range" class="form-range w-100" name="price" value="<?php if(isset($price)){echo $price;}else{ echo"100";}?>" min="1" max="10000" id="customRange2" oninput="updatePriceStamp()">
                    
                    
                </div>
            </div>
            <div class="price-stamp" id="priceStamp">₱100</div>
            <div class="form-group my-3 mx-3">
                <input type="submit" name="search" value="Search" class="btn btn-primary">
            </div>

        </form>

</section>




<!-- 3RD SECTION -->
    <!-- Featured -->
    <section id="shop" class="my-5 py-5" >
        <div class="container text-center mt-5 py-5">
            <h3>Products</h3>
            <hr class="mx-auto">
            <p>Check our products</p>
        </div>
        <div class="row mx-auto container">

        <?php while($row = $products->fetch_assoc()) { ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12 ">
                <img class="img-fluid mb-3" src="../ShopCCICT/lib/img/<?php echo $row['product_image'];?>" />
                <!-- STAR  -->
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name'];?></h5>
                <h4 class="p-price">₱<?php echo number_format($row['product_price'], 2);?></h4>
                <a style="background-color: orange; text-decoration: none; color: black;" href="view_product.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-link">
                    Buy Now!
                </a>
            </div>
            
        <?php } ?>    



        <!--Page OR Pagination-->
        <nav aria-label="Page navigation example">
                <ul class="pagination mt-5">
                    <li class="page-item <?php if($page_no<=1){echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no <= 1) { echo '#'; }else{ echo "?page_no=".($page_no-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>

                    <?php if($page_no >=3) {?>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="<?php echo "?page_no=" . $page_no;?>"><?php echo ($page_no);?></a></li>
                    <?php } ?>


                    <li class="page-item <?php if($page_no >= $total_no_of_pages){ echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no >= $total_no_of_pages) {echo '#';} else{ echo "?page_no=" . ($page_no+1);}?>">Next</a></li>

                </ul>
            </nav>
        </div>
    </section>





<script>

    function updatePriceStamp() {
    const price = document.getElementById('customRange2').value;
    document.getElementById('priceStamp').innerText = '₱' + parseFloat(price).toFixed(2);
}

</script>




<?php include_once('layouts/footer.php'); ?>