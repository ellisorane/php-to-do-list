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
    <title>TO DO LIST 10.0 | Add Task</title>
</head>
<body>
    <header>
        <h1>TO DO LIST 10.0</h1>
    </header>

    
    <div class="form-container">
        <h1><u>Add Task</u></h1>

        <!-- ADD TASK -->
        <?php
          
          createTask();

        ?>

        <form action="" method="post">
            <div>
              <label for="task_name">TASK</label><br>
              <input type="text" name="task_name">
            </div>
            <div class="">
                <label for="priority">PRIORITY</label><br>
                <select name="priority">
                  <option value="regular">Regular Priority</option>
                  <option value="high">High Priority</option>
                </select>
              </div>
            <button type="submit" name="submit" class="btn submit-task-btn">Add Task</button>
          </form>

    </div>

    <script src="node_modules/jquery/dist/jquery.min.js"></script>
</body>
</html>