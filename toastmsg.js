/*
this funkction uses jquery v3.3.1
and bootstrap v3.3.7 glyphicons
also add this for spinner animation to work fo your css:

      @-webkit-keyframes spinner-border {
        to {
          -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
*/
function toast(toastObj){
    if(typeof toastObj === 'string' || toastObj instanceof String){
      if($('#toast-'+toastObj).length){
        removeToast(toastObj);
      }
      
    }else{
    var text,duration,width,height,position,bgColor,opacity,fontSize,textColor,fullToast,autoRemove,borderRadius,loadingSpinner,spinnerPosition,spinnerColor,icon,iconSize,id;
    text=toastObj.text|| '';//tekstas
    duration=toastObj.duration|| 1000; //po kiek laiko dings jeigu autoRemove=true
    fontSize=toastObj.fontSize|| '15px'; //teksto dydis
    height=toastObj.height|| "30px";// pranešimo laukelio ilgis
    width=toastObj.width|| "auto"; // pranešimo laukelio plotis
    position=toastObj.position|| 'bottom'; // pranešimo laukelio pozicija
    opacity=toastObj.opacity|| 0.8; //pranešimo laukelio permatomumas
    bgColor=toastObj.backGroundColor|| '0,0,0'; // pranešimo laukelio fono spalva
    textColor=toastObj.textColor|| "255,255,255"; // teksto spalva
    borderRadius=toastObj.borderRadius|| "30px"; // pranešimo laukelio apvalumas
    autoRemove=(toastObj.autoRemove==undefined)?true:toastObj.autoRemove; // automatinis laukelio pašalinimas
    loadingSpinner=toastObj.loadingSpinner|| false; // ar laukelis yra loading spinneris
    spinnerPosition=toastObj.spinnerPosition|| 'spinner-only'; // spinnerio pozicija nuo teksto left/right arba tik spinneris spinner-only
    spinnerColor=toastObj.spinnerColor||textColor;
    spinnerThickness=toastObj.spinnerThickness||"0.25rem"
    spinnerSize=toastObj.spinnerSize||"2rem";
    icon=toastObj.icon||null; // icona info/erro/warning/success
    iconSize=toastObj.iconSize||'25px';
    id=toastObj.id||null // custom pranesimo laukelio id
    dim=toastObj.dim||false;
    var positionClass;
    if($('#toast-'+id).length == 0){
      switch(position){
        case "bottom":
        position="bottom: 40px;";
        positionClass="bottom-toast";
        break; 
        case "top":
          position="top: 70px;";
          positionClass="top-toast";
        break;
        case "middle":
          position = "top: 50%;";
          positionClass="middle-toast";
        break;
        default:
          position="bottom: 80px;";
          positionClass="bottom-toast";
      }
      if(loadingSpinner==true){   
        var spinnerContent='<div  class="spinner-border" style="display: inline-block;width:'+spinnerSize+';height:'+spinnerSize+';color:rgb('+spinnerColor+'); vertical-align: text-bottom;border: '+spinnerThickness+' solid currentColor;border-right-color: transparent;border-radius: 50%;-webkit-animation: spinner-border .75s linear infinite;animation: spinner-border .75s linear infinite;';
        switch(spinnerPosition){
          case "spinner-only":
            spinnerContent+='"></div>';
            text=spinnerContent;
          break;
          case "left":
            spinnerContent+='margin-right:5px;"></div>';
            text=spinnerContent+text;
          break;
          case "right":
            spinnerContent+='margin-left:5px;"></div>';
            text=text+spinnerContent;
          break;
          default:
            spinnerContent+='"></div>';
            text=spinnerContent;
        } 
        $('.spinner-border').animate({transform:"rotate(360deg')"},{duration: 1000,easing:'linear',iterations: Infinity});
      }
      if(icon != null){   
        var iconGlyph,iconColor;
        switch(icon){
          case "info":
            iconGlyph='glyphicon-info-sign';
            iconColor='53, 157, 255';
          break;
          case "success":
            iconGlyph='glyphicon-ok-sign';
            iconColor='72, 255, 48';
          break;
          case "warning":
            iconGlyph='glyphicon-warning-sign';
            iconColor='255, 210, 63'
          break;
          case "error":
            iconGlyph='glyphicon-remove-sign';
            iconColor='216, 0, 0';
          break;
          default:
            iconGlyph='';
            iconColor='';
        } 
        if(toastObj.iconColor!=null&&toastObj.iconColor!=undefined){
          iconColor=toastObj.iconColor;
        }
        text='<i class="glyphicon '+iconGlyph+'" style="color: rgb('+iconColor+');font-size:'+iconSize+'"></i><div>'+text+'</div>';
      }
  
      var fullBgColor = 'rgba('+bgColor+','+opacity+')';
      var toastIndex = (id!=null)?id:$('#toasts').children('.toast').length;
      var toastLength = $('#toasts').children('.'+positionClass).length;
      var previousToastBottom,previousToastHeight;
      if(toastLength>0){
        previousToastBottom = $('.'+positionClass).eq(toastLength-1).css('bottom');
        previousToastHeight = $('.'+positionClass).eq(toastLength-1).css('height');
        previousToastBottom = parseInt(previousToastBottom.match(/[0-9]+/));
        previousToastHeight = parseInt(previousToastHeight.match(/[0-9]+/));
        position = 'bottom: ' + (previousToastBottom+previousToastHeight) + 'px;';
      }
      if($('body').children('#toasts').length==0){
        $('body').append('<div id="toasts"></div>');
      }
      if(dim==true && $('body').children('#dim-screen').length==0){
        //$('<div id="dim-screen"></div>').before('#toasts');
        $('body').prepend('<div id="dim-screen"></div>');
      }
      fullToast = ' <div class="toast '+positionClass+'" id="toast-'+toastIndex+'" style="pointer-events:none;   width:100%; min-height: ' + height + '; position:fixed;display: none; z-index:9999;font-size: '+fontSize+'; ' + position + ';left: 0; text-align: center;">';
      fullToast += '<div  style="min-width: ' + width + ';min-height: ' + height + ';display: inline-block; padding: 5px 15px;background-color: ' + fullBgColor + ';border-radius:'+borderRadius+';color: rgb(' + textColor + ');">' + text + '</div></div>';
      $("#toasts").append(fullToast);
      /*if(loadingSpinner==true){
        
      }*/
      $(".toast").fadeIn(150);
      
      if(autoRemove==true){
        removeToast(toastIndex);
      }
    }
  }   
      function removeToast(toast){
        if($('#toast-'+toast).length){
          setTimeout(() => {
            $('#toast-'+toast).fadeOut(300);
            setTimeout(() => {   
              if(positionClass==undefined && positionClass==null && $('#toast-'+toast).length){
                var  positionClass=$('#toast-'+toast).attr('class').match(/[a-z]+\-toast/);
              }
              if($('#toast-'+toast).index() == 0 && $('#toasts').children('.'+positionClass).length > 1){
                var Height = parseInt($('#toast-'+toast).css('height').match(/[0-9]+/));
                $('.'+positionClass).each(function(){
                var thisBottom = parseInt($(this).css('bottom').match(/[0-9]+/));
                var newBottom = (thisBottom-Height);
                $(this).css('bottom',newBottom+'px');
                });
              }
              $('#toast-'+toast).remove();
              if($('#toasts').children().length == 0){
                $('#toasts').remove();
              }
              if(dim==true){
                $('#dim-screen').remove();
              }
            },300);
          },duration);
        }
      }
  }