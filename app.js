var map,markers=[],info,myLocationMarker,zoomLevel=15,onceOpened=false;
var infowindow,createPostMarker;
var createPostInfoWindow;
var bounds={};  

//core functions
function init(){
    console.log(urlData);
map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 54.72247035, lng: 25.33771767051836},
    zoom: 12,
    //streetView: new google.maps.StreetViewPanorama(document.getElementById("street-view")),
    controls: {
    scrollwheel: true,
    zoom: true,
    myLocationButton : true ,
    createPost: true,
    //streetView:true
    }
});
var panorama = new google.maps.StreetViewPanorama(
    document.getElementById('street-view'),
    {
        position: {lat: 37.869260, lng: -122.254811},
        pov: {heading: 165, pitch: 0}
    });
    map.setStreetView(panorama);
map.addListener('tilesloaded', (e)=> {
    if(urlData.report_id!=undefined && urlData.report_id!=null){
    bounds["report_id"]=parseInt(urlData.report_id);
    }
    loadMarkers();
    google.maps.event.clearListeners(map,'tilesloaded');
    google.maps.event.addListener(map, 'bounds_changed', ()=> {
    map.addListener('idle', (e)=> {

    loadMarkers();
    google.maps.event.clearListeners(map,'idle');
    });
    
});
});
    addYourLocationButton(map);
    addCreatePostButton(map);
}
function loadMarkers(){
toast({
    loadingSpinner:true,
    spinnerPosition:'spinner-only',
    id:"markers",
    autoRemove:false
});
bounds={
    NElat:map.getBounds().getNorthEast().lat(),
    NElng:map.getBounds().getNorthEast().lng(),
    SWlat:map.getBounds().getSouthWest().lat(),
    SWlng:map.getBounds().getSouthWest().lng()
};
if(urlData.category!=undefined && urlData.category!=null){
    bounds["category"]=urlData.category;
}
if(urlData.search_query!=undefined && urlData.search_query!=null){
    bounds["search_query"]=urlData.search_query;
}
console.log(bounds);
$.ajax({
method:"post",
dataType: "json" ,
    url: "/reports_data.php",
    data:bounds,
    success:(data)=>{
    if(data.status_code!=null&&data.status_code!=undefined){
        toast({
        text:data.status_message,
        icon:'info',
        position:'middle',
        duration:3000
        });
        toast("markers");
    }else{
        $.each(data,(i,report_data)=>{
            if(!markers[report_data.report_id]){
            var reportPos={
                lat:parseFloat(report_data.latitude),
                lng:parseFloat(report_data.longitude)
                }
                if(report_data.report_id==urlData.report_id) map.setCenter(reportPos);
                var m=new google.maps.Marker({
                icon:{url:'/report-marker.png',
                scaledSize: new google.maps.Size(30, 30),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(15, 15)
                },
                map:map,
                draggable:false,
                title: report_data.title,
                position:reportPos
                });
                google.maps.event.addListener(m,'click',()=>{
                // history.pushState(null,null,"?title="+report_data.title.replace(' ','_')+"&report_id="+report_data.report_id);
                history.pushState(null,report_data.title,"/post/"+report_data.report_id+"/"+report_data.title.replace(' ','_'));
                });
                //markers.push(m);
                markers[report_data.report_id]=m;
                //var content='<div class="row" style="margin:5px;"><h1 class="col-12">'+report_data.title+'</h1><p class="col-12">'+report_data.info+'</p><img class="col-12" src="/report_images/'+report_data.image+'" style="width:300px;height:auto;"></img><div class="col-12"><button class="btn btn-success" >Koreguoti</button><button class="btn btn-default" >Dalintis</button><button class="btn btn-danger" value="'+report_data.id+'">Trinti</button><button class="btn fa fa-copy" onClick="copyURLToClipBoard()"></button><button type="button" class="btn btn-default" onClick="navigate(\'https://www.google.com/maps/search/?api=1&query='+reportPos.lat+','+reportPos.lng+'\')">Navigate</button></div></div>';
                var content=report_data.content;
                var i=new google.maps.InfoWindow({
                content:content
                });
                google.maps.event.addListener(i,'closeclick',()=>{
                    if(map.getZoom()>zoomLevel){
                    smoothZoom(map,zoomLevel,map.getZoom(),'out',reportPos,0.000255);
                    }
                });
                m.addListener('click',()=>{
                i.open(map,m);
                const tmpPos=reportPos;
                smoothZoom(map,20,map.getZoom(),'in',tmpPos,0.000255);
                });
        }
        });
        toast("markers");
    }
    },
    error:()=>{
    toast({
        icon:'error',
        text:'Nepavyko įkelti duomenų'
    });
    toast("markers");
    }
}).done(()=>{
    
    if(onceOpened==false){
    if(urlData!= null &&urlData!= undefined &&urlData.length!=0){
    myPos();
    openReportFromUrl(urlData);
    }else{
    myPos(true);
    }
    onceOpened=true;
    }
});

}      
function myPos(toZoom){
    if(toZoom==null||toZoom==undefined)toZoom==false;
    //var errInfo = new google.maps.InfoWindow;
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position)=> {
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
        icon:'/me-marker.png',
        position:myPos
        });
        myLocationMarker.addListener('click',()=>{ 
        smoothZoom(map,20,map.getZoom(),'in',myPos);
        });
        if(toZoom==true) smoothZoom(map,20,map.getZoom(),'in',myPos);
    }, ()=> {
        locErrHandler(true, /*errInfo,*/ map.getCenter());
        return null;
    });
    } else {
        locErrHandler(false, /*errInfo,*/ map.getCenter());
        return null;
    }
    function locErrHandler(browserHasGeolocation, /*errInfo, myPos*/) {
    (browserHasGeolocation)?toast({icon:'error',position:'middle',text:'Klaida geolokacija nerasta',duration:5000}):toast({icon:'error',position:'middle',text:'Klaida: naršyklė geolokacijos nepalaiko',duration:5000});
    }
}
   
// button functions
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
    secondChild.style.backgroundImage = 'url(/mylocation-sprite-2x.png)';
    secondChild.style.backgroundSize = '180px 18px';
    secondChild.style.backgroundPosition = '0 0';
    secondChild.style.backgroundRepeat = 'no-repeat';
    firstChild.appendChild(secondChild);

    google.maps.event.addListener(map, 'center_changed', function () {
        secondChild.style['background-position'] = '0 0';
    });

    firstChild.addEventListener('click', function () {
      toast({
        text:"My location",
        icon:'info',
        position:'middle',
        duration:1000
      });
        var imgX = 0,
            animationInterval = setInterval(function () {
                imgX = -imgX - 18 ;
                secondChild.style['background-position'] = imgX+'px 0';
            }, 500);

        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position)=>{
                var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                var   myPos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
                map.setCenter(latlng);
                smoothZoom(map,20,map.getZoom(),'in');
                clearInterval(animationInterval);
                secondChild.style['background-position'] = '-144px 0';
               
              if(myLocationMarker!= null  || myLocationMarker!=undefined){
                myLocationMarker.setMap(null);
                myLocationMarker=undefined;
                myLocationMarker=new google.maps.Marker({
              id:'myLocationMarker',
              map:map,
              zIndex:99999999,
              draggable:false,
              animation: google.maps.Animation.DROP,
              icon:'/me-marker.png',
              position:latlng
            });
            myLocationMarker.addListener('click',()=>{
              zoomLevel=map.getZoom();
              //map.setCenter(myPos);
              smoothZoom(map,20,map.getZoom(),'in',myPos);
              //map.setCenter(myPos);
            });
            //markers["myloc"]=myLocationMarker;
            //markers.push(myLocationMarker);
              }else{
              myLocationMarker=new google.maps.Marker({
              id:'myLocationMarker',
              zIndex:99999999,
              map:map,
              draggable:false,
              animation: google.maps.Animation.DROP,
              icon:'/me-marker.png',
              position:latlng
            });
            myLocationMarker.addListener('click',()=>{
              zoomLevel=map.getZoom();
              //map.setCenter(myPos);
              smoothZoom(map,20,map.getZoom(),'in',myPos);
              //map.setCenter(myPos);
            });
            markers["myloc"]=myLocationMarker;
           // markers.push(myLocationMarker);
              }
            },()=> {
          clearInterval(animationInterval);
          secondChild.style['background-position'] = '0 0';
          locErrHandler(true);
          return null;
        });
        } else {
          locErrHandler(false);
            clearInterval(animationInterval);
            secondChild.style['background-position'] = '0 0';
            return null;
        }
        function locErrHandler(browserHasGeolocation) {
        (browserHasGeolocation)?toast({icon:'error',position:'middle',text:'Klaida geolokacija nerasta',duration:5000}):toast({icon:'error',position:'middle',text:'Klaida: naršyklė geolokacijos nepalaiko',duration:5000});
      }
    });

    controlDiv.index = 1;
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}
function addCreatePostButton (map){
    var controlDiv = document.createElement('div');
    var firstChild = document.createElement('button');
    firstChild.style.backgroundColor = '#fff';
    firstChild.style.border = 'none';
    firstChild.style.outline = 'none';
    firstChild.style.width = '40px';
    firstChild.style.height = '40px';
    firstChild.style.borderRadius = '2px';
    firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
    firstChild.style.cursor = 'pointer';
    firstChild.style.marginRight = '10px';
    firstChild.style.marginBottom = '10px';
    firstChild.style.padding = '0';
    firstChild.title = 'Post';
    controlDiv.appendChild(firstChild);

    var secondChild = document.createElement('div');
    secondChild.class="post-it";
    secondChild.style.margin = '0px';
    secondChild.style.width = '40px';
    secondChild.style.height = '40px';
    secondChild.style.backgroundImage = 'url(/post_it_icon.png)';
    secondChild.style.backgroundSize = '40px 40px';
    secondChild.style.backgroundRepeat = 'no-repeat';
    firstChild.appendChild(secondChild);

    firstChild.addEventListener('click', function () {
      if(secondChild.class=="post-it"){
        secondChild.style.backgroundImage = 'url(/post_it_icon_cancel.png)';
        secondChild.class="post-it-cancel";
      }else{
        secondChild.style.backgroundImage = 'url(/post_it_icon.png)';
        secondChild.class="post-it";
      }
            if(createPostMarker==undefined &&createPostMarker==null) {
                var   centerPos=map.getCenter();

               createPostMarker=new google.maps.Marker({
                    id:'createPostMarker',
                    map:map,
                    zIndex:99999999,
                    draggable:false,
                   // icon:'me-marker.png',
                    position:centerPos
                  });
            createPostMarker.addListener('click',()=>{
              if(createPostInfoWindow==null||createPostInfoWindow==undefined){
                var content='<form method="post" id="post-form" class="container" style="max-width:400px" ><div class="row" style="margin-bottom:5px;"><h3 class="col-12">Make new post</h3></div><div class="row"><div class="col-12"><label>Title<span style="color:red;">*</span></label><input type="text" class="form-control" name="title"></input></div></div><div class="row"><div class="col-12"><label>Comment</label><textarea class="form-control"  name="comment"></textarea></div></div><div class="row"><div class="col-12"><label>Photo(optional)</label><input type="file" class="form-control" name="photo" accept="image/*"></div></div><div class="row"><div class="col-12"><label>Latitude<span style="color:red;">*</span></label><input type="text" name="latitude" class="form-control" value="'+createPostMarker.getPosition().lat()+'"></input></div></div><div class="row"><div class="col-12"><label>Longitude<span style="color:red;">*</span></label><input type="text" class="form-control" name="longitude" value="'+createPostMarker.getPosition().lng()+'"></input></div></div><div class="row"><div class="col-12"><label>Share options<span style="color:red;">*</span></label><select class="form-control" name="share-option"><option value="1" selected>Everyone</option><option value="0">Only me</option></select></div></div><div class="row"><div class="col-12"><label> Post life span<span style="color:red;">*</span></label><select class="form-control" name="life-span"><option value="1" selected>1 day</option><option value="2">2 days</option><option value="3">3 day</option><option value="7">1 Week</option></select></div></div><div class="row"><div class="col-12"><button  type="button" id="btn-new-post" class="btn btn-success" style="float:left;" >Post it</button><button type="button" id="btn-cancel-new-post" class="btn btn-danger" style="float:right;" >Cancel</button></div></div><div class="row"><div class="col-12" style="color:grey"><span style="color:red">*</span>- required</div></div></form>';
                createPostInfoWindow=new google.maps.InfoWindow({
                    content:content
                });
                google.maps.event.addListener(createPostInfoWindow,'closeclick',function(){
                    createPostInfoWindow.close();
                  map.addListener('center_changed',()=>{
                          createPostMarker.setPosition(map.getCenter());
                  });
                  toast({
                    text:"Canceled",
                  });
                  google.maps.event.clearListeners(createPostInfoWindow, 'closeclick');
                  createPostInfoWindow=null;
                });
                    createPostInfoWindow.open(map,createPostMarker);
                    google.maps.event.clearListeners(map, 'center_changed');
                }
            });
           map.addListener('center_changed',()=>{
              createPostMarker.setPosition(map.getCenter());
            });

        } else {
          if(createPostInfoWindow!=null&&createPostInfoWindow!=undefined){
          toast({
          text:"Canceled",
          //textColor:'159,10,156'
        });  
        }
          createPostMarker.setMap(null);
          createPostMarker=null;
          createPostInfoWindow=null;
          google.maps.event.clearListeners(map, 'center_changed');
        }
    });
    controlDiv.index = 2;
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}

//button event functions

$(document).on('click','#btn-cancel-new-post',()=>{
    google.maps.event.trigger(createPostInfoWindow, 'closeclick');
  });
$(document).on('click','#btn-new-post',()=>{
toast({
    position:'middle',
    loadingSpinner:true,
    id:"post-spinner",
    autoRemove:false
});
//event.preventDefault();
$.ajax({
    type:'post',
    dataType: "json",
    url:"/new_post.php",
    data:$('#post-form').serialize(),
    success:(data)=>{
    toast("post-spinner");
    data.response_status=parseInt(data.response_status);
    console.log(data.response_status);
        if(data.response_status==0 ||data.response_status==2){
        toast({
            text:data.response_message,
            icon:'success',
            position:'middle'
        });
        google.maps.event.trigger(createPostInfoWindow, 'closeclick');
        }
        else if(data.response_status==1 ||data.response_status==3 ||data.response_status==4 ||data.response_status==5 ||data.response_status==6){
        toast({
            text:data.response_message,
            icon:'warning',
            duration:3000,
            position:'middle'
        });
        }else if(data.response_status==9){
        toast({
            text:data.response_message,
            icon:'info',
            duration:3000,
            position:'middle'
        });
        $('.highlight').addClass('highlight-anim');
        setTimeout(()=>{ $('.highlight').removeClass('highlight-anim');},3000);
        }else{
        toast({
            text:(data.response_message)?data.response_message:"Error occured",
            icon:'error',
            duration:3000,
            position:'middle'
        });
        }
    },
    error:()=>{
    toast("post-spinner");
    toast({
        text:"Error: Failed to create post",
        position:'middle',
        duration:3000,
        icon:'error'
    });
    
    }
});
});
/*$(document).on('click','btn-delete-post',()=>{

});*/

//utility functions
function openReportFromUrl(urlData){
    if(urlData.report_id!=null && urlData.report_id!=undefined){
      var repId=parseInt(urlData.report_id);
        google.maps.event.trigger(markers[repId], 'click');
    }
}
function smoothZoom (map, toZoom, currentZoom,direction,pos,offset) {

    if(direction==undefined) direction='in';
    if(offset==undefined||offset==null)offset=0;
    if(pos!=undefined ||pos!=null){
      map.setCenter({lat:pos.lat+parseFloat(offset),lng:pos.lng});
    }
    if(toZoom!=currentZoom){
      if ((currentZoom >= toZoom &&direction=='in')||(currentZoom <= toZoom &&direction=='out')) {
          if(pos!=undefined &&pos!=null){
            map.setCenter({lat:pos.lat+parseFloat(offset),lng:pos.lng});//0.000255
          }
          return;
      }
      else {
          zoomListener = google.maps.event.addListener(map, 'zoom_changed', (event)=>{
              google.maps.event.removeListener(zoomListener);
              smoothZoom(map, toZoom,(direction=='in')?currentZoom + 1:currentZoom-1,direction,pos,offset);
          });
          setTimeout(()=>{map.setZoom(currentZoom)}, 80);
          if(pos!=undefined &&pos!=null){
            map.setCenter({lat:pos.lat+parseFloat(offset),lng:pos.lng});//0.000255
          }
      }
    }
}  
function copyURLToClipBoard(){
    // window.clipboardData.setData("Text",location.href);
    var copyText = location.href;
navigator.clipboard.writeText(copyText);
toast({
    text:'Link copied!'
});
}
function navigate(link){
  window.location.replace(link);
}
