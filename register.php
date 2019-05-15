<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="/faviconn.ico" />
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/style.css"> 
    <script src="/toastmsg.js"></script>
    </head>
  <body>
  <?php
      include_once("navbar.php");
    ?>
  <?php if(isset($_SESSION['valid']))echo $_SESSION['valid']." ".$_SESSION['invalid_text'];?>
    <div class="container home-bg">
      <div style="height:20%;display:inline-block;width:100%"></div>
      <form class="form-group login-register-form" id="register-form" action="/register_user.php" method="POST">
        <div class="row">
          <h1 class="col-12">Register</h1>
        </div> 
        <div class="row">
          <div class="col-12">
           <label for="user_name">Log in name</label>
           <input type="text" name="user_name" class="form-control" placeholder="Your user name"/>
          </div>
        </div>
        <di class="row">
          <div class="col-12">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Your password"/>
          </div>
        </di>
        <div class="row">
          <div class="col-12">
           <label for="password_again">Repeat Password</label>
           <input type="password" name="password_again" class="form-control" placeholder="Repeat your password"/>
         </div>
        </div>
      <div class="row">
        <div class="col-12">
         <button type="submit" id="btn-register" class="btn btn-success form-control" style="max-width:200px;">Register</button>
        </div>
      </div>
      
      <?php
      if(isset($_SESSION['valid'])){
      echo "<div class='row'><div class='col-12'><p style='color:red;'>".$_SESSION['invalid_text']."</p></div></div>";
      unset($_SESSION['valid']);
      unset($_SESSION['invalid_text']);
      }
      ?>
      </form>  
    </div>  
    <script>
        $(document).ready(()=>{
            $('#btn-register').click(()=>{
                toast({
                    loadingSpinner:true,
                    dim:true,
                    autoRemove:false,
                    position:'middle',
                    width:'200px',
                    height:'100px',
                    spinnerThickness:'1rem',
                    spinnerSize:'8rem'
                });
            });
        });
    </script>  
  </body>
</html>