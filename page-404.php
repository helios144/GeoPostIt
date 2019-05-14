<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Baigiamasis</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/style.css"> 
    <style>
        .user-name{
        position: relative;
        display: block;
        padding: 15px 15px;
        }
        #navbar {
            margin:0;
            padding:0;
        }
        #map {
            height: calc(100% - 50px);
            margin:0;
            padding:0;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #navbar div,a,p{
            color:black;
        }
        #navbar a:hover{
            font-weight: 600;
            color:black;
        }
        .navbar-brand{
            font-size:16px;
        }
      </style>
    </head>
    <body>
        <?php
        include_once("navbar.php");
        ?>
        <div class="container home-bg">
        <div style="height:20%;display:inline-block;width:100%"></div>
            <h1 class="text-center">Puslapis nerastas</h1>
            <a href="/" style="color:blue">Grįžti į pagrindinį puslapį</a>
        </div>
       

    </body>
</html>