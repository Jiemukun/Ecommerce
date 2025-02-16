<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "CCICT";?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="../lib/css/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-black py-1 fixed-top" >
    <div class="container" style="color: white; ">
        <img src="../lib/img/ccictnobg.png" class="navbar-logo" alt="Logo"/> CCICT
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
        
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) { ?>
                    <li class="nav-item">
                        <a class="nav-link" style="color: white;" href="logout.php?logout=1">Logout</a>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </div>
</nav>
