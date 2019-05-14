<?php
$img_upload_dir="report_images/";
$uploadStatus=1;
$response_status_code=0;
$response_message='';
$response=[];
$imageName;
$valid_image_types=['png'=>'image/png','jpg'=>'image/jpg','jpeg'=>'jpeg'];
    if(isset($_POST)){
        if(isset($_FILES["image"])){
            if($_FILES["image"]["error"] == 0){
                $file_extention=pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                if(array_key_exists($file_extention,$valid_image_types)){
                    $maximum_image_size=10*1024*1024;
                    if($_FILES["image"]["size"]<=$maximum_image_size){
                        if(!file_exists($img_upload_dir.$_FILES["image"]["name"])){
                            move_uploaded_file($_FILES["image"]["tmp_name"], $img_upload_dir . $_FILES["image"]["name"]);
                             $imageName=$_FILES["image"]["name"];
                            $_POST['image']=$_FILES["image"]["name"];
                        }else{
                            $imageName=$_FILES["image"]["name"];
                            $imageName=(string)rand(1000, 9999).$imageName;
                            move_uploaded_file($_FILES["image"]["tmp_name"], $img_upload_dir . $fileNewName);
                            $_POST['image']=$imageName;
                           // $response_status_code = 6; // file already exists
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

if($response_status_code==0){
    $dateTime=new DateTime();
    $date=$dateTime->format("Y-m-d H:i:s");
    $dateTime->modify(($_POST['life-span']==0)?'+36500 day':'+'.$_POST['life-span'].' day');
    $deleteDate=$dateTime->format("Y-m-d H:i:s");
    unset($_POST['life-span']);
    $_POST['delete_date']=$deleteDate;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "geopostit";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        //die("Connection failed: " . $con->connect_error);
        $response_status_code=7;
    }else{ 
        $con->query("SET NAMES 'utf8'");
      //  $sql="INSERT INTO reports (user_id,share_option,title,info,latitude,longitude,image,category,post_creation_date,delete_date) VAULES ('".$_SESSION['user_data']['user_name']."','".$_POST['share-option']."','".$_POST['title']."','".$_POST['comment']."','".$_POST['tatitude']."','".$_POST['longitude']."','".$imageName."','".$_POST['category']."','".$date."','".$deleteDate."')";
       $post_id=$_POST['post_id'];
       unset($_POST['post_id']);
        $sql = "UPDATE posts SET ";
        foreach($_POST as $key => $val){
            $sql.=$key."='".$val."',";
        }
        $sql=substr($sql, 0, -1);
        $sql.=" WHERE post_id=".$post_id;
       // echo $sql;
        if ($con->query($sql) === TRUE) {
            $response_message= 'Post edited successfully';
        }
        else {
            $response_status_code=8;
            $response_message='Error: failed to edit post. Try again later' ;//'Error: '. $con->error;
        }
        $con->close();
        //$response_message= 'Post successfully created';
    }
} else if($response_status_code==2){
    $dateTime=new DateTime();
$date=$dateTime->format("Y-m-d H:i:s");
$dateTime->modify(($_POST['life-span']==0)?'+36500 day':'+'.$_POST['life-span'].' day');
$deleteDate=$dateTime->format("Y-m-d H:i:s");
unset($_POST['life-span']);
$_POST['delete_date']=$deleteDate;
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "geopostit";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        //die("Connection failed: " . $con->connect_error);
        $response_status_code=7;
    }else{ 
        $con->query("SET NAMES 'utf8'");
        //$sql="INSERT INTO reports (user_id,share_option,title,info,latitude,longitude,post_creation_date,delete_date) VALUES ('".$_SESSION['user_data']['user_id']."','".$_POST['share-option']."','".$_POST['title']."','".$_POST['comment']."','".$_POST['latitude']."','".$_POST['longitude']."','".$date."','".$deleteDate."')";
        /*echo $sql;
        die();*/
        //mysqli_query($con, $sql);
        $post_id=$_POST['post_id'];
        unset($_POST['post_id']);
        if(isset($_POST['image'])) unset($_POST['image']);
         $sql = "UPDATE posts SET ";
         foreach($_POST as $key => $val){
             $sql.=$key."='".$val."',";
         }
         $sql=substr($sql, 0, -1);
         $sql.=" WHERE post_id=".$post_id;
        // echo $sql;
        // die();
        if ($con->query($sql) === TRUE) {
            $response_message= 'Post edited successfully';
        }
        else {
            $response_status_code=8;
            $response_message='Error: failed to edit post. Try again later' ;
        }
        $con->close();
        
    }
}else{
    switch($response_status_code){
        case 1:
            $response_message= "Nothing was changed";
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
    }
}
$response['status_code']=$response_status_code;
$response['response_message']= $response_message;
//echo json_encode($response);
//die();
header('Location: /posts');
?>