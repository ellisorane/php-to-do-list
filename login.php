<?php 
  include "includes/db.php";
  include "functions.php"; 

  ob_start();
  session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Shadows+Into+Light&display=swap" rel="stylesheet">
    <title>TO DO LIST 10.0 | Login</title>
</head>
<body>

    <div class="clip-path"></div>
    <header>
        <h1>TO DO LIST 10.0</h1>
    </header>

    
    <div class="form-container">
        <h1><u>Login</u></h1>

        <?php login(); ?>

        <form action="" method="post">
            <div class="">
              <label for="username" class="">Username</label><br>
              <input type="text" id="" name="username" required>
            </div>
            <div class="">
                <label for="" class="">Password</label><br>
                <input type="password" id="" name="user_password" required>
              </div>
            <button type="submit" name="submit" class="btn submit-task-btn">Submit</button>
            <p>No login?</p>
            <a href="signup.php" class="btn submit-task-btn">Signup Page</a>

          </form>
          

    </div>
    
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
</body>
</html>