<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Maps</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
  <body>
  <?php if(isset($_SESSION['valid']))echo $_SESSION['valid']." ".$_SESSION['invalid_text'];?>
    <div class="container">
        <div class="row">
            <form action="register_user.php" method="POST">
                <div class="col-12">
                    <label for="user_name">Įvesti prisijungimo vardą</label>
                    <input type="text" name="user_name" class="col-12"/>
                </div>
                <div class="col-12">
                	<label for="password">Įvesti slaptažodį</label>
                	<input type="text" name="password" class="col-12">
                </div>
				<div class="col-12">
					<label for="password_again">Pakartinai įveskite slaptažodį</label>
					<input type="text" name="password_again"/>
				</div>
				<div class="col-12">
					<button type="submit" class="col-12">Registruotis</button>
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