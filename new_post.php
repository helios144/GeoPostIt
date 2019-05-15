<?php
session_start();
$img_upload_dir="posts_images/";
$uploadStatus=1;
$response_status_code=0;
$response_message='';
$response=[];
$imageName;
$valid_image_types=['png'=>'image/png','jpg'=>'image/jpg','jpeg'=>'jpeg'];
if(isset($_SESSION['user_data'])){
    if($_POST['title']!=null&&$_POST['latitude']!=null&&$_POST['longitude']!=null&&$_POST['life-span']!=null&&$_POST['share_option']!=null){
        if(isset($_FILES["image"])&&$_FILES["image"]['size']>0){
            if($_FILES["image"]["error"] == 0){
                $file_extention=pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                if(array_key_exists($file_extention,$valid_image_types)){
                    $maximum_image_size=10*1024*1024;
                    if($_FILES["image"]["size"]<=$maximum_image_size){
                        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/posts_images')){
                            mkdir($_SERVER['DOCUMENT_ROOT'].'/posts_images', 0755, true);
                        }
                        if(!file_exists($img_upload_dir.$_FILES["image"]["name"])){
                            move_uploaded_file($_FILES["image"]["tmp_name"], $img_upload_dir . $_FILES["image"]["name"]);
                            $imageName=$_FILES["image"]["name"];
                        }else{
                            $imageName=$_FILES["image"]["name"];
                            $imageName=(string)rand(1000, 9999).$imageName;
                            move_uploaded_file($_FILES["image"]["tmp_name"], $img_upload_dir . $fileNewName);
                            $response_status_code = 6; // file already exists
                        }
                    }else{
                        $response_status_code = 5; // over size limit
                    }    
                }else{
                    $response_status_code = 4; //invalid image type
                }
            }else{
                $response_status_code = 3;// invalid upload
            }    
        }else{
            $response_status_code = 2; // no image
        }
    }else{
        $response_status_code = 1; //no data
    }
}else{
    $response_status_code = 9; //not logged in
}
$dateTime=new DateTime();
$date=$dateTime->format("Y-m-d H:i:s");
$dateTime->modify(($_POST['life-span']==0)?'+36500 day':'+'.$_POST['life-span'].' day');
$deleteDate=$dateTime->format("Y-m-d H:i:s");
if($response_status_code==0){
    require('database_credentials.php');
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        $response_status_code=7;
    }else{ 
        $sql="INSERT INTO posts (user_id,share_option,title,comment,latitude,longitude,image,category,post_creation_date,delete_date) VALUES ('".$_SESSION['user_data']['user_id']."','".$_POST['share_option']."','".$_POST['title']."','".$_POST['comment']."','".$_POST['latitude']."','".$_POST['longitude']."','".$imageName."','".$_POST['category']."','".$date."','".$deleteDate."')";
        if ($con->query($sql) === TRUE) {
            $response_message= 'Post successfully created';
        }
        else {
            $response_status_code=8;
            $response_message='Error: Failed to create post. Try again later' ;//'Error: '. $con->error;
        }
        $con->close();
    }
} else if($response_status_code==2){
    require('database_credentials.php');
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        //die("Connection failed: " . $con->connect_error);
        $response_status_code=7;
    }else{ 
        $sql="INSERT INTO posts (user_id,share_option,title,comment,latitude,longitude,post_creation_date,delete_date) VALUES ('".$_SESSION['user_data']['user_id']."','".$_POST['share_option']."','".$_POST['title']."','".$_POST['comment']."','".$_POST['latitude']."','".$_POST['longitude']."','".$date."','".$deleteDate."')";
        if ($con->query($sql) === TRUE) {
            $response_message= 'Post successfully created';
        }
        else {
            $response_status_code=8;
            $response_message= 'Error: '. $con->error;
        }
        $con->close();
        
    }
}else{
    switch($response_status_code){
        case 1:
            $response_message= "Please fill in all required fields";
        break;   
        case 3:
            $response_message= "Invalid image upload";
        break;
        case 4:
            $response_message= "Invalid image type accepted: png,jpg,jpeg";
        break;
        case 5:
            $response_message= "Image exceeds 10MB size limit";
        break;
        case 6:
            $response_message= "File already exists";
        break;
        case 7:
            $response_message= "Failed to create post. Try again later";
        break;
        case 9:
            $response_message= "You need to Log in to make post";
        break;
    }
}
$response['status_code']=$response_status_code;
$response['response_message']= $response_message;
//echo $response['status_code'];
//$response['response_message'];
//die();
//echo json_encode($response);
if($response['status_code']==0 ||$response['status_code']==2){
    $_SESSION['response']="<script> $(document).ready(function(){
        toast({
            text:'".$response['response_message']."',
            icon:'success',
            position:'middle'
        });
    });</script>";
}else if($response['status_code']==1 ||$response['status_code']==3 ||$response['status_code']==4 ||$response['status_code']==5 ||$response['status_code']==6){
    $_SESSION['response']="<script> $(document).ready(function(){
        toast({
            text:'".$response['response_message']."',
            icon:'warning',
            duration:3000,
            position:'middle'
        });
    });</script>";
}else if($response['status_code']==9){
    $_SESSION['response']="<script> $(document).ready(function(){
        toast({
            text:'".$response['response_message']."',
            icon:'info',
            duration:3000,
            position:'middle'
        });

        $('.highlight').addClass('highlight-anim');
    setTimeout(()=>{ $('.highlight').removeClass('highlight-anim');},3000);
    });</script>";
    
}
else{
    $_SESSION['response']="<script> $(document).ready(function(){
        toast({
            text:'Error occured. Try again',
            icon:'error',
            duration:3000,
            position:'middle'
        });
    });</script>";
}
header('Location: /posts');
//die();
?>