<?php

//CHECK QUERIES FOR ERRORS AND DISPLAY THEM
function errorChecker($query) {
    global $conn;

    if(!$query) {
        die("QUERY FAILED: " . mysqli_error($conn));
    }
}

function signup() {
    global $conn;

    //CAN ONLY SIGNUP IF THIS VAR REMAINS TRUE
    $can_signup = true;

    if(isset($_POST["submit"])) {

        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $user_password = mysqli_real_escape_string($conn, $_POST["user_password"]);
        $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);

        //GET SALT FROM DB FOR crypt()
        $query = "SELECT randSalt FROM users";
        $get_randSalt_query = mysqli_query($conn, $query);

        errorChecker($get_randSalt_query);

       

        //GET SALT FROM THE DB CONTINUED
        $saltRow = mysqli_fetch_array($get_randSalt_query);
        $salt = $saltRow["randSalt"];

        //CHECK IF PWD IS LONG ENOUGH
        if(strlen($user_password) < 5) {

            echo "<p class='signup-error'>Password needs to be longer than 4 characters.</p>";

            $can_signup = false;

        }

        //ENCRYPTED PWD USING SALT FROM DB
        $user_password = crypt($user_password, $salt);
        $confirm_password = crypt($confirm_password, $salt);

        
        // FORM VALIDATION
        if(empty($username) || empty($user_password || $confirm_password)) {

            echo "<p class='signup-error'>Account creation failed. Please fill all fields.</p>";

            $can_signup = false;

        } elseif (strlen($username) < 5) { 

            echo "<p class='signup-error'>Username needs to be longer than 4 characters.</p>";

            $can_signup = false;

        } elseif ($user_password != $confirm_password) {

            echo "<p class='signup-error'>Account creation failed. Passwords don't match.</p>";

            $can_signup = false;

        } else {

            $query = "SELECT * FROM users ";
            $get_all_users_query = mysqli_query($conn, $query);
            errorChecker($get_all_users_query);
    
            while($row = mysqli_fetch_array($get_all_users_query)) {
    
                $db_usernames = $row["username"];
    
                if($username === $db_usernames) {

                    echo "<p class='signup-error'>User already exists. Try another username</p>"; 

                    $can_signup = false;
    
                } 
                
            }
            
        }
        
        if($can_signup) {
            //CREATE ACCOUNT
            $query = "INSERT INTO users (username, user_password) ";
            $query .= "VALUES('$username', '$user_password') ";

            $signup_query = mysqli_query($conn, $query);

            errorChecker($signup_query);

            // echo "Account created";

            header("Location: login.php");
        }

    }        
        
}   

function login() {
    global $conn;

    $user_exists = false;

    if(isset($_POST["submit"])) {

        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $user_password = mysqli_real_escape_string($conn, $_POST["user_password"]);

        $query = "SELECT * FROM users ";
        $get_all_users_query = mysqli_query($conn, $query);
        errorChecker($get_all_users_query);

        while($row = mysqli_fetch_array($get_all_users_query)) {

            $db_usernames = $row["username"];
            
            if($username === $db_usernames) {

                $user_exists = true;

                
                $query = "SELECT * FROM users WHERE username = '$username' ";
                $find_user_query = mysqli_query($conn, $query);
        
                errorChecker($find_user_query);
        
                while($row = mysqli_fetch_array($find_user_query)) {
        
                  $db_user_id = $row["user_id"];
                  $db_username = $row["username"];
                  $db_user_password = $row["user_password"];
        
                }
        
                // ENSURE THAT THE INPUT PWD MATCHES THE PWD IN THE DB BY RUNNING IT THROUGH crypt()
                $user_password = crypt($user_password, $db_user_password);
        
                // PWD VALIDATION
                if($user_password != $db_user_password) {
                    echo "<p class='login-error'>Incorrect password</p>";
                }
        
                // IF THE LOGIN CREDENTIALS ARE APPROVED GRAB THE APPROPRIATE INFO AND STORE THEM INTO SESSION VARIABLES 
                if($username === $db_username && $user_password === $db_user_password) {
        
                  $_SESSION["username"] = $db_username;
                  $_SESSION["user_id"] = $db_user_id;
        
                  header("Location: index.php");
        
                }
                
            } 
        }

        if(!$user_exists) {

            echo "<p class='signup-error'>User Doesn't exists. Try signing up.</p>"; 

        }

    }
}

function createTask() {
    global $conn;
    if(isset($_POST['submit'])) {

        $user_id = $_SESSION["user_id"];

        $task_name = mysqli_real_escape_string($conn, $_POST["task_name"]);
        $priority = mysqli_real_escape_string($conn, $_POST["priority"]);

        if($task_name == "" || empty($task_name)) {

            echo "<h4>This field should not be empty.</h4>";

        } else {

            $query = "INSERT INTO tasks(task_name, priority, creator_id) ";
            $query .= "VALUE('$task_name', '$priority', $user_id) ";

            $add_task_query = mysqli_query($conn, $query);

            errorChecker($add_task_query);
            
            header("Location: index.php");

        }

    }
}

function editTask() {
    global $conn;

    if(isset($_GET["edit"])) {
        $id = $_GET["edit"];

        $query = "SELECT * FROM tasks WHERE id = $id";
        $select_task = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($select_task)) {
            $id = $row["id"];
            $task_name = $row["task_name"];
            
            echo "
                <form action='' method='post'>
                    <div>
                        <label for='task_name'>TASK</label><br>"; ?>
                        <input value="<?php echo $task_name; ?>"" type="text" name="task_name">
                        <?php echo "</div>
                    <div class=''>
                        <label for='priority'>PRIORITY</label><br>
                        <select name='priority'>
                        <option value='regular'>Regular Priority</option>
                        <option value='high'>High Priority</option>
                        </select>
                    </div>
                    <button type='submit' name='update' class='btn submit-task-btn'>Update Task</button>
                </form>
            ";
        }
    }

    if(isset($_POST["update"])) {

        $task_name = mysqli_real_escape_string($conn, $_POST["task_name"]);
        $priority = mysqli_real_escape_string($conn, $_POST["priority"]);

        $query = "UPDATE tasks SET task_name = '$task_name', ";
        $query .= "priority = '$priority' WHERE  $id = id";
        $update_query = mysqli_query($conn, $query);

        errorChecker($update_query);

        header("Location: index.php");

    }
}

function deleteTask() {
    global $conn;

    $query = "SELECT * FROM tasks";
    $retrieve_tasks = mysqli_query($conn, $query);

    if(isset($_GET["delete"])) {
        $task_id = $_GET["delete"];

        $query = "DELETE FROM tasks WHERE id = $task_id ";
        $delete_query = mysqli_query($conn, $query);

        header("Location: index.php");

    }
}

function clearAllComplete() {
    global $conn;

    if(isset($_GET["clear_all"])) {

        $query = "DELETE FROM tasks WHERE task_status = 'Complete'";
        $clear_all_complete = mysqli_query($conn, $query);
    
        header("Location: index.php");
        
    }
}

function completeTask() {
    global $conn;

    if(isset($_GET["complete"])) {
        $id = $_GET["complete"];

        $query = "UPDATE tasks SET task_status = 'Complete' WHERE id = $id";
        $complete_task_query = mysqli_query($conn, $query);

        errorChecker($complete_task_query);

        header("Location: index.php");

        
    }
}