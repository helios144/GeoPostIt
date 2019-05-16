<?php 
$status_code=0;
$response_message='';
    if(isset($_POST['post_id'])){
      /*  $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "geopostit";*/
        require('database_credentials.php');
        $con = new mysqli($servername, $username, $password, $dbname);
        if ($con->connect_error) {
            //die("Connection failed: " . $con->connect_error);
            $status_code=2;
            $response_message='Error: failed to delete post. Try again later';
        }else{
            $sql="SELECT image FROM posts WHERE post_id=".$_POST['post_id'];
            $data=mysqli_query($con, $sql);
            $data=mysqli_fetch_all($data,MYSQLI_ASSOC);
            if(isset($data[0]['image'])&& strlen($data[0]['image'])>0){
             $delfile=$_SERVER['DOCUMENT_ROOT'].'/posts_images/'.$data[0]['image'];
                if (file_exists($delfile)) { unlink ($delfile); }
            }
           
            $sql="DELETE FROM posts WHERE post_id=".$_POST['post_id'];
            if ($con->query($sql) === TRUE) {
                $response_message= 'Post deleted succesfully';
            }
            else {
                $status_code=3;
                $response_message='Error: failed to delete post. Try again later';// 'Error: '. $con->error;
            }
            $con->close();
        }
    }else{
        $status_code=1;
        $response_message='Error: no data';
    }
    $response['status_code']=$status_code;
    $response['response_message']= $response_message;
    echo json_encode($response);
    die();
?>