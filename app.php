
<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php 
    echo "Žemėlapis";
   /* if(isset($_GET['title'])){
      echo str_replace('_',' ',$_GET['title']);
    }else{
     echo "Žemėlapis";
    }*/
    ?></title>
    <link rel="icon" type="image/x-icon" href="/faviconn.ico" />
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <script src="/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/style.css">

    <script src="/toastmsg.js"></script> 
             <script>
             //80
      var urlData=<?php 
                  if(isset($_GET)){
                echo json_encode($_GET);
              };?>;
            console.log(urlData);
    </script> 
  </head>
    <body>
    <?php
      include_once("navbar.php");
    ?>
      <div id="street-view"></div>
      <div id="map"></div>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ-Qxu9ZZ4ODH2UP8mDsls2olKychnmdc&callback=init"
    async defer></script>
      <script src="/app.js"></script>  
  </body>
</html>

