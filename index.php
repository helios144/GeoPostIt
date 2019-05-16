<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>GeoPostIT</title>
    <link rel="icon" type="image/x-icon" href="/faviconn.ico" />
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="/toastmsg.js"></script>
</head>
    <body>
    <?php
      include_once("navbar.php");
    ?>
      <div class="container home-bg">
          <div style="height:20%;display:inline-block;width:100%"></div>
          <div class="search">
            <h1>GeoPostIT</h1>
            <form action="/posts" method="get" class="form-group">
            <input type="text" name="search_query" class="form-control" placeholder="Search"></input>
            <select name="category" class="form-control">
              <option value="all" disabled selected>Select category</option>
              <?php
                  require('database_credentials.php');
                  $con = new mysqli($servername, $username, $password, $dbname);
                  if ($con->connect_error) {
                      die('<option value="all">All<i class="glyphicon glyphicon-globe"></></option>');
                  } 
                  $sql="SELECT category,icon FROM categories";
                  $cats=mysqli_query($con, $sql);
                  $cats=mysqli_fetch_all($cats,MYSQLI_ASSOC);
                  foreach($cats as $val){
                    echo '<option data-icon="<i class=\''.$val['icon'].'\'></i>" value="'.$val['category'].'">'.ucwords($val['category']).'</option>';
                  }
              ?>
            </select>
            <button type="submit" class="btn btn-success" style="min-width:100px;min-height:50px;">Search</button>
            </form>
            <a href="/posts"><button class="btn btn-default"style="margin-top:20px;width:150px;height:70px">Explore <i class="glyphicon glyphicon-globe"></i></button></a>
          </div>
      </div>
  </body>
</html>