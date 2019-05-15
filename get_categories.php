<?php
$content='';
$response_status_code=0;
$response_message='';
$categories='';
    require('database_credentials.php');
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        $status_code=1;
        $response_message='Error: failed to get categories. Turn on and off new post marker';
    }else{
        $sql="SELECT category,icon FROM categories";
        $cats=mysqli_query($con, $sql);
        if ( $cats !== false) {
                  $cats=mysqli_fetch_all($cats,MYSQLI_ASSOC);
                  $count=0;
                  foreach($cats as $val){
                    $categories.= '<option value="'.$val['category'].'"'.(($count==0)?"selected":"").'>'.ucwords($val['category']).'</option>';
                    $count=1;
                  }
        }
        else {
            $response_status_code=2;
            $response_message='Error: failed to get categories. Turn on and off new post marker' ;
        }
    }
    $response['status_code']=$response_status_code;
    $response['response_message']= $response_message;
    $response['content']=$categories;
    echo json_encode($response);
    die();
?>