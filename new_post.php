<?php
session_start();
$img_upload_dir="report_images/";
$uploadStatus=1;
$response_status=0;
$response_message;
$response=[];
$imageName;
$valid_image_types=['png'=>'image/png','jpg'=>'image/jpg','jpeg'=>'jpeg'];
if(isset($_SESSION['user_data'])){
    if($_POST['title']!=null&&$_POST['latitude']!=null&&$_POST['longitude']!=null&&$_POST['life-span']!=null&&$_POST['share-option']!=null){
        print_r("all not null");
        echo $title;
        die();
        if(isset($_FILES["image"])){
            if($_FILES["image"]["error"] == 0){
                $file_extention=pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                if(array_key_exists($file_extention,$valid_image_types)){
                    $maximum_image_size=10*1024*1024;
                    if($_FILES["image"]["size"]<=$maximum_image_size){
                        if(!file_exists($img_upload_dir.$_FILES["image"]["name"])){
                            move_uploaded_file($_FILES["image"]["tmp_name"], $img_upload_dir . $_FILES["image"]["name"]);
                        }else{
                            $imageName=$_FILES["image"]["name"];
                            $imageName=(string)rand(1000, 9999).$imageName;
                            move_uploaded_file($_FILES["image"]["tmp_name"], $img_upload_dir . $fileNewName);
                            $response_status = 6; // file already exists
                        }
                    }else{
                        $response_status = 5; // over size limit
                    }    
                }else{
                    $response_status = 4; //invalid image type
                }
            }else{
                $response_status = 3;// invalid upload
            }    
        }else{
            $response_status = 2; // no image
        }
    }else{
        $response_status = 1; //no data
    }
}else{
    $response_status = 9; //not logged in
}
$dateTime=new DateTime();
$date=$dateTime->format("Y-m-d H:i:s");
$dateTime->modify('+'.$_POST['life-span'].' day');
$deleteDate=$dateTime->format("Y-m-d H:i:s");
if($response_status==0){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "geopostit";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        //die("Connection failed: " . $con->connect_error);
        $response_status=7;
    }else{ 
        $sql="INSERT INTO reports (user_id,share_option,title,info,latitude,longitude,image,post_creation_date,delete_date) VAULES ('".$_SESSION['user_data']['user_name']."','".$_POST['share-option']."','".$_POST['title']."','".$_POST['comment']."','".$_POST['tatitude']."','".$_POST['longitude']."','".$imageName."','".$date."','".$deleteDate."')";
        if ($con->query($sql) === TRUE) {
            $response_message= 'Post successfully created';
        }
        else {
            $response_status=8;
            $response_message= 'Error: '. $con->error;
        }
        $con->close();
        //$response_message= 'Post successfully created';
    }
} else if($response_status==2){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "geopostit";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        //die("Connection failed: " . $con->connect_error);
        $response_status=7;
    }else{ 

        $sql="INSERT INTO reports (user_id,share_option,title,info,latitude,longitude,post_creation_date,delete_date) VALUES ('".$_SESSION['user_data']['user_id']."','".$_POST['share-option']."','".$_POST['title']."','".$_POST['comment']."','".$_POST['latitude']."','".$_POST['longitude']."','".$date."','".$deleteDate."')";
        /*echo $sql;
        die();*/
        //mysqli_query($con, $sql);
        if ($con->query($sql) === TRUE) {
            $response_message= 'Post successfully created';
        }
        else {
            $response_status=8;
            $response_message= 'Error: '. $con->error;
        }
        $con->close();
        
    }
}else{
    switch($response_status){
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
$response['response_status']=$response_status;
$response['response_message']= $response_message;
echo json_encode($response);
die();
?>