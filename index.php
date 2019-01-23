
<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Baigiamasis</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ-Qxu9ZZ4ODH2UP8mDsls2olKychnmdc&callback=init"
    async defer></script>
    <script src="https://cdn.klokantech.com/maptilerlayer/v1/index.js"></script>
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
             <script>
      var map,pos,markers=[],info,myLocationMarker,zoomLevel=15;
      var pData=<?php 
                  if(isset($_GET)){
                echo json_encode($_GET);
              } ?>;
      function init(){
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 54.72247035, lng: 25.33771767051836},
          zoom: 15,
          controls: {
            zoom: true,
            myLocationButton : true    
        }
        });
        map.addListener('idle', function(e) {
          loadMarkers(pos);
          myPos();
          google.maps.event.clearListeners(map,'idle');
        });
            addYourLocationButton(map);
        
      }
       
      function loadMarkers(position){
        $.ajax({
        method:"post",
        dataType: "json" ,
          url: "reports_data.php",
        }).done(function(data) {
          $.each(data,function(i,place_data){
           var placePos={
              lat:parseFloat(place_data.latitude),
              lng:parseFloat(place_data.longitude)
            }
            var m=new google.maps.Marker({
              icon:'./report-marker.png',
              size: new google.maps.Size(130, 30),
              anchor: new google.maps.Point(15, 15),
              map:map,
              draggable:false,
              title: place_data.title,
              position:placePos
            });
            markers.push(m);
            var content='<div class="row" style="margin:5px;"><h1 class="col-12">'+place_data.title+'</h1><p class="col-12">'+place_data.info+'</p><img class="col-12" src="./report_images/'+place_data.image+'" style="width:300px;height:auto;"></img><div class="col-12"><button class="btn btn-success" >Koreguoti</button><button class="btn btn-default" >Dalintis</button><button class="btn btn-danger" value="'+place_data.id+'">Trinti</button></div></div>';
            var i=new google.maps.InfoWindow({
              content:content
            });
            google.maps.event.addListener(i,'closeclick',function(){
              console.log('hi');
                if(map.getZoom()>zoomLevel){
                  smoothZoom(map,zoomLevel,map.getZoom(),'out');
                }
            });
            m.addListener('click',function(){
              i.open(map,m);
              zoomLevel=map.getZoom();
              
              const plPos=placePos;
              console.log(plPos);
              smoothZoom(map,20,map.getZoom(),'in',plPos);
              //map.setCenter(myPos);
            });
          });
        });
      }
      

    function myPos(){
     var errInfo = new google.maps.InfoWindow;
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
          var   myPos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            myLocationMarker=new google.maps.Marker({
              id:'myLocationMarker',
              map:map,
              draggable:false,
              zIndex:99999999,
              animation: google.maps.Animation.DROP,
              icon:'./me-marker.png',
              position:myPos
            });
            map.setCenter(myPos);
            smoothZoom(map,20,map.getZoom(),'in');
          }, function() {
            locErrHandler(true, errInfo, map.getCenter());
            return null;
          });
        } else {
          locErrHandler(false, errInfo, map.getCenter());
          return null;
        }
      function locErrHandler(browserHasGeolocation, errInfo, myPos) {
        errInfo.setContent(browserHasGeolocation ?'Klaida geolokacija nerasta' : 'Klaida: naršyklė geolokacijos nepalaiko');
        errInfo.setPosition(myPos);
        errInfo.open(map);
      }
    }
    function getUrlParams(){
      var paramsObj={};
      var url=window.location.search.substr(1);
      if(url){
        url=url.split('#')[0];
        urlArr=url.split(/[&;]/);
        $.each(urlArr,function(k,v){
          var paramData=v.split('=');
          var paramKey=paramData[0];
          var paramValue=typeof(paramData[1])!=='undefined' ? paramData[1] : null ;
          if(paramKey.match(/\[(\d+)?\]$/)){
            var paramArrKey=paramKey.replace(/\[(\d+)?\]/,'');
            if(!paramsObj[paramArrKey])paramsObj[paramArrKey]=[];
              if (paramKey.match(/\[\d+\]$/)) {
                var arrIndex= /\[(\d+)\]/.exec(paramKey)[1];
                paramsObj[paramArrKey][arrIndex]=paramValue;
              }else{
                paramsObj[paramArrKey].push(paramValue);
              }
          }else{
            if(!paramsObj[paramKey]){
              paramsObj[paramKey]=paramValue;
            } else if (paramsObj[paramKey] && typeof paramsObj[paramKey] === 'string'){
              paramsObj[paramKey]=[paramsObj[paramKey]];
              paramsObj[paramKey].push(paramValue);
            } else{
              paramsObj[paramKey].push(paramValue);
            }
          }
        });
      }
      return paramsObj;
    }
    function addYourLocationButton (map){
    var controlDiv = document.createElement('div');
    var firstChild = document.createElement('button');
    firstChild.style.backgroundColor = '#fff';
    firstChild.style.border = 'none';
    firstChild.style.outline = 'none';
    firstChild.style.width = '28px';
    firstChild.style.height = '28px';
    firstChild.style.borderRadius = '2px';
    firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
    firstChild.style.cursor = 'pointer';
    firstChild.style.marginRight = '10px';
    firstChild.style.padding = '0';
    firstChild.title = 'Your Location';
    controlDiv.appendChild(firstChild);

    var secondChild = document.createElement('div');
    secondChild.style.margin = '5px';
    secondChild.style.width = '18px';
    secondChild.style.height = '18px';
    secondChild.style.backgroundImage = 'url(./mylocation-sprite-2x.png)';
    secondChild.style.backgroundSize = '180px 18px';
    secondChild.style.backgroundPosition = '0 0';
    secondChild.style.backgroundRepeat = 'no-repeat';
    firstChild.appendChild(secondChild);

    google.maps.event.addListener(map, 'center_changed', function () {
        secondChild.style['background-position'] = '0 0';
    });

    firstChild.addEventListener('click', function () {
        var imgX = 0,
            animationInterval = setInterval(function () {
                imgX = -imgX - 18 ;
                secondChild.style['background-position'] = imgX+'px 0';
            }, 500);

        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(latlng);
                smoothZoom(map,20,map.getZoom(),'in');
                clearInterval(animationInterval);
                secondChild.style['background-position'] = '-144px 0';
               
              if(myLocationMarker!= null  && myLocationMarker!=undefined){
                myLocationMarker.setMap(null);
                myLocationMarker=undefined;
                myLocationMarker=new google.maps.Marker({
              id:'myLocationMarker',
              map:map,
              zIndex:99999999,
              draggable:false,
              animation: google.maps.Animation.DROP,
              icon:'./me-marker.png',
              position:latlng
            });
            markers.push(myLocationMarker);
              }else{
              myLocationMarker=new google.maps.Marker({
              id:'myLocationMarker',
              zIndex:99999999,
              map:map,
              draggable:false,
              animation: google.maps.Animation.DROP,
              icon:'./me-marker.png',
              position:latlng
            });
            markers.push(myLocationMarker);
              }
            });
        } else {
            clearInterval(animationInterval);
            secondChild.style['background-position'] = '0 0';
        }
    });

    controlDiv.index = 1;
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}
function smoothZoom (map, toZoom, currentZoom,direction,pos) {
  //debugger;
    if(direction==undefined) direction='in';
    if ((currentZoom >= toZoom &&direction=='in')||(currentZoom <= toZoom &&direction=='out')) {
        if(pos!=undefined){
          var tmp=pos;
          //tmp.lat+=parseFloat(0.0002);
          map.setCenter({lat:pos.lat+parseFloat(0.000255),lng:pos.lng});
        }
        return;
    }
    else {
        zoomListener = google.maps.event.addListener(map, 'zoom_changed', function(event){
            google.maps.event.removeListener(zoomListener);
            smoothZoom(map, toZoom,(direction=='in')?currentZoom + 1:currentZoom-1,direction,pos);
        });
        setTimeout(function(){map.setZoom(currentZoom)}, 80);
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