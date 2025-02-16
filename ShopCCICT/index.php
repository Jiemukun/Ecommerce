<?php
  $page_title = 'Home';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  
?>
<?php include_once('layouts/header.php'); ?>
<?//php include_once('sessionz.php'); ?>
<!-- Home -->
<section id="home">
    <div class="container" style="color: orange;">
        <h5>New Products</h5>
        <h1>Best Prices</h1>
        <p>Keyboard warriors - CCICT</p>
        <a href="shop.php"><button>Shop Now!</button></a>
    </div>
</section>





<?php include_once('layouts/footer.php'); ?>