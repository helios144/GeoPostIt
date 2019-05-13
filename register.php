<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Maps</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css"> 
    <script src="/toastmsg.js"></script>
    </head>
  <body>
  <?php
      include_once("navbar.php");
    ?>
  <?php if(isset($_SESSION['valid']))echo $_SESSION['valid']." ".$_SESSION['invalid_text'];?>
    <div class="container" style="width: 100%; height: 100%;">
        <div class="row">
            <form action="register_user.php" method="POST">
                <div class="col-12">
                    <label for="user_name">Log in name</label>
                    <input type="text" name="user_name" class="col-12"/>
                </div>
                <div class="col-12">
                	<label for="password">Password</label>
                	<input type="text" name="password" class="col-12">
                </div>
				<div class="col-12">
					<label for="password_again">Repeat Password</label>
					<input type="text" name="password_again"/>
				</div>
				<div class="col-12">
					<button type="submit" class="col-12">Register</button>
					<?php
					if(isset($_SESSION['valid'])){
						echo "<p style='color:red;'>".$_SESSION['invalid_text']."</p>";
						unset($_SESSION['valid']);
						unset($_SESSION['invalid_text']);
					}
				?>
				</div>
            </form>  
        </div>
    </div>   
  </body>
</html>