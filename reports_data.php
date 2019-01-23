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
$reports_data=mysqli_query($con, $sql);
$reports_data=mysqli_fetch_all($reports_data,MYSQLI_ASSOC);
$con->close();
echo json_encode($reports_data);
die();
?>