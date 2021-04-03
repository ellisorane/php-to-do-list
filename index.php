<?php include "includes/db.php"; ?>
<?php 

include "functions.php";
ob_start();
session_start();

// USERS CANNOT COME TO THIS PAGE UNLESS THEY ARE LOGGED IN
if(!isset($_SESSION["username"])) {

    header("Location: login.php");

}

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

    <title>TO DO LIST 10.0 | Home</title>
</head>
<body>
    <div class="clip-path"></div>
    <header>
        <h1>TO DO LIST 10.0</h1>
    </header>

    <div class="greeting">
        <h2><u><?php echo strtoupper($_SESSION["username"]); ?></u></h2>
        <button class='btn'><a href="logout.php">Logout</a></button>
    </div>
    

    <div class="add-task-div ">
    <button class="add-task"><a href="add_task_form.php">ADD TASK</a></button>
    </div>
    
    <div class="container">
        <div class="tasks tasks-container">
            <ul class="tasks-list">

            <?php

                deleteTask();
                completeTask();
                editTask();

                $user_id = $_SESSION["user_id"];

                $query = "SELECT * FROM tasks WHERE creator_id = $user_id";
                $retrieve_tasks = mysqli_query($conn, $query);

                while($row = mysqli_fetch_assoc($retrieve_tasks)) {
                    $id = $row["id"];
                    $task_name = $row["task_name"];
                    $priority = $row["priority"];
                    $status = $row["task_status"];

                    if($status === "Not complete") {
                        echo "
                            <li class='task'>
                                <span>
                                    <span class='priority $priority-priority'></span>
                                    $task_name    
                                </span>
                                <span class='task-right'>
                                    <a href='index.php?complete=$id'><button class='btn task-btn complete'>Complete</button></a>
                                    <a href='index.php?edit=$id'><button class='btn task-btn edit'>Edit</button></a>
                                    <a href='index.php?delete=$id'><button class='btn task-btn delete'>Delete</button></a>
                                </span>
                            </li>
                            ";
                    }
                }


            ?>

            </ul>
        </div>

        <div class="tasks completed-tasks">
            <h2>
                <u>Completed Tasks</u>
                <button class="btn clear-tasks"><a href='index.php?clear_all'>Clear All</a></button>
            </h2>
            <ul class="completed-tasks-list">

            <?php

                clearAllComplete();
            
                $query = "SELECT * FROM tasks WHERE creator_id = $user_id";
                $retrieve_tasks = mysqli_query($conn, $query);

                while($row = mysqli_fetch_assoc($retrieve_tasks)) {
                $task_name = $row["task_name"];
                $priority = $row["priority"];
                $status = $row["task_status"];

                    if($status === "Complete") {
                        echo "
                        <li><strike>$task_name</strike></li>
                        ";
                    }

                }
            
            ?>
                
            </ul>
        </div>
    </div>


    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="js/index.js"></script>
</body>
</html>