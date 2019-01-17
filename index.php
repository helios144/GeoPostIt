
<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ-Qxu9ZZ4ODH2UP8mDsls2olKychnmdc&callback=init"
    async defer></script>
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
        height: 95%;
        margin:0;
        padding:0;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
             <script>
      var map,pos,markers,info;
      function init(){
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 54.72247035, lng: 25.33771767051836},
          zoom: 15
        });
        myPos();
        map.addListener('idle', function(e) {
          loadMarkers(pos);
          google.maps.event.clearListeners(map,'idle');
        });
      }
      function loadMarkers(position){
        $.ajax({
        method:"post",
        dataType: "json" ,
          url: "reports_data.php",
        }).done(function(data) {
          $.each(data,function(i,place_data){
            var m=new google.maps.Marker({
              map:map,
              draggable:false,
              title: place_data.title,
              position:{lat:parseFloat(place_data.latitude),lng:parseFloat(place_data.longitude)}
            });
            var content='<div class="row" style="margin:5px;"><h1 class="col-12">'+place_data.title+'</h1><p class="col-12">'+place_data.info+'</p><img class="col-12" src="./report_images'+place_data.image+'" style="width:300px;height:auto;"></img><div class="col-12"><button class="btn btn-success" >Koreguoti</button><button class="btn btn-default" >Dalintis</button><button class="btn btn-danger" value="'+place_data.id+'">Trinti</button></div></div>';
            var i=new google.maps.InfoWindow({
              content:content
            });
            m.addListener('click',function(){
              i.open(map,m);
            });
          });
        });
      }
      

    function myPos(){
      infoWindow = new google.maps.InfoWindow;
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
             pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
         /*   infoWindow.setPosition(pos);
            infoWindow.setContent('<div class="row" style="margin:5px;"><h1 class="col-12">Gatvės muzikantas</h1><p class="col-12">Šauniai grojantis muzikantas</p><img class="col-12" src="./report_images/img1.jpg" style="width:300px;height:auto;"></img><div class="col-12"><button class="btn btn-success">Koreguoti</button><button class="btn btn-default">Dalintis</button><button class="btn btn-danger">Trinti</button></div></div>');
            var marker = new google.maps.Marker({
          position: pos,
          map: map,
        });
            infoWindow.open(map);*/
            map.setCenter(pos);
            
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }
    }
    </script> 
  </head>
    <body>
    <?php
      include_once("navbar.php");
    ?>
      
      <div id="map"></div>

  </body>
</html>