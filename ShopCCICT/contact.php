<?php
  $page_title = 'Contact';
  require_once('includes/load.php');
  //if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  
?>
<?php include_once('layouts/header.php'); ?>
<?php //include_once('sessionz.php'); ?>


<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Contact</h2>
        <hr class="mx-auto">

    </div>  

    <div class="mx-auto container text-center">
        <form id="contact-form" action="">
        <i class="fab fa-youtube" style="color: red; font-size: 20px;"></i><label style="font-size: 20px;">&nbsp;CTU</label><br>
            <label style="font-size: 20px;">Contact us: 123456789</label> <br>
            <label style="font-size: 20px;">Email: CTU@gmail.com</label>     

           

        </form>
    </div>
</section>










<?php include_once('layouts/footer.php'); ?>