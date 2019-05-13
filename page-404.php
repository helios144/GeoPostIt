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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
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
        <div class="container">
            <h1 class="text-center">Puslapis nerastas</h1>
            <a href="/index.php">Grįžti į pagrindinį puslapį</a>
        </div>
       

    </body>
</html>