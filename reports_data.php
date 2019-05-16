<?php
session_start();
require('database_credentials.php');
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$posts_data=[];
$new_data=[];
$urlPost=0;
if(isset($_POST)&&count($_POST)>0){ 
    if(isset($_POST['post_id'])){
        $urlPost=1;
        $sql="SELECT post_id,user_id,share_option,latitude,longitude,title,comment,image FROM posts WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng']." OR post_id=".$_POST['post_id']; 
    }else if(isset($_POST['category'])||isset($_POST['search_query'])){
        $urlPost=2;
        $sql="SELECT post_id,user_id,share_option,latitude,longitude,title,comment,image FROM posts WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng'];
        if(isset($_POST['category'])&&$_POST['category']!='all')$sql.=" AND category='".$_POST['category']."'";
        if(isset($_POST['search_query'])) $sql.=" AND (UPPER(comment) LIKE UPPER('%".$_POST['search_query']."%') OR UPPER(title) LIKE UPPER('%".$_POST['search_query']."%'))";
    }else{
        $sql="SELECT post_id,user_id,share_option,latitude,longitude,title,comment,image FROM posts WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng']; 
    }
    
    $con->query("SET NAMES 'utf8'");
    $posts_data=mysqli_query($con, $sql);
    $posts_data=mysqli_fetch_all($posts_data,MYSQLI_ASSOC);
    foreach($posts_data as $key => $row){
        if(isset($_SESSION['user_data']['user_id'])){
            if($_SESSION['user_data']['user_id']==$row['user_id']||$row['share_option']==1){
                $new_data[$key]['latitude']=$row['latitude'];
                $new_data[$key]['longitude']=$row['longitude'];
                $new_data[$key]['user_id']=$row['user_id'];
                $new_data[$key]['title']=$row['title'];
                $new_data[$key]['post_id']=$row['post_id'];
                $new_data[$key]['content']='
                <div class="row" style="margin:10px;">
                <h3 class="col-12" style="margin-top:0px">'.$row['title'].'</h3></div>
                <div class="row" style="margin:10px;"><p class="col-12">'.$row['comment'].'</p></div>';

                if($row['image']!=null) $new_data[$key]['content'].='<div class="row" style="margin:10px;"><img class="col-12" src="/posts_images/'.$row['image'].'" style="max-width:291px;height:auto;"></img></div>';
                $new_data[$key]['content'].='<div class="row" style="margin:10px;"><div class="col-12">
                <button class="btn fa fa-copy" onClick="copyURLToClipBoard()"></button>
                <button type="button" class="btn btn-default" onClick="navigate(\'https://www.google.com/maps/search/?api=1&query='.$row['latitude'].','.$row['longitude'].'\')">Navigate</button></div></div>';
                if(isset($_SESSION['user_data']['user_id'])){
                    if($_SESSION['user_data']['user_id']==$row['user_id']){
                        $new_data[$key]['content'].='
                        <div class="row" style="margin:10px;"><div class="col-12">
                        <button class="btn btn-success btn-edit-post" value="'.$row['post_id'].'">Edit</button>
                        <button class="btn btn-danger btn-delete-post" value="'.$row['post_id'].'">Delete</button></div></div>';
                    }
                }      
            }
        }else{
            if($row['share_option']==1){
                $new_data[$key]['latitude']=$row['latitude'];
                $new_data[$key]['longitude']=$row['longitude'];
                $new_data[$key]['user_id']=$row['user_id'];
                $new_data[$key]['title']=$row['title'];
                $new_data[$key]['post_id']=$row['post_id'];
                $new_data[$key]['content']='
                <div class="row" style="margin:5px;">
                <h3 class="col-12" style="margin-top:0px">'.$row['title'].'</h3></div>
                <div class="row" style="margin:10px;"><p class="col-12">'.$row['comment'].'</p></div>';
                if($row['image']!=null) $new_data[$key]['content'].='<div class="row" style="margin:10px;"><img class="col-12" src="/posts_images/'.$row['image'].'" style="max-width:291px;height:auto;"></img></div>';
                $new_data[$key]['content'].='<div class="row" style="margin:10px;"><div class="col-12">
                <button class="btn fa fa-copy" onClick="copyURLToClipBoard()"></button>
                <button type="button" class="btn btn-default" onClick="navigate(\'https://www.google.com/maps/search/?api=1&query='.$row['latitude'].','.$row['longitude'].'\')">Navigate</button></div></div>';
            }
        }
    }
    if($urlPost==1&&$new_data==null){
        $new_data['status_code']=2;
        $new_data['status_message']='Post does not exist or is private';
    }
    if($urlPost==2&&$new_data==null){
        $new_data['status_code']=2;
        $new_data['status_message']='Posts does not exist or are private';
    }
}else{
    $new_data['status_code']=1;
    $new_data['status_message']='Error occured';
}
$con->close();
echo json_encode($new_data);
die();
?>