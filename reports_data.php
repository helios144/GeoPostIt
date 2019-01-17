<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baigiamasis";

$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql="SELECT report_id,user_id,latitude,longitude,title,info,image FROM reports";
//$reports_data=$conn->query($sql);
//$reports_data=$con->query($sql);
$reports_data=mysqli_query($con, $sql);
//print_r($reports_data);
$reports_data=mysqli_fetch_all($reports_data,MYSQLI_ASSOC);
//print_r($reports_data);
/*foreach ($reports_data as $key => $value) {
    foreach ($value as $k => $v) {
        echo "$k $v \n";
    }
    echo "\n \n";
}*/
$con->close();
echo json_encode($reports_data);
die();
?>