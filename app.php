
<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Žemėlapis</title>
    <link rel="icon" type="image/x-icon" href="/faviconn.ico" />
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    -->
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
    </script> 
  </head>
    <body>
    <?php
      include_once("navbar.php");
    ?>
      <div id="street-view"></div>
      <div id="map"></div>
      <script src="/markerClusterer.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ-Qxu9ZZ4ODH2UP8mDsls2olKychnmdc&callback=init"
    async defer></script>

      <script src="/app.js"></script>  
      <?php
        if(isset($_SESSION['response'])){
          echo $_SESSION['response'];
          unset($_SESSION['response']);
        }
      ?>
  </body>
</html>

