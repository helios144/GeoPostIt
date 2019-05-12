<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "geopostit";

$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST)&&count($_POST)>0){
    ////var_dump( $_POST);
    if(isset($_POST['report_id']))$sql="SELECT report_id,user_id,latitude,longitude,title,info,image FROM reports WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng']." OR report_id=".$_POST['report_id']; 
    else  $sql="SELECT report_id,user_id,latitude,longitude,title,info,image FROM reports WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng']; 
    //echo $sql;
}else{
    $sql="SELECT report_id,user_id,latitude,longitude,title,info,image FROM reports";
}
//echo $sql;
$con->query("SET NAMES 'utf8'");
$reports_data=mysqli_query($con, $sql);
$reports_data=mysqli_fetch_all($reports_data,MYSQLI_ASSOC);
$con->close();
//print_r($reports_data);
//var_export( json_encode($reports_data));
echo json_encode($reports_data);
die();
?>