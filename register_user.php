<?php
session_start();
if(strlen($_POST['password'])>0&& strlen($_POST['password_again'])&& strlen($_POST['user_name'])>0){
if($_POST['password']==$_POST['password_again']&&strlen($_POST['password'])>0){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "geopostit";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $sql="SELECT user_name FROM users WHERE user_name='".$_POST['user_name']."'";
    $user_data=mysqli_query($con, $sql);
    $user_data=mysqli_fetch_all($user_data,MYSQLI_ASSOC);
    if($user_data){
        $_SESSION['valid']=0;
        $_SESSION['invalid_text']="Vartotojo vardas toks jau yra";
        $con->close();
        header('Location: /register');
    }else{
        $password_hash=password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql="INSERT INTO users (user_name,password_hash,share_list) VALUES ('".$_POST['user_name']."','".$password_hash."',1)";
        $con->query($sql);
        $con->close();
        header('Location: /login');
    }
}else{
    $_SESSION['valid']=0;
    $_SESSION['invalid_text']="Slaptažodis nesutapo arba neįvestas";
    header('Location: /register');
}
}else{
    $_SESSION['valid']=0;
    $_SESSION['invalid_text']="Yra neįvestų laukų";
    header('Location: /register');
}
?>